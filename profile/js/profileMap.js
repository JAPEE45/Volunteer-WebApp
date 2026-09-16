const toggleBtn = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("active");
  
});

const map = L.map("map").setView([13.8, 124.2], 10);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "&copy; OpenStreetMap contributors"
  }).addTo(map);

  // ---------- MARKERS ----------
  const locations = {
    "Virac": [13.584, 124.237],
    "San Andres": [13.598, 124.091],
    "Pandan": [14.058, 124.167],
    "San Miguel": [13.788, 124.219],
    "Bato": [13.602, 124.317],
    "Panganiban": [14.093, 124.329],
    "Gigmoto": [13.781, 124.390],
    "Viga": [13.884, 124.300]
  };

  for (const [town, coords] of Object.entries(locations)) {
    const marker = L.marker(coords).addTo(map).bindPopup(`<b>${town}</b>`);
    marker.on("click", () => {
      showVolunteers(town);
    });
  }