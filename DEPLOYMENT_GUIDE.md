# EduTrack - Comprehensive Deployment Guide

## Project Overview
**EduTrack** is a Laravel 10 web application for academic management and grading system.

### Tech Stack
- **Backend:** Laravel 10.10+ (PHP 8.1+)
- **Frontend:** Bootstrap 5, Blade templates, Vite (CSS/JS bundling)
- **Database:** MySQL 8.0+
- **Package Managers:** Composer (PHP), NPM (Node.js)
- **Build Tools:** Vite
- **PDF Generation:** DOMPDF
- **Server:** Apache/Nginx with PHP-FPM

---

## PART 1: CURRENT SYSTEM OPTIMIZATION & DEPLOYMENT (Windows with Laragon)

### Step 1: Final Code Review & Cleanup
```bash
# 1.1 Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 1.2 Rebuild caches for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 2: Database Optimization
```bash
# 2.1 Check database integrity
php artisan tinker
# In tinker shell, run:
DB::select('SELECT 1');
exit

# 2.2 Create database backup
# Via Laragon UI or MySQL command:
mysqldump -u root -p edutrack > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Step 3: Dependencies Check
```bash
# 3.1 Verify Composer packages
composer install --no-dev --optimize-autoloader

# 3.2 Update Node packages
npm install
npm run build

# 3.3 Verify no vulnerabilities
composer audit
npm audit
```

### Step 4: Environment Configuration
```bash
# 4.1 Create .env file from template
cp .env.example .env

# 4.2 Generate APP_KEY
php artisan key:generate

# 4.3 Verify critical settings in .env
APP_NAME=EduTrack
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost (or your domain)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edutrack
DB_USERNAME=root
DB_PASSWORD=(your password)
```

### Step 5: File Permissions
```bash
# 5.1 Set proper permissions (Windows/Laragon - optional but recommended)
# In PowerShell (Admin):
# The following directories must be writable:
# - storage/
# - bootstrap/cache/
# - public/

# Clear storage logs
Remove-Item storage/logs/* -Recurse
```

### Step 6: Final Testing
```bash
# 6.1 Run migrations (if needed)
php artisan migrate

# 6.2 Seed initial data (optional)
php artisan db:seed

# 6.3 Test application
php artisan serve
# Visit http://127.0.0.1:8000

# 6.4 Test all major routes
# - Login page: /login
# - Dashboard: /teacher/dashboard
# - Classes: /teacher/classes
# - Courses: /teacher/subjects
```

### Step 7: Production-Ready Checklist
- [ ] APP_DEBUG = false
- [ ] APP_ENV = production
- [ ] database backed up
- [ ] All migrations run
- [ ] Caches rebuilt
- [ ] Node packages compiled (npm run build)
- [ ] .env file secured (outside public dir)
- [ ] No sensitive data in git
- [ ] All routes tested
- [ ] File permissions correct

---

## PART 2: DEPLOYING TO A DIFFERENT PC/SERVER

### Option A: WINDOWS (with Laragon) - Easiest for Development

#### Prerequisites Installation (One-time Setup)
1. **Download & Install Laragon** (Complete Package)
   - Download from: https://laragon.org
   - Includes: PHP 8.1+, MySQL 8.0+, Apache, Node.js, Git
   - Installation: Run installer, choose default location `C:\laragon`

2. **Verify Installations**
```bash
php --version          # Should show PHP 8.1+
mysql --version        # Should show MySQL 8.0+
composer --version     # Should be included with Laragon
node --version         # Should be included with Laragon
npm --version
```

#### Step-by-Step Deployment

**Step 1: Get Project Files**
```bash
# Option 1A: Clone from Git
cd C:\laragon\www
git clone https://github.com/your-repo/edutrack.git
cd edutrack

# Option 1B: Copy project folder
# Copy entire edutrack folder to C:\laragon\www\
```

**Step 2: Install Dependencies**
```bash
# 2.1 Open Terminal in project directory (C:\laragon\www\edutrack)
composer install

# 2.2 Install Node packages
npm install

# 2.3 Build frontend assets
npm run build
```

**Step 3: Environment Setup**
```bash
# 3.1 Copy environment file
copy .env.example .env

# 3.2 Generate App Key
php artisan key:generate

# 3.3 Edit .env file (C:\laragon\www\edutrack\.env)
# Essential settings:
APP_NAME=EduTrack
APP_ENV=local
APP_DEBUG=true (for development, false for production)
APP_URL=http://edutrack.local or http://localhost/edutrack
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edutrack
DB_USERNAME=root
DB_PASSWORD= (leave empty for default Laragon)
```

