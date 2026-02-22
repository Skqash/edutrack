# EduTrack - System Requirements & Setup Guide

## PROJECT INFORMATION
- **Name:** EduTrack
- **Version:** 1.0
- **Type:** Laravel Web Application
- **Framework:** Laravel 10.10+
- **Database:** MySQL
- **Architecture:** MVC (Model-View-Controller)
- **Frontend Framework:** Bootstrap 5, Blade Templating
- **Build Tool:** Vite

---

## MINIMUM SYSTEM REQUIREMENTS

### For Windows Development (Local Machine)

#### Hardware
- **Processor:** 2+ GHz (Intel Core i3 or equivalent)
- **RAM:** 4 GB minimum (8 GB recommended for smooth performance)
- **Disk Space:** 5 GB free space (SSD recommended)
- **Network:** Stable internet connection for initial setup

#### Software
| Component | Minimum Version | Recommended |
|-----------|-----------------|------------|
| Windows OS | Windows 10 | Windows 11 |
| PHP | 8.1 | 8.2 or 8.3 |
| MySQL | 5.7 | 8.0+ |
| Node.js | 16.x | 18.x or 20.x |
| npm | 8.x | 9.x+ |
| Composer | 2.x | Latest 2.x |
| Git | 2.25+ | Latest |
| Apache | 2.4+ | Latest |

---

### For Ubuntu/Debian Server (Production)

#### Hardware (Minimum)
- **Processor:** 1+ GHz multi-core
- **RAM:** 1 GB minimum (2+ GB recommended)
- **Disk Space:** 3 GB free space (10+ GB recommended for production)
- **Network:** Static IP, stable connection

#### Hardware (Recommended)
- **Processor:** 2+ GHz quad-core
- **RAM:** 4+ GB
- **Disk Space:** 20 GB+
- **Network:** Dedicated server or VPS

#### Software
| Component | Version |
|-----------|---------|
| Ubuntu | 20.04 LTS or newer |
| Debian | 11 or newer |
| PHP-FPM | 8.1+ |
| MySQL/MariaDB | 8.0+ |
| Nginx/Apache | Latest |
| Node.js | 18.x+ |

---

### For cPanel/Shared Hosting

#### Requirements (Provided by Host)
- **PHP Version:** 8.1+
- **MySQL Version:** 5.7+ (usually 8.0+)
- **Disk Space:** Minimum 1 GB
- **Bandwidth:** Unlimited or high limit
- **SSH Access:** Yes (recommended)
- **Composer Support:** Yes
- **Node.js Support:** Not always (might need to skip npm build)

---

## INSTALLATION GUIDES

## METHOD 1: WINDOWS WITH LARAGON (EASIEST)

### What is Laragon?
Laragon is a portable development environment that includes PHP, MySQL, Apache, Node.js, and Composer all in one package.

### Installation Steps

#### Step 1: Download & Install Laragon
1. **Download Laragon**
   - Go to: https://laragon.org/download.html
   - Download the "Full" version (includes PHP, MySQL, Node.js, etc.)
   - File size: ~500 MB

2. **Install Laragon**
   - Run the installer
   - Choose installation location (default: `C:\laragon`)
   - Click "Install"
   - Choose default components (all recommended)

3. **First Launch**
   - Double-click Laragon icon on desktop
   - Click "Start" button to start services
   - You'll see services starting:
     - Apache
     - MySQL
     - NodeJS
   - Ensure all show green checkmarks

#### Step 2: Verify Installation
```bash
# Open PowerShell or Command Prompt and run:

php --version
# Expected output: PHP 8.x.x

mysql --version
# Expected output: MySQL 8.x.x

composer --version
# Expected output: Composer version 2.x.x

node --version
# Expected output: v18.x.x or higher

npm --version
# Expected output: 9.x.x or higher
```

#### Step 3: Get EduTrack Project

**Option A: Clone from Git (Recommended if you have Git installed)**
```bash
# 1. Navigate to Laragon www directory
cd C:\laragon\www

# 2. Clone the repository
git clone https://github.com/your-username/edutrack.git
cd edutrack
```

**Option B: Copy Existing Project**
1. Copy your existing `edutrack` folder
2. Paste it into: `C:\laragon\www\`

**Option C: Start Fresh**
```bash
# Create new Laravel project (alternative):
cd C:\laragon\www
composer create-project laravel/laravel edutrack
```

#### Step 4: Install Dependencies

```bash
# Open PowerShell in project directory or use Laragon terminal:
# Click Menu → Terminal → PowerShell

