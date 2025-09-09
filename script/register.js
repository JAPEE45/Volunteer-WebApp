document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registrationForm");

  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();

      const volunteer = {
        fullName: document.getElementById("fullName").value,
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

      let volunteers = JSON.parse(localStorage.getItem("volunteers")) || [];
      volunteers.push(volunteer);
      localStorage.setItem("volunteers", JSON.stringify(volunteers));

      alert("Registration successful!");

      form.reset();
      window.location.href = "homePage.html";
    });
  }
});
