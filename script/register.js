document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registrationForm");

  if (form) {
   form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const formData = new FormData();

  // Add all your text fields
  formData.append("firstName", document.getElementById("firstName").value);
  formData.append("middleName", document.getElementById("middleName").value);
  formData.append("lastName", document.getElementById("lastName").value);
  formData.append("fullName", `${document.getElementById("firstName").value} ${document.getElementById("middleName").value} ${document.getElementById("lastName").value}`.trim());
  formData.append("birthPlace", document.getElementById("birthPlace").value);
  formData.append("sex", document.getElementById("sex").value);
  formData.append("dob", document.getElementById("dob").value);
  formData.append("religion", document.getElementById("religion").value);
  formData.append("height", document.getElementById("height").value);
  formData.append("weight", document.getElementById("weight").value);
  formData.append("civilStatus", document.getElementById("civilStatus").value);
  formData.append("spouse", document.getElementById("spouse").value);
  formData.append("children", document.getElementById("children").value);
  formData.append("mobile", document.getElementById("mobile").value);
  formData.append("landline", document.getElementById("landline").value);
  formData.append("address", document.getElementById("address").value);
  formData.append("health", document.getElementById("health").value);
  formData.append("medication", document.getElementById("medication").value);
  formData.append("bloodType", document.getElementById("bloodType").value);
  formData.append("elementary", document.getElementById("elementary").value);
  formData.append("elemYearGrad", document.getElementById("elemYearGrad").value);
  formData.append("highSchool", document.getElementById("highSchool").value);
  formData.append("hsYearGrad", document.getElementById("hsYearGrad").value);
  formData.append("college", document.getElementById("college").value);
  formData.append("collegeYearGrad", document.getElementById("collegeYearGrad").value);
  formData.append("postGrad", document.getElementById("postGrad").value);
  formData.append("postGradYear", document.getElementById("postGradYear").value);
  formData.append("skills", document.getElementById("skills").value);
  formData.append("languages", document.getElementById("languages").value);
  formData.append("involvements", document.getElementById("involvements").value);
  formData.append("company", document.getElementById("company").value);
  formData.append("position", document.getElementById("position").value);
  formData.append("workDates", document.getElementById("workDates").value);
  formData.append("redCrossMember", document.getElementById("redCrossMember").value);
  formData.append("membershipType", document.getElementById("membershipType").value);
  formData.append("trainings", document.getElementById("trainings").value);
  formData.append("refName", document.getElementById("refName").value);
  formData.append("refContact", document.getElementById("refContact").value);
  formData.append("age", document.getElementById("age").value);

  // Add the files
  const files = document.getElementById("documents").files;
  for (let i = 0; i < files.length; i++) {
    formData.append("documents[]", files[i]); // use [] for multiple files
  }

  const res = await fetch("./utility/addUser.php", {
    method: "POST",
    body: formData,
  });

  const j = await res.json();
  console.log(j);

  alert("Registration submitted! Awaiting approval in SMS Alerts.");
});

  }
});
