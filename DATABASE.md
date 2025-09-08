# 📊 DENR Travel Order Management System - Database Documentation

## 📋 Table of Contents
- [Database Overview](#-database-overview)
- [Schema Design](#-schema-design)
- [Tables Reference](#-tables-reference)
- [Relationships](#-relationships)
- [Indexes](#-indexes)
- [Data Retention & Auditing](#-data-retention--auditing)
- [Security Considerations](#-security-considerations)

## 🌐 Database Overview

The DENR Travel Order Management System uses a relational database with a well-structured schema designed to efficiently manage travel orders, employee information, and approval workflows. The database follows normalization principles to minimize redundancy while maintaining data integrity.

## 🏗️ Schema Design

### Core Entities
- **Users & Authentication** (`users` table)
- **Employee Management** (`employees`, `emp_status`)
- **Travel Order Processing** (`travel_orders`, `travel_order_status`)
- **Approval Workflow** (`travel_order_roles`, `employee_signatures`)
- **Document Management** (`travel_order_numbers`)

## 📑 Tables Reference

### 1. `users` - User Accounts
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| email | string | Unique email address |
| email_verified_at | timestamp | Email verification timestamp |
| password | string | Hashed password |
| is_admin | boolean | Admin privileges flag |
| remember_token | string | "Remember me" token |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Last update time |

### 2. `emp_status` - Employment Status
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string | Status name (e.g., 'Permanent', 'Contractor') |
| desc | string | Status description |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Last update time |

### 3. `employees` - Employee Records
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| first_name | string | Employee's first name |
| middle_name | string | Middle name (optional) |
| last_name | string | Last name |
| suffix | string | Name suffix (e.g., 'Jr.', 'III') |
| sex | string | Gender |
| email | string | Work email (unique) |
| emp_status | string | Employment status |
| position_name | string | Job position |
| assignment_name | string | Department/Unit assignment |
| div_sec_unit | string | Division/Section/Unit |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Last update time |

### 4. `travel_order_roles` - Role Definitions
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string | Role name (unique) |
| description | text | Role description |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Last update time |

### 5. `travel_order_status` - Order Status
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string | Status name (unique) |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Last update time |

### 6. `travel_orders` - Travel Orders
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| employee_email | string | Employee's email (FK to users.email) |
| employee_salary | decimal(10,2) | Employee's salary |
| destination | string | Travel destination |
| purpose | text | Purpose of travel |
| departure_date | date | Departure date |
| arrival_date | date | Expected return date |
| recommender | string | Recommender's email (FK to users.email) |
| approver | string | Approver's email (FK to users.email) |
| appropriation | string | Budget appropriation |
| per_diem | decimal(10,2) | Daily allowance |
| laborer_assistant | decimal(10,0) | Labor/assistant costs |
| remarks | text | Additional notes |
| status_id | bigint | Current status (FK to travel_order_status.id) |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Last update time |

### 7. `employee_signatures` - Digital Signatures
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| employee_id | bigint | FK to employees.id |
| signature_data | text | Base64-encoded signature |
| signature_path | string | Path to signature file |
| mime_type | string | MIME type (default: 'image/png') |
| is_active | boolean | Active signature flag |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Last update time |

### 8. `travel_order_numbers` - Order Numbers
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| travel_order_number | string | Unique order number |
| travel_order_id | bigint | FK to travel_orders.id |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Last update time |

## 🔗 Relationships

### One-to-Many
- **users** → **travel_orders** (via employee_email, recommender, approver)
- **travel_order_status** → **travel_orders**
- **travel_orders** → **travel_order_numbers**

### One-to-One
- **employees** → **employee_signatures**

## 🔍 Indexes

### Primary Keys
- All tables have an auto-incrementing `id` primary key

### Foreign Keys
- `travel_orders.employee_email` → `users.email`
- `travel_orders.status_id` → `travel_order_status.id`
- `travel_orders.recommender` → `users.email`
- `travel_orders.approver` → `users.email`
- `employee_signatures.employee_id` → `employees.id`
- `travel_order_numbers.travel_order_id` → `travel_orders.id`

### Unique Constraints
- `users.email`
- `employees.email`
- `travel_order_roles.name`
- `travel_order_status.name`
- `travel_order_numbers.travel_order_number`

## 📅 Data Retention & Auditing

### Soft Deletes
- The system implements soft deletes for data retention
- Deleted records are marked but not physically removed

### Timestamps
- All tables include:
  - `created_at`: When the record was created
  - `updated_at`: When the record was last modified

## 🔒 Security Considerations

### Data Protection
- Passwords are hashed using bcrypt
- Sensitive data is encrypted at rest
- Database backups are encrypted

### Access Control
- Role-based access control (RBAC) implementation
- Principle of least privilege enforced
- Regular access reviews conducted

### Audit Trails
- All changes to critical data are logged
- Timestamps track creation and modification
- User actions are recorded for accountability

## 📈 Performance Optimization

### Indexing Strategy
- Foreign keys are automatically indexed
- Additional indexes on frequently queried columns
- Composite indexes for common query patterns

### Query Optimization
- Eager loading for related data
- Database query caching enabled
- Regular query performance analysis