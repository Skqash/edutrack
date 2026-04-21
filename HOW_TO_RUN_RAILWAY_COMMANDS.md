# 💻 How to Run Railway Commands

## 🎯 Where to Run Commands

You run Railway commands on **YOUR LOCAL COMPUTER**, not on Railway's website!

---

## 📍 Step-by-Step Guide

### Step 1: Open Terminal on Your Computer

**Choose ONE of these options:**

#### Option A: Command Prompt (Windows)
1. Press `Windows Key + R`
2. Type: `cmd`
3. Press Enter

#### Option B: PowerShell (Windows - Recommended)
1. Press `Windows Key + X`
2. Click "Windows PowerShell" or "Terminal"

#### Option C: Git Bash (If you have Git installed)
1. Right-click in your project folder
2. Click "Git Bash Here"

#### Option D: VS Code Terminal (If using VS Code)
1. Open VS Code
2. Press `` Ctrl + ` `` (backtick key)
3. Terminal opens at bottom

---

### Step 2: Navigate to Your Project Folder

```bash
# Go to your project directory
cd C:\laragon\www\edutrack
```

**Or wherever your edutrack project is located!**

---

### Step 3: Install Railway CLI (First Time Only)

**You only need to do this ONCE:**

```bash
npm install -g @railway/cli
```

**Wait for installation to complete** (takes 1-2 minutes)

---

### Step 4: Login to Railway

```bash
railway login
```

**What happens:**
- A browser window will open
- Click "Authorize" to connect Railway CLI to your account
- Terminal will show "Logged in successfully"

---

### Step 5: Link Your Project

```bash
railway link
```

**What happens:**
- Railway CLI will show a list of your projects
- Use arrow keys to select your **edutrack** project
- Press Enter
- Terminal will show "Project linked successfully"

---

### Step 6: Run Your Commands!

Now you can run Railway commands:

```bash
# Run migrations
railway run php artisan migrate --force

# Create admin account
railway run php artisan tinker

# Seed database
railway run php artisan db:seed --class=CPSUVictoriasSeeder

# View logs
railway logs

# Clear cache
railway run php artisan cache:clear
```

---

## 🖼️ Visual Guide

### What Your Terminal Should Look Like:

```
C:\Users\YourName> cd C:\laragon\www\edutrack

C:\laragon\www\edutrack> npm install -g @railway/cli
[Installing Railway CLI...]
✓ Railway CLI installed successfully

C:\laragon\www\edutrack> railway login
Opening browser for authentication...
✓ Logged in successfully as youremail@example.com

C:\laragon\www\edutrack> railway link
? Select a project: 
  > edutrack
    other-project
    
✓ Linked to edutrack

C:\laragon\www\edutrack> railway run php artisan migrate --force
Running migrations...
✓ Migrations completed successfully

C:\laragon\www\edutrack> _
```

---

## 📋 Complete Setup Process

### First Time Setup (Do Once):

```bash
# 1. Open terminal
# 2. Go to your project folder
cd C:\laragon\www\edutrack

# 3. Install Railway CLI
npm install -g @railway/cli

# 4. Login to Railway
railway login

# 5. Link your project
railway link
```

### Every Time You Need to Run Commands:

```bash
# 1. Open terminal
# 2. Go to your project folder
cd C:\laragon\www\edutrack

# 3. Run your command
railway run php artisan migrate --force
```

---

## 🎯 Quick Commands Reference

### Database Commands:
```bash
# Run migrations
railway run php artisan migrate --force

# Rollback migrations
railway run php artisan migrate:rollback

# Check migration status
railway run php artisan migrate:status

# Seed database
railway run php artisan db:seed
```

### Admin Account Creation:
```bash
# Open tinker
railway run php artisan tinker

# Then paste your admin creation code
# Type 'exit' when done
```

### Cache Commands:
```bash
# Clear all cache
railway run php artisan cache:clear

# Clear config cache
railway run php artisan config:clear

# Clear view cache
railway run php artisan view:clear

# Clear route cache
railway run php artisan route:clear
```

### Logs and Debugging:
```bash
# View live logs
railway logs

# View logs with follow
railway logs --follow
```

---

## 🚨 Troubleshooting

### Issue: "railway: command not found"
**Solution:** Railway CLI not installed
```bash
npm install -g @railway/cli
```

### Issue: "npm: command not found"
**Solution:** Node.js not installed
1. Download Node.js from: https://nodejs.org
2. Install it
3. Restart terminal
4. Try again

### Issue: "Not logged in"
**Solution:**
```bash
railway login
```

### Issue: "No project linked"
**Solution:**
```bash
railway link
```

### Issue: "Permission denied"
**Solution:** Run terminal as Administrator
1. Right-click Command Prompt or PowerShell
2. Click "Run as Administrator"
3. Try again

---

## 📍 Where Am I Running This?

### ✅ CORRECT - Run on YOUR Computer:
```
Your Computer
  ↓
