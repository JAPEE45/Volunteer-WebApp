// var map = L.map('map').setView([13.5844, 124.2372], 11);
// L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//   attribution: '© OpenStreetMap'
// }).addTo(map);

// const locations = [
//   { name: "Virac, Catanduanes", coords: [13.5844, 124.2372] },
//   { name: "San Andres", coords: [13.598, 124.091] },
//   { name: "Pandan", coords: [14.058, 124.167] },
//   { name: "San Miguel", coords: [13.788, 124.219] },
//   { name: "Bato", coords: [13.602, 124.317] },
//   { name: "Panganiban", coords: [14.093, 124.329] },
//   { name: "Gigmoto", coords: [13.781, 124.390] },
//   { name: "Viga", coords: [13.884, 124.300] }
// ];

// locations.forEach(loc => {
//   L.marker(loc.coords)
//     .addTo(map)
//     .bindPopup(`<b>${loc.name}</b>`);
// });


// ---------- SIDE NAV TOGGLE ----------
const toggleBtn = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

// Toggle sidebar open/close when menu button is clicked
toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});


// ---------- MODAL HANDLING ----------
function openModal() {
  document.getElementById("volunteerModal").style.display = "flex";
}

function closeModal() {
  document.getElementById("volunteerModal").style.display = "none";
}


// ---------- SAVE NEW VOLUNTEER ----------
function saveVolunteer() {
  const name = document.getElementById("volName").value.trim();
  const location = document.getElementById("volLocation").value.trim();

  if (name && location) {
    let volunteers = JSON.parse(localStorage.getItem("volunteers")) || [];
    const newVolunteer = {
      fullName: name,
      address: location,
      age: "Not provided",
      sex: "Not provided"
    };

    volunteers.push(newVolunteer);
    localStorage.setItem("volunteers", JSON.stringify(volunteers));
    addVolunteerToTable(newVolunteer, volunteers.length - 1);

    closeModal();
    document.getElementById("volName").value = "";
    document.getElementById("volLocation").value = "";
  }
}



