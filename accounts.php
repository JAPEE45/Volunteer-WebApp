<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/accounts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>
   <div class="sidebar" id="sidebar">
    <div class="logo">
      <img src="img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
    </div>
    <a href="homePage.php"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a>
    <a href="volunteers.php"><i class="fas fa-users fa-fw"></i> Volunteers</a>
    <a href="accounts.php"><i class="fas fa-users fa-fw"></i>Accounts</a>
    <a href="events.php"><i class="fas fa-calendar-alt fa-fw"></i> Events</a>
    <a href="map.php"><i class="fas fa-map-marker-alt fa-fw"></i> Map</a>
    <a href="login.php"><i class="fas fa-sign-out-alt fa-fw"></i>Logout</a>
    <!-- <a href="sms.php"><i class="fas fa-envelope fa-fw"></i> SMS Alerts</a> -->

  </div>
  <button class="menu-toggle" id="menu-toggle">☰</button>

  <div class="content">
    <div class="table-header">
      <h1>Account Management</h1>
      <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search account...">
      </div>
    </div>

    <table id="accountsTable" border="1">
      <thead>
        <tr>
          <th>Username</th>
          <th>Password</th>
          <th>Created</th>
          <th>Last Login</th>
          <th>Role</th>
          <th>Linked User</th>
          <th>Account Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

  <!-- USER DETAILS MODAL -->
  <div id="userModal" class="modal" style="display:none;">
    <div class="modal-content">
      <h2 id="modalTitle">Account Details</h2>
      <div id="userDetails" class="details-container"></div>

      <div class="modal-actions">
        <button id="acceptBtn" class="confirm-btn">Accept</button>
        <button id="rejectBtn" class="reject-btn">Reject</button>
        <button onclick="closeUserModal()" class="close-btn">Close</button>
      </div>
    </div>
  </div>

  <style>
  .modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; z-index: 9999; }
  .modal-content { background: #fff; padding: 25px 30px; border-radius: 12px; width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 5px 20px rgba(0,0,0,0.2); }
  .modal-actions { margin-top: 20px; display: flex; justify-content: space-between; }
  .confirm-btn { background: #4CAF50; color: #fff; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; }
  .reject-btn { background: #E53935; color: #fff; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; }
  .close-btn { background: #757575; color: #fff; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; }
  </style>

</body>
 <script src="script/accounts.js"></script>
</html>
