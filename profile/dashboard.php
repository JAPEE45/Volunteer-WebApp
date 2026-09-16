<?php
include_once '../utility/db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    // User not found, redirect to login
    session_destroy();
    header('Location: ../login.php');
    exit();
}

$result = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Volunteer Dashboard - Philippine Red Cross</title>
<link rel="stylesheet" href="./style/dashboard.css"/>

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
      <a href="dashboard.php" class="nav-item active">
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
      <a href="myReports.php" class="nav-item">
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
    <div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
      <div class="header-content">
        <div class="welcome-text">
          <span>👋</span>
          <span>Welcome Back, <span id="volunteerName"><?php echo ($result['given_name'] ?? $result['firstName'] ?? '')." ".($result['middle_name'] ?? $result['middleName'] ?? '')." ".($result['last_name'] ?? $result['lastName'] ?? ''); ?></span></span>
        </div>
        <div class="volunteer-info">
          <span>Volunteer ID: <strong id="volunteerId">VL-<?php echo str_pad($user_id, 4, '0', STR_PAD_LEFT); ?></strong></span> • 
          <span>Member since: <strong id="memberSince"><?php echo $result['created_at'] ?? $result['createdAt'] ?? '';?></strong></span>
        </div>
      </div>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">🚑</div>
        <div class="stat-label">Total Deployments</div>
        <div class="stat-value" id="totalDeployments">0</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">⏱️</div>
        <div class="stat-label">Active Hours</div>
        <div class="stat-value" id="activeHours">0</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🎯</div>
        <div class="stat-label">Missions Completed</div>
        <div class="stat-value" id="missionsCompleted">0</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">⭐</div>
        <div class="stat-label">Recognition Points</div>
        <div class="stat-value" id="recognitionPoints">0</div>
      </div>
    </div>

    <!-- Current Deployment -->
    <div>
      <h2 class="section-title">Current Deployment</h2>
      <div class="current-deployment" id="currentDeploymentSection">
        <div class="deployment-status inactive">
          <span class="status-indicator"></span>
          <span>LOADING...</span>
        </div>
      </div>
    </div>

    <!-- Deployment History -->
    <div>
      <h2 class="section-title">Deployment History</h2>
      <div class="history-section">
        <div class="timeline" id="deploymentTimeline">
          <!-- Timeline items will be loaded dynamically -->
          <div style="text-align: center; padding: 2rem; color: #718096;">
            Loading deployment history...
          </div>
        </div>
      </div>
    </div>
  </div>

    </div>
  </main>

  <script>
    // Toggle sidebar for mobile
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.querySelector('.sidebar-overlay');
      sidebar.classList.toggle('active');
      overlay.classList.toggle('active');
    }

    function navigateTo(page) {
      document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
      });
      event.currentTarget.classList.add('active');
      if (window.innerWidth <= 768) {
        toggleSidebar();
      }
      switch(page) {
        case 'dashboard':
          console.log('Navigate to Dashboard');
          break;
        case 'profile':
          console.log('Navigate to Profile');
          break;
        case 'report':
          console.log('Navigate to Report');
          break;
      }
    }

    function handleLogout() {
      if (confirm('Are you sure you want to logout?')) {
        console.log('Logging out...');
        window.location.href = '../index.php';
      }
    }

    function formatDate(dateString) {
      const date = new Date(dateString);
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      return date.toLocaleDateString('en-US', options);
    }

    async function loadVolunteerData() {
      try {
        const response = await fetch('../utility/getVolunteerDashboard.php');
        const data = await response.json();
        
        if (!data.success) {
          throw new Error(data.error || 'Failed to load data');
        }

        // Update statistics
        document.getElementById('totalDeployments').textContent = data.totalDeployments || 0;
        document.getElementById('activeHours').textContent = data.activeHours || 0;
        document.getElementById('missionsCompleted').textContent = data.missionsCompleted || 0;
        document.getElementById('recognitionPoints').textContent = data.recognitionPoints || 0;

        // Update current deployment section
        const currentDeploymentSection = document.getElementById('currentDeploymentSection');
        
        if (data.userStatus === 'deployed' && data.currentDeployment) {
          const deployment = data.currentDeployment;
          currentDeploymentSection.innerHTML = `
            <div class="deployment-status active">
              <span class="status-indicator"></span>
              <span>ACTIVELY DEPLOYED</span>
            </div>
            <h3 style="font-size: 1.8rem; color: #2d3748; margin-bottom: 1rem;">${deployment.eventName}</h3>
            <div class="deployment-details">
              <div class="detail-item">
                <div class="detail-label">Event Name</div>
                <div class="detail-value">${deployment.eventName}</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Location</div>
                <div class="detail-value">${deployment.location}</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Event Date</div>
                <div class="detail-value">${formatDate(deployment.eventDate)}</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Deployed On</div>
                <div class="detail-value">${formatDate(deployment.deploymentDate)}</div>
              </div>
            </div>
          `;
        } else {
          currentDeploymentSection.innerHTML = `
            <div class="deployment-status inactive">
              <span class="status-indicator"></span>
              <span>NOT CURRENTLY DEPLOYED</span>
            </div>
            <div class="empty-state">
              <div class="empty-state-icon">🏠</div>
              <div class="empty-state-text">You are not currently deployed to any event</div>
            </div>
          `;
        }

        // Update deployment history
        const timelineContainer = document.getElementById('deploymentTimeline');
        
        if (data.deploymentHistory && data.deploymentHistory.length > 0) {
          timelineContainer.innerHTML = '';
          
          data.deploymentHistory.forEach((deployment, index) => {
            const timelineItem = document.createElement('div');
            timelineItem.className = 'timeline-item';
            
            timelineItem.innerHTML = `
              <div class="timeline-card">
                <div class="timeline-header">
                  <h3 class="timeline-title">${deployment.eventName}</h3>
                  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <span class="timeline-date">📅 ${formatDate(deployment.eventDate)}</span>
                    <span class="badge completed">✓ Completed</span>
                  </div>
                </div>
                <div class="timeline-details">
                  <div class="timeline-detail-item">
                    <strong>Location:</strong> ${deployment.location}
                  </div>
                  <div class="timeline-detail-item">
                    <strong>Deployed On:</strong> ${formatDate(deployment.deploymentDate)}
                  </div>
                  <div class="timeline-detail-item">
                    <strong>Event Date:</strong> ${formatDate(deployment.eventDate)}
                  </div>
                </div>
              </div>
            `;
            
            timelineContainer.appendChild(timelineItem);
          });
        } else {
          timelineContainer.innerHTML = `
            <div style="text-align: center; padding: 2rem; color: #718096;">
              <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
              <div style="font-size: 1.1rem;">No deployment history yet</div>
              <div style="font-size: 0.9rem; margin-top: 0.5rem;">Your deployment records will appear here</div>
            </div>
          `;
        }

      } catch (error) {
        console.error('Error loading volunteer data:', error);
        
        // Show error message
        document.getElementById('currentDeploymentSection').innerHTML = `
          <div class="deployment-status inactive">
            <span class="status-indicator"></span>
            <span>ERROR LOADING DATA</span>
          </div>
          <div class="empty-state">
            <div class="empty-state-icon">⚠️</div>
            <div class="empty-state-text">Failed to load deployment data. Please refresh the page.</div>
          </div>
        `;
        
        document.getElementById('deploymentTimeline').innerHTML = `
          <div style="text-align: center; padding: 2rem; color: #e53e3e;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
            <div style="font-size: 1.1rem;">Error loading deployment history</div>
          </div>
        `;
      }
    }

    // Load data when page loads
    document.addEventListener('DOMContentLoaded', loadVolunteerData);
  </script>
</body>
</html>