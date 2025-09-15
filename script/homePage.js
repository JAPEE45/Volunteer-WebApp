
 var map = L.map('mapid').setView([13.5833, 124.2333], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
    }).addTo(map);


    L.marker([13.5833, 124.2333]).addTo(map)
      .bindPopup("<b>Virac, Catanduanes</b><br>Red Cross Event Location")
      .openPopup();

const toggleBtn = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("active");
  
});

document.addEventListener("DOMContentLoaded", () => {
      fetch("sidebar.html")
        .then(res => res.text())
        .then(data => {
          document.getElementById("sidebar-container").innerHTML = data;
        });
    });