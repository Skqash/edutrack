# EduTrack GitHub Deployment Guide

## 🚀 Complete Guide to Deploying EduTrack Using GitHub

This guide will teach you how to deploy your EduTrack Laravel application using GitHub for version control, collaboration, and automated deployment.

---

## 📋 Table of Contents

1. [System Analysis](#system-analysis)
2. [GitHub Setup](#github-setup)
3. [Local Repository Setup](#local-repository-setup)
4. [Deployment Strategies](#deployment-strategies)
5. [CI/CD Pipeline Setup](#cicd-pipeline-setup)
6. [Production Deployment](#production-deployment)
7. [Maintenance & Updates](#maintenance--updates)

---

## 🔍 System Analysis

### Your EduTrack Application Stack
- **Framework**: Laravel 10.10+
- **PHP Version**: 8.1+
- **Database**: MySQL 8.0+
- **Frontend**: Bootstrap 5 + Blade Templates
- **Build Tools**: Vite
- **Additional Packages**: DOMPDF for PDF generation

### Key Features
- Teacher dashboard and grading system
- Attendance management
- Student management
- Grade entry with KSA (Knowledge, Skills, Attitude) system
- PDF report generation
- Multi-user authentication (Admin, Teacher, Student)

### Current Project Structure
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
├── vendor/             # Composer dependencies
├── .env.example        # Environment template
├── composer.json       # PHP dependencies
├── package.json        # Node dependencies
└── vite.config.js      # Vite configuration
```

---

## 🐙 GitHub Setup

### Step 1: Create GitHub Account
1. Go to [GitHub.com](https://github.com)
2. Sign up for a new account or log in
3. Verify your email address

### Step 2: Create New Repository
1. Click the **+** button in the top right corner
2. Select **"New repository"**
3. Fill in repository details:
   - **Repository name**: `edutrack`
   - **Description**: `EduTrack - Academic Management System`
   - **Visibility**: Choose Private (for sensitive data) or Public
   - **Initialize with README**: ✅ Check this
   - **Add .gitignore**: Select `Laravel`
   - **Choose a license**: MIT License

### Step 3: Configure SSH Keys (Recommended)
```bash
# Generate SSH key
ssh-keygen -t ed25519 -C "your-email@example.com"

# Start SSH agent
eval "$(ssh-agent -s)"

# Add SSH key to agent
ssh-add ~/.ssh/id_ed25519

# Copy public key to clipboard
cat ~/.ssh/id_ed25519.pub
```

Then add the SSH key to GitHub:
1. Go to GitHub → Settings → SSH and GPG keys
2. Click "New SSH key"
3. Paste your public key
4. Test connection: `ssh -T git@github.com`

---

## 💻 Local Repository Setup

### Step 1: Initialize Git in Your Project
```bash
# Navigate to your project directory
cd c:\laragon\www\edutrack

# Initialize git repository
git init

# Add remote repository
git remote add origin git@github.com:your-username/edutrack.git

# Set default branch
git branch -M main
```

### Step 2: Create .gitignore File
Your project should already have a good `.gitignore`, but let's verify:

```gitignore
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log
.DS_Store
Thumbs.db
```

### Step 3: Prepare Environment Files
```bash
# Ensure .env.example is complete
cp .env.example .env.example.backup

# Remove sensitive data from .env.example
# Keep only structure, no real passwords/keys
```

### Step 4: First Commit
```bash
# Add all files
git add .

# Make initial commit
git commit -m "Initial commit: EduTrack Academic Management System

- Laravel 10 application with teacher dashboard
- Student management and grading system
- Attendance tracking
- KSA grading methodology
- PDF report generation
- Multi-role authentication system"

# Push to GitHub
git push -u origin main
```

---

## 🚀 Deployment Strategies

### Strategy 1: GitHub Actions + DigitalOcean (Recommended)
**Best for**: Production applications with automated deployment

### Strategy 2: GitHub Actions + Shared Hosting
**Best for**: Budget-friendly deployment with basic automation

### Strategy 3: Manual Deployment via GitHub
**Best for**: Learning and small projects

### Strategy 4: GitHub Pages (Frontend Only)
**Not suitable**: This is a full-stack Laravel application

---

## 🔄 CI/CD Pipeline Setup

### Step 1: Create GitHub Actions Workflow
Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy EduTrack

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: edutrack_test
        ports:
          - 3306:3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: mbstring, xml, mysql, bcmath, curl, zip, gd
        coverage: none
    
    - name: Copy environment file
      run: cp .env.example .env
    
    - name: Install Composer dependencies
      run: composer install --no-progress --no-interaction --prefer-dist
    
    - name: Generate application key
      run: php artisan key:generate
    
    - name: Set up database
      run: |
        php artisan migrate:fresh --force
        php artisan db:seed --force
    
    - name: Run tests
      run: php artisan test

  deploy:
    needs: test
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: mbstring, xml, mysql, bcmath, curl, zip, gd
    
    - name: Install Composer dependencies
      run: composer install --no-dev --optimize-autoloader
    
    - name: Install Node dependencies
      uses: actions/setup-node@v3
      with:
        node-version: '18'
    - run: npm ci
    - run: npm run build
    
    - name: Deploy to server
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.SSH_KEY }}
        script: |
          cd /var/www/edutrack
          git pull origin main
          composer install --no-dev --optimize-autoloader
          npm install
          npm run build
          php artisan migrate --force
          php artisan cache:clear
          php artisan config:cache
          php artisan route:cache
          php artisan view:cache
          sudo chown -R www-data:www-data .
          sudo chmod -R 755 .
          sudo chmod -R 777 storage bootstrap/cache
          sudo systemctl restart apache2
```

### Step 2: Add GitHub Secrets
Go to your GitHub repository → Settings → Secrets and variables → Actions → New repository secret:

```
HOST: your-server-ip
USERNAME: your-server-username
SSH_KEY: -----BEGIN OPENSSH PRIVATE KEY-----
(your private key content)
-----END OPENSSH PRIVATE KEY-----
```

### Step 3: Create Production Environment File
Create `.env.production` (don't commit this to Git):

```env
APP_NAME=EduTrack
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edutrack
DB_USERNAME=edutrack_user
DB_PASSWORD=secure_database_password

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-server
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🌐 Production Deployment

### Option 1: DigitalOcean Droplet (Recommended)

#### Server Setup
```bash
# Create DigitalOcean Droplet with:
# - Ubuntu 22.04 LTS
# - 2GB RAM minimum
# - 25GB SSD minimum
# - SSH key added

# SSH into your server
ssh root@your-server-ip

# Update system
apt update && apt upgrade -y

# Install required software
apt install -y software-properties-common
add-apt-repository -y ppa:ondrej/php
apt update

# Install PHP 8.1 and extensions
apt install -y php8.1 php8.1-fpm php8.1-mysql php8.1-mbstring \
  php8.1-xml php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath

# Install Nginx
apt install -y nginx

# Install MySQL
apt install -y mysql-server
mysql_secure_installation

# Install Redis
apt install -y redis-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt install -y nodejs

# Install Git
apt install -y git
```

#### Database Setup
```bash
# Login to MySQL
mysql -u root -p

# Create database and user
CREATE DATABASE edutrack;
CREATE USER 'edutrack'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON edutrack.* TO 'edutrack'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Nginx Configuration
Create `/etc/nginx/sites-available/edutrack`:

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/edutrack/public;
    index index.php index.html;

    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss;

    # Browser caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable the site:
```bash
ln -s /etc/nginx/sites-available/edutrack /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default
nginx -t
systemctl restart nginx
```

#### SSL Certificate
```bash
# Install Certbot
apt install -y certbot python3-certbot-nginx

# Get SSL certificate
certbot --nginx -d your-domain.com -d www.your-domain.com

# Set up auto-renewal
echo "0 12 * * * /usr/bin/certbot renew --quiet" | crontab -
```

#### Deploy Application
```bash
# Create deployment directory
mkdir -p /var/www/edutrack
cd /var/www/edutrack

# Clone repository
git clone https://github.com/your-username/edutrack.git .

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Setup environment
cp .env.example .env
php artisan key:generate

# Edit .env file with production values
nano .env

# Run migrations
php artisan migrate --force

# Set permissions
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 777 storage bootstrap/cache

# Create storage link
php artisan storage:link

# Cache configuration
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Option 2: Shared Hosting with cPanel

#### Preparation
```bash
# On your local machine, create deployment package
cd c:\laragon\www\edutrack

# Install dependencies
composer install --no-dev
npm install
npm run build

# Create deployment zip
zip -r edutrack-deploy.zip . -x ".git/*" "node_modules/*" "storage/logs/*" ".env"
```

#### cPanel Deployment
1. Upload `edutrack-deploy.zip` to cPanel File Manager
2. Extract to `public_html/edutrack/`
3. Create MySQL database via cPanel
4. Edit `.env` file with database credentials
5. Use cPanel Terminal to run:
   ```bash
   cd public_html/edutrack
   php artisan key:generate
   php artisan migrate --force
   php artisan storage:link
   ```

---

## 🔄 Maintenance & Updates

### Daily Maintenance Tasks
```bash
# Clear caches
php artisan cache:clear
php artisan view:clear

# Backup database
mysqldump -u edutrack -p edutrack > backup_$(date +%Y%m%d).sql

# Check logs
tail -f storage/logs/laravel.log
```

### Weekly Maintenance
```bash
# Update dependencies
composer update
npm update

# Run migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services (Ubuntu)
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm
sudo systemctl restart redis
```

### Monthly Maintenance
```bash
# Security updates
sudo apt update && sudo apt upgrade -y

# Check for Laravel updates
composer outdated

# Clean old logs
find storage/logs -name "*.log" -mtime +30 -delete

# Optimize database
mysql -u root -p -e "OPTIMIZE TABLE edutrack.*;"
```

---

## 📊 Monitoring & Backups

### Setup Monitoring
Create a simple health check route in `routes/web.php`:

```php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
        'cache' => Cache::get('health_check', 'miss'),
    ]);
});
```

### Automated Backups
Create backup script `/home/user/scripts/backup.sh`:

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/user/backups"

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u edutrack -p'password' edutrack > $BACKUP_DIR/db_backup_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz /var/www/edutrack

# Remove old backups (keep 7 days)
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

Add to crontab:
```bash
# Daily at 2 AM
0 2 * * * /home/user/scripts/backup.sh
```

---

## 🚨 Troubleshooting Common Issues

### Issue: Deployment Fails
```bash
# Check GitHub Actions logs
# Verify SSH key permissions
# Check server disk space
df -h

# Check services status
sudo systemctl status nginx
sudo systemctl status php8.1-fpm
sudo systemctl status mysql
```

### Issue: 500 Error
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check permissions
ls -la storage/
ls -la bootstrap/cache/

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Issue: Database Connection
```bash
# Test database connection
mysql -u edutrack -p -h localhost edutrack

# Check MySQL service
sudo systemctl status mysql

# Check .env file
cat .env | grep DB_
```

---

## 📚 Best Practices

### Security
- Use strong passwords and SSH keys
- Keep dependencies updated
- Enable HTTPS everywhere
- Regular security audits
- Use environment variables for secrets

### Performance
- Enable Redis caching
- Use CDN for static assets
- Enable Gzip compression
- Optimize database queries
- Monitor server resources

### Git Workflow
- Use feature branches for new features
- Write descriptive commit messages
- Create pull requests for review
- Tag releases for major versions
- Keep main branch stable

---

## 🎯 Quick Start Checklist

### Pre-Deployment
- [ ] GitHub repository created
- [ ] SSH keys configured
- [ ] .gitignore properly set up
- [ ] Environment files prepared
- [ ] Initial commit pushed

### Server Setup
- [ ] Server provisioned (DigitalOcean/Shared Hosting)
- [ ] LEMP stack installed
- [ ] Database created
- [ ] SSL certificate installed
- [ ] Firewall configured

### Deployment
- [ ] Application cloned
- [ ] Dependencies installed
- [ ] Environment configured
- [ ] Migrations run
- [ ] Caches cleared
- [ ] Permissions set

### Post-Deployment
- [ ] Application tested
- [ ] Monitoring setup
- [ ] Backups configured
- [ ] CI/CD pipeline active
- [ ] Documentation updated

---

## 🆘 Support Resources

### Documentation
- [Laravel Documentation](https://laravel.com/docs/10.x)
- [GitHub Actions Docs](https://docs.github.com/en/actions)
- [DigitalOcean Docs](https://docs.digitalocean.com)
- [Nginx Configuration](https://nginx.org/en/docs/)

### Community
- [Laracasts](https://laracasts.com)
- [Laravel Forums](https://laracasts.com/discuss)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/laravel)
- [GitHub Community](https://github.com/community)

### Emergency Commands
```bash
# Quick rollback
git checkout HEAD~1
php artisan migrate:rollback

# Emergency maintenance mode
php artisan down
php artisan up

# Clear everything
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

---

## 🎉 Conclusion

You now have a complete understanding of how to deploy your EduTrack application using GitHub! This guide covers:

✅ **System Analysis** - Understanding your Laravel application  
✅ **GitHub Setup** - Repository creation and configuration  
✅ **CI/CD Pipeline** - Automated testing and deployment  
✅ **Production Deployment** - Multiple deployment options  
✅ **Maintenance** - Ongoing care and updates  
✅ **Monitoring** - Health checks and backups  
✅ **Troubleshooting** - Common issues and solutions  

Your EduTrack system is now ready for professional deployment with version control, automated workflows, and production-ready infrastructure.

---

**Happy Deploying! 🚀**

*Last Updated: February 23, 2026*  
*Version: 1.0*  
*Author: EduTrack Development Team*
<tool_call>file_path</arg_key>
<arg_value>c:\laragon\www\edutrack\GITHUB_DEPLOYMENT_GUIDE.md
