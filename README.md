# DENR Travel Order Information System (DENR-TOIS)

A comprehensive Travel Order Management System for the Department of Environment and Natural Resources (DENR) built with Laravel 10 and Tailwind CSS 3. This system streamlines the process of creating, managing, and tracking travel orders within the organization.

## ✨ Features

- **Modern Dashboard** - Overview of travel orders with key statistics
- **Travel Order Management**
  - Create new travel orders with detailed information
  - View and track existing travel orders
  - Filter and search functionality
- **User Management**
  - Manage user accounts and permissions
  - Role-based access control
  - User activity tracking
- **Responsive Design** - Fully responsive interface for desktop and mobile devices
- **Real-time Updates** - Dynamic UI with real-time status changes
- **Intuitive UI/UX** - Clean and user-friendly interface built with Tailwind CSS

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
git clone https://github.com/brandon3sican/denr_travel_order.git
cd denr_travel_order
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

## 🚀 Running the Project

### Development Environment

1. **Start the Laravel development server**:
   ```bash
   php artisan serve
   ```
   This will start the server at `http://127.0.0.1:8000`

2. **Run Vite development server** (for hot-reloading of assets):
   ```bash
   npm run dev
   ```
   This will start Vite's development server for hot module replacement.

3. **Access the application**:
   - Frontend: http://localhost:8000
   - Vite dev server: http://localhost:5173 (for assets)

### Production Environment

1. **Build assets for production**:
   ```bash
   npm run build
   ```

2. **Optimize the application**:
   ```bash
   php artisan optimize
   php artisan view:cache
   php artisan route:cache
   php artisan config:cache
   ```

3. **Configure your web server**:
   - Point your web server (Apache/Nginx) to the `/public` directory
   - Set the document root to the `public` directory
   - Configure URL rewriting as per Laravel's documentation

4. **Set proper permissions**:
   ```bash
   chmod -R 755 storage
   chmod -R 755 bootstrap/cache
   ```

### Using Laravel Sail (Docker)

If you have Docker installed, you can use Laravel Sail:

1. Install dependencies:
   ```bash
   docker run --rm \
     -u "$(id -u):$(id -g)" \
     -v $(pwd):/var/www/html \
     -w /var/www/html \
     laravelsail/php82-composer:latest \
     composer install --ignore-platform-reqs
   ```

2. Start the Sail environment:
   ```bash
   ./vendor/bin/sail up -d
   ```

3. Run database migrations:
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```

4. Access the application at: http://localhost

## Project Structure

```
resources/views/
├── dashboard/              # Dashboard views
├── layout/                 # Main layout templates
├── travel-order/           # Travel order related views
│   ├── create-travel-order.blade.php
│   └── my-travel-orders.blade.php
└── user-management/        # User management views
```

## Available Routes

- `/` - Login page
- `/dashboard` - Main dashboard
- `/travel-order/create` - Create new travel order
- `/travel-order/my-orders` - View my travel orders
- `/user-management` - User management console

## Development

### Frontend Assets

Compile assets with:
```bash
npm run dev
# or for production
npm run build
```

### Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Environment Variables

| Variable | Description |
|----------|-------------|
| `APP_ENV` | Application environment (local, production, etc.) |
| `APP_DEBUG` | Enable/disable debug mode |
| `APP_URL` | Application URL |
| `DB_*` | Database connection settings |
| `MAIL_*` | Email configuration |

## Default Login Credentials

- **Admin User**
  - Email: admin@denr.gov.ph
  - Password: password

- **Regular User**
  - Email: user@denr.gov.ph
  - Password: password

## License

This project is open-source and available under the [MIT License](LICENSE).

## Contact

For any inquiries, please contact the development team at it-support@denr.gov.ph
