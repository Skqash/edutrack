# GRADING SYSTEM - QUICK START GUIDE
**Date:** April 7, 2026  
**System:** EduTrack Multi-Mode Grading v4.0

---

## 🚀 5-MINUTE SETUP

### 1. Run Migrations
```bash
cd /laragon/www/edutrack
php artisan migrate
```

**What this does:**
- Adds new columns to `grading_scale_settings`
- Creates `student_attendance_signatures` table
- Creates `grading_sheet_templates` table  
- Adds new fields to `assessment_components`

### 2. Register Routes
Edit `routes/web.php` and add at the bottom:
```php
// Grading system routes (multi-mode)
require_once 'grading-routes.php';
```

### 3. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 4. Access the System

**Mode Configuration:**
```
http://localhost/edutrack/teacher/grades/{classId}/mode
```

**E-Signature Management:**
```
http://localhost/edutrack/teacher/grades/{classId}/signatures
```

**Grading Sheet:**
```
http://localhost/edutrack/teacher/grades/{classId}/sheet
```

---

## 🎯 SELECTING A GRADING MODE

### For Traditional Grading → Use **STANDARD**
- Teachers enter component scores
- System calculates KSA automatically
- Familiar KSA framework
- Best for consistent, structure-based grading

### For Complete Teacher Control → Use **MANUAL**
- Teachers enter grades directly
- No automatic calculations
- Total flexibility
- Best for subjective/qualitative assessments

### For Objective Assessment → Use **AUTOMATED**
- System calculates everything
- Teachers enter raw component scores
- Consistent formula application
- Best for objective tests/quizzes

### For Mixed Assessment → Use **HYBRID**
- Choose per-component entry method
- Mix manual and automated
- Maximum flexibility
- Best for diverse assessment types

---

## ⚙️ CONFIGURING YOUR CLASS

### Step 1: Open Mode Configuration
Navigate to: `/teacher/grades/{classId}/mode`

### Step 2: Select Grading Mode
Choose one of the 4 modes

### Step 3: Set KSA Percentages
```
Knowledge: 40% (default)
Skills:    50% (default)
Attitude:  10% (default)
Total:     100% (required)
```

### Step 4: Select Quiz Mode
- **Manual**: You enter quiz scores
- **Automated**: System scores objective quizzes
- **Both**: Either option available

### Step 5: Choose Output Format
- **Standard**: Full KSA + components
- **Detailed**: All calculations shown
- **Summary**: Final grades only

### Step 6: Enable Features
- ☑ E-Signature uploads (recommended)
- ☑ Auto-calculation (depends on mode)
- ☑ Weighted components (for flexibility)

### Step 7: Set Thresholds
```
Passing Grade: 75 (default)
Attendance Weight: 0% (optional)
```

### Step 8: Save
Click "Save Configuration"

---

## 📊 GRADING A CLASS

### Standard/Automated Modes

1. **Go to Grade Entry**
   - Click on class → Grade Entry

2. **Enter Component Scores**
   - Exam: [0-100]
   - Quiz 1: [0-100]
   - Quiz 2: [0-100]
   - Output: [0-100]
   - etc.

3. **System Calculates**
   - ✅ Category averages
   - ✅ Final grade
   - ✅ Decimal grade
   - ✅ Pass/Fail status

4. **Generate Grading Sheet**
   - Go to `/teacher/grades/{classId}/sheet`
   - Preview or download (PDF/CSV/HTML)

### Manual Mode

1. **Go to Grade Entry**

2. **Enter Final Grades Directly**
   - Knowledge: [0-100]
   - Skills: [0-100]
   - Attitude: [0-100]
   - Final Grade: [0-100]

3. **System Stores As-Is**
   - No calculations applied
   - Full teacher control

### Hybrid Mode

1. **Configure Component Modes**
   - Choose manual or automated for each component
   
2. **Enter Scores**
   - Manual components: you enter
   - Automated components: system calculates

3. **System Blends Results**
   - Manual scores override
   - Automated scores calculated
   - Blended final grade

---

## ✍️ MANAGING E-SIGNATURES

### Upload Signature

1. Navigate to: `/teacher/grades/{classId}/signatures`

2. Click "Upload E-Signature"

3. Select Student

4. Choose Signature Type:
   - **File Upload**: PNG/JPG/PDF
   - **Digital Draw**: Draw on canvas
   - **Pen-based**: From tablet

5. Set Signed Date

6. Upload/Draw Signature

7. Click "Upload Signature"

