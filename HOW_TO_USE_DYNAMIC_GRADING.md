# 🎯 How to Use Dynamic Grade Entry System

## Quick Start (5 minutes)

### Step 1: Configure Grade Components

1. **Go to Grade Settings**
   - Navigate to Teacher Dashboard
   - Find a class in "My Classes" table
   - Click the **Configure Assessment** button (orange icon with sliders)
   - OR go directly to: `/teacher/grades/settings/{classId}?term=midterm`

2. **Initialize Default Components**
   - Click the **"Initialize Default Components"** button
   - This creates:
     - **Knowledge**: Midterm/Final Exam + 5 Quizzes
     - **Skills**: 3 Outputs, 3 Participation, 3 Activities, 3 Assignments
     - **Attitude**: 3 Behavior, 3 Attendance, 3 Awareness

3. **Customize (Optional)**
   - Add more components: Click "Add Component" in any category
   - Edit components: Click edit icon
   - Delete components: Click trash icon
   - Adjust KSA percentages: Use the sliders (must sum to 100%)

4. **Lock Settings (Optional)**
   - Click **"Lock Settings"** to prevent accidental changes

### Step 2: Enter Grades

1. **Access Grade Entry**
   - From Grade Settings page, click **"Grade Entry"** button
   - OR go to: `/teacher/grades/entry/{classId}?term=midterm`

2. **You'll see the NEW Dynamic Grade Entry**
   - Table columns are generated based on your configured components
   - Each component shows its max score
   - Category averages calculate automatically
   - Final grade calculates using your KSA percentages

3. **Enter Scores**
   - Type scores in each cell
   - Grades calculate in real-time
   - Click **"Save All Grades"** when done

---

## What You'll See

### Before Configuration
If you haven't configured components yet, you'll see:
- Old fixed grade entry with hard-coded columns

### After Configuration
Once you initialize components, you'll see:
- **Dynamic table** with only the components you configured
- **Flexible columns** - add/remove as needed
- **Real-time calculations** using your custom KSA percentages
- **Color-coded categories** (Blue=Knowledge, Green=Skills, Purple=Attitude)

---

## Example Workflow

### Scenario: Teacher wants 10 quizzes instead of 5

1. Go to Grade Settings
2. Click "Add Component" in Knowledge section
3. Add:
   - Quiz 6: Max=25, Weight=4%
   - Quiz 7: Max=25, Weight=4%
   - Quiz 8: Max=25, Weight=4%
   - Quiz 9: Max=25, Weight=4%
   - Quiz 10: Max=25, Weight=4%
4. Adjust existing quiz weights to balance (each ~4%)
5. Go to Grade Entry
6. **See 10 quiz columns** instead of 5!

### Scenario: Teacher wants different KSA distribution

1. Go to Grade Settings
2. Adjust sliders:
   - Knowledge: 60%
   - Skills: 30%
   - Attitude: 10%
3. Save changes
4. Go to Grade Entry
5. **Final grades now calculate with 60:30:10 ratio**

---

## Key Features

✅ **Unlimited Components** - Add as many as you need
✅ **Custom Max Scores** - Each component can have different max
✅ **Flexible KSA %** - Adjust Knowledge/Skills/Attitude weights
✅ **Real-time Calculation** - See grades update as you type
✅ **Auto-Normalization** - All scores convert to 100-point scale
✅ **Per-Term Settings** - Different configs for midterm/final

---

## Troubleshooting

### "I don't see the dynamic grade entry"
**Solution:** You need to initialize components first!
1. Go to Grade Settings
2. Click "Initialize Default Components"
3. Then go to Grade Entry

### "The table looks the same as before"
**Solution:** The system falls back to old grade entry if no components exist.
- Check if components were created in Grade Settings
- Make sure you're viewing the correct class

### "I want to go back to the old system"
**Solution:** Delete all components in Grade Settings
- The system will automatically fall back to legacy grade entry

---

## URLs Reference

```
Grade Settings:  /teacher/grades/settings/{classId}?term=midterm
Grade Entry:     /teacher/grades/entry/{classId}?term=midterm
```

---

## Next Steps

1. ✅ Configure components for your class
2. ✅ Adjust KSA percentages if needed
3. ✅ Enter grades using dynamic table
4. ✅ Enjoy unlimited flexibility!

---

**Status:** ✅ System Ready  
**Version:** 1.0.0  
**Date:** March 17, 2026

🎉 **Your dynamic grade entry is now live!**
