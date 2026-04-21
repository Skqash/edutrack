# ✅ Railway Deployment Quick Checklist

Print this and check off each step as you complete it!

---

## Before You Start

- [ ] Code is committed to GitHub
- [ ] All changes are pushed (`git push origin main`)
- [ ] You have a GitHub account

---

## Railway Setup (5 minutes)

### 1. Create Account
- [ ] Go to https://railway.app
- [ ] Sign up with GitHub
- [ ] Authorize Railway

### 2. Deploy Project
- [ ] Click "New Project"
- [ ] Select "Deploy from GitHub repo"
- [ ] Choose your EduTrack repository
- [ ] Wait for initial deployment

### 3. Add Database
- [ ] Click "New" → "Database" → "Add MySQL"
- [ ] Wait 30 seconds for provisioning
- [ ] See green dot on MySQL service

---

## Configuration (5 minutes)

### 4. Set Environment Variables
- [ ] Click on Web Service (not database)
- [ ] Go to "Variables" tab
- [ ] Add these variables:

```
APP_NAME=EduTrack
APP_ENV=production
APP_DEBUG=false
APP_KEY=(generate in next step)
APP_URL=(generate in step 6)

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}

SESSION_DRIVER=database
CACHE_DRIVER=database
LOG_LEVEL=error
```

### 5. Generate APP_KEY
- [ ] Run locally: `php artisan key:generate --show`
- [ ] Copy the output (starts with `base64:`)
- [ ] Paste into APP_KEY variable in Railway
- [ ] Click "Add" or "Save"

### 6. Generate Domain
- [ ] Go to "Settings" tab
- [ ] Click "Generate Domain"
- [ ] Copy the URL (e.g., `https://your-app.railway.app`)
- [ ] Go back to "Variables" tab
- [ ] Paste URL into APP_URL variable
- [ ] Click "Add" or "Save"

---

## Deployment (3 minutes)

### 7. Wait for Deployment
- [ ] Go to "Deployments" tab
- [ ] Wait for green checkmark ✅
- [ ] Click deployment → "View Logs"
- [ ] Check for errors (should see success messages)

---

## Database Setup (5 minutes)

### 8. Install Railway CLI
```bash
npm install -g @railway/cli
```
- [ ] CLI installed successfully

### 9. Login and Link
```bash
railway login
railway link
```
- [ ] Logged in to Railway
- [ ] Project linked

### 10. Run Migrations
```bash
railway run php artisan migrate --force
```
- [ ] Migrations completed successfully
- [ ] No errors in output

### 11. Seed Database (Optional)
```bash
railway run php artisan db:seed --class=CPSUVictoriasSeeder
```
- [ ] Seeding completed
- [ ] Sample data created

---

## Create Admin Account (2 minutes)

### 12. Create Admin User
```bash
railway run php artisan tinker
```

Then paste:
```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@edutrack.com';
$user->password = Hash::make('Admin123!');
$user->role = 'admin';
$user->status = 'Active';
$user->campus_status = 'approved';
$user->save();

$admin = new App\Models\Admin();
$admin->user_id = $user->id;
$admin->employee_id = 'ADM-001';
$admin->department = 'Administration';
$admin->status = 'Active';
$admin->save();

exit
```

- [ ] Admin user created
- [ ] No errors

---

## Testing (5 minutes)

### 13. Test Application
- [ ] Visit your Railway URL
- [ ] See login page (not error page)
- [ ] Login with: `admin@edutrack.com` / `Admin123!`
- [ ] Dashboard loads successfully
- [ ] Can navigate to Teachers page
- [ ] Can navigate to Students page
- [ ] Can navigate to Classes page
- [ ] Try teacher signup
- [ ] All features work

---

## 🎉 Success!

### Your Application is Live!

**URL:** `https://your-app.railway.app`

**Admin Login:**
- Email: `admin@edutrack.com`
- Password: `Admin123!`

**If you seeded the database:**
- Admin (Victorias): `admin.victorias@cpsu.edu.ph` / `admin123`
- Teacher: `maria.santos@cpsu.edu.ph` / `teacher123`

---

## 🔧 Quick Fixes

### If something goes wrong:

**Clear Cache:**
```bash
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan view:clear
```

**Check Logs:**
```bash
railway logs
```

**Restart Deployment:**
- Deployments tab → Click "Deploy" → "Redeploy"

**Check Database:**
```bash
railway run php artisan tinker
DB::connection()->getPdo();
exit
```

---

## 📊 Important URLs

- **Your App:** `https://your-app.railway.app`
- **Railway Dashboard:** https://railway.app/dashboard
- **Railway Docs:** https://docs.railway.app
- **Railway Discord:** https://discord.gg/railway

---

## 💡 Pro Tips

1. **Bookmark your Railway dashboard**
2. **Save your Railway URL**
3. **Keep admin credentials secure**
4. **Monitor usage in Railway dashboard**
5. **Check logs if anything breaks**
6. **Railway auto-deploys on git push**

---

## ⏱️ Total Time: ~20 minutes

**Congratulations! Your EduTrack system is now live! 🎉**

---

**Need help?** Check `RAILWAY_DEPLOYMENT_STEPS.md` for detailed instructions.
