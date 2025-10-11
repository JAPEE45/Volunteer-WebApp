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
    }

    .report-container {
      max-width: 1200px;
      margin: 0 auto;
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
        <form id="reportForm" class="upload-form" onsubmit="handleSubmit(event)">
          <label for="reportFile" class="upload-box" id="uploadBox">
            <div class="upload-icon">📄</div>
            <div class="upload-text">Choose Reports to Upload</div>
            <div class="upload-hint">Supports DOC, DOCX, and PDF files (Multiple files allowed)</div>
            <input type="file" id="reportFile" class="file-input" accept=".doc,.docx,.pdf" multiple>
          </label>
          
          <div id="selectedFiles" class="selected-files"></div>
          
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
              <span id="totalReports">8</span>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-label">This Month</div>
            <div class="stat-value">
              <span>📅</span>
              <span id="monthReports">3</span>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Pending Review</div>
            <div class="stat-value">
              <span>⏳</span>
              <span id="pendingReports">2</span>
            </div>
          </div>
        </div>

        <ul class="report-list" id="reportList">
          <!-- Sample Report Items -->
          <li class="report-item">
            <div class="report-info">
              <div class="report-name">
                <span>📄</span>
                <span>Flood Relief Operation Report - October 2025</span>
              </div>
              <div class="report-meta">
                <span><strong>📅</strong> Oct 12, 2025</span>
                <span><strong>📏</strong> 2.4 MB</span>
                <span class="status-badge submitted">✓ Submitted</span>
              </div>
            </div>
            <div class="report-actions">
              <button class="action-btn" onclick="downloadReport('flood-relief-oct-2025.pdf')">
                <span>⬇️</span>
                <span>Download</span>
              </button>
              <button class="action-btn delete" onclick="deleteReport(this)">
                <span>🗑️</span>
                <span>Delete</span>
              </button>
            </div>
          </li>

          <li class="report-item">
            <div class="report-info">
              <div class="report-name">
                <span>📄</span>
                <span>Medical Mission Report - September 2025</span>
              </div>
              <div class="report-meta">
                <span><strong>📅</strong> Sep 20, 2025</span>
                <span><strong>📏</strong> 1.8 MB</span>
                <span class="status-badge submitted">✓ Submitted</span>
              </div>
            </div>
            <div class="report-actions">
              <button class="action-btn" onclick="downloadReport('medical-mission-sep-2025.pdf')">
                <span>⬇️</span>
                <span>Download</span>
              </button>
              <button class="action-btn delete" onclick="deleteReport(this)">
                <span>🗑️</span>
                <span>Delete</span>
              </button>
            </div>
          </li>

          <li class="report-item">
            <div class="report-info">
              <div class="report-name">
                <span>📄</span>
                <span>Earthquake Response Report - August 2025</span>
              </div>
              <div class="report-meta">
                <span><strong>📅</strong> Aug 28, 2025</span>
                <span><strong>📏</strong> 3.2 MB</span>
                <span class="status-badge pending">⏳ Pending Review</span>
              </div>
            </div>
            <div class="report-actions">
              <button class="action-btn" onclick="downloadReport('earthquake-aug-2025.pdf')">
                <span>⬇️</span>
                <span>Download</span>
              </button>
              <button class="action-btn delete" onclick="deleteReport(this)">
                <span>🗑️</span>
                <span>Delete</span>
              </button>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </main>

  <script>
    let selectedFiles = [];

    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.querySelector('.sidebar-overlay');
      sidebar.classList.toggle('active');
      overlay.classList.toggle('active');
    }

    function handleLogout() {
      if (confirm('Are you sure you want to logout?')) {
        console.log('Logging out...');
        window.location.href = 'logout.php';
      }
    }

    // File selection handler
    document.getElementById('reportFile').addEventListener('change', function(e) {
      const files = Array.from(e.target.files);
      selectedFiles = [...selectedFiles, ...files];
      updateSelectedFiles();
      document.getElementById('uploadBtn').disabled = selectedFiles.length === 0;
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
    }

    function handleSubmit(e) {
      e.preventDefault();
      
      if (selectedFiles.length === 0) {
        alert('Please select at least one file to upload.');
        return;
      }

      // Simulate upload
      const uploadBtn = document.getElementById('uploadBtn');
      uploadBtn.disabled = true;
      uploadBtn.innerHTML = '<span>⏳</span><span>Uploading...</span>';

      setTimeout(() => {
        alert(`Successfully uploaded ${selectedFiles.length} report(s)!`);
        
        // Add uploaded files to the report list
        selectedFiles.forEach(file => {
          addReportToList(file);
        });

        // Reset form
        selectedFiles = [];
        updateSelectedFiles();
        document.getElementById('reportFile').value = '';
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<span>📤</span><span>Upload Reports</span>';

        // Update stats
        updateStats();
      }, 2000);
    }

    function addReportToList(file) {
      const reportList = document.getElementById('reportList');
      const li = document.createElement('li');
      li.className = 'report-item';
      
      const date = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
      const size = (file.size / (1024 * 1024)).toFixed(1);
      
      li.innerHTML = `
        <div class="report-info">
          <div class="report-name">
            <span>📄</span>
            <span>${file.name}</span>
          </div>
          <div class="report-meta">
            <span><strong>📅</strong> ${date}</span>
            <span><strong>📏</strong> ${size} MB</span>
            <span class="status-badge pending">⏳ Pending Review</span>
          </div>
        </div>
        <div class="report-actions">
          <button class="action-btn" onclick="downloadReport('${file.name}')">
            <span>⬇️</span>
            <span>Download</span>
          </button>
          <button class="action-btn delete" onclick="deleteReport(this)">
            <span>🗑️</span>
            <span>Delete</span>
          </button>
        </div>
      `;
      
      reportList.appendChild(li);
    }

    function updateStats() {
      const totalReports = document.getElementById('totalReports');
      const monthReports = document.getElementById('monthReports');
      const pendingReports = document.getElementById('pendingReports'); 
      
      totalReports.textContent = parseInt(totalReports.textContent) + selectedFiles.length;
      monthReports.textContent = parseInt(monthReports.textContent) + selectedFiles.length;
      pendingReports.textContent = parseInt(pendingReports.textContent) + selectedFiles.length;
    }

    function downloadReport(filename) {
      alert(`Downloading ${filename}...`);
    }

    function deleteReport(element) {
      if (confirm('Are you sure you want to delete this report?')) {
        const reportItem = element.closest('.report-item');
        reportItem.remove();
      }
    }
  </script>
</