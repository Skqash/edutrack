# Attendance & Grade Integration Guide

## Overview
This guide explains how attendance records are calculated and integrated into the final grade system.

## Attendance Calculation Formula

### Basic Formula
```
Attendance Score = (Attendance Count / Total Meetings) × 50 + 50
```

Where:
- **Attendance Count** = Present + Late (both count as attended)
- **Total Meetings** = Total class meetings for the term (Midterm or Final)
- **Score Range** = 50-100 (capped at 100)

### Example Calculations

**Example 1: Perfect Attendance**
- Total Meetings: 20
- Present: 20, Late: 0
- Attendance Count: 20
- Score: (20/20) × 50 + 50 = **100**

**Example 2: 80% Attendance**
- Total Meetings: 20
- Present: 15, Late: 1, Absent: 4
- Attendance Count: 16
- Score: (16/20) × 50 + 50 = **90**

**Example 3: 50% Attendance (Minimum)**
- Total Meetings: 20
- Present: 10, Late: 0, Absent: 10
- Attendance Count: 10
- Score: (10/20) × 50 + 50 = **75**

**Example 4: 0% Attendance**
- Total Meetings: 20
- Present: 0, Late: 0, Absent: 20
- Attendance Count: 0
- Score: (0/20) × 50 + 50 = **50**

## Attendance Status Types

| Status | Counts as Attended? | Description |
|--------|-------------------|-------------|
| Present | ✅ Yes | Student was present |
| Late | ✅ Yes | Student was late but attended |
| Absent | ❌ No | Student was absent |
| Leave | ❌ No | Student was on approved leave |

## Integration with Final Grade

### Current Grade Calculation (Without Attendance)
```
Final Grade = (Knowledge × 40%) + (Skills × 50%) + (Attitude × 10%)
```

### Proposed Grade Calculation (With Attendance)

#### Option 1: Attendance as Part of Attitude
```
Attitude Score = (Behavior × 50%) + (Awareness × 25%) + (Attendance × 25%)
Final Grade = (Knowledge × 40%) + (Skills × 50%) + (Attitude × 10%)
```

#### Option 2: Attendance as Separate Component
```
Final Grade = (Knowledge × 35%) + (Skills × 45%) + (Attitude × 10%) + (Attendance × 10%)
```

#### Option 3: Attendance as Multiplier (Recommended)
```
Base Grade = (Knowledge × 40%) + (Skills × 50%) + (Attitude × 10%)
Final Grade = Base Grade × (Attendance Score / 100)
```

This ensures students with poor attendance get proportionally lower grades.

## Database Structure

### Tables Involved

1. **attendance** - Individual attendance records
   - student_id
   - class_id
   - date
   - status (Present/Late/Absent/Leave)
   - term (Midterm/Final)

2. **classes** - Class configuration
   - total_meetings_midterm
   - total_meetings_final
   - attendance_percentage (weight in final grade)

3. **student_attendance** - Calculated attendance scores
   - student_id
   - class_id
   - term
   - attendance_score (calculated)
   - total_classes
   - present_classes
   - absent_classes

## Implementation Steps

### Step 1: Configure Total Meetings
Teachers must set the total number of meetings for each term in the class settings:
```php
// In class configuration
$class->total_meetings_midterm = 20;
$class->total_meetings_final = 25;
$class->attendance_percentage = 10; // 10% of final grade
```

### Step 2: Record Attendance
Teachers record attendance for each class meeting:
```php
Attendance::create([
    'student_id' => $studentId,
    'class_id' => $classId,
    'date' => '2026-03-19',
    'status' => 'Present', // or Late, Absent, Leave
    'term' => 'Midterm'
]);
```

### Step 3: Calculate Attendance Score
Use the AttendanceCalculationService:
```php
$service = new AttendanceCalculationService();
$result = $service->calculateAttendanceScore($studentId, $classId, 'Midterm');

// Returns:
// [
//     'attendance_score' => 85.00,
//     'attendance_percentage' => 70.00,
//     'attendance_count' => 14,
//     'total_meetings' => 20,
//     'present_count' => 13,
//     'late_count' => 1,
//     'absent_count' => 6,
//     'leave_count' => 0
// ]
```

