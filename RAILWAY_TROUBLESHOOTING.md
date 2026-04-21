# 🔧 Railway Deployment Troubleshooting Guide

## Quick Diagnostic Commands

```bash
# Check if Railway CLI is working
railway --version

# View real-time logs
railway logs

# Check environment variables
railway variables

# Test database connection
railway run php artisan tinker
DB::connection()->getPdo();
exit

# Clear all caches
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan route:clear
railway run php artisan view:clear
```

---

## Common Issues & Solutions

### ❌ Issue 1: "Application Error" or Blank Page

**Symptoms:**
- White screen
- "Application Error" message
- 500 Internal Server Error

**Solutions:**

**Step 1: Check Logs**
```bash
railway logs
```
Look for error messages in red.

**Step 2: Clear Cache**
```bash
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan view:clear
```

**Step 3: Verify APP_KEY**
- Go to Railway → Variables
- Check if `APP_KEY` is set
- Should start with `base64:`
- If missing, generate: `php artisan key:generate --show`

**Step 4: Check APP_DEBUG**
- Temporarily set `APP_DEBUG=true` in Variables
- Visit your site to see detailed error
- **Remember to set back to `false` after fixing!**

---

### ❌ Issue 2: Database Connection Failed

**Symptoms:**
- "SQLSTATE[HY000] [2002] Connection refused"
- "Database connection failed"
- Can't login

**Solutions:**

**Step 1: Verify Database Service**
- Go to Railway dashboard
- Check MySQL service has green dot
- If red, click on it and check status

**Step 2: Check Database Variables**
Go to Web Service → Variables, verify:
```
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}
```

**Important:** Use `${{MySQL.MYSQL_*}}` format (with double curly braces)

**Step 3: Test Connection**
```bash
railway run php artisan tinker
DB::connection()->getPdo();
```
Should return: `PDO {#...}` (not an error)

**Step 4: Recreate Database**
- Delete MySQL service
- Add new MySQL service
- Update variables
- Run migrations again

---

### ❌ Issue 3: Migrations Failed

**Symptoms:**
- "Migration table not found"
- "Syntax error in migration"
- "Column already exists"

**Solutions:**

**Step 1: Fresh Migration**
```bash
railway run php artisan migrate:fresh --force
```
⚠️ **Warning:** This deletes all data!

**Step 2: Check Migration Files**
- Look for syntax errors in migration files
- Check for duplicate column names
- Verify table names are correct

**Step 3: Run Migrations One by One**
```bash
railway run php artisan migrate --path=/database/migrations/2026_03_22_000001_add_campus_approval_to_users.php --force
```

**Step 4: Check Migration Status**
```bash
railway run php artisan migrate:status
```

---

### ❌ Issue 4: APP_KEY Not Set

**Symptoms:**
- "No application encryption key has been specified"
- Can't access any page

**Solutions:**

**Step 1: Generate Key Locally**
```bash
php artisan key:generate --show
```

**Step 2: Add to Railway**
- Copy the output (including `base64:`)
- Go to Railway → Variables
- Add/Update `APP_KEY` variable
- Paste the key
- Click "Add"

**Step 3: Redeploy**
- Go to Deployments tab
- Click "Deploy" → "Redeploy"

---

### ❌ Issue 5: 404 on All Pages

**Symptoms:**
- Login page shows 404
- All routes return 404
- Only homepage works

**Solutions:**

**Step 1: Clear Route Cache**
```bash
railway run php artisan route:clear
railway run php artisan route:cache
```

**Step 2: Check .htaccess**
Make sure `public/.htaccess` exists with:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ index.php [L]
</IfModule>
```

**Step 3: Verify Procfile**
Check `Procfile` contains:
```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

---

### ❌ Issue 6: Assets Not Loading (CSS/JS)

**Symptoms:**
- Page loads but no styling
- Images don't show
- JavaScript not working

**Solutions:**

**Step 1: Check APP_URL**
- Go to Variables
- Verify `APP_URL` matches your Railway domain
- Should be: `https://your-app.railway.app`
- No trailing slash!

**Step 2: Check Asset URLs**
In your blade files, use:
```php
{{ asset('css/style.css') }}
```
Not:
```php
/css/style.css
```

**Step 3: Clear View Cache**
```bash
railway run php artisan view:clear
```

---

### ❌ Issue 7: Can't Login / Authentication Issues

**Symptoms:**
- "These credentials do not match our records"
- Login form doesn't work
- Redirects to login after logging in

**Solutions:**

**Step 1: Verify Admin Account Exists**
```bash
railway run php artisan tinker
App\Models\User::where('email', 'admin@edutrack.com')->first();
```

**Step 2: Reset Admin Password**
```bash
railway run php artisan tinker
$user = App\Models\User::where('email', 'admin@edutrack.com')->first();
$user->password = Hash::make('Admin123!');
$user->save();
exit
```

**Step 3: Check Session Driver**
- Variables → `SESSION_DRIVER=database`
- Run: `railway run php artisan migrate` (creates sessions table)

**Step 4: Clear Sessions**
```bash
railway run php artisan session:table
railway run php artisan migrate
```

---

### ❌ Issue 8: Deployment Keeps Failing

**Symptoms:**
- Red X on deployment
- Build fails
- Deployment never completes

**Solutions:**

**Step 1: Check Build Logs**
- Click on failed deployment
- Read error messages
- Look for missing dependencies

**Step 2: Verify composer.json**
- Make sure all dependencies are listed
- Check for syntax errors
- Verify PHP version requirement

