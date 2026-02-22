# EduTrack - Troubleshooting & FAQ Guide

## Common Issues & Solutions

---

## DATABASE ISSUES

### Problem: "SQLSTATE[HY000] [2002] No connection could be made"

**Symptoms:**
- Application shows database connection error
- Cannot log in
- Database queries fail

**Causes:**
- MySQL service not running
- Wrong database credentials in .env
- Database doesn't exist
- Wrong DB_HOST value

**Solutions:**

1. **Check MySQL is running:**
   ```bash
   # Windows (Laragon)
   # Check if MySQL shows green in Laragon UI
   
   # Linux
   sudo systemctl status mysql
   # or
   sudo service mysql status
   ```

2. **Verify .env credentials:**
   ```
   DB_HOST=127.0.0.1 (or localhost)
   DB_PORT=3306
   DB_DATABASE=edutrack  (must match created database)
   DB_USERNAME=root      (or your username)
   DB_PASSWORD=          (blank for Laragon default)
   ```

3. **Test connection manually:**
   ```bash
   mysql -h 127.0.0.1 -u root -p
   # Enter password when prompted
   # If it connects, you see "mysql>"
   ```

4. **For Windows (Laragon):**
   - Right-click Laragon icon
   - Ensure MySQL shows running (green checkmark)
   - Click "MySQL Admin" to test phpMyAdmin

5. **For Linux:**
   ```bash
   # Restart MySQL
   sudo systemctl restart mysql
   
   # Check MySQL logs
   sudo tail -f /var/log/mysql/error.log
   ```

---

### Problem: "SQLSTATE[HY000]: General error: 1030 Got error"

**Symptoms:**
- Database operation fails
- Tables showing as corrupt

**Causes:**
- Disk space full
- Database file corruption
- Permission issues

**Solutions:**

1. **Check disk space:**
   ```bash
   # Linux
   df -h
   
   # Windows
   # Right-click drive → Properties
   ```

2. **Repair database:**
   ```bash
   # Connect to MySQL
   mysql -u root -p
   
   # Select database
   USE edutrack;
   
   # Repair table
   REPAIR TABLE courses;
   REPAIR TABLE students;
   # Run for all affected tables
   ```

3. **Check permissions:**
   ```bash
   # Linux
   sudo chown -R mysql:mysql /var/lib/mysql/
   sudo chmod 750 /var/lib/mysql/
   ```

---

### Problem: "SQLSTATE[42S02]: Table doesn't exist"

**Symptoms:**
- Specific table error (e.g., "students table doesn't exist")
- Migration errors

**Causes:**
- Migrations not run
- Wrong database selected
- Student typo in query

**Solutions:**

1. **Run migrations:**
   ```bash
   php artisan migrate
   
   # For production (using --force)
   php artisan migrate --force
   ```

2. **Verify database selected:**
   ```bash
   # Check .env
   DB_DATABASE=edutrack  # Should match created database
   ```

3. **Check migrations:**
   ```bash
   php artisan migrate:status
   # Should show all migrations as "Ran"
   
   # If not, run again:
   php artisan migrate
   ```

---

### Problem: "Incorrect integer value" (when saving classes)

**Symptoms:**
- Cannot create or update classes
- Error during class_level assignment
- Database integrity error

**Causes:**
- class_level column expecting integer but getting string
- Data type mismatch

**Solutions:**

1. **Verify fix is applied (TeacherController.php line ~1689):**
   ```php
   // Correct:
   'class_level' => (int) $validated['year'],
   
   // Wrong:
   'class_level' => 'Year ' . $validated['year'],
   ```

2. **Check migration (database/migrations):**
   ```php
   // Should be:
   $table->integer('class_level');
   
   // Not:
   $table->string('class_level');
   ```

3. **Clear cache and retry:**
   ```bash
   php artisan cache:clear
   php artisan config:cache
   ```

---

## RELATIONSHIP & MODEL ISSUES

### Problem: "Undefined property: App\Models\Class::$subject"

**Symptoms:**
- "Call to undefined method" errors
- Dashboard shows relationship error
- Classes page fails to load

**Causes:**
- Using wrong relationship name (subject vs course)
- Wrong model referenced

**Solutions:**

1. **Verify TeacherController relationships are fixed:**
   
   All `.with('subject')` should be `.with('course')`
   
   ```php
   // Correct:
   $classes = ClassModel::with('course')->get();
   
   // Wrong:
   $classes = ClassModel::with('subject')->get();
   ```

2. **Check ClassModel.php for relationships:**
   ```php
   // Should have:
   public function course()
   {
       return $this->belongsTo(Course::class);
   }
   
   // Should NOT have this as the primary relationship:
   public function subject()
   ```

3. **Clear cache:**
   ```bash
   php artisan cache:clear
   php artisan config:cache
   ```

