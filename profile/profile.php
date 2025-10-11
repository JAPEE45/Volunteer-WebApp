<?php
  include_once "../utility/db.php";
  session_start();
  $userId = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->execute([$userId]);
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  $stmt->close();
  $conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style/profile.css">
  <title>Volunteer Profile</title>
</head>
<body>
  <div class="sidebar" id="sidebar">
        <div class="logo">
            <img src="../img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
        </div>
        <a href="#">Profile</a>
        <a href="../profile/profileMap.php">Map</a>
        <a href="../profile/profileSms.php">Sms</a>
        <a href="../profile/report.php">Report</a>
    </div>
    <button class="menu-toggle" id="menu-toggle">☰</button>

  <div class="content-centered">
    <h1>Volunteer Profile</h1>

    <div class="profile-header">
      <img id="profileImage" src="img/default_profile.png" alt="Profile Picture" class="profile-pic">
      <input type="file" id="fileInput" accept="image/*" style="display:none">
      <button id="uploadBtn">Change Picture</button>
    </div>

    <section>
      <h2>Personal Information</h2>
      <div class="info-grid">
        <div><strong>Full Name:</strong><?php echo $row['fullName']?><span id="infoName"></span></div>
        <div><strong>Birth Place:</strong> <span id="infoBirthPlace"><?php echo $row['birthPlace']?></span></div>
        <div><strong>Sex:</strong> <span id="infoSex"><?php echo $row['sex']?></span></div>
        <div><strong>Date of Birth:</strong> <span id="infoDob"><?php echo $row['dob']?></span></div>
        <div><strong>Religion:</strong> <span id="infoReligion"><?php echo $row['religion']?></span></div>
        <div><strong>Civil Status:</strong> <span id="infoCivilStatus"><?php echo $row['civilStatus']?></span></div>
        <div><strong>Spouse:</strong> <span id="infoSpouse"><?php echo $row['spouse']?></span></div>
        <div><strong>Number of Children:</strong> <span id="infoChildren"><?php echo $row['children']?></span></div>
      </div>
    </section>

    <section>
      <h2>Physical Information</h2>
      <div class="info-grid">
        <div><strong>Height:</strong> <span id="infoHeight"><?php echo $row['height']?></span> cm</div>
        <div><strong>Weight:</strong> <span id="infoWeight"><?php echo $row['weight']?></span> kg</div>
        <div><strong>Blood Type:</strong> <span id="infoBloodType"><?php echo $row['bloodType']?></span></div>
      </div>
    </section>

    <section>
      <h2>Contact Information</h2>
      <div class="info-grid">
        <div><strong>Mobile:</strong> <span id="infoMobile"><?php echo $row['mobile']?></span></div>
        <div><strong>Landline:</strong> <span id="infoLandline"><?php echo $row['landline']?></span></div>
        <div class="full"><strong>Complete Address:</strong> <span id="infoAddress"><?php echo $row['address']?></span></div>
      </div>
    </section>

    <!-- Medical Info -->
    <section>
      <h2>Medical Information</h2>
      <div class="info-grid">
        <div><strong>Health Conditions / Allergies:</strong> <span id="infoHealth"><?php echo $row['health']?></span></div>
        <div><strong>Current Medication:</strong> <span id="infoMedication"></span><?php echo $row['medication']?></div>
      </div>
    </section>

    <section>
      <h2>Educational Background</h2>
      <div class="info-grid">
        <div><strong>Elementary:</strong> <span id="infoElementary"><?php echo $row['elementary']?></span></div>
        <div><strong>Year Graduated:</strong> <span id="infoElemYearGrad"><?php echo $row['elemYearGrad']?></span></div>
        <div><strong>High School:</strong> <span id="infoHighSchool"><?php echo $row['highSchool']?></span></div>
        <div><strong>Year Graduated:</strong> <span id="infoHsYearGrad"><?php echo $row['hsYearGrad']?></span></div>
        <div><strong>College / Course:</strong> <span id="infoCollege"><?php echo $row['college']?></span></div>
        <div><strong>Year Graduated:</strong> <span id="infoCollegeYearGrad"><?php echo $row['collegeYearGrad']?></span></div>
        <div><strong>Post Graduate:</strong> <span id="infoPostGrad"><?php echo $row['postGrad']?></span></div>
        <div><strong>Year Graduated:</strong> <span id="infoPostGradYear"><?php echo $row['postGradYear']?></span></div>
      </div>
    </section>

    <section>
      <h2>Talents & Skills</h2>
      <div class="info-grid">
        <div class="full"><strong>Skills:</strong> <span id="infoSkills"><?php echo $row['skills']?></span></div>
        <div class="full"><strong>Languages / Dialects:</strong> <span id="infoLanguages"><?php echo $row['languages']?></span></div>
      </div>
    </section>

    <section>
      <h2>Socio-Civic & Cultural Involvements</h2>
      <div class="info-grid">
        <div class="full"><strong>Affiliation / Position:</strong> <span id="infoInvolvements"><?php echo $row['involvements']?></span></div>
      </div>
    </section>

    <section>
      <h2>Work Experience</h2>
      <div class="info-grid">
        <div><strong>Company Name:</strong> <span id="infoCompany"><?php echo $row['company']?></span></div>
        <div><strong>Position:</strong> <span id="infoPosition"><?php echo $row['position']?></span></div>
        <div><strong>Inclusive Dates:</strong> <span id="infoWorkDates"><?php echo $row['workDates']?></span></div>
      </div>
    </section>

    <section>
      <h2>Red Cross Experience</h2>
      <div class="info-grid">
        <div><strong>Red Cross Member:</strong> <span id="infoRedCrossMember"><?php echo $row['redCrossMember']?></span></div>
        <div><strong>Membership Type:</strong> <span id="infoMembershipType"><?php echo $row['membershipType']?></span></div>
        <div class="full"><strong>Trainings Attended:</strong> <span id="infoTrainings"><?php echo $row['trainings']?></span></div>
      </div>
    </section>
    <section>
      <h2>References</h2>
      <div class="info-grid">
        <div><strong>Name:</strong> <span id="infoRefName"><?php echo $row['refName']?></span></div>
        <div><strong>Contact Number:</strong> <span id="infoRefContact"><?php echo $row['refContact']?></span></div>
      </div>
    </section>

  </div>

  <script src="../profile/js/profile.js"></script>
</body>
</html>
