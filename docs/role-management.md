# Role Management

## Overview

The Role Management system controls user access and permissions within the DENR Travel Order System. It's built on Laravel's built-in authorization system with a custom role-based access control (RBAC) implementation.

## Roles and Permissions

### Default Roles

1. **Super Admin**
   - Full system access and configuration
   - Manage all users, roles, and permissions
   - Override any travel order status
   - Access to system settings and maintenance
   - Generate comprehensive reports
   - Manage system backups
   - Configure approval workflows

2. **Admin**
   - Full access to travel order management
   - Manage users and their roles
   - Approve/recommend any travel order
   - Generate and export reports
   - Manage departments and divisions
   - Cannot modify system configurations
   - View system logs

3. **Recommender**
   - Recommend travel orders assigned to them
   - View travel orders requiring recommendation
   - Attach e-signatures to recommendations
   - View recommendation history
   - Cannot recommend their own travel orders
   - View department-specific reports
   - Export recommendation history

4. **Approver**
   - Approve/reject travel orders
   - View travel orders requiring approval
   - Attach e-signatures to approvals
   - Generate official travel order numbers
   - View approval statistics
   - Delegate approval authority (if configured)
   - Cannot approve their own travel orders

5. **User** (Default)
   - Create and manage their own travel orders
   - View their travel order history
   - Upload and manage their digital signature
   - View their signature history
   - Set default signature
   - Delete old signatures
   - Track request status
   - View personal travel statistics
   - Export their travel orders
   - Update profile information

### Permission Groups

#### Travel Order Permissions
- `view-travel-orders` - View travel order lists
- `create-travel-orders` - Create new travel orders
- `edit-travel-orders` - Modify existing travel orders
- `delete-travel-orders` - Remove travel orders
- `recommend-travel-orders` - Recommend travel orders
- `approve-travel-orders` - Approve travel orders
- `view-all-travel-orders` - View all travel orders
- `export-travel-orders` - Export travel order data
- `cancel-travel-orders` - Cancel travel orders
- `manage-travel-order-templates` - Manage travel order templates

#### User Management Permissions
- `view-users` - View user list
- `create-users` - Add new users
- `edit-users` - Modify user details
- `delete-users` - Remove users
- `assign-roles` - Assign roles to users
- `manage-roles` - Create and modify roles
- `view-user-profiles` - View detailed user profiles
- `impersonate-users` - Login as another user

#### Signature Permissions
- `upload_signature` - Upload a new signature
- `delete_signature` - Remove a signature
- `view_signature` - View signatures
- `manage_own_signature` - Manage own signatures
- `manage_all_signatures` - Manage all users' signatures (admin only)
- `use_digital_signature` - Ability to use digital signature on documents
- `require_signature` - Documents require signature for submission

#### System Permissions
- `view-reports` - Access reporting features
- `generate-reports` - Create custom reports
- `manage-settings` - Modify system settings
- `manage-departments` - Manage department structure
- `view-audit-logs` - View system audit logs
- `manage-backups` - Create and manage backups
- `view-system-health` - Monitor system health

## Implementation

### Role Assignment

Roles are managed through the Admin Panel (`/admin`). The interface provides:
- Role creation and modification
- Permission assignment to roles
- User role management
- Role-based dashboard access
- Audit trail for role changes

### Authorization

#### Middleware Protection
```php
// Role-based access
Route::middleware(['auth', 'role:admin,approver'])->group(function () {
    // Routes accessible by admin or approver
});

// Permission-based access
Route::middleware(['auth', 'permission:approve-travel-orders'])->group(function () {
    // Routes for users who can approve travel orders
});

// Multiple permissions
Route::middleware(['auth', 'permission:create-travel-orders|edit-travel-orders'])->group(function () {
    // Routes for users who can create or edit travel orders
});
```

#### Blade Directives

```blade
@role('admin')
    <!-- Content visible only to admin -->
    <div class="admin-actions">
        <!-- Admin actions here -->
    </div>
@endrole

@can('approve-travel-orders')
    <!-- Content visible to users with approval permission -->
    <button>Approve Travel Order</button>
@endcan
```

### Programmatic Checks

```php
// Controller method
public function approve(TravelOrder $travelOrder)
{
    $this->authorize('approve-travel-orders');
    
    // Approval logic here
}

// Service class
if (auth()->user()->can('edit-travel-orders')) {
    // Update travel order
}
```

## Best Practices

1. **Least Privilege**
   - Assign minimum required permissions
   - Use role inheritance where applicable
   - Regularly review and audit permissions

2. **Role Design**
   - Create roles based on job functions
   - Avoid role explosion (too many specific roles)
   - Document role purposes and permissions

3. **Security**
   - Never assign super admin role lightly
   - Implement IP restrictions for sensitive roles
   - Enable two-factor authentication for admin roles
   - Log all permission changes

4. **Maintenance**
   - Document all custom permissions
   - Review role assignments periodically
   - Clean up unused roles and permissions
   - Test role changes in staging first

## Common Tasks

### Creating a New Role
1. Go to Admin Panel > Roles
2. Click "Add New Role"
3. Enter role name and description
4. Assign appropriate permissions
5. Save the role
6. Assign to users as needed

### Modifying Permissions
1. Go to Admin Panel > Roles
2. Select the role to modify
3. Update permission assignments
4. Save changes
5. Notify affected users if needed

### Auditing Access
1. View audit logs in Admin Panel
2. Filter by user, role, or action
3. Review permission changes
4. Generate audit reports as needed
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