---

### Problem: "Call to undefined relationship [courses]" (plural vs singular)

**Symptoms:**
- Dashboard shows error
- Using .with('courses') instead of .with('course')

**Causes:**
- Wrong method name on model
- Typo in with() clause

**Solutions:**

1. **Check TeacherController line ~146:**
   ```php
   // Correct:
   .with('course')
   
   // Wrong:
   .with('courses')
   ```

2. **Verify ClassModel hasOne relationship (not hasMany):**
   ```php
   // Each class belongs to ONE course:
   public function course()
   {
       return $this->belongsTo(Course::class);
   }
   ```

---

## AUTHENTICATION & PERMISSION ISSUES

### Problem: "This action is unauthorized" or 403 error

**Symptoms:**
- Cannot access certain pages
- Permission denied error
- Blank page or redirect

**Causes:**
- Not logged in
- Wrong user role
- Missing authorization policy
- CSRF token expired

**Solutions:**

1. **Verify you're logged in:**
   - Check if login page appears
   - Try logging in with: `admin@example.com` / `password`

2. **Check user role:**
   ```bash
   php artisan tinker
   
   # Check user role
   $user = User::find(1);
   echo $user->role; # Should be "teacher", "admin", or "super_admin"
   ```

3. **Clear sessions:**
   ```bash
   # Linux
   rm -rf storage/framework/sessions/*
   
   # Windows
   # Delete files in storage\framework\sessions\
   ```

4. **Reset authentication:**
   - Log out
   - Clear browser cookies
   - Log in again

---

### Problem: "CSRF token mismatch"

**Symptoms:**
- Forms don't submit
- Error after form submission
- Redirect to previous page

**Causes:**
- Session expired
- CSRF token not in form
- Middleware issue

**Solutions:**

1. **Verify form has CSRF token:**
   ```blade
   <form method="POST">
       @csrf  <!-- This line must be present -->
       <!-- form fields -->
   </form>
   ```

2. **Clear session cache:**
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```

3. **Restart browser/clear cookies**

---

## FILE & PERMISSION ISSUES

### Problem: "The ... directory must be writable" (Storage or Bootstrap/Cache)

**Symptoms:**
- Cannot write logs
- Cache not working
- View cache errors
- File upload fails

**Causes:**
- Permissions too restrictive
- Wrong ownership
- SELinux enabled (Linux)

**Solutions:**

1. **Fix storage permissions:**
   ```bash
   # Linux/Mac
   chmod -R 777 storage/
   chmod -R 777 bootstrap/cache/
   
   # Set ownership
   chown -R www-data:www-data storage/
   chown -R www-data:www-data bootstrap/cache/
   
   # Better permissions (more secure)
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   # And set owner to web user
   ```

   ```bash
   # Windows (via PowerShell as Admin)
   icacls "storage" /grant:r "*S-1-5-17:F" /t
   icacls "bootstrap\cache" /grant:r "*S-1-5-17:F" /t
   ```

2. **For Laragon (Windows):**
   - Right-click folder → Properties → Security
   - Grant full permissions to your user

3. **On cPanel:**
   - Right-click folder in File Manager → Change Permissions
   - Set to 777

---

### Problem: "No such file or directory" or file not found

**Symptoms:**
- Missing migration file
- Configuration file not found
- View file error

**Causes:**
- File not uploaded
- Wrong file path
- Typo in require statement

**Solutions:**

1. **Verify file exists:**
   ```bash
   # Linux
   ls -la path/to/file
   
   # Windows
   dir path\to\file
   ```

2. **Check for typos:**
   - Filenames are case-sensitive on Linux
   - Windows is case-insensitive

3. **Re-upload if missing:**
   - Via FTP or file manager
   - Ensure full directory structure uploaded

---

## ASSET & CSS/JS ISSUES

### Problem: "CSS and JavaScript not loading" or 404 errors

**Symptoms:**
- Styles missing (unstyled page)
- JavaScript not working
- Browser console shows 404 errors

**Causes:**
- Vite build not run
- Wrong APP_URL
- Assets not compiled for production

**Solutions:**

1. **Build assets:**
   ```bash
   npm run build  # For production
   npm run dev    # For development (watch mode)
   ```

2. **Verify build succeeded:**
   - Check for `public/build/` directory
   - Should contain `manifest.json`

3. **Check APP_URL in .env:**
   ```
   APP_URL=http://localhost/edutrack  (for local)
   APP_URL=https://your-domain.com    (for production)
   # Must match your actual domain
   ```

4. **Clear browser cache:**
   - Press Ctrl+Shift+Delete
   - Clear all cache/cookies
   - Hard refresh: Ctrl+F5

5. **For production, ensure assets cached:**
   ```bash
   php artisan config:cache
   ```

---

### Problem: "Mix" or "Vite" related errors

**Symptoms:**
- Reference to undefined Mix/Vite functions
- Asset compilation errors

**Causes:**
- Using old Mix syntax with Vite
- Missing package.json dependencies

**Solutions:**

1. **Verify Vite is installed:**
   ```bash
   npm list vite
   # Should show version 4.x or higher
   ```

2. **Reinstall dependencies:**
   ```bash
   rm node_modules/
   rm package-lock.json
   npm install
   ```

3. **Run build:**
   ```bash
   npm run build
   ```

---

## SERVER CONFIGURATION ISSUES

### Problem: "Access Denied" or permission error on cPanel

**Symptoms:**
- Cannot access domain
- 403 Forbidden error
- File Permission Denied

**Causes:**
- Document root misconfigured
- Wrong public folder referenced
- Permissions on public_html wrong

**Solutions:**

1. **Verify public folder is document root:**
   - Create `.htaccess` in `public/` folder (not project root)
   - Ensure DocumentRoot points to `public/` folder

2. **Fix permissions:**
   ```bash
   chmod 755 /home/username/public_html/edutrack/public
   ```

3. **Check .htaccess rewrite rules:**
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteRule ^ index.php [QSA,L]
   </IfModule>
   ```

