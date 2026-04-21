# 🧪 Comprehensive Testing Suite for Attendance & Grading System

**File Created:** April 7, 2026  
**Framework:** Laravel PHPUnit Testing  
**Total Test Classes:** 5  
**Total Test Methods:** 85+

---

## 📁 Test Structure

```
tests/
├── Feature/
│   ├── AttendanceSignatureTest.php         (13 tests)
│   ├── GradingModeTest.php                 (14 tests)
│   └── ComponentManagementTest.php         (26 tests)
├── Unit/
│   ├── GradingCalculationServiceTest.php   (16 tests)
│   └── AttendanceSignatureModelTest.php    (19 tests)
└── TestCase.php (Base class with setup)
```

---

## 🧪 Test Categories

### 1. FEATURE TESTS - AttendanceSignatureTest.php (13 tests)

**Location:** `tests/Feature/AttendanceSignatureTest.php`

Tests for the AttendanceSignatureController and e-signature workflows:

| Test # | Method | Purpose |
|--------|--------|---------|
| 1 | `test_index_displays_signatures_for_class` | Display all signatures paginated |
| 2 | `test_create_shows_upload_form` | Show upload form for e-signature |
| 3 | `test_store_digital_signature` | Store base64 digital signature |
| 4 | `test_approve_signature` | Approve pending signature |
| 5 | `test_reject_signature` | Reject signature with remarks |
| 6 | `test_delete_signature` | Soft delete signature |
| 7 | `test_get_signature_statistics` | Get statistics (total, approved, pending) |
| 8 | `test_cannot_store_signature_for_unauthorized_student` | Authorization check |
| 9 | `test_validate_signature_required_fields` | Validate form input |
| 10 | `test_index_filters_signatures_by_status` | Filter by status |
| 11+ | Additional authorization & validation tests | Field validation |

**Run These Tests:**
```bash
php artisan test tests/Feature/AttendanceSignatureTest.php
```

---

### 2. FEATURE TESTS - GradingModeTest.php (14 tests)

**Location:** `tests/Feature/GradingModeTest.php`

Tests for all 4 grading modes and mode switching:

| Test # | Method | Purpose |
|--------|--------|---------|
| 1 | `test_show_grading_mode_selector` | Display mode selector UI |
| 2 | `test_update_mode_to_manual` | Switch to Manual mode |
| 3 | `test_update_mode_to_automated` | Switch to Automated mode |
| 4 | `test_update_mode_to_hybrid` | Switch to Hybrid with config |
| 5 | `test_calculate_standard_mode_grades` | Calc Standard (KSA weighted) |
| 6 | `test_manual_mode_accepts_direct_grades` | Manual mode direct entry |
| 7 | `test_automated_mode_requires_score_input` | Auto mode from scores |
| 8 | `test_hybrid_mode_per_component_selection` | Hybrid per-component choice |
| 9 | `test_can_switch_modes_preserves_entries` | Mode switch preserves data |
| 10 | `test_invalid_mode_rejected` | Validate mode values |
| 11 | `test_mode_affects_grade_display` | UI changes per mode |
| 12+ | Additional mode integration tests | Cross-mode validation |

**Run These Tests:**
```bash
php artisan test tests/Feature/GradingModeTest.php
```

---

### 3. FEATURE TESTS - ComponentManagementTest.php (26 tests)

**Location:** `tests/Feature/ComponentManagementTest.php`

Complete CRUD testing for grade components:

| Test # | Method | Purpose |
|--------|--------|---------|
| 1 | `test_get_components_for_class` | Fetch all components |
| 2 | `test_add_component` | Create new component |
| 3 | `test_add_component_with_auto_weight` | Auto-calculate weight |
| 4 | `test_edit_component` | Update component details |
| 5 | `test_edit_component_weight_redistribution` | Update weight & auto-redistribute |
| 6 | `test_delete_component` | Soft delete component |
| 7 | `test_delete_component_redistributes_weight` | Weight auto-redistribution on delete |
| 8 | `test_delete_component_cascades_entries` | Cascade delete entries |
| 9 | `test_reorder_components` | Drag-to-reorder |
| 10 | `test_get_component_templates` | List pre-built templates |
| 11 | `test_apply_component_template` | Apply template (Knowledge/Skills/Attitude) |
| 12 | `test_validate_component_max_score` | Validate positive max_score |
| 13 | `test_component_name_unique_per_class_term` | Unique name validation |
| 14 | `test_cannot_edit_component_unauthorized` | Authorization checks |
| 15 | `test_bulk_delete_components` | Delete multiple components |
| 16 | `test_duplicate_component` | Clone existing component |
| 17 | `test_get_component_statistics` | Get stats (avg, count, etc) |
| 18+ | Additional CRUD & validation tests | Edge cases |

