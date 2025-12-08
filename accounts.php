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
    <a href="adminActivityReports.php"><i class="fas fa-file-alt fa-fw"></i> Activity Reports</a>
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
        <button id="evaluateBtn" class="evaluate-btn">Evaluate</button>
        <button id="acceptBtn" class="confirm-btn">Accept</button>
        <button id="rejectBtn" class="reject-btn">Reject</button>
        <button onclick="closeUserModal()" class="close-btn">Close</button>
      </div>
    </div>
  </div>

  <!-- EVALUATION MODAL -->
  <div id="evaluationModal" class="modal" style="display:none;">
    <div class="modal-content evaluation-form">
      <h2>Volunteer Evaluation</h2>
      <p class="eval-subtitle">Evaluate the volunteer's readiness and capabilities</p>

      <form id="evaluationForm">
        <input type="hidden" id="evalUserId" name="user_id">
        
        <div class="rating-section">
          <div class="rating-item">
            <label>Physical Fitness</label>
            <div class="rating-control">
              <input type="range" id="physicalFitness" name="physical_fitness" min="1" max="5" step="0.5" value="3">
              <span class="rating-value">3.0</span>
            </div>
          </div>

          <div class="rating-item">
            <label>Communication Skills</label>
            <div class="rating-control">
              <input type="range" id="communicationSkills" name="communication_skills" min="1" max="5" step="0.5" value="3">
              <span class="rating-value">3.0</span>
            </div>
          </div>

          <div class="rating-item">
            <label>Teamwork</label>
            <div class="rating-control">
              <input type="range" id="teamwork" name="teamwork" min="1" max="5" step="0.5" value="3">
              <span class="rating-value">3.0</span>
            </div>
          </div>

          <div class="rating-item">
            <label>Reliability</label>
            <div class="rating-control">
              <input type="range" id="reliability" name="reliability" min="1" max="5" step="0.5" value="3">
              <span class="rating-value">3.0</span>
            </div>
          </div>
        </div>

        <div class="overall-rating">
          <label>Overall Rating</label>
          <div class="overall-value" id="overallRating">3.0</div>
          <div class="rating-stars" id="ratingStars">★★★☆☆</div>
        </div>

        <div class="form-group">
          <label for="evaluationComments">Comments & Recommendations</label>
          <textarea id="evaluationComments" name="comments" rows="4" placeholder="Add any additional notes or recommendations..."></textarea>
        </div>

        <div class="modal-actions">
          <button type="submit" class="confirm-btn">Submit Evaluation</button>
          <button type="button" onclick="closeEvaluationModal()" class="close-btn">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <style>
  .modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; z-index: 9999; }
  .modal-content { background: #fff; padding: 25px 30px; border-radius: 12px; width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 5px 20px rgba(0,0,0,0.2); }
  .modal-actions { margin-top: 20px; display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
  .confirm-btn { background: #4CAF50; color: #fff; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; }
  .confirm-btn:hover { background: #45a049; }
  .reject-btn { background: #E53935; color: #fff; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; }
  .reject-btn:hover { background: #d32f2f; }
  .close-btn { background: #757575; color: #fff; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; }
  .close-btn:hover { background: #616161; }
  .evaluate-btn { background: #2196F3; color: #fff; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; }
  .evaluate-btn:hover { background: #1976D2; }
  
  /* Evaluation Form Styles */
  .evaluation-form { max-width: 700px; }
  .eval-subtitle { color: #666; margin-bottom: 20px; font-size: 0.95rem; }
  .rating-section { margin: 20px 0; }
  .rating-item { margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #dc143c; }
  .rating-item label { display: block; font-weight: 600; color: #333; margin-bottom: 10px; }
  .rating-control { display: flex; align-items: center; gap: 15px; }
  .rating-control input[type="range"] { flex: 1; height: 8px; border-radius: 5px; background: linear-gradient(to right, #dc143c 0%, #dc143c 50%, #ddd 50%, #ddd 100%); outline: none; -webkit-appearance: none; }
  .rating-control input[type="range"]::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 20px; height: 20px; border-radius: 50%; background: #dc143c; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
  .rating-control input[type="range"]::-moz-range-thumb { width: 20px; height: 20px; border-radius: 50%; background: #dc143c; cursor: pointer; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
  .rating-value { font-size: 1.2rem; font-weight: 700; color: #dc143c; min-width: 40px; text-align: center; }
  .overall-rating { text-align: center; padding: 25px; background: linear-gradient(135deg, #dc143c 0%, #a00000 100%); color: white; border-radius: 12px; margin: 25px 0; }
  .overall-rating label { font-size: 1rem; font-weight: 600; margin-bottom: 10px; display: block; text-transform: uppercase; letter-spacing: 1px; }
  .overall-value { font-size: 3rem; font-weight: 700; margin: 10px 0; }
  .rating-stars { font-size: 2rem; letter-spacing: 5px; }
  .form-group { margin: 20px 0; }
  .form-group label { display: block; font-weight: 600; color: #333; margin-bottom: 8px; }
  .form-group textarea { width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 0.95rem; resize: vertical; }
  .form-group textarea:focus { outline: none; border-color: #dc143c; }
  .evaluation-badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; margin-left: 10px; }
  .evaluation-badge.evaluated { background: #4CAF50; color: white; }
  .evaluation-badge.not-evaluated { background: #ff9800; color: white; }
  </style>

</body>
 <script src="script/accounts.js"></script>
</html>
