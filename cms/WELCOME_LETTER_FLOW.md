# Welcome Letter Flow - Employee Movement System

## Overview
This system automatically moves selected employees from the interviews section to the documents section after sending welcome letters.

## Flow Description

### 1. Selected Interviews Page
- **URL**: `http://127.0.0.1:8000/admin/interviews/selected`
- **Purpose**: Shows employees who have been selected from interviews
- **Filter**: Only shows interviews where `result = 'Selected'` AND `welcome_letter_sent = false`

### 2. Welcome Letter Process
When admin clicks "Send Welcome Letter":
1. **Email Sent**: Welcome letter is sent to candidate's email
2. **Database Updates**:
   - `interviews.welcome_letter_sent` = `true`
   - New employee record created in `employees` table
3. **Employee Creation**:
   - First name and last name extracted from candidate name
   - Email from interview record
   - Phone from lead record (if available)
   - Department set to job role
   - User type set to 'employee'
   - Auto-approved (is_approved = true)
   - Default password: 'password123'

### 3. Documents Page
- **URL**: `http://127.0.0.1:8000/admin/employees/documents`
- **Purpose**: Shows all employees who need to submit documents
- **Features**:
  - Shows all employees with user_type = 'employee'
  - Highlights new employees (created within 7 days) with "NEW" badge
  - Shows notification section for recently added employees from interviews

## Key Files Modified

### Controllers
1. **InterviewController.php**
   - Modified `sendWelcomeLetter()` method
   - Added `createEmployeeFromInterview()` private method
   - Updated `selectedEmployees()` to filter out processed interviews

2. **EmployeeDocumentController.php**
   - Updated `adminDocumentsIndex()` to show recent additions
   - Added logic to display recently processed interviews

### Models
1. **Interview.php**
   - Added `welcome_letter_sent` to fillable array

### Views
1. **selected.blade.php**
   - Updated success message to indicate employee movement

2. **documents_index.blade.php**
   - Added notification section for recently added employees
   - Added "NEW" badge for recent employees

### Database
1. **Migration**: `add_welcome_letter_sent_to_interviews_table_if_not_exists.php`
   - Ensures `welcome_letter_sent` column exists in interviews table

## Usage Instructions

### For Admin Users:
1. Go to **Interviews > Selected** to see candidates who passed interviews
2. Click **"Send Welcome Letter"** button for any candidate
3. Enter joining date when prompted
4. System will:
   - Send welcome email to candidate
   - Create employee record automatically
   - Move candidate from Selected page to Documents page
5. Go to **Employees > Documents** to see all employees including newly added ones

### Visual Indicators:
- **Selected Page**: Only shows candidates pending welcome letters
- **Documents Page**: Shows "NEW" badge for recently added employees
- **Notification Section**: Shows recently processed candidates from interviews

## Technical Details

### Database Schema Changes:
```sql
ALTER TABLE interviews ADD COLUMN welcome_letter_sent BOOLEAN DEFAULT FALSE;
```

### Employee Creation Logic:
```php
// Split name
$nameParts = explode(' ', trim($interview->candidate_name), 2);
$firstName = $nameParts[0] ?? '';
$lastName = $nameParts[1] ?? '';

// Create employee
Employee::create([
    'first_name' => $firstName,
    'last_name' => $lastName,
    'email' => $interview->candidate_email,
    'phone' => $interview->lead->number ?? null,
    'department' => $interview->job_role,
    'user_type' => 'employee',
    'is_approved' => true,
    'password' => Hash::make('password123'),
]);
```

## Testing
Run the test script to verify functionality:
```bash
php test_welcome_letter_flow.php
```

This will show:
- Current selected interviews pending welcome letters
- Existing employees in documents section
- Recently processed interviews

## Benefits
1. **Automated Workflow**: No manual employee creation needed
2. **Data Consistency**: Employee data automatically populated from interview records
3. **Clear Separation**: Selected candidates vs. Active employees
4. **Visual Feedback**: Clear indicators for new employees and recent additions
5. **Audit Trail**: Welcome letter status tracked in database