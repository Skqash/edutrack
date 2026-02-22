# EduTrack - Deployment Checklist

Use this checklist to track your deployment progress.

---

## PRE-DEPLOYMENT (Do Before You Start)

- [ ] Backup your current system/database
- [ ] Test all code locally first
- [ ] Verify all files are ready for deployment
- [ ] Confirm target system specifications match requirements
- [ ] Have all credentials ready (FTP, SSH, MySQL)
- [ ] Plan maintenance window if updating existing installation
- [ ] Document current configuration

---

## WINDOWS/LARAGON DEPLOYMENT

### Installation Phase
- [ ] Downloaded Laragon from https://laragon.org
- [ ] Installed Laragon to C:\laragon
- [ ] Started Laragon services (Apache, MySQL, Node.js)
- [ ] Verified PHP version (php --version)
- [ ] Verified MySQL version (mysql --version)
- [ ] Verified Node.js (node --version)
- [ ] Verified npm (npm --version)
- [ ] Verified Composer (composer --version)

### Project Setup
- [ ] Copied/cloned EduTrack to C:\laragon\www\edutrack
- [ ] Opened terminal in project directory
- [ ] Ran: `composer install`
- [ ] Ran: `npm install`
- [ ] Copied .env.example to .env
- [ ] Ran: `php artisan key:generate`
- [ ] Created database: `edutrack`
- [ ] Updated DB_DATABASE in .env to `edutrack`
- [ ] Set DB_PASSWORD (empty for Laragon default)

### Database & Migrations
- [ ] Created MySQL database via phpMyAdmin or CLI
- [ ] Ran: `php artisan migrate`
- [ ] Verified all migrations completed successfully
- [ ] Database tables created (check in phpMyAdmin)

### Testing
- [ ] Ran: `php artisan serve` OR clicked Laragon's edutrack link
- [ ] Accessed: http://localhost/edutrack
- [ ] Verified login page loads
- [ ] Logged in with admin@example.com / password
- [ ] Checked all main pages load without errors
- [ ] Verified student list shows
- [ ] Verified courses page loads
- [ ] Verified grading system works
- [ ] Checked dashboard stats display correctly
- [ ] Tested Add/Edit/Delete functions

### Final Steps
- [ ] Ran: `php artisan cache:clear`
- [ ] Ran: `php artisan route:cache`
- [ ] Ran: `php artisan config:cache`
- [ ] Ran: `php artisan view:cache`

---

## UBUNTU/DEBIAN SERVER DEPLOYMENT

### System Preparation
- [ ] SSH access verified to server
- [ ] Ran: `sudo apt update` and `sudo apt upgrade`
- [ ] Installed build-essential and dependencies

### PHP Installation
- [ ] Added PHP repository: `sudo add-apt-repository ppa:ondrej/php`
- [ ] Installed PHP 8.3 and extensions
- [ ] Verified PHP: `php --version`
- [ ] Enabled PHP modules if using Apache

### MySQL Installation
- [ ] Installed MySQL: `sudo apt install mysql-server`
- [ ] Ran: `sudo mysql_secure_installation`
- [ ] Verified MySQL: `mysql --version`
- [ ] MySQL service running: `sudo systemctl status mysql`

### Web Server Configuration
**For Apache:**
- [ ] Installed: `sudo apt install apache2 libapache2-mod-php8.3`
- [ ] Enabled modules: `a2enmod rewrite`, `a2enmod headers`
- [ ] Created virtual host config
- [ ] Enabled site: `a2ensite edutrack.conf`
- [ ] Tested config: `apache2ctl configtest` (returns "Syntax OK")
- [ ] Restarted Apache: `sudo systemctl restart apache2`

**For Nginx:**
- [ ] Installed: `sudo apt install nginx php8.3-fpm`
- [ ] Created Nginx config in `/etc/nginx/sites-available/`
- [ ] Created symlink to `sites-enabled/`
- [ ] Tested: `sudo nginx -t`
- [ ] Restarted Nginx: `sudo systemctl restart nginx`

### Additional Tools
- [ ] Installed Node.js: `curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -`
- [ ] Installed Composer globally
- [ ] Verified: `composer --version`

