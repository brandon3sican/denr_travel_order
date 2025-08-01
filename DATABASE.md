# Travel Order System - Database Documentation

## Database Schema

### Tables

#### 1. `users`
- **Description**: Stores user account information
- **Source**: Laravel's default users table
- **Relationships**:
  - Has many `travel_orders`
  - Has many `approvals` (as recommender or approver)
  - Has many `notifications`

#### 2. `roles`
- **Description**: Defines user roles in the system
- **Fields**:
  - `id` (bigint, primary key)
  - `name` (string, unique)
  - `description` (text, nullable)
  - `created_at` (timestamp)
  - `updated_at` (timestamp)

#### 3. `travel_status`
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

#### 4. `travel_orders`
- **Description**: Main table for travel order requests
- **Fields**:
  - `id` (bigint, primary key)
  - `user_email` (string, foreign key to `users.email`)
  - `destination` (string)
  - `purpose` (text)
  - `departure_date` (date)
  - `arrival_date` (date)
  - `appropriation` (string, nullable)
  - `per_diem` (decimal(10,2), nullable)
  - `laborer_assistant` (string, nullable)
  - `remarks` (text, nullable)
  - `status_id` (bigint, foreign key to `travel_status.id`)
  - `created_at` (timestamp)
  - `updated_at` (timestamp)
- **Relationships**:
  - Belongs to `users` (via `user_email`)
  - Belongs to `travel_status`
  - Has one `approval`
  - Has many `notifications`

#### 5. `approvals`
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

#### 6. `notifications`
- **Description**: System notifications for users
- **Fields**:
  - `id` (bigint, primary key)
  - `user_email` (string, foreign key to `users.email`)
  - `travel_order_id` (bigint, foreign key to `travel_orders.id`)
  - `type` (enum: 'Approved', 'Disapproved', 'Cancelled')
  - `message` (text)
  - `is_read` (boolean, default: false)
  - `created_at` (timestamp)
  - `updated_at` (timestamp)
- **Relationships**:
  - Belongs to `users`
  - Belongs to `travel_orders`

## Entity Relationship Diagram (ERD)

```
+-------------+       +------------------+       +-----------------+
|    users    |       |   travel_orders  |       |  travel_status  |
+-------------+       +------------------+       +-----------------+
| email (PK)  |<------| user_email (FK)  |       | id (PK)         |
| name        |       | status_id (FK)   |------>| name            |
| password    |       | ...              |       | created_at      |
| ...         |       +------------------+       | updated_at      |
+-------------+                 |                +-----------------+
       ^                        |
       |                        |
       |                        |
+-------------+        +------------------+
| approvals  |        |  notifications   |
+-------------+        +------------------+
| travel_order_id (FK)| user_email (FK)  |
| recommender_email (FK)| travel_order_id (FK)|
| approver_email (FK) | ...              |
| ...                 +------------------+
+-------------+
```

## Indexes

### Primary Keys
- All tables have an auto-incrementing `id` as primary key

### Foreign Keys
1. `travel_orders`:
   - `user_email` references `users(email)`
   - `status_id` references `travel_status(id)`
2. `approvals`:
   - `travel_order_id` references `travel_orders(id)`
   - `recommender_email` references `users(email)`
   - `approver_email` references `users(email)`
3. `notifications`:
   - `user_email` references `users(email)`
   - `travel_order_id` references `travel_orders(id)`

## Data Seeding

The database includes seeders for initial data:
- `TravelStatusSeeder`: Populates the `travel_status` table with default status values

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
