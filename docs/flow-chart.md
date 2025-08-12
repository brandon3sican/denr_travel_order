# Travel Order Management System - Workflow Documentation

## 1. Complete Workflow Diagram

```mermaid
flowchart TD
    %% Main Flow
    A[Start: Create Travel Order Request] --> B[Save as Draft]
    B --> C{User Action}
    
    C -->|Continue Editing| B
    C -->|Submit| D[Status: For Recommendation]
    
    %% Recommendation Phase
    D --> E{Recommender Reviews}
    E -->|Disapprove| F[Status: Disapproved]
    F -->|Notify User| B
    
    E -->|Recommend| G[Status: For Approval]
    
    %% Approval Phase
    G --> H{Approver Reviews}
    H -->|Disapprove| I[Status: Disapproved]
    I -->|Notify User| B
    
    H -->|Approve| J[Generate Official Travel Order No.]
    J --> K[Status: Approved]
    
    %% Post-Approval Actions
    K --> L[System Actions]
    L --> M[Notify Requester]
    L --> N[Update Records]
    L --> O[Generate Official Documents]
    
    %% Status Tracking
    B -->|Status| P((Draft))
    D -->|Status| Q((For Recommendation))
    F -->|Status| R((Disapproved))
    G -->|Status| S((For Approval))
    K -->|Status| T((Approved))
    
    %% Styling
    classDef startend fill:#4caf50,stroke:#2e7d32,color:white,stroke-width:2px
    classDef process fill:#2196f3,stroke:#0d47a1,color:white,stroke-width:2px
    classDef decision fill:#ff9800,stroke:#e65100,color:black,stroke-width:2px
    classDef status fill:#9c27b0,stroke:#4a148c,color:white,stroke-width:2px
    classDef system fill:#607d8b,stroke:#263238,color:white,stroke-width:2px
    
    class A startend
    class B,C,D,E,F,G,H,I,J,K,L,M,N,O process
    class P,Q,R,S status
    class L system
    G -->|Status| O[For Approval]
    K -->|Status| P[Approved]
    
    %% Styling
    classDef default fill:#f9f9f9,stroke:#333,stroke-width:1px;
    classDef process fill:#e1f5fe,stroke:#0288d1,stroke-width:2px;
    classDef decision fill:#fff3e0,stroke:#f57c00,stroke-width:2px;
    classDef status fill:#e8f5e9,stroke:#388e3c,stroke-width:1px;
    
    class A,B,D,G,J,L process;
    class C,E,H decision;
    class M,N,O,P status;
```

## 2. Detailed Process Flow

### 2.1 Request Creation & Submission
1. **Travel Order Request**
   - User creates a new travel order request with:
     - Complete travel details (dates, destination, purpose)
     - Required documentation
     - Supporting information
   - System validates all mandatory fields
   - Auto-saves progress as draft

2. **Submission for Recommendation**
   - User finalizes and submits the request
   - System:
     - Validates all required information
     - Updates status to "For Recommendation"
     - Notifies the designated recommender
     - Locks the request from further edits

### 2.2 Recommendation Phase
1. **Review Process**
   - Recommender is notified of pending request
   - Reviews travel order details and documentation
   - Makes decision:
     - **Recommend for Approval**: Moves to approval phase
     - **Disapprove**: Request is rejected with reason

2. **Recommendation Actions**
   - If recommended:
     - Status changes to "For Approval"
     - Approver is automatically notified
   - If disapproved:
     - Status changes to "Disapproved"
     - User receives notification with reason
     - User must address issues and resubmit

### 2.3 Approval Phase
1. **Final Review Process**
   - Approver receives notification
   - Conducts final review of:
     - Travel details
     - Recommendation notes
     - Supporting documents
   - Makes final decision:
     - **Approve**: Proceeds to finalize
     - **Disapprove**: Rejects with reason

2. **Approval Actions**
   - If approved:
     - System generates official travel order number
     - Status changes to "Approved"
     - Official documents are generated and stamped
   - If disapproved:
     - Status changes to "Disapproved"
     - User receives detailed feedback

