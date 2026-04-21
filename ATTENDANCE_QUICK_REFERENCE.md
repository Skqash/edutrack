# Attendance Integration - Quick Reference

## 🎯 Quick Start

### Enable Attendance in Grades
1. Go to **Grades → Settings & Components**
2. Set **Total Meetings** (e.g., 18)
3. Check **Enable Attendance**
4. Set **Attendance Weight** (e.g., 10%)
5. Choose **Affects Category** (Knowledge/Skills/Attitude)
6. Click **Save**

### Record Attendance
1. Go to **Attendance → Manage Attendance**
2. Select class and date
3. Mark each student: Present, Late, Absent, or Leave
4. Save attendance

### View Final Grades
1. Go to **Grades → Advanced Grade Entry**
2. Enter component scores
3. Final grades automatically include attendance

---

## 📊 Formulas

### Attendance Score
```
Attendance Score = (Attendance Count / Total Meetings) × 50 + 50

Where:
- Attendance Count = Present + Late
- Range: 50-100
```

### Category Application
```
New Category Average = (Original × (1 - Weight)) + (Attendance × Weight)

Example (10% weight):
New Average = (Original × 0.9) + (Attendance × 0.1)
```

### Final Grade
```
Final Grade = (Knowledge × 40%) + (Skills × 50%) + (Attitude × 10%)
```

---

## 💡 Examples

### Example 1: Good Attendance
```
Attendance: 16/18 = 88.89% → Score: 94.44
Skills (before): 85
Skills (after): (85 × 0.9) + (94.44 × 0.1) = 85.94
Impact: +0.94 points ✅
```

### Example 2: Poor Attendance
```
Attendance: 10/18 = 55.56% → Score: 77.78
Skills (before): 90
Skills (after): (90 × 0.9) + (77.78 × 0.1) = 88.78
Impact: -1.22 points ⚠️
```

### Example 3: Perfect Attendance
```
Attendance: 18/18 = 100% → Score: 100
Skills (before): 88
Skills (after): (88 × 0.9) + (100 × 0.1) = 89.2
Impact: +1.2 points ✅
```

---

## ⚙️ Settings

### Default Configuration
```
Knowledge Weight: 40%
Skills Weight: 50%
Attitude Weight: 10%
Attendance Weight: 10%
Attendance Category: Skills
Total Meetings: 18
Enable Attendance: Yes
```

### Recommended Weights
- **Conservative**: 5-8%
- **Standard**: 10-12%
- **Aggressive**: 15-20%

---

## 🔍 Troubleshooting

| Issue | Solution |
|-------|----------|
| Attendance not affecting grades | Check if enabled in settings |
| Wrong category affected | Verify attendance_category setting |
| Incorrect score | Check attendance records and total meetings |
| Grade not updating | Clear cache and recalculate |

---

## 📈 Impact Guide

| Attendance % | Score | Typical Impact |
|--------------|-------|----------------|
| 100% | 100 | +0.5 to +1.2 |
| 90-99% | 95-99.5 | +0.3 to +0.8 |
| 80-89% | 90-94.5 | +0.1 to +0.5 |
| 70-79% | 85-89.5 | 0 to +0.3 |
| 60-69% | 80-84.5 | -0.3 to +0.1 |
| 50-59% | 75-79.5 | -0.8 to -0.3 |
| <50% | 50-74.5 | -2.0 to -0.8 |

---

## 🎓 Best Practices

### For Teachers
1. ✅ Set total meetings at start of term
2. ✅ Record attendance consistently
3. ✅ Review attendance reports regularly
4. ✅ Communicate policy to students
5. ✅ Use reasonable weight (10-15%)

### For Students
1. ✅ Attend all classes
2. ✅ Arrive on time (late is better than absent)
3. ✅ Check attendance records
4. ✅ Understand the impact
5. ✅ Communicate absences in advance

---

## 🚀 Quick Commands

### Test Attendance Integration
```bash
php test_attendance_integration.php
```

### View Demonstration
```bash
php demo_attendance_impact.php
```

### Check Classes
```bash
php check_classes.php
```

---

## 📞 Need Help?

### Documentation
- `ATTENDANCE_INTEGRATION_SUMMARY.md` - Complete summary
- `ATTENDANCE_GRADE_INTEGRATION_COMPLETE.md` - Detailed guide

### Test Scripts
- `test_attendance_integration.php` - Integration test
- `demo_attendance_impact.php` - Visual demonstration

---

**Quick Reference v1.0** | Last Updated: April 16, 2026
