//--------- SIDEBAR ----------
const toggleBtn = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});

// ---------- MAP SCRIPT ----------
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("volunteerModal");
  const closeModalBtn = document.getElementById("closeModal");
  const closeBtn = document.getElementById("closeBtn");
  const volunteerListEl = document.getElementById("volunteerList");
  const locationNameEl = document.getElementById("locationName");

  const map = L.map("map").setView([13.8, 124.2], 10);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "&copy; OpenStreetMap contributors"
  }).addTo(map);

  let locations = {
    "Virac": [13.584, 124.237],
    "San Andres": [13.598, 124.091],
    "Pandan": [14.058, 124.167],
    "San Miguel": [13.788, 124.219],
    "Bato": [13.602, 124.317],
    "Panganiban": [14.093, 124.329],
    "Gigmoto": [13.781, 124.390],
    "Viga": [13.884, 124.300]
  };

async function renderEventLocation(){
  const res = await fetch("./utility/mapLocation.php");
  const j = await res.json()
  locations = {}
  j.forEach(e=>{
    locations[e.eventName] = [e.latitude, e.longitude]
  })
  console.log(j)
    Object.entries(locations).forEach(([town, coords]) => {
    const marker = L.marker(coords).addTo(map).bindPopup(`
      <b>${town}</b><br>
      <span style="font-size: 12px; color: #666;">
        📍 Lat: ${coords[0]}<br>
        📍 Lng: ${coords[1]}
      </span>
    `);
    marker.on("click", () => showVolunteersForLocation(town));
  });


}
renderEventLocation()
  async function showVolunteersForLocation(location) {
    locationNameEl.textContent = location;

    // const volunteers = JSON.parse(localStorage.getItem("volunteers")) || [];
    const v = await fetch("./utility/getAllVolunteer.php");
    const volunteers = await v.json()
    console.log(volunteers)
    const filtered = volunteers.filter(v => v.eventName == location);
    console.log(filtered)

    if (!filtered.length) {
      volunteerListEl.innerHTML = `
        <p style="color:#666; text-align:center; padding:16px;">
          No volunteers deployed in ${location}.
        </p>
      `;
    } else {
      volunteerListEl.innerHTML = filtered.map((v, i) => {
        const age = getAge(v.dob) || "N/A";
        const deployed = (v.deployedLocation === location);

        return `
          <div class="volunteer-card" data-index="${i}">
            <div class="volunteer-summary">
              <div>
                <div class="name">${escapeHtml(v.fullName || "Unnamed")}</div>
                <div class="sub">Age: ${v.age} • ${v.sex || 'N/A'}</div>
              </div>
              <div class="status">
                <span class="status-dot ${deployed ? "deployed" : "not-deployed"}"></span>
                <span>${v.status}</span>
              </div>
            </div>
            <div class="volunteer-details">
              <div><b>Mobile:</b> ${escapeHtml(v.mobile || "N/A")}</div>
              <div><b>Address:</b> ${escapeHtml(v.address || "N/A")}</div>
            </div>
          </div>
        `;
      }).join("");

      document.querySelectorAll(".volunteer-card").forEach(card => {
        card.addEventListener("click", () => {
          card.classList.toggle("expanded");
        });
      });
    }

    modal.classList.remove("hidden");
  }

  // ---------- MODAL HANDLING ----------
  closeModalBtn.addEventListener("click", closeModal);
  closeBtn.addEventListener("click", closeModal);
  modal.addEventListener("click", (e) => { if (e.target === modal) closeModal(); });
  document.addEventListener("keydown", (e) => { if (e.key === "Escape") closeModal(); });

  function closeModal() {
    modal.classList.add("hidden");
  }

  // ---------- HELPERS ----------
  function getAge(dob) {
    if (!dob) return null;
    const birth = new Date(dob);
    if (isNaN(birth.getTime())) return null;

    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();

    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
      age--;
    }
    return age;
  }

  function escapeHtml(str) {
    if (!str) return "";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }
});
