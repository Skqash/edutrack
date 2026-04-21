# Testing Instructions - Signup and Admin Fix

## Quick Test Steps

### Test 1: Teacher Signup ✅
1. Navigate to `/register`
2. Fill in the registration form:
   - First Name: Test
   - Last Name: Teacher
   - Email: test.teacher@example.com
   - Phone: 123-456-7890
   - Campus: (select any campus)
   - Password: Test1234
   - Confirm Password: Test1234
3. Click "Register"
4. **Expected Result:** Success message, redirected to login
5. **Check Database:**
   ```sql
   SELECT * FROM users WHERE email = 'test.teacher@example.com';
   -- Should show: role='teacher', campus_status='pending'
   
   SELECT * FROM teachers WHERE email = 'test.teacher@example.com';
   -- Should show: user_id linked to users table
   ```

### Test 2: Admin Dashboard Access ✅
1. Login as admin
2. Navigate to `/admin/dashboard`
3. **Expected Result:** Dashboard loads without errors
4. **Check Display:**
   - Total Teachers count
   - Approved Teachers count
   - Pending Teachers count
   - Rejected Teachers count
   - No SQL errors in browser console or Laravel logs

### Test 3: View Pending Approvals ✅
1. As admin, navigate to `/admin/teachers/campus-approvals`
2. **Expected Result:** List of pending teacher approvals
3. **Check Display:**
   - Pending teachers tab shows teachers with campus_status='pending'
   - Approved teachers tab shows teachers with campus_status='approved'
   - Rejected teachers tab shows teachers with campus_status='rejected'

### Test 4: Approve Teacher ✅
1. As admin, go to pending approvals
2. Click "Approve" on a pending teacher
3. **Expected Result:** Success message
4. **Check Database:**
   ```sql
   SELECT campus_status, campus_approved_at, campus_approved_by 
   FROM users 
   WHERE email = 'test.teacher@example.com';
   -- Should show: campus_status='approved', timestamps filled
   ```

### Test 5: Teacher Login After Approval ✅
1. Logout admin
2. Login as the approved teacher
3. Navigate to `/teacher/dashboard`
4. **Expected Result:** Full access to teacher dashboard
5. **Check:** No "pending approval" messages

## SQL Verification Queries

### Check User Record
```sql
SELECT 
    id,
    name,
    email,
    role,
    campus,
    campus_status,
    campus_approved_at,
    campus_approved_by,
    status
FROM users 
WHERE role = 'teacher'
ORDER BY created_at DESC
LIMIT 10;
```

### Check Teacher-User Link
```sql
SELECT 
    t.id as teacher_id,
    t.user_id,
    t.first_name,
    t.last_name,
    t.email as teacher_email,
    u.email as user_email,
    u.campus_status,
    u.campus
FROM teachers t
LEFT JOIN users u ON t.user_id = u.id
ORDER BY t.created_at DESC
LIMIT 10;
```

### Check Pending Approvals
```sql
SELECT 
    u.id,
    u.name,
    u.email,
    u.campus,
    u.campus_status,
    u.created_at
FROM users u
WHERE u.role = 'teacher' 
AND u.campus_status = 'pending'
ORDER BY u.created_at DESC;
```

## Error Checking

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

Look for:
- ❌ "Column not found: campus_status" errors (should NOT appear)
- ✅ Successful registration logs
- ✅ Successful approval logs

### Check Browser Console
1. Open Developer Tools (F12)
2. Go to Console tab
3. Look for:
   - ❌ JavaScript errors (should be none)
   - ❌ 500 Internal Server errors (should be none)
   - ✅ Successful AJAX responses

## Common Issues and Solutions

### Issue 1: "Column not found: campus_status"
**Solution:** Run `php artisan migrate` to ensure all migrations are applied

### Issue 2: Teacher has no user_id
**Solution:** The teacher record is orphaned. Delete and re-register:
```sql
DELETE FROM teachers WHERE user_id IS NULL;
```

### Issue 3: Approval doesn't work
**Check:**
1. Is the teacher record linked to a user? (`user_id` should not be null)
2. Does the user record exist?
3. Check Laravel logs for specific error

### Issue 4: Dashboard shows 0 teachers
**Check:**
1. Are there users with `role='teacher'` in the database?
2. Run the verification query above
3. Check if campus filter is too restrictive

## Manual Database Fix (If Needed)

### Link Existing Teachers to Users
```sql
-- For teachers without user_id
UPDATE teachers t
INNER JOIN users u ON t.email = u.email
SET t.user_id = u.id
WHERE t.user_id IS NULL AND u.role = 'teacher';
```

### Set Default Campus Status
```sql
-- For users without campus_status
UPDATE users 
SET campus_status = 'pending' 
WHERE role = 'teacher' 
AND campus_status IS NULL;
```

### Approve All Pending Teachers (Testing Only)
```sql
UPDATE users 
SET 
    campus_status = 'approved',
    campus_approved_at = NOW(),
    campus_approved_by = 1
WHERE role = 'teacher' 
AND campus_status = 'pending';
```

## Success Criteria

### ✅ All Tests Pass When:
1. Teacher can register without SQL errors
2. Admin dashboard loads and shows statistics
3. Pending approvals list displays correctly
4. Approval/rejection updates user record
5. Approved teacher has full access
6. No "Column not found" errors in logs

## Rollback Plan (If Issues Persist)

### Step 1: Check Migration Status
```bash
php artisan migrate:status
```

### Step 2: Rollback Last Migration (If Needed)
```bash
php artisan migrate:rollback --step=1
```

### Step 3: Re-run Migrations
```bash
php artisan migrate
```

### Step 4: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## Contact Support

If issues persist after following these steps:
1. Check `storage/logs/laravel.log` for detailed errors
2. Verify database schema matches expected structure
3. Ensure all migrations have run successfully
4. Check that relationships are properly defined in models

---

**Testing Guide Version:** 1.0  
**Last Updated:** April 20, 2026  
**Status:** Ready for Testing ✅
