# PEST Testing - Command Reference Card
## EduTrack LMS

```
📍 Project: EduTrack Learning Management System
📅 Date: April 8, 2026
🔬 Testing Framework: PEST 3.8.6
⚡ Status: Ready to Use (18/138 tests passing)
```

---

## 🎯 Most Common Commands

```bash
# Run all tests
php vendor/bin/pest

# Run feature tests only
php vendor/bin/pest tests/Feature

# Run unit tests only
php vendor/bin/pest tests/Unit

# Run browser tests
php vendor/bin/pest tests/Browser

# Stop on first failure
php vendor/bin/pest --bail
```

---

## 🚀 Speed & Performance

```bash
# Run tests in parallel (4x faster)
php vendor/bin/pest --parallel

# Run with 8 processes
php vendor/bin/pest --parallel --processes=8

# Watch mode (auto-re-run on file save)
php vendor/bin/pest --watch

# Only run failing tests
php vendor/bin/pest --retry
```

---

## 🔍 Filtering & Selection

```bash
# Run tests matching "Grade"
php vendor/bin/pest --filter="Grade"

# Run multiple filters
php vendor/bin/pest --filter="Grade|Attendance"

# Run specific file
php vendor/bin/pest tests/Feature/GradingSystemTest.php

# Run specific test suite
php vendor/bin/pest --testsuite=Feature

# List all tests without running
php vendor/bin/pest --list-tests
```

---

## 📊 Coverage & Reporting

```bash
# Generate coverage report
php vendor/bin/pest --coverage

# Generate HTML coverage
php vendor/bin/pest --coverage --coverage-html=coverage

# Generate text coverage
php vendor/bin/pest --coverage --coverage-text

# Coverage for specific file
php vendor/bin/pest --covers="App\Models\Student"

# Show test execution time
php vendor/bin/pest --profile
```

---

## 🧪 Test Suites by Feature

```bash
# Authentication tests
php vendor/bin/pest tests/Feature/AuthenticationTest.php

# Grading system tests
php vendor/bin/pest tests/Feature/GradingSystemTest.php

# Attendance tests
php vendor/bin/pest tests/Feature/AttendanceTest.php

# School management tests
php vendor/bin/pest tests/Feature/SchoolManagementTest.php

# API endpoints tests
php vendor/bin/pest tests/Feature/APIEndpointsTest.php

# Student model tests
php vendor/bin/pest tests/Unit/StudentModelTest.php

# ClassModel tests (✅ ALL PASSING)
php vendor/bin/pest tests/Unit/ClassModelTest.php

# Browser simulation tests
php vendor/bin/pest tests/Browser/BrowserSimulationTest.php
```

---

## 🔴 Debugging

```bash
# Verbose output
php vendor/bin/pest tests/Feature/GradingSystemTest.php --no-coverage

# Show all errors
php vendor/bin/pest --display-warnings --display-notices

# With Xdebug
php -d xdebug.mode=debug vendor/bin/pest tests/Feature/AuthenticationTest.php

# Quiet output
php vendor/bin/pest --quiet

# With colors (helps readability)
php vendor/bin/pest --colors
```

---

## 📁 Test Files Overview

| File | Tests | Command |
|------|-------|---------|
| AuthenticationTest.php | 14 | `pest tests/Feature/AuthenticationTest.php` |
| GradingSystemTest.php | 12 | `pest tests/Feature/GradingSystemTest.php` |
| AttendanceTest.php | 9 | `pest tests/Feature/AttendanceTest.php` |
| SchoolManagementTest.php | 8 | `pest tests/Feature/SchoolManagementTest.php` |
| APIEndpointsTest.php | 12 | `pest tests/Feature/APIEndpointsTest.php` |
| StudentModelTest.php | 7 | `pest tests/Unit/StudentModelTest.php` |
| ClassModelTest.php | 7 ✅ | `pest tests/Unit/ClassModelTest.php` |
| BrowserSimulationTest.php | 22 | `pest tests/Browser/BrowserSimulationTest.php` |

---

## 🎯 Pre-Commit Workflow

```bash
# Before committing:
php vendor/bin/pest --bail          # Stop if any test fails

# If all pass:
git add .
git commit -m "Feature: Add grading system"

# If any fail:
# 1. Read the error
# 2. Fix the code
# 3. Re-run tests
# 4. Commit when all pass
```

---

## ⚙️ CI/CD Integration

```bash
# For GitHub Actions / GitLab CI:
php vendor/bin/pest \
  --coverage \
  --coverage-clover=coverage.xml \
  --coverage-html=coverage/html \
  --parallel \
  --colors

# Show results:
echo "✅ Tests passed"
```

---

## 🚀 Quick Development Cycle

```bash
# Terminal 1 - Watch tests while coding
php vendor/bin/pest --watch

# Terminal 2 - Your editor
# Edit code and save - tests auto-run!
```

---

## 📊 Current Test Status

```
Total Tests: 138
✅ Passing: 18
❌ Failing: 120
📈 Pass Rate: 13%

Next Step: Create missing API routes to increase pass rate
```

---

## 💡 Pro Tips

1. **Use `--parallel` for speed**
   ```bash
   php vendor/bin/pest --parallel --processes=4
   ```

2. **Watch mode during development**
   ```bash
   php vendor/bin/pest --watch
   ```

3. **Filter to work on one area**
   ```bash
   php vendor/bin/pest --filter="Grade"
   ```

4. **Check coverage regularly**
   ```bash
   php vendor/bin/pest --coverage --coverage-text
   ```

5. **Use bail to fail fast**
   ```bash
   php vendor/bin/pest --bail
   ```

---

## 🔗 Related Documentation

- Full Report: `PEST_TEST_REPORT.md`
- Quick Start: `PEST_QUICK_START.md`
- Official Docs: https://pestphp.com

---

## ✅ Test Categories

### ✅ Tests Passing
- ClassModel relationships (7 tests)
- Other unit tests (11 tests)

### ⚠️ Tests Awaiting Routes
- Grade entry API (6 tests)
- Student API (6 tests)
- Attendance API (6 tests)
- School management (8 tests)

### ⚠️ Tests Awaiting Implementations  
- Authentication endpoints (14 tests)
- Grading calculations (12 tests)
- Approval workflows (9 tests)

### 🌐 Browser Tests Ready
- Dashboard UI tests (22 tests)
- Navigation tests
- Form submission tests

---

## 🎓 Learning Resources

| Resource | Link |
|----------|------|
| PEST Docs | https://pestphp.com |
| Laravel Testing | https://laravel.com/docs/testing |
| PHPUnit | https://phpunit.de |
| TDD Guide | https://laravel.com/docs/testing#test-driven-development |

---

**Quick Copy-Paste Commands:**

```bash
# Most common
php vendor/bin/pest                    # Run all
php vendor/bin/pest --bail             # Stop on fail
php vendor/bin/pest --parallel          # Fast mode
php vendor/bin/pest --filter="Grade"   # Specific tests
php vendor/bin/pest --watch             # Development mode
php vendor/bin/pest --coverage          # Coverage report
```

---

*Last Updated: April 8, 2026*  
*Framework: PEST 3.8.6*