// ---------- SEARCH VOLUNTEERS ----------
document.getElementById("searchInput").addEventListener("keyup", function () {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll("#volunteerTable tbody tr");

  rows.forEach(row => {
    const name = row.querySelector("td").textContent.toLowerCase();
    if (name.includes(filter)) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
});


// ---------- REMOVE VOLUNTEER ----------
async function removeVolunteer(id) {
  if (!confirm('Are you sure you want to delete this volunteer?')) {
    return;
  }
  
  try {
    const response = await fetch('./utility/deleteAccountAndUser.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id })
    });
    
    const result = await response.text();
    alert(result);
    renderTable();
  } catch (error) {
    console.error('Error deleting volunteer:', error);
    alert('Failed to delete volunteer');
  }
}

// Volunteer roles for deployment
const volunteerRoles = [
  'First Aider',
  'Ambulance Driver',
  'Logistics Support',
  'Communications Officer',
  'Search and Rescue',
  'Medical Assistant',
  'Crowd Control',
  'Documentation',
  'Relief Distribution',
  'Psychosocial Support'
];

// Deploy Modal HTML - Single volunteer deployment with role
const deployModalHTML = `
  <div id="deployModalOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(5px); z-index: 9999; align-items: center; justify-content: center; animation: fadeIn 0.3s ease;">
    <div style="background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); width: 550px; max-width: 90%; height: auto; max-height: 90vh; display: flex; flex-direction: column; animation: slideUp 0.4s ease; position: relative;">
      
      <div style="background: linear-gradient(135deg, #dc143c 0%, #a00000 100%); color: white; padding: 2rem; position: relative; overflow: hidden; flex-shrink: 0;">
        <div style="content: '✚'; position: absolute; font-size: 8rem; opacity: 0.1; right: -20px; top: -20px; transform: rotate(15deg);">✚</div>
        
        <button onclick="closeDeployModal()" style="position: absolute; top: 1.5rem; right: 1.5rem; background: rgba(255, 255, 255, 0.2); border: 2px solid white; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; line-height: 1;">&times;</button>
        
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
          <span style="font-size: 2rem;">🚑</span>
          <span>Deploy Volunteer</span>
          <span id="deployEventCount" style="display: inline-block; background: rgba(255, 255, 255, 0.2); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;"></span>
        </h2>
        <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem; opacity: 0.9;">Assign volunteer to emergency response event</p>
      </div>
      
      <div style="padding: 2rem; flex: 1; overflow-y: auto; min-height: 0;">
        <div style="background: linear-gradient(135deg, #fff5f5 0%, #fee 100%); border: 2px solid #dc143c; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem; position: relative; overflow: hidden;">
          <div style="content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: linear-gradient(180deg, #dc143c 0%, #ff4757 100%);"></div>
          
          <h3 id="deployVolunteerName" style="font-size: 1.3rem; font-weight: 700; color: #dc143c; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <span style="font-size: 1.5rem;">👤</span>
            <span>Loading...</span>
          </h3>
          <p style="font-size: 0.85rem; color: #666; margin: 0.25rem 0 0 0; font-weight: 500;">ID: <span id="deployVolunteerId"></span></p>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
          <label for="eventSelect" style="display: block; font-size: 0.9rem; font-weight: 700; color: #2d3748; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">
            <span style="color: #dc143c; font-size: 1.2rem; margin-right: 0.5rem;">▪</span>
            <span>Select Event</span>
          </label>
          <select id="eventSelect" style="width: 100%; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; font-weight: 500; color: #2d3748; background: white; cursor: pointer; transition: all 0.3s ease; appearance: none; background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2724%27 height=%2724%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%23dc143c%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3E%3Cpolyline points=%276 9 12 15 18 9%27%3E%3C/polyline%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 20px; padding-right: 3rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;">
            <option value="">-- Choose an event --</option>
          </select>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
          <label for="roleSelect" style="display: block; font-size: 0.9rem; font-weight: 700; color: #2d3748; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">
            <span style="color: #dc143c; font-size: 1.2rem; margin-right: 0.5rem;">▪</span>
            <span>Assign Role</span>
          </label>
          <select id="roleSelect" style="width: 100%; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; font-weight: 500; color: #2d3748; background: white; cursor: pointer; transition: all 0.3s ease; appearance: none; background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2724%27 height=%2724%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%23dc143c%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3E%3Cpolyline points=%276 9 12 15 18 9%27%3E%3C/polyline%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 20px; padding-right: 3rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;">
            <option value="">-- Choose a role --</option>
          </select>
        </div>
      </div>
      
      <div style="padding: 1.5rem 2rem; background: #f7fafc; border-top: 2px solid #e2e8f0; display: flex; gap: 1rem; justify-content: flex-end; flex-wrap: wrap; flex-shrink: 0;">
        <button onclick="closeDeployModal()" style="padding: 0.875rem 2rem; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 0.5rem; background: white; color: #64748b; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
          <span>✕</span>
          <span>Cancel</span>
        </button>
        <button onclick="confirmDeploy()" id="mainDeployButton" style="padding: 0.875rem 2rem; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; display: flex !important; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #dc143c 0%, #a00000 100%); color: white; box-shadow: 0 4px 12px rgba(220, 20, 60, 0.4); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; visibility: visible !important; opacity: 1 !important;">
          <span>🚀</span>
          <span>Deploy Now</span>
        </button>
      </div>
    </div>
  </div>
  
  <!-- Batch Deploy Modal for Multiple Volunteers -->
  <div id="batchDeployModalOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(5px); z-index: 9999; align-items: center; justify-content: center; animation: fadeIn 0.3s ease;">
    <div style="background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); max-width: 700px; width: 90%; max-height: 90vh; overflow: hidden; animation: slideUp 0.4s ease; position: relative;">
      
      <div style="background: linear-gradient(135deg, #dc143c 0%, #a00000 100%); color: white; padding: 2rem; position: relative; overflow: hidden;">
        <div style="content: '✚'; position: absolute; font-size: 8rem; opacity: 0.1; right: -20px; top: -20px; transform: rotate(15deg);">✚</div>
        
        <button onclick="closeBatchDeployModal()" style="position: absolute; top: 1.5rem; right: 1.5rem; background: rgba(255, 255, 255, 0.2); border: 2px solid white; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; line-height: 1;">&times;</button>
        
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
          <span style="font-size: 2rem;">👥</span>
          <span>Batch Deploy Volunteers</span>
          <span id="batchVolunteerCount" style="display: inline-block; background: rgba(255, 255, 255, 0.2); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">0 Selected</span>
        </h2>
        <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem; opacity: 0.9;">Deploy multiple volunteers with assigned roles</p>
      </div>
      
      <div style="padding: 2rem; max-height: 50vh; overflow-y: auto;">
        <div style="margin-bottom: 1.5rem;">
          <label for="batchEventSelect" style="display: block; font-size: 0.9rem; font-weight: 700; color: #2d3748; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">
            <span style="color: #dc143c; font-size: 1.2rem; margin-right: 0.5rem;">▪</span>
            <span>Select Event for All</span>
          </label>
          <select id="batchEventSelect" style="width: 100%; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; font-weight: 500; color: #2d3748; background: white; cursor: pointer; transition: all 0.3s ease; font-family: inherit;">
            <option value="">-- Choose an event --</option>
          </select>
        </div>
        
        <div id="batchVolunteersList" style="display: flex; flex-direction: column; gap: 1rem;">
          <!-- Volunteer items will be added here dynamically -->
        </div>
      </div>
      
      <div style="padding: 1.5rem 2rem; background: #f7fafc; border-top: 2px solid #e2e8f0; display: flex; gap: 1rem; justify-content: flex-end;">
        <button onclick="closeBatchDeployModal()" style="padding: 0.875rem 2rem; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 0.5rem; background: white; color: #64748b; font-family: inherit;">
          <span>✕</span>
          <span>Cancel</span>
        </button>
        <button id="batchDeployBtn" onclick="confirmBatchDeploy()" style="padding: 0.875rem 2rem; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #dc143c 0%, #a00000 100%); color: white; box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3); font-family: inherit;">
          <span>🚀</span>
          <span>Deploy All</span>
        </button>
      </div>
    </div>
  </div>
  
  <style>
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(50px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    #deployModalOverlay.active, #batchDeployModalOverlay.active {
      display: flex !important;
    }
    
    #deployModalOverlay button:hover, #batchDeployModalOverlay button:hover {
      opacity: 0.9;
      transform: translateY(-2px);
    }
    
    #eventSelect:focus, #roleSelect:focus, #batchEventSelect:focus {
      outline: none;
      border-color: #dc143c !important;
      box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.1);
    }
    
    #eventSelect:hover, #roleSelect:hover, #batchEventSelect:hover {
      border-color: #dc143c;
    }
    
    .batch-volunteer-item {
      background: linear-gradient(135deg, #fff5f5 0%, #fee 100%);
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      padding: 1rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      transition: all 0.3s ease;
    }
    
    .batch-volunteer-item:hover {
      border-color: #dc143c;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.1);
    }
    
    .batch-volunteer-item .volunteer-info {
      flex: 1;
    }
    
    .batch-volunteer-item .volunteer-name {
      font-weight: 700;
      color: #dc143c;
      font-size: 1rem;
    }
    
    .batch-volunteer-item .role-select {
      width: 200px;
      padding: 0.75rem;
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      font-size: 0.9rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .batch-volunteer-item .role-select:focus {
      outline: none;
      border-color: #dc143c;
      box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.1);
    }
    
    .volunteer-checkbox {
      width: 20px;
      height: 20px;
      cursor: pointer;
      accent-color: #dc143c;
    }
    
    @media (max-width: 768px) {
      #deployModalOverlay > div, #batchDeployModalOverlay > div {
        width: 95% !important;
        max-height: 95vh !important;
      }
      
      .batch-volunteer-item {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .batch-volunteer-item .role-select {
        width: 100%;
      }
    }
  </style>
`;

// Add modal to body when script loads
document.addEventListener('DOMContentLoaded', function() {
  const modalContainer = document.createElement('div');
  modalContainer.innerHTML = deployModalHTML;
  document.body.appendChild(modalContainer);
});

// Sample events data - Replace with your actual events from database
const events = [
  { id: 1, name: "Flood Relief Operation - Manila", date: "2025-10-15", status: "active" },
  { id: 2, name: "Medical Mission - Quezon City", date: "2025-10-20", status: "active" },
  { id: 3, name: "Blood Donation Drive - Makati", date: "2025-10-25", status: "active" },
  { id: 4, name: "Earthquake Response - Masbate", date: "2025-11-01", status: "active" },
  { id: 5, name: "First Aid Training - Pasig", date: "2025-11-05", status: "active" },
  { id: 6, name: "Fire Emergency Response - Taguig", date: "2025-11-10", status: "active" }
];

// Store current volunteer data
let currentVolunteer = null;
let selectedVolunteers = new Map(); // Store selected volunteers for batch deployment

// Function to open deploy modal
async function deployNow(volunteerId) {

  try {
    const res = await fetch(`./utility/getVolunteerDetails.php?id=${volunteerId}`);
    const data = await res.json();
    
    currentVolunteer = data;
  
    document.getElementById('deployVolunteerName').innerHTML = `<span style="font-size: 1.5rem;">👤</span><span>${data.fullName || 'Unknown Volunteer'}</span>`;
    document.getElementById('deployVolunteerId').textContent = data.id || volunteerId;
    
    // Populate event select
    const eventSelect = document.getElementById('eventSelect');
    eventSelect.innerHTML = '<option value="">-- Choose an event --</option>';
  
    const e = await fetch("./utility/getEvent.php");
    const ev = await e.json();
    console.log(ev)
    ev.forEach(event => {
      const option = document.createElement('option');
      option.value = event.id;
      option.textContent = `${event.eventName} (${event.date})`;
      eventSelect.appendChild(option);
    });
    
    // Populate role select
    const roleSelect = document.getElementById('roleSelect');
    roleSelect.innerHTML = '<option value="">-- Choose a role --</option>';
    volunteerRoles.forEach(role => {
      const option = document.createElement('option');
      option.value = role;
      option.textContent = role;
      roleSelect.appendChild(option);
    });
    
    // Update event count
    document.getElementById('deployEventCount').textContent = `${ev.length} Available`;
    
    // Show modal
    document.getElementById('deployModalOverlay').classList.add('active');
    
  } catch (error) {
    console.error('Error fetching volunteer details:', error);
    alert('Error loading volunteer information');
  }
}

// Function to close deploy modal
function closeDeployModal() {
  document.getElementById('deployModalOverlay').classList.remove('active');
  currentVolunteer = null;
}

// Function to confirm deployment
async function confirmDeploy() {
  const eventId = document.getElementById('eventSelect').value;
  const role = document.getElementById('roleSelect').value;
  
  if (!eventId) {
    alert('Please select an event');
    return;
  }
  
  if (!role) {
    alert('Please select a role');
    return;
  }
  
  if (!currentVolunteer) {
    alert('No volunteer selected');
    return;
  }
  
  // Get the button that was clicked
  const deployBtns = document.querySelectorAll('#deployModalOverlay button');
  const deployBtn = deployBtns[deployBtns.length - 1]; // Last button is Deploy Now
  const originalHTML = deployBtn.innerHTML;
  deployBtn.innerHTML = '<span>⏳</span><span>Deploying...</span>';
  deployBtn.disabled = true;
  deployBtn.style.opacity = '0.7';
  deployBtn.style.cursor = 'not-allowed';
  
  try {
    // Make API call to deploy volunteer
    const response = await fetch('./utility/deployVolunteer.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        volunteerId: currentVolunteer.id,
        eventId: eventId,
        role: role
      })
    });
    
    const result = await response.json();
    
    if (result.success) {
      alert(`Successfully deployed ${currentVolunteer.fullName} as ${role}!`);
      closeDeployModal();
      location.reload()
      // Refresh the volunteer list or update UI
      // location.reload(); // Or call your refresh function
    } else {
      alert('Deployment failed: ' + (result.message || 'Unknown error'));
      deployBtn.innerHTML = originalHTML;
      deployBtn.disabled = false;
      deployBtn.style.opacity = '1';
      deployBtn.style.cursor = 'pointer';
    }
    
  } catch (error) {
    console.error('Error deploying volunteer:', error);
    alert('Error deploying volunteer. Please try again.');
    deployBtn.innerHTML = originalHTML;
    deployBtn.disabled = false;
    deployBtn.style.opacity = '1';
    deployBtn.style.cursor = 'pointer';
  }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
  const modalOverlay = document.getElementById('deployModalOverlay');
  const batchModalOverlay = document.getElementById('batchDeployModalOverlay');
  if (event.target === modalOverlay) {
    closeDeployModal();
  }
  if (event.target === batchModalOverlay) {
    closeBatchDeployModal();
  }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    closeDeployModal();
    closeBatchDeployModal();
  }
});

