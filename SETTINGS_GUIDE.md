# CRM System Settings Guide

## Overview
The CRM system now includes a comprehensive settings management system that allows Super Admin users to configure application-wide settings through a user-friendly interface. All settings are stored in the database and cached for optimal performance.

## Features

### 🔧 Settings Categories
- **Application**: App name, version, maintenance mode, timezone
- **Email**: SMTP configuration for system emails
- **Security**: Session timeout, password policies, login attempts
- **Business**: Company information, GST number, currency settings

### 🎯 Global Integration
Settings are automatically used throughout the entire CRM system:
- **Email sending** (quotations, password resets) uses SMTP settings
- **Application branding** uses app name and company info
- **Currency formatting** uses default currency setting
- **Security policies** enforce password and session rules

## Setup Instructions

### 1. Initial Setup
```
http://localhost/crm/settings/setup
```
This creates the `system_settings` table and initializes default values.

### 2. Access Settings Management
```
http://localhost/crm/settings
```
Only Super Admin users can access this page.

## Usage Guide

### Settings Helper Functions
The system provides global helper functions accessible throughout the application:

```php
// Get any setting value
$app_name = get_setting('app_name', 'Default CRM');

// Get email configuration
$email_config = get_email_config();

// Get company information
$company_info = get_company_info();

// Format currency
$formatted = format_currency(1000.50); // ₹ 1,000.50

// Send system email
send_system_email('user@example.com', 'Subject', 'Message');
```

### Adding New Settings
1. Access Settings Management page
2. Click "Add New Setting"
3. Fill in the form:
   - **Category**: Group settings logically
   - **Setting Key**: Unique identifier (e.g., `new_feature_enabled`)
   - **Setting Value**: The actual value
   - **Description**: What this setting controls
   - **Input Type**: text, number, email, password, textarea, select, checkbox
   - **Options**: For select/checkbox (format: `value1:label1,value2:label2`)

### Updating Settings
- **Quick Update**: Click on any setting value to edit inline
- **Full Edit**: Use the edit button for complete setting modification
- **Bulk Category Update**: Use category tabs to update related settings together

## Technical Implementation

### Database Schema
```sql
CREATE TABLE system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(100) NOT NULL,
    setting_key VARCHAR(255) NOT NULL UNIQUE,
    setting_value TEXT,
    description TEXT,
    input_type ENUM('text', 'number', 'email', 'password', 'textarea', 'select', 'checkbox'),
    options TEXT,
    sort_order INT DEFAULT 999,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);
```

### Caching System
- **Static Cache**: In-memory cache for current request
- **CodeIgniter Cache**: Persistent cache (1 hour TTL)
- **Auto-Clear**: Cache automatically cleared when settings are updated

### Integration Points

#### Email System
All email functionality now uses database settings:
```php
// Quotation emails
$config = get_email_config();
$this->load->library('email', $config);

// Password reset emails
$from_email = get_setting('from_email', 'noreply@hsadcrm.com');
$from_name = get_setting('from_name', get_app_name());
```

#### Application Configuration
```php
// Dynamic page titles
<title><?= get_app_name() ?></title>

// Company information in documents
$company = get_company_info();
echo $company['name']; // HSAD Technologies
```

## Security Features

### Access Control
- Only Super Admin role can manage settings
- RBAC enforced at controller level
- UI elements hidden for non-authorized users

### Data Validation
- Server-side validation for all input types
- Unique key constraints
- XSS protection with input sanitization
- CSRF token protection

### Error Handling
- Graceful fallbacks to default values
- Database error logging
- Cache failure recovery

## Performance Optimization

### Caching Strategy
1. **First Request**: Database → Cache → Return
2. **Subsequent Requests**: Cache → Return
3. **Cache Miss**: Database → Update Cache → Return
4. **Setting Update**: Clear Cache → Database → New Cache

### Best Practices
- Use specific setting keys (avoid generic names)
- Group related settings in categories
- Provide meaningful descriptions
- Set appropriate default values
- Clear cache after bulk updates

## Common Settings

### Email Configuration
```
smtp_host: smtp.gmail.com
smtp_port: 587
smtp_username: your-email@gmail.com
smtp_password: your-app-password
from_email: noreply@yourcompany.com
from_name: Your Company CRM
```

### Business Information
```
company_name: Your Company Name
company_address: Your Complete Address
company_phone: +91-XXXXXXXXXX
company_email: info@yourcompany.com
gst_number: 22AAAAA0000A1Z5
default_currency: INR
```

### Security Settings
```
session_timeout: 3600 (1 hour)
password_min_length: 8
max_login_attempts: 5
lockout_duration: 900 (15 minutes)
```

## Troubleshooting

### Common Issues

1. **Settings not loading**
   - Check if `system_settings` table exists
   - Run setup URL to create table
   - Verify database connection

2. **Cache not clearing**
   - Check CodeIgniter cache configuration
   - Manually clear cache files
   - Restart web server

3. **Permission denied**
   - Verify user has Super Admin role
   - Check RBAC configuration
   - Review session data

### Debug Commands
```php
// Check if setting exists
var_dump(get_setting('app_name'));

// Clear all settings cache
clear_settings_cache();

// Get all settings
$all_settings = get_settings();
```

## Future Enhancements

### Planned Features
- Settings import/export functionality
- Environment-specific settings
- Settings backup and restore
- API endpoints for settings management
- Settings change history/audit log

### Extension Points
- Custom validation rules
- Setting-specific UI components
- Integration with external configuration systems
- Multi-tenant settings support

## Support

For technical support or feature requests related to the settings system, please:
1. Check this documentation first
2. Review error logs in `application/logs/`
3. Test with default values
4. Contact system administrator

---

*This settings system provides enterprise-grade configuration management for the HSAD CRM system, ensuring scalability, security, and ease of use.*