**Run These Tests:**
```bash
php artisan test tests/Feature/ComponentManagementTest.php
```

---

### 4. UNIT TESTS - GradingCalculationServiceTest.php (16 tests)

**Location:** `tests/Unit/GradingCalculationServiceTest.php`

Core calculation logic for all 4 grading modes:

| Test # | Method | Purpose |
|--------|--------|---------|
| 1 | `test_calculate_standard_mode_ksa_weights` | KSA: (K*0.4 + S*0.5 + A*0.1) |
| 2 | `test_calculate_manual_mode_direct_grade` | Manual: Direct grade entry |
| 3 | `test_calculate_automated_mode_from_scores` | Auto: Avg(scores) → grade |
| 4 | `test_calculate_hybrid_mode_per_component` | Hybrid: Per-component calc |
| 5 | `test_validate_grades` | Validate grade array |
| 6 | `test_validate_grades_negative` | Reject negative grades |
| 7 | `test_validate_grades_over_100` | Reject grades > 100 |
| 8 | `test_apply_grading_scale_conversion` | Points to letter (A-F) |
| 9 | `test_handle_missing_student_entries` | Handle incomplete data |
| 10 | `test_calculate_class_average` | Calculate class avg |
| 11 | `test_identify_high_low_performers` | Sort by performance |
| 12+ | Additional calculation tests | Edge cases & rounding |

**Run These Tests:**
```bash
php artisan test tests/Unit/GradingCalculationServiceTest.php
```

---

### 5. UNIT TESTS - AttendanceSignatureModelTest.php (19 tests)

**Location:** `tests/Unit/AttendanceSignatureModelTest.php`

E-signature model and database operations:

| Test # | Method | Purpose |
|--------|--------|---------|
| 1 | `test_create_attendance_signature` | Create signature record |
| 2 | `test_get_signature_student` | Student relationship |
| 3 | `test_get_signature_class` | Class relationship |
| 4 | `test_approve_signature` | Approve method |
| 5 | `test_reject_signature` | Reject method |
| 6 | `test_archive_signature` | Archive method |
| 7 | `test_signature_status_enum` | Status enums |
| 8 | `test_signature_type_enum` | Type enums (digital/upload/pen) |
| 9 | `test_store_base64_signature` | Store base64 data |
| 10 | `test_store_file_path_signature` | Store file path |
| 11 | `test_track_signature_metadata` | IP & user agent tracking |
| 12 | `test_query_signatures_by_status` | Query by status |
| 13 | `test_query_signatures_by_term` | Query by term |
| 14 | `test_soft_delete_signature` | Soft delete |
| 15 | `test_restore_soft_deleted_signature` | Restore |
| 16 | `test_permanently_delete_signature` | Force delete |
| 17 | `test_valid_terms` | Term validation |
| 18 | `test_can_update_existing_signature` | Update existing |
| 19 | `test_signature_verification_flag` | Verification flag |

**Run These Tests:**
```bash
php artisan test tests/Unit/AttendanceSignatureModelTest.php
```

---

## 🚀 Running Tests

### Run ALL tests
```bash
php artisan test
```

### Run specific test file
```bash
php artisan test tests/Feature/GradingModeTest.php
php artisan test tests/Unit/GradingCalculationServiceTest.php
```

### Run specific test
```bash
php artisan test tests/Feature/GradingModeTest.php --filter test_calculate_standard_mode_grades
```

### Run with verbose output
```bash
php artisan test --verbose
```

### Run with code coverage
```bash
php artisan test --coverage
```

### Run tests in parallel (faster)
```bash
php artisan test --parallel
```

### Run and stop on first failure
```bash
php artisan test --stop-on-failure
```

---

## 🎯 Test Coverage

### Attendance & E-Signatures
- ✅ Upload signatures (digital, file, pen-based)
- ✅ Approve/reject workflow
- ✅ Authorization checks
- ✅ Filtering and statistics
- ✅ Model relationships
- ✅ Soft delete/restore
- ✅ Metadata tracking

