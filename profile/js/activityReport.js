// Activity Report JavaScript

// Sidebar toggle
const toggleBtn = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

if (toggleBtn) {
  toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("active");
  });
}

// Deployment selection handler
const deploymentSelect = document.getElementById('deploymentSelect');
const deploymentInfo = document.getElementById('deploymentInfo');
const eventIdInput = document.getElementById('eventId');

if (deploymentSelect) {
  deploymentSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (this.value) {
      // Show deployment info
      deploymentInfo.style.display = 'block';
      
      // Populate info fields
      document.getElementById('infoEventName').textContent = selectedOption.dataset.eventName || '-';
      document.getElementById('infoLocation').textContent = selectedOption.dataset.location || '-';
      document.getElementById('infoDate').textContent = selectedOption.dataset.date || '-';
      document.getElementById('infoRole').textContent = selectedOption.dataset.role || 'Volunteer';
      
      // Set hidden event ID
      eventIdInput.value = selectedOption.dataset.eventId || '';
      
      // Set activity date default to deployment date
      const activityDate = document.getElementById('activityDate');
      if (activityDate && selectedOption.dataset.date) {
        activityDate.value = selectedOption.dataset.date;
        activityDate.setAttribute('min', selectedOption.dataset.date);
      }
    } else {
      deploymentInfo.style.display = 'none';
      eventIdInput.value = '';
    }
  });
}

// File upload preview
const documentsInput = document.getElementById('documents');
const filePreview = document.getElementById('filePreview');
let selectedFiles = [];

if (documentsInput) {
  documentsInput.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    
    // Validate file count
    if (files.length > 5) {
      alert('You can only upload up to 5 files');
      this.value = '';
      return;
    }
    
    // Validate file sizes
    const maxSize = 5 * 1024 * 1024; // 5MB
    for (let file of files) {
      if (file.size > maxSize) {
        alert(`File "${file.name}" is too large. Maximum size is 5MB`);
        this.value = '';
        return;
      }
    }
    
    selectedFiles = files;
    displayFilePreview(files);
  });
}

function displayFilePreview(files) {
  filePreview.innerHTML = '';
  
  files.forEach((file, index) => {
    const fileItem = document.createElement('div');
    fileItem.className = 'file-item';
    
    const icon = getFileIcon(file.type);
    const fileName = file.name.length > 30 ? file.name.substring(0, 27) + '...' : file.name;
    const fileSize = formatFileSize(file.size);
    
    fileItem.innerHTML = `
      <i class="${icon}"></i>
      <div>
        <div style="font-weight: 600; color: #2d3748;">${fileName}</div>
        <div style="font-size: 0.85rem; color: #64748b;">${fileSize}</div>
      </div>
      <button type="button" class="file-remove" onclick="removeFile(${index})" title="Remove">×</button>
    `;
    
    filePreview.appendChild(fileItem);
  });
}

function getFileIcon(fileType) {
  if (fileType.startsWith('image/')) return 'fas fa-image';
  if (fileType === 'application/pdf') return 'fas fa-file-pdf';
  if (fileType.includes('word')) return 'fas fa-file-word';
  return 'fas fa-file';
}

function formatFileSize(bytes) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function removeFile(index) {
  const dt = new DataTransfer();
  const files = Array.from(selectedFiles);
  
  files.splice(index, 1);
  
  files.forEach(file => dt.items.add(file));
  documentsInput.files = dt.files;
  selectedFiles = files;
  
  displayFilePreview(files);
}

// Form submission
const activityReportForm = document.getElementById('activityReportForm');

if (activityReportForm) {
  activityReportForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Validate form
    if (!deploymentSelect.value) {
      alert('Please select a deployment');
      deploymentSelect.focus();
      return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('.btn-primary');
    const originalHTML = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
    submitBtn.disabled = true;
    this.classList.add('loading');
    
    try {
      // Prepare form data
      const formData = new FormData(this);
      
      // Submit to server
      const response = await fetch('../utility/submitActivityReport.php', {
        method: 'POST',
        body: formData
      });
      
      const result = await response.json();
      
      if (result.success) {
        alert('Activity report submitted successfully! You will be notified once it is reviewed.');
        window.location.href = 'myReports.php';
      } else {
        throw new Error(result.message || 'Submission failed');
      }
      
    } catch (error) {
      console.error('Error submitting report:', error);
      alert('Error submitting report: ' + error.message);
      
      // Restore button
      submitBtn.innerHTML = originalHTML;
      submitBtn.disabled = false;
      this.classList.remove('loading');
    }
  });
}

// Character counter for textareas (optional enhancement)
document.querySelectorAll('textarea').forEach(textarea => {
  textarea.addEventListener('input', function() {
    const length = this.value.length;
    const maxLength = this.getAttribute('maxlength');
    
    if (maxLength) {
      let counter = this.parentElement.querySelector('.char-counter');
      if (!counter) {
        counter = document.createElement('small');
        counter.className = 'char-counter';
        counter.style.float = 'right';
        counter.style.color = '#64748b';
        this.parentElement.appendChild(counter);
      }
      counter.textContent = `${length}/${maxLength} characters`;
    }
  });
});

// Form validation helpers
function validateHours() {
  const hoursInput = document.getElementById('hoursWorked');
  if (hoursInput) {
    hoursInput.addEventListener('blur', function() {
      const value = parseFloat(this.value);
      if (value && (value < 0.5 || value > 24)) {
        alert('Hours worked must be between 0.5 and 24');
        this.value = '';
        this.focus();
      }
    });
  }
}

validateHours();

// Auto-save draft (optional - can implement localStorage)
function saveDraft() {
  const formData = {
    deployment_id: deploymentSelect.value,
    date_of_activity: document.getElementById('activityDate').value,
    hours_worked: document.getElementById('hoursWorked').value,
    activities_performed: document.getElementById('activitiesPerformed').value,
    challenges_faced: document.getElementById('challengesFaced').value,
    outcomes_achieved: document.getElementById('outcomesAchieved').value,
    recommendations: document.getElementById('recommendations').value
  };
  
  localStorage.setItem('activityReportDraft', JSON.stringify(formData));
}

// Load draft on page load
function loadDraft() {
  const draft = localStorage.getItem('activityReportDraft');
  if (draft) {
    const confirm = window.confirm('A saved draft was found. Would you like to restore it?');
    if (confirm) {
      const data = JSON.parse(draft);
      if (data.deployment_id) deploymentSelect.value = data.deployment_id;
      if (data.date_of_activity) document.getElementById('activityDate').value = data.date_of_activity;
      if (data.hours_worked) document.getElementById('hoursWorked').value = data.hours_worked;
      if (data.activities_performed) document.getElementById('activitiesPerformed').value = data.activities_performed;
      if (data.challenges_faced) document.getElementById('challengesFaced').value = data.challenges_faced;
      if (data.outcomes_achieved) document.getElementById('outcomesAchieved').value = data.outcomes_achieved;
      if (data.recommendations) document.getElementById('recommendations').value = data.recommendations;
      
      // Trigger deployment select change
      if (data.deployment_id) {
        deploymentSelect.dispatchEvent(new Event('change'));
      }
    }
  }
}

// Auto-save every 30 seconds
setInterval(saveDraft, 30000);

// Load draft on page load
window.addEventListener('load', loadDraft);

// Clear draft on successful submission
window.addEventListener('beforeunload', function() {
  // Only clear if form is being submitted
  if (activityReportForm && activityReportForm.classList.contains('loading')) {
    localStorage.removeItem('activityReportDraft');
  }
});
