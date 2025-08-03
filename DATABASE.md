# Travel Order System - Database Documentation

## Database Schema

### Core Tables

#### 1. `users` - User Accounts
- **What it does**: Stores login information for all system users
- **Key Fields**:
  - `email` - Unique email address (also used as username)
  - `name` - User's full name
  - `password` - Encrypted password

- **Connections**:
  - Each user has one employee profile
  - Can create multiple travel orders
  - Can be assigned as recommender/approver for travel orders
  - Receives notifications about travel order updates

#### 2. `employees` - Employee Information
- **What it does**: Stores detailed information about each employee
- **Key Fields**:
  - `email` - Links to the user account
  - `first_name`, `last_name` - Employee's full name
  - `position` - Job title/position
  - `department` - Department/office
  - `recommender_email` - Who recommends their travel requests
  - `approver_email` - Who approves their travel requests

- **Connections**:
  - Linked to one user account
  - Can have multiple travel orders
  - Can be assigned as recommender/approver for others

#### 3. `roles` - User Permissions
- **What it does**: Controls what users can do in the system
- **Key Fields**:
  - `name` - Role name (e.g., Admin, Employee, Approver)
  - `description` - What this role can do

#### 4. `travel_order_status` - Request Status
- **What it does**: Shows where a travel order is in the approval process
- **Possible Statuses**:
  - `For Recommendation` - Waiting for recommender's review
  - `For Approval` - Waiting for final approval
  - `Approved` - Travel order is approved
  - `Disapproved` - Travel order is rejected
  - `Cancelled` - Request was cancelled
  - `Completed` - Travel is finished

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
- **Enum**: Used for status fields with fixed options

## Security Considerations

1. Email is used as a foreign key in multiple tables for user identification
2. Sensitive data like passwords are hashed (handled by Laravel's authentication)
3. All database queries should use prepared statements to prevent SQL injection
4. Input validation is implemented at the application level
