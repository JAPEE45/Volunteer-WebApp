<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style/register.css">
  <title>Red Cross Volunteer Registration</title>
</head>
<body>
  <div class="header">
    <img src="img/Philippine_Red_Cross_logo.jpg" alt="Red Cross">
    <h2>RED CROSS VOLUNTEER REGISTRATION</h2>
  </div>

  <form id="registrationForm" class="form-table">

    <h3>Personal Information</h3>
    <div class="grid">
      <div><label>First Name</label><input name="firstname" type="text" id="firstName" required></div>
      <div><label>Middle Name</label><input name="middlename" type="text" id="middleName"></div>
      <div><label>Last Name</label><input name="lastname" type="text" id="lastName" required></div>
      <div><label>Birth Place</label><input name="birthplace" type="text" id="birthPlace"></div>
      <div><label>Sex</label>
        <select name="sex" id="sex" required>
          <option value="">-- Select --</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>
      <div><label>Date of Birth</label><input name="date_of_birth" type="date" id="dob"></div>
      <div><label>Age</label><input name="age" type="number" id="age"></div>
      <div><label>Religion</label><input name="religion" type="text" id="religion"></div>
      <div><label>Height (cm)</label><input name="height" type="number" id="height"></div>
      <div><label>Weight (kg)</label><input name="weight" type="number" id="weight"></div>
      <div><label>Civil Status</label><input name="civil_status" type="text" id="civilStatus"></div>
      <div><label>Spouse Name</label><input name type="text" id="spouse"></div>
      <div><label>Number of Children</label><input type="number" id="children"></div>
      <div><label>Mobile Number</label><input type="text" id="mobile"></div>
      <div><label>Landline Number</label><input type="text" id="landline"></div>
      <div class="wide"><label>Complete Address</label><input type="text" id="address"></div>
    </div>


    <h3>Medical</h3>
    <div class="grid">
      <div><label>Health conditions / Allergies</label><input type="text" id="health"></div>
      <div><label>Current Medication</label><input type="text" id="medication"></div>
      <div><label>Blood Type</label><input type="text" id="bloodType"></div>
    </div>

    <h3>Educational Background</h3>
    <div class="grid">
      <div><label>Elementary</label><input type="text" id="elementary"></div>
      <div><label>Year Graduated</label><input type="text" id="elemYearGrad"></div>
      <div><label>High School</label><input type="text" id="highSchool"></div>
      <div><label>Year Graduated</label><input type="text" id="hsYearGrad"></div>
      <div><label>College / Course</label><input type="text" id="college"></div>
      <div><label>Year Graduated</label><input type="text" id="collegeYearGrad"></div>
      <div><label>Post Graduate</label><input type="text" id="postGrad"></div>
      <div><label>Year Graduated</label><input type="text" id="postGradYear"></div>
    </div>

    <h3>Talents & Skills</h3>
    <div class="grid">
      <div class="wide"><label>What skills do you possess?</label><input type="text" id="skills"></div>
      <div class="wide"><label>Languages / Dialects spoken</label><input type="text" id="languages"></div>
    </div>

    <h3>Socio-civic, Cultural & Religious Involvements</h3>
    <div class="grid">
      <div class="wide"><label>Affiliation / Position</label><input type="text" id="involvements"></div>
    </div>

    <h3>Work Experience</h3>
    <div class="grid">
      <div><label>Company Name</label><input type="text" id="company"></div>
      <div><label>Position</label><input type="text" id="position"></div>
      <div><label>Inclusive Dates</label><input type="text" id="workDates"></div>
    </div>

    <h3>Red Cross Experience</h3>
    <div class="grid">
      <div><label>Have you been a Red Cross Member?</label>
        <select id="redCrossMember">
          <option value="">-- Select --</option>
          <option value="Yes">Yes</option>
          <option value="No">No</option>
        </select>
      </div>
      <div><label>Membership Type</label><input type="text" id="membershipType"></div>
      <div class="wide"><label>Trainings Attended</label><input type="text" id="trainings"></div>
    </div>

    <h3>References</h3>
    <div class="grid">
      <div><label>Name</label><input type="text" id="refName"></div>
      <div><label>Contact Number</label><input type="text" id="refContact"></div>
    </div>

    <h3>Supporting Documents</h3>
    <div class="grid">
      <div class="wide">
        <label>Upload Certificates / ID / Documents</label>
        <input type="file" id="documents" multiple>
      </div>
    </div>

    <button type="submit" class="submit-btn">SUBMIT</button>
  </form>

  <script src="script/register.js"></script>
</body>
</html>
