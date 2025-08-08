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
- `remember_token` (string, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 2. employees
- `id` (bigint, primary key)
- `user_id` (foreign key to users)
- `first_name` (string)
- `last_name` (string)
- `position_name` (string)
- `assignment_name` (string, nullable)
- `div_sec_unit` (string, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 3. roles
- `id` (bigint, primary key)
- `name` (string, unique)
- `description` (text, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 4. role_user
- `id` (bigint, primary key)
- `user_id` (foreign key to users)
- `role_id` (foreign key to roles)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 5. travel_orders
- `id` (bigint, primary key)
- `user_id` (foreign key to users)
- `recommender_id` (foreign key to users)
- `approver_id` (foreign key to users)
- `status_id` (foreign key to travel_order_statuses)
- `purpose` (text)
- `destination` (string)
- `departure_date` (date)
- `arrival_date` (date)
- `travel_order_number` (string, nullable, unique)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### 6. travel_order_statuses
- `id` (bigint, primary key)
- `name` (string, unique)
- `description` (text, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

## Relationships

1. **User - Employee**: One-to-One
   - A user has one employee record
   - An employee belongs to one user

2. **User - Roles**: Many-to-Many
   - A user can have multiple roles
   - A role can be assigned to multiple users

3. **TravelOrder - User**: Many-to-One
   - A travel order is created by one user
   - A user can create many travel orders

4. **TravelOrder - Status**: Many-to-One
   - A travel order has one status
   - A status can be assigned to many travel orders

## Indexes

- `users.email` (unique)
- `employees.user_id` (unique)
- `travel_orders.travel_order_number` (unique)
- `travel_orders.user_id` (index)
- `travel_orders.status_id` (index)

## Seeding

The database includes seeders for:
- Default roles (Admin, Recommender, Approver, User)
- Initial admin user
- Sample travel order statuses
- Test users for each role
