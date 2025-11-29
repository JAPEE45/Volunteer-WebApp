# Volunteer Evaluation System - Installation Instructions

## Database Setup

### Step 1: Create Evaluations Table
Run the SQL file in phpMyAdmin to create the evaluations table:

**File:** `database_evaluations_table.sql`

**Instructions:**
1. Open phpMyAdmin
2. Select the `volunteer-web` database
3. Go to the "SQL" tab
4. Copy and paste the contents of `database_evaluations_table.sql`
5. Click "Go" to execute

**OR** use the Import feature:
1. Open phpMyAdmin
2. Select the `volunteer-web` database
3. Click "Import" tab
4. Choose `database_evaluations_table.sql`
5. Click "Go"

## Features Implemented

### 1. Evaluation Form
- **Location:** Accounts page (`accounts.php`)
- **Access:** Admin clicks "Evaluate" button in user details modal
- **Criteria:** 
  - Physical Fitness (1-5 scale with 0.5 increments)
  - Communication Skills (1-5 scale)
  - Teamwork (1-5 scale)
  - Reliability (1-5 scale)
- **Overall Rating:** Automatically calculated as average of all criteria
- **Pass/Fail:** Auto-determined (≥3.0 = Pass, <3.0 = Fail)
- **Comments:** Optional text area for evaluator notes

### 2. Evaluation Display
- Evaluation status badge shows in user details (Evaluated/Not Evaluated)
- Full evaluation results displayed when available:
  - Individual scores for each criterion
  - Overall rating with star visualization
  - Pass/Fail status
  - Evaluator comments
  - Evaluation date

### 3. Approval Workflow Changes
- **Before:** Admin could accept volunteers directly
- **Now:** 
  - Admin must evaluate volunteer first
  - Accept button will reject with error message if no evaluation exists
  - Must have "passed" evaluation (≥3.0 rating) to accept
  - Failed evaluations cannot be accepted

### 4. Re-evaluation Support
- Admins can update evaluations at any time
- Click "Evaluate" button again to modify existing evaluation
- Form pre-fills with previous ratings
- New evaluation replaces old one

## File Changes Summary

### New Files Created:
1. `database_evaluations_table.sql` - Database table creation
2. `utility/saveEvaluation.php` - API to save/update evaluations
3. `utility/getEvaluation.php` - API to fetch evaluations

### Modified Files:
1. `accounts.php` - Added evaluation modal HTML/CSS
2. `script/accounts.js` - Added evaluation form logic and display
3. `utility/updateAccountStatus.php` - Added evaluation check before acceptance

## Testing Checklist

- [ ] Database table created successfully
- [ ] Evaluate button appears for pending volunteers
- [ ] Evaluation form opens with default values (3.0 each)
- [ ] Rating sliders update values in real-time
- [ ] Overall rating calculates correctly
- [ ] Star visualization updates based on overall rating
- [ ] Form submission saves evaluation successfully
- [ ] Evaluation displays in user details after submission
- [ ] Accept button blocked if no evaluation exists
- [ ] Accept button blocked if evaluation failed (<3.0)
- [ ] Accept button works if evaluation passed (≥3.0)
- [ ] Can re-evaluate and update existing evaluations
- [ ] Evaluation badge shows correct status

## Business Rules

1. **Minimum Rating:** 3.0/5.0 overall to pass
2. **Evaluation Required:** Cannot accept volunteer without evaluation
3. **Failed Evaluations:** Cannot accept volunteers with failed evaluations
4. **Re-evaluation:** Allowed at any time (replaces previous evaluation)
5. **Visibility:** Evaluations visible to admins only (not to volunteers)

## Future Enhancements (Optional)

- [ ] Allow volunteers to view their evaluation scores
- [ ] Email notification when evaluated
- [ ] Evaluation history tracking (keep all evaluations, not just latest)
- [ ] Multiple evaluators with averaged scores
- [ ] Custom pass/fail threshold settings
- [ ] Export evaluation reports