**Step 4: Database Setup**
```bash
# 4.1 Create database (via Laragon UI or MySQL command)
# Open Laragon, click MySQL, then MySQL Admin (or use command):
mysql -u root -e "CREATE DATABASE IF NOT EXISTS edutrack;"

# 4.2 Run migrations
php artisan migrate

# 4.3 (Optional) Seed sample data
php artisan db:seed
```

**Step 5: Start Application**
```bash
# 5.1 Start Laragon (via UI or terminal)
laragon start
# OR start manually: double-click Laragon app

# 5.2 Access application
# Open browser and go to:
http://localhost/edutrack
# or
http://edutrack.local (if you set up local domain in Laragon)

# 5.3 Or with development server:
php artisan serve
# Visit http://127.0.0.1:8000
```

**Step 6: Default Login**
```
Email: admin@example.com
Password: password
(Update in database or seed file if different)
```

---

### Option B: UBUNTU/DEBIAN SERVER - Production Deployment

#### Prerequisites Installation

**Step 1: Update System**
```bash
sudo apt update && sudo apt upgrade -y
```

**Step 2: Install PHP 8.1**
```bash
sudo apt install php8.1 php8.1-cli php8.1-fpm \
  php8.1-mysql php8.1-mbstring php8.1-xml \
  php8.1-curl php8.1-zip php8.1-gd -y
```

**Step 3: Install MySQL 8.0**
```bash
sudo apt install mysql-server -y
sudo mysql_secure_installation
```

**Step 4: Install Apache (or Nginx)**
```bash
# Apache (easier for beginners):
sudo apt install apache2 libapache2-mod-php8.1 -y
sudo a2enmod php8.1
sudo a2enmod rewrite
sudo systemctl restart apache2

# OR Nginx (better performance but more complex):
sudo apt install nginx php8.1-fpm -y
```

**Step 5: Install Node.js & Composer**
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

**Step 6: Install Git**
```bash
sudo apt install git -y
```

#### Deploy Application

**Step 1: Clone Repository**
```bash
cd /var/www
sudo git clone https://github.com/your-repo/edutrack.git
cd edutrack
sudo chown -R www-data:www-data .
```

**Step 2: Install Dependencies**
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

**Step 3: Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate

# Edit .env with your settings:
sudo nano .env
# Important settings:
APP_ENV=production
APP_DEBUG=false
DB_HOST=127.0.0.1
DB_DATABASE=edutrack
DB_USERNAME=edutrack
DB_PASSWORD=secure_password
```

**Step 4: Database Setup**
```bash
sudo mysql -u root -p
# Inside MySQL:
CREATE DATABASE edutrack;
CREATE USER 'edutrack'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON edutrack.* TO 'edutrack'@'localhost';
FLUSH PRIVILEGES;
exit;

# Run migrations:
php artisan migrate --force
```

**Step 5: Configure Web Server**

**For Apache:**
```bash
sudo nano /etc/apache2/sites-available/edutrack.conf
```
Add:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAdmin admin@your-domain.com
    DocumentRoot /var/www/edutrack/public

    <Directory /var/www/edutrack/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/edutrack-error.log
    CustomLog ${APACHE_LOG_DIR}/edutrack-access.log combined
</VirtualHost>
```

**Enable and restart:**
```bash
sudo a2ensite edutrack.conf
sudo a2dissite 000-default.conf
sudo apache2ctl configtest
sudo systemctl restart apache2
```

**For Nginx:**
```bash
sudo nano /etc/nginx/sites-available/edutrack
```
Add:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/edutrack/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }

    error_log /var/log/nginx/edutrack-error.log;
    access_log /var/log/nginx/edutrack-access.log;
}
```

**Enable and restart:**
```bash
sudo ln -s /etc/nginx/sites-available/edutrack /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

