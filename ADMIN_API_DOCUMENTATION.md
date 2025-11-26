# Apex Property Management Admin API Documentation

This document provides comprehensive documentation for the Apex Property Management Admin API endpoints. These endpoints are restricted to users with the 'admin' role.

## Authentication

All admin API endpoints require authentication with a Bearer token and the user must have the 'admin' role.

## Dashboard

### System Overview
- **Endpoint**: `GET /api/admin/dashboard/overview`
- **Response**: System-wide statistics including tenant count, user count, properties, units, leases, payments, etc.

### Analytics Data
- **Endpoint**: `GET /api/admin/dashboard/analytics`
- **Query Params**: `period` (days, default 30)
- **Response**: Detailed analytics including user registrations, property stats, financial data, maintenance stats, dispute stats, and agent stats

### Recent Activity
- **Endpoint**: `GET /api/admin/dashboard/recent-activity`
- **Response**: Recent system activities (user registrations, property additions, disputes, etc.)

### Tenant Overview
- **Endpoint**: `GET /api/admin/dashboard/tenants`
- **Response**: Overview of all tenants with their statistics and subscription status

## User Management

### List Users
- **Endpoint**: `GET /api/admin/users`
- **Query Params**: `tenant_id`, `role`, `page`, `per_page`
- **Response**: Paginated list of users with roles and tenant info

### Create User
- **Endpoint**: `POST /api/admin/users`
- **Body**:
  ```json
  {
    "name": "string (required)",
    "phone": "string (required, unique)",
    "email": "string (optional, unique)",
    "password": "string (required, min 8)",
    "role": "string (optional)",
    "tenant_id": "integer (optional)",
    "roles": "array (optional)",
    "is_verified": "boolean (optional)"
  }
  ```
- **Response**: User object with roles

### Get User
- **Endpoint**: `GET /api/admin/users/{id}`
- **Response**: User object with roles and tenant

### Update User
- **Endpoint**: `PUT /api/admin/users/{id}`
- **Body**: Partial update of create fields
- **Response**: Updated user object

### Delete User
- **Endpoint**: `DELETE /api/admin/users/{id}`
- **Response**: Success message

### Assign Role to User
- **Endpoint**: `POST /api/admin/users/{id}/assign-role`
- **Body**:
  ```json
  {
    "role": "string (required, existing role name)"
  }
  ```
- **Response**: Success message

### Remove Role from User
- **Endpoint**: `POST /api/admin/users/{id}/remove-role`
- **Body**:
  ```json
  {
    "role": "string (required, existing role name)"
  }
  ```
- **Response**: Success message

## Role Management

### List Roles
- **Endpoint**: `GET /api/admin/roles`
- **Response**: Array of roles with permissions

### Create Role
- **Endpoint**: `POST /api/admin/roles`
- **Body**:
  ```json
  {
    "name": "string (required, unique)",
    "guard_name": "string (optional)",
    "permissions": "array (optional)"
  }
  ```
- **Response**: Role object with permissions

### Get Role
- **Endpoint**: `GET /api/admin/roles/{id}`
- **Response**: Role object with permissions

### Update Role
- **Endpoint**: `PUT /api/admin/roles/{id}`
- **Body**: Partial update of create fields
- **Response**: Updated role object

### Delete Role
- **Endpoint**: `DELETE /api/admin/roles/{id}`
- **Response**: Success message

### Assign Permission to Role
- **Endpoint**: `POST /api/admin/roles/{id}/assign-permission`
- **Body**:
  ```json
  {
    "permission": "string (required, existing permission name)"
  }
  ```
- **Response**: Success message

### Remove Permission from Role
- **Endpoint**: `POST /api/admin/roles/{id}/remove-permission`
- **Body**:
  ```json
  {
    "permission": "string (required, existing permission name)"
  }
  ```
- **Response**: Success message

## Permission Management

### List Permissions
- **Endpoint**: `GET /api/admin/permissions`
- **Response**: Array of permissions

### Create Permission
- **Endpoint**: `POST /api/admin/permissions`
- **Body**:
  ```json
  {
    "name": "string (required, unique)",
    "guard_name": "string (optional)"
  }
  ```
