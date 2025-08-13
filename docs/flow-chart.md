# Travel Order Management System - Workflow

## 1. Workflow Overview

```mermaid
flowchart TD
    A[1. Create Travel Order] --> B[Status: For Recommendation]
    
    B --> C{2. Recommender}
    C -->|Approve| D[3. Status: For Approval]
    C -->|Disapprove| E[Status: Disapproved]
    
    D --> F{4. Approver}
    F -->|Approve| G[5. Status: Approved\nAssign Travel Order #]
    F -->|Disapprove| H[Status: Disapproved]
    
    E --> I[6. Create New Request]
    H --> I
    G --> J[Complete]
    
    %% Styling
    classDef process fill:#e3f2fd,stroke:#1976d2,stroke-width:2px,color:#0d47a1
    classDef decision fill:#fff3e0,stroke:#fb8c00,stroke-width:2px,color:#e65100
    classDef approved fill:#e8f5e9,stroke:#2e7d32,stroke-width:2px,color:#1b5e20
    classDef rejected fill:#ffebee,stroke:#c62828,stroke-width:2px,color:#b71c1c
    classDef status fill:#e1f5fe,stroke:#0288d1,stroke-width:1.5px,color:#01579b
    
    class A process
    class C,F decision
    class G approved
    class E,H rejected
    class B,D status
    
    class A startend
    class B,C,D,E,F,G,H,I,J,K,L,M,N,O process
    class P,Q,R,S status
    class L system
```

## 2. Process Steps

### 2.1 Create Travel Order
1. **Requester**
   - Complete travel details
   - Attach required documents
   - Submit for recommendation
   - Status: For Recommendation

### 2.2 Recommendation Phase
1. **Recommender**
   - Reviews the request
   - **Approve**: Moves to approver
   - **Disapprove**: Rejects request
   - If disapproved:
     - Status: Disapproved
     - New request needed

### 2.3 Approval Phase
1. **Approver**
   - Reviews the request
   - **Approve**: 
     - Assigns travel order #
     - Status: Approved
   - **Disapprove**: 
     - Status: Disapproved
     - New request needed

## 3. Status Flow

```mermaid
stateDiagram-v2
    [*] --> ForRecommendation: New Request
    ForRecommendation --> ForApproval: Approved
    ForRecommendation --> Disapproved: Rejected
    ForApproval --> Approved: Approved
    ForApproval --> Disapproved: Rejected
    
    Disapproved --> [*]: New Request Needed
    Approved --> [*]: Complete
```

## 4. Key Rules

1. **No Edits**
   - Cannot modify after submission
   - Create new request for changes

2. **Disapproved Requests**
   - Cannot be modified
   - New request required

3. **Approval**
   - Sequential (recommender → approver)
   - Travel order # assigned at final approval

## 5. Notifications

| When | Who | What |
|------|-----|------|
| New Request | Recommender | Review request |
| Approved by Recommender | Approver | Review request |
| Disapproved | Requester | Create new request |
| Approved | Requester, Finance | Travel order # assigned |

## 6. Process Notes

1. **For Requesters**
   - Double-check all details before submission
   - Keep copies of submitted documents
   - Create new request if rejected

2. **For Approvers**
   - Review all details carefully
   - Provide clear reason if rejecting
   - Verify travel order # is assigned

3. **System**
   - Maintains complete audit log
   - Tracks all status changes
   - Ensures sequential approval
