# Travel Order Management System - Workflow

## 1. Complete Workflow

```mermaid
flowchart TD
    %% Step 1: Create Travel Order
    A[1. Requester Creates Travel Order] --> B[Status: For Recommendation]
    
    %% Step 2 & 3: Recommender Review
    B --> C{2. Recommender Reviews}
    C -->|Approve| D[3. Forwarded for Approval]
    C -->|Disapprove| E[Status: Disapproved]
    
    %% Step 4 & 5: Approver Review
    D --> F{4. Approver Reviews}
    F -->|Approve| G[5. Status: Approved\nAssign Travel Order No.]
    F -->|Disapprove| H[Status: Disapproved]
    
    %% Step 6: End States
    E --> I[6. Create New Request]
    H --> I
    G --> J[6. Process Complete]
    
    %% Styling
    classDef default fill:#f9f9f9,stroke:#333,stroke-width:1px
    classDef process fill:#e1f5fe,stroke:#0288d1,stroke-width:2px
    classDef decision fill:#fff3e0,stroke:#f57c00,stroke-width:2px
    classDef status fill:#e8f5e9,stroke:#388e3c,stroke-width:1px
    classDef rejected fill:#ffebee,stroke:#c62828,stroke-width:1px
    
    class A,B,D process
    class C,F decision
    class G status
    class E,H rejected
    
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

## 2. Step-by-Step Process

### 2.1 Create Travel Order (Requester)
1. **Submit New Request**
   - Fill out travel order form
   - Attach necessary documents
   - Submit for processing
   - System sets status to "For Recommendation"

### 2.2 Recommendation Phase
1. **Recommender Review**
   - Receives notification of new request
   - Reviews travel order details
   - Makes decision:
     - **Approve**: Forwards to approver
     - **Disapprove**: Rejects request
   - If disapproved:
     - Status set to "Disapproved"
     - Requester must create new request

### 2.3 Approval Phase
1. **Approver Review**
   - Receives notification of forwarded request
   - Conducts final review
   - Makes decision:
     - **Approve**: Final approval
     - **Disapprove**: Reject request
   - If approved:
     - System generates unique travel order number
     - Status set to "Approved"
   - If disapproved:
     - Status set to "Disapproved"
     - Requester must create new request

## 3. Status Flow

```mermaid
stateDiagram-v2
    [*] --> ForRecommendation: 1. New Request
    
    state ForRecommendation {
        [*] --> Pending
        Pending --> Reviewed: 2. Recommender reviews
        Reviewed --> ForApproval: Approved
        Reviewed --> Disapproved: Disapproved
    }
    
    state ForApproval {
        [*] --> PendingApproval
        PendingApproval --> FinalReview: 4. Approver reviews
        FinalReview --> Approved: Approved
        FinalReview --> Disapproved: Disapproved
    }
    
    Approved --> [*]: 6. Process Complete
    Disapproved --> NewRequest: 6. Create New Request
    NewRequest --> [*]
    
    classDef default fill:#f9f9f9,stroke:#333,stroke-width:1px
    classDef pending fill:#fffde7,stroke:#fbc02d,stroke-width:2px
    classDef approved fill:#e8f5e9,stroke:#388e3c,stroke-width:2px
    classDef rejected fill:#ffebee,stroke:#c62828,stroke-width:2px
```
    class ForRecommendation,ForApproval pending
    class Approved approved
    class Disapproved rejected

## 4. Key Points

1. **Workflow Steps**
   1. Requester creates travel order
   2. Recommender reviews (Approve/Disapprove)
   3. If approved, moves to approver
   4. Approver reviews (Approve/Disapprove)
   5. If approved, assigns travel order number
   6. Process completes or new request needed

2. **Important Rules**
   - No editing after submission
   - Disapproved requests cannot be modified
   - New request required for any changes
   - Travel order number assigned only after final approval

## 5. Notifications

| Step | Event | Sent To | Method |
|------|-------|---------|--------|
| 1    | New Request Submitted | Recommender | Email, In-App |
| 2-3  | Recommendation Decision | | |
|      | • Approved | Approver | Email, In-App |
|      | • Disapproved | Requester | Email, In-App |
| 4-5  | Approval Decision | | |
|      | • Approved | Requester, Finance | Email, In-App |
|      | • Disapproved | Requester | Email, In-App |

## 6. Important Notes

1. **Request Creation**
   - Complete all required fields
   - Attach necessary documents
   - Review before submission
   - No changes allowed after submission

2. **Disapproved Requests**
   - Cannot be modified or resubmitted
   - New request must be created
   - Previous requests remain in system for reference

3. **Approval Process**
   - Strictly sequential (recommendation → approval)
   - No step can be skipped
   - All decisions are final
   - Full audit trail maintained