- **Response**: Permission object

### Get Permission
- **Endpoint**: `GET /api/admin/permissions/{id}`
- **Response**: Permission object

### Update Permission
- **Endpoint**: `PUT /api/admin/permissions/{id}`
- **Body**: Partial update of create fields
- **Response**: Updated permission object

### Delete Permission
- **Endpoint**: `DELETE /api/admin/permissions/{id}`
- **Response**: Success message

## Tenant Management

### List Tenants
- **Endpoint**: `GET /api/admin/tenants`
- **Query Params**: `page`, `per_page`
- **Response**: Paginated list of tenants with subscription and user count

### Create Tenant
- **Endpoint**: `POST /api/admin/tenants`
- **Body**:
  ```json
  {
    "name": "string (required)",
    "domain": "string (required, unique)"
  }
  ```
- **Response**: Tenant object

### Get Tenant
- **Endpoint**: `GET /api/admin/tenants/{id}`
- **Response**: Tenant object with subscription, users, properties, and units

### Update Tenant
- **Endpoint**: `PUT /api/admin/tenants/{id}`
- **Body**: Partial update of create fields
- **Response**: Updated tenant object

### Delete Tenant
- **Endpoint**: `DELETE /api/admin/tenants/{id}`
- **Response**: Success message

### Get Tenant Statistics
- **Endpoint**: `GET /api/admin/tenants/{id}/stats`
- **Response**:
  ```json
  {
    "total_users": "integer",
    "total_properties": "integer",
    "total_units": "integer",
    "active_leases": "integer",
    "pending_maintenance": "integer",
    "total_payments": "numeric",
    "subscription_status": "string"
  }
  ```

## Payment Management

### List Payments
- **Endpoint**: `GET /api/admin/payments`
- **Query Params**: `tenant_id`, `status`, `date_from`, `date_to`, `page`, `per_page`
- **Response**: Paginated list of payments with lease and tenant info

### Get Payment
- **Endpoint**: `GET /api/admin/payments/{id}`
- **Response**: Payment object with lease and tenant details

### Update Payment
- **Endpoint**: `PUT /api/admin/payments/{id}`
- **Body**:
  ```json
  {
    "status": "pending|completed|failed|refunded (optional)",
    "payment_method": "string (optional)",
    "notes": "string (optional)"
  }
  ```
- **Response**: Updated payment object

### Get Payment Statistics
- **Endpoint**: `GET /api/admin/payments/stats`
- **Query Params**: `tenant_id`
- **Response**:
  ```json
  {
    "total_payments": "integer",
    "total_amount": "numeric",
    "completed_payments": "integer",
    "completed_amount": "numeric",
    "pending_payments": "integer",
    "failed_payments": "integer"
  }
  ```

## Agent Management

### List Agents
- **Endpoint**: `GET /api/admin/agents`
- **Query Params**: `status` (verified|pending), `tenant_id`, `page`, `per_page`
- **Response**: Paginated list of agents with user and tenant info

### Get Agent
- **Endpoint**: `GET /api/admin/agents/{id}`
- **Response**: Agent details with user and tenant information

### Update Agent
- **Endpoint**: `PUT /api/admin/agents/{id}`
- **Body**:
  ```json
  {
    "agency_name": "string (optional)",
    "commission_rate": "numeric (optional)",
    "is_verified": "boolean (optional)"
  }
  ```
- **Response**: Updated agent object

### Delete Agent
- **Endpoint**: `DELETE /api/admin/agents/{id}`
- **Response**: Success message

### Verify Agent
- **Endpoint**: `POST /api/admin/agents/{id}/verify`
- **Response**: Success message with verified agent

### Remove Agent Verification
- **Endpoint**: `POST /api/admin/agents/{id}/unverify`
- **Response**: Success message

### Agent Statistics
- **Endpoint**: `GET /api/admin/agents/stats`
- **Response**:
  ```json
  {
    "total_agents": "integer",
    "verified_agents": "integer",
    "pending_verification": "integer",
    "avg_commission_rate": "numeric"
  }
  ```

## Dispute Management

