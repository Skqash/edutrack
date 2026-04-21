# Complete Deployment Guide - EduTrack System

## 🚀 Deployment Options

### Option 1: Free Hosting (Best for Testing)
- **Railway.app** - Free tier, easy deployment
- **Render.com** - Free tier with PostgreSQL
- **Fly.io** - Free tier available

### Option 2: Affordable Hosting (Best for Production)
- **DigitalOcean** - $6/month droplet
- **Vultr** - $6/month VPS
- **Linode** - $5/month VPS

### Option 3: Shared Hosting (Easiest)
- **Hostinger** - ~$3/month
- **Namecheap** - ~$3/month
- **A2 Hosting** - ~$3/month

---

## 📋 Pre-Deployment Checklist

### 1. Prepare Your GitHub Repository

**Files to Update Before Pushing:**

#### `.gitignore` (Make sure these are included)
```
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.phpunit.result.cache
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log
/.idea
/.vscode
```

#### Create `.env.example` (Template for production)
```bash
cp .env .env.example
```

Then edit `.env.example` to remove sensitive data:
```env
APP_NAME="EduTrack"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Optimize for Production

Create a deployment script: `deploy.sh`
```bash
#!/bin/bash

echo "🚀 Starting deployment..."

# Pull latest code
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Clear and cache config
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deployment complete!"
```

Make it executable:
```bash
chmod +x deploy.sh
```

---

## 🎯 Method 1: Deploy to Railway.app (Recommended - FREE)

### Step 1: Prepare Your Project

1. **Create `Procfile` in root directory:**
```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

2. **Create `railway.json` in root directory:**
```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "php artisan serve --host=0.0.0.0 --port=$PORT",
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

3. **Create `nixpacks.toml` in root directory:**
```toml
[phases.setup]
nixPkgs = ['php82', 'php82Packages.composer']

[phases.install]
cmds = ['composer install --no-dev --optimize-autoloader']

[phases.build]
cmds = [
    'php artisan config:clear',
    'php artisan cache:clear',
    'php artisan view:clear'
]

[start]
cmd = 'php artisan serve --host=0.0.0.0 --port=$PORT'
```

### Step 2: Deploy to Railway

1. **Go to [Railway.app](https://railway.app)**
2. **Sign up with GitHub**
3. **Click "New Project"**
4. **Select "Deploy from GitHub repo"**
5. **Choose your repository**
6. **Add MySQL Database:**
   - Click "New" → "Database" → "Add MySQL"
   - Railway will auto-create database and provide credentials

7. **Set Environment Variables:**
   Click on your service → Variables → Add these:
   ```
   APP_NAME=EduTrack
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:YOUR_KEY_HERE
   APP_URL=https://your-app.railway.app
   
   DB_CONNECTION=mysql
   DB_HOST=${{MYSQL_HOST}}
   DB_PORT=${{MYSQL_PORT}}
   DB_DATABASE=${{MYSQL_DATABASE}}
   DB_USERNAME=${{MYSQL_USER}}
   DB_PASSWORD=${{MYSQL_PASSWORD}}
   
   SESSION_DRIVER=database
   CACHE_DRIVER=database
   ```

8. **Generate APP_KEY:**
   - Run locally: `php artisan key:generate --show`
   - Copy the output and paste as APP_KEY

9. **Deploy:**
   - Railway will auto-deploy
   - Wait for build to complete
   - Click "Generate Domain" to get your URL

10. **Run Migrations:**
    - Go to your service → Settings → Deploy
    - Add this to "Custom Start Command":
    ```
    php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
    ```

---

## 🎯 Method 2: Deploy to Render.com (FREE)

### Step 1: Prepare Files

1. **Create `render.yaml` in root:**
```yaml
services:
  - type: web
    name: edutrack
    env: php
    buildCommand: |
      composer install --no-dev --optimize-autoloader
      php artisan config:clear
      php artisan cache:clear
      php artisan migrate --force
    startCommand: php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: APP_KEY
        generateValue: true
      - key: DB_CONNECTION
        value: mysql

databases:
  - name: edutrack-db
    databaseName: edutrack
    user: edutrack
```

### Step 2: Deploy

1. **Go to [Render.com](https://render.com)**
2. **Sign up with GitHub**
3. **Click "New +" → "Web Service"**
4. **Connect your GitHub repository**
5. **Configure:**
   - Name: edutrack
   - Environment: PHP
   - Build Command: `composer install --no-dev --optimize-autoloader`
   - Start Command: `php artisan serve --host=0.0.0.0 --port=$PORT`

6. **Add Database:**
   - Click "New +" → "PostgreSQL" (or use external MySQL)
   - Copy connection details

7. **Set Environment Variables** (same as Railway)

8. **Deploy!**

---

## 🎯 Method 3: Deploy to Shared Hosting (cPanel)

### Step 1: Prepare Your Hosting

**Requirements:**
- PHP 8.1 or higher
- MySQL database
- SSH access (optional but recommended)
- Composer installed

### Step 2: Upload Files

**Option A: Via Git (Recommended)**
```bash
# SSH into your server
ssh username@your-server.com

# Navigate to your home directory
cd ~

# Clone your repository
git clone https://github.com/yourusername/your-repo.git