### Project Deployment
- [ ] Created `/var/www/edutrack` directory
- [ ] Cloned/copied EduTrack files
- [ ] Set ownership: `sudo chown -R www-data:www-data /var/www/edutrack`
- [ ] Installed dependencies: `sudo -u www-data composer install --no-dev`
- [ ] Installed Node packages: `sudo -u www-data npm install --production`
- [ ] Built assets: `sudo -u www-data npm run build`

### Database Setup
- [ ] Created MySQL user with proper privileges
- [ ] Created `edutrack` database
- [ ] Updated .env with DB connection details
- [ ] Ran migrations: `php artisan migrate --force`
- [ ] Verified tables in database

### Permissions & Security
- [ ] Set storage permissions: `chmod -R 777 storage/`
- [ ] Set bootstrap cache permissions: `chmod -R 777 bootstrap/cache/`
- [ ] Set proper file permissions: `find . -type f -exec chmod 644 {} \;`
- [ ] Set proper directory permissions: `find . -type d -exec chmod 755 {} \;`
- [ ] Created storage link: `php artisan storage:link`

### SSL Certificate (Production)
- [ ] Installed Certbot: `sudo apt install certbot`
- [ ] Installed Certbot plugin (Apache or Nginx)
- [ ] Obtained certificate: `sudo certbot --apache -d yourdomain.com`
- [ ] Set auto-renewal: `sudo certbot renew --dry-run`
- [ ] Verified HTTPS access

### Final Optimization
- [ ] Generated .env key (in production)
- [ ] Ran: `php artisan config:cache`
- [ ] Ran: `php artisan route:cache`
- [ ] Ran: `php artisan view:cache`
- [ ] Set APP_DEBUG=false in .env
- [ ] Set APP_ENV=production in .env

### Monitoring & Testing
- [ ] Application accessible via domain
- [ ] HTTPS working (if configured)
- [ ] Login functionality working
- [ ] Database operations working
- [ ] File uploads working
- [ ] Email notifications working (if configured)
- [ ] Cron jobs configured (if needed)

---

## CPANEL/SHARED HOSTING DEPLOYMENT

### Pre-Upload
- [ ] Ran: `npm run build` locally
- [ ] Ran: `composer install --no-dev --optimize-autoloader` locally
- [ ] Prepared files for upload

### cPanel Configuration
- [ ] Logged into cPanel
- [ ] Created MySQL database via cPanel
- [ ] Created MySQL user with all privileges
- [ ] Recorded database name, username, password

### File Upload
- [ ] Connected via FTP (FileZilla or similar)
- [ ] Uploaded EduTrack files to public_html (or subdirectory)
- [ ] Verified all files uploaded correctly
- [ ] Set permissions on storage: chmod 777
- [ ] Set permissions on bootstrap/cache: chmod 777

### Application Configuration
- [ ] Created .env file in project root
- [ ] Set APP_ENV=production
- [ ] Set APP_DEBUG=false
- [ ] Set APP_URL to correct domain
- [ ] Set DB_HOST=localhost
- [ ] Set DB_DATABASE to cPanel database name
- [ ] Set DB_USERNAME to cPanel user
- [ ] Set DB_PASSWORD to cPanel password
- [ ] Generated APP_KEY (if not auto-generated)

### Database Setup
- [ ] Connected to cPanel Terminal (or SSH)
- [ ] Navigated to project directory
- [ ] Ran: `php artisan migrate --force`
- [ ] Verified tables created in database

### .htaccess Configuration
- [ ] Verified .htaccess exists in public folder
- [ ] Confirmed rewrite rules are correct
- [ ] Tested routing (e.g., /dashboard loads without error)

### Testing
- [ ] Accessed: https://yourdomain.com/edutrack
- [ ] Verified homepage loads
- [ ] Tested login functionality
- [ ] Verified database connection (no errors)
- [ ] Tested file uploads
- [ ] Tested all main features

### Final Steps
- [ ] Ran: `php artisan config:cache`
- [ ] Ran: `php artisan route:cache`
- [ ] Ran: `php artisan view:cache`
- [ ] Set proper file permissions (644 for files, 755 for dirs)

---

## POST-DEPLOYMENT (All Environments)

### Immediate Actions
- [ ] Tested all routes (no 404 errors)
- [ ] Verified login works
- [ ] Checked database is accessible
- [ ] Confirmed assets load (CSS, JS, images)
- [ ] Tested file upload functionality
- [ ] Verified email sending (if configured)

