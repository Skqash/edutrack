# Grading Automation Modes - Quick Start Guide

## 🎯 What You Get

Three different ways to manage component weights in your grading system:

| Mode | Auto-Adjust | Best For | Min Components |
|------|------------|----------|----------------|
| **Manual** 🎯 | ❌ No | Custom weights | 1+ |
| **Semi-Auto** 🔄 | ✅ Yes (proportional) | Most classrooms | 2+ |
| **Fully Auto** 🤖 | ✅ Yes (equal) | Equal-weight components | 2+ |

---

## 🚀 How to Use

### Step 1: Go to Grade Settings
1. Click **Grades** in sidebar
2. Select your class and term
3. Click **Settings & Components** tab
4. Scroll to "⚡ Component Weight Automation Mode"

### Step 2: Select Mode
Choose one of three options:

**Option A: 🎯 Manual Mode**
- You control all weights
- No automatic redistribution
- Total must = 100%
- Click radio button
- Click **"Save Automation Mode"**

**Option B: 🔄 Semi-Auto Mode** (Recommended)
- Change one component → others adjust
- Perfect for most situations
- Automatic weight redistribution
- Click radio button
- Click **"Save Automation Mode"**

**Option C: 🤖 Fully Auto Mode**
- All components get same weight
- Change any → ALL change
- Equal-weight fairness
- Needs 2+ components
- Click radio button
- Click **"Save Automation Mode"**

### Step 3: Manage Components
Once mode is set, add/edit components normally.

---

## 📊 Examples by Mode

### Example 1: Manual Mode
**Scenario:** You want custom weights for different assessment types

```
Knowledge Components (Manual Mode):
  ├─ Final Exam: 60% (most important)
  ├─ Quiz Bundle: 30% (medium importance)
  └─ Homework: 10% (low importance)
  
Change Exam to 50%:
  ❌ System shows error: "Total = 110%, must = 100%"
  
Fix it: Change Homework to 0%:
  ✅ Now: Exam 50% + Quizzes 30% + Homework 0% = 80%
  ❌ Still invalid! Need to add 20%
  
Solution: Set Homework to 20%:
  ✅ Now: 50% + 30% + 20% = 100% ✅
```

### Example 2: Semi-Auto Mode (Recommended)
**Scenario:** You have multiple quizzes of similar importance

```
Knowledge Components (Semi-Auto Mode):
  ├─ Quiz 1: 25%
  ├─ Quiz 2: 25%
  ├─ Quiz 3: 25%
  └─ Quiz 4: 25%
  
Change Quiz 1 to 30%:
  ✅ System auto-adjusts others:
      ├─ Quiz 1: 30% (your change)
      ├─ Quiz 2: 23.33% (auto-adjusted)
      ├─ Quiz 3: 23.33% (auto-adjusted)
      └─ Quiz 4: 23.34% (auto-adjusted + rounding)
  ✅ Total: 100% (automatic!)
```

### Example 3: Fully Auto Mode
**Scenario:** All quizzes equally important, equal points

```
Knowledge Components (Fully Auto Mode):
  ├─ Quiz 1: 25%
  ├─ Quiz 2: 25%
  ├─ Quiz 3: 25%
  └─ Quiz 4: 25%
  
Change Quiz 2 to 20%:
  ✅ System sets ALL to 20%:
      ├─ Quiz 1: 20% (changed auto)
      ├─ Quiz 2: 20% (changed by you)
      ├─ Quiz 3: 20% (changed auto)
      └─ Quiz 4: 20% (changed auto)
  ❌ Total: 80% (⚠️ Warning!)
  
Add Quiz 5:
  ✅ All automatically adjust to 20% each = 100%
```

---

## ⚡ Mode Comparison Table

### When Something Changes...

| Scenario | Manual | Semi-Auto | Auto |
|----------|--------|-----------|------|
| Change Quiz 1 from 25% to 30% | Others stay same | Others auto-adjust down | ALL become 30% |
| Add new Quiz | Redistribute manually | Auto-redistributes | Auto-redistributes |
| Delete a Quiz | Redistribute manually | Auto-redistributes | Auto-redistributes |
| Want unequal weights | ✅ Yes | ✅ Yes | ❌ No (all equal) |
| Want equal weights | Manual setup | ✅ Yes | ✅ Yes |

