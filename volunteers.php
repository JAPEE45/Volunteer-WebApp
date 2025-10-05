<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/volunteers.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>
 <div class="sidebar" id="sidebar">
    <div class="logo">
      <img src="img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
    </div>
    <a href="homePage.php"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a>
    <a href="volunteers.php"><i class="fas fa-users fa-fw"></i> Volunteers</a>
    <a href="events.php"><i class="fas fa-calendar-alt fa-fw"></i> Events</a>
    <a href="#"><i class="fas fa-map-marker-alt fa-fw"></i> Map</a>
    <a href="sms.php"><i class="fas fa-envelope fa-fw"></i> SMS Alerts</a>

  </div>
  <button class="menu-toggle" id="menu-toggle">☰</button>

  <div class="content">
    <div class="table-header">
      <h1>Volunteer Management</h1>
      <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search volunteer...">
      </div>
    </div>
    <!-- <button onclick="openModal()">ADD VOLUNTEER</button>
    <div id="map"></div> -->
    <table id="volunteerTable" border="1">
      <thead>
        <tr>
          <th>Name</th>
          <th>Deployment Location</th>
          <th>Status</th>
          <th></th>
        </tr>
        <tr>
        <td>Maria Isabel D. Rebanal</td>
        <td>Virac, Catanduanes, Philippines</td>
        <td></td>
        <td><button class="delete-btn" onclick="removeRow(this)">🗑</button></td>
      </tr>
      </thead>
      <tbody>
        
      </tbody>
    </table>
  </div>
<!-- 
   <div class="modal" id="volunteerModal">
    <div class="modal-content">
      <h3>Add Volunteer</h3>
      <input type="text" id="volName" placeholder="Enter name">
      <input type="text" id="volLocation" placeholder="Enter location">
      <div class="modal-buttons">
        <button onclick="saveVolunteer()">Save</button>
        <button onclick="closeModal()">Cancel</button>
      </div>
    </div>
  </div> -->

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


</body>
 <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
 <script src="script/volunteers.js"></script>
</html>