# 🏪 Laravel Point of Sale System

A comprehensive **Point of Sale (POS) management system** with advanced features including invoice generation, inventory management, and reporting capabilities. Built with **Laravel 10**, **MySQL**, **Tailwind CSS**, and **Alpine.js**.

## 🛠️ Tech Stack

-   **Backend**: Laravel 10.x (PHP 8.1+)
-   **Database**: MySQL
-   **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
-   **Authentication**: Laravel Breeze
-   **Icons**: Font Awesome, Heroicons
-   **Tools**: Vite, Laravel Backup, Laravel Permission
-   **Additional**: Shopping Cart, Barcode Generator, Excel Export

## 📸 Screenshots

![Dashboard](https://user-images.githubusercontent.com/71541409/234483153-38816efd-c261-4585-bb93-28639508f5e3.jpg)

_More screenshots coming soon..._

## 😎 Features

### 🏪 Core POS Features

-   **Point of Sale (POS)** - Modern, user-friendly POS interface
-   **Barcode Scanning** - Quick product identification
-   **Receipt Printing** - Professional invoice generation
-   **Real-time Inventory Updates** - Automatic stock adjustments

### 📊 Order & Sales Management

-   **Order Management**
    -   Pending Orders
    -   Completed Orders
    -   Pending Due Payments
    -   Order History & Analytics
-   **Invoice Generation** - Professional PDF invoices
-   **Sales Reports** - Daily, weekly, monthly reports

### 📦 Inventory & Product Management

-   **Stock Management** - Real-time inventory tracking
-   **Low Stock Alerts** - Automated notifications
-   **Product Management**
    -   Products with variants
    -   Categories & Subcategories
    -   Barcode generation
    -   Product images
-   **Supplier Management** - Vendor tracking and purchase orders

### 👥 Personnel Management

-   **Employee Management** - Staff profiles and roles
-   **Customer Management** - Customer database and loyalty
-   **Supplier Management** - Vendor relationships
-   **Salary Management**
    -   Advance Salary
    -   Pay Salary
    -   Salary History
-   **Attendance Management** - Time tracking

### 🔐 Security & Administration

-   **Role & Permission System** - Granular access control
-   **User Management** - Multi-user support
-   **Database Backup** - Automated backups
-   **Audit Trail** - Activity logging

### 📱 Modern UI/UX

-   **Responsive Design** - Works on all devices
-   **Dark/Light Mode** - Theme switching
-   **Modern Dashboard** - Analytics and insights
-   **Quick Actions** - Streamlined workflows

## 📋 System Requirements

Before installation, ensure your system meets the following requirements:

-   **PHP**: 8.1 or higher
-   **Composer**: Latest version
-   **Node.js**: 16+ and npm
-   **Database**: MySQL 5.7+ or MariaDB 10.3+
-   **Web Server**: Apache or Nginx
-   **Extensions**: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo, GD

## 🚀 Installation Guide

#### 1. Clone the Repository

To get started, clone or download the repository:

```bash
git clone https://github.com/4urie/laravel-pos.git
```

#### 2. Set Up the Project

Once you've cloned the repository, navigate to the project directory and install dependencies:

```bash
cd laravel-pos
composer install
```

Open the project in your preferred code editor:

```bash
code .
```

#### 3. Configure the Environment

Rename the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

#### 4. Set Faker Locale (Optional)

To set the Faker locale (for example, to Indonesian), add the following line at the end of your `.env` file:

```bash
FAKER_LOCALE="id_ID"
```

#### 5. Set Up the Database

Configure your database credentials in the `.env` file.

#### 6. Migrate and Seed Database

Run the following command to migrate and seed the database with sample data:

```bash
php artisan migrate:fresh --seed
```

**Note**: If you encounter any errors, ensure your database connection is properly configured and try again.

#### 7. Build Frontend Assets

Compile the frontend assets:

```bash
npm run dev
```

For production:

```bash
npm run build
```

#### 8. Create Storage Link

Create a symbolic link for storage:

```bash
php artisan storage:link
```

#### 9. Start the Development Server

To run the application locally:

```bash
php artisan serve
```

The application will be available at `http://127.0.0.1:8000`

#### 10. Access the Application

Use the following default credentials to log in:

-   **Username**: `admin`
-   **Password**: `password`

## ⚙️ Configuration

#### 1. Environment Configuration

Configure your `.env` file with appropriate values:

```bash
APP_NAME="Laravel POS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

#### 2. Cart Configuration

Configure cart settings in `./config/cart.php`:

```php
// Tax rate
'tax' => 0,

// Number format
'number_format' => [
    'decimals' => 2,
    'decimal_point' => '.',
    'thousands_separator' => ','
],
```

For more details, check the [hardevine/shoppingcart documentation](https://packagist.org/packages/hardevine/shoppingcart).

#### 3. Backup Configuration

Configure automatic backups in `./config/backup.php` or use the default settings.

#### 4. Permission System

The system uses Spatie Laravel Permission for role-based access control. Configure roles and permissions as needed.

## 🔧 Troubleshooting

### Common Issues

**Database Connection Error**

```bash
# Check your database credentials in .env file
# Ensure MySQL service is running
```

**Storage Permission Issues**

```bash
# Fix storage permissions (Linux/Mac)
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Windows users: Ensure proper folder permissions
```

**Asset Compilation Issues**

```bash
# Clear cache and rebuild
npm run build
php artisan optimize:clear
```

**Migration Errors**

```bash
# Reset and migrate fresh
php artisan migrate:fresh --seed
```

## 📱 Usage Tips

1. **First Time Setup**: Change default admin password immediately
2. **Regular Backups**: Set up automated database backups
3. **User Training**: Train staff on POS interface and features
4. **Inventory Management**: Regularly update stock levels and prices
5. **Reports**: Review sales reports for business insights

## 🚀 Development

### Running in Development Mode

```bash
# Start development server
php artisan serve

# Watch for asset changes
npm run dev

# Run in background
php artisan serve &
npm run dev
```

### Testing

```bash
# Run tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
```

## 💡 Contributing

Have suggestions or want to contribute? Here's how:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests (`php artisan test`)
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

### Code Standards

-   Follow PSR-12 coding standards
-   Write meaningful commit messages
-   Add tests for new features
-   Update documentation as needed

## 🆘 Support

Need help? Here are your options:

-   📖 **Documentation**: Check this README and code comments
-   🐛 **Issues**: Report bugs or request features via GitHub Issues
-   💬 **Discussions**: Join community discussions
-   📧 **Contact**: Reach out to the maintainer

## 🗂️ Project Structure

```
app/
├── Http/Controllers/     # Application controllers
├── Models/              # Eloquent models
├── Services/            # Business logic services
└── Policies/            # Authorization policies

database/
├── migrations/          # Database migrations
├── seeders/            # Database seeders
└── factories/          # Model factories

resources/
├── views/              # Blade templates
├── js/                 # JavaScript files
└── css/                # Stylesheets

routes/
├── web.php             # Web routes
└── api.php             # API routes
```

## 🔄 Updates & Changelog

### Latest Updates

-   ✅ Laravel 10.x compatibility
-   ✅ Modern UI with Tailwind CSS
-   ✅ Enhanced security features
-   ✅ Improved performance
-   ✅ Better mobile responsiveness
-   ✅ Advanced reporting features

### Roadmap

-   🔄 API development for mobile apps
-   🔄 Multi-language support
-   🔄 Advanced analytics dashboard
-   🔄 Integration with payment gateways
-   🔄 Barcode scanning improvements

## 📄 License

This project is licensed under the [MIT License](LICENSE).

## 🙏 Acknowledgments

-   Laravel Framework
-   Tailwind CSS
-   Font Awesome
-   Spatie Laravel Packages
-   All contributors and supporters

---

**🌟 Star this repo if you find it helpful! 🌟**

Made with ❤️ by [4urie](https://github.com/4urie)

**Repository**: [https://github.com/4urie/laravel-pos](https://github.com/4urie/laravel-pos)
