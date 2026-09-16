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

$reports = [];
$stats = ['total_reports' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0, 'total_hours' => 0];

if ($tableExists) {
    // Get user's activity reports
    $reportsQuery = $conn->prepare("
        SELECT 
            ar.*,
            e.eventName,
            e.location,
            e.date as event_date,
            d.role
        FROM activity_reports ar
        JOIN events e ON ar.event_id = e.id
        LEFT JOIN deployment d ON ar.deployment_id = d.id
        WHERE ar.user_id = ?
        ORDER BY ar.submitted_at DESC
    ");
    $reportsQuery->bind_param("i", $userId);
    $reportsQuery->execute();
    $reportsResult = $reportsQuery->get_result();
    $reports = $reportsResult->fetch_all(MYSQLI_ASSOC);
    $reportsQuery->close();

    // Get statistics
    $statsQuery = $conn->prepare("
        SELECT 
            COUNT(*) as total_reports,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
            SUM(hours_worked) as total_hours
        FROM activity_reports
    WHERE user_id = ?
");
    $statsQuery->bind_param("i", $userId);
    $statsQuery->execute();
    $statsResult = $statsQuery->get_result();
    $stats = $statsResult->fetch_assoc();
    $statsQuery->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Activity Reports - Red Cross Volunteer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style/myReports.css">
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" onclick="toggleSidebar()">☰</button>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">✚</div>
            <div class="logo-text">Philippine Red Cross</div>
            <div class="logo-subtitle">Volunteer Portal</div>
        </div>

        <div class="sidebar-nav">
            <a href="dashboard.php" class="nav-item">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="profile.php" class="nav-item">
                <span class="nav-icon">👤</span>
                <span class="nav-text">Profile</span>
            </a>
            <a href="activityReport.php" class="nav-item">
                <span class="nav-icon">📄</span>
                <span class="nav-text">Submit Report</span>
            </a>
            <a href="myReports.php" class="nav-item active">
                <span class="nav-icon">📂</span>
                <span class="nav-text">My Reports</span>
            </a>
            <a href="profileMap.php" class="nav-item">
                <span class="nav-icon">🗺️</span>
                <span class="nav-text">Map</span>
            </a>
            
            <div class="nav-logout">
                <a href="#logout" class="nav-item logout" onclick="handleLogout()">
                    <span class="nav-icon">🚪</span>
                    <span class="nav-text">Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content">
        <div class="header">
            <h1><i class="fas fa-folder-open"></i> My Activity Reports</h1>
            <a href="activityReport.php" class="btn-new-report">
                <i class="fas fa-plus"></i> Submit New Report
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_reports'] ?? 0; ?></h3>
                    <p>Total Reports</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['pending'] ?? 0; ?></h3>
                    <p>Pending Review</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['approved'] ?? 0; ?></h3>
                    <p>Approved</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo number_format($stats['total_hours'] ?? 0, 1); ?></h3>
                    <p>Total Hours</p>
                </div>
            </div>
        </div>

        <!-- Reports List -->
        <div class="reports-container">
            <?php if (!$tableExists): ?>
                <div class="empty-state" style="background:linear-gradient(135deg, #fff5f5 0%, #fee 100%); border:3px solid #dc143c; padding:3rem;">
                    <i class="fas fa-database" style="color:#dc143c;"></i>
                    <h2 style="color:#dc143c;">Activity Reports Not Available</h2>
                    <p style="color:#2d3748;">The activity reports feature requires database setup. Please contact your administrator to enable this feature.</p>
                    <p style="color:#64748b; margin-top:1rem;"><strong>For Admins:</strong> Run the <code>database_activity_reports.sql</code> script in phpMyAdmin.</p>
                </div>
            <?php elseif (empty($reports)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h2>No Reports Yet</h2>
                    <p>You haven't submitted any activity reports.</p>
                    <a href="activityReport.php" class="btn-primary">
                        <i class="fas fa-plus"></i> Submit Your First Report
                    </a>
                </div>
            <?php else: ?>
                <?php foreach($reports as $report): ?>
                    <div class="report-card">
                        <div class="report-header">
                            <div class="report-title">
                                <h3><?php echo htmlspecialchars($report['eventName']); ?></h3>
                                <p class="report-meta">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($report['location']); ?>
                                    <span class="separator">•</span>
                                    <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($report['date_of_activity'])); ?>
                                    <?php if($report['role']): ?>
                                        <span class="separator">•</span>
                                        <i class="fas fa-user-tag"></i> <?php echo htmlspecialchars($report['role']); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="report-status">
                                <?php
                                $statusClass = '';
                                $statusIcon = '';
                                switch($report['status']) {
                                    case 'pending':
                                        $statusClass = 'status-pending';
                                        $statusIcon = 'fa-clock';
                                        break;
                                    case 'approved':
                                        $statusClass = 'status-approved';
                                        $statusIcon = 'fa-check-circle';
                                        break;
                                    case 'rejected':
                                        $statusClass = 'status-rejected';
                                        $statusIcon = 'fa-times-circle';
                                        break;
                                }
                                ?>
                                <span class="status-badge <?php echo $statusClass; ?>">
                                    <i class="fas <?php echo $statusIcon; ?>"></i>
                                    <?php echo ucfirst($report['status']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="report-body">
                            <div class="report-detail">
                                <i class="fas fa-hourglass-half"></i>
                                <strong>Hours Worked:</strong> <?php echo number_format($report['hours_worked'], 1); ?> hours
                            </div>
                            
                            <div class="report-detail">
                                <i class="fas fa-tasks"></i>
                                <strong>Activities:</strong>
                                <p><?php echo nl2br(htmlspecialchars(substr($report['activities_performed'], 0, 200))); ?>
                                <?php if(strlen($report['activities_performed']) > 200): ?>...<?php endif; ?></p>
                            </div>

                            <?php if($report['admin_notes']): ?>
                                <div class="admin-notes">
                                    <i class="fas fa-comment-dots"></i>
                                    <strong>Admin Notes:</strong>
                                    <p><?php echo nl2br(htmlspecialchars($report['admin_notes'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="report-footer">
                            <span class="submitted-date">
                                <i class="fas fa-paper-plane"></i>
                                Submitted on <?php echo date('M d, Y \a\t h:i A', strtotime($report['submitted_at'])); ?>
                            </span>
                            <button class="btn-view" onclick="viewReport(<?php echo $report['id']; ?>)">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        </div>
    </main>

    <!-- View Report Modal -->
    <div id="viewReportModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-file-alt"></i> Activity Report Details</h2>
                <button class="modal-close" onclick="closeViewModal()">&times;</button>
            </div>
            <div id="modalBody" class="modal-body">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <script src="js/myReports.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function handleLogout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '../index.php';
            }
        }
    </script>
</body>
</html>
