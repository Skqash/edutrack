# Campus Approval System - Complete Implementation

## ✅ **System Overview**

The campus approval system ensures that teachers who select a CPSU campus during registration must be approved by an admin before gaining full access to the system.

## 🔄 **Registration & Approval Flow**

### **1. Teacher Registration**
- Teacher selects a CPSU campus during signup
- System sets `campus_status = 'pending'` automatically
- Teacher account is created but with limited access

### **2. Campus Status Types**
- **`pending`** - Waiting for admin approval (limited access)
- **`approved`** - Admin approved campus affiliation (full access)
- **`rejected`** - Admin rejected campus affiliation (limited access)

### **3. Independent Teachers**
- Teachers who don't select a campus get `campus_status = 'approved'` automatically
- They have full access as independent teachers

## 📱 **Dashboard Status Display**

### **✅ Approved Campus Affiliation**
```
🏛️ Victorias Campus
✅ You are approved and affiliated with this CPSU campus
```
- **Green background**
- **Full access** to all features

### **⏳ Pending Campus Approval**
```
🏛️ Victorias Campus - Pending Approval  
⏳ Your campus affiliation is pending admin approval. Limited access until approved.
```
- **Orange/Yellow background**
- **Limited access** - no grading, subjects, or attendance

### **❌ Rejected Campus Affiliation**
```
🏛️ Victorias Campus - Access Denied
❌ Your campus affiliation was not approved. Contact admin for more information.
```
- **Red background**
- **Limited access** - no grading, subjects, or attendance

### **🎓 Independent Teacher**
```
🏛️ Independent Teacher
You are not affiliated with any specific campus or institution
```
- **Gray background**
- **Full access** as independent teacher

## 🚫 **Access Restrictions for Non-Approved Teachers**

### **Disabled Features:**
- ❌ **Start Grading** button (shows "Approval Required" instead)
- ❌ **My Subjects** (shows locked icon)
- ❌ **Grade Analytics** (shows locked icon)  
- ❌ **Attendance** (shows locked icon)
- ❌ **Class creation and management**

### **Available Features:**
- ✅ **Dashboard viewing**
- ✅ **Profile management**
- ✅ **Basic navigation**

## 👨‍💼 **Admin Interface**

### **Campus Approvals Page** (`/admin/campus-approvals`)

**Three Tabs:**
1. **Pending Approval** - Teachers waiting for approval
2. **Approved** - Teachers with approved campus affiliation
3. **Rejected** - Teachers with rejected requests

### **Admin Actions:**
- **✅ Approve** - Grant campus affiliation and activate account
- **❌ Reject** - Deny campus affiliation request
- **🔄 Revoke** - Remove previously approved affiliation
- **📊 View Details** - See teacher information and request history

## 🗄️ **Database Structure**

### **New Fields in `users` table:**
```sql
campus_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'
campus_approved_at TIMESTAMP NULL
campus_approved_by FOREIGN KEY (users.id) NULL
```

### **Registration Logic:**
```php
'campus_status' => !empty($validated['campus']) ? 'pending' : 'approved'
```
- **Campus selected** → `pending` (needs approval)
- **No campus** → `approved` (independent teacher)

## 🔐 **Access Control Logic**

### **Dashboard Access Check:**
```php
$isApproved = empty($user->campus) || $user->campus_status === 'approved';
```

### **Feature Restrictions:**
- **Independent teachers** (`campus = null`) → Full access
- **Approved campus teachers** (`campus_status = 'approved'`) → Full access  
- **Pending/Rejected teachers** → Limited access

## 📋 **Admin Workflow**

### **1. View Pending Requests**
- Navigate to **Admin → Campus Approvals**
- See list of teachers waiting for approval
- View teacher details, email, requested campus

### **2. Approve Teacher**
- Click **"Approve"** button
- Teacher gets `campus_status = 'approved'`
- Teacher account activated (`status = 'Active'`)
- Teacher gains full access immediately

### **3. Reject Teacher**
- Click **"Reject"** button  
- Teacher gets `campus_status = 'rejected'`
- Teacher maintains limited access
- Can be re-approved later if needed

### **4. Manage Approved Teachers**
- View all approved teachers
- See approval date and approving admin
- Option to revoke approval if needed

## 🎯 **Key Benefits**

### **Security & Control**
- ✅ Prevents unauthorized campus affiliation
- ✅ Admin oversight of all campus associations
- ✅ Audit trail of approvals/rejections

### **User Experience**
- ✅ Clear status messaging for teachers
- ✅ Visual indicators of access level
- ✅ Smooth approval workflow for admins

### **Flexibility**
- ✅ Independent teachers get immediate access
- ✅ Campus teachers get controlled access
- ✅ Reversible approval decisions

## 🚀 **Implementation Files**

### **Database:**
- `2026_03_22_000001_add_campus_approval_to_users.php`

### **Controllers:**
- `app/Http/Controllers/AuthController.php` (registration logic)
- `app/Http/Controllers/Admin/TeacherController.php` (approval methods)

### **Views:**
- `resources/views/teacher/dashboard.blade.php` (status display)
- `resources/views/admin/teachers/campus_approvals.blade.php` (admin interface)

### **Models:**
- `app/Models/User.php` (relationships and casts)

### **Routes:**
- Campus approval routes in `routes/web.php`

The system is now fully functional and provides complete control over campus affiliations while maintaining a smooth user experience for both teachers and administrators.