# EduTrack - Complete Deployment & Setup Guide

## 🎯 Executive Summary

This guide provides comprehensive instructions for deploying EduTrack on your laptop and ensuring it works correctly for presentations.

---

## 📋 SYSTEM REQUIREMENTS

### Hardware Requirements

- **Processor**: Intel i5/AMD Ryzen 5 or better
- **RAM**: Minimum 4GB (8GB recommended)
- **Storage**: 5GB available disk space
- **OS**: Windows 10/11 (64-bit)

### Software Requirements

- **PHP**: 8.1 or higher (with extensions: pdo_mysql, mbstring, openssl, json, curl, xml, tokenizer, bcmath)
- **MySQL/MariaDB**: 5.7 or higher (or use SQLite for development)
- **Composer**: Latest version (PHP dependency manager)
- **Node.js**: 14 or higher (for frontend asset compilation)
- **Git**: For version control (optional)

### Development Environment Options

1. **Laragon** (Recommended for Windows)
    - All-in-one solution with PHP, MySQL, Node.js
    - URL: https://laragon.org/
2. **XAMPP**
    - Classic option, reliable
    - URL: https://www.apachefriends.org/
3. **Docker**
    - Containerized approach
    - Most flexible for different environments

---

## 🚀 INSTALLATION ON LAPTOP (Step-by-Step)

### STEP 1: Install Laragon (Recommended)

1. Download from: https://laragon.org/download/
2. Run the installer and follow on-screen prompts
3. Choose installation directory (e.g., `C:\laragon`)
4. Complete the installation
5. Launch Laragon and verify services are running

### STEP 2: Project Transfer

#### Option A: Using USB/External Drive

```powershell
# Copy entire project folder
Copy-Item -Path "C:\Source\edutrack" -Destination "C:\laragon\www\edutrack" -Recurse
```

#### Option B: Using Cloud Storage

1. Upload project to OneDrive/Google Drive
2. Download on target laptop
3. Extract to `C:\laragon\www\edutrack`

#### Option C: Using Git (If available)

```powershell
cd C:\laragon\www
git clone <repository-url> edutrack
```

### STEP 3: Environment Setup

1. Navigate to project directory:

```powershell
cd C:\laragon\www\edutrack
```

2. Copy example environment file:

```powershell
Copy-Item ".env.example" ".env"
```

3. Edit `.env` file with proper values:

```
APP_NAME=EduTrack
APP_ENV=local
APP_KEY=  # Will be generated in next step
APP_DEBUG=true
APP_URL=http://edutrack.local

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edutrack
DB_USERNAME=root
DB_PASSWORD=  # Default: empty for Laragon
```

### STEP 4: Install Dependencies

1. Install PHP dependencies:

```powershell
composer install
```

2. Install Node.js dependencies (optional, if using asset compilation):

```powershell
npm install
```

### STEP 5: Generate Application Key

```powershell
php artisan key:generate
```

### STEP 6: Database Setup

1. Create database (if not using Laragon terminal):

```powershell
# Using MySQL command line
mysql -u root -e "CREATE DATABASE edutrack;"
```

2. Run migrations:

```powershell
php artisan migrate
```

3. Seed database (optional, populates sample data):

```powershell
php artisan db:seed
```

### STEP 7: Configure Web Server

#### For Laragon:

1. Open Laragon terminal
2. Create virtual host:

```bash
laragon create edutrack
```

3. Or manually: Edit `C:\laragon\etc\hosts` and add:

```
127.0.0.1  edutrack.local
```

4. Configure Apache vhost in `C:\laragon\etc\apache2\sites-enabled\`

#### For XAMPP:

1. Add to `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    ServerName edutrack.local
    DocumentRoot "C:\xampp\htdocs\edutrack\public"
    <Directory "C:\xampp\htdocs\edutrack\public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

2. Add to `C:\Windows\System32\drivers\etc\hosts`:

```
127.0.0.1  edutrack.local
```

### STEP 8: Start Services

```powershell
# Using Laragon (GUI)
# Or via terminal:
laragon start

# Or manually start Apache and MySQL
net start Apache2.4
net start MySQL80
```

### STEP 9: Access Application

1. Open browser and go to: `http://edutrack.local`
2. Or use: `http://localhost`

---

## 🔐 DEFAULT CREDENTIALS

### Super Admin

- **Email**: super@admin.com
- **Password**: password123

### Admin

- **Email**: admin@school.com
- **Password**: password123

### Teacher

- **Email**: teacher@school.com
- **Password**: password123

### Student

- **Email**: student@school.com
- **Password**: password123

**⚠️ IMPORTANT**: Change these credentials immediately after first login in production environment.

---

## 🧪 VERIFICATION CHECKLIST

After setup, verify:

