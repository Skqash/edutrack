# Quick Test Guide - Mode-Aware Component Management

## 🚀 What Was Implemented

The Component Management system now **adapts based on the selected mode**:

- **🎯 Manual Mode**: Full control, weight required
- **🔄 Semi-Auto Mode**: Optional weight, auto-adjustment
- **🤖 Auto Mode**: No manual weight, fully automated

---

## 🧪 Quick Test (5 minutes)

### Step 1: Check Current Setup
1. Navigate to: **Grades → Settings**
2. Note the current **Component Weight Automation Mode**
3. Keep this page open in another tab

### Step 2: Test Manual Mode

1. **Set Mode**: Go to Settings → Select **Manual Mode** → Save Mode
2. **Navigate**: Go to **Grades → Grade Entry → Settings Tab**
3. **Check Alert**: 
   - ✅ Should see **BLUE alert** saying "Manual Mode Active"
   - ✅ Message: "You have full control over component weights"
4. **Open Modal**: Click "Add New Component"
5. **Check Notice**: 
   - ✅ Should see blue notice at top of modal
   - ✅ Says "Manual Mode: You must set the weight..."
6. **Check Weight Field**:
   - ✅ Field should be **ENABLED**
   - ✅ Label shows **"*Required"** in red
   - ✅ Placeholder: "Enter weight %"
7. **Test Validation**: Try to submit without weight
   - ✅ Should show error: "Manual Mode requires you to set a weight"
8. **Test Submission**: Enter weight 25% and submit
   - ✅ Should show confirmation mentioning "ensure 100% total"
   - ✅ Click OK to add component

### Step 3: Test Semi-Auto Mode

1. **Set Mode**: Go to Settings → Select **Semi-Auto Mode** → Save Mode
2. **Navigate**: Go to **Grades → Grade Entry → Settings Tab**
3. **Check Alert**:
   - ✅ Should see **GREEN alert** saying "Semi-Auto Mode Active"
   - ✅ Message mentions "override" and "proportionally"
4. **Open Modal**: Click "Add New Component"
5. **Check Notice**:
   - ✅ Should see green notice
   - ✅ Says "Semi-Auto Mode: Leave weight empty..."
6. **Check Weight Field**:
   - ✅ Field should be **ENABLED**
   - ✅ Label shows **"(Optional - Auto-suggested)"**
   - ✅ Placeholder: "Auto-suggested (can override)"
7. **Test Without Weight**: Submit form without entering weight
   - ✅ Should show confirmation: "Weight will be auto-suggested"
   - ✅ Click OK to add component
8. **Test With Weight**: Add another component with weight 40%
   - ✅ Should show confirmation: "Other components will adjust proportionally"
   - ✅ Click OK to add component
   - ✅ Check that other components adjusted

### Step 4: Test Auto Mode

1. **Set Mode**: Go to Settings → Select **Auto Mode** → Save Mode
2. **Navigate**: Go to **Grades → Grade Entry → Settings Tab**
3. **Check Alert**:
   - ✅ Should see **YELLOW alert** saying "Auto Mode Active"
   - ✅ Message: "Weights are automatically managed"
4. **Open Modal**: Click "Add New Component"
5. **Check Notice**:
   - ✅ Should see yellow notice
   - ✅ Says "Auto Mode: Weights are automatically calculated"
6. **Check Weight Field**:
   - ✅ Field should be **DISABLED** (grayed out)
   - ✅ Label shows **"Auto-Managed"** badge
   - ✅ Placeholder: "Auto-calculated"
7. **Test Submission**: Submit form
   - ✅ Should show confirmation: "auto-calculated equal weight"
   - ✅ Click OK to add component
   - ✅ Check that all components have equal weights

---

## ✅ Success Indicators

### Visual Indicators
- 🟦 **Manual Mode**: Blue alert, enabled required field
- 🟩 **Semi-Auto Mode**: Green alert, enabled optional field
- 🟨 **Auto Mode**: Yellow alert, disabled field

### Functional Indicators
- ✅ Mode status alert appears in Component Management section
- ✅ Modal shows mode-specific notice when opened
- ✅ Weight field behavior changes based on mode
- ✅ Different confirmation messages for each mode
- ✅ Validation rules enforce mode restrictions

---

## 🎯 Expected Behaviors

### Manual Mode
- **Weight field**: Enabled + Required
- **Validation**: Must enter weight
- **Confirmation**: "Ensure 100% total"
- **Result**: Exact weight you entered

### Semi-Auto Mode
- **Weight field**: Enabled + Optional
- **Validation**: Optional, accepts any valid weight
- **Confirmation**: "Auto-suggested" or "Proportional adjustment"
- **Result**: Equal distribution OR your weight + proportional adjustment

### Auto Mode
- **Weight field**: Disabled
- **Validation**: Cannot enter weight
- **Confirmation**: "Auto-calculated equal weight"
- **Result**: Always equal distribution

---

## ❌ Troubleshooting

### Problem: Alert not showing
**Solution**: 
- Hard refresh (Ctrl+Shift+R)
- Check browser console for errors
- Verify mode is saved in Settings

### Problem: Weight field not changing
**Solution**:
- Clear browser cache
- Check if mode-aware script is loaded
- Open browser console, look for "[Mode-Aware]" logs

### Problem: No confirmation dialogs
**Solution**:
- Check if JavaScript is enabled
- Look for browser console errors
- Verify script is loaded: `typeof initializeModeAwareSystem`

### Problem: Mode not persisting
**Solution**:
- Go to Settings and save mode again
- Check database: `SELECT * FROM grading_scale_settings`
- Verify term parameter is correct

---

## 🔍 Browser Console Checks

Open browser console (F12) and check for these logs:

```
[Mode-Aware] Initializing for class: X term: midterm
[Mode-Aware] Current mode: semi-auto
[Mode-Aware] Modal opening, applying restrictions for mode: semi-auto
```

If you don't see these logs, the script may not be loading correctly.

---

## 📊 Quick Comparison

| Feature | Manual | Semi-Auto | Auto |
|---------|--------|-----------|------|
| Alert Color | Blue | Green | Yellow |
| Weight Field | Enabled + Required | Enabled + Optional | Disabled |
| Can Set Weight | Yes | Yes | No |
| Auto-Adjust | No | Yes (Proportional) | Yes (Equal) |
| Confirmation | "Ensure 100%" | "Proportional" | "Auto-calc" |

---

## 🆘 Need Help?

If something doesn't work:

1. **Check mode is saved**: Go to Settings, verify mode is selected
2. **Hard refresh**: Ctrl+Shift+R or Cmd+Shift+R
3. **Check console**: F12 → Console tab, look for errors
4. **Verify script**: Check if `/js/mode-aware-component-management.js` loads
5. **Test different mode**: Try switching to another mode

---

**Estimated Test Time**: 5-7 minutes
**Last Updated**: April 16, 2026
**Status**: ✅ Ready for Testing
