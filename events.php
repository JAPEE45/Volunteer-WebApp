<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
  rel="stylesheet"
  href="https://unpkg.com/leaflet/dist/leaflet.css"
/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style/events.css">
    
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
    <!-- <a href="sms.php"><i class="fas fa-envelope fa-fw"></i> SMS Alerts</a> -->

  </div>

  <button class="menu-toggle" id="menu-toggle">☰</button>

  <div class="content">
    <h1>EVENTS</h1>
    <div class="top-bar">
      <button id="addEventBtn">ADD EVENTS</button>
      <input type="text" id="searchEvent" placeholder="Search events...">
    </div>

  <table id="eventTable">
    <thead>
      <tr>
        <th>EVENT NAME</th>
        <th>LOCATION</th>
        <th>ACTION</th>
      </tr>
    </thead>
    <tbody>
      
    </tbody>
  </table>

  <div id="addEventModal" class="modal hidden">
    <div class="modal-content">
      <span class="close" id="closeAddModal">&times;</span>
      <h3>Add Event</h3>
      <form id="eventForm">
        <label>Event Name</label>
        <input type="text" id="eventName" required>
        <label>Location</label>
        <input type="text" id="eventLocation" required>
        <label>Coordinates</label>
      <input type="text" id="longitude" placeholder="Longitude" required>
      <input type="text" id="latitude" placeholder="Latitude" required>
      <button type="button" id="mapModalBtn">Add coordinates</button>
        <label>Start Date & Time</label>
        <input type="datetime-local" id="eventDateTime" required>
        <label>Duration (hours)</label>
        <input type="number" id="eventDuration" min="1" max="720" value="8" required placeholder="Event duration in hours">
        <small style="color: #666; display: block; margin-top: -10px; margin-bottom: 15px;">
          End time will be calculated automatically
        </small>
        <button type="submit" id="saveEvent">Save Event</button>
      </form>
    </div>
  </div>

  <!-- Map Modal -->
<!-- Map Modal -->
<div id="mapModal" class="modal hidden">
  <div class="modal-content" >
    <span class="close" id="closeMapModal">&times;</span>
    <h3>Select Coordinates</h3>
    <div id="map" style="height: 500px; width: 100%; margin-bottom: 10px;"></div>
    <button id="useLocationBtn" type="button">Use this Location</button>
  </div>
</div>


  <div id="viewEventModal" class="modal hidden">
    <div class="modal-content">
      <span class="close" id="closeViewModal">&times;</span>
      <h3>EVENT INFORMATION</h3>
      <p><strong>Event Name:</strong> <span id="viewName"></span></p>
      <p><strong>Location:</strong> <span id="viewLocation"></span></p>
      <p><strong>Start Date:</strong> <span id="viewDateTime"></span></p>
      <p><strong>Duration:</strong> <span id="viewDuration"></span> hours</p>
      <p><strong>End Date:</strong> <span id="viewEndDate"></span></p>
      <p><strong>Status:</strong> <span id="viewStatus"></span></p>
      <button id="okBtn">OK</button>
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
<script>

document.addEventListener("DOMContentLoaded", function () {
  const mapModalBtn = document.getElementById("mapModalBtn");
  const mapModal = document.getElementById("mapModal");
  const closeMapModal = document.getElementById("closeMapModal");
  const useLocationBtn = document.getElementById("useLocationBtn");
  const longitudeInput = document.getElementById("longitude");
  const latitudeInput = document.getElementById("latitude");
  longitudeInput.disabled = true
  latitudeInput.disabled = true
  let map, marker, selectedCoords;
  mapModalBtn.addEventListener("click", function () {
    mapModal.classList.remove("hidden");

    if (!map) {
      map = L.map("map").setView([13.7089, 124.2422], 10);

      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);
      map.on("click", function (e) {
        const { lat, lng } = e.latlng;
        selectedCoords = { lat, lng };

        if (marker) {
          marker.setLatLng(e.latlng);
        } else {
          marker = L.marker(e.latlng).addTo(map);
        }
      });
    }

    setTimeout(() => {
      map.invalidateSize();
    }, 200);
  });

  closeMapModal.addEventListener("click", function () {
    mapModal.classList.add("hidden");
  });
  useLocationBtn.addEventListener("click", function () {
    if (selectedCoords) {
      latitudeInput.value = selectedCoords.lat.toFixed(6);
      longitudeInput.value = selectedCoords.lng.toFixed(6);
    }
    mapModal.classList.add("hidden");
  });
});


</script>
<script src="script/events.js"></script>
</html>