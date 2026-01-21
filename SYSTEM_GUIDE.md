# EduTrack - Complete Education Management System

## 🎓 System Overview

EduTrack is a comprehensive education management system built with Laravel 11 and MySQL, designed to manage courses, subjects, classes, students, and teachers with a role-based access control system.

---

## ✅ **SYSTEM FULLY COMPLETED & TESTED**

All components are now fully functional:

- ✅ Database completely integrated
- ✅ All CRUD operations working
- ✅ Super Admin Dashboard with real statistics
- ✅ Admin Dashboard fully functional
- ✅ Complete user management (Admin, Teachers, Students)
- ✅ Course and Subject Management
- ✅ Class Management with student enrollment
- ✅ Authentication and Authorization
- ✅ Zero errors and warnings

---

## 🚀 **QUICK START**

### Prerequisites

- PHP 8.4.0+
- MySQL/MariaDB (via Laragon XAMPP)
- Composer
- Node.js (for frontend assets)

### Installation & Setup

```bash
# Navigate to project directory
cd c:\laragon\www\edutrack

# Install PHP dependencies
composer install

# Install frontend dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations with seeding
php artisan migrate:fresh --seed

# Build frontend assets
npm run dev
```

### Start the Application

```bash
# Using Laravel Artisan
php artisan serve

# Application will be available at: http://127.0.0.1:8000
```

---

## 📊 **TEST CREDENTIALS**

### Super Admin

```
Email: superadmin@example.com
Password: password123
Access: /super/dashboard
```

### Admin User

```
Email: admin@example.com
Password: password123
Access: /admin/dashboard
```

### Teachers (Sample)

```
Email: teacher1@example.com
Email: teacher2@example.com
Email: teacher3@example.com
Password: password123 (all)
Access: /teacher/dashboard
```

### Students (Sample)

```
Email: student1@example.com through student10@example.com
Password: password123 (all)
Access: Dashboard
```

---

## 📁 **DATABASE STRUCTURE**

### Users Table

- Stores all system users (admin, teachers, students)
- Fields: id, name, email, password, role, timestamps

### Super Admins Table

- Separate authentication for system administrators
- Fields: id, super_id (unique), first_name, last_name, email, password, contact_number

### Courses Table

- Stores course information
- Fields: id, course_code (unique), course_name, description, instructor_id (FK), status, credit_hours, timestamps
- Relationships: instructor (User model)

### Subjects Table

- Academic subjects within courses
- Fields: id, subject_code (unique), subject_name, category, credit_hours, course_id (FK), instructor_id (FK), description, timestamps
- Relationships: course (Course model), instructor (User model)

### Classes Table

- Student classes/sections
- Fields: id, class_name, class_level, section, capacity, teacher_id (FK), description, status, timestamps
- Relationships: teacher (User model), students (many-to-many)

### Class Students Pivot Table

- Links students to classes (many-to-many)
- Fields: id, class_id (FK), student_id (FK), status, timestamps
- Unique constraint on (class_id, student_id)

---

## 🔐 **AUTHENTICATION & AUTHORIZATION**

### Role-Based Access Control

1. **Super Admin**
    - Access: /super/dashboard
    - Permissions: System-wide management, view all statistics
    - Middleware: `role:super`

2. **Admin**
    - Access: /admin/\* (dashboard, courses, subjects, classes, students, teachers)
    - Permissions: Course and student management, user management
    - Middleware: `role:admin`

3. **Teacher**
    - Access: /teacher/dashboard
    - Permissions: View assigned courses and classes
    - Middleware: `role:teacher`

4. **Student**
    - Access: Student dashboard
    - Permissions: View enrolled classes and courses
    - Middleware: `role:student`

### Authentication Flow

1. User logs in via /login
2. AuthController validates credentials using appropriate guard (super or web)
3. CheckRole middleware verifies role and redirects to appropriate dashboard
4. SuperAdmin can access all routes

---

## 📊 **DASHBOARDS**

### Super Admin Dashboard (/super/dashboard)

**Real-time Statistics:**

- Total Users, Admins, Teachers, Students
- Total Courses (Active/Inactive)
- Total Subjects and Classes
- Class Capacity and Enrollment Overview
- User Distribution Chart

**Management Features:**

- Recent users list
- Recent courses list
- System management options
- Database backup option

### Admin Dashboard (/admin/dashboard)

**Key Metrics:**

- Total Students
- Total Teachers
- Total Courses
- Total Subjects
- Total Classes

**Management Sections:**

- Courses Management
- Subjects Management
- Classes Management
- Students Management
- Teachers Management

---

## 🎯 **FEATURE MODULES**

### 1. Courses Management

- **Create**: Add new courses with instructor assignment
- **Read**: View all courses with instructor and subject count
- **Update**: Modify course details
- **Delete**: Remove courses (cascade delete subjects)
- Route: `/admin/courses`

