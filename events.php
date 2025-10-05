<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/events.css">
    <link
  rel="stylesheet"
  href="https://unpkg.com/leaflet/dist/leaflet.css"
/>
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
      <tr>
        <td>Maria Isabel D. Rebanal</td>
        <td>Virac, Catanduanes, Philippines</td>
        <td><button class="delete-btn" onclick="removeRow(this)">🗑</button></td>
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
        <label>Date & Time</label>
        <input type="datetime-local" id="eventDateTime" required>
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
      <p><strong>Date and Time:</strong> <span id="viewDateTime"></span></p>
      <button id="okBtn">OK</button>
    </div>
  </div>
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

  let map, marker, selectedCoords;

  // Open map modal
  mapModalBtn.addEventListener("click", function () {
    mapModal.classList.remove("hidden");

    if (!map) {
      map = L.map("map").setView([13.7089, 124.2422], 10); // Center on Catanduanes

      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);

      // On map click, set marker and save coords
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

  // Close map modal
  closeMapModal.addEventListener("click", function () {
    mapModal.classList.add("hidden");
  });

  // Use selected location
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