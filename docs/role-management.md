# Role Management

## Overview

The Role Management system controls user access and permissions within the DENR Travel Order System. It's built on Laravel's built-in authorization system with custom enhancements.

## Roles and Permissions

### Default Roles

1. **Admin**
   - Full system access
   - Can manage all users and roles
   - Can override any travel order
   - Can generate system reports

2. **Recommender**
   - Can recommend travel orders
   - Can view travel orders assigned to them
   - Can attach e-signatures to recommendations
   - Cannot recommend their own travel orders

3. **Approver**
   - Can approve/reject travel orders
   - Can view travel orders assigned to them
   - Can attach e-signatures to approvals
   - Cannot approve their own travel orders
   - Can generate official travel order numbers

4. **User** (Default)
   - Can create and manage their own travel orders
   - Can view their travel order history
   - Can upload e-signatures
   - Can track status of their requests

## Implementation

### Role Assignment

Roles are assigned through the Role Management interface (`/role-management`). Only users with admin privileges can assign or modify roles.

### Authorization Middleware

```php
// Example middleware usage
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin-only routes
});
```

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
