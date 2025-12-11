# SteamZilla - Car Steam Cleaning Service Booking System

A Laravel web application for managing a mobile car steam cleaning service business. This application allows customers to view service packages, book appointments, and allows administrators to manage bookings, packages, and add-on services.

## Features

### Public Features
- **Home Page**: Landing page with value propositions (eco-friendly, water-saving, convenience, etc.)
- **Packages Page**: Display all available service packages with pricing, duration, and features
- **Add-Ons Section**: List of additional services customers can add to their booking
- **Booking Form**: Comprehensive form to collect customer information and service preferences
- **Contact Page**: Company information, contact details, and FAQ section

### Admin Features
- **Admin Dashboard**: Overview of bookings with statistics
- **Booking Management**: View, update status, and manage all customer bookings
- **Package Management**: Create, edit, and delete service packages
- **Add-On Management**: Create, edit, and delete add-on services
- **Authentication**: Secure admin login system

## Requirements

- PHP >= 8.2
- Composer
- MySQL or compatible database
- Node.js and NPM (for frontend assets)

## Installation

1. **Clone the repository** (if applicable) or navigate to the project directory:
   ```bash
   cd SteamZilla
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install frontend dependencies**:
   ```bash
   npm install
   ```

4. **Set up environment file**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database** in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=steamzilla
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations**:
   ```bash
   php artisan migrate
   ```

7. **Seed the database** with sample data:
   ```bash
   php artisan db:seed
   ```

8. **Build frontend assets**:
   ```bash
   npm run build
   ```

9. **Start the development server**:
   ```bash
   php artisan serve
   ```

## Default Admin Credentials

After seeding, you can log in with:
- **Email**: admin@steamzilla.com
- **Password**: password

**Important**: Change the admin password immediately after first login!

## Database Schema

### Tables
- **packages**: Service packages (name, price, duration, features)
- **addons**: Additional services (name, price, description, category)
- **bookings**: Customer bookings (customer info, package, date/time, status)
- **booking_addons**: Pivot table linking bookings to add-ons
- **users**: Admin users for authentication

## Usage

### For Customers
1. Visit the home page to learn about services
2. Browse packages on the Packages page
3. Click "Select Package" or "Book Now" to start booking
4. Fill out the booking form with your details
5. Select add-on services if desired
6. Submit booking and receive confirmation

### For Administrators
1. Log in at `/admin/login`
2. Access the dashboard to view booking statistics
3. Manage bookings: view details, update status (pending, confirmed, completed, cancelled)
4. Manage packages: create, edit, or delete service packages
5. Manage add-ons: create, edit, or delete additional services

## Routes

### Public Routes
- `/` - Home page
- `/packages` - Service packages listing
- `/contact` - Contact information
- `/booking` - Booking form
- `/booking/success/{id}` - Booking confirmation

### Admin Routes (Protected)
- `/admin/login` - Admin login
- `/admin/dashboard` - Admin dashboard
- `/admin/bookings` - Manage bookings
- `/admin/bookings/{id}` - View booking details
- `/admin/packages` - Manage packages
- `/admin/addons` - Manage add-ons

## Customization

### Adding New Packages
1. Log in as admin
2. Navigate to "Manage Packages"
3. Click "Create New Package"
4. Fill in package details, features (one per line), and pricing

### Adding New Add-Ons
1. Log in as admin
2. Navigate to "Manage Add-Ons"
3. Click "Create New Add-On"
4. Fill in add-on details, category, and pricing

### Styling
The application uses inline CSS in the main layout file (`resources/views/layouts/app.blade.php`). You can customize colors, fonts, and styles there.

## Security Features

- CSRF protection on all forms
- Authentication middleware for admin routes
- Password hashing for admin users
- Input validation on all forms
- SQL injection protection via Eloquent ORM

## Future Enhancements

- Email notifications for booking confirmations
- Customer account system for returning customers
- Payment integration
- Calendar view for booking management
- SMS notifications
- API endpoints for mobile app integration
- Image gallery for services
- Customer reviews and ratings

## License

This project is open-sourced software licensed under the MIT license.

## Support

For issues or questions, please contact the development team.