- [ ] Application loads without errors at `http://edutrack.local`
- [ ] Login page displays correctly
- [ ] Can log in with provided credentials
- [ ] Database is connected (check admin dashboard)
- [ ] All navigation menus are accessible
- [ ] No error messages in browser console
- [ ] File uploads work (test with assignment submission)
- [ ] Grades can be entered and viewed
- [ ] Attendance can be marked

---

## 🛠️ TROUBLESHOOTING

### Common Issues

#### 1. "Page not found" or "ERR_NAME_NOT_RESOLVED"

**Solution**: Check hosts file and restart services

```powershell
# Verify hosts file
notepad C:\Windows\System32\drivers\etc\hosts
# Should contain: 127.0.0.1  edutrack.local

# Restart Apache
net stop Apache2.4
net start Apache2.4
```

#### 2. Database Connection Error

**Solution**: Verify `.env` file settings

```powershell
# Test MySQL connection
mysql -h 127.0.0.1 -u root -e "SHOW DATABASES;"

# Check .env DB_* values match
notepad .env
```

#### 3. Composer "No such file or directory"

**Solution**: Ensure PHP is in PATH or use full path

```powershell
# Add to PATH or use:
php "C:\path\to\composer.phar" install
```

#### 4. Permission Denied on storage folder

**Solution**: Set correct permissions

```powershell
# For Windows (Administrative PowerShell)
icacls "C:\laragon\www\edutrack\storage" /grant:r "$env:USERNAME`:(OI)(CI)F" /T
icacls "C:\laragon\www\edutrack\bootstrap\cache" /grant:r "$env:USERNAME`:(OI)(CI)F" /T
```

#### 5. "Compiled view not found"

**Solution**: Clear caches

```powershell
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

---

## 📁 IMPORTANT DIRECTORIES

- **`public/`** - Web root (point Apache DocumentRoot here)
- **`storage/`** - Logs, file uploads, cache
- **`bootstrap/cache/`** - Application bootstrap cache
- **`app/`** - Application code
- **`database/`** - Migrations and seeders
- **`resources/`** - Blade templates and assets

---

## 🔄 DATABASE BACKUP/RESTORE

### Backup

```powershell
# Export database
mysqldump -u root -p edutrack > backup_edutrack.sql

# Or via Laragon terminal
laragon export edutrack
```

### Restore

```powershell
# Import database
mysql -u root -p edutrack < backup_edutrack.sql

# Or via Laravel
php artisan db:seed
```

---

## 📊 SAMPLE DATA

To populate with sample data for testing:

```powershell
php artisan db:seed --class=DatabaseSeeder
```

This will create:

- Sample users (admin, teachers, students)
- Sample departments
- Sample courses and subjects
- Sample classes
- Sample grades

---

## 🎨 THEME CUSTOMIZATION

Default themes available:

- Light (default)
- Dark
- Ocean
- Forest
- Sunset

Users can switch themes in their dashboard settings.

---

## 🔐 SECURITY CHECKLIST

Before presentation:

- [ ] Change default credentials
- [ ] Set `APP_DEBUG=false` in production
- [ ] Generate new `APP_KEY`
- [ ] Configure proper database user (not root)
- [ ] Set up HTTPS (or use self-signed cert for demo)
- [ ] Review `.env` for sensitive information
- [ ] Disable registration if needed

---

## 📝 QUICK COMMAND REFERENCE

```powershell
# Start fresh
php artisan migrate:fresh --seed

# Clear all caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# View database
php artisan tinker
>>> User::all();

# Run tests
php artisan test

# Create new admin user
php artisan tinker
>>> User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password'), 'role' => 'admin']);
```

---

## 🎓 SYSTEM FEATURES

### Student Features

- View grades and GPA
- Submit assignments
- Mark attendance
- View notifications
- Theme customization

### Teacher Features

- Enter grades (CHED system)
- Configure assessment ranges
- Manage attendance
- Create assignments
- View student progress

### Admin Features

- Manage users and roles
- Configure system settings
- View system-wide reports
- Manage departments
- Manage classes and sections

### Super Admin Features

- Full system control
- Access all dashboards
- User management
- System configuration

---

## 📞 SUPPORT

For issues or questions:

1. Check error logs: `storage/logs/laravel.log`
2. Run: `php artisan tinker` for debugging
3. Review blade templates in `resources/views/`
4. Check database schema: `php artisan migrate:status`

---

## ✅ VERIFICATION SUCCESS

When you see:

- ✓ App loading at `http://edutrack.local`
- ✓ Can login with credentials
- ✓ Dashboard displays data
- ✓ No errors in browser console
- ✓ Database queries work

**You're ready for presentation!**

---

## 🚀 NEXT STEPS AFTER DEPLOYMENT

1. Customize school logo in `public/images/`
2. Update school name in `resources/views/layouts/`
3. Add actual student data via CSV import
4. Test all features with sample data
5. Create backup before presentation
6. Practice the system walkthrough

---

_Last Updated: January 2026_
_EduTrack v1.0_
