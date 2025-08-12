# Travel Order System - Database Documentation

## Database Schema

### Core Tables

#### 1. `users` - User Accounts
- **Purpose**: Central authentication and authorization
- **Key Fields**:
  - `id` - Primary key (auto-increment)
  - `email` - Unique email address (used as username)
  - `password` - Encrypted password (bcrypt)
  - `is_admin` - Boolean flag for admin privileges
  - `email_verified_at` - Timestamp for email verification
  - `remember_token` - For "remember me" functionality
  - `created_at`, `updated_at` - Timestamps
  - `is_active` - Boolean flag for account status

- **Relationships**:
  - Has one `employee` profile
  - Has many `travel_orders` (created)
  - Has many `notifications`
  - Belongs to many `roles`
  - Has many `recommendedTravelOrders` (as recommender)
  - Has many `approvedTravelOrders` (as approver)
  - Has many `signatures`

### Core Relationships

1. **Users and Employees**
   - One-to-One: Each User has exactly one Employee profile
   ```
   User 1:1 Employee
   ```

2. **Users and Roles**
   - Many-to-Many: Users can have multiple roles
   ```
   User *:* Role
   ```
   - Managed through `role_user` pivot table

3. **Travel Orders**
   - Created by User (One-to-Many)
   ```
   User 1:* TravelOrder
   ```
   - Recommended by User (One-to-Many, nullable)
   ```
   User 1:* TravelOrder (as recommender)
   ```
   - Approved by User (One-to-Many, nullable)
   ```
   User 1:* TravelOrder (as approver)
   ```

4. **Travel Order Signatures**
   - Each TravelOrder has multiple signatures (One-to-Many)
   ```
   TravelOrder 1:* TravelOrderSignature
   ```
   - Each signature is created by a User (Many-to-One)
   ```
   User 1:* TravelOrderSignature
   ```

5. **Notifications**
   - Each User has many Notifications (One-to-Many)
   ```
   User 1:* Notification
   ```

### Relationship Methods in Models

#### User Model
```php
public function employee()
{
    return $this->hasOne(Employee::class);
}

public function roles()
{
    return $this->belongsToMany(Role::class);
}

public function travelOrders()
{
    return $this->hasMany(TravelOrder::class, 'created_by');
}

public function recommendedTravelOrders()
{
    return $this->hasMany(TravelOrder::class, 'recommended_by');
}

public function approvedTravelOrders()
{
    return $this->hasMany(TravelOrder::class, 'approved_by');
}

public function signatures()
{
    return $this->hasMany(TravelOrderSignature::class, 'signed_by');
}

public function notifications()
{
    return $this->hasMany(Notification::class);
}
```

#### TravelOrder Model
```php
public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function recommender()
{
    return $this->belongsTo(User::class, 'recommended_by');
}

public function approver()
{
    return $this->belongsTo(User::class, 'approved_by');
}

public function signatures()
{
    return $this->hasMany(TravelOrderSignature::class);
}
```

#### Employee Model
```php
public function user()
{
    return $this->belongsTo(User::class);
}

public function travelOrders()
{
    return $this->hasMany(TravelOrder::class, 'created_by', 'user_id');
}
```

#### 2. `employees` - Employee Information
- **Purpose**: Detailed employee profiles
- **Key Fields**:
  - `id` - Primary key (auto-increment)
  - `employee_id` - Official employee ID
  - `first_name` - Employee's first name
  - `middle_name` - Middle name or initial
  - `last_name` - Employee's last name
  - `suffix` - Name suffix (Jr., Sr., III, etc.)
  - `position_name` - Job title/position
  - `salary_grade` - Salary grade level
  - `step` - Salary step
  - `employment_status` - (Permanent, Contractual, Casual, etc.)
  - `division` - Division/Office
  - `section` - Section/Unit
  - `contact_number` - Mobile/Phone number
  - `date_of_birth` - Date of birth
  - `date_hired` - Date hired
  - `address` - Complete address
  - `created_at`, `updated_at` - Timestamps

- **Indexes**:
  - `user_id` (unique, foreign key to users.id)
  - `employee_id` (unique)
  - `last_name` (for searching)
  - `position_name` (for role-based queries)
  - `division`, `section` (for filtering)

#### 3. `roles` - User Permissions
- **Purpose**: Role-based access control
- **Key Fields**:
  - `id` - Primary key
  - `name` - Role name (admin, approver, employee)
  - `guard_name` - Laravel guard name
  - `created_at`, `updated_at` - Timestamps

#### 4. `travel_orders` - Travel Order Records
- **Purpose**: Main travel order information
- **Key Fields**:
  - `id` - Primary key
  - `employee_email` - Foreign key to employees
  - `destination` - Travel destination
  - `purpose` - Purpose of travel
  - `departure_date` - Start date of travel
  - `arrival_date` - End date of travel
  - `status_id` - Current status
  - `recommender` - Recommender's email
  - `approver` - Approver's email
  - `remarks` - Additional notes
  - `created_at`, `updated_at` - Timestamps

