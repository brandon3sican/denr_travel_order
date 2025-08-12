# Role Management

## Overview

The Role Management system controls user access and permissions within the DENR Travel Order System. It's built on Laravel's built-in authorization system with Spatie's Laravel Permission package for enhanced role and permission management.

## Roles and Permissions

### Default Roles

1. **Super Admin**
   - Full system access
   - Can manage all users, roles, and permissions
   - Can override any travel order status
   - Access to all system settings
   - Can generate comprehensive system reports
   - Can manage system maintenance

2. **Admin**
   - Full access to travel order management
   - Can manage users and their roles
   - Can approve/recommend any travel order
   - Can generate and export reports
   - Cannot modify system settings

3. **Recommender**
   - Can recommend travel orders assigned to them
   - Can view travel orders requiring their recommendation
   - Can attach e-signatures to recommendations
   - Cannot recommend their own travel orders
   - Can view basic reports for their department

4. **Approver**
   - Can approve/reject travel orders
   - Can view travel orders requiring their approval
   - Can attach e-signatures to approvals
   - Cannot approve their own travel orders
   - Can generate official travel order numbers
   - Can view approval statistics

5. **User** (Default)
   - Can create and manage their own travel orders
   - Can view their travel order history
   - Can upload and manage their e-signature
   - Can track status of their requests
   - Can view personal travel statistics
   - Can export their own travel orders

### Permissions

#### Travel Order Permissions
- `view travel orders` - View all travel orders
- `create travel orders` - Create new travel orders
- `edit travel orders` - Modify existing travel orders
- `delete travel orders` - Remove travel orders
- `recommend travel orders` - Recommend travel orders
- `approve travel orders` - Approve travel orders
- `view all travel orders` - View all travel orders in the system
- `export travel orders` - Export travel orders to various formats

#### User Management Permissions
- `view users` - View user list
- `create users` - Add new users
- `edit users` - Modify user details
- `delete users` - Remove users
- `assign roles` - Assign roles to users
- `manage roles` - Create and modify roles

#### System Permissions
- `view reports` - Access to reporting features
- `manage settings` - Modify system settings
- `manage departments` - Manage department structure
- `audit logs` - View system audit logs

## Implementation

### Role Assignment

Roles are managed through the Role Management interface (`/admin/roles`). The interface allows:
- Creating and modifying roles
- Assigning permissions to roles
- Assigning roles to users
- Viewing role assignments

### Authorization

#### Middleware Protection
```php
// Role-based access
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin-only routes
});

// Permission-based access
Route::middleware(['auth', 'permission:approve travel orders'])->group(function () {
    // Routes for users who can approve travel orders
});
```

#### Blade Directives
```blade
@role('admin')
    <!-- Content for admins only -->
@endrole

@can('approve travel orders')
    <!-- Content for users with approval permission -->
@endcan
```

### Best Practices

1. **Least Privilege Principle**
   - Assign only the permissions necessary for a user's role
   - Avoid using the `super admin` role for regular tasks
   
2. **Role Naming**
   - Use clear, descriptive role names
   - Follow a consistent naming convention (e.g., kebab-case)
   
3. **Permission Granularity**
   - Create specific permissions for distinct actions
   - Group related permissions together
   
4. **Audit Trails**
   - Log all role and permission changes
   - Track role assignments and revocations

### Security Considerations

1. **Role Escalation**
   - Validate user permissions on both client and server side
   - Implement proper authorization checks in all controllers
   
2. **Session Management**
   - Invalidate sessions after role changes
   - Implement session timeout for sensitive operations
   
3. **API Protection**
   - Secure API endpoints with proper middleware
   - Validate permissions for all API requests

## Extending the Role System

To add a new role:
1. Create a new role in the database
2. Assign appropriate permissions
3. Update the documentation
4. Test the new role thoroughly

### Example: Adding a New Role

```php
// Create a new role
$role = Role::create(['name' => 'auditor']);

// Assign permissions
$role->givePermissionTo([
    'view travel orders',
    'view reports',
    'audit logs'
]);
```

## Monitoring and Maintenance

1. **Regular Audits**
   - Review role assignments quarterly
   - Remove unused roles and permissions
   
2. **User Training**
   - Train users on role-specific features
   - Document role responsibilities
   
3. **Performance**
   - Monitor query performance for role checks
   - Cache role and permission data when appropriate

### Role Checking in Controllers

```php
// Check if user has a specific role
if ($user->hasRole('admin')) {
    // Admin-specific logic
}

// Check if user has any of the given roles
if ($user->hasAnyRole(['admin', 'approver'])) {
    // Logic for admins or approvers
}
```

### Role Checking in Blade Views

```blade
@role('admin')
    <!-- Content only visible to admins -->
    <a href="/admin/dashboard">Admin Dashboard</a>
@endrole

@hasanyrole('admin|approver')
    <!-- Content visible to both admins and approvers -->
    <a href="/approvals">Approvals</a>
@endhasanyrole
```

## Role Management Interface

The role management interface allows admins to:
- View all users and their roles
- Assign/remove roles from users
- Filter users by role
- Search for specific users

## Custom Permissions

In addition to role-based access, the system also supports granular permissions:

```php
// Define a permission
$permission = Permission::create(['name' => 'approve-travel-orders']);

// Assign permission to a role
$role->givePermissionTo($permission);

// Check permission
if ($user->can('approve-travel-orders')) {
    // User can approve travel orders
}
```

## Best Practices

1. Always use role and permission checks in both controllers and views
2. Follow the principle of least privilege
3. Regularly audit role assignments
4. Document any custom permissions
5. Test authorization thoroughly after changes