### List Disputes
- **Endpoint**: `GET /api/admin/disputes`
- **Query Params**: `status`, `tenant_id`, `date_from`, `date_to`, `page`, `per_page`
- **Response**: Paginated list of disputes with lease and tenant info

### Get Dispute
- **Endpoint**: `GET /api/admin/disputes/{id}`
- **Response**: Dispute details with full lease and tenant information

### Update Dispute
- **Endpoint**: `PUT /api/admin/disputes/{id}`
- **Body**:
  ```json
  {
    "status": "open|resolved|rejected",
    "admin_resolution_notes": "string (optional)"
  }
  ```
- **Response**: Updated dispute object

### Assign Dispute to Admin
- **Endpoint**: `POST /api/admin/disputes/{id}/assign`
- **Body**:
  ```json
  {
    "admin_id": "integer (required, existing admin user ID)"
  }
  ```
- **Response**: Success message with assigned dispute

### Bulk Update Disputes
- **Endpoint**: `POST /api/admin/disputes/bulk-update`
- **Body**:
  ```json
  {
    "dispute_ids": "array (required)",
    "status": "resolved|rejected",
    "admin_resolution_notes": "string (optional)"
  }
  ```
- **Response**: Success message with count of updated disputes

### Dispute Statistics
- **Endpoint**: `GET /api/admin/disputes/stats`
- **Query Params**: `tenant_id`
- **Response**:
  ```json
  {
    "total_disputes": "integer",
    "open_disputes": "integer",
    "resolved_disputes": "integer",
    "rejected_disputes": "integer",
    "avg_resolution_time": "numeric (hours)",
    "disputes_by_month": "object"
  }
  ```

## Plan Management

### List Plans
- **Endpoint**: `GET /api/admin/plans`
- **Query Params**: `is_active`, `search`, `page`, `per_page`
- **Response**: Paginated list of all plans (active and inactive) with subscription counts

### Create Plan
- **Endpoint**: `POST /api/admin/plans`
- **Body**:
  ```json
  {
    "name": "string (required, unique)",
    "description": "string (optional)",
    "monthly_price": "numeric (required)",
    "yearly_price": "numeric (required)",
    "max_properties": "integer (required)",
    "max_units": "integer (required)",
    "max_users": "integer (required)",
    "features": "array (optional)",
    "is_active": "boolean (optional)",
    "trial_days": "integer (optional)"
  }
  ```
- **Response**: Created plan object

### Get Plan
- **Endpoint**: `GET /api/admin/plans/{id}`
- **Response**: Plan details with associated subscriptions

### Update Plan
- **Endpoint**: `PUT /api/admin/plans/{id}`
- **Body**: Partial update of create fields
- **Response**: Updated plan object

### Delete Plan
- **Endpoint**: `DELETE /api/admin/plans/{id}`
- **Response**: Success message

### Toggle Plan Active Status
- **Endpoint**: `POST /api/admin/plans/{id}/toggle`
- **Response**: Success message with updated plan

### Duplicate Plan
- **Endpoint**: `POST /api/admin/plans/{id}/duplicate`
- **Response**: Success message with duplicated plan

### Plan Statistics
- **Endpoint**: `GET /api/admin/plans/stats`
- **Response**:
  ```json
  {
    "total_plans": "integer",
    "active_plans": "integer",
    "inactive_plans": "integer",
    "total_subscriptions": "integer",
    "active_subscriptions": "integer",
    "monthly_subscriptions": "integer",
    "yearly_subscriptions": "integer",
    "total_revenue": "numeric",
    "plans_by_popularity": "array"
  }
  ```

## Error Responses

All endpoints may return the following error responses:

- `401 Unauthorized`: Missing or invalid token
- `403 Forbidden`: Insufficient permissions (not admin)
- `404 Not Found`: Resource not found
- `422 Unprocessable Entity`: Validation errors
- `500 Internal Server Error`: Server error

## Notes

- All requests require `Accept: application/json` header
- Dates should be in YYYY-MM-DD format
- Boolean values: true/false
- Arrays are JSON arrays
- All monetary values are in the system's currency
- Admin role is required for all endpoints in this documentation
