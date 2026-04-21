# PEST Testing Quick Start Guide
## EduTrack Learning Management System

---

## ✅ Installation Complete

PEST testing framework has been successfully installed and configured for the EduTrack system.

### What Was Installed
- ✅ **PEST** v3.8.6 - Modern PHP testing framework
- ✅ **PEST Laravel Plugin** - Laravel-specific testing helpers
- ✅ **9 Comprehensive Test Files** - Ready to use
- ✅ **91 New Tests** - Covering authentication, grading, attendance, and more
- ✅ **Browser Simulation Tests** - UI interaction testing
- ✅ **Test Factories** - Migrated to modern syntax

---

## 🚀 Running Tests

### Quick Start
```bash
cd c:\laragon\www\edutrack

# Run all tests
php vendor/bin/pest

# Run specific test file
php vendor/bin/pest tests/Feature/AuthenticationTest.php

# Run unit tests only
php vendor/bin/pest tests/Unit

# Run feature tests only
php vendor/bin/pest tests/Feature

# Run browser simulation tests
php vendor/bin/pest tests/Browser
```

### Advanced Options
```bash
# Stop on first failure
php vendor/bin/pest --bail

# Run specific test group
php vendor/bin/pest --filter="Authentication"

# Run in parallel (fast, 4 processes)
php vendor/bin/pest --parallel --processes=4

# Generate coverage report
php vendor/bin/pest --coverage --coverage-html=coverage

# Watch mode (re-runs on file changes)
php vendor/bin/pest --watch

# Retry failed tests only
php vendor/bin/pest --retry

# Quiet mode (minimal output)
php vendor/bin/pest tests/Feature --quiet
```

---

## 📊 Test Suites Available

### 1. Authentication Tests (14 tests)
File: `tests/Feature/AuthenticationTest.php`

Tests login, logout, registration, and role-based access control.

```bash
php vendor/bin/pest tests/Feature/AuthenticationTest.php
```

### 2. Grading System Tests (12 tests)
File: `tests/Feature/GradingSystemTest.php`

Tests grade entry, grading modes, and calculations.

```bash
php vendor/bin/pest tests/Feature/GradingSystemTest.php
```

### 3. Attendance System Tests (9 tests)
File: `tests/Feature/AttendanceTest.php`

Tests attendance signatures, approvals, and statistics.

```bash
php vendor/bin/pest tests/Feature/AttendanceTest.php
```

### 4. School Management Tests (8 tests)
File: `tests/Feature/SchoolManagementTest.php`

Tests CRUD operations and access control.

```bash
php vendor/bin/pest tests/Feature/SchoolManagementTest.php
```

### 5. API Endpoints Tests (12 tests)
File: `tests/Feature/APIEndpointsTest.php`

Tests JSON API endpoints and filtering.

```bash
php vendor/bin/pest tests/Feature/APIEndpointsTest.php
```

### 6. Unit Tests - Student Model (7 tests)
File: `tests/Unit/StudentModelTest.php`

```bash
php vendor/bin/pest tests/Unit/StudentModelTest.php
```

### 7. Unit Tests - ClassModel (7 tests) ✅ PASSING
File: `tests/Unit/ClassModelTest.php`

```bash
php vendor/bin/pest tests/Unit/ClassModelTest.php
```

### 8. Browser Simulation Tests (22 tests)
File: `tests/Browser/BrowserSimulationTest.php`

Tests UI interactions without JavaScript.

```bash
php vendor/bin/pest tests/Browser/BrowserSimulationTest.php
```

---

## 📈 Current Status

### Test Results Summary
```
Tests:    120 failed, 18 passed
Assertions: 74
Pass Rate: 13%
```

### Tests Passing ✅
- 7 ClassModel unit tests
- 11 Model relationship tests
- Various configuration tests

### Tests Needing Routes
Most failures are due to missing routes that need to be created:
- `/api/grades/*` - Grade entry endpoints
- `/api/students/*` - Student endpoints
- `/api/classes/*` - Class endpoints
- `/api/attendance/*` - Attendance endpoints
- `/admin/*` - Admin panel
- `/teacher/*` - Teacher dashboard

---

## 🛠️ Next Steps to Increase Pass Rate

### Step 1: Create Missing Routes
```bash
# Edit routes/api.php
# Add grade endpoints
# Add student endpoints
# Add attendance endpoints
# Add school endpoints
```

### Step 2: Create Controllers
```bash
php artisan make:controller GradeController --resource
php artisan make:controller AttendanceController --resource
php artisan make:controller StudentController --resource
```

### Step 3: Run Migrations
```bash
php artisan migrate
```

### Step 4: Re-run Tests
```bash
php vendor/bin/pest
```

---

## 📝 Writing New Tests

### Simple Test Example
```php
it('user can login', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->get('/dashboard');
    
    $response->assertStatus(200);
});
```

### Grouped Tests Example
```php
describe('Authentication', function () {
    it('login succeeds with correct credentials', function () {
        // Test code
    });
    
    it('login fails with wrong credentials', function () {
        // Test code
    });
});
```

