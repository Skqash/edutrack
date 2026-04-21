# 🚀 Quick Deploy to Railway.app (5 Minutes)

## Step 1: Push to GitHub (If not done)

```bash
git add .
git commit -m "Prepare for deployment"
git push origin main
```

## Step 2: Deploy to Railway

1. **Go to https://railway.app**
2. **Click "Start a New Project"**
3. **Click "Deploy from GitHub repo"**
4. **Select your repository**

## Step 3: Add MySQL Database

1. **Click "New" → "Database" → "Add MySQL"**
2. Railway will automatically create the database

## Step 4: Configure Environment Variables

Click on your web service → **Variables** tab → Add these:

```
APP_NAME=EduTrack
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}

DB_CONNECTION=mysql
DB_HOST=${{MYSQL_HOST}}
DB_PORT=${{MYSQL_PORT}}
DB_DATABASE=${{MYSQL_DATABASE}}
DB_USERNAME=${{MYSQL_USER}}
DB_PASSWORD=${{MYSQL_PASSWORD}}

SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=sync

LOG_CHANNEL=stack
LOG_LEVEL=error
```

### How to Get APP_KEY:

**Option A: Generate Locally**
```bash
php artisan key:generate --show
```
Copy the output (including `base64:`) and paste as APP_KEY

**Option B: Generate Online**
Go to https://generate-random.org/laravel-key-generator
Copy the generated key

## Step 5: Generate Public Domain

1. **Go to Settings tab**
2. **Click "Generate Domain"**
3. **Copy your URL** (e.g., `https://your-app.railway.app`)
4. **Update APP_URL** variable with this URL

## Step 6: Deploy!

Railway will automatically:
- ✅ Install dependencies
- ✅ Run migrations
- ✅ Start your application

**Wait 2-3 minutes for deployment to complete**

## Step 7: Create Admin Account

1. **Click on your service**
2. **Go to "Deployments" tab**
3. **Click on latest deployment**
4. **Click "View Logs"**
5. **Open a new terminal locally and run:**

```bash
# Connect to Railway CLI (install first if needed)
npm i -g @railway/cli
railway login
railway link
railway run php artisan tinker
```

Then in tinker:
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

## Step 8: Test Your Application

Visit your Railway URL:
- **Login:** `admin@edutrack.com` / `Admin123!`
- **Test signup:** Create a teacher account
- **Test features:** Check dashboard, grades, attendance

## 🎉 You're Live!

Your application is now running at: `https://your-app.railway.app`

---

## 🔧 Troubleshooting

### Issue: "Application Error"
**Check logs:**
1. Go to your service
2. Click "Deployments"
3. Click "View Logs"
4. Look for errors

**Common fixes:**
```bash
# Reconnect and run:
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan migrate --force
```

### Issue: Database Connection Failed
**Check variables:**
- Make sure all `${{MYSQL_*}}` variables are set
- Verify database service is running
- Check if database and web service are in same project

### Issue: 404 on All Pages
**Fix routing:**
```bash
railway run php artisan route:clear
railway run php artisan route:cache
```

---

## 📊 Monitor Your App

**View Logs:**
- Service → Deployments → View Logs

**Check Metrics:**
- Service → Metrics (CPU, Memory, Network)

**Database Access:**
- MySQL service → Connect → Copy connection string

---

## 🔄 Update Your App

**After making changes:**
```bash
git add .
git commit -m "Update feature"
git push origin main
```

Railway will automatically redeploy! 🚀

---

## 💰 Pricing

**Free Tier:**
- $5 credit per month
- Enough for small projects
- No credit card required

**Upgrade if needed:**
- $5/month for more resources
- Pay as you go

---

## ✅ Success Checklist

- [ ] Repository connected to Railway
- [ ] MySQL database added
- [ ] Environment variables configured
- [ ] APP_KEY generated
- [ ] Public domain generated
- [ ] Deployment successful (green checkmark)
- [ ] Admin account created
- [ ] Can login and access dashboard
- [ ] Teacher signup works
- [ ] All features functional

---

**Need Help?** 
- Railway Docs: https://docs.railway.app
- Railway Discord: https://discord.gg/railway
- Check deployment logs for errors
