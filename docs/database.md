# Database Design

## Overview

This document outlines the database schema, relationships, and key tables used in the DENR Travel Order System.

## Database Schema

### Core Tables

#### 1. users
- `id` (bigint, primary key)
- `name` (string)
- `email` (string, unique)
- `email_verified_at` (timestamp, nullable)
- `password` (string)
- `role` (string) - 'admin', 'recommender', 'approver', 'user'
- `is_active` (boolean, default: true)
- `last_login_at` (timestamp, nullable)
- `last_login_ip` (string, nullable)
- `remember_token` (string, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 2. employees
- `id` (bigint, primary key)
- `user_id` (bigint, foreign key to users.id)
- `employee_id` (string, unique)
- `first_name` (string)
- `middle_name` (string, nullable)
- `last_name` (string)
- `suffix` (string, nullable)
- `position_name` (string)
- `department` (string)
- `div_sec_unit` (string)
- `email` (string, unique)
- `phone` (string, nullable)
- `signature_path` (string, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)
- `deleted_at` (timestamp, nullable)

#### 3. travel_orders
- `id` (bigint, primary key)
- `tracking_number` (string, unique)
- `employee_id` (bigint, foreign key to employees.id)
- `purpose` (text)
- `destination` (string)
- `start_date` (date)
- `end_date` (date)
- `fund_source` (string)
- `recommender_id` (bigint, foreign key to users.id, nullable)
- `recommended_at` (timestamp, nullable)
- `recommendation_notes` (text, nullable)
- `recommendation_signature` (text, nullable)
- `approver_id` (bigint, foreign key to users.id, nullable)
- `approved_at` (timestamp, nullable)
- `approval_notes` (text, nullable)
- `approval_signature` (text, nullable)
- `status` (string) - 'draft', 'for_recommendation', 'for_approval', 'approved', 'rejected', 'cancelled', 'completed'
- `rejection_reason` (text, nullable)
- `created_by` (bigint, foreign key to users.id)
- `updated_by` (bigint, foreign key to users.id, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)
- `deleted_at` (timestamp, nullable)

#### 4. travel_order_passengers
- `id` (bigint, primary key)
- `travel_order_id` (bigint, foreign key to travel_orders.id)
- `employee_id` (bigint, foreign key to employees.id)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 5. travel_order_attachments
- `id` (bigint, primary key)
- `travel_order_id` (bigint, foreign key to travel_orders.id)
- `file_name` (string)
- `file_path` (string)
- `file_type` (string)
- `file_size` (integer)
- `uploaded_by` (bigint, foreign key to users.id)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 6. travel_order_histories
- `id` (bigint, primary key)
- `travel_order_id` (bigint, foreign key to travel_orders.id)
- `status_from` (string)
- `status_to` (string)
- `remarks` (text, nullable)
- `action_by` (bigint, foreign key to users.id)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 7. notifications
- `id` (bigint, primary key)
- `type` (string)
- `notifiable_type` (string)
- `notifiable_id` (bigint)
- `data` (json)
- `read_at` (timestamp, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

## Relationships

### User Model
- `hasOne` Employee
- `hasMany` TravelOrder (as creator)
- `hasMany` RecommendedTravelOrders (foreignKey: 'recommender_id')
- `hasMany` ApprovedTravelOrders (foreignKey: 'approver_id')
- `hasMany` TravelOrderHistories
- `morphMany` Notifications

### Employee Model
- `belongsTo` User
- `hasMany` TravelOrders (as passenger)
- `hasMany` TravelOrderPassengers
- `hasMany` Attachments (through TravelOrder)

### TravelOrder Model
- `belongsTo` Employee
- `belongsTo` Recommender (User)
- `belongsTo` Approver (User)
- `belongsTo` Creator (User, foreignKey: 'created_by')
- `hasMany` Passengers (through TravelOrderPassenger)
- `hasMany` Attachments
- `hasMany` Histories
- `hasMany` Notifications

## Indexes

### users
- Primary: `id`
- Unique: `email`
- Index: `role`, `is_active`

### employees
- Primary: `id`
- Unique: `employee_id`, `email`
- Index: `user_id`, `department`, `div_sec_unit`
- Fulltext: `first_name`, `middle_name`, `last_name`

### travel_orders
- Primary: `id`
- Unique: `tracking_number`
- Index: `employee_id`, `recommender_id`, `approver_id`, `status`, `start_date`, `end_date`
- Fulltext: `purpose`, `destination`

## Soft Deletes
- `employees`
- `travel_orders`
- `travel_order_attachments`

## Enums

### Travel Order Status
- `draft` - Initial draft state
- `for_recommendation` - Submitted and awaiting recommendation
- `for_approval` - Recommended and awaiting approval
- `approved` - Fully approved
- `rejected` - Rejected by recommender or approver
- `cancelled` - Cancelled by creator or admin
- `completed` - Travel has been completed

## Indexes

- `users.email` (unique)
- `employees.email` (unique)
- `travel_orders.employee_email` (foreign key)
- `travel_orders.recommender` (foreign key)
- `travel_orders.approver` (foreign key)
- `travel_orders.status_id` (foreign key)
- `user_travel_order_roles.user_email` (foreign key)
- `user_travel_order_roles.travel_order_role_id` (foreign key)
- `user_travel_order_roles` composite unique (user_email, travel_order_role_id)

## Data Types and Conventions

### Field Types
- **Strings**: Used for names, emails, and short text (VARCHAR)
- **Text**: Used for longer content like purposes (TEXT)
- **Decimals**: Used for monetary values (DECIMAL 10,2)
- **Dates**: Used for date fields (DATE)
- **Timestamps**: Used for tracking record creation/updates (TIMESTAMP)
- **Booleans**: Used for flags (TINYINT 1)

### Naming Conventions
- Table names: `snake_case`, plural (e.g., `travel_orders`)
- Foreign keys: `referenced_table_singular` (e.g., `employee_email`)
- Pivot tables: `alphabetical_order` (e.g., `user_travel_order_roles`)
- Timestamps: `created_at`, `updated_at`
- Status fields: `status_id` referencing a status table

## Security Considerations

### Data Validation
- All user inputs are validated before processing
- Email fields are validated for proper format
- Date ranges are validated (arrival date ≥ departure date)
- Foreign key constraints ensure data integrity

### Access Control
- Role-based access control via `travel_order_roles`
- Users can have multiple roles
- Sensitive operations require proper authorization
- All database operations are logged

### Data Protection
- Passwords are hashed using bcrypt
- Sensitive data is not logged
- Database backups are encrypted
- Regular security audits are performed

## Seeding

The database includes seeders for:
- Default roles (Admin, Recommender, Approver, User)
- Initial admin user
- Sample travel order statuses
- Test users for each role
