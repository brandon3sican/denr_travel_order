# Database Design

## Overview

This document outlines the database schema, relationships, and key tables used in the DENR Travel Order System.

## Database Schema

### Core Tables

#### 1. users
- `id` (bigint, primary key)
- `email` (string, unique)
- `email_verified_at` (timestamp, nullable)
- `password` (string)
- `is_admin` (boolean, default: false)
- `remember_token` (string, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 2. emp_status
- `id` (bigint, primary key)
- `name` (string)
- `desc` (string, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 3. employees
- `id` (bigint, primary key)
- `first_name` (string)
- `middle_name` (string, nullable)
- `last_name` (string)
- `suffix` (string, nullable)
- `sex` (string)
- `email` (string, unique)
- `emp_status` (string)
- `position_name` (string)
- `assignment_name` (string)
- `div_sec_unit` (string)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 4. travel_order_roles
- `id` (bigint, primary key)
- `name` (string, unique)
- `description` (text, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 5. travel_order_status
- `id` (bigint, primary key)
- `name` (string, unique)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 6. travel_orders
- `id` (bigint, primary key)
- `employee_email` (string, foreign key to users.email)
- `employee_salary` (decimal 10,2)
- `destination` (string)
- `purpose` (text)
- `departure_date` (date)
- `arrival_date` (date)
- `recommender` (string, foreign key to users.email)
- `approver` (string, foreign key to users.email)
- `appropriation` (string)
- `per_diem` (decimal 10,2)
- `laborer_assistant` (decimal 10,0)
- `remarks` (string)
- `status_id` (unsigned bigint, foreign key to travel_order_status.id)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 7. user_travel_order_roles
- `id` (bigint, primary key)
- `user_email` (string, foreign key to users.email)
- `travel_order_role_id` (unsigned bigint, foreign key to travel_order_roles.id)
- `created_at` (timestamp)
- `updated_at` (timestamp)

## Relationships

### Users
- `hasMany` TravelOrder (as employee_email)
- `hasMany` TravelOrder (as recommender)
- `hasMany` TravelOrder (as approver)
- `belongsToMany` TravelOrderRole (through user_travel_order_roles)

### Employees
- `belongsTo` User (via email)

### Travel Orders
- `belongsTo` User (as employee_email)
- `belongsTo` User (as recommender)
- `belongsTo` User (as approver)
- `belongsTo` TravelOrderStatus (as status_id)

### User Travel Order Roles
- `belongsTo` User (as user_email)
- `belongsTo` TravelOrderRole

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
