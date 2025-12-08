<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include_once '../utility/db.php';

$userId = $_SESSION['user_id'];

// Check if activity_reports table exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'activity_reports'");
$tableExists = $tableCheck->num_rows > 0;

$deployments = [];

if ($tableExists) {
    // Get volunteer's deployments for the dropdown
    $deploymentsQuery = $conn->prepare("
        SELECT 
            d.id as deployment_id,
            d.event_id,
            e.eventName,
            e.location,
            e.date,
            d.role,
            d.createdAt as deployment_date
        FROM deployment d
        JOIN events e ON d.event_id = e.id
        WHERE d.user_id = ?
        ORDER BY d.createdAt DESC
    ");
    $deploymentsQuery->bind_param("i", $userId);
    $deploymentsQuery->execute();
    $deploymentsResult = $deploymentsQuery->get_result();
    $deployments = $deploymentsResult->fetch_all(MYSQLI_ASSOC);
}
$deploymentsQuery->close();

// Get user info
$userQuery = $conn->prepare("SELECT fullName FROM users WHERE id = ?");
$userQuery->bind_param("i", $userId);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user = $userResult->fetch_assoc();
$userQuery->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Activity Report - Red Cross Volunteer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style/activityReport.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <img src="../img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
        </div>
        <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
        <a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="activityReport.php" style="background: rgba(255, 255, 255, 0.15);"><i class="fas fa-file-alt"></i> Submit Report</a>
        <a href="myReports.php"><i class="fas fa-folder-open"></i> My Reports</a>
        <a href="profileSms.php"><i class="fas fa-envelope"></i> Messages</a>
        <a href="profileMap.php"><i class="fas fa-map-marked-alt"></i> Map</a>
        <a href="../index.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Menu Toggle -->
    <button class="menu-toggle" id="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Main Content -->
    <div class="content">
        <div class="header">
            <h1><i class="fas fa-file-alt"></i> Submit Activity Report</h1>
            <p>Document your volunteer activities and contributions</p>
        </div>

        <div class="form-container">
            <form id="activityReportForm" enctype="multipart/form-data">
                <!-- Volunteer Info (Read-only) -->
                <div class="section">
                    <h2><i class="fas fa-user-circle"></i> Volunteer Information</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" value="<?php echo htmlspecialchars($user['fullName'] ?? 'N/A'); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Volunteer ID</label>
                            <input type="text" value="<?php echo htmlspecialchars($userId); ?>" readonly>
                        </div>
                    </div>
                </div>

                <?php if (!$tableExists): ?>
                <!-- Database Setup Notice -->
                <div class="section" style="background:linear-gradient(135deg, #fff5f5 0%, #fee 100%); border:3px solid #dc143c; text-align:center; padding:3rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size:4rem; color:#dc143c; margin-bottom:1rem;"></i>
                    <h2 style="color:#dc143c; margin-bottom:1rem;">Activity Reports Feature Not Available</h2>
                    <p style="color:#2d3748; font-size:1.1rem; margin-bottom:1.5rem;">
                        The activity reports database table has not been set up yet. Please contact your administrator to enable this feature.
                    </p>
                    <p style="color:#64748b;">
                        <strong>For Admins:</strong> Run the <code>database_activity_reports.sql</code> script in phpMyAdmin to enable this feature.
                    </p>
                </div>
                <?php else: ?>
                <!-- Deployment Selection -->
                <div class="section">
                    <h2><i class="fas fa-calendar-check"></i> Deployment Details</h2>
                    <div class="form-group">
                        <label for="deploymentSelect">Select Deployment <span class="required">*</span></label>
                        <select id="deploymentSelect" name="deployment_id" required>
                            <option value="">-- Choose a deployment to report on --</option>
                            <?php foreach($deployments as $deployment): ?>
                                <option 
                                    value="<?php echo $deployment['deployment_id']; ?>"
                                    data-event-id="<?php echo $deployment['event_id']; ?>"
                                    data-event-name="<?php echo htmlspecialchars($deployment['eventName']); ?>"
                                    data-location="<?php echo htmlspecialchars($deployment['location']); ?>"
                                    data-date="<?php echo $deployment['date']; ?>"
                                    data-role="<?php echo htmlspecialchars($deployment['role'] ?? 'Volunteer'); ?>">
                                    <?php echo htmlspecialchars($deployment['eventName']); ?> - 
                                    <?php echo $deployment['date']; ?> - 
                                    <?php echo htmlspecialchars($deployment['location']); ?>
                                    <?php if($deployment['role']): ?>
                                        (<?php echo htmlspecialchars($deployment['role']); ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="deploymentInfo" style="display: none;" class="info-box">
                        <div class="info-row">
                            <span class="info-label">Event:</span>
                            <span id="infoEventName">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Location:</span>
                            <span id="infoLocation">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date:</span>
                            <span id="infoDate">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Your Role:</span>
                            <span id="infoRole">-</span>
                        </div>
                    </div>

                    <input type="hidden" id="eventId" name="event_id">
                </div>

                <!-- Activity Details -->
                <div class="section">
                    <h2><i class="fas fa-tasks"></i> Activity Information</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="activityDate">Date of Activity <span class="required">*</span></label>
                            <input type="date" id="activityDate" name="date_of_activity" required>
                        </div>
                        <div class="form-group">
                            <label for="hoursWorked">Hours Worked <span class="required">*</span></label>
                            <input type="number" id="hoursWorked" name="hours_worked" min="0.5" max="24" step="0.5" placeholder="e.g., 8.5" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="activitiesPerformed">Activities Performed <span class="required">*</span></label>
                        <textarea id="activitiesPerformed" name="activities_performed" rows="5" placeholder="Describe the activities you performed during this deployment (e.g., First aid assistance, crowd control, relief distribution, etc.)" required></textarea>
                        <small>Provide detailed description of your tasks and responsibilities</small>
                    </div>

                    <div class="form-group">
                        <label for="challengesFaced">Challenges Faced</label>
                        <textarea id="challengesFaced" name="challenges_faced" rows="4" placeholder="Describe any challenges or difficulties encountered (optional)"></textarea>
                        <small>This helps improve future operations</small>
                    </div>

                    <div class="form-group">
                        <label for="outcomesAchieved">Outcomes Achieved</label>
                        <textarea id="outcomesAchieved" name="outcomes_achieved" rows="4" placeholder="What were the results or impacts of your work? (optional)"></textarea>
                        <small>E.g., number of people served, items distributed, etc.</small>
                    </div>

                    <div class="form-group">
                        <label for="recommendations">Recommendations</label>
                        <textarea id="recommendations" name="recommendations" rows="4" placeholder="Any suggestions for improvement? (optional)"></textarea>
                        <small>Your feedback is valuable for planning future events</small>
                    </div>
                </div>

                <!-- Supporting Documents -->
                <div class="section">
                    <h2><i class="fas fa-paperclip"></i> Supporting Documents (Optional)</h2>
                    <div class="form-group">
                        <label for="documents">Upload Photos or Documents</label>
                        <input type="file" id="documents" name="documents[]" multiple accept="image/*,.pdf,.doc,.docx">
                        <small>You can upload photos, PDFs, or documents (Max 5 files, 5MB each)</small>
                    </div>
                    <div id="filePreview" class="file-preview"></div>
                </div>

                <!-- Submit Buttons -->
                <div class="form-actions">
                    <button type="button" onclick="window.history.back()" class="btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Report
                    </button>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script src="js/activityReport.js"></script>
</body>
</html>
