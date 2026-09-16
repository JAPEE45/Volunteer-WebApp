<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SMS Alerts</title>
  <link rel="stylesheet" href="style/sms.css">
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
    <a href="adminActivityReports.php"><i class="fas fa-file-alt fa-fw"></i> Activity Reports</a>
    <a href="map.php"><i class="fas fa-map-marker-alt fa-fw"></i> Map</a>
    <a href="sms.php"><i class="fas fa-envelope fa-fw"></i> SMS Alerts</a>

  </div>
  <button class="menu-toggle" id="menu-toggle">☰</button>

  <div class="content">
    <h1>SMS Alerts</h1>

    <table id="eventTable">
      <thead>
        <tr>
          <th>TYPE</th>
          <th>NAME</th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody>
        <!-- Dynamic rows load here -->
      </tbody>
    </table>
  </div>

  <div id="volunteerModal" class="modal">
    <div class="modal-content">
      <span class="close-btn" onclick="closeModal()">&times;</span>
      <h2>Volunteer Application</h2>
      <div id="volunteerDetails"></div>
      <div class="modal-actions">
        <button id="downloadDocBtn">Download Application (DOC)</button>
        <button id="confirmBtn">Confirm</button>
        <button id="rejectBtn">Reject</button>
      </div>
    </div>
  </div>

  <script src="script/sms.js"></script>
</body>
</html>