// ---------- CHECKBOX SELECTION FUNCTIONS ----------
function toggleVolunteerSelection(checkbox, volunteerId, volunteerName) {
  if (checkbox.checked) {
    selectedVolunteers.set(volunteerId, { id: volunteerId, name: volunteerName });
  } else {
    selectedVolunteers.delete(volunteerId);
  }
  updateDeploySelectedButton();
}

function toggleSelectAll(checkbox) {
  const allCheckboxes = document.querySelectorAll('.volunteer-checkbox');
  allCheckboxes.forEach(cb => {
    if (!cb.disabled) {
      cb.checked = checkbox.checked;
      const volunteerId = parseInt(cb.dataset.volunteerId);
      const volunteerName = cb.dataset.volunteerName;
      if (checkbox.checked) {
        selectedVolunteers.set(volunteerId, { id: volunteerId, name: volunteerName });
      } else {
        selectedVolunteers.delete(volunteerId);
      }
    }
  });
  updateDeploySelectedButton();
}

function updateDeploySelectedButton() {
  const btn = document.getElementById('deploySelectedBtn');
  const countSpan = document.getElementById('selectedCount');
  const count = selectedVolunteers.size;
  
  countSpan.textContent = count;
  btn.style.display = count > 0 ? 'inline-flex' : 'none';
}

