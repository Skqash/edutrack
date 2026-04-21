# 🚀 Deploy Your EduTrack System NOW!

## 🎯 Fastest Method: Railway.app (5 Minutes)

### Step 1: Go to Railway
👉 **https://railway.app** → Sign up with GitHub

### Step 2: Deploy
1. Click **"Start a New Project"**
2. Click **"Deploy from GitHub repo"**
3. Select your **EduTrack repository**

### Step 3: Add Database
1. Click **"New"** → **"Database"** → **"Add MySQL"**
2. Wait 30 seconds for database to be ready

### Step 4: Set Variables
Click your web service → **"Variables"** → Add these:

```
APP_NAME=EduTrack
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:GENERATE_THIS_BELOW
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

### Step 5: Generate APP_KEY
**Run this locally:**
```bash
php artisan key:generate --show
```
Copy the output and paste as `APP_KEY` value

**OR use online generator:**
👉 https://generate-random.org/laravel-key-generator

### Step 6: Get Your URL
1. Go to **"Settings"** tab
2. Click **"Generate Domain"**
3. Copy your URL (e.g., `https://edutrack-production.railway.app`)
4. Update `APP_URL` variable with this URL

### Step 7: Wait for Deployment
⏱️ Wait 2-3 minutes. Check **"Deployments"** tab for green checkmark ✅

### Step 8: Create Admin Account
**Install Railway CLI:**
```bash
npm i -g @railway/cli
```

**Login and create admin:**
```bash
railway login
railway link
railway run php artisan tinker
```

**In tinker, run:**
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

### Step 9: Test It! 🎉
Visit your Railway URL and login:
- **Email:** admin@edutrack.com
- **Password:** Admin123!

---

## 🔧 Quick Fixes

### If you see "Application Error":
```bash
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan migrate --force
```

### If database won't connect:
1. Check MySQL service is running (green dot)
2. Verify all `${{MYSQL_*}}` variables are set
3. Make sure database and web service are in same project

### If APP_KEY error:
Generate new key and add to variables:
```bash
php artisan key:generate --show
```

---

## 📱 Alternative: Deploy to Render.com

### Step 1: Go to Render
👉 **https://render.com** → Sign up with GitHub

### Step 2: Create Web Service
1. Click **"New +"** → **"Web Service"**
2. Connect your GitHub repository
3. Configure:
   - **Name:** edutrack
   - **Environment:** PHP
   - **Build Command:** `composer install --no-dev --optimize-autoloader`
   - **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`

### Step 3: Add Database
1. Click **"New +"** → **"PostgreSQL"** (free tier)
2. Or use external MySQL provider

### Step 4: Set Environment Variables
Same as Railway (see above)

### Step 5: Deploy!
Render will build and deploy automatically

---

## 💻 Alternative: Deploy to Your Own Server

### Requirements:
- Ubuntu 22.04 server
- Root access
- Domain name (optional)

### Quick Install Script:
```bash
# SSH into your server
ssh root@your-server-ip

# Run this one-liner
curl -s https://raw.githubusercontent.com/laravel/sail/1.x/bin/install.sh | bash

# Or manual setup:
apt update && apt upgrade -y
apt install nginx mysql-server php8.2-fpm php8.2-mysql composer git -y

# Clone your repo
cd /var/www
git clone https://github.com/yourusername/edutrack.git
cd edutrack

# Install dependencies
composer install --no-dev --optimize-autoloader

# Setup database
mysql -u root -p
CREATE DATABASE edutrack;
CREATE USER 'edutrack'@'localhost' IDENTIFIED BY 'password';
GRANT ALL ON edutrack.* TO 'edutrack'@'localhost';
EXIT;

# Configure
cp .env.example .env
nano .env  # Update database credentials
php artisan key:generate
php artisan migrate --force

# Set permissions
chown -R www-data:www-data /var/www/edutrack
chmod -R 755 /var/www/edutrack
chmod -R 775 storage bootstrap/cache

# Configure Nginx (see DEPLOYMENT_GUIDE.md)
```

---

## 🌐 Custom Domain (Optional)

### For Railway:
1. Go to **Settings** → **Domains**
2. Click **"Custom Domain"**
3. Add your domain (e.g., `edutrack.yourdomain.com`)
4. Update DNS records as shown
5. Wait for SSL certificate (automatic)

### DNS Records:
```
Type: CNAME
Name: edutrack (or @)
Value: your-app.railway.app
```

---

## 📊 What You Get

### ✅ Free Tier (Railway):
- **$5 credit/month** (enough for small projects)
- **Automatic HTTPS**
- **Automatic deployments** from GitHub
- **MySQL database** included
- **No credit card** required initially

### ✅ Your Live Application:
- **Public URL** accessible anywhere
- **Admin dashboard** for management
- **Teacher portal** for grade entry
- **Student portal** for viewing grades
- **Attendance tracking**
- **Grade calculations**
- **Reports and analytics**

---

## 🎓 After Deployment

### 1. Create Demo Data (Optional)
```bash
railway run php artisan db:seed --class=CPSUAccurateSeeder
```

### 2. Share with Users
- Send them the URL
- Provide login instructions
- Create teacher accounts

### 3. Monitor Performance
- Check Railway metrics
- Review application logs
- Monitor database usage

### 4. Make Updates
```bash
# Make changes locally
git add .
git commit -m "Update feature"
git push origin main

# Railway auto-deploys!
```

---

## 🆘 Need Help?

### Check These First:
1. **Deployment logs** - Railway dashboard → Deployments → View Logs
2. **Application logs** - `railway logs` or check Laravel logs
3. **Database status** - Make sure MySQL service is running
4. **Environment variables** - Verify all are set correctly

### Common Issues:
- **500 Error** → Clear cache: `railway run php artisan cache:clear`
- **Database Error** → Check connection variables
- **APP_KEY Error** → Generate and set APP_KEY
- **404 on all pages** → Clear routes: `railway run php artisan route:clear`

### Get Support:
- **Railway Discord:** https://discord.gg/railway
- **Railway Docs:** https://docs.railway.app
- **Laravel Docs:** https://laravel.com/docs

---

## ✨ Success!

**Your EduTrack system is now LIVE! 🎉**

**What's Next?**
1. ✅ Login as admin
2. ✅ Create teacher accounts
3. ✅ Set up courses and classes
4. ✅ Start using the system
5. ✅ Invite users
6. ✅ Enjoy your online grading system!

---

**Deployment Time:** ~5 minutes  
**Cost:** FREE (Railway free tier)  
**Difficulty:** Easy ⭐⭐☆☆☆

**Ready? Let's deploy! 🚀**