### 2. Subjects Management

- **Create**: Add subjects to courses with instructor assignment
- **Read**: View subjects with category and credit hours
- **Update**: Modify subject details
- **Delete**: Remove subjects
- Route: `/admin/subjects`

### 3. Classes Management

- **Create**: Create classes with teacher assignment and capacity
- **Read**: View classes with student enrollment and utilization
- **Update**: Modify class details and student capacity
- **Delete**: Remove classes
- **Features**: Student enrollment, capacity tracking, utilization percentage
- Route: `/admin/classes`

### 4. Students Management

- **Create**: Add new student users
- **Read**: View all students (paginated)
- **Update**: Modify student information
- **Delete**: Remove student accounts
- Route: `/admin/students`

### 5. Teachers Management

- **Create**: Add teacher users to system
- **Read**: View all teachers (paginated)
- **Update**: Modify teacher details
- **Delete**: Remove teacher accounts
- Route: `/admin/teachers`

---

## 🔧 **TECHNICAL STACK**

### Backend

- **Framework**: Laravel 11.x
- **Database**: MySQL 8.0+
- **ORM**: Eloquent
- **Authentication**: Laravel Auth (dual guards)
- **Routing**: RESTful routes with resource controllers

### Frontend

- **Template Engine**: Blade
- **CSS Framework**: Bootstrap 5
- **Charts**: Chart.js 3.9.1
- **Icons**: Font Awesome 6.4.0
- **Build Tool**: Vite

### Development Server

- **Host**: 127.0.0.1
- **Port**: 8000 (or 8001)
- **PHP Version**: 8.4.0

---

## 📋 **API ENDPOINTS**

### Authentication Routes

```
GET  /login                    → Show login form
POST /login                    → Process login
GET  /logout                   → Logout user
GET  /register                 → Show registration form
POST /register                 → Process registration
```

### Super Admin Routes

```
GET /super/dashboard           → Super Admin Dashboard
```

### Admin Routes (Prefix: /admin)

```
GET    /dashboard              → Admin Dashboard
GET    /courses                → List courses
GET    /courses/create         → Create course form
POST   /courses                → Store course
GET    /courses/{id}/edit      → Edit course form
PUT    /courses/{id}           → Update course
DELETE /courses/{id}           → Delete course

GET    /subjects               → List subjects
GET    /subjects/create        → Create subject form
POST   /subjects               → Store subject
GET    /subjects/{id}/edit     → Edit subject form
PUT    /subjects/{id}          → Update subject
DELETE /subjects/{id}          → Delete subject

GET    /classes                → List classes
GET    /classes/create         → Create class form
POST   /classes                → Store class
GET    /classes/{id}/edit      → Edit class form
PUT    /classes/{id}           → Update class
DELETE /classes/{id}           → Delete class

GET    /students               → List students
GET    /students/create        → Create student form
POST   /students               → Store student
GET    /students/{id}/edit     → Edit student form
PUT    /students/{id}          → Update student
DELETE /students/{id}          → Delete student

GET    /teachers               → List teachers
GET    /teachers/create        → Create teacher form
POST   /teachers               → Store teacher
GET    /teachers/{id}/edit     → Edit teacher form
PUT    /teachers/{id}          → Update teacher
DELETE /teachers/{id}          → Delete teacher
```

### Teacher Routes

```
GET /teacher/dashboard         → Teacher Dashboard
```

---

## 🗄️ **DATABASE MIGRATIONS**

All migrations have been created and executed successfully:

1. `create_users_table` - User accounts with roles
2. `create_password_reset_tokens_table` - Password reset functionality
3. `create_failed_jobs_table` - Failed job tracking
4. `create_personal_access_tokens_table` - API token management
5. `create_super_admins_table` - Super admin accounts
6. `update_users_table_add_role` - Add role column to users
7. `create_courses_table` - Course information
8. `create_subjects_table` - Academic subjects
9. `create_classes_table` - Student classes
10. `create_class_students_table` - Student-class enrollment pivot

---

## 🔍 **VALIDATION RULES**

### Course Creation

- `course_code`: Required, unique across all courses
- `course_name`: Required, string
- `instructor_id`: Required, must exist in users table
- `credit_hours`: Required, integer, minimum 1
- `status`: Required, must be "Active" or "Inactive"

### Subject Creation

- `subject_code`: Required, unique
- `subject_name`: Required, string
- `category`: Required, string
- `credit_hours`: Required, integer
- `course_id`: Required, must exist in courses
- `instructor_id`: Required, must exist in users

### Class Creation

- `class_name`: Required, string
- `capacity`: Required, integer, minimum 10
- `teacher_id`: Optional, must exist in users if provided
- `status`: Required, "Active" or "Inactive"

