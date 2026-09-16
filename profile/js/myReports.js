// My Reports JavaScript

// Sidebar toggle
const toggleBtn = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

if (toggleBtn) {
  toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("active");
  });
}

// View report details
async function viewReport(reportId) {
  const modal = document.getElementById('viewReportModal');
  const modalBody = document.getElementById('modalBody');
  
  // Show modal with loading state
  modalBody.innerHTML = '<div style="text-align:center;padding:3rem;"><i class="fas fa-spinner fa-spin" style="font-size:3rem;color:#dc143c;"></i><p style="margin-top:1rem;">Loading report...</p></div>';
  modal.classList.add('active');
  
  try {
    const response = await fetch(`../utility/getActivityReport.php?id=${reportId}`);
    const data = await response.json();
    
    if (data.success) {
      displayReportDetails(data.report);
    } else {
      modalBody.innerHTML = '<div style="text-align:center;padding:3rem;color:#ef4444;"><i class="fas fa-exclamation-circle" style="font-size:3rem;"></i><p style="margin-top:1rem;">Error loading report</p></div>';
    }
  } catch (error) {
    console.error('Error fetching report:', error);
    modalBody.innerHTML = '<div style="text-align:center;padding:3rem;color:#ef4444;"><i class="fas fa-exclamation-circle" style="font-size:3rem;"></i><p style="margin-top:1rem;">Error loading report</p></div>';
  }
}

function displayReportDetails(report) {
  const modalBody = document.getElementById('modalBody');
  
  // Parse supporting documents if exists
  let documentsHTML = '';
  if (report.supporting_documents) {
    try {
      const docs = JSON.parse(report.supporting_documents);
      if (docs.length > 0) {
        documentsHTML = `
          <div class="detail-section">
            <h3><i class="fas fa-paperclip"></i> Supporting Documents</h3>
            <div class="documents-list">
              ${docs.map(doc => {
                const fileName = doc.split('/').pop();
                const fileExt = fileName.split('.').pop().toLowerCase();
                const icon = fileExt === 'pdf' ? 'fa-file-pdf' : 
                            ['jpg','jpeg','png','gif'].includes(fileExt) ? 'fa-image' : 'fa-file';
                return `
                  <a href="../uploads/${doc}" target="_blank" class="doc-link">
                    <i class="fas ${icon}"></i> ${fileName}
                  </a>
                `;
              }).join('')}
            </div>
          </div>
        `;
      }
    } catch (e) {
      console.error('Error parsing documents:', e);
    }
  }
  
  modalBody.innerHTML = `
    <div class="detail-section">
      <h3><i class="fas fa-info-circle"></i> Event Information</h3>
      <div class="detail-grid">
        <div class="detail-item">
          <span class="detail-label">Event Name:</span>
          <span class="detail-value">${report.eventName || 'N/A'}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Location:</span>
          <span class="detail-value">${report.location || 'N/A'}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Event Date:</span>
          <span class="detail-value">${formatDate(report.event_date)}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Your Role:</span>
          <span class="detail-value">${report.role || 'Volunteer'}</span>
        </div>
      </div>
    </div>

    <div class="detail-section">
      <h3><i class="fas fa-tasks"></i> Activity Details</h3>
      <div class="detail-grid">
        <div class="detail-item">
          <span class="detail-label">Activity Date:</span>
          <span class="detail-value">${formatDate(report.date_of_activity)}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Hours Worked:</span>
          <span class="detail-value">${parseFloat(report.hours_worked).toFixed(1)} hours</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Status:</span>
          <span class="detail-value">
            <span class="status-badge status-${report.status}">
              ${getStatusIcon(report.status)} ${report.status.charAt(0).toUpperCase() + report.status.slice(1)}
            </span>
          </span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Submitted:</span>
          <span class="detail-value">${formatDateTime(report.submitted_at)}</span>
        </div>
      </div>
      
      <div class="detail-item-full">
        <span class="detail-label">Activities Performed:</span>
        <div class="detail-text">${escapeHTML(report.activities_performed)}</div>
      </div>
      
      ${report.challenges_faced ? `
        <div class="detail-item-full">
          <span class="detail-label">Challenges Faced:</span>
          <div class="detail-text">${escapeHTML(report.challenges_faced)}</div>
        </div>
      ` : ''}
      
      ${report.outcomes_achieved ? `
        <div class="detail-item-full">
          <span class="detail-label">Outcomes Achieved:</span>
          <div class="detail-text">${escapeHTML(report.outcomes_achieved)}</div>
        </div>
      ` : ''}
      
      ${report.recommendations ? `
        <div class="detail-item-full">
          <span class="detail-label">Recommendations:</span>
          <div class="detail-text">${escapeHTML(report.recommendations)}</div>
        </div>
      ` : ''}
    </div>

    ${documentsHTML}

    ${report.admin_notes ? `
      <div class="detail-section" style="background: linear-gradient(135deg, #fff5f5 0%, #fee 100%); border-left: 4px solid #dc143c; padding: 1.5rem; border-radius: 12px;">
        <h3><i class="fas fa-comment-dots"></i> Admin Notes</h3>
        <div class="detail-text">${escapeHTML(report.admin_notes)}</div>
        ${report.reviewed_at ? `
          <div style="margin-top: 1rem; font-size: 0.9rem; color: #64748b;">
            Reviewed on ${formatDateTime(report.reviewed_at)}
          </div>
        ` : ''}
      </div>
    ` : ''}
    
    <style>
      .detail-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 2px solid #fee;
      }
      
      .detail-section:last-child {
        border-bottom: none;
      }
      
      .detail-section h3 {
        font-size: 1.2rem;
        color: #dc143c;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }
      
      .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
      }
      
      .detail-item {
        padding: 0.75rem;
        background: #f7fafc;
        border-radius: 8px;
      }
      
      .detail-item-full {
        padding: 1rem;
        background: #f7fafc;
        border-radius: 8px;
        margin-bottom: 1rem;
      }
      
      .detail-label {
        display: block;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }
      
      .detail-value {
        color: #2d3748;
        font-weight: 600;
      }
      
      .detail-text {
        color: #2d3748;
        line-height: 1.8;
        white-space: pre-wrap;
      }
      
      .documents-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
      }
      
      .doc-link {
        padding: 0.75rem 1rem;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        color: #dc143c;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
      }
      
      .doc-link:hover {
        border-color: #dc143c;
        background: #fff5f5;
      }
      
      .doc-link i {
        font-size: 1.2rem;
      }
    </style>
  `;
}

function closeViewModal() {
  const modal = document.getElementById('viewReportModal');
  modal.classList.remove('active');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
  const modal = document.getElementById('viewReportModal');
  if (event.target === modal) {
    closeViewModal();
  }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    closeViewModal();
  }
});

// Helper functions
function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}

function formatDateTime(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}

function getStatusIcon(status) {
  switch(status) {
    case 'pending': return '<i class="fas fa-clock"></i>';
    case 'approved': return '<i class="fas fa-check-circle"></i>';
    case 'rejected': return '<i class="fas fa-times-circle"></i>';
    default: return '';
  }
}

function escapeHTML(text) {
  if (!text) return '';
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML.replace(/\n/g, '<br>');
}