**Step 6: Final Setup**
```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/edutrack
sudo chmod -R 755 /var/www/edutrack
sudo chmod -R 777 /var/www/edutrack/storage
sudo chmod -R 777 /var/www/edutrack/bootstrap/cache

# Clear caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Step 7: SSL Certificate (Highly Recommended for Production)**
```bash
sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache -d your-domain.com
```

**Step 8: Access Application**
```
Visit: https://your-domain.com
```

---

### Option C: CPANEL/SHARED HOSTING

**Step 1: Upload Files via FTP/File Manager**
1. Extract project locally or use git
2. Upload to public_html or subdomain folder
3. Upload to folder like `public_html/edutrack`

**Step 2: Create Database**
1. Go to cPanel → Databases → MySQL Databases
2. Create new database: `edutrack`
3. Create new user: `edutrack_user`
4. Assign user to database with ALL privileges

**Step 3: Setup via File Manager**
1. Navigate to `public_html/edutrack`
2. Upload `.env` file or create/edit it:
```
APP_NAME=EduTrack
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com/edutrack
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=edutrack
DB_USERNAME=edutrack_user
DB_PASSWORD=your_password
```

**Step 4: Generate App Key**
1. Go to cPanel → Terminal
2. Navigate to project: `cd public_html/edutrack`
3. Run: `php artisan key:generate`

**Step 5: Run Migrations**
```bash
php artisan migrate --force
```

**Step 6: Update htaccess**
Ensure `.htaccess` in `public` folder is correct:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteCond %{HTTP:Authorization} .
    RewriteRule ^(.*)$ index.php [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [L]
</IfModule>
```

**Step 7: Access Application**
```
https://yourdomain.com/edutrack
```

---

## REQUIREMENTS SUMMARY TABLE

| Component | Windows (Laragon) | Ubuntu/Debian | cPanel |
|-----------|------------------|---------------|--------|
| PHP | 8.1+ | 8.1+ | 8.1+ |
| MySQL | 8.0+ | 8.0+ | 8.0+ |
| Composer | Included | Manual | Via Terminal |
| Node.js | Included | npm install | Via Terminal |
| Web Server | Apache (Laragon) | Apache/Nginx | cPanel |
| RAM | 4GB+ | 1GB+ | Varies |
| Disk Space | 5GB+ | 3GB+ | 2GB+ |
| OS | Windows 10/11 | Ubuntu 20.04+ | Any |

---

## POST-DEPLOYMENT CHECKLIST

### Security
- [ ] Change all default passwords
- [ ] Update administrator credentials
- [ ] Enable HTTPS/SSL certificate
- [ ] Configure firewall rules
- [ ] Enable database backups
- [ ] Set up regular backups (automated)
- [ ] Review .env security (not in git)

### Performance
- [ ] Enable Redis caching (optional)
- [ ] Configure queue jobs (optional)
- [ ] Set up log rotation
- [ ] Monitor server resources
- [ ] Enable gzip compression
- [ ] Browser caching configured

### Monitoring
- [ ] Set up error logging
- [ ] Monitor database size
- [ ] Track user activity
- [ ] Monitor disk space
- [ ] Check PHP error logs regularly

### Maintenance
- [ ] Schedule weekly backups
- [ ] Monthly security updates
- [ ] Monitor Laravel outdated packages
- [ ] Update node packages regularly
- [ ] Review user access logs

---

## TROUBLESHOOTING COMMON ISSUES

### Issue: "Class not found" Error
```bash
php artisan cache:clear
php artisan config:clear
composer dump-autoload
php artisan config:cache
```

### Issue: Database Connection Refused
```bash
# Verify MySQL is running
mysql -u root -p
# Check .env credentials match database setup
# Verify DB_HOST (use 127.0.0.1 not localhost in some cases)
```

### Issue: 404 Not Found on Routes
```bash
# Enable mod_rewrite (Apache)
sudo a2enmod rewrite
sudo systemctl restart apache2

# Clear route cache
php artisan route:clear
php artisan route:cache
```

### Issue: Blank Screen / 500 Error
```bash
# Check storage permissions
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/

# Check PHP error logs
tail -f storage/logs/laravel.log
```

### Issue: npm/Node.js Missing
```bash
# Install Node.js dependencies again
npm install
npm run build
```

---

## QUICK REFERENCE COMMANDS

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
composer update                  # Update packages
```

---

## Support & Resources

- **Laravel Docs:** https://laravel.com/docs/10.x
- **Laravel Community:** https://laracasts.com
- **Laragon Docs:** https://laragon.org/docs/
- **PHP Documentation:** https://www.php.net/docs.php

---

**Last Updated:** February 18, 2026  
**Project:** EduTrack v1.0  
**Status:** Production Ready
