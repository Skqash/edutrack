# EduTrack System - Online Access via ngrok

## Status: ✅ RUNNING

Your EduTrack system is now accessible online via ngrok!

## Access Information

### Public URL
```
https://interlobular-ricardo-spinproof.ngrok-free.dev
```

### Local Development Server
```
http://127.0.0.1:8080
```

### ngrok Web Interface (Inspect Traffic)
```
http://127.0.0.1:4040
```

## Important Notes

### ngrok Free Plan Limitations
- URL changes every time you restart ngrok
- Session expires after 2 hours (need to restart)
- Limited to 40 connections per minute
- Shows ngrok warning page on first visit (click "Visit Site" to continue)

### Security Considerations
- This URL is publicly accessible - anyone with the link can access your system
- Do NOT share this URL publicly or on social media
- Only share with trusted testers
- Consider using ngrok authentication for added security

## How to Use

1. **Access the System**: Open the public URL in any browser
2. **Login**: Use your existing credentials
   - Teacher: teacher@example.com / password
   - Admin: admin@example.com / password
   - Super Admin: super@example.com / password

3. **Test Features**:
   - Create subjects (now working with nullable program_id)
   - Create classes and assign subjects
   - Enter grades with attendance integration
   - Record attendance
   - View reports

## Recent Fixes Applied

### 1. Subject Creation Fixed
- Made `program_id` nullable in subjects table
- Teachers can now create independent subjects without selecting a program
- Added year_level and semester fields to subject creation form

### 2. Class Assignment Fixed
- Independent subjects now appear in class creation dropdown
- Course selection is now optional for independent subjects
- Auto-creates "Independent Studies" course if needed

### 3. Attendance Integration Complete
- Attendance column appears in grade entry
- Attendance affects selected KSA category (Knowledge/Skills/Attitude)
- Configurable attendance settings per class

## Managing the Servers

### Check Server Status
```powershell
# List running processes
php artisan serve --help
```

### Stop Servers
The servers are running in background processes:
- Laravel Server: Terminal ID 3
- ngrok: Terminal ID 4

To stop them, close the terminal windows or use Ctrl+C

### Restart Servers
If you need to restart:
```powershell
# Stop existing processes first
# Then start Laravel server
php artisan serve --host=127.0.0.1 --port=8080

# In another terminal, start ngrok
ngrok http 8080
```

### Get New ngrok URL
If ngrok restarts, get the new URL from:
```powershell
# Visit ngrok web interface
http://127.0.0.1:4040
```

Or check the terminal output for the "Forwarding" line.

## Troubleshooting

### Port Already in Use
If port 8080 is busy, try another port:
```powershell
php artisan serve --port=8081
ngrok http 8081
```

### ngrok Session Expired
Free plan sessions expire after 2 hours. Just restart ngrok:
```powershell
ngrok http 8080
```

### Database Connection Issues
Make sure your .env file has correct database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edutrack_db
DB_USERNAME=root
DB_PASSWORD=
```

### 404 Errors
Clear Laravel cache:
```powershell
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Sharing with Others

When sharing the URL:
1. Provide the full ngrok URL
2. Mention they'll see an ngrok warning page first (click "Visit Site")
3. Provide login credentials
4. Specify which features to test

Example message:
```
Hi! Please test our EduTrack system:

URL: https://interlobular-ricardo-spinproof.ngrok-free.dev
(Click "Visit Site" on the ngrok warning page)

Login as Teacher:
Email: teacher@example.com
Password: password

Please test:
- Creating a new subject
- Creating a class with that subject
- Entering grades
- Recording attendance
```

## Monitoring Traffic

Visit the ngrok web interface to see:
- All HTTP requests
- Response times
- Request/response details
- Replay requests

URL: http://127.0.0.1:4040

## Upgrading ngrok (Optional)

For longer sessions and custom domains, consider upgrading:
- ngrok Pro: $8/month - 3 domains, no time limit
- ngrok Business: $20/month - 10 domains, IP whitelisting

Visit: https://ngrok.com/pricing

## Next Steps

1. Test the system thoroughly via the public URL
2. Share with team members or testers
3. Collect feedback
4. When done testing, stop the servers to save resources

## Support

If you encounter issues:
1. Check the Laravel logs: `storage/logs/laravel.log`
2. Check ngrok web interface: http://127.0.0.1:4040
3. Restart both servers
4. Clear all caches

---

**System Status**: ✅ Online and Ready
**Last Updated**: $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")
