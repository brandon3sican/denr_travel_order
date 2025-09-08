# DENR Travel Order Management System

A comprehensive Travel Order Management System for the Department of Environment and Natural Resources (DENR) built with Laravel and Tailwind CSS.

## 🚀 Features

### User Management
- Role-based access control (Admin, Approver, Employee)
- User authentication and authorization
- Profile management
- Digital signature upload and verification

### Travel Order Management
- Create and submit travel orders
- Track order status (Pending, For Approval, Approved, Rejected)
- View travel history
- Filter and search functionality
- Digital approval workflow

### Reporting
- Generate travel order reports
- Filter by date range, status, and department
- Export to PDF/Excel

### Document Management
- Digital document storage
- Version control
- Document approval workflows

## 🗄️ Database Structure

### Tables

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

## 🔧 Development

### Running the development server
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

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 📞 Contact

For any inquiries, please contact the development team.