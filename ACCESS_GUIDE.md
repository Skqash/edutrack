# 🌐 EduTrack - System Access Guide

## Quick Access URLs

### Main Entry Point

```
http://127.0.0.1:8000
└─ Redirects to login page
```

### Authentication Routes

```
Login Page:        http://127.0.0.1:8000/login
Register Page:     http://127.0.0.1:8000/register
Forgot Password:   http://127.0.0.1:8000/forgot-password
Logout:            http://127.0.0.1:8000/logout
```

---

## 🔐 ROLE-BASED DASHBOARDS

### Super Admin Dashboard (Most Powerful)

```
URL:      http://127.0.0.1:8000/super/dashboard
Email:    superadmin@example.com
Password: password123
```

**Features:**

- System-wide statistics dashboard
- User count by role (14 total users)
- Course and subject management overview
- Class capacity and enrollment tracking
- User distribution chart
- Recent users and courses lists
- System management options

---

### Admin Dashboard (Educational Management)

```
URL:      http://127.0.0.1:8000/admin/dashboard
Email:    admin@example.com
Password: password123
```

**Features:**

- Course Management
- Subject Management
- Class Management
- Student Management
- Teacher Management
- Real-time statistics
- Quick action buttons

---

### Teacher Dashboard (Course Management)

```
URL:      http://127.0.0.1:8000/teacher/dashboard
Email:    teacher1@example.com OR teacher2@example.com OR teacher3@example.com
Password: password123
```

**Features:**

- View assigned courses
- View assigned classes
- Student roster
- Class schedules

---

### Student Dashboard (Learning)

```
URL:      http://127.0.0.1:8000/student/dashboard (if created)
Email:    student1@example.com through student10@example.com
Password: password123
```

**Features:**

- View enrolled classes
- View courses
- Access course materials
- View grades (if available)

---

## 📊 ADMIN MANAGEMENT MODULES

All URLs require Admin login (admin@example.com)

### Courses Management

```
List Courses:     http://127.0.0.1:8000/admin/courses
Create Course:    http://127.0.0.1:8000/admin/courses/create
Edit Course:      http://127.0.0.1:8000/admin/courses/{id}/edit
Delete Course:    http://127.0.0.1:8000/admin/courses/{id} [DELETE]
```

### Subjects Management

```
List Subjects:    http://127.0.0.1:8000/admin/subjects
Create Subject:   http://127.0.0.1:8000/admin/subjects/create
Edit Subject:     http://127.0.0.1:8000/admin/subjects/{id}/edit
Delete Subject:   http://127.0.0.1:8000/admin/subjects/{id} [DELETE]
```

### Classes Management

```
List Classes:     http://127.0.0.1:8000/admin/classes
Create Class:     http://127.0.0.1:8000/admin/classes/create
Edit Class:       http://127.0.0.1:8000/admin/classes/{id}/edit
Delete Class:     http://127.0.0.1:8000/admin/classes/{id} [DELETE]
```

### Students Management

```
List Students:    http://127.0.0.1:8000/admin/students
Create Student:   http://127.0.0.1:8000/admin/students/create
Edit Student:     http://127.0.0.1:8000/admin/students/{id}/edit
Delete Student:   http://127.0.0.1:8000/admin/students/{id} [DELETE]
```

### Teachers Management

```
List Teachers:    http://127.0.0.1:8000/admin/teachers
Create Teacher:   http://127.0.0.1:8000/admin/teachers/create
Edit Teacher:     http://127.0.0.1:8000/admin/teachers/{id}/edit
Delete Teacher:   http://127.0.0.1:8000/admin/teachers/{id} [DELETE]
```

---

## 📋 TEST DATA REFERENCE

### Super Admin Account

```
Account Type:  Super Admin
Email:         superadmin@example.com
Password:      password123
First Name:    Super
Last Name:     Admin
Contact:       +1234567890
```

### Admin Account

