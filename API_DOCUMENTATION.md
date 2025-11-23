# Apex Property Management API Documentation

This document provides comprehensive documentation for the Apex Property Management API endpoints.

## Authentication

All API endpoints except authentication require a Bearer token obtained via login.

### Register
- **Endpoint**: `POST /api/auth/register`
- **Body**:
  ```json
  {
    "name": "string",
    "phone": "string",
    "email": "string (optional)",
    "password": "string",
    "password_confirmation": "string",
    "role": "tenant|landlord|agent|admin"
  }
  ```
- **Response**: User object with token

### Login
- **Endpoint**: `POST /api/auth/login`
- **Body**:
  ```json
  {
    "phone_or_email": "string",
    "password": "string"
  }
  ```
- **Response**: User object with token

### Logout
- **Endpoint**: `POST /api/auth/logout`
- **Headers**: `Authorization: Bearer {token}`
- **Response**: Success message

## Properties

### List Properties
- **Endpoint**: `GET /api/properties`
- **Query Params**: `neighborhood`, `price_min`, `price_max`, `bedrooms`
- **Response**: Array of properties with units

### Get Property
- **Endpoint**: `GET /api/properties/{id}`
- **Response**: Property object with units

### Create Property
- **Endpoint**: `POST /api/properties`
- **Body**:
  ```json
  {
    "title": "string",
    "description": "string (optional)",
    "address": "string",
    "neighborhood": "string",
    "geo_lat": "numeric (optional)",
    "geo_lng": "numeric (optional)",
    "amenities": "array (optional)"
  }
  ```
- **Response**: Property object

### Update Property
- **Endpoint**: `PUT /api/properties/{id}`
- **Body**: Same as create (partial)
- **Response**: Property object

### Delete Property
- **Endpoint**: `DELETE /api/properties/{id}`
- **Response**: Success message

## Units

### List Units
- **Endpoint**: `GET /api/units`
- **Query Params**: `property_id`, `is_available`, `bedrooms`
- **Response**: Array of units

### Get Unit
- **Endpoint**: `GET /api/units/{id}`
- **Response**: Unit object

### Create Unit
- **Endpoint**: `POST /api/properties/{propertyId}/units`
- **Body**:
  ```json
  {
    "unit_label": "string",
    "bedrooms": "integer",
    "bathrooms": "integer",
    "size_m2": "numeric (optional)",
    "rent_amount": "numeric",
    "deposit_amount": "numeric",
    "is_available": "boolean (optional)",
    "photos": "array (optional)"
  }
  ```
- **Response**: Unit object

### Update Unit
- **Endpoint**: `PUT /api/units/{id}`
- **Body**: Same as create (partial)
- **Response**: Unit object

### Delete Unit
- **Endpoint**: `DELETE /api/units/{id}`
- **Response**: Success message

## Leases

### List Leases
- **Endpoint**: `GET /api/leases`
- **Response**: Array of leases

### Request Lease
- **Endpoint**: `POST /api/leases/{unit_id}/request`
- **Body**:
  ```json
  {
    "start_date": "date",
    "end_date": "date",
    "payment_frequency": "monthly|weekly|quarterly"
  }
  ```
- **Response**: Lease object

### Get Lease
- **Endpoint**: `GET /api/leases/{id}`
- **Response**: Lease object

### Update Lease
- **Endpoint**: `PUT /api/leases/{id}`
- **Body**:
  ```json
  {
    "status": "pending|active|terminated (optional)",
    "start_date": "date (optional)",
    "end_date": "date (optional)"
  }
  ```
- **Response**: Lease object

### Sign Lease
- **Endpoint**: `POST /api/leases/{id}/sign`
- **Body**:
  ```json
  {
    "signature_type": "typed|image",
    "signature": "string"
  }
  ```
- **Response**: Lease object

### Generate PDF
- **Endpoint**: `POST /api/leases/{id}/generate-pdf`
- **Response**: PDF URL

### Cancel Lease
- **Endpoint**: `DELETE /api/leases/{id}`
- **Response**: Success message

## Maintenance Requests

### List Maintenance Requests
- **Endpoint**: `GET /api/maintenance`
- **Response**: Array of requests

### Get Maintenance Request
- **Endpoint**: `GET /api/maintenance/{id}`
- **Response**: Request object

### Create Maintenance Request
- **Endpoint**: `POST /api/maintenance`
- **Body**:
  ```json
  {
    "unit_id": "integer",
    "title": "string",
    "description": "string",
    "priority": "low|medium|high|urgent",
    "photos": "files (optional)"
  }
  ```
- **Response**: Request object

### Update Maintenance Request
- **Endpoint**: `PATCH /api/maintenance/{id}`
- **Body**:
  ```json
  {
    "status": "open|in_progress|resolved|rejected (optional)",
    "assigned_to": "integer (optional)",
    "resolution_notes": "string (optional)"
  }
  ```
- **Response**: Request object

### Cancel Maintenance Request
- **Endpoint**: `DELETE /api/maintenance/{id}`
- **Response**: Success message

## Agents

### List Agents
- **Endpoint**: `GET /api/agents`
- **Response**: Array of agents

### Get Agent
- **Endpoint**: `GET /api/agents/{id}`
- **Response**: Agent object

### Register as Agent
- **Endpoint**: `POST /api/agents`
- **Body**:
  ```json
  {
    "agency_name": "string",
    "commission_rate": "numeric (optional)",
    "docs": "files (optional)"
  }
  ```