// ---------- BATCH DEPLOYMENT FUNCTIONS ----------
async function openBatchDeployModal() {
  if (selectedVolunteers.size === 0) {
    alert('Please select at least one volunteer');
    return;
  }
  
  // Update volunteer count
  document.getElementById('batchVolunteerCount').textContent = `${selectedVolunteers.size} Selected`;
  
  // Populate event select
  const eventSelect = document.getElementById('batchEventSelect');
  eventSelect.innerHTML = '<option value="">-- Choose an event --</option>';
  
  try {
    const e = await fetch("./utility/getEvent.php");
    const ev = await e.json();
    ev.forEach(event => {
      const option = document.createElement('option');
      option.value = event.id;
      option.textContent = `${event.eventName} (${event.date})`;
      eventSelect.appendChild(option);
    });
  } catch (error) {
    console.error('Error fetching events:', error);
  }
  
  // Populate volunteer list with role selects
  const volunteersList = document.getElementById('batchVolunteersList');
  volunteersList.innerHTML = '';
  
  selectedVolunteers.forEach((volunteer, id) => {
    const roleOptions = volunteerRoles.map(role => 
      `<option value="${role}">${role}</option>`
    ).join('');
    
    const volunteerItem = document.createElement('div');
    volunteerItem.className = 'batch-volunteer-item';
    volunteerItem.innerHTML = `
      <div class="volunteer-info">
        <span class="volunteer-name">👤 ${volunteer.name}</span>
      </div>
      <select class="role-select" data-volunteer-id="${id}">
        <option value="">-- Select Role --</option>
        ${roleOptions}
      </select>
    `;
    volunteersList.appendChild(volunteerItem);
  });
  
  // Show modal
  document.getElementById('batchDeployModalOverlay').classList.add('active');
}

function closeBatchDeployModal() {
  document.getElementById('batchDeployModalOverlay').classList.remove('active');
}

