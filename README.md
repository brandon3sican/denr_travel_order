# DENR Travel Order System

A web-based Travel Order Management System for the Department of Environment and Natural Resources (DENR) built with Laravel and Tailwind CSS.

## Features

- User authentication and role-based access control
- Create and manage travel orders
- Multi-level approval workflow
- Email notifications
- Responsive design for all devices
- Dashboard with travel order statistics

## Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL 5.7+ or MariaDB 10.3+
- Web server (Apache/Nginx)
- Git

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/denr-travel-order-system.git
cd denr-travel-order-system/travel-order
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install NPM Dependencies

```bash
npm install
npm run build
```

### 4. Configure Environment

1. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```

2. Generate application key:
   ```bash
   php artisan key:generate
   ```

3. Update the following variables in the `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=denr_travel_order
   DB_USERNAME=your_db_username
   DB_PASSWORD=your_db_password
   
   MAIL_MAILER=smtp
   MAIL_HOST=your_mail_host
   MAIL_PORT=587
   MAIL_USERNAME=your_mail_username
   MAIL_PASSWORD=your_mail_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=no-reply@denr.gov.ph
   MAIL_FROM_NAME="DENR Travel Order System"
   ```

### 5. Run Database Migrations and Seeders

```bash
php artisan migrate --seed
```

### 6. Set Storage Link

```bash
php artisan storage:link
```

### 7. Start the Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Default Login Credentials

- **Admin User**
  - Email: admin@denr.gov.ph
  - Password: password

- **Regular User**
  - Email: user@denr.gov.ph
  - Password: password

## Project Structure

```
travel-order-system/
├── travel-order/              # Laravel application
│   ├── app/                   # Application code
│   ├── bootstrap/             # Framework bootstrap files
│   ├── config/                # Configuration files
│   ├── database/              # Database migrations and seeders
│   ├── public/                # Publicly accessible files
│   ├── resources/             # Views and assets
│   ├── routes/                # Application routes
│   └── storage/               # Storage for logs, cache, etc.
├── public/                    # Public files for the frontend
│   ├── css/                   # Compiled CSS
│   ├── js/                    # Frontend JavaScript
│   └── images/                # Image assets
└── README.md                  # This file
```

## Development

### Compiling Assets

```bash
npm run dev
```

### Running Tests

```bash
php artisan test
```

## Environment Variables

| Variable | Description |
|----------|-------------|
| `APP_ENV` | Application environment (local, production, etc.) |
| `APP_DEBUG` | Enable/disable debug mode |
| `APP_URL` | Application URL |
| `DB_*` | Database connection settings |
| `MAIL_*` | Email configuration |

## Contributing

1. Fork the repository
2. Create a new branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced under the [MIT License](LICENSE).

## Contact

For any inquiries, please contact the development team at it-support@denr.gov.ph
