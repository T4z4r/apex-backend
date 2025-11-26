# Database Structure

This document outlines the database schema for the Apex property management system, a multi-tenant Laravel application.

## Overview

The application uses multi-tenancy where most tables include a `tenant_id` foreign key to the `tenants` table, allowing data isolation per tenant. The system manages properties, units, leases, payments, maintenance requests, agents, disputes, and messaging.

## Tables

### users
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| tenant_id | bigint unsigned | Foreign Key (tenants.id), On Delete Cascade |
| name | varchar(255) | |
| phone | varchar(255) | Unique |
| email | varchar(255) | Nullable, Unique |
| password | varchar(255) | |
| role | enum('tenant', 'landlord', 'agent', 'admin') | |
| is_verified | tinyint(1) | Default 0 |
| id_document_url | varchar(255) | Nullable |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

### tenants
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| name | varchar(255) | |
| domain | varchar(255) | Unique |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

### properties
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| tenant_id | bigint unsigned | Foreign Key (tenants.id), On Delete Cascade |
| landlord_id | bigint unsigned | Foreign Key (users.id), On Delete Cascade |
| title | varchar(255) | |
| description | text | Nullable |
| address | varchar(255) | |
| neighborhood | varchar(255) | |
| geo_lat | decimal(10,7) | Nullable |
| geo_lng | decimal(10,7) | Nullable |
| amenities | json | Nullable |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

### units
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| tenant_id | bigint unsigned | Foreign Key (tenants.id), On Delete Cascade |
| property_id | bigint unsigned | Foreign Key (properties.id), On Delete Cascade |
| unit_label | varchar(255) | |
| bedrooms | int | Default 1 |
| bathrooms | int | Default 1 |
| size_m2 | decimal(6,2) | Nullable |
| rent_amount | decimal(12,2) | |
| deposit_amount | decimal(12,2) | |
| is_available | tinyint(1) | Default 1 |
| photos | json | Nullable |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

### leases
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| unit_id | bigint unsigned | Foreign Key (units.id), On Delete Cascade |
| tenant_id | bigint unsigned | Foreign Key (users.id), On Delete Cascade |
| landlord_id | bigint unsigned | Foreign Key (users.id), On Delete Cascade |
| start_date | date | |
| end_date | date | |
| rent_amount | decimal(12,2) | |
| deposit_amount | decimal(12,2) | |
| payment_frequency | enum('monthly', 'weekly', 'quarterly') | Default 'monthly' |
| status | enum('pending', 'active', 'expired', 'terminated') | Default 'pending' |
| lease_pdf_url | varchar(255) | Nullable |
| signed_at | timestamp | Nullable |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

### payments
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| tenant_id | bigint unsigned | Foreign Key (tenants.id), On Delete Cascade |
| lease_id | bigint unsigned | Foreign Key (leases.id), Nullable, On Delete Set Null |
| unit_id | bigint unsigned | Foreign Key (units.id), Nullable, On Delete Set Null |
| payer_id | bigint unsigned | Foreign Key (users.id), On Delete Cascade |
| payee_id | bigint unsigned | Foreign Key (users.id), On Delete Cascade |
| amount | decimal(12,2) | |
| method | enum('mpesa', 'airtel', 'bank') | |
| reference | varchar(255) | Nullable |
| status | enum('pending', 'completed', 'failed') | Default 'pending' |
| transaction_date | timestamp | Nullable |
| receipt_url | varchar(255) | Nullable |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

### maintenance_requests
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| unit_id | bigint unsigned | Foreign Key (units.id), On Delete Cascade |
| tenant_id | bigint unsigned | Foreign Key (users.id), On Delete Cascade |
| landlord_id | bigint unsigned | Foreign Key (users.id), On Delete Cascade |
| title | varchar(255) | |
| description | text | |
| status | enum('open', 'in_progress', 'resolved', 'rejected') | Default 'open' |
| priority | enum('low', 'medium', 'high', 'urgent') | Default 'medium' |
| photos | json | Nullable |
| assigned_to | bigint unsigned | Foreign Key (users.id), Nullable, On Delete Set Null |
| resolved_at | timestamp | Nullable |
| resolution_notes | text | Nullable |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