async function confirmBatchDeploy() {
  const eventId = document.getElementById('batchEventSelect').value;
  
  if (!eventId) {
    alert('Please select an event');
    return;
  }
  
  // Collect all volunteer-role pairs
  const deployments = [];
  const roleSelects = document.querySelectorAll('#batchVolunteersList .role-select');
  let missingRoles = false;
  
  roleSelects.forEach(select => {
    const volunteerId = parseInt(select.dataset.volunteerId);
    const role = select.value;
    
    if (!role) {
      missingRoles = true;
      select.style.borderColor = '#ef4444';
    } else {
      select.style.borderColor = '#e2e8f0';
      deployments.push({ volunteerId, role });
    }
  });
  
  if (missingRoles) {
    alert('Please assign a role to all selected volunteers');
    return;
  }
  
  // Disable deploy button
  const deployBtn = document.getElementById('batchDeployBtn');
  const originalHTML = deployBtn.innerHTML;
  deployBtn.innerHTML = '<span>⏳</span><span>Deploying...</span>';
  deployBtn.disabled = true;
  deployBtn.style.opacity = '0.7';
  deployBtn.style.cursor = 'not-allowed';
  
  try {
    // Deploy all volunteers
    const results = await Promise.all(deployments.map(async (dep) => {
      const response = await fetch('./utility/deployVolunteer.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          volunteerId: dep.volunteerId,
          eventId: eventId,
          role: dep.role
        })
      });
      return response.json();
    }));
    
    const successCount = results.filter(r => r.success).length;
    const failCount = results.filter(r => !r.success).length;
    
    if (successCount > 0) {
      alert(`Successfully deployed ${successCount} volunteer(s)!${failCount > 0 ? ` (${failCount} failed)` : ''}`);
      selectedVolunteers.clear();
      closeBatchDeployModal();
      location.reload();
    } else {
      alert('All deployments failed. Please try again.');
      deployBtn.innerHTML = originalHTML;
      deployBtn.disabled = false;
      deployBtn.style.opacity = '1';
      deployBtn.style.cursor = 'pointer';
    }
    
  } catch (error) {
    console.error('Error in batch deployment:', error);
    alert('Error deploying volunteers. Please try again.');
    deployBtn.innerHTML = originalHTML;
    deployBtn.disabled = false;
    deployBtn.style.opacity = '1';
    deployBtn.style.cursor = 'pointer';
  }
}

// ---------- ADD ROW TO TABLE ----------
function addVolunteerToTable(volunteer, index) {
  const table = document.getElementById("volunteerTable").querySelector("tbody");
  const row = document.createElement("tr");
  
  // Check both status field and deployedLocation to determine if deployed
  const isDeployed = volunteer.status === 'deployed' || (volunteer.deployedLocation && volunteer.deployedLocation !== '');
  const statusDot = isDeployed 
    ? `<span class="status-dot deployed"></span>` 
    : `<span class="status-dot not-deployed"></span>`;
  const isRejected = volunteer.account_status === "rejected";
  const isPending = volunteer.account_status === "pending";
  const isAccepted = volunteer.account_status === "accepted";
  
  // Checkbox - only show for accepted volunteers who can be deployed
  const canSelect = isAccepted && !isDeployed;
  const checkbox = canSelect 
    ? `<input type="checkbox" class="volunteer-checkbox" data-volunteer-id="${volunteer.id}" data-volunteer-name="${volunteer.fullName || 'N/A'}" onchange="toggleVolunteerSelection(this, ${volunteer.id}, '${(volunteer.fullName || 'N/A').replace(/'/g, "\\'")}')">` 
    : `<input type="checkbox" class="volunteer-checkbox" disabled style="opacity: 0.3;">`;
  
  // Determine what button to show
  let actionButton = '';
  if (isRejected) {
    actionButton = '😔';
  } else if (isPending) {
    actionButton = `<button class="delete-btn" onclick="removeVolunteer(${volunteer.id})" title="Delete">🗑</button>`;
  } else if (isAccepted) {
    // Show deploy button - if deployed, it will allow re-deployment to different event
    if (isDeployed) {
      actionButton = `
        <span style="color: #10b981; font-weight: 600; margin-right: 8px;">✅</span>
        <button class="accept-btn" onclick="deployNow(${volunteer.id})" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); font-size: 0.8rem; padding: 0.5rem 1rem;">Redeploy</button>
      `;
    } else {
      actionButton = `<button class="accept-btn" onclick="deployNow(${volunteer.id})">Deploy</button>`;
    }
  }
  
  // Display role if deployed
  const deploymentInfo = isDeployed && !isRejected 
    ? `${volunteer.deployedLocation || volunteer.eventName || 'Deployed'}${volunteer.deploymentRole ? ' <span style="color:#dc143c;font-weight:600;">(' + volunteer.deploymentRole + ')</span>' : ''}` 
    : 'Not deployed';
  
  row.innerHTML = `
    <td style="text-align:center;width:50px;">${checkbox}</td>
    <td class="volunteer-name" style="${isRejected ? 'filter: blur(3px); pointer-events: none;' : ''}" data-index="${index}">${volunteer.fullName ? volunteer.fullName.toUpperCase() : 'N/A'}</td>
    <td style='padding-inline:20px;'>${deploymentInfo}</td>
    <td style='text-align:center;width:100px;'>${statusDot} <span class="status-text">${isDeployed ? 'Deployed' : 'Available'}</span></td>
    <td style='padding:10px'>${actionButton}</td>
  `;
 
  table.appendChild(row);
}


// ---------- AUTO RECALL CHECK ----------
async function checkAndRecallVolunteers() {
  try {
    const res = await fetch('./utility/recallVolunteers.php');
    const result = await res.json();
    if (result.recalled_count > 0) {
      console.log(`Auto-recalled ${result.recalled_count} volunteers from completed events`);
    }
    return result;
  } catch (error) {
    console.error('Error checking volunteer recalls:', error);
    return null;
  }
}