```
Account Type:  Admin
Email:         admin@example.com
Password:      password123
Name:          Administrator
Role:          admin
```

### Teacher Accounts

```
Teacher 1:
  Email:       teacher1@example.com
  Password:    password123
  Name:        Dr. Smith
  Role:        teacher

Teacher 2:
  Email:       teacher2@example.com
  Password:    password123
  Name:        Prof. Johnson
  Role:        teacher

Teacher 3:
  Email:       teacher3@example.com
  Password:    password123
  Name:        Ms. Williams
  Role:        teacher
```

### Student Accounts

```
Student 1:     student1@example.com / password123
Student 2:     student2@example.com / password123
Student 3:     student3@example.com / password123
...up to...
Student 10:    student10@example.com / password123
```

---

## 🎯 SAMPLE TEST WORKFLOWS

### Test 1: View System Statistics (Super Admin)

1. Go to http://127.0.0.1:8000/login
2. Enter: superadmin@example.com / password123
3. Click Login
4. View Super Admin Dashboard at http://127.0.0.1:8000/super/dashboard
5. See: 14 total users, 3 courses, 3 subjects, 3 classes
6. View: User distribution chart
7. View: Recent users and courses lists

### Test 2: Create a New Course (Admin)

1. Login as admin@example.com
2. Go to http://127.0.0.1:8000/admin/courses
3. Click "Create Course"
4. Fill in:
    - Course Code: CS201
    - Course Name: Advanced Programming
    - Instructor: Select Dr. Smith
    - Credit Hours: 4
    - Status: Active
5. Click Submit
6. Course appears in list with statistics updated

### Test 3: Manage Students (Admin)

1. Login as admin@example.com
2. Go to http://127.0.0.1:8000/admin/students
3. View paginated list of 10 students
4. Create new student: Click "Add Student"
5. Edit existing student: Click "Edit" button
6. Delete student: Click "Delete" button

### Test 4: View Class Utilization (Admin)

1. Login as admin@example.com
2. Go to http://127.0.0.1:8000/admin/classes
3. View class list with:
    - Total classes: 3
    - Total capacity: 180
    - Active classes: 3
4. See utilization percentage for each class
5. Color-coded progress bars indicate occupancy

### Test 5: Teacher View (Teacher)

1. Login as teacher1@example.com
2. See /teacher/dashboard
3. View assigned courses and classes
4. See students in assigned classes

---

## 🔄 WORKFLOW EXAMPLES

### Complete Course Creation Workflow

```
Step 1: Admin Login
  ├─ URL: http://127.0.0.1:8000/login
  ├─ Email: admin@example.com
  └─ Password: password123

Step 2: Navigate to Courses
  ├─ URL: http://127.0.0.1:8000/admin/courses
  └─ View: List of existing courses (3 courses)

Step 3: Create New Course
  ├─ URL: http://127.0.0.1:8000/admin/courses/create
  ├─ Fill: Course code, name, instructor, credits, status
  └─ Submit: Course creation form

Step 4: Verify Creation
  ├─ URL: http://127.0.0.1:8000/admin/courses
  └─ View: New course in list with updated statistics

Step 5: Create Subject Under Course
  ├─ URL: http://127.0.0.1:8000/admin/subjects/create
  ├─ Select: Course from dropdown
  ├─ Fill: Subject details and instructor
  └─ Submit: Subject creation form

Step 6: View Updated Subjects
  ├─ URL: http://127.0.0.1:8000/admin/subjects
  └─ View: New subject linked to course
```

---

## 💾 IMPORTANT ENDPOINTS SUMMARY

### Core Routes

```
POST   /login                          → Authenticate user
GET    /logout                         → End session
POST   /register                       → Create new user
GET    /forgot-password                → Password recovery form
POST   /forgot-password                → Send reset link
```

### Super Admin Routes (Prefix: /super)

```
GET    /super/dashboard                → System statistics dashboard
```

