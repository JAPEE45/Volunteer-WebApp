function loginUser() {
      let user = document.getElementById("username").value;
      let pass = document.getElementById("password").value;

      if(user === "admin" && pass === "1234") {
        window.location.href = "homePage.html";
      } else {
        alert("Invalid login!");
      }
      return false; 
    }