cd C:\laragon\www\edutrack

# Install PHP dependencies (required)
composer install

# Install Node.js dependencies (required)
npm install
```

Expected output:
- Composer should install 100+ packages
- npm should install 50+ packages

#### Step 5: Create Environment File

```bash
# Copy the environment template
copy .env.example .env

# Generate application key
php artisan key:generate
```

#### Step 6: Edit .env File

Open `C:\laragon\www\edutrack\.env` with a text editor (Notepad++ recommended):

```dotenv
APP_NAME=EduTrack
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxx (auto-generated)
APP_DEBUG=true
APP_URL=http://localhost/edutrack

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edutrack
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

**Important Notes:**
- DB_PASSWORD is empty by default in Laragon
- If you set a MySQL password, update DB_PASSWORD field

#### Step 7: Setup Database

```bash
# Option 1: Using Laragon UI
# 1. Click Laragon menu
# 2. Click "MySQL Admin"
# 3. In phpMyAdmin, click "New Database"
# 4. Name: edutrack
# 5. Collation: utf8mb4_unicode_ci
# 6. Click Create

# Option 2: Using Command Line
mysql -u root -e "CREATE DATABASE IF NOT EXISTS edutrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

#### Step 8: Run Migrations

```bash
# Inside project directory (C:\laragon\www\edutrack)
php artisan migrate

# You should see output like:
# Migrated: 2014_10_12_000000_create_users_table
# Migrated: 2014_10_12_100000_create_password_reset_tokens_table
# ... (many more migrations)
```

#### Step 9: Access the Application

**Option 1: Quick Access (via Laragon)**
1. Right-click Laragon icon → Click "edutrack"
2. Browser should open to: `http://localhost/edutrack`

**Option 2: Manual Access**
1. Open browser
2. Go to: `http://localhost/edutrack`

**Option 3: Development Server**
```bash
# In terminal at C:\laragon\www\edutrack
php artisan serve
# Visit: http://127.0.0.1:8000
```

#### Step 10: Default Login

```
Email: admin@example.com
Password: password
```

(Update credentials after first login or modify in database)

---

## METHOD 2: UBUNTU/DEBIAN SERVER (PRODUCTION)

### Prerequisites
- Root or sudo access
- Ubuntu 20.04 LTS or Debian 11+
- VPS or dedicated server

### Installation Steps

#### Step 1: Update System
```bash
sudo apt update
sudo apt upgrade -y
sudo apt autoremove -y
```

Expected time: 5-10 minutes

#### Step 2: Install System Dependencies

```bash
# Install required packages in one command
sudo apt install -y curl wget git zip unzip build-essential \
    software-properties-common apt-transport-https ca-certificates \
    gnupg lsb-release ubuntu-keyring
```

#### Step 3: Install PHP 8.1

```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP and extensions
sudo apt install -y php8.1 php8.1-cli php8.1-fpm \
    php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl \
    php8.1-zip php8.1-gd php8.1-bcmath php8.1-imagick

# Verify installation
php --version

# Expected: PHP 8.1.x (cli)
```

#### Step 4: Install MySQL 8.0

```bash
# Install MySQL
sudo apt install -y mysql-server

# Secure MySQL installation
sudo mysql_secure_installation
# When prompted:
# - Remove anonymous users? Y
# - Disable root login remotely? Y
# - Remove test database? Y
# - Reload privileges? Y

# Verify MySQL
mysql --version
```

#### Step 5: Install Apache or Nginx

**Option A: Apache (Simpler)**
```bash
sudo apt install -y apache2 libapache2-mod-php8.1

# Enable required modules
sudo a2enmod php8.1
sudo a2enmod rewrite
sudo a2enmod headers

# Verify
sudo apache2ctl -v
```

**Option B: Nginx (Better Performance)**
```bash
sudo apt install -y nginx php8.1-fpm

# Enable service
sudo systemctl enable nginx
sudo systemctl start nginx

# Verify
sudo nginx -v
```

#### Step 6: Install Node.js & Composer

```bash
# Install Node.js (LTS version)
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Verify
php -v
node -v
npm -v
composer -v
```

#### Step 7: Clone EduTrack Project

