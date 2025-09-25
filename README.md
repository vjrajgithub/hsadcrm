# CRM System - CodeIgniter 3

A comprehensive Customer Relationship Management system built with CodeIgniter 3, featuring enterprise-grade security, performance optimization, and advanced functionality.

## Features

### Core Functionality
- **Company Management** - Complete company profile management
- **Client Management** - Advanced client relationship tracking
- **Quotation System** - Professional quotation generation with PDF export
- **Bank Integration** - Multi-bank account management
- **Product/Service Catalog** - Comprehensive inventory management
- **User Management** - Role-based access control
- **Email Integration** - Send quotations via email

## Technical Stack

- **Backend**: CodeIgniter 3.x (PHP MVC framework)
- **Database**: MySQL
- **Frontend**: AdminLTE 3 + Bootstrap 4
- **JavaScript**: jQuery, DataTables, Chart.js, SweetAlert2
- **PDF**: dompdf library

## Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for dependencies)

### Setup Steps

1. **Clone/Download the project**
   ```bash
   git clone <repository-url>
   cd crm
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Database Setup**
   - Create a MySQL database named `crm_db`
   - Import the SQL file: `crm_db(3).sql`
   - Update database credentials in `.env` file (see Environment Configuration)

4. **Environment Configuration**
   - Copy `.env.example` to `.env`
   - Update the following variables:
     ```
     DB_HOSTNAME=localhost
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
     DB_DATABASE=crm_db
     APP_BASE_URL=http://localhost/crm/
     APP_ENCRYPTION_KEY=generate-a-strong-32-character-key
     ```

5. **Web Server Configuration**
   - Ensure mod_rewrite is enabled
   - Point document root to the project folder
   - The `.htaccess` file handles URL rewriting

6. **File Permissions**
   ```bash
   chmod -R 755 application/cache/
   chmod -R 755 application/logs/
   chmod -R 755 assets/uploads/
   ```

## Default Login

- **Email**: Create a Super Admin user in the database
- **Password**: Use PHP's `password_hash()` function to generate password hash

## Project Structure

```
crm/
├── application/
│   ├── controllers/     # MVC Controllers
│   ├── models/         # Database Models
│   ├── views/          # View Templates
│   ├── config/         # Configuration files
│   └── ...
├── assets/
│   ├── admin/          # Admin theme assets
│   ├── uploads/        # File uploads
│   └── ...
├── system/             # CodeIgniter core files
├── .htaccess          # URL rewriting rules
├── index.php          # Application entry point
└── composer.json      # Dependencies
```

## Security Features

- **Environment Variables** - Sensitive data stored in `.env` file
- **Input Validation** - Form validation and XSS protection
- **Authentication** - Session-based login system
- **Access Control** - Super Admin role restrictions
- **Security Headers** - Added via `.htaccess`
- **File Protection** - Sensitive files blocked from direct access

## Development

### VS Code Configuration

The project includes VS Code launch configuration for debugging:
- Launch CRM Application in Chrome
- Proper workspace settings

### Code Standards

- Follow CodeIgniter 3 conventions
- Use MVC architecture
- Implement proper error handling
- Add comments for complex logic

## Recent Improvements

✅ **Code Cleanup**
- Removed duplicate controller and model files
- Cleaned up commented-out code in routes
- Fixed duplicate chart rendering in dashboard

✅ **Security Enhancements**
- Added environment variable support
- Improved database configuration
- Enhanced `.htaccess` security headers
- Protected sensitive files

✅ **Configuration**
- Updated VS Code launch settings
- Improved URL rewriting rules
- Better error handling

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check `.env` file configuration
   - Verify MySQL service is running
   - Confirm database exists and credentials are correct

2. **404 Errors**
   - Ensure mod_rewrite is enabled
   - Check `.htaccess` file permissions
   - Verify base URL in configuration

3. **Permission Errors**
   - Set proper file permissions for cache and logs directories
   - Ensure web server has write access to uploads folder

4. **Login Issues**
   - Verify user exists in database with 'Super Admin' role
   - Check password hash is generated correctly
   - Ensure session configuration is proper

## Contributing

1. Follow CodeIgniter coding standards
2. Test all changes thoroughly
3. Update documentation as needed
4. Use meaningful commit messages

## License

This project is licensed under the MIT License.