// ---------- RENDER ALL VOLUNTEERS ----------
async function renderTable() {
  // First check and recall any volunteers from ended events
  await checkAndRecallVolunteers();
  const tbody = document.getElementById("volunteerTable").querySelector("tbody");
  tbody.innerHTML = "";

  const res = await fetch("./utility/getAllVolunteer.php");
  const all = await res.json();
  console.log(all);

  // Determine filter value
  const statusFilter = document.getElementById("statusFilter");
  const filterValue = statusFilter ? (statusFilter.value || 'all').toLowerCase() : 'all';

  // Filter volunteers by account_status when requested
  let filtered = all;
  const normalize = s => (s === null || s === undefined) ? '' : String(s).toLowerCase().trim();
  if (filterValue === 'accepted') {
    filtered = all.filter(v => normalize(v.account_status).includes('accept') || normalize(v.account_status).includes('active'));
  } else if (filterValue === 'rejected') {
    filtered = all.filter(v => normalize(v.account_status).includes('reject') || normalize(v.account_status).includes('decline'));
  } else if (filterValue === 'pending') {
    filtered = all.filter(v => normalize(v.account_status).includes('pending') || normalize(v.account_status) === 'pending');
  }

  // Render filtered list
  filtered.forEach((volunteer, index) => {
    addVolunteerToTable(volunteer, index);
  });

  // Wire click handlers to open the correct (filtered) item
  document.querySelectorAll(".volunteer-name").forEach(td => {
    td.style.cursor = "pointer";
    td.addEventListener("click", () => {
      const idx = parseInt(td.getAttribute("data-index"), 10);
      if (!Number.isNaN(idx)) {
        openInfoModal(filtered[idx]);
      }
    });
  });
}


// ---------- INFO MODAL ----------




// ---------- DEPLOY VOLUNTEER ----------
function submitDeployment() {
  const deployLocation = document.getElementById("deployLocation").value;
  const name = document.getElementById("infoName").textContent;

  if (!deployLocation) {
    alert("Please select a deployment location.");
    return;
  }

  let volunteers = JSON.parse(localStorage.getItem("volunteers")) || [];
  let updated = volunteers.map(v => {
    if (v.fullName === name) {
      v.deployedLocation = deployLocation;
    }
    return v;
  });

  localStorage.setItem("volunteers", JSON.stringify(updated));
  document.getElementById("successMessage").textContent = 
    `${name} has been deployed to: ${deployLocation}`;
  document.getElementById("successModal").style.display = "flex";

  closeInfoModal();
  renderTable(); 
}

function closeSuccessModal() {
  document.getElementById("successModal").style.display = "none";
}