```bash
# Create web root directory
sudo mkdir -p /var/www/edutrack
cd /var/www/edutrack

# Clone repository
sudo git clone https://github.com/your-username/edutrack.git .

# Change ownership to web user
sudo chown -R www-data:www-data /var/www/edutrack
```

#### Step 8: Install Dependencies

```bash
cd /var/www/edutrack

# Install Composer packages
sudo -u www-data composer install --no-dev --optimize-autoloader

# Install Node packages
sudo -u www-data npm install --production

# Build assets
sudo -u www-data npm run build
```

#### Step 9: Setup Environment

```bash
# Copy environment file
sudo cp .env.example .env

# Edit .env file
sudo nano .env
```

Set the following values:
```
APP_NAME=EduTrack
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edutrack
DB_USERNAME=edutrack_user
DB_PASSWORD=strong_password_here
```

#### Step 10: Generate App Key

```bash
sudo -u www-data php artisan key:generate
```

#### Step 11: Setup Database

```bash
# Login to MySQL
mysql -u root -p

# Run these commands in MySQL:
CREATE DATABASE edutrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'edutrack_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON edutrack.* TO 'edutrack_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
cd /var/www/edutrack
sudo -u www-data php artisan migrate --force
```

#### Step 12: Configure Web Server

**For Apache:**
```bash
# Create virtual host configuration
sudo nano /etc/apache2/sites-available/edutrack.conf
```

Add this content:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    ServerAdmin admin@your-domain.com
    
    DocumentRoot /var/www/edutrack/public
    
    <Directory /var/www/edutrack/public>
        AllowOverride All
        Require all granted
        
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^ index.php [QSA,L]
        </IfModule>
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/edutrack-error.log
    CustomLog ${APACHE_LOG_DIR}/edutrack-access.log combined
</VirtualHost>
```

```bash
# Enable site
sudo a2ensite edutrack.conf
sudo a2dissite 000-default.conf

# Test configuration
sudo apache2ctl configtest
# Should output: Syntax OK

# Restart Apache
sudo systemctl restart apache2
```

**For Nginx:**
```bash
# Create configuration file
sudo nano /etc/nginx/sites-available/edutrack
```

Add this content:
```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/edutrack/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }

    error_log /var/log/nginx/edutrack-error.log;
    access_log /var/log/nginx/edutrack-access.log;
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/edutrack /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
```

#### Step 13: Set Proper Permissions

```bash
# Set directory permissions
sudo chown -R www-data:www-data /var/www/edutrack
sudo find /var/www/edutrack -type f -exec chmod 644 {} \;
sudo find /var/www/edutrack -type d -exec chmod 755 {} \;
sudo chmod -R 777 /var/www/edutrack/storage
sudo chmod -R 777 /var/www/edutrack/bootstrap/cache
```

#### Step 14: Setup SSL Certificate (Optional but Recommended)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-apache
# OR for Nginx:
sudo apt install -y certbot python3-certbot-nginx

# Get certificate
sudo certbot --apache -d your-domain.com -d www.your-domain.com
# OR for Nginx:
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Auto-renewal test
sudo certbot renew --dry-run
```

#### Step 15: Final Setup

```bash
cd /var/www/edutrack

# Clear caches
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Create symbolic link for storage
sudo -u www-data php artisan storage:link
```

#### Step 16: Access Application

```
Visit: https://your-domain.com
```

---

## METHOD 3: CPANEL/SHARED HOSTING

### Prerequisites
- Active cPanel/Hosting account
- FTP/File Manager access
- MySQL database creation capability
- SSH access (optional but recommended)

### Installation Steps

#### Step 1: Prepare Local Environment
```bash
# On your local machine, prepare files:
cd C:\laragon\www\edutrack

# Build frontend assets
npm run build

# Optimize for production
composer install --no-dev --optimize-autoloader
```

#### Step 2: Create cPanel Database

1. **Login to cPanel**
2. Navigate to: **Databases → MySQL Databases**
3. Create new database:
   - Database Name: `username_edutrack`
   - Click "Create Database"
4. Create new user:
   - User Name: `username_edu` (max 8 chars)
   - Password: (generate strong password)
   - Click "Create User"
5. Add user to database:
   - Select both from dropdowns
   - Check all privileges
   - Click "Add"

#### Step 3: Upload Files via FTP