### Step 4: Integrate with Final Grade
Add attendance to the grade calculation:
```php
// Get component grades
$knowledgeAvg = 85;
$skillsAvg = 90;
$attitudeAvg = 88;

// Get attendance score
$attendanceScore = $service->calculateAttendanceScore($studentId, $classId, $term)['attendance_score'];

// Calculate base grade
$baseGrade = ($knowledgeAvg * 0.40) + ($skillsAvg * 0.50) + ($attitudeAvg * 0.10);

// Apply attendance multiplier
$finalGrade = $baseGrade * ($attendanceScore / 100);
```

## API Endpoints

### Get Attendance Score
```
GET /api/attendance/score/{studentId}/{classId}/{term}
```

Response:
```json
{
    "attendance_score": 85.00,
    "attendance_percentage": 70.00,
    "attendance_count": 14,
    "total_meetings": 20,
    "present_count": 13,
    "late_count": 1,
    "absent_count": 6,
    "leave_count": 0
}
```

### Get Class Attendance Summary
```
GET /api/attendance/summary/{classId}/{term}
```

### Update Attendance Records
```
POST /api/attendance/update/{classId}/{term}
```

## UI Integration

### Display Attendance in Grade Entry

Add an attendance column to the grade entry table:

```blade
<th>Attendance</th>
...
<td>
    @php
        $attendanceData = $attendanceService->calculateAttendanceScore($student->id, $class->id, $term);
    @endphp
    <span class="badge bg-info">
        {{ $attendanceData['attendance_score'] }}
        ({{ $attendanceData['attendance_count'] }}/{{ $attendanceData['total_meetings'] }})
    </span>
</td>
```

### Show Attendance Impact

Display how attendance affects the final grade:

```blade
<div class="alert alert-info">
    <strong>Attendance Impact:</strong>
    <ul>
        <li>Base Grade: {{ $baseGrade }}</li>
        <li>Attendance Score: {{ $attendanceScore }}</li>
        <li>Final Grade: {{ $finalGrade }} ({{ $baseGrade }} × {{ $attendanceScore/100 }})</li>
    </ul>
</div>
```

## Best Practices

1. **Set Total Meetings Early**: Configure total meetings at the start of the term
2. **Record Regularly**: Record attendance for every class meeting
3. **Review Before Grading**: Check attendance records before finalizing grades
4. **Communicate Policy**: Make sure students understand how attendance affects grades
5. **Handle Edge Cases**: 
   - What if a student joins late?
   - What about approved leaves?
   - How to handle makeup classes?

## Troubleshooting

### Issue: Attendance score is 50 for all students
**Solution**: Check if `total_meetings_midterm` or `total_meetings_final` is set in the class configuration.

### Issue: Attendance records not showing
**Solution**: Verify that attendance records have the correct `term` value (Midterm or Final).

### Issue: Late students not counted
**Solution**: Ensure the calculation includes both Present and Late statuses.

## Migration Path

To add attendance to existing grade system:

1. Add `total_meetings_midterm` and `total_meetings_final` columns to `classes` table
2. Add `attendance_percentage` column to `classes` table
3. Update grade calculation logic to include attendance
4. Migrate existing attendance records to include term
5. Recalculate all grades with attendance included

## Example Implementation

See `app/Services/AttendanceCalculationService.php` for the complete implementation.

## Related Files

- `app/Services/AttendanceCalculationService.php` - Attendance calculation logic
- `app/Http/Controllers/TeacherController.php` - Grade and attendance integration
- `database/migrations/*_create_attendance_table.php` - Database schema
- `resources/views/teacher/attendance/manage.blade.php` - Attendance recording UI
- `resources/views/teacher/grades/advanced_grade_entry_embedded.blade.php` - Grade entry UI
