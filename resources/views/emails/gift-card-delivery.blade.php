<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your SteamZilla Gift Card</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #45A247; padding: 20px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">SteamZilla Gift Card</h1>
    </div>
    
    <div style="background-color: #f9f9f9; padding: 30px; border: 2px solid #45A247; border-top: none; border-radius: 0 0 10px 10px;">
        <h2 style="color: #45A247; margin-top: 0;">You've Received a Gift Card!</h2>
        
        @if($giftCard->sender_name)
        <p>Hi {{ $giftCard->recipient_name ?? 'there' }},</p>
        <p><strong>{{ $giftCard->sender_name }}</strong> has sent you a SteamZilla gift card!</p>
        @else
        <p>Hi there,</p>
        <p>You've received a SteamZilla gift card!</p>
        @endif

        @if($giftCard->message)
        <div style="background-color: white; padding: 15px; border-left: 4px solid #45A247; margin: 20px 0;">
            <p style="margin: 0; font-style: italic;">"{{ $giftCard->message }}"</p>
        </div>
        @endif

        <div style="background-color: white; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;">
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #666;">Gift Card Number</p>
            <p style="margin: 0 0 20px 0; font-size: 24px; font-weight: bold; font-family: monospace; color: #45A247;">{{ $giftCard->gift_card_number }}</p>
            
            @if($giftCard->pin)
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #666;">PIN</p>
            <p style="margin: 0 0 20px 0; font-size: 20px; font-weight: bold; font-family: monospace; color: #333;">{{ $giftCard->pin }}</p>
            @endif
            
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #666;">Card Value</p>
            <p style="margin: 0; font-size: 32px; font-weight: bold; color: #45A247;">${{ number_format($giftCard->amount, 2) }}</p>
        </div>

        <div style="margin: 30px 0;">
            <a href="{{ route('gift-cards') }}" style="display: inline-block; background-color: #45A247; color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold;">Use Your Gift Card</a>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
            <p><strong>Important Information:</strong></p>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>This gift card can be used for any SteamZilla service</li>
                <li>Gift cards never expire</li>
                @if($giftCard->expires_at)
                <li>Valid until {{ $giftCard->expires_at->format('F d, Y') }}</li>
                @endif
                <li>Visit <a href="{{ route('gift-cards') }}" style="color: #45A247;">steamzilla.com/gift-cards</a> to check your balance</li>
            </ul>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #666;">
        <p>© {{ date('Y') }} SteamZilla. All rights reserved.</p>
    </div>
</body>
</html>

