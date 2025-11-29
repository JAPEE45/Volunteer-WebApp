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
  <title>Volunteer Report Upload - Philippine Red Cross</title>
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

    .main-content {
      margin-left: 280px;
      flex: 1;
      padding: 2rem;
      width: calc(100% - 280px);
      min-height: 100vh;
    }

    .report-container {
      max-width: 1200px;
      margin: 0 auto;
      position: relative;
      z-index: 1;
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
        padding-top: 6rem;
      }

      .mobile-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .report-container {
        padding-top: 0;
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
      z-index: 1;
    }

    .page-header::before {
      content: "📝";
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

    .upload-section {
      background: white;
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border: 3px solid #dc143c;
      position: relative;
      z-index: 1;
    }

    .section-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .section-title::before {
      content: "";
      width: 4px;
      height: 30px;
      background: linear-gradient(180deg, #dc143c 0%, #ff4757 100%);
      border-radius: 10px;
    }

    .upload-form {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .upload-box {
      border: 3px dashed #dc143c;
      border-radius: 15px;
      padding: 3rem;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      background: linear-gradient(135deg, #fff5f5 0%, #fee 100%);
      position: relative;
      overflow: hidden;
    }

    .upload-box:hover {
      border-color: #a00000;
      background: linear-gradient(135deg, #fee 0%, #fdd 100%);
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(220, 20, 60, 0.2);
    }

    .upload-box::before {
      content: "📤";
      position: absolute;
      font-size: 8rem;
      opacity: 0.05;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .upload-icon {
      font-size: 4rem;
      margin-bottom: 1rem;
      position: relative;
      z-index: 1;
    }

    .upload-text {
      font-size: 1.3rem;
      font-weight: 600;
      color: #2d3748;
      margin-bottom: 0.5rem;
      position: relative;
      z-index: 1;
    }

    .upload-hint {
      font-size: 0.95rem;
      color: #64748b;
      position: relative;
      z-index: 1;
    }

    .file-input {
      display: none;
    }

    .selected-files {
      margin-top: 1rem;
    }

    .file-chip {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      margin: 0.25rem;
      font-size: 0.9rem;
      font-weight: 600;
      animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .file-chip button {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      color: white;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.8rem;
      transition: all 0.2s ease;
    }

    .file-chip button:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: scale(1.1);
    }

    .upload-btn {
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      border: none;
      padding: 1rem 2rem;
      border-radius: 25px;
      font-size: 1.1rem;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
    }

    .upload-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(220, 20, 60, 0.4);
    }

    .upload-btn:disabled {
      background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
      cursor: not-allowed;
      transform: none;
    }

    .reports-section {
      background: white;
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .report-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: linear-gradient(135deg, #fff5f5 0%, #fee 100%);
      padding: 1.5rem;
      border-radius: 15px;
      border-left: 5px solid #dc143c;
      transition: all 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(220, 20, 60, 0.2);
    }

    .stat-label {
      font-size: 0.85rem;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: #dc143c;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .report-list {
      list-style: none;
    }

    .report-item {
      background: linear-gradient(135deg, #fff 0%, #fff5f5 100%);
      padding: 1.5rem;
      border-radius: 12px;
      margin-bottom: 1rem;
      border-left: 5px solid #dc143c;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .report-item:hover {
      transform: translateX(10px);
      box-shadow: 0 8px 20px rgba(220, 20, 60, 0.15);
    }

    .report-info {
      flex: 1;
      min-width: 250px;
    }

    .report-name {
      font-size: 1.1rem;
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .report-meta {
      display: flex;
      gap: 1.5rem;
      flex-wrap: wrap;
      font-size: 0.9rem;
      color: #64748b;
    }

    .report-meta span {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .report-actions {
      display: flex;
      gap: 0.5rem;
    }

    .action-btn {
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .action-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
    }

    .action-btn.delete {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .action-btn.delete:hover {
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      color: #94a3b8;
    }

    .empty-icon {
      font-size: 5rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }

    .empty-text {
      font-size: 1.2rem;
      font-weight: 600;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      gap: 0.5rem;
    }

    .status-badge.submitted {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
    }

    .status-badge.pending {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: white;
    }

    .status-badge.approved {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
    }

    .status-badge.rejected {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
    }

    .status-badge.reviewed {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      color: white;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      display: block;
      font-weight: 600;
      color: #2d3748;
      margin-bottom: 0.5rem;
      font-size: 0.95rem;
    }

    .form-label .required {
      color: #dc143c;
      margin-left: 2px;
    }

    .form-input,
    .form-select,
    .form-textarea {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 2px solid #e2e8f0;
      border-radius: 10px;
      font-size: 1rem;
      font-family: inherit;
      transition: all 0.3s ease;
      background: white;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
      outline: none;
      border-color: #dc143c;
      box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.1);
    }

    .form-textarea {
      resize: vertical;
      min-height: 100px;
    }

    .form-hint {
      font-size: 0.85rem;
      color: #64748b;
      margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
      .page-title {
        font-size: 1.8rem;
      }

      .upload-box {
        padding: 2rem 1rem;
      }

      .report-item {
        flex-direction: column;
        align-items: flex-start;
      }

      .report-actions {
        width: 100%;
        justify-content: flex-end;
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
      <a href="profileMap.php" class="nav-item">
        <span class="nav-icon">🗺️</span>
        <span class="nav-text">Map</span>
      </a>
      <a href="report.php" class="nav-item active">
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
    <div class="report-container">
      <!-- Page Header -->
      <div class="page-header">
        <div class="header-content">
          <div class="page-title">
            <span>📝</span>
            <span>Submit Report</span>
          </div>
          <div class="page-subtitle">
            Upload and manage your deployment reports
          </div>
        </div>
      </div>

      <!-- Upload Section -->
      <div class="upload-section">
        <h2 class="section-title">📤 Upload New Report</h2>
        <form id="reportForm" class="upload-form" onsubmit="handleSubmit(event)" enctype="multipart/form-data">
          
          <div class="form-group">
            <label for="reportTitle" class="form-label">
              Report Title <span class="required">*</span>
            </label>
            <input 
              type="text" 
              id="reportTitle" 
              name="report_title"
              class="form-input" 
              placeholder="e.g., Flood Relief Operation Report - October 2025"
              required
            >
            <div class="form-hint">Provide a clear and descriptive title for your report</div>
          </div>

          <div class="form-group">
            <label for="deploymentSelect" class="form-label">
              Related Deployment <span style="color: #64748b; font-weight: 400;">(Optional)</span>
            </label>
            <select id="deploymentSelect" name="deployment_id" class="form-select">
              <option value="">Select a deployment...</option>
            </select>
            <div class="form-hint">Link this report to a specific deployment event</div>
          </div>

          <div class="form-group">
            <label for="reportDescription" class="form-label">
              Report Description <span style="color: #64748b; font-weight: 400;">(Optional)</span>
            </label>
            <textarea 
              id="reportDescription" 
              name="report_description"
              class="form-textarea" 
              placeholder="Brief summary of the report contents, key findings, or important notes..."
            ></textarea>
            <div class="form-hint">Add any additional context or summary for this report</div>
          </div>

          <div class="form-group">
            <label class="form-label">
              Report Files <span class="required">*</span>
            </label>
            <label for="reportFile" class="upload-box" id="uploadBox">
              <div class="upload-icon">📄</div>
              <div class="upload-text">Choose Reports to Upload</div>
              <div class="upload-hint">Supports DOC, DOCX, and PDF files (Max 10MB per file, Multiple files allowed)</div>
              <input type="file" id="reportFile" name="reports[]" class="file-input" accept=".doc,.docx,.pdf" multiple>
            </label>
            <div id="selectedFiles" class="selected-files"></div>
            <div id="fileError" style="color: #dc143c; font-size: 0.9rem; margin-top: 0.5rem; display: none;">
              ⚠️ Please select at least one file to upload
            </div>
          </div>
          
          <button type="submit" class="upload-btn" id="uploadBtn" disabled>
            <span>📤</span>
            <span>Upload Reports</span>
          </button>
        </form>
      </div>

      <!-- Reports Section -->
      <div class="reports-section">
        <h2 class="section-title">📋 Submitted Reports</h2>
        
        <div class="report-stats">
          <div class="stat-card">
            <div class="stat-label">Total Reports</div>
            <div class="stat-value">
              <span>📊</span>
              <span id="totalReports">0</span>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-label">This Month</div>
            <div class="stat-value">
              <span>📅</span>
              <span id="monthReports">0</span>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Pending Review</div>
            <div class="stat-value">
              <span>⏳</span>
              <span id="pendingReports">0</span>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Approved</div>
            <div class="stat-value">
              <span>✅</span>
              <span id="approvedReports">0</span>
            </div>
          </div>
        </div>

        <ul class="report-list" id="reportList">
          <!-- Reports will be loaded dynamically -->
          <li style="text-align: center; padding: 2rem; color: #64748b;">
            Loading reports...
          </li>
        </ul>
      </div>
    </div>
  </main>

  <script>
    let selectedFiles = [];
    let userDeployments = [];

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

    // Load deployments for dropdown
    async function loadDeployments() {
      try {
        const response = await fetch('../utility/getUserDeployments.php');
        const data = await response.json();
        
        if (data.success && data.deployments) {
          userDeployments = data.deployments;
          const select = document.getElementById('deploymentSelect');
          
          data.deployments.forEach(deployment => {
            const option = document.createElement('option');
            option.value = deployment.deployment_id;
            option.setAttribute('data-event-id', deployment.event_id);
            option.textContent = `${deployment.eventName} - ${deployment.location} (${formatDate(deployment.event_date)})`;
            select.appendChild(option);
          });
        }
      } catch (error) {
        console.error('Error loading deployments:', error);
      }
    }

    // Load existing reports
    async function loadReports() {
      try {
        const response = await fetch('../utility/getReports.php');
        const data = await response.json();
        
        if (!data.success) {
          throw new Error(data.error || 'Failed to load reports');
        }

        // Update statistics
        document.getElementById('totalReports').textContent = data.statistics.total || 0;
        document.getElementById('monthReports').textContent = data.statistics.month || 0;
        document.getElementById('pendingReports').textContent = data.statistics.pending || 0;
        document.getElementById('approvedReports').textContent = data.statistics.approved || 0;

        // Display reports
        const reportList = document.getElementById('reportList');
        reportList.innerHTML = '';

        if (data.reports.length === 0) {
          reportList.innerHTML = `
            <div class="empty-state">
              <div class="empty-icon">📋</div>
              <div class="empty-text">No reports submitted yet</div>
            </div>
          `;
        } else {
          data.reports.forEach(report => {
            const li = createReportItem(report);
            reportList.appendChild(li);
          });
        }

      } catch (error) {
        console.error('Error loading reports:', error);
        document.getElementById('reportList').innerHTML = `
          <div style="text-align: center; padding: 2rem; color: #ef4444;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
            <div>Error loading reports. Please refresh the page.</div>
          </div>
        `;
      }
    }

    function createReportItem(report) {
      const li = document.createElement('li');
      li.className = 'report-item';
      li.setAttribute('data-report-id', report.id);
      
      const date = formatDate(report.submission_date);
      const size = formatFileSize(report.file_size);
      const statusBadge = getStatusBadge(report.status);
      
      const eventInfo = report.event_name ? `<br><strong>📍</strong> ${report.event_name} - ${report.event_location}` : '';
      const description = report.report_description ? `<br><strong>📝</strong> ${report.report_description}` : '';
      
      li.innerHTML = `
        <div class="report-info">
          <div class="report-name">
            <span>📄</span>
            <span>${report.report_title}</span>
          </div>
          <div class="report-meta">
            <span><strong>📅</strong> ${date}</span>
            <span><strong>📏</strong> ${size}</span>
            <span><strong>📎</strong> ${report.file_name}</span>
            ${statusBadge}
          </div>
          ${eventInfo ? `<div style="margin-top: 0.5rem; font-size: 0.9rem; color: #64748b;">${eventInfo}</div>` : ''}
          ${description ? `<div style="margin-top: 0.5rem; font-size: 0.9rem; color: #64748b;">${description}</div>` : ''}
          ${report.comments ? `<div style="margin-top: 0.5rem; font-size: 0.9rem; color: #dc143c;"><strong>Comments:</strong> ${report.comments}</div>` : ''}
        </div>
        <div class="report-actions">
          <button class="action-btn" onclick="downloadReport('${report.file_path}', '${report.file_name}')">
            <span>⬇️</span>
            <span>Download</span>
          </button>
          <button class="action-btn delete" onclick="deleteReport(${report.id})">
            <span>🗑️</span>
            <span>Delete</span>
          </button>
        </div>
      `;
      
      return li;
    }

    function getStatusBadge(status) {
      const badges = {
        'pending': '<span class="status-badge pending">⏳ Pending Review</span>',
        'reviewed': '<span class="status-badge reviewed">👁️ Reviewed</span>',
        'approved': '<span class="status-badge approved">✅ Approved</span>',
        'rejected': '<span class="status-badge rejected">❌ Rejected</span>'
      };
      return badges[status] || '<span class="status-badge pending">⏳ Pending</span>';
    }

    function formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }

    function formatFileSize(bytes) {
      if (!bytes) return 'Unknown';
      const mb = bytes / (1024 * 1024);
      return mb.toFixed(2) + ' MB';
    }

    // File selection handler
    document.getElementById('reportFile').addEventListener('change', function(e) {
      const files = Array.from(e.target.files);
      selectedFiles = [...selectedFiles, ...files];
      updateSelectedFiles();
      document.getElementById('uploadBtn').disabled = selectedFiles.length === 0;
      
      // Hide error message when files are selected
      if (selectedFiles.length > 0) {
        document.getElementById('fileError').style.display = 'none';
      }
    });

    // Drag and drop functionality
    const uploadBox = document.getElementById('uploadBox');
    
    uploadBox.addEventListener('dragover', function(e) {
      e.preventDefault();
      uploadBox.style.borderColor = '#a00000';
      uploadBox.style.background = 'linear-gradient(135deg, #fee 0%, #fdd 100%)';
    });

    uploadBox.addEventListener('dragleave', function(e) {
      e.preventDefault();
      uploadBox.style.borderColor = '#dc143c';
      uploadBox.style.background = 'linear-gradient(135deg, #fff5f5 0%, #fee 100%)';
    });

    uploadBox.addEventListener('drop', function(e) {
      e.preventDefault();
      uploadBox.style.borderColor = '#dc143c';
      uploadBox.style.background = 'linear-gradient(135deg, #fff5f5 0%, #fee 100%)';
      
      const files = Array.from(e.dataTransfer.files).filter(file => {
        return file.name.endsWith('.doc') || file.name.endsWith('.docx') || file.name.endsWith('.pdf');
      });
      
      selectedFiles = [...selectedFiles, ...files];
      updateSelectedFiles();
      document.getElementById('uploadBtn').disabled = selectedFiles.length === 0;
      
      // Hide error message when files are selected
      if (selectedFiles.length > 0) {
        document.getElementById('fileError').style.display = 'none';
      }
    });

    function updateSelectedFiles() {
      const container = document.getElementById('selectedFiles');
      container.innerHTML = '';
      
      selectedFiles.forEach((file, index) => {
        const chip = document.createElement('div');
        chip.className = 'file-chip';
        chip.innerHTML = `
          <span>📄 ${file.name}</span>
          <button type="button" onclick="removeFile(${index})">×</button>
        `;
        container.appendChild(chip);
      });
    }

    function removeFile(index) {
      selectedFiles.splice(index, 1);
      updateSelectedFiles();
      document.getElementById('uploadBtn').disabled = selectedFiles.length === 0;
      
      // Show error if no files remain (only if form was previously submitted)
      if (selectedFiles.length === 0) {
        document.getElementById('fileError').style.display = 'none';
      }
    }

    async function handleSubmit(e) {
      e.preventDefault();
      
      // Hide any previous error messages
      const fileError = document.getElementById('fileError');
      fileError.style.display = 'none';
      
      // Validate files
      if (selectedFiles.length === 0) {
        fileError.style.display = 'block';
        fileError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
      }

      const reportTitle = document.getElementById('reportTitle').value.trim();
      if (!reportTitle) {
        alert('Please provide a report title.');
        document.getElementById('reportTitle').focus();
        return;
      }

      const uploadBtn = document.getElementById('uploadBtn');
      uploadBtn.disabled = true;
      uploadBtn.innerHTML = '<span>⏳</span><span>Uploading...</span>';

      try {
        const formData = new FormData();
        
        // Add form fields
        formData.append('report_title', reportTitle);
        formData.append('report_description', document.getElementById('reportDescription').value);
        
        const deploymentSelect = document.getElementById('deploymentSelect');
        if (deploymentSelect.value) {
          formData.append('deployment_id', deploymentSelect.value);
          const selectedOption = deploymentSelect.options[deploymentSelect.selectedIndex];
          formData.append('event_id', selectedOption.getAttribute('data-event-id'));
        }
        
        // Add files
        selectedFiles.forEach(file => {
          formData.append('reports[]', file);
        });

        const response = await fetch('../utility/uploadReport.php', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        if (data.success) {
          alert(data.message);
          
          // Reset form
          document.getElementById('reportForm').reset();
          selectedFiles = [];
          updateSelectedFiles();
          
          // Reload reports
          await loadReports();
        } else {
          alert('Upload failed: ' + (data.error || 'Unknown error'));
          if (data.details && data.details.length > 0) {
            console.error('Upload errors:', data.details);
          }
        }

      } catch (error) {
        console.error('Error uploading report:', error);
        alert('An error occurred while uploading. Please try again.');
      } finally {
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<span>📤</span><span>Upload Reports</span>';
      }
    }

    function downloadReport(filepath, filename) {
      // Create a temporary link and trigger download
      const link = document.createElement('a');
      link.href = filepath;
      link.download = filename;
      link.target = '_blank';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }

    async function deleteReport(reportId) {
      if (!confirm('Are you sure you want to delete this report? This action cannot be undone.')) {
        return;
      }

      try {
        const response = await fetch('../utility/deleteReport.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ report_id: reportId })
        });

        const data = await response.json();

        if (data.success) {
          alert('Report deleted successfully');
          
          // Remove from DOM
          const reportItem = document.querySelector(`[data-report-id="${reportId}"]`);
          if (reportItem) {
            reportItem.remove();
          }
          
          // Reload reports to update statistics
          await loadReports();
        } else {
          alert('Failed to delete report: ' + (data.error || 'Unknown error'));
        }

      } catch (error) {
        console.error('Error deleting report:', error);
        alert('An error occurred while deleting. Please try again.');
      }
    }

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      loadDeployments();
      loadReports();
    });
  </script>
</