# 🏢 DENR Travel Order Management System (DENR-TOIS)

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel)](https://laravel.com/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?logo=tailwind-css)](https://tailwindcss.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)](https://php.net/)
[![GitHub last commit](https://img.shields.io/github/last-commit/yourusername/denr-travel-order)](https://github.com/yourusername/denr-travel-order/commits/main)
[![GitHub issues](https://img.shields.io/github/issues/yourusername/denr-travel-order)](https://github.com/yourusername/denr-travel-order/issues)

A modern, efficient, and secure Travel Order Management System developed for the Department of Environment and Natural Resources (DENR). Built with Laravel 12, Tailwind CSS 3, and Alpine.js, this solution streamlines the entire travel order process from creation to approval and archival.

## 📋 Table of Contents
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Database Structure](#-database-structure)
- [API Documentation](#-api-documentation)
- [Testing](#-testing)
- [Deployment](#-deployment)
- [Contributing](#-contributing)
- [License](#-license)

## ✨ Key Features

### 👨‍💼 Role-Based Access Control
- **Administrators**
  - Full system configuration and management
  - User account management and permissions
  - System monitoring and audit logs
  - Database maintenance tools

- **Approvers**
  - Review and validate travel requests
  - Digital approval workflow
  - Budget verification tools
  - Departmental reporting

- **Employees**
  - Intuitive travel order submission
  - Real-time status tracking
  - Document upload and management
  - Travel history and reports

### 📝 Travel Order Management
- **Intelligent Forms**
  - Dynamic form validation
  - Auto-save draft functionality
  - Required field enforcement
  - Contextual help tooltips

- **Workflow Automation**
  - Multi-level approval chains
  - Automated notifications
  - Status change alerts
  - Deadline reminders

- **Document Generation**
  - Professional PDF generation
  - Digital signature support
  - Customizable templates
  - Batch processing

### 📊 Analytics & Reporting
- **Real-time Dashboards**
  - Travel statistics overview
  - Departmental spending
  - Approval turnaround times
  - Employee travel history

- **Advanced Reporting**
  - Custom report builder
  - Export to multiple formats (PDF, Excel, CSV)
  - Scheduled report delivery
  - Data visualization tools

### 🔒 Security & Compliance
- **Data Protection**
  - End-to-end encryption
  - Role-based data access
  - Audit logging
  - Regular security audits

- **Compliance Features**
  - Data retention policies
  - Access control lists
  - Activity monitoring
  - Automated backups

## 🗃️ Database Structure

The system utilizes a well-structured relational database designed for performance, scalability, and data integrity. The database schema follows normalization principles to minimize redundancy while maintaining efficient query performance.

### Core Tables Overview

| Table | Description | Key Fields |
|-------|-------------|------------|
| **users** | System authentication and authorization | id, email, is_admin |
| **employees** | Employee master data | id, email, position_name, div_sec_unit |
| **travel_orders** | Core travel order records | id, employee_email, status_id, dates |
| **travel_order_status** | Status workflow definitions | id, name |
| **employee_signatures** | Digital signature management | id, employee_id, signature_data |
| **travel_order_numbers** | Document numbering system | id, travel_order_number, travel_order_id |

### Entity Relationship Diagram

```mermaid
erDiagram
    users ||--o{ travel_orders : "creates"
    users ||--o{ travel_orders : "recommends"
    users ||--o{ travel_orders : "approves"
    employees ||--o| users : "authenticates"
    employees ||--o| employee_signatures : "has"
    travel_order_status ||--o{ travel_orders : "tracks"
    travel_orders ||--o{ travel_order_numbers : "references"
    
    users {
        bigint id PK
        string email "Unique"
        string password "Hashed"
        boolean is_admin
        timestamp email_verified_at
        timestamps
    }
    
    employees {
        bigint id PK
        string first_name
        string last_name
        string email "Unique"
        string position_name
        string div_sec_unit "Division/Section/Unit"
        timestamps
    }
    
    travel_orders {
        bigint id PK
        string employee_email FK
        bigint status_id FK
        date departure_date
        date arrival_date
        string destination
        decimal per_diem
        string purpose
        timestamps
    }
    
    employee_signatures {
        bigint id PK
        bigint employee_id FK
        text signature_data "Base64 encoded"
        boolean is_active
        timestamps
    }
    
    travel_order_status {
        bigint id PK
        string name "Unique"
        timestamps
    }
    
    travel_order_numbers {
        bigint id PK
        string travel_order_number "Unique"
        bigint travel_order_id FK
        timestamps
    }
```

For comprehensive database documentation including field specifications, relationships, and indexing strategy, please refer to [DATABASE.md](DATABASE.md).

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