### agents
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| tenant_id | bigint unsigned | Foreign Key (tenants.id), On Delete Cascade |
| user_id | bigint unsigned | Foreign Key (users.id), On Delete Cascade |
| agency_name | varchar(255) | |
| commission_rate | decimal(5,2) | Default 0.00 |
| verified_at | timestamp | Nullable |
| docs | json | Nullable |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

### disputes
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| tenant_id | bigint unsigned | Foreign Key (tenants.id), On Delete Cascade |
| lease_id | bigint unsigned | Foreign Key (leases.id), On Delete Cascade |
| raised_by | bigint unsigned | Foreign Key (users.id), On Delete Cascade |
| issue | text | |
| status | enum('open', 'in_review', 'resolved', 'rejected') | Default 'open' |
| evidence | json | Nullable |
| admin_resolution_notes | text | Nullable |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

### conversations
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| tenant_id | bigint unsigned | Foreign Key (tenants.id), On Delete Cascade |
| title | varchar(255) | Nullable |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

### messages
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| tenant_id | bigint unsigned | Foreign Key (tenants.id), On Delete Cascade |
| conversation_id | bigint unsigned | Foreign Key (conversations.id), On Delete Cascade |
| sender_id | bigint unsigned | Foreign Key (users.id), On Delete Cascade |
| content | text | |
| attachments | json | Nullable |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

### plans
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| name | varchar(255) | |
| description | text | Nullable |
| monthly_price | decimal(8,2) | |
| yearly_price | decimal(8,2) | |
| max_properties | int | Default -1 |
| max_units | int | Default -1 |
| max_users | int | Default -1 |
| features | json | Nullable |
| is_active | tinyint(1) | Default 1 |
| trial_days | int | Default 30 |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

### subscriptions
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| tenant_id | bigint unsigned | Foreign Key (tenants.id), On Delete Cascade |
| plan_id | bigint unsigned | Foreign Key (plans.id), On Delete Cascade |
| billing_cycle | varchar(255) | |
| trial_ends_at | date | Nullable |
| ends_at | date | Nullable |
| status | varchar(255) | Default 'active' |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

### permissions (Spatie Laravel Permission)
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| name | varchar(255) | |
| guard_name | varchar(255) | |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

*Unique: name, guard_name*

### roles (Spatie Laravel Permission)
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| team_foreign_key | bigint unsigned | Nullable (if teams enabled) |
| name | varchar(255) | |
| guard_name | varchar(255) | |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |

*Unique constraints depend on team configuration*

### model_has_permissions (Spatie Laravel Permission)
| Column | Type | Constraints |
|--------|------|-------------|
| permission_id | bigint unsigned | Foreign Key (permissions.id), On Delete Cascade |
| model_type | varchar(255) | |
| model_id | bigint unsigned | |
| team_foreign_key | bigint unsigned | Nullable (if teams enabled) |

*Composite Primary Key*

### model_has_roles (Spatie Laravel Permission)
| Column | Type | Constraints |
|--------|------|-------------|
| role_id | bigint unsigned | Foreign Key (roles.id), On Delete Cascade |
| model_type | varchar(255) | |
| model_id | bigint unsigned | |
| team_foreign_key | bigint unsigned | Nullable (if teams enabled) |

*Composite Primary Key*

### role_has_permissions (Spatie Laravel Permission)
| Column | Type | Constraints |
|--------|------|-------------|
| permission_id | bigint unsigned | Foreign Key (permissions.id), On Delete Cascade |
| role_id | bigint unsigned | Foreign Key (roles.id), On Delete Cascade |

*Primary Key: permission_id, role_id*

### password_reset_tokens
| Column | Type | Constraints |
|--------|------|-------------|
| email | varchar(255) | Primary Key |
| token | varchar(255) | |
| created_at | timestamp | Nullable |

### failed_jobs
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| uuid | varchar(255) | Unique |
| connection | text | |
| queue | text | |
| payload | longtext | |
| exception | longtext | |
| failed_at | timestamp | |

### personal_access_tokens
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint unsigned | Primary Key, Auto Increment |
| tokenable_type | varchar(255) | |
| tokenable_id | bigint unsigned | |
| name | varchar(255) | |
| token | varchar(64) | Unique |
| abilities | text | Nullable |
| last_used_at | timestamp | Nullable |
| expires_at | timestamp | Nullable |
| created_at | timestamp | Nullable |
| updated_at | timestamp | Nullable |