#### 5. `travel_order_status` - Status Tracking
- **Purpose**: Track travel order status changes
- **Status Flow**:
  1. `Draft` - Initial draft status
  2. `For Recommendation` - Awaiting recommender
  3. `For Approval` - Awaiting approver
  4. `Approved` - Travel approved
  5. `Disapproved` - Travel rejected
  6. `Cancelled` - Travel cancelled
  7. `Completed` - Travel completed

#### 6. `notifications` - System Notifications
- **Purpose**: Track system notifications
- **Key Fields**:
  - `id` - Primary key
  - `type` - Notification class
  - `notifiable_type` - Related model
  - `notifiable_id` - Related model ID
  - `data` - JSON data
  - `read_at` - When read
  - `created_at`, `updated_at` - Timestamps

#### 5. `travel_orders` - Travel Requests
- **What it does**: Stores all travel order details
- **Key Information**:
  - `employee_email` - Who is traveling
  - `destination` - Where they're going
  - `purpose` - Why they're traveling
  - `departure_date`/`arrival_date` - Travel dates
  - `status_id` - Current status of the request
  - `per_diem` - Daily allowance amount
  - `laborer_assistant` - If bringing support staff

- **Connections**:
  - Linked to one employee (traveler)
  - Has one approval record
  - Can have multiple status updates

#### 6. `approvals` - Approval Tracking
- **What it does**: Manages who needs to approve each travel request
- **Key Information**:
  - `recommender_email` - First person to review
  - `approver_email` - Final decision maker
  - `recommender_status` - Recommender's decision
  - `approver_status` - Approver's decision
  - `remarks` - Any notes about the decision

- **How it works**:
  - Each travel order gets one approval record
  - Tracks both recommender and approver decisions
  - Records when each decision was made

#### 7. `notifications` - System Alerts
- **What it does**: Keeps users updated about their travel orders
- **Key Information**:
  - `user_email` - Who gets notified
  - `travel_order_id` - Which request it's about
  - `type` - What happened (approved, rejected, etc.)
  - `message` - Details about the update
  - `is_read` - Whether the user has seen it

- **When sent**:
  - When a travel order status changes
  - When action is required (e.g., needs approval)
  - When there's an important update

## Entity Relationship Diagram (ERD)

```
+-------------+       +------------------+       +-----------------+
|   users     |       |   employees      |       |  travel_orders  |
+-------------+       +------------------+       +-----------------+
| email (PK)  |<------| email (FK)       |<------| employee_email  |
| name        |       | first_name      |       | destination     |
| password    |       | last_name       |       | purpose         |
| ...         |       | position        |       | departure_date  |
+-------------+       | department      |       | arrival_date    |
       ^             | ...             |       | appropriation   |
       |             +-----------------+       | per_diem        |
       |                   ^                   | laborer_assistant
       |                   |                   | remarks         |
       |                   |                   | status_id (FK)  |
       |             +-----------------+       | ...             |
       |             |  approvals      |       +-----------------+  +---------------------+
       |             +-----------------+               ^  ^         |  travel_order_status|
       |             | travel_order_id |<--------------+  |         +---------------------+
       |             | recommender_email|                  +---------| id (PK)             |
       |             | approver_email  |                  |         | name                |
       |             | rec_status      |       +-----------------+  | created_at          |
       |             | appr_status     |       |  notifications  |  | updated_at          |
       |             | rec_date        |<------| travel_order_id |  +---------------------+
       |             | appr_date       |       | user_email (FK) |
       |             | remarks         |       | status_id (FK)  |
       |             | ...             |       | type            |
       |             +-----------------+       | message         |
       |                   ^                   | is_read         |
       |                   |                   | ...             |
       |                   |                   +-----------------+
       |                   |
       |             +-----------------+
       |             |      roles     |
       |             +-----------------+
       +-------------| id (PK)        |
                     | name           |
                     | description    |
                     | created_at     |
                     | updated_at     |
                     +-----------------+

Key Relationships:
- users 1:1 employees (users.email = employees.email)
- users 1:N travel_orders (users.email = travel_orders.employee_email)
- travel_orders 1:1 approvals (travel_orders.id = approvals.travel_order_id)
- users 1:N approvals as recommender (users.email = approvals.recommender_email)
- users 1:N approvals as approver (users.email = approvals.approver_email)
- travel_orders 1:N notifications (travel_orders.id = notifications.travel_order_id)
- travel_order_status 1:N travel_orders (travel_order_status.id = travel_orders.status_id)
- travel_order_status 1:N notifications (travel_order_status.id = notifications.status_id)
- users 1:N notifications (users.email = notifications.user_email)
```

## Indexes

### Primary Keys
- All tables have an auto-incrementing `id` as primary key

