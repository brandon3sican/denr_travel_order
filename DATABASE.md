# Travel Order System - Database Documentation

## Database Schema

### Tables

#### 1. `users`
- **Description**: Stores user authentication information
- **Source**: Laravel's default users table
- **Fields**:
  - `id` (bigint, primary key)
  - `name` (string)
  - `email` (string, unique)
  - `email_verified_at` (timestamp, nullable)
  - `password` (string)
  - `remember_token` (string, nullable)
  - `created_at` (timestamp)
  - `updated_at` (timestamp)
- **Relationships**:
  - Has one `employee`
  - Has many `travel_orders`
  - Has many `approvals` (as recommender or approver)
  - Has many `notifications`

#### 2. `employees`
- **Description**: Stores detailed employee information
- **Fields**:
  - `id` (bigint, primary key)
  - `email` (string, unique, foreign key to `users.email`)
  - `first_name` (string)
  - `middle_name` (string, nullable)
  - `last_name` (string)
  - `position` (string, nullable)
  - `department` (string, nullable)
  - `recommender_email` (string, foreign key to `employees.email`)
  - `approver_email` (string, foreign key to `employees.email`)
  - `created_at` (timestamp)
  - `updated_at` (timestamp)
- **Relationships**:
  - Belongs to `users` (via `email`)
  - Has many `travel_orders`
  - Has many `approvals` (as recommender or approver)

#### 3. `roles`
- **Description**: Defines user roles in the system
- **Fields**:
  - `id` (bigint, primary key)
  - `name` (string, unique)
  - `description` (text, nullable)
  - `created_at` (timestamp)
  - `updated_at` (timestamp)

#### 4. `travel_order_status`
- **Description**: Tracks the status of travel orders
- **Fields**:
  - `id` (bigint, primary key)
  - `name` (string, unique)
  - `created_at` (timestamp)
  - `updated_at` (timestamp)
- **Default Statuses**:
  - For Recommendation
  - For Approval
  - Approved
  - Disapproved
  - Cancelled
  - Completed

#### 5. `travel_orders`
- **Description**: Main table for travel order requests
- **Fields**:
  - `id` (bigint, primary key)
  - `employee_email` (string, foreign key to `employees.email`)
  - `destination` (string)
  - `purpose` (text)
  - `departure_date` (date)
  - `arrival_date` (date)
  - `appropriation` (string, nullable)
  - `per_diem` (decimal(10,2), nullable)
  - `laborer_assistant` (string, nullable)
  - `remarks` (text, nullable)
  - `status_id` (bigint, foreign key to `travel_order_status.id`)
  - `created_at` (timestamp)
  - `updated_at` (timestamp)
- **Relationships**:
  - Belongs to `employees` (via `employee_email`)
  - Belongs to `travel_order_status`
  - Has one `approval`
  - Has many `notifications`

#### 6. `approvals`
- **Description**: Tracks the approval workflow for travel orders
- **Fields**:
  - `id` (bigint, primary key)
  - `travel_order_id` (bigint, foreign key to `travel_orders.id`)
  - `recommender_email` (string, foreign key to `users.email`)
  - `approver_email` (string, foreign key to `users.email`)
  - `recommender_status` (enum: 'Pending', 'Approved', 'Disapproved', default: 'Pending')
  - `approver_status` (enum: 'Pending', 'Approved', 'Disapproved', default: 'Pending')
  - `recommender_date` (datetime, nullable)
  - `approver_date` (datetime, nullable)
  - `remarks` (text, nullable)
  - `created_at` (timestamp)
  - `updated_at` (timestamp)
- **Relationships**:
  - Belongs to `travel_orders`
  - Belongs to `users` (as recommender)
  - Belongs to `users` (as approver)

#### 7. `notifications`
- **Description**: System notifications for users
- **Fields**:
  - `id` (bigint, primary key)
  - `user_email` (string, foreign key to `users.email`)
  - `travel_order_id` (bigint, foreign key to `travel_orders.id`)
  - `status_id` (bigint, foreign key to `travel_order_status.id`)
  - `type` (enum: 'Approved', 'Disapproved', 'Cancelled')
  - `message` (text)
  - `is_read` (boolean, default: false)
  - `created_at` (timestamp)
  - `updated_at` (timestamp)
- **Relationships**:
  - Belongs to `users`
  - Belongs to `travel_orders`
  - Belongs to `travel_order_status`

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
       ^             | recommender_email|       | appropriation   |
       |             | approver_email   |       | per_diem        |
       |             | ...             |       | laborer_assistant
       |             +-----------------+       | remarks         |
       |                   ^                   | status_id (FK)  |
       |                   |                   | ...             |
       |             +-----------------+       +-----------------+  +---------------------+
       |             |  approvals      |               ^  ^         |  travel_order_status|
       |             +-----------------+               |  |         +---------------------+
       |             | travel_order_id |<--------------+  +---------| id (PK)             |
       |             | recommender_email|                  |         | name                |
       |             | approver_email  |       +-----------------+  | created_at          |
       |             | rec_status      |       |  notifications  |  | updated_at          |
       |             | appr_status     |       +-----------------+  +---------------------+
       |             | rec_date        |<------| travel_order_id |
       |             | appr_date       |       | user_email (FK) |
       |             | remarks         |       | status_id (FK)  |
       |             | ...             |       | type            |
       |             +-----------------+       | message         |
       |                                       | is_read         |
       |                                       | ...             |
       |                                       +-----------------+
       |
+-----------------+
|      roles     |
+-----------------+
| id (PK)        |
| name           |
| description    |
| created_at     |
| updated_at     |
+-----------------+

Key Relationships:
- users 1:1 employees (users.email = employees.email)
- employees 1:N travel_orders (employees.email = travel_orders.employee_email)
- employees 1:1 recommenders (employees.recommender_email = employees.email)
- employees 1:1 approvers (employees.approver_email = employees.email)
- travel_orders 1:1 approvals (travel_orders.id = approvals.travel_order_id)
- travel_orders 1:N notifications (travel_orders.id = notifications.travel_order_id)
- travel_order_status 1:N travel_orders (travel_order_status.id = travel_orders.status_id)
- travel_order_status 1:N notifications (travel_order_status.id = notifications.status_id)
- users 1:N notifications (users.email = notifications.user_email)
- employees 1:N approvals as recommender (employees.email = approvals.recommender_email)
- employees 1:N approvals as approver (employees.email = approvals.approver_email)
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
- **Enum**: Used for status fields with fixed options

## Security Considerations

1. Email is used as a foreign key in multiple tables for user identification
2. Sensitive data like passwords are hashed (handled by Laravel's authentication)
3. All database queries should use prepared statements to prevent SQL injection
4. Input validation is implemented at the application level
