# Quick Command Reference

## 🚀 Getting Started

### Start the Server (Access on Network)

```bash
cd c:\laragon\www\edutrack
php artisan serve --host=0.0.0.0 --port=8000
```

**Access URLs:**

- Local Computer: `http://localhost:8000`
- Other Devices (Same Wi-Fi): `http://192.168.1.6:8000`

### Fresh Database Setup

```bash
php artisan migrate:fresh --seed
```

### Run Migrations Only

```bash
php artisan migrate
```

---

## 📊 Key URLs

### Grade Management

```
Configure Ranges:    /teacher/assessment/configure/1
Enhanced Grade Entry: /teacher/grades/entry-enhanced/1/midterm
All Grades Dashboard: /teacher/grades
```

### Attendance

```
Manage Attendance: /teacher/attendance/manage/1
```

---

## 📝 Database Queries (Tinker)

### View Assessment Ranges

```php
php artisan tinker
>>> App\Models\AssessmentRange::all();
>>> App\Models\AssessmentRange::where('class_id', 1)->first();
```

### View Grades

```php
>>> App\Models\Grade::where('class_id', 1)->get();
>>> App\Models\Grade::where('term', 'midterm')->get();
```

### View Attendance

```php
>>> App\Models\StudentAttendance::all();
>>> App\Models\StudentAttendance::where('class_id', 1)->get();
```

### Clear All Assessment Ranges

```php
>>> App\Models\AssessmentRange::truncate();
>>> echo 'Ranges cleared';
```

### Clear All Grades

```php
>>> App\Models\Grade::truncate();
>>> echo 'Grades cleared';
```

---

## 🛠️ Artisan Commands

### Create New Model

```bash
php artisan make:model ModelName
```

### Create New Migration

```bash
php artisan make:migration migration_name
```

### Create New Controller

```bash
php artisan make:controller YourController
```

### Rollback Migrations

```bash
php artisan migrate:rollback
```

### Reset Database

```bash
php artisan migrate:reset
```

### Refresh Database (Rollback + Migrate)

```bash
php artisan migrate:refresh
```

### Seed Database

```bash
php artisan db:seed
```

---

## 📂 Important Files

### Models

```
app/Models/Grade.php                 - Grade calculation
app/Models/AssessmentRange.php       - Range configuration
app/Models/StudentAttendance.php     - Attendance tracking
app/Models/ClassModel.php            - Class info
app/Models/Student.php               - Student data
```

### Controllers

```
app/Http/Controllers/TeacherController.php - Main controller
```

### Views

```
resources/views/teacher/assessment/configure.blade.php       - Config form
resources/views/teacher/grades/entry_enhanced.blade.php     - Grade entry
resources/views/teacher/attendance/manage.blade.php         - Attendance
```

### Database

```
database/migrations/2026_01_21_000003_create_assessment_ranges_table.php
database/migrations/2026_01_21_000004_create_student_attendance_table.php
```

### Routes

```
routes/web.php - All routes defined here
```

---

## 📚 Documentation Files

| File                                             | Purpose                                     |
| ------------------------------------------------ | ------------------------------------------- |
| `CONFIGURABLE_ASSESSMENT_RANGES_GUIDE.md`        | Technical documentation (API, schema, code) |
| `CONFIGURABLE_RANGES_QUICKSTART.md`              | Step-by-step user guide                     |
| `CONFIGURABLE_RANGES_IMPLEMENTATION_COMPLETE.md` | Implementation summary                      |
| `CONFIGURABLE_RANGES_ARCHITECTURE.md`            | System diagrams and architecture            |
| `IMPLEMENTATION_SUMMARY.md`                      | Complete overview and checklist             |

---

## 🔐 Test Credentials

### Teacher Login

```
Email: teacher1@example.com
Password: password123
```

### Admin Login

```
Email: admin@example.com
Password: password123
```

---

## 🐛 Debugging

### Check for Errors

```bash
php artisan config:cache
php artisan cache:clear
php artisan route:clear
```

### View Logs

```bash
tail -f storage/logs/laravel.log
```

### Test with Tinker

```bash
php artisan tinker
>>> DB::table('assessment_ranges')->get();
```

---

## 📊 Calculate Final Grade (Example)

### Manual Calculation

