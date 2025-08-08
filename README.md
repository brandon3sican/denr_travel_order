# DENR Travel Order Information System (DENR-TOIS)

A comprehensive Travel Order Management System for the Department of Environment and Natural Resources (DENR) built with Laravel 10 and Tailwind CSS 3. This system streamlines the process of creating, managing, and tracking travel orders within the organization.

## 📋 System Workflow

### Travel Order Request Process

1. **Request Creation**
   - Any user can create a travel order request
   - Each request requires:
     - Basic travel details (dates, destination, purpose)
     - Selection of one Recommender
     - Selection of one Approver
   - System validations:
     - Recommender and Approver must be different users
     - User cannot be both Recommender and Approver for the same request
     - User cannot be Recommender/Approver for their own request
   - Status: Set to "Pending/For Recommendation" upon creation

2. **Recommendation Phase**
   - The assigned Recommender receives the travel order
   - Actions available:
     - Review travel order details
     - Attach e-signature to recommend
     - Forward to Approver
   - Status: Changes to "For Approval" after recommendation

3. **Approval Phase**
   - The assigned Approver receives the travel order
   - Actions available:
     - Review travel order and recommendation
     - Attach e-signature to approve
     - System automatically assigns an official travel order number upon approval
   - Status: Changes to "Approved" with O.R. number

### User Roles and Permissions

- **Regular Users**
  - Can create and manage their own travel orders
  - Can be assigned as Recommender or Approver for other users' requests
  - Cannot approve or recommend their own requests

- **Recommenders**
  - Can recommend travel orders assigned to them
  - Must provide e-signature when recommending
  - Cannot recommend their own travel orders

- **Approvers**
  - Can approve travel orders assigned to them
  - Must provide e-signature when approving
  - System assigns official travel order number upon approval
  - Cannot approve their own travel orders

- **Admins**
  - Full system access
  - Can manage users and roles
  - Can override or reassign requests if needed

### Status Flow
1. Pending/For Recommendation → For Approval → Approved (with O.R. number)
2. Can be Rejected at any stage with appropriate reason
3. Special Status: Cancelled (for terminated requests)

## 🚀 Recent Updates

- **Database Seeding**
  - Fixed duplicate email entries in user seeding
  - Enhanced seeders with `updateOrCreate` to prevent duplicates
  - Improved data consistency across related tables

- **User Interface**
  - Updated role management view for better user experience
  - Improved display of user names in the interface
  - Removed redundant modals for cleaner UI

- **Security**
  - Enhanced data validation in seeders
  - Improved foreign key constraints
  - Better error handling for database operations

## ✨ Features

- **Modern Dashboard**
  - Real-time statistics and key metrics
  - Quick overview of pending and completed travel orders
  - Interactive charts and visualizations

- **Advanced Travel Order Management**
  - Intuitive travel order creation wizard
  - Comprehensive view of all travel orders
  - Advanced filtering and search capabilities
  - Status tracking with visual indicators
  - Export functionality for reports

- **Employee Management**
  - Detailed employee profiles with contact information
  - Department and position tracking
  - Employee performance metrics

- **User Management**
  - Role-based access control (Admin, Approver, Employee)
  - User activity logging
  - Password management and security

- **Document Management**
  - Digital document storage
  - Version control for travel orders
  - Document approval workflows

- **Responsive Design**
  - Mobile-first approach
  - Optimized for all screen sizes
  - Touch-friendly interface

- **Real-time Updates**
  - Live status updates
  - Instant notifications
  - Activity feed

## ✅ To-Do List

### 🚀 In Progress

#### Travel Order Creation
- [ ] Implement date validations:
  - Departure date must be at least 1 day after current date
  - Arrival date must be on or after departure date
- [ ] Create navigation for users to view travel orders where they are:
  - Requestor (their own requests)
  - Recommender (assigned to them)
  - Approver (assigned to them)
- [ ] Implement e-signature requirements:
  - Users must upload e-signature before creating requests
  - Store e-signatures securely in personal storage
  - Require e-signature verification for recommendations/approvals
- [ ] Ensure successful data insertion with proper error handling
- [ ] Comprehensive testing and UI/UX improvements

#### Approval Workflow
- [ ] Implement recommendation phase:
  - Recommender receives notification of new travel order
  - System requires e-signature for recommendation
  - Forward to Approver after recommendation
- [ ] Implement approval phase:
  - Approver receives notification of pending approval
  - System requires e-signature for approval
  - Handle rejection workflow with reason

### 📅 Upcoming

- [ ] **Travel Order Number Assignment**
  - Implement automatic generation of travel order numbers
  - Format: TBD (To Be Decided)
  - Ensure uniqueness and sequential numbering

- [ ] **Document Generation**
  - Create travel order document template
  - Implement PDF generation with all details
  - Include e-signatures and approval stamps

- [ ] **Notification System**
  - Email notifications for:
    - New travel order assignment (to Recommender)
    - Recommendation results (to Requestor and Approver)
    - Approval/Rejection results (to Requestor)
  - In-app notifications
  - Status update alerts

- [ ] **Reporting**
  - Generate travel order reports
  - Filter by date range, status, department
  - Export to Excel/PDF

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
