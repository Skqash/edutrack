# 🔧 Fix: Railway Database Connection Issue

## ❌ Error You're Seeing:
```
SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it
(Connection: mysql, Host: 127.0.0.1, Port: 3306)
```

## 🎯 Problem:
Railway CLI is trying to use your **local database** (127.0.0.1) instead of Railway's database.

---

## ✅ Solution 1: Use Railway Shell (Recommended)

### Step 1: Open Railway Shell
```bash
railway shell
```

This opens a shell connected directly to Railway's environment.

### Step 2: Run Migrations in Shell
```bash
php artisan migrate --force
```

### Step 3: Create Admin (Optional)
```bash
php artisan tinker
```

Then paste your admin creation code.

### Step 4: Exit Shell
```bash
exit
```

---

## ✅ Solution 2: Temporarily Rename .env File

### Step 1: Rename Your Local .env
```bash
# In your project folder
mv .env .env.local
```

Or manually rename `.env` to `.env.local` in File Explorer

### Step 2: Run Railway Command
```bash
railway run php artisan migrate --force
```

### Step 3: Restore Your .env
```bash
mv .env.local .env
```

Or rename `.env.local` back to `.env`

---

## ✅ Solution 3: Use Railway Dashboard (Easiest!)

Railway might have already run migrations automatically during deployment!

### Check if Migrations Already Ran:

1. **Go to Railway Dashboard**
2. **Click on your MySQL service**
3. **Click "Data" tab**
4. **Look for tables:**
   - If you see tables like `users`, `teachers`, `students`, etc.
   - **Migrations already ran!** ✅

### If No Tables Exist:

We'll use Railway's built-in terminal:

1. **Go to Railway Dashboard**
2. **Click on your Web Service (edutrack)**
3. **Click on "Deployments" tab**
4. **Click on the latest deployment**
5. **Look for "Shell" or "Terminal" option**
6. **If available, click it and run:**
   ```bash
   php artisan migrate --force
   ```

---

## ✅ Solution 4: Check Railway Variables First

Before running migrations, verify Railway has the correct database variables:

### Step 1: Check Railway Variables
1. Go to Railway Dashboard
2. Click your Web Service
3. Click "Variables" tab
4. Verify these exist:
   ```
   DB_CONNECTION=mysql
   DB_HOST=${{MySQL.MYSQL_HOST}}
   DB_PORT=${{MySQL.MYSQL_PORT}}
   DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
   DB_USERNAME=${{MySQL.MYSQL_USER}}
   DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}
   ```

### Step 2: If Missing, Add Them
Click "New Variable" and add each one.

### Step 3: Wait for Redeploy
Railway will automatically redeploy (2-3 minutes)

### Step 4: Try Migration Again
```bash
railway shell
php artisan migrate --force
```

---

## 🎯 Recommended Approach (Step-by-Step)

### Method 1: Use Railway Shell (Simplest)

```bash
# 1. Open Railway shell
C:\laragon\www\edutrack> railway shell

# 2. You're now connected to Railway's environment
# Run migrations
bash-5.1$ php artisan migrate --force

# 3. Create admin account
bash-5.1$ php artisan tinker
>>> $user = new App\Models\User();
>>> $user->name = 'Admin';
>>> $user->email = 'admin@edutrack.com';
>>> $user->password = Hash::make('Admin123!');
>>> $user->role = 'admin';
>>> $user->status = 'Active';
>>> $user->campus_status = 'approved';
>>> $user->save();
>>> $admin = new App\Models\Admin();
>>> $admin->user_id = $user->id;
>>> $admin->employee_id = 'ADM-001';
>>> $admin->department = 'Administration';
>>> $admin->status = 'Active';
>>> $admin->save();
>>> exit

# 4. Exit shell
bash-5.1$ exit
```

---

## 🔍 Verify Database Connection

### Check if Railway Database is Accessible:

```bash
# Open Railway shell
railway shell

# Test database connection
php artisan tinker

# Try to query database
>>> DB::connection()->getPdo();
=> PDO {#...}

# If you see PDO object, connection works!
>>> exit
```

---

## 📊 What's Happening?

### The Issue:
```
Your Computer
    ↓
railway run command
    ↓
Reads local .env file (127.0.0.1)
    ↓
Tries to connect to local MySQL
    ↓
❌ Connection refused
```

### The Fix (Railway Shell):
```
Your Computer
    ↓
railway shell
    ↓
Opens shell on Railway's server
    ↓
Uses Railway's environment variables
    ↓
Connects to Railway's MySQL
    ↓
✅ Connection successful
```

---

## 🚨 Troubleshooting

### Issue: "railway shell" command not found
**Solution:** Update Railway CLI
```bash
npm install -g @railway/cli@latest
```

### Issue: Shell opens but commands fail
**Solution:** Check Railway deployment status
1. Go to Railway Dashboard
2. Check deployment is successful (green ✅)
3. Try again

### Issue: "Access denied" in shell
**Solution:** Check database variables in Railway
- Verify DB_HOST, DB_USERNAME, DB_PASSWORD are set

### Issue: Migrations run but tables not created
**Solution:** Check migration files exist
```bash
railway shell
ls database/migrations
```

---

## ✅ Quick Checklist

Before running migrations:

- [ ] Railway CLI installed and updated
- [ ] Logged in to Railway (`railway whoami`)
- [ ] Project linked (`railway status`)
- [ ] APP_KEY set in Railway Variables
- [ ] Database variables set in Railway Variables
- [ ] Deployment successful (green ✅)
- [ ] MySQL service running in Railway

---

## 🎯 Your Next Steps

### Right Now:

1. **Try Railway Shell:**
   ```bash
   railway shell
   ```

2. **If shell works, run migrations:**
   ```bash
   php artisan migrate --force
   ```

3. **Create admin:**
   ```bash
   php artisan tinker
   # Paste admin creation code
   ```

4. **Exit and test:**
   ```bash
   exit
   ```
   Then visit your Railway URL

### If Railway Shell Doesn't Work:

1. **Check if migrations already ran:**
   - Visit your Railway URL
   - Try to login (even if you don't have account yet)
   - If you see proper login page (not 500 error), migrations might be done

2. **Check Railway Dashboard:**
   - MySQL service → Data tab
   - Look for existing tables

3. **Try Solution 2:**
   - Rename .env temporarily
   - Run `railway run php artisan migrate --force`
   - Rename .env back

---

## 💡 Pro Tip

### Check if Migrations Already Ran:

Railway might have automatically run migrations during deployment!

**Quick Test:**
1. Visit your Railway URL
2. If you see login page (not 500 error)
3. Migrations probably already ran! ✅

**To verify:**
```bash
railway shell
php artisan migrate:status
```

This shows which migrations have run.

---

## 📞 Quick Commands Reference

```bash
# Open Railway shell (recommended)
railway shell

# Run migrations in shell
php artisan migrate --force

# Check migration status
php artisan migrate:status

# Create admin in shell
php artisan tinker

# Exit shell
exit

# Alternative: Run with railway run
railway run php artisan migrate --force

# View logs
railway logs
```

---

## ✨ Expected Output

### Successful Migration:
```bash
bash-5.1$ php artisan migrate --force

Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table (45.23ms)
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table (32.45ms)
Migrating: 2019_08_19_000000_create_failed_jobs_table
Migrated:  2019_08_19_000000_create_failed_jobs_table (28.67ms)
...
✓ All migrations completed successfully
```

---

## 🎉 After Successful Migration

Once migrations complete:

1. **Create admin account** (using tinker in railway shell)
2. **Visit your Railway URL**
3. **Login with admin credentials**
4. **Start using your system!**

---

**Try `railway shell` now and run the migrations! 🚀**