```
Configuration: Q1=20, Q2=15, Exam=60

Student scores: Q1=18/20, Q2=12/15, Exam=55/60

Step 1: Normalize
  Q1: (18/20)×100 = 90
  Q2: (12/15)×100 = 80
  Exam: (55/60)×100 = 91.67

Step 2: Knowledge
  Q_avg = (90+80)/2 = 85
  K = (85×0.40) + (91.67×0.60) = 34 + 55 = 89

Step 3: Skills (assuming)
  S = 85

Step 4: Attitude (assuming)
  A = 90

Step 5: Final
  Final = (89×0.40) + (85×0.50) + (90×0.10)
        = 35.6 + 42.5 + 9
        = 87.1 → Grade: B
```

---

## 🔄 Common Tasks

### Add New Student to Class

```php
$student = Student::create([
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john@example.com',
    'class_id' => 1,
]);
```

### Create Grade Record

```php
$grade = Grade::create([
    'student_id' => 1,
    'class_id' => 1,
    'subject_id' => 1,
    'teacher_id' => 2,
    'term' => 'midterm',
    'q1' => 4.5,
    'q2' => 4.0,
    'final_grade' => 87.5,
    'grade_letter' => 'B',
]);
```

### Update Assessment Range

```php
$range = AssessmentRange::where('class_id', 1)->update([
    'quiz_1_max' => 25,
    'quiz_2_max' => 20,
]);
```

### Record Attendance

```php
$attendance = StudentAttendance::create([
    'student_id' => 1,
    'class_id' => 1,
    'subject_id' => 1,
    'term' => 'midterm',
    'total_classes' => 50,
    'present_classes' => 45,
    'absent_classes' => 5,
    'attendance_score' => 90.0,
]);
```

---

## 🚨 Troubleshooting

### "Table doesn't exist"

```bash
php artisan migrate
```

### "Class not found"

```bash
composer dump-autoload
```

### "Permission denied"

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Clear All Cache

```bash
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Rebuild Cache

```bash
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📱 Access from Different Devices

### Windows/Mac Computer

```
http://localhost:8000
```

### Android Phone (Same Network)

```
http://192.168.1.6:8000
(Replace IP with your computer's IP)
```

### iPhone (Same Network)

```
http://192.168.1.6:8000
(Replace IP with your computer's IP)
```

### Get Your IP Address

```bash
Windows:  ipconfig
Mac:      ifconfig
Linux:    ip addr
```

---

## 💾 Backup Database

### Export Database

```bash
mysqldump -u root -p edutrack > edutrack_backup.sql
```

### Import Database

```bash
mysql -u root -p edutrack < edutrack_backup.sql
```

---

## 📊 Database Tables

```
assessment_ranges
  ├─ id
  ├─ class_id
  ├─ subject_id
  ├─ teacher_id
  ├─ quiz_1_max to quiz_5_max
  ├─ exam ranges
  ├─ skills ranges
  ├─ attitude ranges
  ├─ attendance_max
  └─ timestamps

student_attendance
  ├─ id
  ├─ student_id
  ├─ class_id
  ├─ subject_id
  ├─ term
  ├─ attendance_score
  ├─ total_classes
  ├─ present_classes
  └─ timestamps

grades
  ├─ id
  ├─ student_id
  ├─ class_id
  ├─ subject_id
  ├─ teacher_id
  ├─ q1-q5
  ├─ exams
  ├─ skills components
  ├─ attitude components
  ├─ calculated scores
  ├─ final_grade
  ├─ grade_letter
  ├─ term
  └─ timestamps
```

---

## ⚡ Performance Tips

### Enable Query Caching

```php
// In .env
DB_QUERY_LOG=true
```

### Use Eager Loading

```php
$grades = Grade::with('student', 'subject', 'class')->get();
```

### Add Indexes

```php
Schema::table('grades', function (Blueprint $table) {
    $table->index(['class_id', 'term']);
});
```

---

## 🎓 Learning Resources

### Laravel Documentation

- https://laravel.com/docs

### Blade Templating

- https://laravel.com/docs/blade

### Eloquent ORM

- https://laravel.com/docs/eloquent

### Database Migrations

- https://laravel.com/docs/migrations

---

## 📞 Support

### Check Documentation

```
1. IMPLEMENTATION_SUMMARY.md - Overview
2. CONFIGURABLE_RANGES_QUICKSTART.md - How to use
3. CONFIGURABLE_ASSESSMENT_RANGES_GUIDE.md - Technical details
4. CONFIGURABLE_RANGES_ARCHITECTURE.md - System design
```

### Test System

```
1. Run migrations: php artisan migrate
2. Start server: php artisan serve --host=0.0.0.0
3. Visit: http://localhost:8000
4. Login and test features
```

---

**Last Updated**: January 21, 2026
**Status**: ✅ Ready to Use