# Move files to public_html
mv your-repo/* public_html/
```

**Option B: Via FTP**
1. Download your GitHub repo as ZIP
2. Extract locally
3. Upload all files EXCEPT `public` folder contents to root
4. Upload `public` folder contents to `public_html`

### Step 3: Configure

1. **Create Database in cPanel:**
   - Go to MySQL Databases
   - Create new database: `username_edutrack`
   - Create user and assign to database
   - Note credentials

2. **Update `.env` file:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_edutrack
DB_USERNAME=username_dbuser
DB_PASSWORD=your_password
```

3. **Set Permissions:**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

4. **Run Setup Commands:**
```bash
cd /home/username/public_html
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

5. **Configure `.htaccess` in public_html:**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

## 🎯 Method 4: Deploy to DigitalOcean (Production Ready)

### Step 1: Create Droplet

1. **Go to [DigitalOcean](https://digitalocean.com)**
2. **Create Droplet:**
   - Choose Ubuntu 22.04 LTS
   - Basic plan: $6/month
   - Choose datacenter region
   - Add SSH key

### Step 2: Install LEMP Stack

```bash
# SSH into your droplet
ssh root@your-droplet-ip

# Update system
apt update && apt upgrade -y

# Install Nginx
apt install nginx -y

# Install MySQL
apt install mysql-server -y
mysql_secure_installation

# Install PHP 8.2
apt install software-properties-common -y
add-apt-repository ppa:ondrej/php -y
apt update
apt install php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Install Git
apt install git -y
```

### Step 3: Deploy Application

```bash
# Create directory
mkdir -p /var/www/edutrack
cd /var/www/edutrack

# Clone repository
git clone https://github.com/yourusername/your-repo.git .

# Install dependencies
composer install --no-dev --optimize-autoloader

# Set permissions
chown -R www-data:www-data /var/www/edutrack
chmod -R 755 /var/www/edutrack
chmod -R 775 /var/www/edutrack/storage
chmod -R 775 /var/www/edutrack/bootstrap/cache
```

### Step 4: Configure Nginx

Create `/etc/nginx/sites-available/edutrack`:
```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/edutrack/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:
```bash
ln -s /etc/nginx/sites-available/edutrack /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx
```

### Step 5: Setup Database

```bash
mysql -u root -p

CREATE DATABASE edutrack;
CREATE USER 'edutrack_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON edutrack.* TO 'edutrack_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 6: Configure Application

```bash
cd /var/www/edutrack
cp .env.example .env
nano .env
```

Update `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edutrack
DB_USERNAME=edutrack_user
DB_PASSWORD=strong_password
```

Run setup:
```bash
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 7: Install SSL (Free with Let's Encrypt)

```bash
apt install certbot python3-certbot-nginx -y
certbot --nginx -d your-domain.com -d www.your-domain.com
```

---

## 🔒 Security Checklist

### 1. Environment Configuration
```bash
# Set proper permissions
chmod 644 .env
chmod -R 755 storage bootstrap/cache

# Disable directory listing
# Add to .htaccess or nginx config
Options -Indexes
```

### 2. Update `.env` for Production
```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

### 3. Enable HTTPS
- Use Let's Encrypt (free)
- Force HTTPS in `.env`:
```env
APP_URL=https://your-domain.com
```

### 4. Database Security
- Use strong passwords
- Limit database user privileges
- Enable MySQL firewall rules

---

## 📊 Post-Deployment Tasks

### 1. Create Admin Account
```bash
php artisan tinker
```
```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@yourdomain.com';
$user->password = Hash::make('SecurePassword123!');
$user->role = 'admin';
$user->status = 'Active';
$user->save();
```

### 2. Seed Initial Data (Optional)
```bash
php artisan db:seed --class=CPSUAccurateSeeder
```

### 3. Test the Application
- Visit your domain
- Try logging in
- Test teacher signup
- Test admin dashboard
- Check all major features

### 4. Setup Monitoring
- Enable Laravel logs
- Setup error tracking (Sentry, Bugsnag)
- Monitor server resources

---

## 🔄 Continuous Deployment

### Setup Auto-Deploy from GitHub

**For Railway/Render:**
- Automatic on git push to main branch

**For VPS (DigitalOcean/Vultr):**

Create webhook script `/var/www/deploy.php`:
```php
<?php
$secret = 'your-webhook-secret';
$payload = file_get_contents('php://input');
$signature = hash_hmac('sha256', $payload, $secret);

if (hash_equals('sha256=' . $signature, $_SERVER['HTTP_X_HUB_SIGNATURE_256'])) {
    shell_exec('cd /var/www/edutrack && ./deploy.sh > /dev/null 2>&1 &');
    echo 'Deployment triggered';
} else {
    http_response_code(403);
    echo 'Invalid signature';
}
```

Add webhook in GitHub:
- Settings → Webhooks → Add webhook
- Payload URL: `https://your-domain.com/deploy.php`
- Content type: `application/json`
- Secret: your-webhook-secret

---

## 🆘 Troubleshooting

### Issue: 500 Internal Server Error
```bash
# Check logs
tail -f storage/logs/laravel.log

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Check permissions
chmod -R 775 storage bootstrap/cache
```

### Issue: Database Connection Failed
```bash
# Test connection
php artisan tinker
DB::connection()->getPdo();

# Check credentials in .env
# Verify database exists
mysql -u username -p
SHOW DATABASES;
```

### Issue: Assets Not Loading
```bash
# Run asset compilation
npm install
npm run build

# Check public folder permissions
chmod -R 755 public
```

---

## 📞 Support Resources

- **Laravel Docs:** https://laravel.com/docs
- **Railway Docs:** https://docs.railway.app
- **DigitalOcean Tutorials:** https://www.digitalocean.com/community/tutorials
- **Stack Overflow:** https://stackoverflow.com/questions/tagged/laravel

---

## ✅ Quick Start Recommendation

**For Beginners:** Use **Railway.app** (free, automatic, easy)
**For Production:** Use **DigitalOcean** ($6/month, full control)
**For Budget:** Use **Shared Hosting** ($3/month, limited)

---

**Need help?** Follow the Railway.app method first - it's the easiest and free!