**Step 3: Check nixpacks.toml**
Should contain:
```toml
[phases.setup]
nixPkgs = ['php82', 'php82Packages.composer', 'nodejs']

[phases.install]
cmds = ['composer install --no-dev --optimize-autoloader']

[start]
cmd = 'php artisan serve --host=0.0.0.0 --port=$PORT'
```

**Step 4: Manual Redeploy**
- Deployments → "Deploy" → "Redeploy"

---

### ❌ Issue 9: Out of Memory / Slow Performance

**Symptoms:**
- Application crashes randomly
- Very slow loading
- "Memory limit exceeded"

**Solutions:**

**Step 1: Optimize Composer**
```bash
# Locally
composer install --no-dev --optimize-autoloader
git add composer.lock
git commit -m "Optimize dependencies"
git push
```

**Step 2: Cache Configuration**
```bash
railway run php artisan config:cache
railway run php artisan route:cache
railway run php artisan view:cache
```

**Step 3: Upgrade Railway Plan**
- Free tier has limited resources
- Consider $5/month plan for more memory

---

### ❌ Issue 10: Railway CLI Not Working

**Symptoms:**
- `railway: command not found`
- CLI commands fail
- Can't link project

**Solutions:**

**Step 1: Reinstall CLI**
```bash
npm uninstall -g @railway/cli
npm install -g @railway/cli
```

**Step 2: Verify Installation**
```bash
railway --version
```

**Step 3: Login Again**
```bash
railway logout
railway login
```

**Step 4: Link Project**
```bash
railway link
```
Select your project from the list.

---

## 🔍 Debugging Checklist

When something goes wrong, check these in order:

1. **[ ] Check Deployment Status**
   - Green checkmark = deployed
   - Red X = failed
   - Yellow = deploying

2. **[ ] View Logs**
   ```bash
   railway logs
   ```

3. **[ ] Verify Environment Variables**
   - All required variables set
   - No typos in variable names
   - Database variables use correct format

4. **[ ] Test Database Connection**
   ```bash
   railway run php artisan tinker
   DB::connection()->getPdo();
   ```

5. **[ ] Check Migration Status**
   ```bash
   railway run php artisan migrate:status
   ```

6. **[ ] Clear All Caches**
   ```bash
   railway run php artisan config:clear
   railway run php artisan cache:clear
   railway run php artisan route:clear
   railway run php artisan view:clear
   ```

7. **[ ] Verify APP_KEY is Set**
   - Check Variables tab
   - Should start with `base64:`

8. **[ ] Check APP_URL Matches Domain**
   - Variables → APP_URL
   - Should match Railway domain

9. **[ ] Test Admin Login**
   - Visit site
   - Try logging in
   - Check credentials

10. **[ ] Review Recent Changes**
    - What changed before it broke?
    - Revert if needed

---

## 📞 Getting Help

### Railway Support Channels:

1. **Railway Discord** (Fastest)
   - https://discord.gg/railway
   - Active community
   - Railway staff available

2. **Railway Docs**
   - https://docs.railway.app
   - Comprehensive guides
   - Search for specific issues

3. **Railway Status**
   - https://status.railway.app
   - Check for outages
   - Service status

4. **GitHub Issues**
   - Check if it's a known issue
   - Report bugs

### When Asking for Help:

**Provide:**
- Error message (exact text)
- Deployment logs
- What you've tried
- Environment (Railway, PHP version, etc.)

**Don't share:**
- APP_KEY
- Database passwords
- API keys
- Sensitive data

---

## 🛠️ Useful Commands Reference

```bash
# View logs
railway logs

# Run artisan commands
railway run php artisan [command]

# Access tinker
railway run php artisan tinker

# Clear caches
railway run php artisan cache:clear
railway run php artisan config:clear
railway run php artisan route:clear
railway run php artisan view:clear

# Run migrations
railway run php artisan migrate --force
railway run php artisan migrate:fresh --force

# Seed database
railway run php artisan db:seed
railway run php artisan db:seed --class=CPSUVictoriasSeeder

# Check migration status
railway run php artisan migrate:status

# List routes
railway run php artisan route:list

# Check environment
railway run php artisan env

# Test database
railway run php artisan tinker
DB::connection()->getPdo();

# View variables
railway variables

# Link project
railway link

# Logout
railway logout
```

---

## 💡 Prevention Tips

1. **Always test locally first**
   ```bash
   php artisan serve
   ```

2. **Commit working code**
   ```bash
   git status
   git add .
   git commit -m "Working version"
   ```

3. **Use .env.example**
   - Keep it updated
   - Document all variables

4. **Monitor Railway usage**
   - Check metrics regularly
   - Watch for memory issues

5. **Keep dependencies updated**
   ```bash
   composer update
   ```

6. **Backup database regularly**
   - Export from Railway dashboard
   - Keep local backups

7. **Test after each deployment**
   - Login
   - Check main features
   - Review logs

---

## ✅ Health Check Script

Create this file locally to test everything:

```bash
#!/bin/bash
echo "🔍 Railway Health Check"
echo ""

echo "1. Testing Railway CLI..."
railway --version

echo ""
echo "2. Testing database connection..."
railway run php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database OK';"

echo ""
echo "3. Checking migrations..."
railway run php artisan migrate:status

echo ""
echo "4. Testing application..."
curl -I https://your-app.railway.app

echo ""
echo "✅ Health check complete!"
```

---

**Remember:** Most issues are solved by:
1. Checking logs
2. Clearing cache
3. Verifying environment variables
4. Redeploying

**Don't panic!** Railway makes it easy to fix issues. 🚀
