<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Volunteer Report Upload</title>
  <link rel="stylesheet" href="style/report.css">
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="logo">
      <img src="../img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
    </div>
    <a href="../profile/profile.html">Profile</a>
    <a href="../profile/profileMap.html">Map</a>
    <a href="../profile/profileSms.html">Sms</a>
    <a href="#" class="active">Report</a>
  </div>

  <button class="menu-toggle" id="menu-toggle">☰</button>

  <div class="content">
    <h1>Submit Report</h1>
    <form id="reportForm" class="upload-form">
      <label for="reportFile" class="upload-box">
        <span>📄 Choose Reports (Multiple allowed)</span>
        <input type="file" id="reportFile" accept=".doc,.docx,.pdf" multiple hidden>
      </label>
      <button type="submit" class="upload-btn">Upload</button>
    </form>

    <div id="reportStatusBox" class="status-box">
      <h2>Submitted Reports</h2>
      <ul id="reportList"></ul>
    </div>
  </div>

  <script src="js/report.js"></script>
</body>
</html>
