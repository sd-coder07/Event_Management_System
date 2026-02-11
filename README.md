# Event Management System

A comprehensive web-based event management system with role-based access control for Admin, Vendors, and Users.

## Features

### Admin Module
- Full system control and management
- User management (add, update, delete, activate/deactivate)
- Vendor management (add, update, delete, activate/deactivate)
- Membership management (add, update, extend, cancel)
- View all system transactions

### Vendor Module
- Vendor registration and authentication
- Product management (add, update, delete)
- View product orders and status
- Transaction history
- User request management

### User Module
- User registration and authentication
- Browse vendors by category (Catering, Florist, Decoration, Lighting)
- Shopping cart functionality
- Guest list management
- Secure checkout process
- Order tracking and status updates

## Technologies Used

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP 7+
- **Database:** MySQL
- **Server:** Apache (XAMPP recommended)

## Installation

1. **Install XAMPP**
   - Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Start Apache and MySQL services

2. **Setup Database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `event_management_system`
   - Import the database schema:
     ```sql
     -- Navigate to database/ folder
     -- Execute schema.sql file
     ```

3. **Configure Database Connection**
   - Open `config/database.php`
   - Update database credentials if needed:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'event_management_system');
     ```

4. **Setup Project**
   - Copy the entire project folder to XAMPP's `htdocs` directory
   - Example: `C:\xampp\htdocs\Axiom`

5. **Create Uploads Directory**
   - Ensure the `uploads/` directory exists and has write permissions
   - On Windows: Right-click → Properties → Security → Edit → Allow write

6. **Access the Application**
   - Open browser and navigate to: `http://localhost/Axiom/`

## Default Admin Credentials

- **Username:** Admin
- **Password:** Admin

**Important:** Change the default password after first login for security.

## Project Structure

```
Axiom/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── validation.js
├── config/
│   ├── database.php
│   └── session.php
├── database/
│   └── schema.sql
├── includes/
│   └── functions.php
├── pages/
│   ├── admin/
│   │   ├── dashboard.php
│   │   ├── maintenance.php
│   │   ├── manage_users.php
│   │   ├── manage_vendors.php
│   │   ├── add_membership.php
│   │   └── update_membership.php
│   ├── vendor/
│   │   ├── dashboard.php
│   │   ├── add_item.php
│   │   ├── your_items.php
│   │   ├── product_status.php
│   │   ├── request_items.php
│   │   └── transactions.php
│   ├── user/
│   │   ├── dashboard.php
│   │   ├── browse_vendors.php
│   │   ├── cart.php
│   │   ├── checkout.php
│   │   ├── guest_list.php
│   │   ├── order_status.php
│   │   └── order_success.php
│   ├── auth/
│   │   ├── admin_login_process.php
│   │   ├── vendor_login_process.php
│   │   ├── user_login_process.php
│   │   ├── vendor_signup_process.php
│   │   ├── user_signup_process.php
│   │   └── logout.php
│   ├── admin_login.php
│   ├── vendor_login.php
│   ├── vendor_signup.php
│   ├── user_login.php
│   ├── user_signup.php
│   └── flowchart.php
├── uploads/
├── index.php
└── README.md
```

## Usage Guide

### For Admin:
1. Login with admin credentials
2. Access maintenance menu for system management
3. Manage users and vendors
4. Add/Update vendor memberships

### For Vendors:
1. Register as a new vendor with category selection
2. Login with credentials
3. Add products with images and prices
4. View orders and manage products
5. Track transactions

### For Users:
1. Register as a new user
2. Login with credentials
3. Browse vendors by category
4. Add products to cart
5. Manage guest list
6. Proceed to checkout
7. Track order status

## Security Features

- Password hashing using PHP's `password_hash()`
- Session-based authentication
- Role-based access control
- SQL injection prevention using prepared statements
- Input sanitization
- XSS protection

## Form Validations

- Client-side validation using JavaScript
- Server-side validation using PHP
- Email format validation
- Required field checks
- Password strength validation
- Phone number format validation

## Browser Compatibility

- Chrome (recommended)
- Firefox
- Edge
- Safari

## Troubleshooting

### Database Connection Error
- Verify MySQL service is running in XAMPP
- Check database credentials in `config/database.php`
- Ensure database `event_management_system` exists

### Image Upload Issues
- Check `uploads/` directory exists
- Verify write permissions on uploads folder
- Check PHP `upload_max_filesize` in php.ini

### Session Issues
- Clear browser cookies/cache
- Check PHP session configuration
- Verify session.save_path is writable

## Future Enhancements

- Email notifications for orders
- Payment gateway integration
- Real-time order status updates
- Vendor ratings and reviews
- Advanced search and filtering
- Report generation
- Mobile app integration

## Support

For issues or questions, please refer to the project documentation or contact the development team.

## License

This project is developed for educational purposes as part of a college project.

## Contributors

- Project developed according to technical specifications
- Following standard web development best practices

---

**Note:** This is a college project. Please ensure to follow your institution's guidelines and requirements.
