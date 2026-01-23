# EduTrack - Laptop Transfer & Presentation Guide

## 🎯 Complete Guide to Transfer and Present on Your Laptop

---

## 📋 PART 1: PREPARATION (Before Transfer)

### On Your Current Machine

#### Step 1: Prepare the Project

```powershell
cd c:\laragon\www\edutrack

# Clear cache to reduce file size
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Remove vendor if transferring via USB (to save space)
# Note: Will need to reinstall on target laptop
# Optional: rm -r vendor
```

#### Step 2: Create a Backup

```powershell
# Export database
mysqldump -u root -p edutrack > C:\backup_edutrack.sql

# Or use Laragon terminal
laragon export edutrack
```

#### Step 3: Prepare Package Info

```powershell
# These files preserve dependencies
# - composer.json (PHP dependencies)
# - package.json (JavaScript dependencies)
# - composer.lock (exact versions)
# - package-lock.json (exact versions)

# All should already be in the project
```

---

## 💾 PART 2: TRANSFER METHOD OPTIONS

### Option A: USB Flash Drive (Recommended for Presentations)

**Advantages**: Fast, reliable, no internet needed

**Steps**:

1. Copy entire project folder to USB:

    ```powershell
    Copy-Item -Path "C:\laragon\www\edutrack" -Destination "E:\edutrack" -Recurse
    ```

2. Also copy database backup:

    ```powershell
    Copy-Item -Path "backup_edutrack.sql" -Destination "E:\"
    ```

3. Create a file: `E:\SETUP_INSTRUCTIONS.txt`

    ```
    EduTrack Setup Instructions

    1. Copy edutrack folder to target: C:\laragon\www\
    2. Open PowerShell in edutrack directory
    3. Run: composer install
    4. Run: npm install (optional)
    5. Run: php artisan migrate:fresh --seed
    6. Or import backup: mysql edutrack < backup_edutrack.sql
    7. Access: http://edutrack.local

    See DEPLOYMENT_AND_SETUP_GUIDE.md for details
    ```

4. USB Contents:
    ```
    E:\
    ├── edutrack\           (entire project)
    ├── backup_edutrack.sql (database)
    └── SETUP_INSTRUCTIONS.txt
    ```

### Option B: Cloud Storage (Google Drive / OneDrive)

**Advantages**: Secure, accessible anywhere, auto-backup

**Steps**:

1. Zip the project:

    ```powershell
    Compress-Archive -Path "C:\laragon\www\edutrack" -DestinationPath "C:\edutrack.zip" -CompressionLevel Optimal
    ```

2. Upload to cloud service
3. Download on target machine
4. Extract to `C:\laragon\www\edutrack`

**File Size**: ~200-300 MB (with vendor folder)
**Without vendor**: ~50 MB (reinstall dependencies on target)

### Option C: Git (If Available)

**Advantages**: Version control, easy updates

**Steps**:

1. Push to GitHub/GitLab
2. Clone on target machine:
    ```powershell
    cd C:\laragon\www
    git clone <repository-url> edutrack
    cd edutrack
    composer install
    npm install
    ```

### Option D: External Hard Drive

**Advantages**: Large capacity, reliable

**Steps**: Same as USB, but with more space available

---

## 🖥️ PART 3: SETUP ON TARGET LAPTOP (Windows 10/11)

### Step 1: Install Laragon

**If not already installed**:

1. Download from: https://laragon.org/
2. Run installer: `laragon-5.0.0-full.exe`
3. Select installation directory: `C:\laragon`
4. Complete installation
5. Launch Laragon
6. Start services: Click "Start All"

### Step 2: Transfer Project to Laptop

**Using USB**:

```powershell
# Copy from USB to laptop
Copy-Item -Path "E:\edutrack" -Destination "C:\laragon\www\edutrack" -Recurse

# Copy database backup
Copy-Item -Path "E:\backup_edutrack.sql" -Destination "C:\laragon\www\"
```

**Using Cloud**:

1. Download edutrack.zip
2. Extract to `C:\laragon\www\`
3. Rename folder to `edutrack`

### Step 3: Configure Project

1. Open PowerShell as Administrator
2. Navigate to project:

    ```powershell
    cd C:\laragon\www\edutrack
    ```

3. Copy environment file:

    ```powershell
    Copy-Item ".env.example" ".env"
    ```

4. Edit `.env`:

    ```powershell
    notepad .env

    # Change these values:
    APP_NAME=EduTrack
    APP_ENV=local
    APP_DEBUG=true
    APP_URL=http://edutrack.local

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=edutrack
    DB_USERNAME=root
    DB_PASSWORD=
    ```

### Step 4: Install Dependencies

**If vendor folder exists**:

```powershell
# Just generate key
php artisan key:generate
```

**If vendor folder doesn't exist** (after transfer):

```powershell
# Install PHP dependencies
composer install

# Install JS dependencies (optional)
npm install
```

### Step 5: Database Setup

**Option A: Use backup file** (Recommended - faster)

```powershell
# Create database
mysql -u root -e "CREATE DATABASE edutrack;"

# Restore from backup
mysql -u root edutrack < backup_edutrack.sql
```

**Option B: Fresh migration**

```powershell
# Run migrations
php artisan migrate

# Seed sample data
php artisan db:seed
```

### Step 6: Configure Web Server

**For Laragon**:

```powershell
# Laragon automatically handles this, just add to hosts:

# Edit hosts file as Administrator
notepad C:\Windows\System32\drivers\etc\hosts

# Add this line:
127.0.0.1  edutrack.local
```

**For XAMPP** (if using XAMPP instead):

1. Add vhost configuration
2. Update hosts file
3. Restart Apache

### Step 7: Start Services

```powershell
# Laragon (in Laragon app, click "Start All")
# Or in terminal:
laragon start

# Verify MySQL and Apache are running
laragon status
```

### Step 8: Access Application

**Open browser**:

- URL: `http://edutrack.local`
- Or: `http://localhost`

You should see the login page!

---

## 🧪 PART 4: VERIFICATION ON LAPTOP

### Pre-Presentation Checklist

- [ ] Application loads without errors
- [ ] Login page displays correctly
- [ ] Can login with credentials:
    - Email: admin@school.com
    - Password: password123
- [ ] Dashboard shows data
- [ ] Navigation menus work
- [ ] No errors in browser console
- [ ] Grades can be viewed/entered
- [ ] Attendance can be marked
- [ ] Assignments display properly
- [ ] Theme switching works
- [ ] File uploads work

### Quick Test

```powershell
# Test database connection
php artisan tinker
>>> User::count();
# Should return number of users

>>> User::first();
# Should return a user record
```

---

## 🎨 PART 5: CUSTOMIZATION FOR PRESENTATION

### Change School Name

1. Edit `.env`:

    ```powershell
    APP_NAME="Your School Name"
    ```

2. Update in views:

    ```
    resources/views/layouts/
    ```

3. Add school logo:
    ```
    public/images/logo.png
    ```

### Customize User Data

```powershell
php artisan tinker

# Create specific admin user
>>> User::create(['name' => 'John Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password123'), 'role' => 'admin']);

# Create teacher
>>> User::create(['name' => 'Jane Teacher', 'email' => 'teacher@test.com', 'password' => bcrypt('password123'), 'role' => 'teacher']);

# Create student
>>> User::create(['name' => 'Bob Student', 'email' => 'student@test.com', 'password' => bcrypt('password123'), 'role' => 'student']);
```

### Reset to Fresh State (If needed)

```powershell
# Clear all data and rebuild
php artisan migrate:fresh --seed

# Clear cache
php artisan cache:clear
php artisan view:clear
```

---

## 🚨 PART 6: TROUBLESHOOTING ON LAPTOP

### Issue: "Page not found" / "Connection refused"

**Solution**:

```powershell
# Verify hosts file
notepad C:\Windows\System32\drivers\etc\hosts
# Check for: 127.0.0.1  edutrack.local

# Restart Apache
net stop Apache2.4
net start Apache2.4

# Or restart Laragon
laragon restart
```

### Issue: Database connection error

**Solution**:

