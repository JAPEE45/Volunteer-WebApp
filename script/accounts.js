// Side nav toggle (shared behavior)
document.addEventListener('DOMContentLoaded', () => {
  const toggleBtn = document.getElementById("menu-toggle");
  const sidebar = document.getElementById("sidebar");
  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener("click", () => sidebar.classList.toggle("active"));
  }

  // wire search
  const search = document.getElementById('searchInput');
  if (search) {
    search.addEventListener('keyup', function () {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll("#accountsTable tbody tr");
      rows.forEach(row => {
        const cols = row.querySelectorAll('td');
        const username = (cols[0]?.textContent || '').toLowerCase();
        const linked = (cols[5]?.textContent || '').toLowerCase();
        const status = (cols[6]?.textContent || '').toLowerCase();
        if (username.includes(filter) || linked.includes(filter) || status.includes(filter)) row.style.display = ""; else row.style.display = "none";
      });
    });
  }

  // load table
  renderTable();
});

async function renderTable() {
  const tbody = document.querySelector('#accountsTable tbody');
  tbody.innerHTML = '';

  try {
    const res = await fetch('./utility/getAllUsers.php');
    const users = await res.json();

    users.forEach((u, index) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="username">${u.username || ''}</td>
  <td class="password" data-password="${u.password || ''}">••••••</td>
        <td class="created">${u.createdAt || ''}</td>
        <td class="lastLogin">${u.lastLogin || ''}</td>
        <td class="role">${u.account_type || ''}</td>
        <td class="linked">${u.fullName || ''}</td>
        <td class="acct-status">${u.account_status || ''}</td>
        <td><button class="view-btn" data-account-id="${u.account_id}" data-user-id="${u.user_pk || u.user_id || ''}">View</button></td>
      `;
      tbody.appendChild(tr);
    });

    document.querySelectorAll('.view-btn').forEach(btn => btn.addEventListener('click', (e) => {
      const userId = e.currentTarget.getAttribute('data-user-id');
      // if there's a linked user record, open its details. Otherwise open modal with account info.
      if (userId) openUserModal(userId);
      else openAccountModal(e.currentTarget.getAttribute('data-account-id'));
    }));

  } catch (err) {
    console.error('Failed to load users', err);
  }
}

async function openUserModal(id) {
  try {
    const res = await fetch(`./utility/getVolunteerDetails.php?id=${id}`);
    const data = await res.json();

    document.getElementById('modalTitle').textContent = data.fullName || 'Account Details';
    const details = document.getElementById('userDetails');

    // try to parse documents JSON
    let docsHtml = 'None';
    if (data.documents) {
      try {
        const docs = JSON.parse(data.documents);
        if (Array.isArray(docs) && docs.length) {
          docsHtml = docs.map(d => {
            const url = `uploads/${d}`;
            return `<a href="${url}" target="_blank" rel="noopener">${d}</a>`;
          }).join('<br>');
        }
      } catch (e) {
        // not JSON, print raw
        docsHtml = data.documents;
      }
    }

   details.innerHTML = `
  <style>
    .detail-section {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 2px 8px rgba(220, 20, 60, 0.08);
      border-left: 4px solid #dc143c;
      animation: fadeInUp 0.5s ease-out;
    }
    
    .detail-section:last-child {
      margin-bottom: 0;
    }
    
    .section-header {
      font-size: 1.2rem;
      font-weight: 700;
      background: linear-gradient(135deg, #dc143c 0%, #c41e3a 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin: 0 0 1rem 0;
      padding-bottom: 0.5rem;
      border-bottom: 3px solid #dc143c;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .section-header::before {
      content: "✚";
      font-size: 1.3rem;
      color: #dc143c;
      -webkit-text-fill-color: #dc143c;
    }
    
    .detail-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1rem;
    }
    
    .detail-item {
      background: linear-gradient(135deg, #fff 0%, #fff5f5 100%);
      padding: 1rem;
      border-radius: 10px;
      border: 2px solid #fee;
      border-left: 4px solid #dc143c;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .detail-item::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: linear-gradient(180deg, #dc143c 0%, #ff4757 100%);
      transition: width 0.3s ease;
    }
    
    .detail-item:hover {
      border-color: #dc143c;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.15);
    }
    
    .detail-item:hover::before {
      width: 100%;
      opacity: 0.05;
    }
    
    .detail-label {
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
    
    .detail-label::before {
      content: "▪";
      color: #dc143c;
      font-size: 1rem;
    }
    
    .detail-value {
      font-size: 1rem;
      color: #2d3748;
      font-weight: 500;
      line-height: 1.5;
      word-break: break-word;
    }
    
    .detail-value.na {
      color: #cbd5e0;
      font-style: italic;
      font-weight: 400;
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
      margin-right: 0.5rem;
      margin-bottom: 0.5rem;
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
    
    .status-badge.rejected {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .status-badge.deployed {
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.4);
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
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.2); opacity: 0.8; }
    }
    
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .docs-list {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      margin-top: 0.5rem;
    }
    
    .doc-item {
      padding: 0.75rem 1rem;
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      border-left: 3px solid #0ea5e9;
      border-radius: 8px;
      font-size: 0.9rem;
      color: #0c4a6e;
      font-weight: 500;
      transition: all 0.2s ease;
    }
    
    .doc-item:hover {
      background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
      transform: translateX(5px);
    }
    
    @media (max-width: 768px) {
      .detail-grid {
        grid-template-columns: 1fr;
      }
      
      .detail-section {
        padding: 1rem;
      }
    }
  </style>
  
  <!-- Personal Information -->
  <div class="detail-section">
    <h3 class="section-header">Personal Information</h3>
    <div class="detail-grid">
      <div class="detail-item">
        <div class="detail-label">Full Name</div>
        <div class="detail-value ${!data.fullName ? 'na' : ''}">${data.fullName || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">First Name</div>
        <div class="detail-value ${!data.firstName ? 'na' : ''}">${data.firstName || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Middle Name</div>
        <div class="detail-value ${!data.middleName ? 'na' : ''}">${data.middleName || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Last Name</div>
        <div class="detail-value ${!data.lastName ? 'na' : ''}">${data.lastName || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Birthplace</div>
        <div class="detail-value ${!data.birthPlace ? 'na' : ''}">${data.birthPlace || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Date of Birth</div>
        <div class="detail-value ${!data.dob ? 'na' : ''}">${data.dob || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Sex</div>
        <div class="detail-value ${!data.sex ? 'na' : ''}">${data.sex || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Age</div>
        <div class="detail-value ${!data.age ? 'na' : ''}">${data.age || 'N/A'}</div>
      </div>
    </div>
  </div>

  <!-- Contact Information -->
  <div class="detail-section">
    <h3 class="section-header">Contact Information</h3>
    <div class="detail-grid">
      <div class="detail-item">
        <div class="detail-label">Mobile</div>
        <div class="detail-value ${!data.mobile ? 'na' : ''}">${data.mobile || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Landline</div>
        <div class="detail-value ${!data.landline ? 'na' : ''}">${data.landline || 'N/A'}</div>
      </div>
      <div class="detail-item" style="grid-column: 1 / -1;">
        <div class="detail-label">Address</div>
        <div class="detail-value ${!data.address ? 'na' : ''}">${data.address || 'N/A'}</div>
      </div>
    </div>
  </div>

  <!-- Additional Details -->
  <div class="detail-section">
    <h3 class="section-header">Additional Details</h3>
    <div class="detail-grid">
      <div class="detail-item">
        <div class="detail-label">Religion</div>
        <div class="detail-value ${!data.religion ? 'na' : ''}">${data.religion || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Blood Type</div>
        <div class="detail-value ${!data.bloodType ? 'na' : ''}">${data.bloodType || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Health</div>
        <div class="detail-value ${!data.health ? 'na' : ''}">${data.health || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Medication</div>
        <div class="detail-value ${!data.medication ? 'na' : ''}">${data.medication || 'N/A'}</div>
      </div>
    </div>
  </div>

  <!-- Education -->
  <div class="detail-section">
    <h3 class="section-header">Education</h3>
    <div class="detail-grid">
      <div class="detail-item">
        <div class="detail-label">Elementary</div>
        <div class="detail-value ${!data.elementary ? 'na' : ''}">${data.elementary || 'N/A'} ${data.elemYearGrad ? `(${data.elemYearGrad})` : ''}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">High School</div>
        <div class="detail-value ${!data.highSchool ? 'na' : ''}">${data.highSchool || 'N/A'} ${data.hsYearGrad ? `(${data.hsYearGrad})` : ''}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">College</div>
        <div class="detail-value ${!data.college ? 'na' : ''}">${data.college || 'N/A'} ${data.collegeYearGrad ? `(${data.collegeYearGrad})` : ''}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Post Graduate</div>
        <div class="detail-value ${!data.postGrad ? 'na' : ''}">${data.postGrad || 'N/A'} ${data.postGradYear ? `(${data.postGradYear})` : ''}</div>
      </div>
    </div>
  </div>

  <!-- Work & Skills -->
  <div class="detail-section">
    <h3 class="section-header">Work Experience & Skills</h3>
    <div class="detail-grid">
      <div class="detail-item">
        <div class="detail-label">Company</div>
        <div class="detail-value ${!data.company ? 'na' : ''}">${data.company || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Position</div>
        <div class="detail-value ${!data.position ? 'na' : ''}">${data.position || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Work Dates</div>
        <div class="detail-value ${!data.workDates ? 'na' : ''}">${data.workDates || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Skills</div>
        <div class="detail-value ${!data.skills ? 'na' : ''}">${data.skills || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Languages</div>
        <div class="detail-value ${!data.languages ? 'na' : ''}">${data.languages || 'N/A'}</div>
      </div>
    </div>
  </div>

  <!-- Membership & References -->
  <div class="detail-section">
    <h3 class="section-header">Membership & References</h3>
    <div class="detail-grid">
      <div class="detail-item">
        <div class="detail-label">Red Cross Member</div>
        <div class="detail-value ${!data.redCrossMember ? 'na' : ''}">${data.redCrossMember || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Membership Type</div>
        <div class="detail-value ${!data.membershipType ? 'na' : ''}">${data.membershipType || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Trainings</div>
        <div class="detail-value ${!data.trainings ? 'na' : ''}">${data.trainings || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Reference Name</div>
        <div class="detail-value ${!data.refName ? 'na' : ''}">${data.refName || 'N/A'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Reference Contact</div>
        <div class="detail-value ${!data.refContact ? 'na' : ''}">${data.refContact || 'N/A'}</div>
      </div>
    </div>
  </div>

  <!-- Account & Status -->
  <div class="detail-section">
    <h3 class="section-header">Account & Status</h3>
    <div class="detail-grid">
      <div class="detail-item" style="grid-column: 1 / -1;">
        <div class="detail-label">Account Status</div>
        <div>
          <span class="status-badge ${data.account_status === 'accepted' ? 'active' : data.account_status === 'pending' ? 'pending' : 'rejected'}">
            <span class="status-indicator"></span>
            <span>${data.account_status || 'N/A'}</span>
          </span>
        </div>
      </div>
      <div class="detail-item" style="grid-column: 1 / -1;">
        <div class="detail-label">Deployment Status</div>
        <div>
          <span class="status-badge ${data.status === 'deployed' ? 'deployed' : 'not-deployed'}">
            <span class="status-indicator"></span>
            <span>${data.status || 'N/A'}</span>
          </span>
        </div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Account Created</div>
        <div class="detail-value ${!data.created_at ? 'na' : ''}">${data.created_at || 'N/A'}</div>
      </div>
    </div>
  </div>

  <!-- Documents -->
  <div class="detail-section">
    <h3 class="section-header">Documents</h3>
    <div class="docs-list">
      ${docsHtml || '<div class="detail-value na">No documents available</div>'}
    </div>
  </div>
`;

    const accept = document.getElementById('acceptBtn');
    const reject = document.getElementById('rejectBtn');

    if (data.account_status === 'accepted' || data.account_status === 'active') {
      accept.style.display = 'none';
      reject.style.display = 'inline-block';
    } else {
      accept.style.display = 'inline-block';
      reject.style.display = 'inline-block';
    }

    accept.onclick = () => updateAccountStatus(id, 'accepted');
    reject.onclick = () => updateAccountStatus(id, 'rejected');
    reject.innerHTML = "Delete"
    document.getElementById('userModal').style.display = 'flex';

  } catch (err) {
    console.error(err);
    alert('Failed to load user details');
  }
}

function closeUserModal() {
  document.getElementById('userModal').style.display = 'none';
}

async function updateAccountStatus(id, status) {
  try {
    if (status === 'delete') {
      const res = await fetch('./utility/deleteAccountAndUser.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      });
      const txt = await res.text();
      alert(txt);
      closeUserModal();
      renderTable();
      return;
    }
    // Otherwise, update status as before
    const res = await fetch('./utility/updateAccountStatus.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id, status })
    });
    const txt = await res.text();
    alert(txt);
    closeUserModal();
    renderTable();
  } catch (err) {
    console.error(err);
    alert('Failed to update account status');
  }
}

// If account has no linked user, show account-only info
async function openAccountModal(accountId) {
  try {
    const res = await fetch(`./utility/getAllUsers.php`);
    const rows = await res.json();
    const acc = rows.find(r => String(r.account_id) === String(accountId));
    if (!acc) {
      alert('Account not found');
      return;
    }

    document.getElementById('modalTitle').textContent = acc.username || 'Account Details';
    const details = document.getElementById('userDetails');
    details.innerHTML = `
      <p><strong>Username:</strong> ${acc.username || 'N/A'}</p>
      <p><strong>Password:</strong> <span id="revealPassword">••••••</span> <button id="revealBtn" style="margin-left:8px;padding:3px 8px;border-radius:4px;border:none;background:#b30000;color:#fff;cursor:pointer;">Reveal</button></p>
      <p><strong>Created At:</strong> ${acc.createdAt || 'N/A'}</p>
      <p><strong>Last Login:</strong> ${acc.lastLogin || 'N/A'}</p>
      <p><strong>Account Role:</strong> ${acc.account_type || 'N/A'}</p>
    `;

    // hide accept/reject for accounts without linked user
    document.getElementById('acceptBtn').style.display = 'none';
    document.getElementById('rejectBtn').style.display = 'none';
    document.getElementById('userModal').style.display = 'flex';

    // reveal password behavior
    const revealBtn = document.getElementById('revealBtn');
    if (revealBtn) {
      revealBtn.addEventListener('click', () => {
        const span = document.getElementById('revealPassword');
        span.textContent = acc.password || 'N/A';
        setTimeout(() => { span.textContent = '••••••'; }, 5000);
      });
    }

  } catch (err) {
    console.error(err);
    alert('Failed to load account info');
  }
}