// ---------- OPEN VOLUNTEER MODAL ----------
async function openInfoModal(volunteer) {
  const res = await fetch(`./utility/getVolunteerDetails.php?id=${volunteer.id}`);
  const data = await res.json();
  console.log(data)

  const detailsDiv = document.getElementById("userDetails");
  document.getElementById("modalTitle").textContent = data.fullName || "Volunteer Details";

  detailsDiv.innerHTML = `
    <style>
      #userDetails {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        padding: 0;
        max-height: 70vh;
        overflow-y: auto;
        background: linear-gradient(135deg, #ffffff 0%, #fff5f5 100%);
      }
      
      #userDetails::-webkit-scrollbar {
        width: 10px;
      }
      
      #userDetails::-webkit-scrollbar-track {
        background: #fee;
        border-radius: 10px;
      }
      
      #userDetails::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #dc143c 0%, #a00000 100%);
        border-radius: 10px;
      }
      
      #userDetails::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #ff1744 0%, #c62828 100%);
      }
      
      .modal-section {
        margin-bottom: 2rem;
        animation: fadeInUp 0.5s ease-out;
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(220, 20, 60, 0.08);
        border-left: 4px solid #dc143c;
      }
      
      .modal-section:last-child {
        margin-bottom: 0;
      }
      
      .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #dc143c 0%, #c41e3a 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid #dc143c;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }
      
      .section-title::before {
        content: "✚";
        font-size: 1.3rem;
        color: #dc143c;
        -webkit-text-fill-color: #dc143c;
      }
      
      .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
      }
      
      .info-item {
        background: linear-gradient(135deg, #fff 0%, #fff5f5 100%);
        padding: 1rem;
        border-radius: 10px;
        border: 2px solid #fee;
        border-left: 4px solid #dc143c;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
      }
      
      .info-item::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #dc143c 0%, #ff4757 100%);
        transition: width 0.3s ease;
      }
      
      .info-item:hover {
        border-color: #dc143c;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 20, 60, 0.15);
      }
      
      .info-item:hover::before {
        width: 100%;
        opacity: 0.05;
      }
      
      .info-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #dc143c;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
      }
      
      .info-label::before {
        content: "▪";
        color: #dc143c;
        font-size: 1rem;
      }
      
      .info-value {
        font-size: 1rem;
        color: #2d3748;
        font-weight: 500;
        line-height: 1.5;
      }
      
      .empty-value {
        color: #cbd5e0;
        font-style: italic;
        font-weight: 400;
      }
      
      .status-container {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1rem;
      }
      
      .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.25rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 700;
        gap: 0.6rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }
      
      .status-badge:hover {
        transform: scale(1.05);
      }
      
      .status-badge.active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
      }
      
      .status-badge.pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
      }
      
      .status-badge.inactive {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
      }
      
      .status-badge.deployed {
        background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(220, 20, 60, 0.4);
        border-color: #ff1744;
      }
      
      .status-badge.not-deployed {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
      }
      
      .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: white;
        animation: pulse 2s infinite;
        box-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
      }
      
      @keyframes pulse {
        0%, 100% {
          transform: scale(1);
          opacity: 1;
        }
        50% {
          transform: scale(1.2);
          opacity: 0.8;
        }
      }
      
      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      
      .red-cross-accent {
        position: absolute;
        top: -5px;
        right: -5px;
        color: #dc143c;
        opacity: 0.1;
        font-size: 3rem;
        pointer-events: none;
      }
      
      @media (max-width: 768px) {
        .info-grid {
          grid-template-columns: 1fr;
        }
        
        .modal-section {
          padding: 1rem;
        }
      }
    </style>
    
    <!-- Personal Information -->
    <div class="modal-section">
      <h3 class="section-title">Personal Information</h3>
      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Birthplace</div>
          <div class="info-value">${data.birthPlace || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Date of Birth</div>
          <div class="info-value">${data.dob || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Age</div>
          <div class="info-value">${data.age || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Sex</div>
          <div class="info-value">${data.sex || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Civil Status</div>
          <div class="info-value">${data.civilStatus || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Religion</div>
          <div class="info-value">${data.religion || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Blood Type</div>
          <div class="info-value">${data.bloodType || '<span class="empty-value">Not provided</span>'}</div>
        </div>
      </div>
    </div>

    <!-- Contact Information -->
    <div class="modal-section">
      <h3 class="section-title">Contact Information</h3>
      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Address</div>
          <div class="info-value">${data.address || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Mobile</div>
          <div class="info-value">${data.mobile || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Landline</div>
          <div class="info-value">${data.landline || '<span class="empty-value">Not provided</span>'}</div>
        </div>
      </div>
    </div>

    <!-- Education -->
    <div class="modal-section">
      <h3 class="section-title">Education</h3>
      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Elementary</div>
          <div class="info-value">${data.elementary || '<span class="empty-value">Not provided</span>'} ${data.elemYearGrad ? `(${data.elemYearGrad})` : ''}</div>
        </div>
        <div class="info-item">
          <div class="info-label">High School</div>
          <div class="info-value">${data.highSchool || '<span class="empty-value">Not provided</span>'} ${data.hsYearGrad ? `(${data.hsYearGrad})` : ''}</div>
        </div>
        <div class="info-item">
          <div class="info-label">College</div>
          <div class="info-value">${data.college || '<span class="empty-value">Not provided</span>'} ${data.collegeYearGrad ? `(${data.collegeYearGrad})` : ''}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Post Graduate</div>
          <div class="info-value">${data.postGrad || '<span class="empty-value">Not provided</span>'} ${data.postGradYear ? `(${data.postGradYear})` : ''}</div>
        </div>
      </div>
    </div>

    <!-- Work Experience -->
    <div class="modal-section">
      <h3 class="section-title">Work Experience</h3>
      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Company</div>
          <div class="info-value">${data.company || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Position</div>
          <div class="info-value">${data.position || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Work Dates</div>
          <div class="info-value">${data.workDates || '<span class="empty-value">Not provided</span>'}</div>
        </div>
      </div>
    </div>

    <!-- Skills & Training -->
    <div class="modal-section">
      <h3 class="section-title">Skills & Training</h3>
      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Skills</div>
          <div class="info-value">${data.skills || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Languages</div>
          <div class="info-value">${data.languages || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Trainings</div>
          <div class="info-value">${data.trainings || '<span class="empty-value">Not provided</span>'}</div>
        </div>
      </div>
    </div>

    <!-- Other Information -->
    <div class="modal-section">
      <h3 class="section-title">Other Information</h3>
      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Health</div>
          <div class="info-value">${data.health || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Medication</div>
          <div class="info-value">${data.medication || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Red Cross Member</div>
          <div class="info-value">${data.redCrossMember || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Membership Type</div>
          <div class="info-value">${data.membershipType || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Reference Name</div>
          <div class="info-value">${data.refName || '<span class="empty-value">Not provided</span>'}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Reference Contact</div>
          <div class="info-value">${data.refContact || '<span class="empty-value">Not provided</span>'}</div>
        </div>
      </div>
    </div>

    <!-- Status -->
    <div class="modal-section">
      <h3 class="section-title">Status</h3>
      <div class="status-container">
        <div class="status-badge ${data.account_status === 'accepted' ? 'active' : data.account_status === 'pending' ? 'pending' : 'inactive'}">
          <span class="status-indicator"></span>
          <span>Account: ${data.account_status || 'Unknown'}</span>
        </div>
        <div class="status-badge ${data.status === 'deployed' ? 'deployed' : 'not-deployed'}">
          <span class="status-indicator"></span>
          <span>Deployment: ${data.status || 'Unknown'}</span>
        </div>
      </div>
    </div>
  `;

  // Set confirm and reject button handlers
  const acpt = document.getElementById("confirmBtn")
  const rjct = document.getElementById("rejectBtn")
  const deployButton = document.getElementById("deployBtn")
  
  if(data.account_status == "accepted"){
    // Hide confirm/reject, show deploy button for accepted volunteers
    acpt.style.display = "none";
    rjct.style.display = "none";
    deployButton.style.display = "inline-block";
    deployButton.onclick = () => {
      closeUserModal();
      deployNow(data.id);
    };
  }else{
    // Show confirm/reject, hide deploy for pending volunteers
    acpt.style.display = "inline";
    rjct.style.display = "inline";
    deployButton.style.display = "none";
  }
  acpt.onclick = () => updateStatus(data.id, "accepted", "", data.mobile, data.fullName);
  rjct.onclick = () => openReasonModal(data.id);

  document.getElementById("userModal").style.display = "flex";
}