### User Creation

- `name`: Required, string
- `email`: Required, unique, valid email
- `password`: Required, minimum 8 characters
- `role`: Required, must be admin/teacher/student

---

## 📈 **DATABASE SEEDING**

The system comes with comprehensive test data:

**Users Created:**

- 1 Super Admin (superadmin@example.com)
- 1 Admin (admin@example.com)
- 3 Teachers (teacher1-3@example.com)
- 10 Students (student1-10@example.com)

**Courses Created:** 3 sample courses
**Subjects Created:** 3 sample subjects
**Classes Created:** 3 sample classes with teacher assignments

All credentials use password: `password123`

---

## 🛠️ **PROJECT STRUCTURE**

```
edutrack/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── CourseController.php
│   │   │   │   ├── SubjectController.php
│   │   │   │   ├── ClassController.php
│   │   │   │   ├── StudentController.php
│   │   │   │   └── TeacherController.php
│   │   │   ├── Super/
│   │   │   │   └── DashboardController.php
│   │   │   └── AuthController.php
│   │   ├── Middleware/
│   │   │   └── CheckRole.php
│   │   └── Kernel.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Course.php
│   │   ├── Subject.php
│   │   ├── ClassModel.php
│   │   └── SuperAdmin.php
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── super/
│   │   ├── teacher/
│   │   └── layouts/
│   ├── css/
│   └── js/
├── routes/
│   └── web.php
└── public/
```

---

## 🔄 **WORKFLOW EXAMPLES**

### Creating a New Course

1. Login as admin (admin@example.com)
2. Navigate to `/admin/courses`
3. Click "Add Course"
4. Fill form with:
    - Course Code: CS101
    - Course Name: Introduction to Computer Science
    - Instructor: Select from dropdown
    - Credit Hours: 3
    - Status: Active
5. Click Submit
6. Course will be created and subjects can be added to it

### Adding a Subject to a Course

1. Go to `/admin/subjects`
2. Click "Add Subject"
3. Select course from dropdown
4. Fill subject details
5. Submit

### Creating a Class

1. Go to `/admin/classes`
2. Click "Add Class"
3. Enter class details:
    - Class Name: Class A
    - Capacity: 60
    - Teacher: Select from teachers
4. Submit
5. Students can now enroll in this class

---

## 📞 **SUPPORT & TROUBLESHOOTING**

### Common Issues

**Issue: 404 on dashboard routes**

- Solution: Ensure migrations were run with `php artisan migrate:fresh --seed`

**Issue: Database connection error**

- Solution: Check `.env` file has correct database credentials

**Issue: Authentication failing**

- Solution: Verify user exists in database with correct role and password is hashed

**Issue: Styling not loading**

- Solution: Run `npm run dev` to compile frontend assets

---

## 🔐 **SECURITY FEATURES**

1. **Password Hashing**: All passwords automatically hashed using Laravel's Argon2 hasher
2. **CSRF Protection**: All forms protected with CSRF tokens
3. **Role-Based Access Control**: Middleware checks user roles on every request
4. **SQL Injection Prevention**: All queries use Eloquent ORM with parameterized queries
5. **Email Verification**: Can be enabled in config/auth.php
6. **Rate Limiting**: Built-in Laravel rate limiting available

---

## 📦 **DEPLOYMENT CHECKLIST**

Before deploying to production:

- [ ] Update `.env` with production database credentials
- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Configure proper email service for password resets
- [ ] Set up HTTPS/SSL certificate
- [ ] Configure proper file permissions
- [ ] Run database migrations: `php artisan migrate`
- [ ] Backup database before any production changes

---

## 📚 **ADDITIONAL RESOURCES**

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Eloquent ORM](https://laravel.com/docs/eloquent)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.0/)
- [Chart.js Documentation](https://www.chartjs.org/docs/latest/)

---

## 📝 **CHANGELOG**

### Version 1.0.0 (Current)

- ✅ Complete backend implementation
- ✅ Database integration with 4 new tables
- ✅ All CRUD operations working
- ✅ Super Admin Dashboard with real statistics
- ✅ Admin Dashboard with course/subject/class management
- ✅ Complete authentication and authorization
- ✅ Role-based access control
- ✅ Comprehensive database seeding
- ✅ Zero errors and warnings

---

## 👨‍💻 **DEVELOPMENT TEAM**

Built with Laravel 11 and modern web technologies.

---

## 📄 **LICENSE**

This project is provided as-is for educational purposes.

---

## ✨ **SYSTEM STATUS: FULLY OPERATIONAL** ✨

All features are working correctly. The system is ready for:

- Testing and validation
- User acceptance testing (UAT)
- Production deployment
- Feature expansion

**Last Updated:** January 20, 2026
**Status:** ✅ Production Ready
