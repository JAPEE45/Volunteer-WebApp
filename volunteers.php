<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/volunteers.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
  <style>
    /* Enhanced Global Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
      background: linear-gradient(135deg, #fff5f5 0%, #fee 100%);
      min-height: 100vh;
    }

    /* Enhanced Sidebar */
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

    .sidebar .logo {
      text-align: center;
      padding: 0 1.5rem 2rem;
      border-bottom: 2px solid rgba(255, 255, 255, 0.2);
      margin-bottom: 2rem;
    }

    .sidebar .logo img {
      max-width: 120px;
      height: auto;
      border-radius: 50%;
      border: 3px solid white;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .sidebar a {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem 1.5rem;
      margin: 0.5rem 1rem;
      border-radius: 12px;
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .sidebar a::before {
      content: "";
      position: absolute;
      left: 0;
      top: 0;
      width: 0;
      height: 100%;
      background: rgba(255, 255, 255, 0.15);
      transition: width 0.3s ease;
    }

    .sidebar a:hover::before {
      width: 100%;
    }

    .sidebar a:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateX(5px);
    }

    .sidebar a i {
      font-size: 1.3rem;
      min-width: 25px;
      position: relative;
      z-index: 1;
    }

    /* Menu Toggle Button */
    .menu-toggle {
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

    .menu-toggle:hover {
      transform: scale(1.05);
    }

    /* Enhanced Content Area */
    .content {
      margin-left: 280px;
      padding: 2rem;
      transition: margin-left 0.3s ease;
    }

    /* Enhanced Table Header */
    .table-header {
      background: white;
      padding: 2rem;
      border-radius: 20px;
      margin-bottom: 2rem;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1.5rem;
      border-left: 5px solid #dc143c;
    }

    .table-header h1 {
      font-size: 2rem;
      background: linear-gradient(135deg, #dc143c 0%, #c41e3a 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .table-header h1::before {
      content: "👥";
      font-size: 2.5rem;
      -webkit-text-fill-color: initial;
    }

    /* Enhanced Search Container */
    .search-container {
      position: relative;
    }

    .search-container::before {
      content: "🔍";
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1.2rem;
      pointer-events: none;
    }

    #searchInput {
      padding: 0.875rem 1rem 0.875rem 3rem;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 1rem;
      width: 300px;
      transition: all 0.3s ease;
      background: white;
      font-family: inherit;
    }

    #searchInput:focus {
      outline: none;
      border-color: #dc143c;
      box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.1);
    }

    #statusFilter {
      padding: 0.875rem 1.5rem;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      background: white;
      font-family: inherit;
    }

    #statusFilter:focus {
      outline: none;
      border-color: #dc143c;
      box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.1);
    }

    #statusFilter:hover {
      border-color: #dc143c;
    }

    /* Enhanced Table */
    #volunteerTable {
      width: 100%;
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.1);
      border-collapse: separate;
      border-spacing: 0;
    }

    #volunteerTable thead {
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
    }

    #volunteerTable thead th {
      padding: 1.5rem;
      text-align: left;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 0.9rem;
      border: none;
    }

    #volunteerTable tbody tr {
      border-bottom: 1px solid #fee;
      transition: all 0.3s ease;
    }

    #volunteerTable tbody tr:hover {
      background: linear-gradient(135deg, #fff5f5 0%, #fee 100%);
      transform: scale(1.01);
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.1);
    }

    #volunteerTable tbody td {
      padding: 1.25rem 1.5rem;
      color: #2d3748;
      font-weight: 500;
      border: none;
    }

    .volunteer-name {
      font-weight: 700;
      color: #dc143c;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .volunteer-name:hover {
      text-decoration: underline;
      color: #a00000;
    }

    /* Status Dots */
    .status-dot {
      display: inline-block;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      margin-right: 0.5rem;
      animation: pulse 2s infinite;
    }

    .status-dot.deployed {
      background: #10b981;
      box-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
    }

    .status-dot.not-deployed {
      background: #64748b;
      box-shadow: 0 0 8px rgba(100, 116, 139, 0.3);
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.2); opacity: 0.8; }
    }

    /* Enhanced Buttons */
    .accept-btn {
      padding: 0.75rem 1.5rem;
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-size: 0.9rem;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
    }

    .accept-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(220, 20, 60, 0.4);
    }

    .delete-btn {
      padding: 0.75rem 1rem;
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 1.2rem;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .delete-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }

    /* Enhanced Modal Styles */
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(5px);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .modal-content {
      background: white;
      padding: 2.5rem;
      border-radius: 20px;
      width: 90%;
      max-width: 700px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      animation: slideUp 0.4s ease;
      position: relative;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(50px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .modal-content::-webkit-scrollbar {
      width: 10px;
    }

    .modal-content::-webkit-scrollbar-track {
      background: #fee;
      border-radius: 10px;
    }

    .modal-content::-webkit-scrollbar-thumb {
      background: linear-gradient(180deg, #dc143c 0%, #a00000 100%);
      border-radius: 10px;
    }

    .modal-content h2, .modal-content h3 {
      background: linear-gradient(135deg, #dc143c 0%, #c41e3a 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 3px solid #dc143c;
    }

    .details-container {
      margin: 1.5rem 0;
    }

    .details-container p {
      margin: 1rem 0;
      padding: 1rem;
      background: linear-gradient(135deg, #fff 0%, #fff5f5 100%);
      border-left: 4px solid #dc143c;
      border-radius: 8px;
      font-size: 1rem;
    }

    .details-container strong {
      color: #dc143c;
      font-weight: 700;
      margin-right: 0.5rem;
    }

    .modal-actions {
      margin-top: 2rem;
      display: flex;
      justify-content: flex-end;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .confirm-btn {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
      padding: 0.875rem 2rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .confirm-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }

    .reject-btn {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      padding: 0.875rem 2rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .reject-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }

    .close-btn {
      background: linear-gradient(135deg, #64748b 0%, #475569 100%);
      color: white;
      padding: 0.875rem 2rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
    }

    .close-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(100, 116, 139, 0.4);
    }

    /* Info Modal */
    .info-modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(5px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .info-modal-content {
      background: white;
      padding: 2.5rem;
      border-radius: 20px;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      animation: slideUp 0.4s ease;
    }

    .info-modal-content h3 {
      background: linear-gradient(135deg, #dc143c 0%, #c41e3a 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-size: 1.6rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
    }

    .info-modal-content p {
      margin: 1rem 0;
      font-size: 1rem;
    }

    .info-modal-content strong {
      color: #dc143c;
      font-weight: 700;
    }

    .info-modal-content select {
      width: 100%;
      padding: 1rem;
      margin: 1.5rem 0;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 1rem;
      font-family: inherit;
      transition: all 0.3s ease;
    }

    .info-modal-content select:focus {
      outline: none;
      border-color: #dc143c;
      box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.1);
    }

    .info-modal-buttons {
      display: flex;
      gap: 1rem;
      margin-top: 1.5rem;
      justify-content: flex-end;
    }

    .submit-btn {
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      padding: 0.875rem 2rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(220, 20, 60, 0.4);
    }

    /* Success Modal */
    .success-modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(5px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .success-modal-content {
      background: white;
      padding: 2.5rem;
      border-radius: 20px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      animation: slideUp 0.4s ease;
    }

    .success-modal-content h3 {
      color: #10b981;
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .success-modal-content p {
      font-size: 1.1rem;
      color: #2d3748;
      margin-bottom: 1.5rem;
    }

    .success-modal-content button {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
      padding: 0.875rem 2rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .success-modal-content button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .menu-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .content {
        margin-left: 0;
        padding: 1rem;
        padding-top: 5rem;
      }

      .table-header {
        flex-direction: column;
        align-items: stretch;
      }

      .table-header h1 {
        font-size: 1.5rem;
      }

      #searchInput {
        width: 100%;
      }

      #volunteerTable {
        font-size: 0.9rem;
      }

      #volunteerTable thead th,
      #volunteerTable tbody td {
        padding: 1rem;
      }

      .modal-content,
      .info-modal-content,
      .success-modal-content {
        width: 95%;
        padding: 1.5rem;
      }

      .modal-actions,
      .info-modal-buttons {
        flex-direction: column;
      }

      .modal-actions button,
      .info-modal-buttons button {
        width: 100%;
      }
    }
  </style>
</head>
<body>
   <div class="sidebar" id="sidebar">
    <div class="logo">
      <img src="img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
    </div>
    <a href="homePage.php"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a>
    <a href="volunteers.php"><i class="fas fa-users fa-fw"></i> Volunteers</a>
    <a href="accounts.php"><i class="fas fa-users fa-fw"></i>Accounts</a>
    <a href="events.php"><i class="fas fa-calendar-alt fa-fw"></i> Events</a>
    <a href="map.php"><i class="fas fa-map-marker-alt fa-fw"></i> Map</a>
    <a href="login.php"><i class="fas fa-sign-out-alt fa-fw"></i>Logout</a>
  </div>
  <button class="menu-toggle" id="menu-toggle">☰</button>

  <div class="content">
    <div class="table-header">
      <h1>REGISTERED VOLUNTEER</h1>
      <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;">
        <div class="search-container">
          <input type="text" id="searchInput" placeholder="Search volunteer...">
        </div>
        <div>
          <label for="statusFilter" style="font-weight:600;margin-right:6px;color:#2d3748;">Filter:</label>
          <select id="statusFilter">
            <option value="all">All</option>
            <option value="accepted">Accepted</option>
            <option value="pending">Pending</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
        <button id="deploySelectedBtn" onclick="openBatchDeployModal()" style="display:none; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #dc143c 0%, #a00000 100%); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.9rem; box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);">
          <span>🚀 Deploy Selected (<span id="selectedCount">0</span>)</span>
        </button>
      </div>
    </div>
    <table id="volunteerTable" border="1">
      <thead>
        <tr>
          <th style="width:50px;text-align:center;"><input type="checkbox" id="selectAllVolunteers" title="Select All" onclick="toggleSelectAll(this)"></th>
          <th>Name</th>
          <th>Deployment Location</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        
      </tbody>
    </table>
  </div>

  <div class="info-modal" id="infoModal">
    <div class="info-modal-content">
      <h3>Volunteer Information</h3>
      <p><strong>Full Name:</strong> <span id="infoName"></span></p>
      <p><strong>Address:</strong> <span id="infoAddress"></span></p>
      <p><strong>Age:</strong> <span id="infoAge"></span></p>
      <p><strong>Sex:</strong> <span id="infoSex"></span></p>

        <select id="deployLocation">
          <option value="">-- Select deployment location --</option>
          <option value="Virac">Virac</option>
          <option value="San Andres">San Andres</option>
          <option value="Pandan">Pandan</option>
          <option value="San Miguel">San Miguel</option>
          <option value="Bato">Bato</option>
          <option value="Panganiban">Panganiban</option>
          <option value="Gigmoto">Gigmoto</option>
          <option value="Viga">Viga</option>
        </select>

      <div class="info-modal-buttons">
        <button class="submit-btn" onclick="submitDeployment()">Submit</button>
        <button class="close-btn" onclick="closeInfoModal()">Close</button>
      </div>
    </div>
  </div>

  <div class="success-modal" id="successModal">
    <div class="success-modal-content">
      <h3>✅ Deployment Successful</h3>
      <p id="successMessage"></p>
      <button onclick="closeSuccessModal()">OK</button>
    </div>
  </div>

<!-- VOLUNTEER DETAILS MODAL -->
<div id="userModal" class="modal" style="display:none;">
  <div class="modal-content">
    <h2 id="modalTitle">Volunteer Details</h2>
    <div id="userDetails" class="details-container"></div>

    <div class="modal-actions">
      <button id="confirmBtn" class="confirm-btn">Confirm</button>
      <button id="rejectBtn" class="reject-btn">Reject</button>
      <button onclick="closeUserModal()" class="close-btn">Close</button>
    </div>
  </div>
</div>

<script defer>
  // Toggle sidebar for mobile
  document.getElementById('menu-toggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('active');
  });

  // Close sidebar when clicking outside on mobile
  document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('menu-toggle');
    
    if (window.innerWidth <= 768 && 
        !sidebar.contains(event.target) && 
        !toggle.contains(event.target) && 
        sidebar.classList.contains('active')) {
      sidebar.classList.remove('active');
    }
  });
</script>

</body>
 <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
   <script defer src="script/volunteers.js"></script>
</html>