### 2.4 Post-Approval
1. **Document Generation & Notification**
   - System generates and assigns official travel order number
   - Creates official documents:
     - Travel Order with official number
     - Approved itinerary
     - Supporting documentation
   - Notifications sent to:
     - Requester (with travel order details)
     - Relevant departments (Finance, HR, etc.)

2. **Record Keeping**
   - All actions are logged with:
     - Timestamp
     - User who performed the action
     - Comments/Notes
   - Full audit trail maintained

## 3. Status Lifecycle

### 3.1 Status Transition Diagram

```mermaid
stateDiagram-v2
    [*] --> Draft: New Request
    
    state Draft {
        [*] --> Editing
        Editing --> Editing: Auto-save
        Editing --> Validation: Submit
        Validation --> Editing: Validation Failed
        Validation --> ForRecommendation: Valid
    }
    
    state ForRecommendation {
        [*] --> PendingReview
        PendingReview --> UnderReview: Opened by Recommender
        UnderReview --> Disapproved: Disapproved
        UnderReview --> ForApproval: Recommended
    }
    
    state ForApproval {
        [*] --> PendingApproval
        PendingApproval --> FinalReview: Opened by Approver
        FinalReview --> Disapproved: Disapproved
        FinalReview --> Approved: Approved
    }
    
    state Disapproved {
        [*] --> NeedsRevision
        NeedsRevision --> [*]: User Updates
    }
    
    state Approved {
        [*] --> Processing
        Processing --> Active: Documents Generated
        Active --> Completed: Travel Completed
    }
    
    %% Transitions
    Draft --> [*]: Delete
    Disapproved --> Draft: User Resubmits
    
    %% Styling
    classDef startend fill:#4caf50,stroke:#2e7d32,color:white
    classDef draft fill:#2196f3,stroke:#0d47a1,color:white
    classDef review fill:#ff9800,stroke:#e65100,color:black
    classDef approved fill:#9c27b0,stroke:#4a148c,color:white
    classDef completed fill:#607d8b,stroke:#263238,color:white
    
    class Draft draft
    class ForRecommendation,ForApproval review
    class Approved approved
    class Completed completed
    class [*] startend

## 4. Additional Workflow Details

### 4.1 User Roles & Permissions

| Role | Permissions |
|------|-------------|
| Requester | Create, Edit, Submit, Withdraw, View own |
| Recommender | Review, Comment, Recommend, Request Changes |
| Approver | Approve, Reject, Request Changes, View all |
| Administrator | Full access, Override, Reporting |

### 4.2 Notification Matrix

| Event | Recipients | Channel |
|-------|------------|----------|
| Submitted for Recommendation | Recommender | Email, In-App |
| Recommendation Decision | | |
| • Recommended | Approver | Email, In-App |
| • Disapproved | Requester | Email, In-App |
| Approval Decision | | |
| • Approved | Requester, Finance, HR | Email, In-App |
| • Disapproved | Requester | Email, In-App |
| Travel Order Number Assigned | Requester, All Stakeholders | Email, In-App |

### 4.3 Error Handling

| Scenario | Action |
|----------|--------|
| Validation Failure | Show specific error messages |
| Missing Approver | Escalate to backup approver |
| System Error | Log details, notify admin, allow retry |
| Data Conflict | Show warning, allow resolution |

### 4.4 Performance Considerations

- All status changes are processed asynchronously
- Heavy operations (document generation) are queued
- Frequent auto-saves don't block user interaction
- Notifications are batched for better performance

## 5. Implementation Notes

1. **Audit Trail**
   - Every action is logged with:
     - User ID
     - Timestamp
     - IP Address
     - Action details
     - Before/after values (if applicable)

2. **Security**
   - Role-based access control
   - Data validation at all levels
   - Input sanitization
   - CSRF protection
   - Rate limiting

3. **Scalability**
   - Database indexing for frequent queries
   - Caching of frequently accessed data
   - Queue system for background jobs
   - Horizontal scaling support