1. **Connection Details (from cPanel)**
   - Host: `ftphost.com` (from cPanel)
   - Username: `your_cpanel_user`
   - Password: your FTP password
   - Port: 21

2. **Using FileZilla or similar FTP client:**
   - Connect to hosting
   - Navigate to: `public_html`
   - Upload entire `edutrack` folder
   - Or upload to subdirectory like `public_html/edutrack`

3. **Set Permissions**
   - Right-click `storage` folder → File Attributes → Set to 777
   - Right-click `bootstrap/cache` folder → Set to 777

#### Step 4: Setup Environment File

1. **Via File Manager in cPanel:**
   - Navigate to edutrack folder
   - Right-click → Create new file: `.env`
   - Add content (see Step 5 below)

2. **Or via FTP:**
   - Upload prepared `.env` file

#### Step 5: Configure .env for cPanel

Edit `.env` file with following values:

```dotenv
APP_NAME=EduTrack
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com/edutrack
# OR: https://yourdomain.com (if installing in root)

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_edutrack
DB_USERNAME=username_edu
DB_PASSWORD=your_generated_password

LOG_CHANNEL=stack
SESSION_DRIVER=files
CACHE_DRIVER=file
```

#### Step 6: Generate App Key

1. **Via cPanel Terminal (if available):**
```bash
cd ~/public_html/edutrack
php artisan key:generate
```

2. **If no terminal access:**
   - Use online key generator to create a base64 key
   - Manually add to .env: `APP_KEY=base64:your_generated_key`

#### Step 7: Run Database Migrations

Terminal method (recommended):
```bash
cd ~/public_html/edutrack
php artisan migrate --force
```

#### Step 8: Verify htaccess

Ensure `.htaccess` in `public` folder contains:
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

#### Step 9: Final Optimization (Optional via Terminal)

```bash
cd ~/public_html/edutrack
composer dump-autoload
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Step 10: Access Application

```
https://yourdomain.com/edutrack
```

Default login:
```
Email: admin@example.com
Password: password
```

---

## PORT REFERENCES

| Service | Default Port | Usage |
|---------|------------|-------|
| HTTP | 80 | Web traffic |
| HTTPS | 443 | Secure web |
| MySQL | 3306 | Database |
| Apache | 80 (HTTP) | Web server |
| Nginx | 80 (HTTP) | Web server |
| PHP Dev Server | 8000 | Local development |
| Node Dev | 5173 | Vite dev server |

---

## QUICK DIAGNOSTICS

### Check PHP Extensions
```bash
php -m
# Should include: mysql, mbstring, xml, curl, zip, gd
```

### Test Database Connection
```bash
# From project directory:
php artisan tinker
# In tinker:
DB::connection()->getPDO();
# Should return PDO object (no error)
exit
```

### Test File Permissions
```bash
ls -la storage/
ls -la bootstrap/cache/
# Both should be writable by web user
```

### Test Web Server
```bash
# Check if running
ps aux | grep apache
ps aux | grep nginx
ps aux | grep mysql
# Should show running processes
```

---

## BACKUP & RECOVERY

### Backup Database
```bash
# MySQL dump
mysqldump -u root -p edutrack > backup_$(date +%Y%m%d_%H%M%S).sql

```

### Restore Database
```bash
mysql -u root -p edutrack < backup_filename.sql
```

### Backup Project Files
```bash
# Tar archive
tar -czf edutrack_backup_$(date +%Y%m%d).tar.gz /var/www/edutrack

# Or zip
zip -r edutrack_backup_$(date +%Y%m%d).zip /var/www/edutrack
```

---

## TESTING CHECKLIST

- [ ] PHP version correct (8.1+)
- [ ] MySQL running and accessible
- [ ] All PHP extensions installed
- [ ] Composer installed and working
- [ ] Node.js and npm installed
- [ ] Project files in correct location
- [ ] Dependencies installed (composer install)
- [ ] Node packages installed (npm install)
- [ ] .env file configured
- [ ] App key generated
- [ ] Database created
- [ ] Migrations run
- [ ] Storage permissions correct
- [ ] Web server configured
- [ ] Can access application via browser
- [ ] Can login with default credentials
- [ ] Routes working (no 404 errors)
- [ ] Database queries working
- [ ] Assets loading (CSS, JS, images)

---

**For detailed deployment instructions, see DEPLOYMENT_GUIDE.md**

Last Updated: February 18, 2026
