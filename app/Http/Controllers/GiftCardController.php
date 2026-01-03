<?php

namespace App\Http\Controllers;

use App\Models\GiftCard;
use App\Models\GiftCardTransaction;
use App\Mail\GiftCardDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class GiftCardController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'buy');
        return view('gift-cards', compact('tab'));
    }

    public function buy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:individual,bulk,group',
            'delivery_method' => 'required|in:self,sms,email',
            'send_to_myself' => 'nullable|boolean',
            'recipient_name' => 'required_if:send_to_myself,0|string|max:255',
            'recipient_email' => 'required_if:delivery_method,email|email|max:255',
            'recipient_phone' => 'required_if:delivery_method,sms|string|max:20',
            'delivery_date' => 'required|date',
            'delivery_time' => 'required',
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'required|email|max:255',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('tab', 'buy');
        }

        // Calculate discount
        $pricing = GiftCard::calculateDiscount($request->amount);

        // Store gift card data in session for payment processing
        $giftCardData = [
            'amount' => $request->amount,
            'original_purchase_amount' => $request->amount,
            'discount_applied' => $pricing['discount'],
            'final_paid_amount' => $pricing['final'],
            'sender_name' => $request->sender_name,
            'sender_email' => $request->sender_email,
            'recipient_name' => $request->send_to_myself ? $request->sender_name : $request->recipient_name,
            'recipient_email' => $request->delivery_method === 'email' ? ($request->send_to_myself ? $request->sender_email : $request->recipient_email) : null,
            'recipient_phone' => $request->delivery_method === 'sms' ? ($request->send_to_myself ? null : $request->recipient_phone) : null,
            'delivery_method' => $request->delivery_method,
            'delivery_datetime' => $request->delivery_date . ' ' . $request->delivery_time,
            'message' => $request->message,
        ];

        session(['gift_card_data' => $giftCardData]);

        // Redirect to payment page
        return redirect()->route('gift-cards.payment');
    }

    public function checkBalance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gift_card_number' => 'required|string',
            'pin' => 'nullable|string',
            'no_pin' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('tab', 'check');
        }

        $giftCard = GiftCard::where('gift_card_number', $request->gift_card_number)->first();

        if (!$giftCard) {
            return redirect()->back()
                ->withErrors(['gift_card_number' => 'Gift card not found.'])
                ->withInput()
                ->with('tab', 'check');
        }

        if (!$request->no_pin && $giftCard->pin && $giftCard->pin !== $request->pin) {
            return redirect()->back()
                ->withErrors(['pin' => 'Invalid PIN.'])
                ->withInput()
                ->with('tab', 'check');
        }

        return view('gift-cards-balance', compact('giftCard'));
    }

    public function checkReloadCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gift_card_number' => 'required|string',
            'pin' => 'nullable|string',
            'no_pin' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('tab', 'reload');
        }

        $giftCard = GiftCard::where('gift_card_number', $request->gift_card_number)->first();

        if (!$giftCard) {
            return redirect()->back()
                ->withErrors(['gift_card_number' => 'Gift card not found.'])
                ->withInput()
                ->with('tab', 'reload');
        }

        if (!$request->no_pin && $giftCard->pin && $giftCard->pin !== $request->pin) {
            return redirect()->back()
                ->withErrors(['pin' => 'Invalid PIN.'])
                ->withInput()
                ->with('tab', 'reload');
        }

        // Store validated card in session
        session(['reload_card_id' => $giftCard->id]);
        
        return redirect()->route('gift-cards', ['tab' => 'reload'])
            ->with('reload_card_validated', true)
            ->with('reload_card_number', $giftCard->gift_card_number);
    }

    public function reload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('tab', 'reload');
        }

        $giftCardId = session('reload_card_id');
        if (!$giftCardId) {
            return redirect()->route('gift-cards', ['tab' => 'reload'])
                ->withErrors(['error' => 'Please validate your gift card first.'])
                ->with('tab', 'reload');
        }

        $giftCard = GiftCard::findOrFail($giftCardId);

        // Calculate discount
        $pricing = GiftCard::calculateDiscount($request->amount);

        // Reload card
        $giftCard->amount += $request->amount;
        $giftCard->save();

        // Create transaction
        GiftCardTransaction::create([
            'gift_card_id' => $giftCard->id,
            'type' => 'reload',
            'amount' => $request->amount,
            'discount_amount' => $pricing['discount'],
            'final_paid_amount' => $pricing['final'],
        ]);

        session()->forget('reload_card_id');

        return redirect()->route('gift-cards.success', $giftCard->id)
            ->with('success', 'Gift card reloaded successfully!');
    }

    public function showPaymentPage(Request $request)
    {
        $giftCardData = session('gift_card_data');

        if (empty($giftCardData)) {
            return redirect()->route('gift-cards', ['tab' => 'buy'])
                ->with('error', 'Please complete the gift card form first.');
        }

        return view('gift-cards.payment', [
            'applicationId' => config('services.square.application_id'),
            'locationId' => config('services.square.location_id'),
            'giftCardData' => $giftCardData,
            'environment' => config('services.square.environment', 'sandbox'),
        ]);
    }

    public function success($id)
    {
        $giftCard = GiftCard::with('transactions')->findOrFail($id);
        return view('gift-cards-success', compact('giftCard'));
    }
}
