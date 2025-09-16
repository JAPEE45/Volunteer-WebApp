// ---------- SIDE NAV TOGGLE ----------

const toggleBtn = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});


// ---------- MAIN SCRIPT ----------
document.addEventListener("DOMContentLoaded", () => {
  // ---------- REFERENCES ----------
  const tableBody = document.querySelector("#eventTable tbody");
  const modal = document.getElementById("volunteerModal");
  const detailsDiv = document.getElementById("volunteerDetails");
  let currentVolunteerIndex = null;

  // ---------- LOAD PENDING VOLUNTEERS ----------
  function loadPending() {
    tableBody.innerHTML = "";
    let pending = JSON.parse(localStorage.getItem("pendingVolunteers")) || [];

    pending.forEach((v, index) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>Notification</td>
        <td>${v.fullName}</td>
        <td><button onclick="viewVolunteer(${index})">View</button></td>
      `;
      tableBody.appendChild(row);
    });
  }

  // ---------- VIEW VOLUNTEER DETAILS ----------
  window.viewVolunteer = function(index) {
    let pending = JSON.parse(localStorage.getItem("pendingVolunteers")) || [];
    const v = pending[index];
    currentVolunteerIndex = index;

    detailsDiv.innerHTML = `
      <p><b>Name:</b> ${v.fullName}</p>
      <p><b>Birth Place:</b> ${v.birthPlace}</p>
      <p><b>Sex:</b> ${v.sex}</p>
      <p><b>Date of Birth:</b> ${v.dob}</p>
      <p><b>Religion:</b> ${v.religion}</p>
      <p><b>Height:</b> ${v.height} cm</p>
      <p><b>Weight:</b> ${v.weight} kg</p>
      <p><b>Civil Status:</b> ${v.civilStatus}</p>
      <p><b>Spouse:</b> ${v.spouse}</p>
      <p><b>Children:</b> ${v.children}</p>
      <p><b>Mobile:</b> ${v.mobile}</p>
      <p><b>Landline:</b> ${v.landline}</p>
      <p><b>Address:</b> ${v.address}</p>
      <p><b>Health:</b> ${v.health}</p>
      <p><b>Medication:</b> ${v.medication}</p>
      <p><b>Blood Type:</b> ${v.bloodType}</p>
    `;

    modal.style.display = "flex";
  };

  // ---------- CONFIRM VOLUNTEER ----------
  document.getElementById("confirmBtn").addEventListener("click", () => {
    let pending = JSON.parse(localStorage.getItem("pendingVolunteers")) || [];
    let volunteers = JSON.parse(localStorage.getItem("volunteers")) || [];

    if (currentVolunteerIndex !== null) {
      volunteers.push(pending[currentVolunteerIndex]);
      localStorage.setItem("volunteers", JSON.stringify(volunteers));

      pending.splice(currentVolunteerIndex, 1);
      localStorage.setItem("pendingVolunteers", JSON.stringify(pending));

      closeModal();
      loadPending();
      alert("Volunteer confirmed!");
    }
  });

  // ---------- REJECT VOLUNTEER ----------
  document.getElementById("rejectBtn").addEventListener("click", () => {
    let pending = JSON.parse(localStorage.getItem("pendingVolunteers")) || [];

    if (currentVolunteerIndex !== null) {
      pending.splice(currentVolunteerIndex, 1);
      localStorage.setItem("pendingVolunteers", JSON.stringify(pending));

      closeModal();
      loadPending();
      alert("Volunteer rejected.");
    }
  });

  // ---------- CLOSE MODAL ----------
  window.closeModal = function() {
    modal.style.display = "none";
    currentVolunteerIndex = null;
  };

  // ---------- INITIAL LOAD ----------
  loadPending();
});
