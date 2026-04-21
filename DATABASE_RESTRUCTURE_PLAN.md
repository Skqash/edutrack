# Database Restructure Plan - Separate User Tables

## Current Structure
- Single `users` table with `role` column (super_admin, admin, teacher, student)
- Students already have separate `students` table

## New Structure
- `super_admins` table - System administrators
- `admins` table - School administrators  
- `teachers` table - Teaching staff
- `students` table - Already exists, will be enhanced

## Migration Strategy

### Phase 1: Create New Tables
1. Create `super_admins` table
2. Create `admins` table  
3. Create `teachers` table
4. Enhance `students` table with auth fields

### Phase 2: Migrate Data
1. Copy super_admin users → super_admins
2. Copy admin users → admins
3. Copy teacher users → teachers
4. Link students table with auth

### Phase 3: Update Models
1. Create SuperAdmin model
2. Create Admin model
3. Update Teacher model
4. Update Student model
5. Add authentication guards

### Phase 4: Update Authentication
1. Create multi-guard authentication
2. Update login controller
3. Update middleware
4. Update routes

### Phase 5: Update Controllers
1. Update all controllers to use new models
2. Update relationships
3. Update queries

### Phase 6: Cleanup
1. Drop old users table
2. Remove old migrations
3. Update seeders

## Implementation Steps

1. ✅ Create migration for new tables
2. ✅ Create models with authentication
3. ✅ Update auth configuration
4. ✅ Update login system
5. ✅ Migrate existing data
6. ✅ Update all controllers
7. ✅ Test thoroughly
8. ✅ Remove old structure

## Risk Mitigation
- Backup database before migration
- Test on development first
- Keep rollback plan ready
- Verify all relationships work

Let's proceed with implementation...