---

### Problem: "502 Bad Gateway" or "Gateway Timeout"

**Symptoms:**
- Server error page
- Application times out
- Intermittent failures

**Causes:**
- Script exceeds time limit
- Database query too slow
- Laravel running out of memory
- Syntax error in service

**Solutions:**

1. **Increase timeout (nginx/Apache):**
   ```nginx
   # Nginx
   fastcgi_read_timeout 300s;
   
   # Apache
   TimeOut 300
   ```

2. **Increase PHP memory limit in .ini:**
   ```ini
   memory_limit = 256M
   max_execution_time = 300
   ```

3. **Check error logs:**
   ```bash
   # Nginx
   tail -f /var/log/nginx/error.log
   
   # Apache
   tail -f /var/log/apache2/error.log
   
   # Laravel
   tail -f storage/logs/laravel.log
   ```

4. **Optimize slow queries:**
   - Add database indexes
   - Check for N+1 query problems
   - Use eager loading with `.with()`

---

## PERFORMANCE ISSUES

### Problem: "Application is slow" or "Database queries slow"

**Symptoms:**
- Pages take long to load
- High CPU/memory usage
- Intermittent slowness

**Causes:**
- Missing database indexes
- N+1 query problem
- Too many queries per page
- Large queries without pagination

**Solutions:**

1. **Enable query logging:**
   ```php
   // In code
   DB::enableQueryLog();
   // do operations
   dd(DB::getQueryLog());
   ```

2. **Add indexes to frequently queried columns:**
   ```php
   // In migration
   Schema::table('courses', function (Blueprint $table) {
       $table->index('teacher_id');
       $table->index('code');
   });
   ```

3. **Use eager loading:**
   ```php
   // Good (eager loading)
   $courses = Course::with('teacher', 'students')->get();
   
   // Bad (lazy loading, N+1 problem)
   $courses = Course::all();
   foreach ($courses as $course) {
       echo $course->teacher->name; // Extra query each time
   }
   ```

4. **Add pagination:**
   ```php
   // Good
   $courses = Course::paginate(15);
   
   // Bad
   $courses = Course::all(); // All records at once
   ```

5. **Cache expensive queries:**
   ```php
   $courses = Cache::remember('all_courses', 3600, function () {
       return Course::with('teacher')->get();
   });
   ```

---

## DEPLOYMENT SPECIFIC ISSUES

### Problem: "Application works locally but fails on server"

**Symptoms:**
- Routes return 404
- Database works locally but not online
- Different behavior

**Causes:**
- .env not uploaded or configured
- Dependencies not installed
- Different PHP version
- Database credentials wrong

**Solutions:**

1. **Verify .env file:**
   - Must exist on server
   - Must have correct DB credentials
   - APP_KEY must be set (unique per environment)

2. **Verify dependencies:**
   ```bash
   # SSH into server
   cd /var/www/edutrack
   - [ ] Installed PHP 8.3 and extensions
  - [ ] Installed: `sudo apt install apache2 libapache2-mod-php8.3`
   composer install
   npm install       # Install Node packages
   npm run build     # Build assets
   ```

3. **Check PHP version:**
   ```bash
   php --version  # Should be 8.3+
   ```

4. **Verify web server rewrite rules:**
   - Apache: `.htaccess` in public folder
   - Nginx: Configured in site config

5. **Clear caches after deployment:**
   ```bash
   php artisan cache:clear
   php artisan config:cache
   php artisan route:cache
   ```

---