---

## 🔒 Advanced Features

### Lock/Unlock Settings
Once you're happy with your setup:
1. Click **"Lock Settings"** button (bottom of component card)
2. Prevents accidental changes during grading
3. Click **"Unlock Settings"** to modify again

### View Available Weight
When adding/editing components, system shows:
```
Available weight for Knowledge: 70%
(30% already assigned)
```

This tells you:
- Total category capacity: 100%
- Already used: 30%
- You can add: up to 70%

---

## ⚠️  Common Errors & Solutions

### Error: "Total weight exceeds 100%"
**Cause:** Manual mode, you've set weights that add up to > 100%
**Solution:** Reduce weights so they total 100%

### Error: "Cannot set to 100% when others exist"
**Cause:** Trying to set one component to 100% in a category with other components
**Solution:** 
- Switch to Semi-Auto (auto-adjusts)
- Or adjust other components down
- Or use only 1 component in category

### Error: "Auto mode requires 2+ components"
**Cause:** Auto mode needs minimum 2 components for equal distribution
**Solution:** 
- Add at least 2 components
- Or switch to Manual/Semi-Auto mode

### Error: "Component weight automation mode updated"  (Success, not an error!)
**This is SUCCESS**, page will reload with new mode active

---

## 💡 Recommendations

### Use MANUAL Mode when:
- Different assessment types have different weights
- Example: Final Exam (60%) + Quizzes (30%) + Homework (10%)
- You need precise control

### Use SEMI-AUTO Mode when: ⭐ RECOMMENDED
- Similar components with likely similar weights
- You want automation but flexibility
- Example: Multiple quizzes, outputs, assignments
- Most common classroom scenario

### Use AUTO Mode when:
- All components in category should be equal weight
- Fairness is priority
- Equal-point assessments
- Example: 5 quizzes, each worth equal %

---

## 🎓 Real Classroom Example

**BSIT 1-A Intro to Computing - Midterm Grading**

**Knowledge Category (40% of final grade):**
- 1 Midterm Exam (main assessment)
- 5 Quizzes (practice assessments)
- Mode: **SEMI-AUTO** ← Why? Equal importance, easy management
- Setup:
  ```
  Set Exam to 60%, quizzes auto-share 40%
  Each quiz gets: 40% ÷ 5 = 8%
  Total: 60% + 8%×5 = 100% ✅
  ```
- If you later want Exam at 50%:
  ```
  Change Exam → 50%
  Quizzes auto-become: 40% ÷ 5 = 8% each
  No manual adjustment needed!
  ```

**Skills Category (50% of final grade):**
- 3 Outputs (equal point projects)
- 6 Activities (equal point exercises)
- Mode: **FULLY AUTO** ← Why? All activities equally important
- Setup:
  ```
  Outputs: 3 items at 50% ÷ 3 = 16.67% each
  Activities: 6 items at... wait, need to decide!
  
  Option 1: Separate categories for each type
  Option 2: Switch Outputs to Semi-Auto
  Option 3: All equal in one category
  ```

**Attitude Category (10% of final grade):**
- Attendance, behavior, participation
- Mode: **MANUAL** ← Why? Different types, custom weights
- Setup:
  ```
  Attendance: 4%
  Behavior: 3%
  Participation: 3%
  Total: 10% ✅
  ```

---

## 📱 Mobile Tips

- Modes work on mobile and desktop
- Same functionality everywhere
- Recommended: Use desktop for initial setup
- Mobile: Fine for quick weight tweaks

---

## 🆘 Need Help?

- **Mode not saving?** Check browser console (F12) for errors
- **Components not redistributing?** Verify mode is "Semi-Auto" or "Auto"
- **Weight validation error?** Check total = 100% per category
- **Locked out?** Click "Unlock Settings" button

---

**Version:** 1.0
**Status:** ✅ Production Ready
**Last Updated:** April 15, 2026