Terminal/Command Prompt
  ↓
Your Project Folder (C:\laragon\www\edutrack)
  ↓
railway run php artisan migrate --force
  ↓
Command executes on Railway's server
```

### ❌ WRONG - Don't try to run here:
- ❌ Railway website
- ❌ Railway dashboard
- ❌ Browser console
- ❌ Railway logs page

---

## 🎓 Understanding Railway CLI

### What is Railway CLI?
- A command-line tool installed on YOUR computer
- Connects your local terminal to Railway's servers
- Lets you run commands on Railway from your computer

### How does it work?
```
Your Computer (Terminal)
        ↓
   Railway CLI
        ↓
   Railway API
        ↓
Your Railway Project
        ↓
   Command Executes
```

---

## 📝 Complete Example Session

Here's a complete example of what you'll do:

```bash
# Open PowerShell or Command Prompt

# Navigate to project
C:\Users\YourName> cd C:\laragon\www\edutrack

# Install Railway CLI (first time only)
C:\laragon\www\edutrack> npm install -g @railway/cli
✓ Installed successfully

# Login to Railway (first time only)
C:\laragon\www\edutrack> railway login
✓ Logged in as youremail@example.com

# Link project (first time only)
C:\laragon\www\edutrack> railway link
✓ Linked to edutrack

# Run migrations
C:\laragon\www\edutrack> railway run php artisan migrate --force
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table (45.23ms)
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table (32.45ms)
...
✓ All migrations completed

# Create admin account
C:\laragon\www\edutrack> railway run php artisan tinker
Psy Shell v0.11.0 (PHP 8.2.0 — cli)
>>> $user = new App\Models\User();
>>> $user->name = 'Admin';
>>> $user->email = 'admin@edutrack.com';
>>> $user->password = Hash::make('Admin123!');
>>> $user->role = 'admin';
>>> $user->status = 'Active';
>>> $user->campus_status = 'approved';
>>> $user->save();
=> true
>>> $admin = new App\Models\Admin();
>>> $admin->user_id = $user->id;
>>> $admin->employee_id = 'ADM-001';
>>> $admin->department = 'Administration';
>>> $admin->status = 'Active';
>>> $admin->save();
=> true
>>> exit
✓ Admin created successfully

# Done!
C:\laragon\www\edutrack> _
```

---

## ✅ Checklist: Am I Ready?

Before running Railway commands, check:

- [ ] Terminal is open on my computer
- [ ] I'm in my project folder (`cd C:\laragon\www\edutrack`)
- [ ] Railway CLI is installed (`railway --version` works)
- [ ] I'm logged in to Railway (`railway whoami` shows my email)
- [ ] Project is linked (`railway status` shows project info)

**If all checked, you're ready to run commands!**

---

## 🎯 Your Next Steps

### Right Now:

1. **Open terminal on your computer**
2. **Navigate to project:**
   ```bash
   cd C:\laragon\www\edutrack
   ```
3. **Install Railway CLI:**
   ```bash
   npm install -g @railway/cli
   ```
4. **Login:**
   ```bash
   railway login
   ```
5. **Link project:**
   ```bash
   railway link
   ```
6. **Run migrations:**
   ```bash
   railway run php artisan migrate --force
   ```
7. **Create admin:**
   ```bash
   railway run php artisan tinker
   ```
   (Then paste admin creation code)

---

## 💡 Pro Tips

### Tip 1: Stay in Project Folder
Always run Railway commands from your project folder:
```bash
cd C:\laragon\www\edutrack
```

### Tip 2: Check Connection
Verify you're connected:
```bash
railway status
```

### Tip 3: View Logs While Running
Open two terminals:
- Terminal 1: Run commands
- Terminal 2: Watch logs (`railway logs --follow`)

### Tip 4: Save Common Commands
Create a file `railway-commands.txt` with your common commands for easy copy-paste

---

## 🆘 Still Confused?

### Simple Answer:
1. Open **Command Prompt** or **PowerShell** on your computer
2. Type: `cd C:\laragon\www\edutrack`
3. Type: `npm install -g @railway/cli`
4. Type: `railway login`
5. Type: `railway link`
6. Type: `railway run php artisan migrate --force`

**That's it!**

---

## 📞 Quick Help

**Q: Where is the terminal?**  
A: Press `Windows Key + R`, type `cmd`, press Enter

**Q: What folder should I be in?**  
A: Your edutrack project folder (where you have your code)

**Q: Do I need to be online?**  
A: Yes, Railway CLI needs internet to connect to Railway

**Q: Can I use VS Code terminal?**  
A: Yes! Press Ctrl + ` in VS Code

**Q: How do I know it's working?**  
A: You'll see output messages showing progress

---

**Now go open your terminal and run those commands! 🚀**

