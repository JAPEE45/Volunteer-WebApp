<?php
session_start();
include_once "./utility/db.php";

// Check if user is admin (basic check - enhance as needed)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if activity_reports table exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'activity_reports'");
$tableExists = $tableCheck->num_rows > 0;

$reports = [];
$stats = ['total_reports' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0, 'total_hours' => 0];
$events = [];

if ($tableExists) {
    // Get filter parameters
    $statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';
    $eventFilter = isset($_GET['event']) ? intval($_GET['event']) : 0;
    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Build query
    $whereConditions = ["1=1"];
    $params = [];
    $types = "";

    if ($statusFilter !== 'all') {
        $whereConditions[] = "ar.status = ?";
        $params[] = $statusFilter;
        $types .= "s";
    }

    if ($eventFilter > 0) {
        $whereConditions[] = "ar.event_id = ?";
        $params[] = $eventFilter;
        $types .= "i";
    }

    if (!empty($searchTerm)) {
        $whereConditions[] = "(u.fullName LIKE ? OR e.eventName LIKE ?)";
        $searchParam = "%{$searchTerm}%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $types .= "ss";
    }

    $whereClause = implode(" AND ", $whereConditions);

    // Get all reports with volunteer and event info
    $query = "
        SELECT 
            ar.*,
            u.fullName as volunteer_name,
            u.mobile,
            e.eventName,
            e.location,
            e.date as event_date,
            d.role
        FROM activity_reports ar
        JOIN users u ON ar.user_id = u.id
        JOIN events e ON ar.event_id = e.id
        LEFT JOIN deployment d ON ar.deployment_id = d.id
        WHERE {$whereClause}
        ORDER BY ar.submitted_at DESC
    ";

    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $reports = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Get statistics
    $statsQuery = $conn->query("
        SELECT 
            COUNT(*) as total_reports,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
            SUM(hours_worked) as total_hours
        FROM activity_reports
    ");
    $stats = $statsQuery->fetch_assoc();
}

// Get all events for filter dropdown (always available)
$eventsQuery = $conn->query("SELECT id, eventName FROM events ORDER BY eventName ASC");
$events = $eventsQuery->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Reports - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style/volunteers.css">
    <style>
        .reports-filters {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(220, 20, 60, 0.1);
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .filter-group label {
            font-weight: 600;
            color: #2d3748;
            font-size: 0.9rem;
        }
        
        .filter-group select,
        .filter-group input {
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            min-width: 200px;
        }
        
        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #dc143c;
        }
        
        .filter-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: auto;
        }
        
        .btn-filter {
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
        }
        
        .btn-clear {
            padding: 0.75rem 1.5rem;
            background: #64748b;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .report-row {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            border-left: 4px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .report-row:hover {
            border-left-color: #dc143c;
            box-shadow: 0 4px 12px rgba(220, 20, 60, 0.1);
        }
        
        .report-row.status-pending {
            border-left-color: #f59e0b;
        }
        
        .report-row.status-approved {
            border-left-color: #10b981;
        }
        
        .report-row.status-rejected {
            border-left-color: #ef4444;
        }
        
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #fee;
        }
        
        .report-info h3 {
            color: #dc143c;
            margin: 0 0 0.5rem 0;
            font-size: 1.2rem;
        }
        
        .report-meta {
            color: #64748b;
            font-size: 0.9rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .report-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .btn-approve {
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.85rem;
        }
        
        .btn-reject {
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.85rem;
        }
        
        .btn-view-report {
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.85rem;
        }
        
        .report-body {
            margin: 1rem 0;
        }
        
        .report-detail {
            padding: 0.75rem;
            background: #f7fafc;
            border-radius: 8px;
            margin-bottom: 0.75rem;
        }
        
        .report-detail strong {
            color: #2d3748;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
        }
        
        .status-approved {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }
        
        .status-rejected {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 20px;
        }
        
        .empty-state i {
            font-size: 5rem;
            color: #e2e8f0;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <img src="img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
        </div>
        <a href="homepage.php"><i class="fas fa-home"></i> Home</a>
        <a href="volunteers.php"><i class="fas fa-users"></i> Volunteers</a>
        <a href="events.php"><i class="fas fa-calendar"></i> Events</a>
        <a href="accounts.php"><i class="fas fa-user-shield"></i> Accounts</a>
        <a href="adminActivityReports.php" style="background: rgba(255, 255, 255, 0.15);"><i class="fas fa-file-alt"></i> Activity Reports</a>
        <!-- <a href="sms.php"><i class="fas fa-envelope"></i> SMS</a> -->
        <a href="map.php"><i class="fas fa-map-marked-alt"></i> Map</a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Menu Toggle -->
    <button class="menu-toggle" id="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Main Content -->
    <div class="content">
        <div class="table-header">
            <h1><i class="fas fa-file-alt"></i> ACTIVITY REPORTS</h1>
            <div style="display:flex;gap:1rem;align-items:center;">
                <div class="stat-badge" style="background:#3b82f6;color:white;">
                    <i class="fas fa-file"></i> <?php echo $stats['total_reports'] ?? 0; ?> Total
                </div>
                <div class="stat-badge" style="background:#f59e0b;color:white;">
                    <i class="fas fa-clock"></i> <?php echo $stats['pending'] ?? 0; ?> Pending
                </div>
                <div class="stat-badge" style="background:#10b981;color:white;">
                    <i class="fas fa-check"></i> <?php echo $stats['approved'] ?? 0; ?> Approved
                </div>
            </div>
        </div>

        <!-- Filters -->
        <form class="reports-filters" method="GET" action="">
            <div class="filter-group">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="all" <?php echo $statusFilter === 'all' ? 'selected' : ''; ?>>All Status</option>
                    <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="approved" <?php echo $statusFilter === 'approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="rejected" <?php echo $statusFilter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="event">Event</label>
                <select name="event" id="event">
                    <option value="0">All Events</option>
                    <?php foreach($events as $event): ?>
                        <option value="<?php echo $event['id']; ?>" <?php echo $eventFilter == $event['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($event['eventName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="search">Search</label>
                <input type="text" name="search" id="search" placeholder="Volunteer name or event..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="adminActivityReports.php" class="btn-clear">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>

        <!-- Reports List -->
        <div class="reports-container">
            <?php if (!$tableExists): ?>
                <div class="empty-state" style="background:linear-gradient(135deg, #fff5f5 0%, #fee 100%); border:3px solid #dc143c;">
                    <i class="fas fa-database" style="color:#dc143c;"></i>
                    <h2 style="color:#dc143c;">Activity Reports Table Not Found</h2>
                    <p style="color:#2d3748; margin-bottom:1.5rem;">The activity reports feature requires database setup. Please run the SQL script to create the necessary table.</p>
                    <div style="background:white; padding:1.5rem; border-radius:12px; text-align:left; max-width:600px; margin:0 auto; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                        <h3 style="color:#dc143c; margin-top:0;">Setup Instructions:</h3>
                        <ol style="text-align:left; line-height:2;">
                            <li>Open <strong>phpMyAdmin</strong> in your browser</li>
                            <li>Select the <strong>volunteer-web</strong> database</li>
                            <li>Go to the <strong>SQL</strong> tab</li>
                            <li>Create a file named <code>database_activity_reports.sql</code> with the table schema</li>
                            <li>Paste and execute the SQL to create the <code>activity_reports</code> table</li>
                            <li>Refresh this page</li>
                        </ol>
                        <p style="margin-bottom:0;"><strong>Note:</strong> The SQL script should be in your project root. Contact your developer if you need the schema.</p>
                    </div>
                </div>
            <?php elseif (empty($reports)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h2>No Reports Found</h2>
                    <p>No activity reports match your filters.</p>
                </div>
            <?php else: ?>
                <?php foreach($reports as $report): ?>
                    <div class="report-row status-<?php echo $report['status']; ?>">
                        <div class="report-header">
                            <div class="report-info">
                                <h3><?php echo htmlspecialchars($report['volunteer_name']); ?></h3>
                                <div class="report-meta">
                                    <span><i class="fas fa-calendar-check"></i> <?php echo htmlspecialchars($report['eventName']); ?></span>
                                    <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($report['location']); ?></span>
                                    <span><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($report['date_of_activity'])); ?></span>
                                    <?php if($report['role']): ?>
                                        <span><i class="fas fa-user-tag"></i> <?php echo htmlspecialchars($report['role']); ?></span>
                                    <?php endif; ?>
                                    <span><i class="fas fa-hourglass"></i> <?php echo number_format($report['hours_worked'], 1); ?> hrs</span>
                                </div>
                            </div>
                            <div>
                                <span class="status-badge status-<?php echo $report['status']; ?>">
                                    <?php
                                    $icon = $report['status'] === 'pending' ? 'fa-clock' : 
                                           ($report['status'] === 'approved' ? 'fa-check-circle' : 'fa-times-circle');
                                    ?>
                                    <i class="fas <?php echo $icon; ?>"></i>
                                    <?php echo ucfirst($report['status']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="report-body">
                            <div class="report-detail">
                                <strong>Activities:</strong> 
                                <?php echo nl2br(htmlspecialchars(substr($report['activities_performed'], 0, 150))); ?>
                                <?php if(strlen($report['activities_performed']) > 150): ?>...<?php endif; ?>
                            </div>
                            <small style="color:#64748b;">
                                <i class="fas fa-paper-plane"></i> Submitted on <?php echo date('M d, Y \a\t h:i A', strtotime($report['submitted_at'])); ?>
                            </small>
                        </div>

                        <div class="report-actions">
                            <button class="btn-view-report" onclick="viewReportAdmin(<?php echo $report['id']; ?>)">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <?php if($report['status'] === 'pending'): ?>
                                <button class="btn-approve" onclick="reviewReport(<?php echo $report['id']; ?>, 'approve')">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button class="btn-reject" onclick="reviewReport(<?php echo $report['id']; ?>, 'reject')">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- View/Review Modal -->
    <div id="reviewModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:white; border-radius:20px; width:90%; max-width:900px; max-height:90vh; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
            <div style="background:linear-gradient(135deg, #dc143c 0%, #a00000 100%); color:white; padding:1.5rem 2rem; display:flex; justify-content:space-between; align-items:center;">
                <h2 style="margin:0; font-size:1.5rem;"><i class="fas fa-file-alt"></i> Activity Report Details</h2>
                <button onclick="closeReviewModal()" style="background:rgba(255,255,255,0.2); border:2px solid white; color:white; width:35px; height:35px; border-radius:50%; cursor:pointer; font-size:1.5rem;">&times;</button>
            </div>
            <div id="reviewModalBody" style="padding:2rem; max-height:calc(90vh - 80px); overflow-y:auto;">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle
        const toggleBtn = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");
        
        if (toggleBtn) {
            toggleBtn.addEventListener("click", () => {
                sidebar.classList.toggle("active");
            });
        }

        // View report details
        async function viewReportAdmin(reportId) {
            const modal = document.getElementById('reviewModal');
            const modalBody = document.getElementById('reviewModalBody');
            
            modalBody.innerHTML = '<div style="text-align:center;padding:3rem;"><i class="fas fa-spinner fa-spin" style="font-size:3rem;color:#dc143c;"></i><p style="margin-top:1rem;">Loading...</p></div>';
            modal.style.display = 'flex';
            
            try {
                const response = await fetch(`utility/getActivityReportAdmin.php?id=${reportId}`);
                const data = await response.json();
                
                if (data.success) {
                    displayAdminReportView(data.report);
                } else {
                    modalBody.innerHTML = '<div style="text-align:center;padding:3rem;color:#ef4444;"><p>Error loading report</p></div>';
                }
            } catch (error) {
                console.error('Error:', error);
                modalBody.innerHTML = '<div style="text-align:center;padding:3rem;color:#ef4444;"><p>Error loading report</p></div>';
            }
        }

        function displayAdminReportView(report) {
            const modalBody = document.getElementById('reviewModalBody');
            const docs = report.supporting_documents ? JSON.parse(report.supporting_documents) : [];
            
            modalBody.innerHTML = `
                <div style="margin-bottom:2rem; padding:1.5rem; background:#f7fafc; border-radius:12px;">
                    <h3 style="margin:0 0 1rem 0; color:#dc143c;">Volunteer Information</h3>
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
                        <div><strong>Name:</strong> ${report.volunteer_name}</div>
                        <div><strong>Contact:</strong> ${report.mobile || 'N/A'}</div>
                        <div><strong>Event:</strong> ${report.eventName}</div>
                        <div><strong>Role:</strong> ${report.role || 'Volunteer'}</div>
                        <div><strong>Date:</strong> ${new Date(report.date_of_activity).toLocaleDateString()}</div>
                        <div><strong>Hours:</strong> ${parseFloat(report.hours_worked).toFixed(1)} hours</div>
                    </div>
                </div>

                <div style="margin-bottom:1.5rem;">
                    <h4 style="color:#2d3748; margin-bottom:0.5rem;">Activities Performed:</h4>
                    <div style="padding:1rem; background:#f7fafc; border-radius:8px; white-space:pre-wrap;">${report.activities_performed}</div>
                </div>

                ${report.challenges_faced ? `
                    <div style="margin-bottom:1.5rem;">
                        <h4 style="color:#2d3748; margin-bottom:0.5rem;">Challenges Faced:</h4>
                        <div style="padding:1rem; background:#f7fafc; border-radius:8px; white-space:pre-wrap;">${report.challenges_faced}</div>
                    </div>
                ` : ''}

                ${report.outcomes_achieved ? `
                    <div style="margin-bottom:1.5rem;">
                        <h4 style="color:#2d3748; margin-bottom:0.5rem;">Outcomes Achieved:</h4>
                        <div style="padding:1rem; background:#f7fafc; border-radius:8px; white-space:pre-wrap;">${report.outcomes_achieved}</div>
                    </div>
                ` : ''}

                ${report.recommendations ? `
                    <div style="margin-bottom:1.5rem;">
                        <h4 style="color:#2d3748; margin-bottom:0.5rem;">Recommendations:</h4>
                        <div style="padding:1rem; background:#f7fafc; border-radius:8px; white-space:pre-wrap;">${report.recommendations}</div>
                    </div>
                ` : ''}

                ${docs.length > 0 ? `
                    <div style="margin-bottom:1.5rem;">
                        <h4 style="color:#2d3748; margin-bottom:0.5rem;">Supporting Documents:</h4>
                        <div style="display:flex; flex-direction:column; gap:0.5rem;">
                            ${docs.map(doc => {
                                const fileName = doc.split('/').pop();
                                return `<a href="uploads/${doc}" target="_blank" style="padding:0.75rem; background:#f7fafc; border:2px solid #e2e8f0; border-radius:8px; color:#dc143c; text-decoration:none; display:flex; align-items:center; gap:0.5rem;"><i class="fas fa-file"></i> ${fileName}</a>`;
                            }).join('')}
                        </div>
                    </div>
                ` : ''}

                ${report.status === 'pending' ? `
                    <div style="border-top:2px solid #fee; padding-top:1.5rem; margin-top:1.5rem;">
                        <h4 style="color:#2d3748; margin-bottom:1rem;">Review This Report:</h4>
                        <div style="margin-bottom:1rem;">
                            <label style="display:block; font-weight:600; margin-bottom:0.5rem;">Admin Notes (Optional):</label>
                            <textarea id="adminNotes" rows="3" style="width:100%; padding:0.75rem; border:2px solid #e2e8f0; border-radius:8px; font-family:inherit;" placeholder="Add notes or feedback for the volunteer..."></textarea>
                        </div>
                        <div style="display:flex; gap:1rem;">
                            <button onclick="submitReview(${report.id}, 'approved')" style="flex:1; padding:0.875rem; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border:none; border-radius:8px; font-weight:700; cursor:pointer;">
                                <i class="fas fa-check"></i> Approve Report
                            </button>
                            <button onclick="submitReview(${report.id}, 'rejected')" style="flex:1; padding:0.875rem; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border:none; border-radius:8px; font-weight:700; cursor:pointer;">
                                <i class="fas fa-times"></i> Reject Report
                            </button>
                        </div>
                    </div>
                ` : `
                    <div style="background:linear-gradient(135deg, #fff5f5 0%, #fee 100%); border-left:4px solid #dc143c; padding:1.5rem; border-radius:12px;">
                        <h4 style="margin:0 0 0.5rem 0;">Status: ${report.status.charAt(0).toUpperCase() + report.status.slice(1)}</h4>
                        ${report.admin_notes ? `<p style="margin:0; white-space:pre-wrap;">${report.admin_notes}</p>` : ''}
                        ${report.reviewed_at ? `<small style="color:#64748b;">Reviewed on ${new Date(report.reviewed_at).toLocaleString()}</small>` : ''}
                    </div>
                `}
            `;
        }

        async function submitReview(reportId, status) {
            const notes = document.getElementById('adminNotes').value;
            
            if (!confirm(`Are you sure you want to ${status === 'approved' ? 'approve' : 'reject'} this report?`)) {
                return;
            }
            
            try {
                const response = await fetch('utility/reviewActivityReport.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        report_id: reportId,
                        status: status,
                        admin_notes: notes
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert(`Report ${status} successfully!`);
                    closeReviewModal();
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error submitting review');
            }
        }

        function reviewReport(reportId, action) {
            viewReportAdmin(reportId);
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').style.display = 'none';
        }

        // Close modal on outside click
        document.getElementById('reviewModal').addEventListener('click', function(e) {
            if (e.target === this) closeReviewModal();
        });
    </script>
</body>
</html>
