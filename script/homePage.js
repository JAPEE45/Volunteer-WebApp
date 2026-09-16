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

//--------- AUTO RECALL CHECK ----------
// Automatically check and recall volunteers from ended events on page load
async function checkAndRecallVolunteers() {
  try {
    const res = await fetch('./utility/recallVolunteers.php');
    const result = await res.json();
    if (result.recalled_count > 0) {
      console.log(`Auto-recalled ${result.recalled_count} volunteers from completed events`);
    }
  } catch (error) {
    console.error('Error checking volunteer recalls:', error);
  }
}
checkAndRecallVolunteers();

//--------- PIE CHART ----------
async function depStatus(){
    const res = await fetch("./utility/getDeployCount.php");
  const j = await res.json()
  let mt = Object.keys(j)
  let dt =Object.values(j)
  console.log(j)
  const volunteersCtx = document.getElementById("volunteersChart").getContext("2d");
  new Chart(volunteersCtx, {
    type: "pie",
    data: {
      labels: ["Deployed", "Not Deployed"],
      datasets: [{
        data: dt,
        backgroundColor: ["#28a745", "#dc3545"]
      }]
    },
    options: { responsive: true }
  });

}
depStatus()
async function barGraph() {
  const res = await fetch("./utility/getUpcomingEvent.php");
  const j = await res.json()
  let mt = Object.keys(j[0])
  let dt =Object.values(j[0])
const eventsCtx = document.getElementById("eventsChart").getContext("2d");
new Chart(eventsCtx, {
  type: "bar",
  data: {
    labels: mt,
    datasets: [{
      label: "Events",
      data: dt,
      backgroundColor: "#b30000"
    }]
  },
  options: {
    responsive: true,
    scales: { y: { beginAtZero: true } }
  }
});

}
barGraph()
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


async function loadEvents() {
  try {
    const response = await fetch('./utility/getEventHomepage.php');
    const events = await response.json();

    const table = document.querySelector(".event-calendar table");
    const rows = table.querySelectorAll("tr:not(:first-child)");
    rows.forEach(r => r.remove()); // Clear old rows

    events.forEach(e => {
      const tr = document.createElement("tr");
      const eventDate = new Date(e.date);

      // Format date to something like "22 May 2025"
      const formattedDate = eventDate.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });

      tr.innerHTML = `
        <td>${formattedDate}</td>
        <td>${e.eventName}</td>
        <td>${e.location}</td>
      `;
      table.appendChild(tr);
    });

  } catch (error) {
    console.error("Failed to load events:", error);
  }
}

// Run on page load
loadEvents();