### Foreign Keys
1. `employees`:
   - `email` references `users(email)`
   - `recommender_email` references `employees(email)`
   - `approver_email` references `employees(email)`
2. `travel_orders`:
   - `employee_email` references `employees(email)`
   - `status_id` references `travel_order_status(id)`
3. `approvals`:
   - `travel_order_id` references `travel_orders(id)`
   - `recommender_email` references `users(email)`
   - `approver_email` references `users(email)`
4. `notifications`:
   - `user_email` references `users(email)`
   - `travel_order_id` references `travel_orders(id)`
   - `status_id` references `travel_order_status(id)`

## Data Seeding

The database includes seeders for initial data:
- `TravelOrderStatusSeeder`: Populates the `travel_order_status` table with default status values
- `DatabaseSeeder`: Main seeder that calls other seeders in order

## Migration Notes

1. All tables include `created_at` and `updated_at` timestamps
2. Foreign key constraints are set to `onDelete('cascade')` for related records
3. String fields use appropriate lengths based on their content
4. Nullable fields are used for optional information
5. Enums are used for fields with a fixed set of possible values

## Data Types

- **Strings**: Used for names, emails, and short text
- **Text**: Used for longer content like purposes and remarks
- **Date/Datetime**: Used for tracking dates and times
- **Decimal**: Used for monetary values like per diem
- **Boolean**: Used for flags like `is_read` in notifications

## Database Relationships

### Core Relationships

1. **Users and Employees**
   - One-to-One: Each User has exactly one Employee profile
   ```
   User 1:1 Employee
   ```

2. **Users and Roles**
   - Many-to-Many: Users can have multiple roles
   ```
   User *:* Role
   ```
   - Managed through `role_user` pivot table

3. **Travel Orders**
   - Created by User (One-to-Many)
   ```
   User 1:* TravelOrder
   ```
   - Recommended by User (One-to-Many, nullable)
   ```
   User 1:* TravelOrder (as recommender)
   ```
   - Approved by User (One-to-Many, nullable)
   ```
   User 1:* TravelOrder (as approver)
   ```

4. **Travel Order Signatures**
   - Each TravelOrder has multiple signatures (One-to-Many)
   ```
   TravelOrder 1:* TravelOrderSignature
   ```
   - Each signature is created by a User (Many-to-One)
   ```
   User 1:* TravelOrderSignature
   ```

5. **Notifications**
   - Each User has many Notifications (One-to-Many)
   ```
   User 1:* Notification
   ```

### Relationship Methods in Models

#### User Model
```php
public function employee()
{
    return $this->hasOne(Employee::class);
}

public function roles()
{
    return $this->belongsToMany(Role::class);
}

public function travelOrders()
{
    return $this->hasMany(TravelOrder::class, 'created_by');
}

public function recommendedTravelOrders()
{
    return $this->hasMany(TravelOrder::class, 'recommended_by');
}

public function approvedTravelOrders()
{
    return $this->hasMany(TravelOrder::class, 'approved_by');
}

public function signatures()
{
    return $this->hasMany(TravelOrderSignature::class, 'signed_by');
}

public function notifications()
{
    return $this->hasMany(Notification::class);
}
```

#### TravelOrder Model
```php
public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function recommender()
{
    return $this->belongsTo(User::class, 'recommended_by');
}

public function approver()
{
    return $this->belongsTo(User::class, 'approved_by');
}

public function signatures()
{
    return $this->hasMany(TravelOrderSignature::class);
}
```

#### Employee Model
```php
public function user()
{
    return $this->belongsTo(User::class);
}

public function travelOrders()
{
    return $this->hasMany(TravelOrder::class, 'created_by', 'user_id');
}
```

## Security Considerations

1. **Authentication & Authorization**
   - Implemented using Laravel's built-in authentication
   - Role-based access control (RBAC)
   - Policy-based authorization for resources

2. **Data Protection**
   - Passwords hashed using bcrypt
   - Sensitive fields encrypted using Laravel's encrypter
   - CSRF protection enabled
   - XSS prevention with output escaping

3. **Audit Trail**
   - All significant actions are logged
   - Tracks who did what and when
   - Includes IP addresses and timestamps

4. **API Security**
   - Token-based authentication
   - Rate limiting on authentication endpoints
   - CORS configuration for API routes

## Performance Optimization

1. **Query Optimization**:
   - Eager loading for relationships
   - Query caching for frequently accessed data
   - Database query logging in development

2. **Database Maintenance**:
   - Regular optimization of tables
   - Index maintenance
   - Regular backups

## Migration Guide

To update the database schema:

```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Refresh database and re-run all migrations
php artisan migrate:refresh
```

## Seeding Test Data

To populate the database with test data:

```bash
php artisan db:seed
```

Or seed specific seeders:

```bash
php artisan db:seed --class=UsersTableSeeder
php artisan db:seed --class=EmployeesTableSeeder
php artisan db:seed --class=TravelOrdersTableSeeder
