document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registrationForm");

  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();

      const volunteer = {
        firstName: document.getElementById("firstName").value,
        middleName: document.getElementById("middleName").value,
        lastName: document.getElementById("lastName").value,
        fullName: `${document.getElementById("firstName").value} ${document.getElementById("middleName").value} ${document.getElementById("lastName").value}`.trim(),
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
        bloodType: document.getElementById("bloodType").value,
        elementary: document.getElementById("elementary").value,
        elemYearGrad: document.getElementById("elemYearGrad").value,
        highSchool: document.getElementById("highSchool").value,
        hsYearGrad: document.getElementById("hsYearGrad").value,
        college: document.getElementById("college").value,
        collegeYearGrad: document.getElementById("collegeYearGrad").value,
        postGrad: document.getElementById("postGrad").value,
        postGradYear: document.getElementById("postGradYear").value,
        skills: document.getElementById("skills").value,
        languages: document.getElementById("languages").value,
        involvements: document.getElementById("involvements").value,
        company: document.getElementById("company").value,
        position: document.getElementById("position").value,
        workDates: document.getElementById("workDates").value,
        redCrossMember: document.getElementById("redCrossMember").value,
        membershipType: document.getElementById("membershipType").value,
        trainings: document.getElementById("trainings").value,
        refName: document.getElementById("refName").value,
        refContact: document.getElementById("refContact").value,
        age: document.getElementById("age").value
      };

      const files = document.getElementById("documents").files;
      volunteer.documents = [];
      for (let i = 0; i < files.length; i++) {
        volunteer.documents.push(files[i].name);
      }

    
      let pending = JSON.parse(localStorage.getItem("pendingVolunteers")) || [];
      pending.push(volunteer);
      localStorage.setItem("pendingVolunteers", JSON.stringify(pending));

      alert("Registration submitted! Awaiting approval in SMS Alerts.");
      form.reset();
      window.location.href = "homePage.html";
    });
  }
});
