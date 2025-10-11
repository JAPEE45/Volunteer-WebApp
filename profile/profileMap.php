<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/profileMap.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <img src="../img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
        </div>
        <a href="../profile/profile.php">Profile</a>
        <a href="#">Map</a>
        <a href="../profile/profileSms.php">Sms</a>
        <a href="../profile/report.php">Report</a>
    </div>
    <button class="menu-toggle" id="menu-toggle">☰</button>

    <div class="content">
        <h1>VOLUNTEERS MAP</h1>
        <div id="map"></div>
    </div>
</body>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="../profile/js/profileMap.js"></script>
</html>