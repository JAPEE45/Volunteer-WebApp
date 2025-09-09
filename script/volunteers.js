var map = L.map('map').setView([13.5844, 124.2372], 11);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap'
}).addTo(map);

const locations = [
  { name: "Virac, Catanduanes", coords: [13.5844, 124.2372] },
  { name: "San Andres", coords: [13.598, 124.091] },
  { name: "Pandan", coords: [14.058, 124.167] },
  { name: "San Miguel", coords: [13.788, 124.219] },
  { name: "Bato", coords: [13.602, 124.317] },
  { name: "Panganiban", coords: [14.093, 124.329] },
  { name: "Gigmoto", coords: [13.781, 124.390] },
  { name: "Viga", coords: [13.884, 124.300] }
];

locations.forEach(loc => {
  L.marker(loc.coords)
    .addTo(map)
    .bindPopup(`<b>${loc.name}</b>`);
});



// ---------- SIDE NAV TOGGLE ----------

const toggleBtn = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("active");
  
});

// ---------- MODAL HANDLING ----------
function openModal() {
  document.getElementById("volunteerModal").style.display = "flex";
}

function closeModal() {
  document.getElementById("volunteerModal").style.display = "none";
}

// ---------- SAVE NEW VOLUNTEER ----------
function saveVolunteer() {
  const name = document.getElementById("volName").value.trim();
  const location = document.getElementById("volLocation").value.trim();

  if (name && location) {
    let volunteers = JSON.parse(localStorage.getItem("volunteers")) || [];

    const newVolunteer = {
      fullName: name,
      address: location,
      age: "Not provided",
      sex: "Not provided"
    };

    volunteers.push(newVolunteer);
    localStorage.setItem("volunteers", JSON.stringify(volunteers));

    addVolunteerToTable(newVolunteer, volunteers.length - 1);

    closeModal();
    document.getElementById("volName").value = "";
    document.getElementById("volLocation").value = "";
  }
}

// ---------- REMOVE VOLUNTEER ----------
function removeVolunteer(index) {
  let volunteers = JSON.parse(localStorage.getItem("volunteers")) || [];
  volunteers.splice(index, 1);
  localStorage.setItem("volunteers", JSON.stringify(volunteers));
  renderTable();
}

// ---------- ADD ROW TO TABLE ----------
function addVolunteerToTable(volunteer, index) {
  const table = document.getElementById("volunteerTable").querySelector("tbody");
  const row = document.createElement("tr");

  row.innerHTML = `
    <td class="volunteer-name" data-index="${index}">${volunteer.fullName}</td>
    <td>${volunteer.address}</td>
    <td><button class="delete-btn" onclick="removeVolunteer(${index})">🗑</button></td>
  `;

  table.appendChild(row);
}

// ---------- RENDER ALL VOLUNTEERS ----------
function renderTable() {
  const tbody = document.getElementById("volunteerTable").querySelector("tbody");
  tbody.innerHTML = "";

  let volunteers = JSON.parse(localStorage.getItem("volunteers")) || [];

  volunteers.forEach((volunteer, index) => {
    addVolunteerToTable(volunteer, index);
  });

  // add click event to each name
  document.querySelectorAll(".volunteer-name").forEach(td => {
    td.style.cursor = "pointer";
    td.addEventListener("click", () => {
      const idx = td.getAttribute("data-index");
      let volunteers = JSON.parse(localStorage.getItem("volunteers")) || [];
      openInfoModal(volunteers[idx]);
    });
  });
}

// ---------- INFO MODAL ----------
function openInfoModal(volunteer) {
  document.getElementById("infoName").textContent = volunteer.fullName;
  document.getElementById("infoAddress").textContent = volunteer.address || "Not provided";
  document.getElementById("infoAge").textContent = volunteer.age || "Not provided";
  document.getElementById("infoSex").textContent = volunteer.sex || "Not provided";
  document.getElementById("deployLocation").value = "";
  document.getElementById("infoModal").style.display = "flex";
}

function closeInfoModal() {
  document.getElementById("infoModal").style.display = "none";
}

function submitDeployment() {
  const deployLocation = document.getElementById("deployLocation").value;
  const name = document.getElementById("infoName").textContent;

  if (deployLocation.trim() === "") {
    alert("Please enter a deployment location.");
    return;
  }

  alert(`${name} has been assigned to: ${deployLocation}`);
  closeInfoModal();
}

function submitDeployment() {
  const deployLocation = document.getElementById("deployLocation").value;
  const name = document.getElementById("infoName").textContent;

  if (!deployLocation) {
    alert("Please select a deployment location.");
    return;
  }

  let volunteers = JSON.parse(localStorage.getItem("volunteers")) || [];
  let updated = volunteers.map(v => {
    if (v.fullName === name) {
      v.deployedLocation = deployLocation;
    }
    return v;
  });

  localStorage.setItem("volunteers", JSON.stringify(updated));

  alert(`${name} has been assigned to: ${deployLocation}`);
  closeInfoModal();
  renderTable(); // refresh table
}

// ---------- INITIALIZE ----------
document.addEventListener("DOMContentLoaded", () => {
  renderTable();
});
