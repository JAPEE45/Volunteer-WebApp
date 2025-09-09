document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("volunteerModal");
  const closeModal = document.getElementById("closeModal");
  const volunteerList = document.getElementById("volunteerList");

  // ---------- SIDE NAV TOGGLE ----------

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

  function showVolunteers(location) {
  const volunteers = JSON.parse(localStorage.getItem("volunteers")) || [];
  const filtered = volunteers.filter(v => v.deployedLocation === location);

  if (filtered.length === 0) {
    volunteerList.innerHTML = `<p>No volunteers deployed in ${location}.</p>`;
  } else {
    volunteerList.innerHTML = filtered.map(v => `
      <div class="volunteer-card">
        <p><strong>Full Name:</strong> ${v.fullName}</p>
        <p><strong>Address:</strong> ${v.address}</p>
        <p><strong>Deployment Address:</strong> ${v.deployedLocation}</p>
        <p><strong>Age:</strong> ${getAge(v.dob)}</p>
        <p><strong>Cellphone:</strong> ${v.mobile}</p>
        <p><strong>Email:</strong> ${v.email || "N/A"}</p>
      </div>
    `).join("");
  }
  modal.classList.remove("hidden");
}


  // Calculate age
  function getAge(dob) {
    if (!dob) return "N/A";
    const birth = new Date(dob);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
    return age;
  }
  
  closeModal.addEventListener("click", () => {
    modal.classList.add("hidden");
  });
});