### Problem: "Uploaded files not accessible" or "File upload fails"

**Symptoms:**
- Cannot upload files
- Uploaded files not visible
- 404 when accessing files

**Causes:**
- Storage directory not writable
- Storage link not created
- Wrong file path in config

**Solutions:**

1. **Create storage link:**
   ```bash
   php artisan storage:link
   # Creates symlink from public/storage to storage/app/public
   ```

2. **Fix storage permissions:**
   ```bash
   chmod -R 777 storage/
   # Or more securely:
   chown -R www-data:www-data storage/
   chmod -R 755 storage/
   ```

3. **Verify .env FILESYSTEM_DISK:**
   ```
   FILESYSTEM_DISK=public
   ```

4. **Check storage config (config/filesystems.php)**

---

## WINDOWS SPECIFIC ISSUES

### Problem: "Permission denied" on Windows with Laragon

**Symptoms:**
- Cannot write to storage
- Cache clear fails
- Migration fails

**Causes:**
- Administrator privileges needed
- Antivirus blocking writes
- File in use

**Solutions:**

1. **Run as Administrator:**
   - Right-click PowerShell
   - Select "Run as administrator"

2. **Disable antivirus temporarily:**
   - Check if file is zoned (Windows Defender SmartScreen)
   - Temporarily disable antivirus during commands

3. **Restart Apache/MySQL:**
   - Close file handles
   - Restart services via Laragon UI

4. **Set WRITE permissions:**
   - Right-click folder → Properties → Security
   - Grant FULL CONTROL to your user

---

### Problem: "Port 80 or 3306 already in use"

**Symptoms:**
- Laragon cannot start services
- "Address already in use" error
- Cannot access application

**Causes:**
- Another application using port
- Service already running elsewhere
- Skype or other apps using ports

**Solutions:**

1. **Find what's using port:**
   ```powershell
   # PowerShell as Admin
   netstat -ano | findstr :80
   netstat -ano | findstr :3306
   # Find PID in output
   tasklist /fi "PID eq XXXX"  # Replace XXXX with PID
   ```

2. **Stop the process:**
   ```powershell
   taskkill /PID XXXX /F
   ```

3. **Or change Laragon ports:**
   - In Laragon → Menu → Preferences
   - Change Apache and MySQL ports
   - Restart services

---

## LINUX/UBUNTU SPECIFIC ISSUES

### Problem: "sudo: composer: command not found"

**Symptoms:**
- Composer not available from command line
- Cannot run composer install

**Causes:**
- Composer not installed
- Not in PATH
- Installed locally but not globally

**Solutions:**

```bash
# Install Composer globally
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Verify
composer --version
```

---

### Problem: "sudo: npm: command not found"

**Symptoms:**
- npm not available
- Cannot run npm install

**Causes:**
- Node.js/npm not installed
- Wrong version

**Solutions:**

```bash
# Install Node.js with npm
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Verify
node --version
npm --version
```

---

### Problem: "Permission denied" when accessing logs

**Symptoms:**
- Cannot read Laravel logs
- tail command fails

**Causes:**
- Wrong permissions
- Running as wrong user

**Solutions:**

```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/edutrack/storage
sudo chmod -R 755 /var/www/edutrack/storage

# View logs as sudo
sudo tail -f /var/www/edutrack/storage/logs/laravel.log
```

---

## QUICK DIAGNOSTIC SCRIPT

Run this to check your installation:

```bash
#!/bin/bash
echo "=== EduTrack Diagnostic ==="

echo "1. PHP Version:"
php --version

echo -e "\n2. MySQL Status:"
systemctl status mysql --no-pager | grep Active || echo "MySQL not running"

echo -e "\n3. Composer:"
composer --version

echo -e "\n4. Node.js & npm:"
node -v
npm -v

echo -e "\n5. Project Directory:"
ls -la .

echo -e "\n6. Storage Permissions:"
ls -la storage/

echo -e "\n7. Laravel Configuration:"
php artisan config:show | head -20

echo -e "\n8. Database Connection:"
php artisan tinker -e "try { DB::connection()->getPDO(); echo 'Connected!'; } catch (Exception \$e) { echo 'Failed: ' . \$e->getMessage(); }"

echo -e "\n=== Diagnostic Complete ==="
```

Save as `diagnose.sh`, then run:
```bash
bash diagnose.sh
```

---

## WHEN TO CONTACT SUPPORT

Contact your hosting provider or Laravel support if:
- You've tried all solutions above
- Error message is not listed
- Issue is specific to your hosting environment
- Database corruption suspected
- Server security issue

---

**For more help, check:**
- Laravel Documentation: https://laravel.com/docs
- DEPLOYMENT_GUIDE.md
- SYSTEM_REQUIREMENTS.md

**Last Updated:** February 18, 2026

