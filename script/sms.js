// ---------- SIDE NAV TOGGLE ----------
const toggleBtn = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});

// ---------- MAIN SCRIPT ----------
document.addEventListener("DOMContentLoaded", () => {
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

    loadReports();
  }

  // ---------- LOAD VOLUNTEER REPORTS ----------
  function loadReports() {
    const reports = JSON.parse(localStorage.getItem("uploadedReports")) || [];

    reports.forEach((report, index) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>Report</td>
        <td>${report.name} <br><small>${report.time}</small></td>
        <td>
          <a href="#" onclick="downloadReport(${index})">
            <button>⬇ Download</button>
          </a>
        </td>
      `;
      tableBody.appendChild(row);
    });
  }

  // ---------- DOWNLOAD REPORT ----------
  window.downloadReport = function(index) {
    const reports = JSON.parse(localStorage.getItem("uploadedReports")) || [];
    if (!reports[index]) return alert("Report not found.");

    const blob = new Blob([`Report: ${reports[index].name}`], { type: "application/octet-stream" });
    const url = URL.createObjectURL(blob);

    const link = document.createElement("a");
    link.href = url;
    link.download = reports[index].name;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
  };

  //---------- VIEW VOLUNTEER ----------
window.viewVolunteer = function(index) {
  const pending = JSON.parse(localStorage.getItem("pendingVolunteers")) || [];
  const v = pending[index];
  currentVolunteerIndex = index;

  detailsDiv.innerHTML = `
    <div class="modal-header">
      <img src="img/Philippine_Red_Cross_logo.jpg" alt="Red Cross">
      <h2>${v.fullName}</h2>
      <p>Volunteer Details</p>
    </div>

    <div class="modal-body">

      <h3>Personal Information</h3>
      <div class="grid">
        <div><b>First Name:</b> ${v.firstName}</div>
        <div><b>Middle Name:</b> ${v.middleName}</div>
        <div><b>Last Name:</b> ${v.lastName}</div>
        <div><b>Birth Place:</b> ${v.birthPlace}</div>
        <div><b>Sex:</b> ${v.sex}</div>
        <div><b>Date of Birth:</b> ${v.dob}</div>
        <div><b>Religion:</b> ${v.religion}</div>
        <div><b>Height / Weight:</b> ${v.height} cm / ${v.weight} kg</div>
        <div><b>Civil Status:</b> ${v.civilStatus}</div>
        <div><b>Spouse:</b> ${v.spouse}</div>
        <div><b>Children:</b> ${v.children}</div>
        <div class="wide"><b>Address:</b> ${v.address}</div>
      </div>

      <h3>Medical</h3>
      <div class="grid">
        <div><b>Health:</b> ${v.health}</div>
        <div><b>Medication:</b> ${v.medication}</div>
        <div><b>Blood Type:</b> ${v.bloodType}</div>
      </div>

      <h3>Educational Background</h3>
      <div class="grid">
        <div><b>Elementary:</b> ${v.elementary}</div>
        <div><b>Year Graduated:</b> ${v.elemYearGrad}</div>
        <div><b>High School:</b> ${v.highSchool}</div>
        <div><b>Year Graduated:</b> ${v.hsYearGrad}</div>
        <div><b>College / Course:</b> ${v.college}</div>
        <div><b>Year Graduated:</b> ${v.collegeYearGrad}</div>
        <div><b>Post Graduate:</b> ${v.postGrad}</div>
        <div><b>Year Graduated:</b> ${v.postGradYear}</div>
      </div>

      <h3>Talents & Skills</h3>
      <div class="grid">
        <div class="wide"><b>Skills:</b> ${v.skills}</div>
        <div class="wide"><b>Languages:</b> ${v.languages}</div>
      </div>

      <h3>Work Experience</h3>
      <div class="grid">
        <div><b>Company:</b> ${v.company}</div>
        <div><b>Position:</b> ${v.position}</div>
        <div class="wide"><b>Inclusive Dates:</b> ${v.workDates}</div>
      </div>

      <h3>Red Cross Experience</h3>
      <div class="grid">
        <div><b>Member?</b> ${v.redCrossMember}</div>
        <div><b>Membership Type:</b> ${v.membershipType}</div>
        <div class="wide"><b>Trainings:</b> ${v.trainings}</div>
      </div>

      <h3>References</h3>
      <div class="grid">
        <div><b>Name:</b> ${v.refName}</div>
        <div class="wide"><b>Contact:</b> ${v.refContact}</div>
      </div>

    </div>
  `;

  modal.style.display = "flex";
};




  // ---------- DOWNLOAD FULL VOLUNTEER APPLICATION AS DOC ----------
  document.getElementById("downloadDocBtn").addEventListener("click", () => {
    let pending = JSON.parse(localStorage.getItem("pendingVolunteers")) || [];

    if (currentVolunteerIndex !== null) {
      const v = pending[currentVolunteerIndex];

      let docContent = `
        <h2 style="text-align:center;">Volunteer Application Form</h2>
        <hr>
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
        <hr>
        <p><i>Generated on: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}</i></p>
      `;

      let blob = new Blob(['\ufeff', docContent], { type: 'application/msword' });
      let url = URL.createObjectURL(blob);

      let link = document.createElement("a");
      link.href = url;
      link.download = `${v.fullName.replace(/\s+/g, "_")}_Application.doc`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      URL.revokeObjectURL(url);
    } else {
      alert("No volunteer selected.");
    }
  });

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
