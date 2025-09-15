document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registrationForm");

  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();

      const firstName = document.getElementById("firstName").value;
      const middleName = document.getElementById("middleName").value;
      const lastName = document.getElementById("lastName").value;

      const volunteer = {
        firstName,
        middleName,
        lastName,
        fullName: `${firstName} ${middleName} ${lastName}`.trim(),
        birthPlace: document.getElementById("birthPlace").value,
        sex: document.getElementById("sex").value,
        dob: document.getElementById("dob").value,
        religion: document.getElementById("religion").value,
        height: document.getElementById("height").value,
        weight: document.getElementById("weight").value,
        civilStatus: document.getElementById("civilStatus").value,
        spouse: document.getElementById("spouse").value,
        children: document.getElementById("children").value,
        mobile: document.getElementById("mobile").value,
        landline: document.getElementById("landline").value,
        address: document.getElementById("address").value,
        health: document.getElementById("health").value,
        medication: document.getElementById("medication").value,
        bloodType: document.getElementById("bloodType").value
      };

      // Save to "pending" instead of volunteers
      let pending = JSON.parse(localStorage.getItem("pendingVolunteers")) || [];
      pending.push(volunteer);
      localStorage.setItem("pendingVolunteers", JSON.stringify(pending));

      alert("Registration submitted! Awaiting approval in SMS Alerts.");
      form.reset();
      window.location.href = "homePage.html";
    });
  }
});
