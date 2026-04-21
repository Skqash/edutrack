# 🚀 Deploy to Railway - Step by Step Guide

## ✅ Pre-Deployment Checklist

### 1. Make Sure Your Code is on GitHub
```bash
# Check if you have uncommitted changes
git status

# If you have changes, commit them
git add .
git commit -m "Prepare for Railway deployment"
git push origin main
```

**Important Files Already Created:**
- ✅ `Procfile` - Railway startup command
- ✅ `nixpacks.toml` - Build configuration
- ✅ `railway-config.json` - Railway settings
- ✅ `.env.example` - Environment template

---

## 🎯 Step 1: Create Railway Account

1. **Go to:** https://railway.app
2. **Click:** "Login" or "Start a New Project"
3. **Sign up with GitHub** (recommended)
4. **Authorize Railway** to access your repositories

---

## 🎯 Step 2: Create New Project

1. **Click:** "New Project" or "Start a New Project"
2. **Select:** "Deploy from GitHub repo"
3. **Choose:** Your EduTrack repository
4. **Wait:** Railway will start deploying (this will fail initially - that's OK!)

---

## 🎯 Step 3: Add MySQL Database

1. **In your project, click:** "New" button
2. **Select:** "Database"
3. **Choose:** "Add MySQL"
4. **Wait:** 30-60 seconds for database to provision
5. **You'll see:** A new MySQL service appear in your project

---

## 🎯 Step 4: Configure Environment Variables

### Click on your **Web Service** (not the database)

1. **Go to:** "Variables" tab
2. **Click:** "New Variable" or "Raw Editor"
3. **Paste these variables:**

```env
APP_NAME=EduTrack
APP_ENV=production
APP_DEBUG=false
APP_KEY=
APP_URL=

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}

SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=sync

LOG_CHANNEL=stack
LOG_LEVEL=error

BROADCAST_DRIVER=log
FILESYSTEM_DISK=local
```

**Note:** Leave `APP_KEY` and `APP_URL` empty for now - we'll fill them in next steps.

---

## 🎯 Step 5: Generate APP_KEY

### Option A: Generate Locally (Recommended)
```bash
# In your local project directory
php artisan key:generate --show
```

**Copy the output** (it looks like: `base64:xxxxxxxxxxxxx`)

### Option B: Use Online Generator
Go to: https://generate-random.org/laravel-key-generator

**Copy the generated key**

### Add APP_KEY to Railway:
1. Go back to Railway → Variables tab
2. Find `APP_KEY` variable
3. Paste the key you copied
4. **Click:** "Add" or "Save"

---

## 🎯 Step 6: Generate Public Domain

1. **Go to:** "Settings" tab (in your web service)
2. **Scroll down to:** "Domains" section
3. **Click:** "Generate Domain"
4. **Copy the URL** (e.g., `https://edutrack-production-abc123.up.railway.app`)

### Update APP_URL:
1. Go back to "Variables" tab
2. Find `APP_URL` variable
3. Paste your Railway domain URL
4. **Click:** "Add" or "Save"

---

## 🎯 Step 7: Redeploy

1. **Go to:** "Deployments" tab
2. **Click:** "Deploy" button (or it may auto-deploy)
3. **Wait:** 2-5 minutes for deployment to complete
4. **Look for:** Green checkmark ✅ next to deployment

### Check Deployment Logs:
- Click on the latest deployment
- Click "View Logs"
- Look for any errors (red text)
- Should see: "Application ready" or similar success message

---

## 🎯 Step 8: Run Database Migrations

### Install Railway CLI:
```bash
npm install -g @railway/cli
```

### Login and Link:
```bash
# Login to Railway
railway login

# Link to your project
railway link
```

**Select your project from the list**

### Run Migrations:
```bash
# Run migrations
railway run php artisan migrate --force

# Seed the database (optional)
railway run php artisan db:seed --class=CPSUVictoriasSeeder
```

---

## 🎯 Step 9: Create Admin Account

### Using Railway CLI:
```bash
railway run php artisan tinker
```

### In Tinker, run:
```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@edutrack.com';
$user->password = Hash::make('Admin123!');
$user->role = 'admin';
$user->status = 'Active';
$user->campus_status = 'approved';
$user->save();

// Create admin profile
$admin = new App\Models\Admin();
$admin->user_id = $user->id;
$admin->employee_id = 'ADM-001';
$admin->department = 'Administration';
$admin->status = 'Active';
$admin->save();

exit
```

---

## 🎯 Step 10: Test Your Application

1. **Visit your Railway URL** (from Step 6)
2. **You should see:** Login page
3. **Login with:**
   - Email: `admin@edutrack.com`
   - Password: `Admin123!`

### Test These Features:
- ✅ Admin dashboard loads
- ✅ Can view teachers
- ✅ Can view students
- ✅ Can create classes
- ✅ Teacher signup works
- ✅ Grade entry works

---

## 🔧 Troubleshooting

### Issue: "Application Error" or 500 Error

**Solution 1: Clear Cache**
```bash
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan view:clear
```

**Solution 2: Check Logs**
```bash
railway logs
```

**Solution 3: Verify Environment Variables**
- Go to Variables tab
- Make sure all variables are set
- Especially check `APP_KEY` and database variables

### Issue: Database Connection Failed

**Check:**
1. MySQL service is running (green dot)
2. Database variables use `${{MySQL.MYSQL_*}}` format
3. Both services are in the same project

**Fix:**
```bash
# Test database connection
railway run php artisan tinker
DB::connection()->getPdo();
exit
```

### Issue: Migrations Failed

**Run migrations manually:**
```bash
railway run php artisan migrate:fresh --force
```

**If that fails, check:**
- Database is accessible
- No syntax errors in migrations
- Enough database storage

### Issue: APP_KEY Not Set

**Generate new key:**
```bash
php artisan key:generate --show
```

**Add to Railway:**
- Variables tab → APP_KEY → Paste key → Save

### Issue: Page Not Found (404)

**Clear routes:**
```bash
railway run php artisan route:clear
railway run php artisan route:cache
```

---

## 📊 Monitoring Your Application

### View Logs:
```bash
# Real-time logs
railway logs

# Or in Railway dashboard
# Deployments → Latest → View Logs
```

### Check Metrics:
- Go to your service
- Click "Metrics" tab
- View CPU, Memory, Network usage

### Database Management:
- Click on MySQL service
- Go to "Data" tab
- View tables and data
- Can export database

---

## 🔄 Updating Your Application

### After making changes locally:

```bash
# Commit changes
git add .
git commit -m "Update feature"
git push origin main
```

**Railway will automatically:**
- Detect the push
- Build new version
- Deploy automatically
- Run migrations (if configured)

### Manual Redeploy:
1. Go to Deployments tab
2. Click "Deploy" button
3. Select "Redeploy"

---

## 💰 Railway Pricing

### Free Tier:
- **$5 credit per month**
- Enough for small projects
- No credit card required initially
- Resets monthly

### Usage Tips:
- Monitor your usage in dashboard
- Free tier is usually enough for development
- Upgrade to $5/month plan if needed

---

## 🎉 Success Checklist

- [ ] Railway account created
- [ ] Project deployed from GitHub
- [ ] MySQL database added
- [ ] Environment variables configured
- [ ] APP_KEY generated and set
- [ ] Public domain generated
- [ ] APP_URL updated
- [ ] Deployment successful (green checkmark)
- [ ] Migrations ran successfully
- [ ] Admin account created
- [ ] Can login to application
- [ ] Dashboard loads correctly
- [ ] All features working

---

## 📞 Need Help?

### Railway Support:
- **Docs:** https://docs.railway.app
- **Discord:** https://discord.gg/railway
- **Status:** https://status.railway.app

### Common Commands:
```bash
# View logs
railway logs

# Run artisan commands
railway run php artisan [command]

# Access database
railway run php artisan tinker

# Clear cache
railway run php artisan cache:clear

# Run migrations
railway run php artisan migrate --force

# Seed database
railway run php artisan db:seed
```

---

## 🚀 You're Live!

**Your application is now running at:**
`https://your-app.railway.app`

**Share it with:**
- Teachers
- Students
- Administrators
- Anyone who needs access

**Next Steps:**
1. Create teacher accounts
2. Set up courses and classes
3. Add students
4. Start using the system!

---

**Deployment Time:** ~10-15 minutes  
**Cost:** FREE (Railway free tier)  
**Difficulty:** Easy ⭐⭐☆☆☆

**Congratulations! 🎉**
