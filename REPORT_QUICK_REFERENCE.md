# Report System - Quick Reference Guide

## Database Setup

### Step 1: Run this SQL query in phpMyAdmin

```sql
CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `deployment_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `report_title` varchar(255) NOT NULL,
  `report_description` text DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `status` enum('pending','reviewed','approved','rejected') DEFAULT 'pending',
  `submission_date` datetime NOT NULL DEFAULT current_timestamp(),
  `review_date` datetime DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `deployment_id` (`deployment_id`),
  KEY `event_id` (`event_id`),
  KEY `status` (`status`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`deployment_id`) REFERENCES `deployment`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE INDEX idx_submission_date ON reports(submission_date DESC);
CREATE INDEX idx_user_status ON reports(user_id, status);
```

---

## Files Summary

### New Files Created:

1. **database_reports_table.sql** - SQL script for table creation
2. **utility/uploadReport.php** - Handles file uploads
3. **utility/getReports.php** - Fetches user reports
4. **utility/deleteReport.php** - Deletes reports
5. **utility/getUserDeployments.php** - Gets user deployments

### Modified Files:

1. **profile/report.php** - Complete redesign with database integration

---

## Key Features

### Report Submission:
- ✅ Report title (required)
- ✅ Deployment selection (optional - links to specific deployment)
- ✅ Description (optional)
- ✅ Multi-file upload (PDF, DOC, DOCX)
- ✅ Max 10MB per file

### Report Management:
- ✅ View all submitted reports
- ✅ Status tracking (pending/reviewed/approved/rejected)
- ✅ Download reports
- ✅ Delete reports
- ✅ See linked deployment info

### Statistics:
- ✅ Total reports
- ✅ Reports this month
- ✅ Pending review count
- ✅ Approved count

---

## Usage

### For Volunteers:

1. **Submit Report:**
   - Fill in report title
   - (Optional) Select related deployment
   - (Optional) Add description
   - Upload files (click or drag-drop)
   - Click "Upload Reports"

2. **View Reports:**
   - All reports listed below form
   - Color-coded status badges
   - Event information displayed

3. **Download/Delete:**
   - Click download button to get file
   - Click delete button to remove

### For Admins (Future):

The database supports admin review:
```sql
-- Update report status
UPDATE reports 
SET status = 'approved',
    review_date = NOW(),
    reviewed_by = {admin_id},
    comments = 'Great report!'
WHERE id = {report_id};
```

---

## Status Types

| Status | Badge | Description |
|--------|-------|-------------|
| pending | ⏳ Orange | Just submitted, awaiting review |
| reviewed | 👁️ Blue | Admin has reviewed |
| approved | ✅ Green | Report approved |
| rejected | ❌ Red | Report rejected |

---

## Security Features

✅ Session validation
✅ User authentication required
✅ File type whitelist
✅ File size limit (10MB)
✅ SQL injection prevention
✅ User authorization (can only manage own reports)
✅ Secure file storage

---

## Common Queries

### Get all pending reports:
```sql
SELECT * FROM reports 
WHERE user_id = ? AND status = 'pending'
ORDER BY submission_date DESC;
```

### Get reports for specific deployment:
```sql
SELECT * FROM reports 
WHERE deployment_id = ?
ORDER BY submission_date DESC;
```

### Get reports with event details:
```sql
SELECT r.*, e.eventName, e.location 
FROM reports r
LEFT JOIN events e ON r.event_id = e.id
WHERE r.user_id = ?;
```

---

## Troubleshooting

### Reports not showing?
- Check if table was created: `SHOW TABLES LIKE 'reports';`
- Check if user is logged in
- Check browser console for errors

### Upload fails?
- Verify uploads/reports/ directory exists
- Check directory permissions (must be writable)
- Verify file size < 10MB
- Check file type (PDF, DOC, DOCX only)

### Can't download files?
- Verify file exists in uploads/reports/
- Check file_path in database matches actual file
- Ensure web server can read files

---

## Quick Test

1. Login as volunteer
2. Go to Reports page
3. Fill title: "Test Report"
4. Upload a small PDF
5. Click Upload
6. Verify:
   - File appears in list
   - File exists in uploads/reports/
   - Record in database
   - Can download
   - Can delete

---

## File Structure

```
Volunteer-WebApp/
├── profile/
│   └── report.php (updated)
├── utility/
│   ├── uploadReport.php (new)
│   ├── getReports.php (new)
│   ├── deleteReport.php (new)
│   └── getUserDeployments.php (new)
├── uploads/
│   └── reports/ (will be created)
│       └── {unique_files}.pdf
└── database_reports_table.sql (new)
```

---

## Support

For issues or questions:
1. Check REPORT_SYSTEM_DOCUMENTATION.md for detailed info
2. Verify all files are in correct locations
3. Check database table was created correctly
4. Verify file permissions on uploads directory
5. Check PHP error logs for detailed errors
