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
  <title>Volunteers Map - Philippine Red Cross</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
      background: linear-gradient(135deg, #fff5f5 0%, #fee 100%);
      min-height: 100vh;
      display: flex;
    }

    .sidebar {
      width: 280px;
      background: linear-gradient(180deg, #dc143c 0%, #a00000 100%);
      color: white;
      padding: 2rem 0;
      position: fixed;
      height: 100vh;
      left: 0;
      top: 0;
      box-shadow: 4px 0 20px rgba(220, 20, 60, 0.3);
      z-index: 1000;
      transition: transform 0.3s ease;
      overflow-y: auto;
    }

    .sidebar::-webkit-scrollbar {
      width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.1);
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.3);
      border-radius: 10px;
    }

    .sidebar-logo {
      text-align: center;
      padding: 0 1.5rem 2rem;
      border-bottom: 2px solid rgba(255, 255, 255, 0.2);
      margin-bottom: 2rem;
    }

    .logo-icon {
      font-size: 3.5rem;
      margin-bottom: 0.5rem;
    }

    .logo-text {
      font-size: 1.2rem;
      font-weight: 700;
      line-height: 1.4;
    }

    .logo-subtitle {
      font-size: 0.8rem;
      opacity: 0.8;
      margin-top: 0.25rem;
    }

    .sidebar-nav {
      padding: 0 1rem;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem 1.5rem;
      margin-bottom: 0.5rem;
      border-radius: 12px;
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    .nav-item::before {
      content: "";
      position: absolute;
      left: 0;
      top: 0;
      width: 0;
      height: 100%;
      background: rgba(255, 255, 255, 0.15);
      transition: width 0.3s ease;
    }

    .nav-item:hover::before {
      width: 100%;
    }

    .nav-item:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateX(5px);
    }

    .nav-item.active {
      background: rgba(255, 255, 255, 0.2);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .nav-item.active::after {
      content: "";
      position: absolute;
      right: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 60%;
      background: white;
      border-radius: 10px 0 0 10px;
    }

    .nav-icon {
      font-size: 1.5rem;
      min-width: 30px;
      text-align: center;
      position: relative;
      z-index: 1;
    }

    .nav-text {
      position: relative;
      z-index: 1;
    }

    .nav-logout {
      margin-top: 2rem;
      border-top: 2px solid rgba(255, 255, 255, 0.2);
      padding-top: 1rem;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem 1.5rem;
      margin-bottom: 0.5rem;
      border-radius: 12px;
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    .nav-item::before {
      content: "";
      position: absolute;
      left: 0;
      top: 0;
      width: 0;
      height: 100%;
      background: rgba(255, 255, 255, 0.15);
      transition: width 0.3s ease;
    }

    .nav-item:hover::before {
      width: 100%;
    }

    .nav-item:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateX(5px);
    }

    .nav-item.active {
      background: rgba(255, 255, 255, 0.2);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .nav-item.active::after {
      content: "";
      position: absolute;
      right: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 60%;
      background: white;
      border-radius: 10px 0 0 10px;
    }

    .nav-icon {
      font-size: 1.5rem;
      min-width: 30px;
      text-align: center;
      position: relative;
      z-index: 1;
    }

    .nav-text {
      position: relative;
      z-index: 1;
    }

    .nav-logout {
      margin-top: 2rem;
      border-top: 2px solid rgba(255, 255, 255, 0.2);
      padding-top: 1rem;
    }

    .main-content {
      margin-left: 280px;
      flex: 1;
      padding: 2rem;
      width: calc(100% - 280px);
      display: flex;
      flex-direction: column;
    }

    .map-container {
      max-width: 1600px;
      margin: 0 auto;
      width: 100%;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .mobile-toggle {
      display: none;
      position: fixed;
      top: 1rem;
      left: 1rem;
      z-index: 1001;
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      border: none;
      width: 50px;
      height: 50px;
      border-radius: 12px;
      font-size: 1.5rem;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.4);
      transition: all 0.3s ease;
    }

    .mobile-toggle:hover {
      transform: scale(1.05);
    }

    .sidebar-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(5px);
      z-index: 999;
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .sidebar-overlay.active {
        display: block;
      }

      .main-content {
        margin-left: 0;
        width: 100%;
        padding: 1rem;
        padding-top: 5rem;
      }

      .mobile-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
      }
    }

    .page-header {
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      padding: 2rem;
      border-radius: 20px;
      margin-bottom: 2rem;
      box-shadow: 0 10px 30px rgba(220, 20, 60, 0.3);
      position: relative;
      overflow: hidden;
    }

    .page-header::before {
      content: "🗺️";
      position: absolute;
      font-size: 15rem;
      opacity: 0.05;
      right: -50px;
      top: -50px;
      transform: rotate(15deg);
    }

    .header-content {
      position: relative;
      z-index: 1;
    }

    .page-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .page-subtitle {
      opacity: 0.9;
      font-size: 1.1rem;
    }

    .map-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: white;
      padding: 1.5rem;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-left: 5px solid #dc143c;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(220, 20, 60, 0.2);
    }

    .stat-icon {
      font-size: 2.5rem;
    }

    .stat-content {
      flex: 1;
    }

    .stat-label {
      font-size: 0.85rem;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 600;
      margin-bottom: 0.25rem;
    }

    .stat-value {
      font-size: 1.8rem;
      font-weight: 700;
      color: #dc143c;
    }

    .map-wrapper {
      background: white;
      border-radius: 20px;
      padding: 1.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border: 3px solid #dc143c;
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 600px;
    }

    .map-controls {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }

    .control-btn {
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.95rem;
    }

    .control-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(220, 20, 60, 0.4);
    }

    .control-btn.secondary {
      background: linear-gradient(135deg, #64748b 0%, #475569 100%);
      box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
    }

    .control-btn.secondary:hover {
      box-shadow: 0 6px 16px rgba(100, 116, 139, 0.4);
    }

    #map {
      flex: 1;
      border-radius: 15px;
      box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.1);
      min-height: 500px;
    }

    .leaflet-popup-content-wrapper {
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .leaflet-popup-content {
      margin: 1rem;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    }

    .popup-header {
      font-size: 1.1rem;
      font-weight: 700;
      color: #dc143c;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .popup-info {
      font-size: 0.9rem;
      color: #64748b;
      line-height: 1.6;
    }

    .popup-info strong {
      color: #2d3748;
    }

    .legend {
      background: white;
      padding: 1rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-left: 4px solid #dc143c;
      margin-top: 1rem;
    }

    .legend-title {
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 0.75rem;
      font-size: 1rem;
    }

    .legend-item {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
      color: #64748b;
    }

    .legend-icon {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
    }

    .legend-icon.active {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
    }

    .legend-icon.deployed {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: white;
    }

    .legend-icon.available {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      color: white;
    }

    @media (max-width: 768px) {
      .page-title {
        font-size: 1.8rem;
      }

      .map-stats {
        grid-template-columns: 1fr;
      }

      .map-controls {
        flex-direction: column;
      }

      .control-btn {
        justify-content: center;
      }

      #map {
        min-height: 400px;
      }
    }
  </style>
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
      <a href="myReports.php" class="nav-item">
        <span class="nav-icon">📂</span>
        <span class="nav-text">My Reports</span>
      </a>
      <a href="profileMap.php" class="nav-item active">
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
    <div class="map-container">
      <!-- Page Header -->
      <div class="page-header">
        <div class="header-content">
          <div class="page-title">
            <span>🗺️</span>
            <span>Volunteers Map</span>
          </div>
          <div class="page-subtitle">
            Real-time location tracking of Red Cross volunteers
          </div>
        </div>
      </div>

      <!-- Statistics -->
      <div class="map-stats">
        <div class="stat-card">
          <div class="stat-icon">�</div>
          <div class="stat-content">
            <div class="stat-label">Total Deployments</div>
            <div class="stat-value" id="totalDeployments">0</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">🚑</div>
          <div class="stat-content">
            <div class="stat-label">Current Deployment</div>
            <div class="stat-value" id="currentDeployment">0</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">✅</div>
          <div class="stat-content">
            <div class="stat-label">Completed</div>
            <div class="stat-value" id="completedDeployments">0</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">�</div>
          <div class="stat-content">
            <div class="stat-label">Status</div>
            <div class="stat-value" id="userStatus" style="font-size: 1.2rem; text-transform: capitalize;">Loading...</div>
          </div>
        </div>
      </div>

      <!-- Map Wrapper -->
      <div class="map-wrapper">
        <div class="map-controls">
          <button class="control-btn" id="btnAllDeployments" onclick="showAllDeployments()">
            <span>�</span>
            <span>All Deployments</span>
          </button>
          <button class="control-btn secondary" id="btnCurrentDeployment" onclick="showCurrentDeployment()">
            <span>🚑</span>
            <span>Current Deployment</span>
          </button>
          <button class="control-btn secondary" id="btnCompletedDeployments" onclick="showCompletedDeployments()">
            <span>✅</span>
            <span>Completed Deployments</span>
          </button>
          <button class="control-btn secondary" onclick="centerMap()">
            <span>🎯</span>
            <span>Center Map</span>
          </button>
        </div>

        <div id="map"></div>

        <div class="legend">
          <div class="legend-title">🏷️ Deployment Status Legend</div>
          <div class="legend-item">
            <div class="legend-icon deployed">🚑</div>
            <span>Current Deployment - Currently deployed</span>
          </div>
          <div class="legend-item">
            <div class="legend-icon active">✅</div>
            <span>Completed - Past deployments</span>
          </div>
          <div class="legend-item">
            <div class="legend-icon available">�</div>
            <span>All Deployments - Complete history</span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    let map;
    let markers = [];
    let allDeploymentsData = [];
    let currentDeploymentData = null;
    let completedDeploymentsData = [];
    let currentFilter = 'all';

    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.querySelector('.sidebar-overlay');
      sidebar.classList.toggle('active');
      overlay.classList.toggle('active');
    }

    function handleLogout() {
      if (confirm('Are you sure you want to logout?')) {
        console.log('Logging out...');
        window.location.href = '../index.php';
      }
    }

    function initMap() {
      // Initialize map centered on Catanduanes, Philippines
      map = L.map('map').setView([13.699929, 124.243526], 10);

      // Add OpenStreetMap tiles
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
      }).addTo(map);

      // Load deployment data from database
      loadDeploymentData();
    }

    async function loadDeploymentData() {
      try {
        const response = await fetch('../utility/getVolunteerMapData.php');
        const data = await response.json();
        
        if (!data.success) {
          throw new Error(data.error || 'Failed to load data');
        }

        allDeploymentsData = data.allDeployments || [];
        currentDeploymentData = data.currentDeployment;
        completedDeploymentsData = data.completedDeployments || [];

        // Update statistics
        document.getElementById('totalDeployments').textContent = data.statistics.total || 0;
        document.getElementById('currentDeployment').textContent = data.statistics.current || 0;
        document.getElementById('completedDeployments').textContent = data.statistics.completed || 0;
        document.getElementById('userStatus').textContent = data.statistics.userStatus || 'N/A';

        // Show all deployments by default
        showAllDeployments();

        // Center map on first deployment if exists
        if (allDeploymentsData.length > 0) {
          const firstDeployment = allDeploymentsData[0];
          map.setView([firstDeployment.lat, firstDeployment.lng], 12);
        }

      } catch (error) {
        console.error('Error loading deployment data:', error);
        alert('Failed to load deployment data. Please refresh the page.');
      }
    }

    function addDeploymentMarkers(deployments, markerType = 'all') {
      // Clear existing markers
      markers.forEach(marker => map.removeLayer(marker));
      markers = [];

      if (deployments.length === 0) {
        alert('No deployments found for this filter.');
        return;
      }

      deployments.forEach(deployment => {
        // Create custom icon based on marker type
        const iconHtml = getDeploymentIcon(markerType);
        const customIcon = L.divIcon({
          className: 'custom-marker',
          html: iconHtml,
          iconSize: [40, 40],
          iconAnchor: [20, 40],
          popupAnchor: [0, -40]
        });

        // Format dates
        const eventDate = new Date(deployment.event_date).toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'short',
          day: 'numeric'
        });
        
        const deploymentDate = new Date(deployment.deployment_date).toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'short',
          day: 'numeric'
        });

        // Create marker
        const marker = L.marker([deployment.lat, deployment.lng], { icon: customIcon })
          .bindPopup(`
            <div class="popup-header">
              ${getDeploymentEmoji(markerType)} ${deployment.eventName}
            </div>
            <div class="popup-info">
              <strong>Location:</strong> ${deployment.location}<br>
              <strong>Event Date:</strong> ${eventDate}<br>
              <strong>Deployed On:</strong> ${deploymentDate}<br>
              <strong>Contact:</strong> ${deployment.mobile}<br>
              <strong>📍 Latitude:</strong> ${deployment.lat}<br>
              <strong>📍 Longitude:</strong> ${deployment.lng}
            </div>
          `)
          .addTo(map);

        markers.push(marker);
      });
    }

    function getDeploymentIcon(type) {
      const colors = {
        current: 'background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);',
        completed: 'background: linear-gradient(135deg, #10b981 0%, #059669 100%);',
        all: 'background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);'
      };

      const emoji = getDeploymentEmoji(type);
      
      return `
        <div style="${colors[type]} width: 40px; height: 40px; border-radius: 50%; 
                     display: flex; align-items: center; justify-content: center; 
                     font-size: 20px; border: 3px solid white; 
                     box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                     animation: pulse 2s infinite;">
          ${emoji}
        </div>
        <style>
          @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
          }
        </style>
      `;
    }

    function getDeploymentEmoji(type) {
      const emojis = {
        current: '🚑',
        completed: '✅',
        all: '�'
      };
      return emojis[type] || '📍';
    }

    function updateButtonStates(activeButton) {
      // Reset all buttons
      document.querySelectorAll('.control-btn').forEach(btn => {
        if (btn.id && btn.id.startsWith('btn')) {
          btn.classList.add('secondary');
          btn.classList.remove('active');
        }
      });

      // Set active button
      if (activeButton) {
        activeButton.classList.remove('secondary');
      }
    }

    function showAllDeployments() {
      currentFilter = 'all';
      updateButtonStates(document.getElementById('btnAllDeployments'));
      addDeploymentMarkers(allDeploymentsData, 'all');
      
      // Center map on first deployment if exists
      if (allDeploymentsData.length > 0) {
        const firstDeployment = allDeploymentsData[0];
        map.setView([firstDeployment.lat, firstDeployment.lng], 12);
      }
    }

    function showCurrentDeployment() {
      currentFilter = 'current';
      updateButtonStates(document.getElementById('btnCurrentDeployment'));
      
      if (currentDeploymentData) {
        addDeploymentMarkers([currentDeploymentData], 'current');
        map.setView([currentDeploymentData.lat, currentDeploymentData.lng], 13);
      } else {
        markers.forEach(marker => map.removeLayer(marker));
        markers = [];
        alert('No current deployment found.');
      }
    }

    function showCompletedDeployments() {
      currentFilter = 'completed';
      updateButtonStates(document.getElementById('btnCompletedDeployments'));
      addDeploymentMarkers(completedDeploymentsData, 'completed');
      
      // Center map on first completed deployment if exists
      if (completedDeploymentsData.length > 0) {
        const firstDeployment = completedDeploymentsData[0];
        map.setView([firstDeployment.lat, firstDeployment.lng], 12);
      }
    }

    function centerMap() {
      // Center based on current filter
      if (currentFilter === 'current' && currentDeploymentData) {
        map.setView([currentDeploymentData.lat, currentDeploymentData.lng], 13);
      } else if (currentFilter === 'completed' && completedDeploymentsData.length > 0) {
        map.setView([completedDeploymentsData[0].lat, completedDeploymentsData[0].lng], 12);
      } else if (allDeploymentsData.length > 0) {
        map.setView([allDeploymentsData[0].lat, allDeploymentsData[0].lng], 12);
      } else {
        // Default to Catanduanes center
        map.setView([13.699929, 124.243526], 10);
      }
    }

    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', function() {
      initMap();
    });
  </script>
</body>
</html>