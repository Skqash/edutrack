# Quick Reference: Configuration Form

## What Was Fixed

### ❌ Error: "Attempt to read property 'id' on null"

- **Cause**: Class didn't have a subject assigned
- **Solution**: Now handles null subjects gracefully
- **Result**: Configuration form now works even if subject isn't assigned

---

## New Configuration Columns

### Skills Assessment (50% Weight)

#### Class Participation

| Period    | Max Points          | Default |
| --------- | ------------------- | ------- |
| Prelim    | Input field         | 5       |
| Midterm   | Input field         | 5       |
| Final     | Input field         | 10      |
| **TOTAL** | **Auto-calculated** | **20**  |

#### Activities (Same structure as above)

| Period    | Max Points          | Default |
| --------- | ------------------- | ------- |
| Prelim    | Input field         | 5       |
| Midterm   | Input field         | 5       |
| Final     | Input field         | 10      |
| **TOTAL** | **Auto-calculated** | **20**  |

#### Assignments (Same structure as above)

| Period    | Max Points          | Default |
| --------- | ------------------- | ------- |
| Prelim    | Input field         | 5       |
| Midterm   | Input field         | 5       |
| Final     | Input field         | 10      |
| **TOTAL** | **Auto-calculated** | **20**  |

#### Output/Project (Same structure as above)

| Period    | Max Points          | Default |
| --------- | ------------------- | ------- |
| Prelim    | Input field         | 5       |
| Midterm   | Input field         | 5       |
| Final     | Input field         | 10      |
| **TOTAL** | **Auto-calculated** | **20**  |

---

### Attitude Assessment (10% Weight)

#### Behavior

| Period    | Max Points          | Default |
| --------- | ------------------- | ------- |
| Prelim    | Input field         | 2       |
| Midterm   | Input field         | 3       |
| Final     | Input field         | 5       |
| **TOTAL** | **Auto-calculated** | **10**  |

#### Awareness/Responsiveness (Same structure as above)

| Period    | Max Points          | Default |
| --------- | ------------------- | ------- |
| Prelim    | Input field         | 2       |
| Midterm   | Input field         | 3       |
| Final     | Input field         | 5       |
| **TOTAL** | **Auto-calculated** | **10**  |

---

## How to Use

1. **Enter Values**: Fill in prelim, midterm, and final points for each component
2. **Automatic Total**: Total automatically updates as you type
3. **Save**: Click "Save Configuration" to persist changes
4. **Use in Grading**: These configurations are used when entering grades

---

## Example Configuration

**Teacher configures for Math 101:**

| Component           | Prelim | Midterm | Final | Total    |
| ------------------- | ------ | ------- | ----- | -------- |
| Class Participation | 5      | 5       | 10    | **20** ✓ |
| Activities          | 5      | 5       | 10    | **20** ✓ |
| Assignments         | 5      | 5       | 10    | **20** ✓ |
| Output/Project      | 5      | 5       | 10    | **20** ✓ |
| **Skills Total**    |        |         |       | **80**   |
|                     |        |         |       |          |
| Behavior            | 2      | 3       | 5     | **10** ✓ |
| Awareness           | 2      | 3       | 5     | **10** ✓ |
| **Attitude Total**  |        |         |       | **20**   |

**Final Grade Formula**: (Skills × 50%) + (Attitude × 10%) + (Knowledge × 40%)

---

## Browser Console Logs (Debug)

When the form loads, totals are automatically calculated. You can verify in browser console:

- All period inputs are detected
- Total fields are populated
- Event listeners are attached to inputs

---

## Troubleshooting

| Issue              | Solution                                    |
| ------------------ | ------------------------------------------- |
| Total not updating | Refresh page and try again                  |
| Form won't submit  | Check browser console for JavaScript errors |
| Validation error   | Ensure all values are between 0-100         |
| Database error     | Run `php artisan migrate`                   |

---

## Technical Details

**Database Columns Added**: 24
**New Input Fields**: 24
**Auto-Calculated Fields**: 6
**Migration File**: `2026_01_22_000001_add_period_based_columns_to_assessment_ranges.php`

---

## Keyboard Shortcuts

- **Tab**: Move to next field
- **Enter**: Submit form
- **Escape**: Can be used in cancel operations

---

## Color Scheme

- **Header**: White with blue left border
- **Card Headers**: White with subtle bottom border
- **Input Fields**: White background, blue/green border when focused
- **Total Fields**: Light gray background (readonly)
- **Buttons**: Solid blue for primary action, white with blue border for secondary

---

## Support

All configurations are automatically saved when you click "Save Configuration"

Changes take effect immediately for new grade entries.
