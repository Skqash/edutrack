# 📋 Deployment Checklist

## Before You Deploy

### ✅ Code Preparation
- [ ] All code committed to GitHub
- [ ] `.env` file is in `.gitignore`
- [ ] `.env.example` exists with template values
- [ ] `composer.json` has all dependencies
- [ ] Database migrations are ready
- [ ] No hardcoded credentials in code

### ✅ Files Created
- [ ] `Procfile` exists
- [ ] `nixpacks.toml` exists
- [ ] `railway-config.json` exists (optional)
- [ ] `deploy.sh` exists (for VPS)

### ✅ GitHub Repository
- [ ] Repository is public or Railway has access
- [ ] Latest code is pushed
- [ ] Branch is `main` or `master`

---

## Deployment Steps (Railway.app)

### 1️⃣ Create Railway Account
- [ ] Go to https://railway.app
- [ ] Sign up with GitHub
- [ ] Authorize Railway to access repositories

### 2️⃣ Create New Project
- [ ] Click "Start a New Project"
- [ ] Select "Deploy from GitHub repo"
- [ ] Choose your EduTrack repository
- [ ] Wait for initial deployment

### 3️⃣ Add MySQL Database
- [ ] Click "New" in your project
- [ ] Select "Database"
- [ ] Choose "Add MySQL"
- [ ] Wait for database to provision

### 4️⃣ Configure Environment Variables
Go to Web Service → Variables → Add:

**Required Variables:**
- [ ] `APP_NAME` = EduTrack
- [ ] `APP_ENV` = production
- [ ] `APP_DEBUG` = false
- [ ] `APP_KEY` = (generate with `php artisan key:generate --show`)
- [ ] `APP_URL` = (will be your Railway domain)

**Database Variables (Auto-filled by Railway):**
- [ ] `DB_CONNECTION` = mysql
- [ ] `DB_HOST` = ${{MYSQL_HOST}}
- [ ] `DB_PORT` = ${{MYSQL_PORT}}
- [ ] `DB_DATABASE` = ${{MYSQL_DATABASE}}
- [ ] `DB_USERNAME` = ${{MYSQL_USER}}
- [ ] `DB_PASSWORD` = ${{MYSQL_PASSWORD}}

**Session & Cache:**
- [ ] `SESSION_DRIVER` = database
- [ ] `CACHE_DRIVER` = database
- [ ] `QUEUE_CONNECTION` = sync

**Logging:**
- [ ] `LOG_CHANNEL` = stack
- [ ] `LOG_LEVEL` = error

### 5️⃣ Generate Public Domain
- [ ] Go to Settings tab
- [ ] Click "Generate Domain"
- [ ] Copy the generated URL
- [ ] Update `APP_URL` variable with this URL

### 6️⃣ Verify Deployment
- [ ] Check deployment status (should be green)
- [ ] View logs for any errors
- [ ] Visit your Railway URL
- [ ] Should see Laravel welcome or login page

### 7️⃣ Run Database Migrations
**Option A: Automatic (Recommended)**
- Already configured in `railway-config.json`
- Migrations run automatically on deploy

**Option B: Manual**
```bash
# Install Railway CLI
npm i -g @railway/cli

# Login and link
railway login
railway link

# Run migrations
railway run php artisan migrate --force
```

### 8️⃣ Create Admin Account
```bash
# Using Railway CLI
railway run php artisan tinker
```

Then run:
```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@edutrack.com';
$user->password = Hash::make('Admin123!');
$user->role = 'admin';
$user->status = 'Active';
$user->campus_status = 'approved';
$user->save();
exit
```

### 9️⃣ Test Application
- [ ] Visit your Railway URL
- [ ] Login with admin credentials
- [ ] Check admin dashboard loads
- [ ] Test teacher signup
- [ ] Create a test class
- [ ] Test grade entry
- [ ] Test attendance
- [ ] Check all major features

### 🔟 Optional: Seed Demo Data
```bash
railway run php artisan db:seed --class=CPSUAccurateSeeder
```

---

## Post-Deployment

### ✅ Security
- [ ] APP_DEBUG is set to false
- [ ] APP_ENV is set to production
- [ ] Strong passwords for admin accounts
- [ ] Database credentials are secure
- [ ] HTTPS is enabled (automatic on Railway)

### ✅ Performance
- [ ] Config is cached (`php artisan config:cache`)
- [ ] Routes are cached (`php artisan route:cache`)
- [ ] Views are cached (`php artisan view:cache`)
- [ ] Composer autoloader is optimized

### ✅ Monitoring
- [ ] Check Railway metrics (CPU, Memory)
- [ ] Monitor application logs
- [ ] Set up error notifications (optional)
- [ ] Test from different devices

### ✅ Documentation
- [ ] Document admin credentials (securely)
- [ ] Note Railway project URL
- [ ] Save database connection details
- [ ] Document any custom configurations

---

## Common Issues & Solutions

### ❌ Issue: 500 Internal Server Error
**Solution:**
```bash
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan view:clear
```

### ❌ Issue: Database Connection Failed
**Check:**
- [ ] MySQL service is running
- [ ] Database variables are set correctly
- [ ] Using `${{MYSQL_*}}` format for variables
- [ ] Web service and database are in same project

### ❌ Issue: APP_KEY Not Set
**Solution:**
```bash
# Generate locally
php artisan key:generate --show

# Or use online generator
# https://generate-random.org/laravel-key-generator

# Add to Railway variables
```

### ❌ Issue: Migrations Not Running
**Solution:**
```bash
# Run manually
railway run php artisan migrate --force

# Check migration status
railway run php artisan migrate:status
```

### ❌ Issue: Assets Not Loading
**Check:**
- [ ] APP_URL is set correctly
- [ ] Public folder is accessible
- [ ] No mixed content errors (HTTP/HTTPS)

---

## Maintenance Tasks

### 🔄 Update Application
```bash
# Local changes
git add .
git commit -m "Update feature"
git push origin main

# Railway auto-deploys
# Check deployment status in Railway dashboard
```

### 🗄️ Backup Database
```bash
# Export database
railway run mysqldump -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE > backup.sql

# Or use Railway dashboard
# MySQL service → Data → Export
```

### 📊 View Logs
```bash
# Using CLI
railway logs

# Or in dashboard
# Service → Deployments → View Logs
```

### 🔧 Run Artisan Commands
```bash
# Clear cache
railway run php artisan cache:clear

# Run migrations
railway run php artisan migrate

# Create user
railway run php artisan tinker
```

---

## Success Metrics

### ✅ Deployment Successful When:
- [ ] Application loads without errors
- [ ] Can login as admin
- [ ] Can register new teacher
- [ ] Dashboard displays correctly
- [ ] Can create classes
- [ ] Can enter grades
- [ ] Can take attendance
- [ ] All features work as expected
- [ ] No console errors
- [ ] No 500 errors in logs

---

## 🎉 Congratulations!

Your EduTrack system is now live and accessible online!

**Your Application URL:** `https://your-app.railway.app`

**Next Steps:**
1. Share URL with users
2. Create teacher accounts
3. Set up classes and courses
4. Start using the system
5. Monitor performance
6. Gather feedback
7. Make improvements

---

## 📞 Support

**Railway Support:**
- Docs: https://docs.railway.app
- Discord: https://discord.gg/railway
- Status: https://status.railway.app

**Laravel Support:**
- Docs: https://laravel.com/docs
- Forums: https://laracasts.com/discuss
- Stack Overflow: https://stackoverflow.com/questions/tagged/laravel

**Need Help?**
- Check deployment logs first
- Review this checklist
- Search Railway docs
- Ask in Railway Discord