### Admin Routes (Prefix: /admin)

```
GET    /admin/dashboard                → Admin dashboard
GET    /admin/courses                  → List all courses
POST   /admin/courses                  → Store new course
GET    /admin/courses/create           → Course creation form
GET    /admin/courses/{id}/edit        → Edit course form
PUT    /admin/courses/{id}             → Update course
DELETE /admin/courses/{id}             → Delete course

GET    /admin/subjects                 → List subjects
POST   /admin/subjects                 → Store subject
GET    /admin/subjects/create          → Create form
GET    /admin/subjects/{id}/edit       → Edit form
PUT    /admin/subjects/{id}            → Update subject
DELETE /admin/subjects/{id}            → Delete subject

GET    /admin/classes                  → List classes
POST   /admin/classes                  → Store class
GET    /admin/classes/create           → Create form
GET    /admin/classes/{id}/edit        → Edit form
PUT    /admin/classes/{id}             → Update class
DELETE /admin/classes/{id}             → Delete class

GET    /admin/students                 → List students
POST   /admin/students                 → Store student
GET    /admin/students/create          → Create form
GET    /admin/students/{id}/edit       → Edit form
PUT    /admin/students/{id}            → Update student
DELETE /admin/students/{id}            → Delete student

GET    /admin/teachers                 → List teachers
POST   /admin/teachers                 → Store teacher
GET    /admin/teachers/create          → Create form
GET    /admin/teachers/{id}/edit       → Edit form
PUT    /admin/teachers/{id}            → Update teacher
DELETE /admin/teachers/{id}            → Delete teacher
```

### Teacher Routes

```
GET    /teacher/dashboard              → Teacher dashboard
```

---

## ⚙️ CONFIGURATION

### Server Information

```
Host:           127.0.0.1
Port:           8000 (or 8001)
Protocol:       HTTP
PHP Version:    8.4.0
Framework:      Laravel 11
Database:       MySQL
```

### Database Connection

```
Host:           localhost
Database:       edutrack
Username:       root
Password:       (empty - Laragon default)
Port:           3306
```

### Application Configuration

```
APP_NAME:       EduTrack
APP_ENV:        local
APP_DEBUG:      true
APP_KEY:        (auto-generated)
TIMEZONE:       UTC
```

---

## 📞 HELP & TROUBLESHOOTING

### Common Access Issues

**Issue: Cannot access http://127.0.0.1:8000**

- Solution 1: Ensure server is running with `php artisan serve`
- Solution 2: Check if port 8000 is in use, try 8001
- Solution 3: Check firewall settings

**Issue: Login not working**

- Solution: Verify database is populated with `php artisan db:seed`
- Credentials: admin@example.com / password123
- Check .env file database credentials

**Issue: 404 on admin routes**

- Solution: Verify migrations ran with `php artisan migrate:fresh --seed`
- Clear cache: `php artisan cache:clear`

**Issue: Cannot see dashboard statistics**

- Solution: Verify database records exist
- Run: `php artisan tinker`
- Check: User::count(), Course::count(), etc.

---

## 📚 DOCUMENTATION FILES

In the project root directory:

- `SYSTEM_GUIDE.md` - Complete system documentation
- `FINAL_STATUS.md` - Final verification and status
- `COMPLETE_SUMMARY.md` - Comprehensive summary
- `IMPLEMENTATION_COMPLETE.md` - Implementation details

---

## ✅ FINAL VERIFICATION

**All URLs Verified Working:**

- ✅ Login page loads
- ✅ Super Admin Dashboard displays statistics
- ✅ Admin Dashboard shows real data
- ✅ Courses list populated from database
- ✅ Subjects list populated from database
- ✅ Classes list with utilization tracking
- ✅ Students paginated list
- ✅ Teachers paginated list
- ✅ All action buttons functional
- ✅ Create/Edit/Delete operations working

---

**System Ready for Testing!** 🚀
