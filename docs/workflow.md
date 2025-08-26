# Travel Order Workflow

## Overview

This document outlines the complete workflow of a travel order from creation to completion in the DENR Travel Order System. The workflow is designed to be flexible yet structured, ensuring proper authorization and documentation at each stage.

## Workflow States

### Main Statuses
1. **Draft** - Initial state when creating a new travel order
2. **For Recommendation** - Submitted and awaiting recommender's action
3. **For Approval** - Recommended and awaiting approver's action
4. **Approved** - Fully approved with travel order number assigned
5. **Rejected** - Rejected by either recommender or approver
6. **Cancelled** - Cancelled by creator or admin
7. **Completed** - Travel has been completed and documented

### Status Transitions

```mermaid
stateDiagram-v2
    [*] --> Draft
    Draft --> For_Recommendation: Submit
    Draft --> Cancelled: Cancel
    
    For_Recommendation --> For_Approval: Recommend
    For_Recommendation --> Rejected: Reject
    For_Recommendation --> Cancelled: Cancel
    
    For_Approval --> Approved: Approve
    For_Approval --> Rejected: Reject
    For_Approval --> Cancelled: Cancel
    
    Approved --> Completed: Mark as Complete
    
    state if_cancelled <<choice>>
    state if_rejected <<choice>>
    
    Cancelled --> [*]
    Rejected --> [*]
    Completed --> [*]
```

## Detailed Workflow

### 1. Draft Stage
- **Initiated by**: Employee/Creator
- **Actions**:
  - Fill in travel details (destination, purpose, dates, etc.)
  - Add passengers (if any)
  - Upload or create digital signature (if not already done)
  - Upload supporting documents
  - Save as draft or submit for recommendation
- **Required Fields**:
  - Destination
  - Purpose
  - Start/End dates
  - Fund source
  - Recommender
  - Approver
- **Notifications**: None
- **Possible Next States**: For Recommendation, Cancelled

### 2. For Recommendation
- **Actioned by**: Recommender
- **Actions**:
  - Review travel order details
  - Add recommendation notes
  - Attach electronic signature
  - Recommend for approval or reject with reason
- **Required Actions**:
  - Review all details
  - Add recommendation notes (optional)
  - Provide electronic signature
- **Notifications**:
  - Email to recommender when assigned
  - Email to creator when recommended/rejected
- **Possible Next States**: For Approval, Rejected, Cancelled

### 3. For Approval
- **Actioned by**: Approver
- **Actions**:
  - Review travel order and recommendation
  - Add approval notes
  - Attach electronic signature
  - Approve with travel order number or reject with reason
- **Required Actions**:
  - Review all details and recommendation
  - Add approval notes (optional)
  - Provide electronic signature
  - Assign travel order number
- **Notifications**:
  - Email to approver when assigned
  - Email to creator and recommender when approved/rejected
- **Possible Next States**: Approved, Rejected, Cancelled

### 4. Approved
- **Status**: Final approval granted
- **Actions**:
  - View and print travel order
  - Mark as completed after travel
  - Cancel (admin only)
- **Notifications**:
  - Email to creator with approved travel order
  - Email to passengers (if any)
- **Possible Next States**: Completed, Cancelled

### 5. Rejected
- **Status**: Rejected by recommender or approver
- **Actions**:
  - View rejection reason
  - Create new version (if allowed)
  - Contact recommender/approver for clarification
- **Notifications**:
  - Email to creator with rejection reason
  - Email to previous approvers (if applicable)
- **Possible Next States**: Draft (new version), [*]

### 6. Cancelled
- **Status**: Travel order cancelled
- **Actions**:
  - View cancellation details
  - Restore (admin only)
  - Create new version (if needed)
- **Notifications**:
  - Email to all involved parties
- **Possible Next States**: [*]

### 7. Completed
- **Status**: Travel has been completed
- **Actions**:
  - View travel order details
  - Download documents
  - Submit completion report (if required)
- **Notifications**:
  - Email confirmation to creator
  - Notification to HR/Admin
- **Final State**: No further actions

## Workflow Rules

### General Rules
1. Only the creator can modify a draft
2. Once submitted, only recommenders/approvers can change the status
3. All status changes are logged in the history
4. Email notifications are sent for all status changes
5. Electronic signatures are required for recommendations and approvals

### Access Control
- **Creators**: Can view and manage their own travel orders
- **Recommenders**: Can view and act on travel orders assigned to them
- **Approvers**: Can view and approve travel orders in their purview
- **Admins**: Full access to all travel orders and settings

### Data Retention
- Drafts older than 30 days may be automatically archived
- Completed travel orders are retained for 5 years
- All actions are logged for audit purposes
  - Delete (admin only)
- **Who Can Modify**: Admin
- **Notification**: Email to all involved parties

#### 7. Completed
- **Description**: Travel has been completed
- **Allowed Actions**:
  - View details
  - Download/print completion report
  - Reopen (admin only)
- **Who Can Modify**: Admin
- **Notification**: Email to creator and approver

## Detailed Workflow Process

### 1. Travel Order Creation
- **Who**: Any authenticated user with `create travel orders` permission
- **Prerequisites**:
  - Valid user account
  - Complete employee profile
  - E-signature on file (if required)
