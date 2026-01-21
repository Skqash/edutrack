# 🚀 EduTrack Quick Start Guide

## **NEW FEATURES - How to Use**

---

## 1. **DEPARTMENTS** 👨‍💼

### Access Department Module

```
1. Login as Admin
2. Click "Departments" in sidebar (under Registrar)
3. You'll see a list of departments
```

### Add a Department

```
1. Click "Add Department" (green button)
2. Fill in:
   - Department Code (e.g., "CS", "ENG")
   - Department Name (e.g., "Computer Science")
   - Department Head (select a teacher)
   - Description
   - Status (Active/Inactive)
3. Click "Add Department"
```

### Edit or Delete

```
- Click the LARGE CYAN "Edit" button to modify
- Click the LARGE RED "Delete" button to remove
- Buttons are BIG and VISIBLE on all devices!
```

### On Mobile Phone

```
- Sidebar slides out from left when you click menu button (☰)
- Each department shows as a card
- "Edit" and "Delete" buttons are full-width
- Swipe or scroll to see all departments
```

---

## 2. **ATTENDANCE** 📋

### Access Attendance Module

```
1. Click "Attendance" in sidebar (under Academic)
2. View all attendance records
```

### Mark Attendance

```
1. Click "Mark Attendance" (green button)
2. Fill in:
   - Student (dropdown)
   - Class (dropdown)
   - Date (calendar picker)
   - Status: Present / Absent / Late / Leave
   - Notes (optional)
3. Click "Mark Attendance"
```

### Color-Coded Status

```
- 🟢 Green = Present
- 🔴 Red = Absent
- 🟡 Yellow = Late
- 🔵 Blue = Leave
```

### On Mobile

```
- Each record shows as a card
- Student name at top
- Status shown in colored badge
- Full-width edit/delete buttons
```

---

## 3. **GRADES** 📊

### Access Grades Module

```
1. Click "Grades" in sidebar (under Academic)
2. View all student grades
```

### Add a Grade

```
1. Click "Add Grade" (green button)
2. Fill in:
   - Student (dropdown)
   - Subject (dropdown)
   - Marks Obtained (e.g., 85)
   - Total Marks (e.g., 100)
   - Grade: A / B / C / D / F (auto or manual)
   - Semester (1-8)
   - Academic Year (e.g., 2023-24)
3. Click "Add Grade"
```

### Grade Scale

```
A = 90-100% (Green badge)
B = 80-89%  (Blue badge)
C = 70-79%  (Yellow badge)
D = 60-69%  (Gray badge)
F = <60%    (Red badge)
```

### View Grades

```
- Desktop: Full table with all columns
- Mobile: Card view with essential info
- Each card shows student name, subject, marks, grade
```

---

## 4. **USER MANAGEMENT** 👥

### Access User Management

```
1. Click "User Management" in sidebar (under System)
2. View all admin/superadmin/teacher users
```

### Create New User

```
1. Click "Add User" (green button)
2. Fill in:
   - Full Name
   - Email (must be unique)
   - Phone (optional)
   - Role: Admin / SuperAdmin / Teacher
   - Password (min 8 characters)
   - Confirm Password
   - Status: Active / Inactive
3. Click "Create User"
```

### Edit User

```
1. Click LARGE CYAN "Edit" button
2. Modify details
3. Password is optional (leave blank to keep current)
4. Click "Update User"
```

### Delete User

```
1. Click LARGE RED "Delete" button
2. Confirm deletion
```

---

## 📱 **MOBILE PHONE TIPS**

### Accessing on Phone Browser

```
1. Open: http://your-server-ip:8000
2. Login with admin credentials
3. Responsive layout automatically adapts
```

### Navigation on Mobile

```
- Tap ☰ (menu button) to open sidebar
- Tap menu item to navigate (sidebar closes)
- All forms are vertical (easy to scroll)
- Buttons are full-width and easy to tap
```

### Forms on Mobile

```
- One field per row
- Large input fields (easy to type)
- Dropdown menus work with scroll
- Date picker shows calendar
- Submit button at bottom (full-width)
```

### Tables on Mobile

```
- Convert to card layout
- One record per card
- Swipe left/right to scroll cards
- Actions buttons stacked vertically
```

---

## 🎯 **QUICK TIPS**

### Dashboard Cards

```
- Shows: Students, Teachers, Classes, Subjects counts
- Updates in real-time
- Click to see full list
- Responsive to screen size
```

### Sidebar

```
Desktop:
- Full-width with icons + labels
- Hover to highlight
- Active page shows green

Mobile:
- Hamburger menu (☰)
- Slides out from left
- Tap outside to close
- Tap item to navigate
```

### Colors Meaning

```
🟢 Green = Active / Success / Add
🔴 Red = Delete / Absent / Error
🔵 Blue = Info / Edit / View
🟡 Yellow = Warning / Late / Caution
🟣 Purple = Admin / User
```

---

## ⚠️ **IMPORTANT NOTES**

1. **Unique Fields**
    - Department Code: Must be unique
    - Attendance: One record per student per class per date
    - Grades: One record per student per subject per semester

2. **Required Fields** (marked with asterisk \*)
    - Must fill before saving
    - Error shown in red if missed

3. **Passwords**
    - Minimum 8 characters
    - Must be confirmed
    - Hashed in database (secure)

4. **Status**
    - Active: Record is visible and usable
    - Inactive: Record is hidden but not deleted

---

## 🔧 **TROUBLESHOOTING**

### Button Not Visible?

```
- Make sure you're on the right page
- Refresh the page (Ctrl+R or F5)
- Check screen size (mobile vs desktop)
- Try zooming out if on small screen
```

### Can't Edit/Delete?

```
- Check your permission level (must be Admin)
- Make sure you have the right role assigned
- Try logging out and back in
```

### Form Won't Submit?

```
- Check for red error messages
- Fill in all required fields (marked with *)
- Make sure unique fields aren't duplicated
- Check internet connection
```

### Mobile View Not Working?

```
- Clear browser cache
- Try different browser
- Check phone screen orientation
- Make sure you're accessing HTTP (not HTTPS if local)
```

---

## 📞 **CONTACT & SUPPORT**

For any issues or questions:

- Check the UPDATE_SUMMARY.md file
- Review model/controller code comments
- Check Laravel error logs in `storage/logs/`
- Database logs in `storage/logs/database.log`

---

## ✅ **VERIFICATION CHECKLIST**

After first login, verify:

- [ ] Departments page loads
- [ ] Can add a department
- [ ] Can edit department
- [ ] Can delete department (with confirmation)
- [ ] Attendance page loads
- [ ] Can mark attendance
- [ ] Grades page loads
- [ ] Can add grade
- [ ] User Management page loads
- [ ] Can create new user
- [ ] All buttons are LARGE and VISIBLE
- [ ] Mobile layout works on phone
- [ ] Sidebar collapses on mobile
- [ ] Forms are full-width on mobile
- [ ] Edit/Delete buttons are full-width on mobile

---

**All features are LIVE and READY to use!** 🎉

Happy Teaching! 📚
