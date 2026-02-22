<p align="center">
  <a href="https://github.com/your-username/edutrack" target="_blank">
    <img src="https://img.shields.io/badge/EduTrack-Academic%20Management%20System-blue" alt="EduTrack">
  </a>
</p>

<p align="center">
  <a href="https://github.com/your-username/edutrack/actions">
    <img src="https://github.com/your-username/edutrack/workflows/Deploy%20EduTrack/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/badge/Laravel-10.10+-red" alt="Laravel Version">
  </a>
  <a href="https://php.net">
    <img src="https://img.shields.io/badge/PHP-8.3+-blue" alt="PHP Version">
  </a>
  <a href="https://opensource.org/licenses/MIT">
    <img src="https://img.shields.io/badge/License-MIT-green" alt="License">
  </a>
</p>

# EduTrack - Academic Management System

A comprehensive Laravel-based academic management system designed for educational institutions to manage students, teachers, courses, grading, and attendance efficiently.

## 🌟 Features

### 📚 Core Functionality
- **Multi-Role Authentication**: Admin, Teacher, and Student portals
- **Student Management**: Enrollment, profiles, and academic records
- **Course Management**: Subject creation and assignment
- **Class Management**: Organize students into classes and sections

### 📊 Grading System
- **KSA Methodology**: Knowledge, Skills, and Attitude assessment
- **Automated Grade Calculations**: Weighted averages and final grades
- **Grade Entry Interface**: Intuitive grade input with real-time calculations
- **PDF Report Generation**: Comprehensive grade reports and transcripts

### 📅 Attendance Management
- **Daily Attendance Tracking**: Present, Absent, Late, Excused
- **Real-time Status Display**: Visual indicators for attendance status
- **Attendance History**: Comprehensive attendance records and analytics
- **Bulk Operations**: Quick attendance marking for entire classes

### 🎨 User Interface
- **Modern Design**: Bootstrap 5 with custom styling
- **Responsive Layout**: Works seamlessly on desktop and mobile
- **Interactive Components**: Dynamic forms and real-time updates
- **Accessibility**: WCAG compliant interface

## 🚀 Quick Start

### Prerequisites
- PHP 8.3+
- MySQL 8.0+
- Composer
- Node.js & NPM
- Git

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/edutrack.git
   cd edutrack
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Start the application**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` in your browser.

### Default Credentials
```
Email: admin@example.com
Password: password
```

## 🌐 Deployment

### GitHub Deployment (Recommended)

For complete deployment instructions using GitHub Actions and CI/CD, see our comprehensive guide:

👉 **[GitHub Deployment Guide](GITHUB_DEPLOYMENT_GUIDE.md)**

This guide covers:
- Repository setup and configuration
- CI/CD pipeline with GitHub Actions
- Multiple deployment options (DigitalOcean, Shared Hosting)
- Automated testing and deployment
- SSL setup and security best practices
- Monitoring and maintenance

### Quick Deployment Options

| Platform | Difficulty | Cost | Automation |
|----------|------------|------|------------|
| **DigitalOcean** | Medium | $5-20/mo | ✅ Full CI/CD |
| **Shared Hosting** | Easy | $5-15/mo | ⚠️ Partial |
| **VPS (Ubuntu)** | Hard | $10-50/mo | ✅ Full CI/CD |
| **Local (Laragon)** | Easy | Free | ❌ Manual |

## 📋 System Requirements

| Component | Minimum | Recommended |
|-----------|---------|-------------|
| PHP | 8.3 | 8.3 |
| MySQL | 8.0 | 8.0+ |
| RAM | 2GB | 4GB |
| Storage | 5GB | 20GB |
| PHP Extensions | mbstring, xml, mysql, bcmath, curl, zip, gd | All above + redis |

## 🛠️ Development

### Project Structure
```
edutrack/
├── app/                 # Application logic
├── bootstrap/          # Bootstrap files
├── config/             # Configuration files
├── database/           # Migrations and seeds
├── public/             # Public assets
├── resources/          # Views, CSS, JS
├── routes/             # Route definitions
├── storage/            # App storage
├── tests/              # Test files
└── .github/workflows/  # CI/CD pipelines
```

### Available Commands

```bash
# Development
php artisan serve                 # Start dev server
npm run dev                       # Watch assets

# Production
php artisan config:cache         # Cache config
php artisan route:cache          # Cache routes
php artisan view:cache           # Cache views
npm run build                    # Build assets

# Database
php artisan migrate              # Run migrations
php artisan migrate:rollback     # Undo migrations
php artisan db:seed              # Seed data

# Maintenance
php artisan cache:clear          # Clear all caches
php artisan storage:link         # Create symlink
```

## 🧪 Testing

Run the test suite:

```bash
php artisan test
```

The test suite includes:
- Unit tests for business logic
- Feature tests for user workflows
- Database integration tests

## 📊 Monitoring

### Health Check
Visit `/health` endpoint to monitor application status:

```json
{
  "status": "ok",
  "timestamp": "2024-02-23T10:30:00.000000Z",
  "database": "connected",
  "cache": "hit"
}
```

### Logging
- Application logs: `storage/logs/laravel.log`
- Error reporting via email
- Database query logging (in debug mode)

## 🔧 Configuration

### Environment Variables
Key environment variables in `.env`:

```env
APP_NAME=EduTrack
APP_ENV=local
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edutrack
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

### Custom Configuration
- Grading system weights in `config/grading.php`
- Attendance settings in `config/attendance.php`
- PDF generation settings in `config/dompdf.php`

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Code Standards
- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation
- Use descriptive commit messages

## 📝 Changelog

### v1.0.0 (2024-02-23)
- ✨ Initial release
- 🎨 Modern UI with Bootstrap 5
- 📊 KSA grading system implementation
- 📅 Attendance management system
- 🔐 Multi-role authentication
- 📄 PDF report generation
- 🚀 GitHub Actions CI/CD pipeline

## 🆘 Support

### Documentation
- [GitHub Deployment Guide](GITHUB_DEPLOYMENT_GUIDE.md)
- [System Requirements](SYSTEM_REQUIREMENTS.md)
- [Troubleshooting Guide](TROUBLESHOOTING_GUIDE.md)

### Community
- [Issues](https://github.com/your-username/edutrack/issues) - Bug reports and feature requests
- [Discussions](https://github.com/your-username/edutrack/discussions) - General questions
- [Wiki](https://github.com/your-username/edutrack/wiki) - Additional documentation

### Getting Help
1. Check the [FAQ](TROUBLESHOOTING_GUIDE.md#faq)
2. Search existing [Issues](https://github.com/your-username/edutrack/issues)
3. Create a new issue with detailed information
4. Join our [Discussions](https://github.com/your-username/edutrack/discussions)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Laravel Framework](https://laravel.com/) - The PHP Framework For Web Artisans
- [Bootstrap](https://getbootstrap.com/) - The most popular CSS framework
- [Font Awesome](https://fontawesome.com/) - The internet's icon library
- [DOMPDF](https://github.com/dompdf/dompdf) - HTML to PDF converter

---

<div align="center">

**⭐ Star this repository if it helped you!**

Made with ❤️ by the EduTrack Development Team

</div>

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