### Security
- [ ] Set APP_DEBUG=false
- [ ] Set APP_ENV=production
- [ ] Updated default passwords
- [ ] Configured SSL/HTTPS
- [ ] Set secure cookie settings
- [ ] Enabled CORS if needed
- [ ] Configured rate limiting

### Data
- [ ] Imported production data (if needed)
- [ ] Verified data integrity
- [ ] Backed up production database
- [ ] Set up automated backups
- [ ] Tested backup restoration

### Monitoring
- [ ] Set up error logging
- [ ] Configured activity logging
- [ ] Set up performance monitoring
- [ ] Created monitoring dashboard
- [ ] Set up alerts for errors

### Documentation
- [ ] Documented deployment details
- [ ] Recorded credentials (secure location)
- [ ] Created runbook for common tasks
- [ ] Documented customizations made
- [ ] Updated README with deployment info

---

## TROUBLESHOOTING QUICK REFERENCE

### Application Won't Load
- [ ] Check browser console for errors (F12)
- [ ] Check Laravel logs: `storage/logs/laravel.log`
- [ ] Verify .env file exists and is readable
- [ ] Verify APP_KEY is set
- [ ] Check database connection: `php artisan tinker` then `DB::connection()->getPDO();`

### Database Connection Failed
- [ ] Verify MySQL is running
- [ ] Check .env DB_* settings
- [ ] Verify MySQL user has correct privileges
- [ ] Test MySQL directly: `mysql -u user -p database`
- [ ] Check MySQL port (usually 3306)

### Permission Denied Errors
- [ ] Fix storage permissions: `chmod -R 777 storage/`
- [ ] Fix cache permissions: `chmod -R 777 bootstrap/cache/`
- [ ] Verify web server user owns files
- [ ] Check file ownership: `ls -la`

### Assets Not Loading (CSS/JS)
- [ ] Verify Vite build ran: `npm run build`
- [ ] Check public/build directory exists
- [ ] Clear browser cache (Ctrl+F5)
- [ ] Verify APP_URL matches domain
- [ ] Check for 404 in browser console

### 500 Server Error
- [ ] Check error logs: `storage/logs/laravel.log`
- [ ] Verify PHP extensions installed (mbstring, xml, etc.)
- [ ] Check file permissions (especially storage/)
- [ ] Verify .env file readable
- [ ] Run: `php artisan config:cache`

### Routes Not Working
- [ ] Clear route cache: `php artisan route:clear`
- [ ] Rebuild route cache: `php artisan route:cache`
- [ ] Verify .htaccess rewrite enabled (Apache)
- [ ] Check Nginx rewrite rules
- [ ] Test with `php artisan serve`

### Slow Performance
- [ ] Enable query caching: check .env CACHE_DRIVER
- [ ] Rebuild caches: `php artisan config:cache`
- [ ] Check database indexes
- [ ] Monitor server resources (CPU, RAM, Disk)
- [ ] Enable Laravel Debugbar locally to profile queries

---

## HELPFUL COMMANDS REFERENCE

### Cache Management
```bash
php artisan cache:clear          # Clear application cache
php artisan config:cache        # Cache configuration
php artisan route:cache         # Cache routes
php artisan view:cache          # Cache views
php artisan cache:clear         # Clear all caches
```

### Database
```bash
php artisan migrate             # Run migrations
php artisan migrate --force     # Force migrations (production)
php artisan migrate:rollback    # Rollback last batch
php artisan db:seed            # Run seeders
```

### Development
```bash
php artisan serve              # Start dev server
php artisan tinker             # Interactive shell
php artisan make:model User    # Create model
php artisan make:controller UserController # Create controller
```

### Maintenance
```bash
php artisan down               # Maintenance mode ON
php artisan up                 # Maintenance mode OFF
```

---

## SUPPORT & DOCUMENTATION

- **Laravel Documentation:** https://laravel.com/docs
- **Deployment Guide:** See DEPLOYMENT_GUIDE.md
- **System Requirements:** See SYSTEM_REQUIREMENTS.md (this file)
- **Laravel Community:** https://laracasts.com

---

**Last Updated:** February 18, 2026  
**Version:** 1.0

---

## SIGN-OFF

Once all checkboxes are complete:

- [x] Deployment is complete
- [x] All systems tested and verified
- [x] Ready for production use

**Deployed By:** ________________  
**Date:** ________________  
**Notes:** _______________________________________________

