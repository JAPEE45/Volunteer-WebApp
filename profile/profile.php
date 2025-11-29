<?php
  include "../utility/db.php";
  session_start();
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->execute([$user_id]);
  $re = $stmt->get_result();
  $user = $re->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Volunteer Profile - Philippine Red Cross</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
      background: linear-gradient(135deg, #fff5f5 0%, #fee 100%);
      min-height: 100vh;
      display: flex;
    }

    .sidebar {
      width: 280px;
      background: linear-gradient(180deg, #dc143c 0%, #a00000 100%);
      color: white;
      padding: 2rem 0;
      position: fixed;
      height: 100vh;
      left: 0;
      top: 0;
      box-shadow: 4px 0 20px rgba(220, 20, 60, 0.3);
      z-index: 1000;
      transition: transform 0.3s ease;
      overflow-y: auto;
    }

    .sidebar::-webkit-scrollbar {
      width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.1);
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.3);
      border-radius: 10px;
    }

    .sidebar-logo {
      text-align: center;
      padding: 0 1.5rem 2rem;
      border-bottom: 2px solid rgba(255, 255, 255, 0.2);
      margin-bottom: 2rem;
    }

    .logo-icon {
      font-size: 3.5rem;
      margin-bottom: 0.5rem;
    }

    .logo-text {
      font-size: 1.2rem;
      font-weight: 700;
      line-height: 1.4;
    }

    .logo-subtitle {
      font-size: 0.8rem;
      opacity: 0.8;
      margin-top: 0.25rem;
    }

    .sidebar-nav {
      padding: 0 1rem;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem 1.5rem;
      margin-bottom: 0.5rem;
      border-radius: 12px;
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    .nav-item::before {
      content: "";
      position: absolute;
      left: 0;
      top: 0;
      width: 0;
      height: 100%;
      background: rgba(255, 255, 255, 0.15);
      transition: width 0.3s ease;
    }

    .nav-item:hover::before {
      width: 100%;
    }

    .nav-item:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateX(5px);
    }

    .nav-item.active {
      background: rgba(255, 255, 255, 0.2);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .nav-item.active::after {
      content: "";
      position: absolute;
      right: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 60%;
      background: white;
      border-radius: 10px 0 0 10px;
    }

    .nav-icon {
      font-size: 1.5rem;
      min-width: 30px;
      text-align: center;
      position: relative;
      z-index: 1;
    }

    .nav-text {
      position: relative;
      z-index: 1;
    }

    .nav-logout {
      margin-top: 2rem;
      border-top: 2px solid rgba(255, 255, 255, 0.2);
      padding-top: 1rem;
    }

    .main-content {
      margin-left: 280px;
      flex: 1;
      padding: 2rem;
      width: calc(100% - 280px);
    }

    .profile-container {
      max-width: 1400px;
      margin: 0 auto;
    }

    .mobile-toggle {
      display: none;
      position: fixed;
      top: 1rem;
      left: 1rem;
      z-index: 1001;
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      border: none;
      width: 50px;
      height: 50px;
      border-radius: 12px;
      font-size: 1.5rem;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.4);
      transition: all 0.3s ease;
    }

    .mobile-toggle:hover {
      transform: scale(1.05);
    }

    .sidebar-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(5px);
      z-index: 999;
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .sidebar-overlay.active {
        display: block;
      }

      .main-content {
        margin-left: 0;
        width: 100%;
        padding: 1rem;
        padding-top: 5rem;
      }

      .mobile-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
      }
    }

    .page-header {
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      padding: 2rem;
      border-radius: 20px;
      margin-bottom: 2rem;
      box-shadow: 0 10px 30px rgba(220, 20, 60, 0.3);
      position: relative;
      overflow: hidden;
    }

    .page-header::before {
      content: "👤";
      position: absolute;
      font-size: 15rem;
      opacity: 0.05;
      right: -50px;
      top: -50px;
      transform: rotate(15deg);
    }

    .header-content {
      position: relative;
      z-index: 1;
    }

    .page-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .page-subtitle {
      opacity: 0.9;
      font-size: 1.1rem;
    }

    .profile-header-card {
      background: white;
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border: 3px solid #dc143c;
      display: flex;
      align-items: center;
      gap: 2rem;
      flex-wrap: wrap;
    }

    .profile-pic-container {
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1rem;
    }

    .profile-pic {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 5px solid #dc143c;
      box-shadow: 0 8px 20px rgba(220, 20, 60, 0.3);
      transition: all 0.3s ease;
    }

    .profile-pic:hover {
      transform: scale(1.05);
      box-shadow: 0 12px 30px rgba(220, 20, 60, 0.4);
    }

    .upload-btn {
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
    }

    .upload-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(220, 20, 60, 0.4);
    }

    .profile-summary {
      flex: 1;
      min-width: 300px;
    }

    .volunteer-name {
      font-size: 2rem;
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 0.5rem;
    }

    .volunteer-id {
      font-size: 1.1rem;
      color: #64748b;
      margin-bottom: 1rem;
    }

    .quick-stats {
      display: flex;
      gap: 1.5rem;
      flex-wrap: wrap;
    }

    .quick-stat {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1.25rem;
      background: linear-gradient(135deg, #fff5f5 0%, #fee 100%);
      border-radius: 12px;
      border-left: 4px solid #dc143c;
    }

    .quick-stat-icon {
      font-size: 1.5rem;
    }

    .quick-stat-label {
      font-size: 0.85rem;
      color: #64748b;
      font-weight: 600;
    }

    .quick-stat-value {
      font-size: 1.1rem;
      font-weight: 700;
      color: #dc143c;
    }

    .section-card {
      background: white;
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .section-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(220, 20, 60, 0.15);
    }

    .section-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding-bottom: 1rem;
      border-bottom: 3px solid #fee;
    }

    .section-title::before {
      content: "";
      width: 4px;
      height: 30px;
      background: linear-gradient(180deg, #dc143c 0%, #ff4757 100%);
      border-radius: 10px;
    }

    .section-icon {
      font-size: 1.8rem;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.25rem;
    }

    .info-item {
      background: linear-gradient(135deg, #fff 0%, #fff5f5 100%);
      padding: 1.25rem;
      border-radius: 12px;
      border-left: 4px solid #dc143c;
      transition: all 0.3s ease;
    }

    .info-item:hover {
      transform: translateX(5px);
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.15);
    }

    .info-item.full-width {
      grid-column: 1 / -1;
    }

    .info-label {
      font-size: 0.85rem;
      font-weight: 700;
      color: #dc143c;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .info-value {
      font-size: 1.05rem;
      color: #2d3748;
      font-weight: 600;
      line-height: 1.5;
    }

    @media (max-width: 768px) {
      .page-title {
        font-size: 1.8rem;
      }

      .profile-header-card {
        flex-direction: column;
        text-align: center;
      }

      .volunteer-name {
        font-size: 1.5rem;
      }

      .quick-stats {
        justify-content: center;
      }

      .info-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <!-- Mobile Toggle Button -->
  <button class="mobile-toggle" onclick="toggleSidebar()">☰</button>

  <!-- Sidebar Overlay for Mobile -->
  <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

  <!-- Sidebar -->
  <nav class="sidebar" id="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon">✚</div>
      <div class="logo-text">Philippine Red Cross</div>
      <div class="logo-subtitle">Volunteer Portal</div>
    </div>

  <div class="sidebar-nav">
      <a href="dashboard.php" class="nav-item">
        <span class="nav-icon">📊</span>
        <span class="nav-text">Dashboard</span>
      </a>
      <a href="profile.php" class="nav-item active">
        <span class="nav-icon">👤</span>
        <span class="nav-text">Profile</span>
      </a>
      <a href="profileMap.php" class="nav-item">
        <span class="nav-icon">🗺️</span>
        <span class="nav-text">Map</span>
      </a>
      <a href="report.php" class="nav-item">
        <span class="nav-icon">📝</span>
        <span class="nav-text">Report</span>
      </a>
      
      <div class="nav-logout">
        <a href="#logout" class="nav-item logout" onclick="handleLogout()">
          <span class="nav-icon">🚪</span>
          <span class="nav-text">Logout</span>
        </a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="main-content">
    <div class="profile-container">
      <!-- Page Header -->
      <div class="page-header">
        <div class="header-content">
          <div class="page-title">
            <span>👤</span>
            <span>Volunteer Profile</span>
          </div>
          <div class="page-subtitle">
            Manage and view your complete volunteer information
          </div>
        </div>
      </div>

      <!-- Profile Header Card -->
      <div class="profile-header-card">
        <!-- <div class="profile-pic-container">
          <img id="profileImage" src="img/default_profile.png" alt="Profile Picture" class="profile-pic">
          <input type="file" id="fileInput" accept="image/*" style="display:none">
          <button class="upload-btn" id="uploadBtn">📷 Change Picture</button>
        </div> -->
        <div class="profile-summary">
          <div class="volunteer-name" id="infoName"><?php echo $user['fullName'] ?></div>
          <div class="volunteer-id">Volunteer ID: L-2025-0001</div>
          <div class="quick-stats">
            <div class="quick-stat">
              <span class="quick-stat-icon">🩸</span>
              <div>
                <div class="quick-stat-label">Blood Type</div>
                <div class="quick-stat-value" id="quickBloodType"><?php echo $user['bloodType'] ?></div>
              </div>
            </div>
            <div class="quick-stat">
              <span class="quick-stat-icon">📞</span>
              <div>
                <div class="quick-stat-label">Mobile</div>
                <div class="quick-stat-value" id="quickMobile"><?php echo $user['mobile'] ?></div>
              </div>
            </div>
            <div class="quick-stat">
              <span class="quick-stat-icon">✅</span>
              <div>
                <div class="quick-stat-label">Status</div>
                <div class="quick-stat-value"><?php echo $user['status'] ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Personal Information -->
      <div class="section-card">
        <h2 class="section-title">
          <span class="section-icon">📋</span>
          Personal Information
        </h2>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">👤 Full Name</div>
            <div class="info-value" id="infoName2"><?php echo $user['fullName'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">📍 Birth Place</div>
            <div class="info-value" id="infoBirthPlace"><?php echo $user['birthPlace'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">⚧ Sex</div>
            <div class="info-value" id="infoSex"><?php echo $user['sex'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">🎂 Date of Birth</div>
            <div class="info-value" id="infoDob"><?php echo $user['dob'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">🕊️ Religion</div>
            <div class="info-value" id="infoReligion"><?php echo $user['religion'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">💍 Civil Status</div>
            <div class="info-value" id="infoCivilStatus"><?php echo $user['civilStatus'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">👫 Spouse</div>
            <div class="info-value" id="infoSpouse"><?php echo $user['spouse'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">👶 Number of Children</div>
            <div class="info-value" id="infoChildren"><?php echo $user['children'] ?></div>
          </div>
        </div>
      </div>

      <!-- Physical Information -->
      <div class="section-card">
        <h2 class="section-title">
          <span class="section-icon">📏</span>
          Physical Information
        </h2>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">📐 Height</div>
            <div class="info-value" id="infoHeight"><?php echo $user['height'] ?> cm</div>
          </div>
          <div class="info-item">
            <div class="info-label">⚖️ Weight</div>
            <div class="info-value" id="infoWeight"><?php echo $user['weight'] ?> kg</div>
          </div>
          <div class="info-item">
            <div class="info-label">🩸 Blood Type</div>
            <div class="info-value" id="infoBloodType"><?php echo $user['bloodType'] ?></div>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="section-card">
        <h2 class="section-title">
          <span class="section-icon">📞</span>
          Contact Information
        </h2>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">📱 Mobile Number</div>
            <div class="info-value" id="infoMobile"><?php echo $user['mobile'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">☎️ Landline</div>
            <div class="info-value" id="infoLandline"><?php echo $user['landline'] ?></div>
          </div>
          <div class="info-item full-width">
            <div class="info-label">🏠 Complete Address</div>
            <div class="info-value" id="infoAddress"><?php echo $user['address'] ?></div>
          </div>
        </div>
      </div>

      <!-- Medical Information -->
      <div class="section-card">
        <h2 class="section-title">
          <span class="section-icon">🏥</span>
          Medical Information
        </h2>
        <div class="info-grid">
          <div class="info-item full-width">
            <div class="info-label">⚕️ Health Conditions / Allergies</div>
            <div class="info-value" id="infoHealth"><?php echo $user['health'] || "No report" ?></div>
          </div>
          <div class="info-item full-width">
            <div class="info-label">💊 Current Medication</div>
            <div class="info-value" id="infoMedication"><?php echo $user['medication'] ?></div>
          </div>
        </div>
      </div>

      <!-- Educational Background -->
      <div class="section-card">
        <h2 class="section-title">
          <span class="section-icon">🎓</span>
          Educational Background
        </h2>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">📚 Elementary</div>
            <div class="info-value" id="infoElementary"><?php echo $user['elementary'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">📅 Year Graduated</div>
            <div class="info-value" id="infoElemYearGrad"><?php echo $user['elemYearGrad'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">🏫 High School</div>
            <div class="info-value" id="infoHighSchool"><?php echo $user['highSchool'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">📅 Year Graduated</div>
            <div class="info-value" id="infoHsYearGrad"><?php echo $user['hsYearGrad'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">🎓 College / Course</div>
            <div class="info-value" id="infoCollege"><?php echo $user['college'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">📅 Year Graduated</div>
            <div class="info-value" id="infoCollegeYearGrad"><?php echo $user['collegeYearGrad'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">📖 Post Graduate</div>
            <div class="info-value" id="infoPostGrad"><?php echo $user['postGrad'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">📅 Year Graduated</div>
            <div class="info-value" id="infoPostGradYear"><?php echo $user['postGradYear'] ?></div>
          </div>
        </div>
      </div>

      <!-- Talents & Skills -->
      <div class="section-card">
        <h2 class="section-title">
          <span class="section-icon">⭐</span>
          Talents & Skills
        </h2>
        <div class="info-grid">
          <div class="info-item full-width">
            <div class="info-label">🛠️ Skills</div>
            <div class="info-value" id="infoSkills"><?php echo $user['skills'] ?></div>
          </div>
          <div class="info-item full-width">
            <div class="info-label">🌐 Languages / Dialects</div>
            <div class="info-value" id="infoLanguages"><?php echo $user['languages'] ?></div>
          </div>
        </div>
      </div>

      <!-- Socio-Civic & Cultural Involvements -->
      <div class="section-card">
        <h2 class="section-title">
          <span class="section-icon">🤝</span>
          Socio-Civic & Cultural Involvements
        </h2>
        <div class="info-grid">
          <div class="info-item full-width">
            <div class="info-label">🏛️ Affiliation / Position</div>
            <div class="info-value" id="infoInvolvements"><?php echo $user['involvements'] ?></div>
          </div>
        </div>
      </div>

      <!-- Work Experience -->
      <div class="section-card">
        <h2 class="section-title">
          <span class="section-icon">💼</span>
          Work Experience
        </h2>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">🏢 Company Name</div>
            <div class="info-value" id="infoCompany"><?php echo $user['company'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">👨‍⚕️ Position</div>
            <div class="info-value" id="infoPosition"><?php echo $user['position'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">📅 Inclusive Dates</div>
            <div class="info-value" id="infoWorkDates"><?php echo $user['workDates'] ?></div>
          </div>
        </div>
      </div>

      <!-- Red Cross Experience -->
      <div class="section-card">
        <h2 class="section-title">
          <span class="section-icon">✚</span>
          Red Cross Experience
        </h2>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">🎯 Red Cross Member</div>
            <div class="info-value" id="infoRedCrossMember"><?php echo $user['redCrossMember'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">🏅 Membership Type</div>
            <div class="info-value" id="infoMembershipType"><?php echo $user['membershipType'] ?></div>
          </div>
          <div class="info-item full-width">
            <div class="info-label">📜 Trainings Attended</div>
            <div class="info-value" id="infoTrainings"><?php echo $user['trainings'] ?></div>
          </div>
        </div>
      </div>

      <!-- References -->
      <div class="section-card">
        <h2 class="section-title">
          <span class="section-icon">👥</span>
          References
        </h2>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">👤 Name</div>
            <div class="info-value" id="infoRefName"><?php echo $user['refName'] ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">📞 Contact Number</div>
            <div class="info-value" id="infoRefContact"><?php echo $user['refContact'] && "N/A" ?></div>
          </div>
        </div>
      </div>

    </div>
  </main>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.querySelector('.sidebar-overlay');
      sidebar.classList.toggle('active');
      overlay.classList.toggle('active');
    }

    function handleLogout() {
      if (confirm('Are you sure you want to logout?')) {
        console.log('Logging out...');
        window.location.href = 'logout.php';
      }
    }

    document.getElementById('uploadBtn').addEventListener('click', function() {
      document.getElementById('fileInput').click();
    });

    document.getElementById('fileInput').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          document.getElementById('profileImage').src = event.target.result;
        };
        reader.readAsDataURL(file);
      }
    });

    function loadProfileData() {
      const data = {
        name: "Juan Dela Cruz",
        birthPlace: "Masbate City",
        sex: "Male",
        dob: "January 15, 1995",
        religion: "Roman Catholic",
        civilStatus: "Single",
        spouse: "N/A",
        children: "0",
        height: "170 cm",
        weight: "65 kg",
        bloodType: "O+",
        mobile: "0912-345-6789",
        landline: "(056) 333-1234",
        address: "123 Quezon Street, Masbate City, Masbate Province 5400",
        health: "None reported",
        medication: "None",
        elementary: "Masbate Central School",
        elemYearGrad: "2007",
        highSchool: "Masbate National Comprehensive High School",
        hsYearGrad: "2011",
        college: "Bicol University - Bachelor of Science in Nursing",
        collegeYearGrad: "2015",
        postGrad: "N/A",
        postGradYear: "N/A",
        skills: "First Aid, CPR, Emergency Medical Response, Community Health Education, Disaster Relief Coordination",
        languages: "Filipino, English, Bicolano, Cebuano",
        involvements: "Barangay Health Committee Member, Community Disaster Response Team Coordinator",
        company: "Masbate Provincial Hospital",
        position: "Staff Nurse",
        workDates: "June 2015 - Present",
        redCrossMember: "Yes",
        membershipType: "Active Volunteer",
        trainings: "Basic Life Support (BLS), First Aid & CPR, Disaster Risk Reduction Management, Psychological First Aid, Search and Rescue Operations",
        refName: "Dr. Maria Santos",
        refContact: "0917-888-9999"
      };

      // Update all profile fields
      document.getElementById('infoName').textContent = data.name;
      document.getElementById('infoName2').textContent = data.name;
      document.getElementById('infoBirthPlace').textContent = data.birthPlace;
      document.getElementById('infoSex').textContent = data.sex;
      document.getElementById('infoDob').textContent = data.dob;
      document.getElementById('infoReligion').textContent = data.religion;
      document.getElementById('infoCivilStatus').textContent = data.civilStatus;
      document.getElementById('infoSpouse').textContent = data.spouse;
      document.getElementById('infoChildren').textContent = data.children;
      document.getElementById('infoHeight').textContent = data.height;
      document.getElementById('infoWeight').textContent = data.weight;
      document.getElementById('infoBloodType').textContent = data.bloodType;
      document.getElementById('quickBloodType').textContent = data.bloodType;
      document.getElementById('infoMobile').textContent = data.mobile;
      document.getElementById('quickMobile').textContent = data.mobile;
      document.getElementById('infoLandline').textContent = data.landline;
      document.getElementById('infoAddress').textContent = data.address;
      document.getElementById('infoHealth').textContent = data.health;
      document.getElementById('infoMedication').textContent = data.medication;
      document.getElementById('infoElementary').textContent = data.elementary;
      document.getElementById('infoElemYearGrad').textContent = data.elemYearGrad;
      document.getElementById('infoHighSchool').textContent = data.highSchool;
      document.getElementById('infoHsYearGrad').textContent = data.hsYearGrad;
      document.getElementById('infoCollege').textContent = data.college;
      document.getElementById('infoCollegeYearGrad').textContent = data.collegeYearGrad;
      document.getElementById('infoPostGrad').textContent = data.postGrad;
      document.getElementById('infoPostGradYear').textContent = data.postGradYear;
      document.getElementById('infoSkills').textContent = data.skills;
      document.getElementById('infoLanguages').textContent = data.languages;
      document.getElementById('infoInvolvements').textContent = data.involvements;
      document.getElementById('infoCompany').textContent = data.company;
      document.getElementById('infoPosition').textContent = data.position;
      document.getElementById('infoWorkDates').textContent = data.workDates;
      document.getElementById('infoRedCrossMember').textContent = data.redCrossMember;
      document.getElementById('infoMembershipType').textContent = data.membershipType;
      document.getElementById('infoTrainings').textContent = data.trainings;
      document.getElementById('infoRefName').textContent = data.refName;
      document.getElementById('infoRefContact').textContent = data.refContact;
    }

    // document.addEventListener('DOMContentLoaded', loadProfileData);
  </script>
</body>
</html>