- **Required Fields**:
  - Travel purpose (min. 20 characters)
  - Destination (with complete address)
  - Start and end dates/times
  - Mode of transportation
  - Estimated expenses (if applicable)
  - Recommender selection
  - Approver selection
- **Validations**:
  - Departure date ≥ current date + 1 business day
  - Arrival date ≥ departure date
  - Required fields completed
  - No date conflicts with existing approved travel
  - Recommender and approver are different and active
- **System Actions**:
  - Generate tracking number
  - Set initial status to 'Draft'
  - Log creation event

### 2. Submission for Recommendation
- **Who**: Travel order creator
- **Actions**:
  - Review all details
  - Submit for recommendation
  - Attach supporting documents (if any)
- **System Validations**:
  - All required fields completed
  - No future-dated edits after submission
  - User has submission quota available (if applicable)
- **System Actions**:
  - Update status to 'For Recommendation'
  - Lock editing of certain fields
  - Send notification to recommender
  - Log submission event

### 3. Recommendation Phase
- **Who**: Assigned Recommender
- **Prerequisites**:
  - `recommend travel orders` permission
  - Active account
  - Not the creator of the travel order
- **Actions**:
  - Review travel order details
  - Verify information accuracy
  - Add recommendation notes (optional)
  - Attach e-signature
  - Recommend for approval or reject with reason
  - Choose to recommend or reject
- **Outcomes**:
  - Recommend: Moves to "For Approval"
  - Reject: Returns to creator with reason

### 4. Approval Phase
- **Who**: Assigned Approver
- **Prerequisites**:
  - `approve travel orders` permission
  - Active account
  - Not the creator or recommender of the travel order
- **Actions**:
  - Review travel order and recommendation
  - Verify all requirements are met
  - Attach e-signature
  - Approve with travel order number or reject with reason
  - Add approval notes (optional)
- **System Actions**:
  - Generate official travel order number
  - Update status to 'Approved'
  - Set approval date and time
  - Lock all fields from further editing
  - Send notification to creator and requester
  - Log approval event
  - Trigger any post-approval workflows

### 5. Travel Completion
- **Who**: Travel Order Creator or Admin
- **Actions**:
  - Submit completion report within 3 business days of return
  - Upload supporting documents (tickets, receipts, etc.)
  - Provide summary of accomplishments
  - Mark travel as completed
- **System Validations**:
  - Travel return date has passed
  - All required documents attached
  - Completion report meets requirements
- **System Actions**:
  - Update status to 'Completed'
  - Calculate actual expenses (if applicable)
  - Update user's travel history
  - Archive travel order and documents
  - Trigger any post-completion workflows
  - Send confirmation to all stakeholders

### 6. Rejection Process
- **Who**: Recommender, Approver, or Admin
- **Valid Reasons for Rejection**:
  - Incomplete information
  - Invalid travel dates
  - Budget constraints
  - Policy violation
  - Other (must specify)
- **Required Actions**:
  - Provide clear reason for rejection
  - Suggest corrections (if applicable)
  - Select appropriate rejection type
- **System Actions**:
  - Update status to 'Rejected'
  - Store rejection reason and details
  - Notify creator with rejection details
  - Allow resubmission with corrections
  - Log rejection event

### 7. Cancellation Process
- **Who**: Creator (before approval), Admin (anytime)
- **Valid Reasons for Cancellation**:
  - Change in travel plans
  - Budget reallocation
  - Emergency situations
  - Policy changes
- **Required Actions**:
  - Select cancellation reason
  - Provide additional details (if required)
  - Confirm cancellation
- **System Actions**:
  - Update status to 'Cancelled'
  - Record cancellation details
  - Notify all involved parties
  - Release any allocated resources
  - Log cancellation event

## Status Transitions

| From State | To State | Action Required | Who Can Perform |
|------------|----------|-----------------|------------------|
| Draft | For Recommendation | Submit | Creator |
| For Recommendation | For Approval | Recommend | Recommender |
| For Recommendation | Rejected | Reject | Recommender |
| Pending | For Approval | Recommend | Recommender |
| Pending | Rejected | Reject | Recommender |
| For Approval | Approved | Approve | Approver |
| For Approval | Rejected | Reject | Approver |
| Any | Cancelled | Cancel | Creator/Admin |
| Approved | Completed | Mark Complete | Creator/Admin |

## E-Signature Flow

1. **User Registration**
   - User must upload e-signature during registration
   - Signature is stored securely
   - Can be updated in profile settings

2. **Recommendation/Approval**
   - System requires e-signature verification
   - Signature is attached to the document
   - Timestamp and user details are recorded

## Notifications

- **Email Notifications**:
  - New travel order assigned (to Recommender)
  - Recommendation made (to Approver)
  - Approval/Rejection (to Creator)
  - Status changes

- **In-App Notifications**:
  - Dashboard alerts
  - Notification bell updates
  - Status change indicators

## Error Handling

- **Validation Errors**:
  - Date validations
  - Required fields
  - Role-based access controls

- **Workflow Errors**:
  - Invalid state transitions
  - Missing signatures
  - Permission issues

## Best Practices

1. Always verify user permissions before state transitions
2. Log all state changes with timestamps and user info
3. Provide clear feedback for invalid actions
4. Maintain audit trail of all actions
5. Validate all inputs before processing
