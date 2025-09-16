//--------- SIDEBAR TOGGLE ----------
const toggleBtn = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});

//--------- LOAD VOLUNTEER INFO ----------
document.addEventListener("DOMContentLoaded", () => {
  let volunteers = JSON.parse(localStorage.getItem("volunteers")) || [];
  if (volunteers.length > 0) {
    let v = volunteers[volunteers.length - 1]; 

    document.getElementById("infoName").textContent = v.fullName || "";
    document.getElementById("infoBirthPlace").textContent = v.birthPlace || "";
    document.getElementById("infoSex").textContent = v.sex || "";
    document.getElementById("infoDob").textContent = v.dob || "";
    document.getElementById("infoReligion").textContent = v.religion || "";
    document.getElementById("infoHeight").textContent = v.height || "";
    document.getElementById("infoWeight").textContent = v.weight || "";
    document.getElementById("infoCivilStatus").textContent = v.civilStatus || "";
    document.getElementById("infoSpouse").textContent = v.spouse || "";
    document.getElementById("infoChildren").textContent = v.children || "";
    document.getElementById("infoMobile").textContent = v.mobile || "";
    document.getElementById("infoLandline").textContent = v.landline || "";
    document.getElementById("infoAddress").textContent = v.address || "";
    document.getElementById("infoHealth").textContent = v.health || "";
    document.getElementById("infoMedication").textContent = v.medication || "";
    document.getElementById("infoBloodType").textContent = v.bloodType || "";

    if (v.profilePic) {
      document.getElementById("profileImage").src = v.profilePic;
    }
  }

  //--------- PROFILE IMAGE UPLOAD ----------
  const fileInput = document.getElementById("fileInput");
  const uploadBtn = document.getElementById("uploadBtn");
  const profileImage = document.getElementById("profileImage");

  uploadBtn.addEventListener("click", () => fileInput.click());

  //--------- UPDATE IMAGE + SAVE ----------
  fileInput.addEventListener("change", function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        profileImage.src = e.target.result;

        if (volunteers.length > 0) {
          volunteers[volunteers.length - 1].profilePic = e.target.result;
          localStorage.setItem("volunteers", JSON.stringify(volunteers));
        }
      };
      reader.readAsDataURL(file);
    }
  });
});
