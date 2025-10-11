<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Volunteer Reports</title>
  <link rel="stylesheet" href="style/profileSms.css">
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="logo">
      <img src="../img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
    </div>
    <a href="../profile/profile.php">Profile</a>
    <a href="../profile/profileMap.php">Map</a>
    <a href="#">Sms</a>
    <a href="../profile/report.php">Report</a>
  </div>

  <button class="menu-toggle" id="menu-toggle">☰</button>

  <div class="content">
    <h1>Volunteer Reports</h1>
    <table id="eventTable">
      <thead>
        <tr>
          <th>TYPE</th>
          <th>NAME</th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <!-- Modal for viewing details -->
  <div id="volunteerModal" class="modal">
    <div class="modal-content">
      <span class="close-btn" onclick="closeModal()">&times;</span>
      <h2>Report Details</h2>
      <div id="reportDetails"></div>
      <div class="modal-actions">
        <button id="downloadBtn">Download DOC</button>
      </div>
    </div>
  </div>

  <script src="../profile/js/profileSms.js"></script>
</body>
</html>
