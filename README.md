# DENR Travel Order Management System (DENR-TOIS)

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel)](https://laravel.com/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?logo=tailwind-css)](https://tailwindcss.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)](https://php.net/)

A comprehensive Travel Order Management System for the Department of Environment and Natural Resources (DENR) built with Laravel 12, Tailwind CSS 3, and Alpine.js. This system streamlines the process of creating, managing, and tracking travel orders within the organization with a modern, responsive interface.

## 🚀 Key Features

### Role-Based Access Control
- **Admin**: Full system access, user management, and system configuration
- **Approver**: Review and approve/reject travel orders
- **Employee**: Create and manage personal travel orders

### User Management
- Secure authentication and authorization
- Profile management with digital signatures
- Role-based access control (Admin, Approver, Employee)
- Employee directory with department/unit filtering

### Travel Order Management
- Intuitive travel order creation wizard
- Real-time status tracking (Draft → Pending → For Approval → Approved/Rejected)
- Comprehensive travel history dashboard
- Advanced filtering and search functionality
- Digital approval workflow with notifications
- Automatic travel order number generation

### Dashboard & Reporting
- Real-time statistics and analytics
- Filterable reports by date range, status, and department
- Export to multiple formats (PDF, Excel, CSV)
- Visual charts and data visualization

### Document Management
- Secure digital document storage
- Version control for travel orders
- Document approval workflows with audit trail
- Automatic PDF generation with digital signatures

## 🗄️ Database Structure

### Entity-Relationship Diagram (Mermaid)

```mermaid
erDiagram
    users ||--o{ travel_orders : "has many"
    users ||--o{ recommended_orders : "recommends"
    users ||--o{ approved_orders : "approves"
    employees ||--o| users : "belongs to"
    employees ||--o| employee_signatures : "has one"
    travel_order_status ||--o{ travel_orders : "has many"
    
    users {
        int id PK
        string email
        string password
        boolean is_admin
        timestamp email_verified_at
        string remember_token
        timestamps
    }
    
    employees {
        int id PK
        string first_name
        string middle_name
        string last_name
        string suffix
        string sex
        string email
        string emp_status
        string position_name
        string assignment_name
        string div_sec_unit
        timestamps
    }
    
    travel_order_status {
        int id PK
        string name
        timestamps
    }
    
    travel_orders {
        int id PK
        string employee_email FK
        decimal employee_salary
        string destination
        text purpose
        date departure_date
        date arrival_date
        string recommender FK
        string approver FK
        string appropriation
        decimal per_diem
        decimal laborer_assistant
        text remarks
        int status_id FK
        timestamps
    }
    
    employee_signatures {
        int id PK
        int employee_id FK
        text signature_data
        timestamps
    }
```

### Database Tables

1. **users**
   - id (PK)
   - email (unique)
   - email_verified_at (timestamp)
   - password
   - is_admin (boolean)
   - remember_token
   - timestamps

2. **employees**
   - id (PK)
   - first_name
   - middle_name (nullable)
   - last_name
   - suffix (nullable)
   - sex
   - email (unique)
   - emp_status
   - position_name
   - assignment_name
   - div_sec_unit
   - timestamps

3. **travel_order_status**
   - id (PK)
   - name (unique)
   - timestamps

4. **travel_orders**
   - id (PK)
   - employee_email (FK -> users.email)
   - employee_salary (decimal)
   - destination
   - purpose (text)
   - departure_date (date)
   - arrival_date (date)
   - recommender (FK -> users.email)
   - approver (FK -> users.email)
   - appropriation
   - per_diem (decimal)
   - laborer_assistant (decimal)
   - remarks (nullable)
   - status_id (FK -> travel_order_status.id)
   - timestamps

5. **employee_signatures**
   - id (PK)
   - employee_id (FK -> employees.id)
   - signature_data (text)
   - created_at
   - updated_at

## 🛠️ Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 5.7+ or MariaDB 10.3+
- Web server (Apache/Nginx)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/denr-travel-order.git
   cd denr-travel-order
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install NPM dependencies**
   ```bash
   npm install
   ```

4. **Create environment file**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Configure database**
   Update your `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=denr_travel_order
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

7. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

8. **Link storage**
   ```bash
   php artisan storage:link
   ```

9. **Compile assets**
   ```bash
   npm run build
   ```

10. **Start the development server**
    ```bash
    php artisan serve
    ```

## 🛠 Development Workflow

### Local Development Setup

1. **Start the development server**
   ```bash
   php artisan serve
   ```

2. **Run Vite dev server** (for hot reloading)
   ```bash
   npm run dev
   ```

3. **Run queue worker** (for processing jobs like emails)
   ```bash
   php artisan queue:work
   ```

### Testing

Run the test suite:
```bash
php artisan test
```

### Code Style & Quality

Format code using Laravel Pint:
```bash
./vendor/bin/pint
```

### Database Migrations

Create a new migration:
```bash
php artisan make:migration create_table_name
```

Run migrations:
```bash
php artisan migrate
```

Rollback last migration:
```bash
php artisan migrate:rollback
```
```bash
php artisan serve
npm run dev
```

### Running tests
```bash
php artisan test
```

### Code style
```bash
./vendor/bin/pint
```

## 🚀 Deployment

### Production Build
```bash
npm run build
php artisan optimize
php artisan view:cache
php artisan route:cache
php artisan config:cache
```

### Environment Variables (Production)
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-production-url.com

# Set to true in production
APP_FORCE_HTTPS=true

# Queue connection (recommend database or redis in production)
QUEUE_CONNECTION=database

# Session driver (recommend database or redis in production)
SESSION_DRIVER=database

# Cache driver (recommend redis or memcached in production)
CACHE_DRIVER=redis
```

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 📞 Contact

For any inquiries or support, please contact the development team at [your-email@example.com](mailto:your-email@example.com).

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework For Web Artisans
- [Tailwind CSS](https://tailwindcss.com) - A utility-first CSS framework
- [Alpine.js](https://alpinejs.dev) - A rugged, minimal framework for composing JavaScript behavior
- [Vite](https://vitejs.dev) - Next Generation Frontend Tooling