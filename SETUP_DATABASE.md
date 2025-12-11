# Database Setup Instructions

## MySQL Configuration

1. **Update your `.env` file** with your MySQL credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=steamzilla
DB_USERNAME=your_mysql_username
DB_PASSWORD=your_mysql_password
```

2. **Create the database** (if it doesn't exist):

```bash
mysql -u your_username -p -e "CREATE DATABASE IF NOT EXISTS steamzilla CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

3. **Run migrations**:

```bash
php artisan migrate
```

4. **Seed the database** with sample data:

```bash
php artisan db:seed
```

## Default Admin Login

After seeding:
- **Email**: admin@steamzilla.com
- **Password**: password

**Important**: Change the password immediately after first login!

## Troubleshooting

If you get "Access denied" errors:
- Verify your MySQL username and password are correct
- Make sure MySQL service is running: `sudo systemctl status mysql`
- Check if the database exists: `mysql -u your_username -p -e "SHOW DATABASES;"`

