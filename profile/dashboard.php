<?php
include_once '../utility/db.php';
session_start();
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();
$result = $res->fetch_assoc();
echo $result['firstName'];
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
      <a href="profileMap.php" class="nav-item">
        <span class="nav-icon">🗺️</span>
        <span class="nav-text">Map</span>
      </a>
      <a href="report.php" class="nav-item">
        <span class="nav-icon">📝</span>
        <span class="nav-text">Report</span>
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
          <span>Welcome Back, <span id="volunteerName"><?php echo $result['firstName']." ".$result['middleName']." ".$result['lastName']; ?></span></span>
        </div>
        <div class="volunteer-info">
          <span>Volunteer ID: <strong id="volunteerId">VL-2025-0001</strong></span> • 
          <span>Member since: <strong id="memberSince"><?php echo $result['createdAt'];?></strong></span>
        </div>
      </div>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">🚑</div>
        <div class="stat-label">Total Deployments</div>
        <div class="stat-value" id="totalDeployments">12</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">⏱️</div>
        <div class="stat-label">Active Hours</div>
        <div class="stat-value" id="activeHours">156</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🎯</div>
        <div class="stat-label">Missions Completed</div>
        <div class="stat-value" id="missionsCompleted">10</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">⭐</div>
        <div class="stat-label">Recognition Points</div>
        <div class="stat-value" id="recognitionPoints">485</div>
      </div>
    </div>

    <!-- Current Deployment -->
    <div>
      <h2 class="section-title">Current Deployment</h2>
      <div class="current-deployment" id="currentDeploymentSection">
        <div class="deployment-status active">
          <span class="status-indicator"></span>
          <span>ACTIVELY DEPLOYED</span>
        </div>
        <h3 style="font-size: 1.8rem; color: #2d3748; margin-bottom: 1rem;">Flood Relief Operation - Masbate</h3>
        <div class="deployment-details">
          <div class="detail-item">
            <div class="detail-label">Event Name</div>
            <div class="detail-value">Flood Relief Operation</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Location</div>
            <div class="detail-value">Masbate City</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Start Date</div>
            <div class="detail-value">Oct 10, 2025</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Expected Duration</div>
            <div class="detail-value">7 Days</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Your Role</div>
            <div class="detail-value">Medical Assistant</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Team Leader</div>
            <div class="detail-value">Dr. Maria Santos</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Deployment History -->
    <div>
      <h2 class="section-title">Deployment History</h2>
      <div class="history-section">
        <div class="timeline" id="deploymentTimeline">
          <!-- Timeline Item 1 -->
          <div class="timeline-item">
            <div class="timeline-card">
              <div class="timeline-header">
                <h3 class="timeline-title">Medical Mission - Quezon City</h3>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                  <span class="timeline-date">📅 Sep 15 - 17, 2025</span>
                  <span class="badge completed">✓ Completed</span>
                </div>
              </div>
              <div class="timeline-details">
                <div class="timeline-detail-item">
                  <strong>Location:</strong> Quezon City Health Center
                </div>
                <div class="timeline-detail-item">
                  <strong>Role:</strong> First Aid Responder
                </div>
                <div class="timeline-detail-item">
                  <strong>Duration:</strong> 3 Days (24 hours)
                </div>
                <div class="timeline-detail-item">
                  <strong>People Served:</strong> 150+ patients
                </div>
              </div>
            </div>
          </div>

          <!-- Timeline Item 2 -->
          <div class="timeline-item">
            <div class="timeline-card">
              <div class="timeline-header">
                <h3 class="timeline-title">Earthquake Response - Batangas</h3>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                  <span class="timeline-date">📅 Aug 20 - 27, 2025</span>
                  <span class="badge completed">✓ Completed</span>
                </div>
              </div>
              <div class="timeline-details">
                <div class="timeline-detail-item">
                  <strong>Location:</strong> Batangas City Emergency Shelter
                </div>
                <div class="timeline-detail-item">
                  <strong>Role:</strong> Relief Distribution Coordinator
                </div>
                <div class="timeline-detail-item">
                  <strong>Duration:</strong> 7 Days (56 hours)
                </div>
                <div class="timeline-detail-item">
                  <strong>Families Assisted:</strong> 200+ families
                </div>
              </div>
            </div>
          </div>

          <!-- Timeline Item 3 -->
          <div class="timeline-item">
            <div class="timeline-card">
              <div class="timeline-header">
                <h3 class="timeline-title">Blood Donation Drive - Makati</h3>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                  <span class="timeline-date">📅 Jul 10, 2025</span>
                  <span class="badge completed">✓ Completed</span>
                </div>
              </div>
              <div class="timeline-details">
                <div class="timeline-detail-item">
                  <strong>Location:</strong> Makati Sports Complex
                </div>
                <div class="timeline-detail-item">
                  <strong>Role:</strong> Donor Registration Staff
                </div>
                <div class="timeline-detail-item">
                  <strong>Duration:</strong> 1 Day (8 hours)
                </div>
                <div class="timeline-detail-item">
                  <strong>Blood Units Collected:</strong> 85 units
                </div>
              </div>
            </div>
          </div>

          <!-- Timeline Item 4 -->
          <div class="timeline-item">
            <div class="timeline-card">
              <div class="timeline-header">
                <h3 class="timeline-title">Typhoon Relief - Catanduanes</h3>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                  <span class="timeline-date">📅 Jun 5 - 12, 2025</span>
                  <span class="badge completed">✓ Completed</span>
                </div>
              </div>
              <div class="timeline-details">
                <div class="timeline-detail-item">
                  <strong>Location:</strong> Virac, Catanduanes
                </div>
                <div class="timeline-detail-item">
                  <strong>Role:</strong> Emergency Medical Technician
                </div>
                <div class="timeline-detail-item">
                  <strong>Duration:</strong> 7 Days (68 hours)
                </div>
                <div class="timeline-detail-item">
                  <strong>Community Members Helped:</strong> 300+
                </div>
              </div>
            </div>
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
        window.location.href = 'logout.php';
      }
    }

    async function loadVolunteerData() {
      try {
        const data = {
          name: "Juan Dela Cruz",
          volunteerId: "VL-2025-0001",
          memberSince: "January 2025",
          totalDeployments: 12,
          activeHours: 156,
          missionsCompleted: 10,
          recognitionPoints: 485,
          currentDeployment: {
            active: true,
            eventName: "Flood Relief Operation",
            location: "Masbate City",
            startDate: "Oct 10, 2025",
            duration: "7 Days",
            role: "Medical Assistant",
            teamLeader: "Dr. Maria Santos"
          }
        };

        // Update UI with data
        // document.getElementById('volunteerName').textContent = data.name;
        document.getElementById('volunteerId').textContent = data.volunteerId;
        document.getElementById('totalDeployments').textContent = data.totalDeployments;
        document.getElementById('activeHours').textContent = data.activeHours;
        document.getElementById('missionsCompleted').textContent = data.missionsCompleted;
        document.getElementById('recognitionPoints').textContent = data.recognitionPoints;

        // If no current deployment
        if (!data.currentDeployment.active) {
          document.getElementById('currentDeploymentSection').innerHTML = `
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
      } catch (error) {
        console.error('Error loading volunteer data:', error);
      }
    }
    document.addEventListener('DOMContentLoaded', loadVolunteerData);
  </script>
</body>
</html>