### Setup and Cleanup
```php
uses(RefreshDatabase::class); // Auto-rollback database

beforeEach(function () {
    $this->user = User::factory()->create();
});

afterEach(function () {
    // Cleanup if needed
});
```

---

## 🔍 Viewing Test Code

### View specific test file
```
tests/Feature/AuthenticationTest.php
tests/Feature/GradingSystemTest.php
tests/Feature/AttendanceTest.php
tests/Unit/StudentModelTest.php
tests/Browser/BrowserSimulationTest.php
```

All test files are well-commented and follow best practices.

---

## 📊 Test Coverage

To see which code is covered by tests:

```bash
# Generate HTML coverage report
php vendor/bin/pest --coverage --coverage-html=coverage/html

# View in browser
start coverage/html/index.html

# Generate text report
php vendor/bin/pest --coverage --coverage-text

# Coverage for specific file
php vendor/bin/pest --coverage --coverage-only=app/Services/GradingService.php
```

---

## 🐛 Debugging Tests

### Verbose Output
```bash
# Show detailed error messages
php vendor/bin/pest tests/Feature/GradingSystemTest.php --no-coverage
```

### Print Debug Info
```php
it('example test', function () {
    dump('Debug info'); // Will print when test fails
    $value = calculate();
    dd($value); // Dump and die
});
```

### Run with Xdebug
```bash
php -d xdebug.mode=debug vendor/bin/pest tests/Feature/AuthenticationTest.php
```

---

## ⚡ Performance Tips

### Run Tests in Parallel
```bash
# Much faster - uses 4 processes
php vendor/bin/pest --parallel
```

### Run Only Changed Tests
```bash
# Only run tests that may be affected
php vendor/bin/pest --retry
```

### Filter Tests to Run
```bash
# Only Authentication tests
php vendor/bin/pest --filter="Authentication"

# Multiple filters
php vendor/bin/pest --filter="Grade|Attendance"
```

---

## 🎯 Test Maintenance

### Run Tests Before Committing
```bash
# Good practice - ensure no regressions
php vendor/bin/pest --bail

# If all pass, commit:
git commit -m "Feature: Add grading system"
```

### Update Tests When Code Changes
```bash
# If you change a model
# Update the corresponding test
# Run tests: php vendor/bin/pest

# If tests fail
# Fix the implementation to pass tests
# Commit both together
```

### Continuous Integration
For CI/CD pipelines (GitHub Actions, GitLab CI, etc.):
```bash
# Run all tests, generate coverage
php vendor/bin/pest --coverage --coverage-clover=coverage.xml
```

---

## 📚 PEST Commands Reference

| Command | Purpose |
|---------|---------|
| `php vendor/bin/pest` | Run all tests |
| `php vendor/bin/pest --init` | Initialize PEST |
| `php vendor/bin/pest --help` | Show help |
| `php vendor/bin/pest --version` | Show version |
| `php vendor/bin/pest --bail` | Stop on first failure |
| `php vendor/bin/pest --parallel` | Run in parallel |
| `php vendor/bin/pest --filter=NAME` | Run tests matching NAME |
| `php vendor/bin/pest --watch` | Watch mode (re-run on changes) |
| `php vendor/bin/pest --coverage` | Generate coverage report |
| `php vendor/bin/pest --profile` | Show test profile times |
| `php vendor/bin/pest --list-tests` | List all tests |
| `php vendor/bin/pest --testsuite=UNIT` | Run specific test suite |

---

## 🌐 Browser Testing Without JavaScript

The browser simulation tests use Laravel's HTTP testing client. They can:

✅ Test routes and redirects  
✅ Test HTML rendering  
✅ Test form submissions  
✅ Test authentication state  
✅ Test JSON responses  
✅ Simulate user interactions  

❌ Cannot test: JavaScript effects, CSS styling, animations

For full browser testing with JavaScript, Laravel Dusk would be needed (requires PHP zip extension not currently available).

---

## 📞 Troubleshooting

### Issue: "Call to undefined method"
**Solution:** Make sure the route exists and the controller method is implemented.

### Issue: "Database does not exist"
**Solution:** Run `php artisan migrate:test` to set up test database.

### Issue: "SQLSTATE[HY000]"
**Solution:** Check model relationships and foreign key constraints.

### Issue: Tests running slowly  
**Solution:** Use `php vendor/bin/pest --parallel --processes=4` for speed.

### Issue: "Assertion failed"
**Solution:** Check the expected value vs actual value in the test output.

---

## 📖 Learn More

- [PEST Documentation](https://pestphp.com)
- [Testing Laravel](https://laravel.com/docs/12.x/testing)
- [PHPUnit TestCase](https://phpunit.readthedocs.io/)
- [Test-Driven Development](https://laravel.com/docs/12.x/testing#test-driven-development)

---

## ✅ Checklist for Regular Testing

- [ ] Run tests before committing code
- [ ] Aim for 80%+ code coverage
- [ ] Keep tests organized and grouped
- [ ] Use descriptive test names
- [ ] Clean up test data with RefreshDatabase
- [ ] Review failing tests carefully
- [ ] Update tests when requirements change
- [ ] Use parallel execution for speed

---

**Happy Testing! 🚀**

For more detailed information, see `PEST_TEST_REPORT.md`