- **Response**: Agent object

### Update Agent
- **Endpoint**: `PUT /api/agents/{id}`
- **Body**: Same as register (partial)
- **Response**: Agent object

### Verify Agent
- **Endpoint**: `POST /api/agents/{id}/verify`
- **Response**: Success message

### Delete Agent
- **Endpoint**: `DELETE /api/agents/{id}`
- **Response**: Success message

## Disputes

### List Disputes (Admin)
- **Endpoint**: `GET /api/disputes`
- **Response**: Array of disputes

### Get Dispute
- **Endpoint**: `GET /api/disputes/{id}`
- **Response**: Dispute object

### Create Dispute
- **Endpoint**: `POST /api/disputes`
- **Body**:
  ```json
  {
    "lease_id": "integer",
    "issue": "string",
    "evidence": "files (optional)"
  }
  ```
- **Response**: Dispute object

### Update Dispute (Admin)
- **Endpoint**: `PATCH /api/disputes/{id}`
- **Body**:
  ```json
  {
    "status": "resolved|rejected",
    "admin_resolution_notes": "string (optional)"
  }
  ```
- **Response**: Dispute object

### Cancel Dispute
- **Endpoint**: `DELETE /api/disputes/{id}`
- **Response**: Success message

## Conversations

### List Conversations
- **Endpoint**: `GET /api/conversations`
- **Response**: Array of conversations

### Get Conversation
- **Endpoint**: `GET /api/conversations/{id}`
- **Response**: Conversation object with messages

### Create Conversation
- **Endpoint**: `POST /api/conversations`
- **Body**:
  ```json
  {
    "title": "string (optional)",
    "participants": "array of user IDs"
  }
  ```
- **Response**: Conversation object

### Update Conversation
- **Endpoint**: `PUT /api/conversations/{id}`
- **Body**:
  ```json
  {
    "title": "string (optional)"
  }
  ```
- **Response**: Conversation object

### Leave/Delete Conversation
- **Endpoint**: `DELETE /api/conversations/{id}`
- **Response**: Success message

## Messages

### List Messages
- **Endpoint**: `GET /api/conversations/{id}/messages`
- **Response**: Array of messages

### Get Message
- **Endpoint**: `GET /api/conversations/{id}/messages/{messageId}`
- **Response**: Message object

### Send Message
- **Endpoint**: `POST /api/conversations/{id}/messages`
- **Body**:
  ```json
  {
    "content": "string (optional)",
    "attachments": "files (optional)"
  }
  ```
- **Response**: Message object

### Update Message
- **Endpoint**: `PUT /api/conversations/{id}/messages/{messageId}`
- **Body**:
  ```json
  {
    "content": "string (optional)"
  }
  ```
- **Response**: Message object

### Delete Message
- **Endpoint**: `DELETE /api/conversations/{id}/messages/{messageId}`
- **Response**: Success message

## Plans

### List Plans
- **Endpoint**: `GET /api/plans`
- **Response**: Array of active plans

### Get Plan
- **Endpoint**: `GET /api/plans/{id}`
- **Response**: Plan object

### Create Plan
- **Endpoint**: `POST /api/plans`
- **Body**:
  ```json
  {
    "name": "string",
    "description": "string (optional)",
    "monthly_price": "numeric",
    "yearly_price": "numeric",
    "max_properties": "integer",
    "max_units": "integer",
    "max_users": "integer",
    "features": "array (optional)",
    "is_active": "boolean (optional)",
    "trial_days": "integer (optional)"
  }
  ```
- **Response**: Plan object

### Update Plan
- **Endpoint**: `PUT /api/plans/{id}`
- **Body**: Same as create (partial)
- **Response**: Plan object

### Delete Plan
- **Endpoint**: `DELETE /api/plans/{id}`
- **Response**: Success message

## Subscriptions

### List Subscriptions
- **Endpoint**: `GET /api/subscriptions`
- **Response**: Array of subscriptions

### Get Subscription
- **Endpoint**: `GET /api/subscriptions/{id}`
- **Response**: Subscription object

### Subscribe to Plan
- **Endpoint**: `POST /api/subscriptions`
- **Body**:
  ```json
  {
    "plan_id": "integer",
    "billing_cycle": "monthly|yearly",
    "tenant_id": "integer (admin only)"
  }
  ```
- **Response**: Subscription object

### Update Subscription
- **Endpoint**: `PUT /api/subscriptions/{id}`
- **Body**:
  ```json
  {
    "plan_id": "integer (optional)",
    "billing_cycle": "monthly|yearly (optional)",
    "status": "active|expired|cancelled (optional)"
  }
  ```
- **Response**: Subscription object

### Cancel Subscription
- **Endpoint**: `DELETE /api/subscriptions/{id}`
- **Response**: Success message

## Error Responses

All endpoints may return the following error responses:

- `400 Bad Request`: Invalid input
- `401 Unauthorized`: Missing or invalid token
- `403 Forbidden`: Insufficient permissions
- `404 Not Found`: Resource not found
- `500 Internal Server Error`: Server error

## Notes

- All requests require `Accept: application/json` header
- File uploads are supported for photos, docs, evidence, attachments
- Dates should be in YYYY-MM-DD format
- Boolean values: true/false
- Arrays are JSON arrays
- All monetary values are in the system's currency
