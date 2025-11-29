<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="style/map.css">

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
  <h1>VOLUNTEERS MAP</h1>
  <div id="map"></div>
  </div>

  <div id="volunteerModal" class="modal hidden" role="dialog" aria-hidden="true">
  <div class="modal-content" role="document">
    <button id="closeModal" class="modal-close" aria-label="Close modal">&times;</button>
    <h3>Volunteers in <span id="locationName"></span></h3>
    <div id="volunteerList" class="volunteer-list" aria-live="polite"></div>
    <div class="modal-actions">
    <button id="closeBtn" class="btn neutral">Close</button>
    </div>
  </div>
  </div>

  <script>
  document.getElementById('menu-toggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('active');
  });

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
<script src="script/map.js"></script>
</html>
