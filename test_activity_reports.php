<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Report System - Testing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: #f5f5f5;
        }
        .test-section {
            background: white;
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #dc143c;
            border-bottom: 3px solid #dc143c;
            padding-bottom: 1rem;
        }
        h2 {
            color: #2d3748;
            margin-top: 0;
        }
        .status {
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin: 0.5rem 0;
        }
        .status.success {
            background: #d1fae5;
            color: #065f46;
        }
        .status.error {
            background: #fee2e2;
            color: #991b1b;
        }
        .status.warning {
            background: #fef3c7;
            color: #92400e;
        }
        .test-item {
            padding: 1rem;
            margin: 1rem 0;
            background: #f7fafc;
            border-left: 4px solid #3b82f6;
            border-radius: 5px;
        }
        code {
            background: #2d3748;
            color: #10b981;
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-size: 0.9rem;
        }
        .checklist {
            list-style: none;
            padding: 0;
        }
        .checklist li {
            padding: 0.75rem;
            margin: 0.5rem 0;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 5px;
        }
        .checklist li:before {
            content: "☐ ";
            color: #dc143c;
            font-weight: bold;
            margin-right: 0.5rem;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #dc143c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 0.5rem 0.5rem 0.5rem 0;
        }
        .btn:hover {
            background: #a00000;
        }
    </style>