```powershell
# Verify MySQL is running
mysql -u root -e "SELECT 1;"

# Check .env credentials
notepad .env
# Verify DB_HOST, DB_USERNAME, DB_PASSWORD match

# Restart MySQL
net stop MySQL80
net start MySQL80
```

### Issue: Composer error

**Solution**:

```powershell
# Update composer
composer self-update

# Clear composer cache
composer clear-cache

# Try install again
composer install
```

### Issue: Blank page / 500 error

**Solution**:

```powershell
# Clear cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Check logs
type storage/logs/laravel.log

# Generate app key if missing
php artisan key:generate
```

### Issue: Port already in use

**Solution**:

```powershell
# Find what's using port 80
netstat -ano | findstr :80

# Use different port in .env
APP_URL=http://edutrack.localhost:8000

# Start PHP built-in server on port 8000
php artisan serve --port=8000
```

---

## 📊 PART 7: DEMO SCENARIOS FOR PRESENTATION

### Scenario 1: Student Login & View Grades

1. Login with: `student@school.com` / `password123`
2. Navigate to dashboard
3. Show grades for courses
4. Show GPA calculation
5. Show attendance

### Scenario 2: Teacher Entry & Grading

1. Login with: `teacher@school.com` / `password123`
2. Navigate to Classes
3. Select a class
4. Show students
5. Show grade entry form
6. Show grading configuration

### Scenario 3: Admin Dashboard

1. Login with: `admin@school.com` / `password123`
2. Show system statistics
3. Show user management
4. Show class management
5. Show reports

### Scenario 4: Feature Demonstration

1. Switch themes (Light, Dark, Ocean, Forest, Sunset)
2. Show attendance marking
3. Show assignment submission
4. Show notifications

---

## 💡 PART 8: BACKUP & RESTORE DURING PRESENTATION

### Take Backup Before Presentation

```powershell
mysqldump -u root -p edutrack > C:\backup_presentation.sql
```

### Restore if Needed

```powershell
mysql -u root -p edutrack < C:\backup_presentation.sql
```

### Emergency Reset

```powershell
# If something goes wrong, reset to fresh state
php artisan migrate:fresh --seed
```

---

## 🎬 PART 9: PRESENTATION TIPS

### Before Presentation

- [ ] Close unnecessary programs
- [ ] Disable notifications (Windows + I → Focus assist → On)
- [ ] Full-screen browser (F11)
- [ ] Test internet if demos needed
- [ ] Have backup presentation (PDF)
- [ ] Practice the demo flow

### During Presentation

- [ ] Speak clearly, go slow
- [ ] Let visuals speak - pause and explain
- [ ] Use "Ctrl + Minus" to zoom out if needed
- [ ] Keep it interactive - ask questions
- [ ] Have pre-loaded demo accounts ready
- [ ] Show the database behind the app (tinker)

### Post-Presentation

- [ ] Back up any new data created
- [ ] Document feedback
- [ ] Note any issues
- [ ] Create new backup for next presentation

---

## 📞 QUICK REFERENCE - KEY FILES

| File                    | Purpose         |
| ----------------------- | --------------- |
| `.env`                  | Configuration   |
| `app/Models/`           | Database models |
| `app/Http/Controllers/` | Business logic  |
| `resources/views/`      | UI templates    |
| `routes/web.php`        | URL routes      |
| `storage/logs/`         | Error logs      |
| `.env.example`          | Config template |

---

## ✅ FINAL CHECKLIST

Before presenting:

- [ ] Laragon/XAMPP running
- [ ] MySQL running
- [ ] Project loads at http://edutrack.local
- [ ] Can login with test credentials
- [ ] Database has sample data
- [ ] All features work (grades, attendance, etc.)
- [ ] Browser console has no errors
- [ ] Demo flow practiced
- [ ] Backup created
- [ ] Laptop fully charged
- [ ] Internet connectivity verified (if needed)

---

## 🚀 YOU'RE READY!

Once all checks pass, you're ready to present EduTrack to your audience!

**Good luck with your presentation! 🎓**

---

_Last Updated: January 2026_
_EduTrack v1.0 - Production Ready_