function closeUserModal() {
  document.getElementById("userModal").style.display = "none";
}

async function updateStatus(id, status, reason, mobile, fullName) {
  const res = await fetch("./utility/updateVolunteerStatus.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id, status, reason, mobile, fullName })
  });
  const msg = await res.text();
  alert(msg);
  closeUserModal();
  renderTable();
}


// ---------- INITIALIZE ----------
document.addEventListener("DOMContentLoaded", () => {
  renderTable();
  const statusFilter = document.getElementById('statusFilter');
  if (statusFilter) {
    statusFilter.addEventListener('change', () => {
      renderTable();
    });
  }
});



document.addEventListener('click', function(event) {
  const modalOverlay = document.getElementById('deployModalOverlay');
  if (event.target === modalOverlay) {
    closeDeployModal();
  }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    closeDeployModal();
  }
});

// ---------- REASON MODAL ----------
const reasonModalHTML = `
  <div id="reasonModalOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(5px); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); max-width: 500px; width: 90%; position: relative;">
      
      <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 2rem; position: relative; overflow: hidden; border-radius: 20px 20px 0 0;">
        <div style="position: absolute; font-size: 8rem; opacity: 0.1; right: -20px; top: -20px; transform: rotate(15deg); pointer-events: none;">✕</div>
        
        <button onclick="closeReasonModal()" style="position: absolute; top: 1.5rem; right: 1.5rem; background: rgba(255, 255, 255, 0.2); border: 2px solid white; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; line-height: 1;">&times;</button>
        
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
          <span style="font-size: 2rem;">⚠️</span>
          <span>Specify Reason</span>
        </h2>
        <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem; opacity: 0.9;">Please provide a reason for rejection</p>
      </div>
      
      <div style="padding: 2rem;">
        <div style="margin-bottom: 0;">
          <label for="reasonInput" style="display: block; font-size: 0.9rem; font-weight: 700; color: #2d3748; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">
            <span style="color: #ef4444; font-size: 1.2rem; margin-right: 0.5rem;">▪</span>
            <span>Reason for Rejection</span>
          </label>
          <textarea id="reasonInput" rows="4" placeholder="Enter your reason here..." style="width: calc(100% - 2rem); padding: 1rem; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; font-weight: 500; color: #2d3748; background: white; resize: vertical; min-height: 100px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; transition: all 0.3s ease; box-sizing: border-box;"></textarea>
        </div>
      </div>
      
      <div style="padding: 1.5rem 2rem; background: #f7fafc; border-top: 2px solid #e2e8f0; display: flex; gap: 1rem; justify-content: flex-end; border-radius: 0 0 20px 20px;">
        <button onclick="closeReasonModal()" style="padding: 0.875rem 2rem; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 0.5rem; background: white; color: #64748b; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;">
          <span>✕</span>
          <span>Cancel</span>
        </button>
        <button onclick="submitReason()" style="padding: 0.875rem 2rem; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;">
          <span>✓</span>
          <span>Submit</span>
        </button>
      </div>
    </div>
  </div>
  
  <style>
    #reasonModalOverlay {
      animation: reasonModalFadeIn 0.3s ease;
    }
    
    #reasonModalOverlay.active {
      display: flex !important;
    }
    
    #reasonModalOverlay.active > div {
      animation: reasonModalSlideUp 0.4s ease;
    }
    
    @keyframes reasonModalFadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    @keyframes reasonModalSlideUp {
      from { opacity: 0; transform: translateY(50px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    #reasonModalOverlay button:hover {
      opacity: 0.9;
      transform: translateY(-2px);
    }
    
    #reasonInput:focus {
      outline: none;
      border-color: #ef4444 !important;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    @media (max-width: 768px) {
      #reasonModalOverlay > div {
        width: 95% !important;
      }
      
      #reasonModalOverlay > div > div:first-child {
        padding: 1.5rem !important;
      }
      
      #reasonModalOverlay > div > div:nth-child(2) {
        padding: 1.5rem !important;
      }
      
      #reasonModalOverlay > div > div:last-child {
        flex-direction: column !important;
        padding: 1rem 1.5rem !important;
      }
      
      #reasonModalOverlay button {
        width: 100% !important;
        justify-content: center !important;
      }
      
      #reasonInput {
        width: calc(100% - 2rem) !important;
      }
    }
  </style>
`;


// Add reason modal to body
document.addEventListener('DOMContentLoaded', function() {
  const reasonModalContainer = document.createElement('div');
  reasonModalContainer.innerHTML = reasonModalHTML;
  document.body.appendChild(reasonModalContainer);
});

// Store volunteer ID for rejection
let rejectVolunteerId = null;

// Function to open reason modal
function openReasonModal(volunteerId) {
  rejectVolunteerId = volunteerId;
  document.getElementById('reasonInput').value = '';
  document.getElementById('reasonModalOverlay').classList.add('active');
}

function closeReasonModal() {
  document.getElementById('reasonModalOverlay').classList.remove('active');
  rejectVolunteerId = null;
}

async function submitReason() {
  const reason = document.getElementById('reasonInput').value.trim();
  
  if (!reason) {
    alert('Please enter a reason for rejection');
    return;
  }
  
  if (!rejectVolunteerId) {
    alert('No volunteer selected');
    return;
  }
  
  // Proceed with rejection
  await updateStatus(rejectVolunteerId, 'rejected', reason);
  closeReasonModal();
}