</head>
<body>
    <h1>🧪 Activity Report System - Testing Checklist</h1>
    
    <div class="test-section">
        <h2>📋 Setup Instructions</h2>
        <div class="status warning">⚠️ Database Setup Required</div>
        <p>Before testing, run this SQL in phpMyAdmin:</p>
        <ol>
            <li>Open phpMyAdmin and select the <code>volunteer-web</code> database</li>
            <li>Go to the "SQL" tab</li>
            <li>Copy and paste the contents of <code>database_activity_reports.sql</code></li>
            <li>Click "Go" to execute</li>
        </ol>
    </div>

    <div class="test-section">
        <h2>✅ Testing Checklist</h2>
        <ul class="checklist">
            <li><strong>Database Table:</strong> Verify <code>activity_reports</code> table exists in phpMyAdmin</li>
            <li><strong>Role Column:</strong> Verify <code>role</code> column exists in <code>deployment</code> table (run <code>database_deployment_role.sql</code> if not)</li>
            <li><strong>Uploads Directory:</strong> Check if <code>uploads/activity_reports/</code> folder is created</li>
            <li><strong>Login:</strong> Test login as volunteer at <code>/Volunteer-WebApp/login.php</code></li>
            <li><strong>Navigation:</strong> Check sidebar shows "Submit Report" and "My Reports" links</li>
            <li><strong>Submit Report:</strong> Fill and submit a test activity report</li>
            <li><strong>File Upload:</strong> Test uploading images/PDFs with report</li>
            <li><strong>View Reports:</strong> Check "My Reports" page displays submitted reports</li>
            <li><strong>Report Details:</strong> Click "View Details" to see full report in modal</li>
            <li><strong>Report Status:</strong> Verify status badges (Pending/Approved/Rejected) display correctly</li>
            <li><strong>Draft Save:</strong> Start filling a report, refresh page, check if draft is restored</li>
            <li><strong>Validation:</strong> Try submitting with missing required fields</li>
            <li><strong>Hours Validation:</strong> Try entering invalid hours (e.g., 25 or -1)</li>
            <li><strong>File Size:</strong> Try uploading a file larger than 5MB</li>
            <li><strong>Multiple Files:</strong> Try uploading more than 5 files</li>
        </ul>
    </div>

    <div class="test-section">
        <h2>🔗 Quick Links</h2>
        <a href="login.php" class="btn">Login Page</a>
        <a href="profile/activityReport.php" class="btn">Submit Report</a>
        <a href="profile/myReports.php" class="btn">My Reports</a>
        <a href="profile/dashboard.php" class="btn">Dashboard</a>
    </div>

    <div class="test-section">
        <h2>🐛 Common Issues & Solutions</h2>
        <div class="test-item">
            <strong>Issue:</strong> "Table 'volunteer-web.activity_reports' doesn't exist"<br>
            <strong>Solution:</strong> Run <code>database_activity_reports.sql</code> in phpMyAdmin
        </div>
        <div class="test-item">
            <strong>Issue:</strong> "Column 'role' doesn't exist in deployment table"<br>
            <strong>Solution:</strong> Run <code>database_deployment_role.sql</code> in phpMyAdmin
        </div>
        <div class="test-item">
            <strong>Issue:</strong> "Unauthorized access" when accessing report pages<br>
            <strong>Solution:</strong> Make sure you're logged in as a volunteer
        </div>
        <div class="test-item">
            <strong>Issue:</strong> File upload fails<br>
            <strong>Solution:</strong> Check <code>uploads/activity_reports/</code> folder exists and has write permissions
        </div>
        <div class="test-item">
            <strong>Issue:</strong> No deployments showing in dropdown<br>
            <strong>Solution:</strong> Make sure the volunteer has been deployed to at least one event
        </div>
    </div>

    <div class="test-section">
        <h2>📊 Test Data Queries</h2>
        <p>Run these in phpMyAdmin SQL tab to check data:</p>
        <div class="test-item">
            <strong>Check all activity reports:</strong><br>
            <code>SELECT * FROM activity_reports ORDER BY submitted_at DESC;</code>
        </div>
        <div class="test-item">
            <strong>Check report statistics:</strong><br>
            <code>SELECT status, COUNT(*) as count, SUM(hours_worked) as total_hours FROM activity_reports GROUP BY status;</code>
        </div>
        <div class="test-item">
            <strong>Check deployments with roles:</strong><br>
            <code>SELECT d.*, u.fullName, e.eventName FROM deployment d JOIN users u ON d.user_id = u.id JOIN events e ON d.event_id = e.id;</code>
        </div>
    </div>

    <div class="test-section">
        <h2>🎯 Next Steps</h2>
        <div class="status success">✅ Phase 1: Volunteer Report Submission - COMPLETE</div>
        <div class="status warning">⏳ Phase 2: Admin Review Interface - IN PROGRESS</div>
        <p><strong>Remaining Features to Implement:</strong></p>
        <ul>
            <li>Admin page to view all activity reports</li>
            <li>Approve/Reject functionality for admins</li>
            <li>PDF generation for approved reports</li>
            <li>Email/SMS notifications on review</li>
            <li>Dashboard statistics integration</li>
            <li>Export reports to Excel/PDF</li>
        </ul>
    </div>

    <?php
    // PHP Database Test
    if (file_exists('utility/db.php')) {
        include_once 'utility/db.php';
        echo '<div class="test-section">';
        echo '<h2>🔌 Database Connection Test</h2>';
        
        if ($conn) {
            echo '<div class="status success">✅ Database connected successfully</div>';
            
            // Check if activity_reports table exists
            $tableCheck = $conn->query("SHOW TABLES LIKE 'activity_reports'");
            if ($tableCheck && $tableCheck->num_rows > 0) {
                echo '<div class="status success">✅ activity_reports table exists</div>';
                
                // Count reports
                $countResult = $conn->query("SELECT COUNT(*) as count FROM activity_reports");
                if ($countResult) {
                    $count = $countResult->fetch_assoc();
                    echo '<p>Total reports in database: <strong>' . $count['count'] . '</strong></p>';
                }
            } else {
                echo '<div class="status error">❌ activity_reports table not found - Run database_activity_reports.sql</div>';
            }
            
            // Check if role column exists in deployment
            $roleCheck = $conn->query("SHOW COLUMNS FROM deployment LIKE 'role'");
            if ($roleCheck && $roleCheck->num_rows > 0) {
                echo '<div class="status success">✅ role column exists in deployment table</div>';
            } else {
                echo '<div class="status error">❌ role column not found in deployment table - Run database_deployment_role.sql</div>';
            }
            
            // Check uploads directory
            if (file_exists('uploads/activity_reports/')) {
                echo '<div class="status success">✅ uploads/activity_reports/ directory exists</div>';
            } else {
                echo '<div class="status warning">⚠️ uploads/activity_reports/ directory not found - Will be created on first upload</div>';
            }
            
        } else {
            echo '<div class="status error">❌ Database connection failed</div>';
        }
        echo '</div>';
    }
    ?>

    <div class="test-section" style="background: linear-gradient(135deg, #fff5f5 0%, #fee 100%); border-left: 5px solid #dc143c;">
        <h2>📌 Important Notes</h2>
        <ul>
            <li>All files have been created with no syntax errors</li>
            <li>Form includes auto-save draft functionality (saves every 30 seconds)</li>
            <li>File uploads limited to 5 files, 5MB each</li>
            <li>Supported formats: JPG, PNG, GIF, PDF, DOC, DOCX</li>
            <li>Reports are submitted with "pending" status by default</li>
            <li>Volunteers can only see their own reports</li>
            <li>Duplicate report prevention (one report per deployment)</li>
        </ul>
    </div>
</body>
</html>