### Approve Signatures (Admin)

1. Go to signatures page

2. View pending signatures

3. Click "Approve" 

4. Add optional notes

5. Save

### Check Coverage

- Dashboard shows:
  - Total signatures
  - Approved count
  - Pending count
  - Coverage percentage

---

## 📄 GENERATING GRADING SHEETS

### Access Sheet Generator
```
/teacher/grades/{classId}/sheet
```

### Preview
- See how sheet will look
- Test different templates
- Verify grades display

### Download Options

**PDF** (Print-ready)
- Professional formatting
- Suitable for printing
- Includes grading scale
- Optional: e-signatures

**CSV** (Spreadsheet)
- Import to Excel
- Column headers
- All grade data
- Easy manipulation

**HTML** (Web View)
- View in browser
- Print from browser
- All details visible
- Responsive design

### Custom Templates

1. Click "Select Template"
2. Choose template:
   - Standard Format
   - Detailed Format
   - Summary Format
3. Download or print

---

## 🔍 VIEWING GRADES

### See Summary
```bash
GET /teacher/grades/{classId}/mode/summary
```

Returns:
```json
{
  "mode": "standard",
  "quiz_mode": "both",
  "output_format": "standard",
  "ksa": {
    "knowledge": 40,
    "skills": 50,
    "attitude": 10
  },
  "features": {
    "e_signature": true,
    "auto_calculation": true,
    "weighted_components": true
  },
  "passing_grade": 75,
  "attendance_weight": 0
}
```

### Get Component Modes (Hybrid)
```bash
GET /teacher/grades/{classId}/mode/components
```

---

## ❓ COMMON SCENARIOS

### Scenario 1: Traditional School
**Mode:** Standard
**Quiz Mode:** Manual
**Output:** Standard Format
**Result:** Standard KSA with manual quiz entry

### Scenario 2: Automated Testing
**Mode:** Automated
**Quiz Mode:** Automated
**Output:** Summary Format
**Result:** All grades auto-calculated, clean summary

### Scenario 3: Flexible School
**Mode:** Hybrid
**Quiz Mode:** Both
**Output:** Detailed Format
**Result:** Mix of manual/automated per component

### Scenario 4: Subjective Assessment
**Mode:** Manual
**Quiz Mode:** Manual
**Output:** Standard Format
**Result:** Complete teacher control, no calculations

---

## 🛠️ TROUBLESHOOTING

### Problem: Can't access mode selector
**Solution:** Run migrations first
```bash
php artisan migrate
```

### Problem: Grades showing as 0
**Solution:** Check entry mode of component
- Manual mode: Ensure teacher entered score
- Automated mode: Ensure raw score entered

### Problem: E-signature upload fails
**Solution:** 
```bash
php artisan storage:link
chmod -R 755 storage/app/public
```

### Problem: PDF won't generate
**Solution:** Install DOMPDF
```bash
composer require barryvdh/laravel-dompdf
```

### Problem: KSA percentages don't save
**Solution:** Verify they sum to 100%
```
40% + 50% + 10% = 100% ✓
```

---

## 📋 CHECKLIST

- [ ] Migrations run successfully
- [ ] Routes registered in web.php
- [ ] Cache cleared
- [ ] Can access `/teacher/grades/{classId}/mode`
- [ ] Can select grading mode
- [ ] Can set KSA percentages
- [ ] Can configure quiz mode
- [ ] Can enable e-signatures
- [ ] Can enter grades (varies by mode)
- [ ] Grades calculated correctly
- [ ] E-signature upload works
- [ ] Grading sheet generates
- [ ] PDF downloads successfully
- [ ] CSV exports correctly

---

## 🎓 GRADING FORMULAS AT A GLANCE

**Knowledge** = (Exam × 60%) + (Quiz × 40%)

**Skills** = (Output × 40%) + (ClassPart × 30%) + (Activity × 30%)

**Attitude** = (Behavior × 50%) + (Attendance × 60%) + (Awareness × 40%)

**Final** = (K × 40%) + (S × 50%) + (A × 10%)

**Decimal Grade** = Based on percentage (98+=1.0, ..., 70=3.50)

---

## 📞 SUPPORT

For issues:
1. Check GRADING_SYSTEM_MULTIMODE_IMPLEMENTATION.md
2. Review troubleshooting section
3. Check Laravel error logs

---

**Version:** 4.0  
**System:** EduTrack  
**Status:** ✅ Production Ready  
**Last Updated:** April 7, 2026
