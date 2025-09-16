//--------- MAP ----------
var map = L.map('mapid').setView([13.5833, 124.2333], 10);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
}).addTo(map);

L.marker([13.5833, 124.2333]).addTo(map)
  .bindPopup("<b>Virac, Catanduanes</b><br>Red Cross Event Location")
  .openPopup();

//--------- SIDEBAR ----------
const toggleBtn = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});

//--------- PIE CHART ----------
const volunteersCtx = document.getElementById("volunteersChart").getContext("2d");
new Chart(volunteersCtx, {
  type: "pie",
  data: {
    labels: ["Deployed", "Not Deployed"],
    datasets: [{
      data: [70, 30],
      backgroundColor: ["#28a745", "#dc3545"]
    }]
  },
  options: { responsive: true }
});

//--------- BAR CHART ----------
const eventsCtx = document.getElementById("eventsChart").getContext("2d");
new Chart(eventsCtx, {
  type: "bar",
  data: {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
    datasets: [{
      label: "Events",
      data: [2, 4, 3, 6, 5, 7],
      backgroundColor: "#b30000"
    }]
  },
  options: {
    responsive: true,
    scales: { y: { beginAtZero: true } }
  }
});

//--------- LINE CHART ----------
const smsCtx = document.getElementById("smsChart").getContext("2d");
new Chart(smsCtx, {
  type: "line",
  data: {
    labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
    datasets: [{
      label: "SMS Sent",
      data: [5, 10, 8, 15, 12, 20, 18],
      borderColor: "#007bff",
      backgroundColor: "rgba(0,123,255,0.2)",
      fill: true,
      tension: 0.3
    }]
  },
  options: {
    responsive: true,
    scales: { y: { beginAtZero: true } }
  }
});
