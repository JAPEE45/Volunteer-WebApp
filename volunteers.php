<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Management</title>
    <link rel="stylesheet" href="style/volunteers-modern.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

 <div class="sidebar" id="sidebar">
    <div class="logo">
      <img src="img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
    </div>
    <a href="homePage.php"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a>
    <a href="volunteers.php" class="active"><i class="fas fa-users fa-fw"></i> Volunteers</a>
    <a href="events.php"><i class="fas fa-calendar-alt fa-fw"></i> Events</a>
    <a href="map.php"><i class="fas fa-map-marker-alt fa-fw"></i> Map</a>
    <a href="index.php"><i class="fas fa-sign-out-alt fa-fw"></i> Logout</a>
  </div>

  <div class="content" id="content">
    <button class="menu-toggle" id="menu-toggle">☰</button>
    <div class="table-header">
      <h1>Volunteer Management</h1>
      <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search volunteer...">
      </div>
    </div>
    
    <div class="table-container">
        <table id="volunteerTable">
            <thead>
                <tr>
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

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="script/volunteers.js"></script>
<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('content').classList.toggle('active');
    });
</script>
</body>
</html>