### Grading Modes (4-Mode System)
- ✅ Standard mode (KSA weighted calculation)
- ✅ Manual mode (direct grade entry)
- ✅ Automated mode (auto-calculation from scores)
- ✅ Hybrid mode (per-component mode selection)
- ✅ Mode switching preserves data
- ✅ Grade validation

### Component Management
- ✅ Add/edit/delete components
- ✅ Weight redistribution on add/remove
- ✅ Reordering
- ✅ Template application
- ✅ Bulk operations
- ✅ Cascade delete entries
- ✅ Authorization checks
- ✅ Field validation

### Calculation Service
- ✅ KSA weighted averaging
- ✅ Component averaging
- ✅ Grade validation
- ✅ Grading scale conversion (points → letters)
- ✅ Class statistics
- ✅ Missing data handling

---

## ✅ Test Database

All tests use an **in-memory SQLite database** and:
- ✅ Auto-migrate before each test
- ✅ Rollback after each test
- ✅ Use factories for test data
- ✅ Clean state between tests

---

## 📊 Expected Test Results

When running all tests, you should see:

```
Tests:         85 passed (or more)
Assertions:   200+ passed
Time:         ~30-45 seconds
```

---

## 🔧 Factories Used

Tests automatically create:
- School instances
- Teacher users
- Student users with relationships
- ClassModel instances
- Subject instances
- AssessmentComponent instances
- ComponentEntry instances
- AttendanceSignature instances
- GradingScaleSetting instances

All factories use `RefreshDatabase` trait for clean state.

---

## 🐛 Debugging Failed Tests

### View detailed failure output
```bash
php artisan test --verbose
```

### Run single failing test
```bash
php artisan test tests/Feature/GradingModeTest.php::test_calculate_standard_mode_grades --verbose
```

### Check database state in test
```php
// Inside test
$this->assertDatabaseHas('table_name', ['column' => 'value']);
```

### Use tinker to inspect
```bash
php artisan tinker
> App\Models\Student::count()
```

---

## 📝 Best Practices Followed

✅ **Setup/Teardown**
- Proper setUp() for each test
- RefreshDatabase between tests
- Factory pattern for test data

✅ **Assertions**
- Clear assertion messages
- Multiple assertions per test
- Edge case testing

✅ **Authorization**
- Test unauthorized access
- Test role-based access
- Test data isolation

✅ **Validation**
- Test valid inputs
- Test invalid inputs
- Test missing required fields

✅ **Relationships**
- Test foreign key relationships
- Test cascading deletes
- Test eager loading

✅ **State Management**
- Test soft deletes/restore
- Test status transitions
- Test data preservation on mode changes

---

## 📦 Dependencies

Ensure these are installed:
```bash
composer require --dev phpunit/phpunit
composer require --dev fakerphp/faker
```

Already configured in `phpunit.xml`.

---

## 🎓 Test Examples

### Example 1: Feature Test
```php
public function test_calculate_standard_mode_grades()
{
    // Arrange
    GradingScaleSetting::create(['grading_mode' => 'standard', ...]);
    AssessmentComponent::create([...]);
    ComponentEntry::create(['score' => 80]);

    // Act
    $grades = $this->calculator->calculateStandardMode($classId, 'midterm');

    // Assert
    $this->assertAlmostEquals(85.5, $grades[0]['final_grade'], 1);
}
```

### Example 2: Unit Test
```php
public function test_approve_signature()
{
    // Arrange
    $signature = AttendanceSignature::factory()->create(['status' => 'pending']);

    // Act
    $signature->approve($teacherId, 'Approved');

    // Assert
    $this->assertEquals('approved', $signature->status);
    $this->assertTrue($signature->is_verified);
}
```

---

## 🚀 Continuous Integration

Tests can be run in CI/CD pipelines:

```bash
# GitHub Actions
php artisan test --coverage

# GitLab CI
php artisan test

# Jenkins
php artisan test --stop-on-failure
```

---

## 💡 Next Steps

1. **Run all tests:**
   ```bash
   php artisan test
   ```

2. **View coverage:**
   ```bash
   php artisan test --coverage
   ```

3. **Fix any failures** by reviewing error output

4. **Add more tests** for:
   - API endpoints
   - Event listeners
   - Mail notifications
   - Job queues

---

**Total Lines of Test Code:** ~1,500+  
**Total Test Assertions:** 200+  
**Estimated Time to Run:** 30-45 seconds  
**Framework:** PHPUnit 10.x + Laravel Testing Features

