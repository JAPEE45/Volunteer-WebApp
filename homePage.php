<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="style/homepage.css">
 
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
    <a href="adminActivityReports.php"><i class="fas fa-file-alt fa-fw"></i> Activity Reports</a>
    <a href="map.php"><i class="fas fa-map-marker-alt fa-fw"></i> Map</a>
     <a href="login.php"><i class="fas fa-sign-out-alt fa-fw"></i>Logout</a>
    <!-- <a href="sms.php"><i class="fas fa-envelope fa-fw"></i> SMS Alerts</a> -->
  </div>

  <button class="menu-toggle" id="menu-toggle">☰</button>

  <div class="content">
    <h1>Admin Dashboard</h1>
    <!--
    <div class="cards">
      <div class="card">100 Volunteers</div>
      <div class="card">5 Upcoming Events</div>
      <div class="card">5 SMS Alerts</div>
    </div>
    -->

    <div class="dashboard-graphs">
      <div class="graph-card">
        <h3>Volunteers Distribution</h3>
        <canvas id="volunteersChart"></canvas>
      </div>
      <div class="graph-card">
        <h3>Upcoming Events</h3>
        <canvas id="eventsChart"></canvas>
      </div>
      <div class="graph-card">
        <h3>SMS Alerts</h3>
        <canvas id="smsChart"></canvas>
      </div>
    </div>

    <div class="dashboard-layout">
      <div class="map">
        <h3>Map</h3>
        <div id="mapid"></div>
      </div>
      <div class="event-calendar">
        <h3>Event Calendar</h3>
        <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Time</th>
              <th>Event</th>
              <th>Location</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>22 May 2025</td>
              <td>9:00 am</td>
              <td>New Volunteer Event</td>
              <td>Virac, Catanduanes</td>
            </tr>
            <tr>
              <td>18 May 2025</td>
              <td>4:10 pm</td>
              <td>Training Session</td>
              <td>Virac, Catanduanes</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!--
    <div class="sms-logs">
      <h3>Recent SMS Logs</h3>
      <div class="sms">
        <p>22 May 2025, 9:00 am - Notification: New Volunteer Event</p>
        <p>18 May 2025, 4:10 pm - Reminder: Training Session</p>
      </div>
    </div>
    -->

  </div>

  <script>
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

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="script/homePage.js"></script>
</body>
</html>