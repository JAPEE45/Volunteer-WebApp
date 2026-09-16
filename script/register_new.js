// Multi-Step Registration Form JavaScript
let currentStep = 1;
const totalSteps = 9;

document.addEventListener('DOMContentLoaded', function() {
    showStep(currentStep);
    
    // Age calculation from DOB
    document.getElementById('dob').addEventListener('change', function() {
        const dob = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        document.getElementById('age').value = age;
    });

    // Show spouse field if married
    document.getElementById('civil_status').addEventListener('change', function() {
        const spouseField = document.getElementById('spouse_field');
        if (this.value === 'Married') {
            spouseField.style.display = 'block';
            spouseField.querySelector('input').required = true;
        } else {
            spouseField.style.display = 'none';
            spouseField.querySelector('input').required = false;
        }
    });

    // Red Cross volunteer conditional fields
    document.getElementById('rc_is_volunteer').addEventListener('change', function() {
        const rcFields = document.getElementById('rc_fields');
        rcFields.style.display = this.value === 'YES' ? 'block' : 'none';
    });

    document.getElementById('rc_has_maab').addEventListener('change', function() {
        const maabFields = document.getElementById('maab_fields');
        maabFields.style.display = this.value === 'YES' ? 'grid' : 'none';
    });

    document.getElementById('rc_basic_orientation').addEventListener('change', function() {
        const basicYearField = document.getElementById('basic_year_field');
        basicYearField.style.display = this.value === 'YES' ? 'block' : 'none';
    });

    document.getElementById('rc_rc143_training').addEventListener('change', function() {
        const rc143YearField = document.getElementById('rc143_year_field');
        rc143YearField.style.display = this.value === 'YES' ? 'block' : 'none';
    });

    // Navigation buttons
    document.getElementById('nextBtn').addEventListener('click', function() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    });

    document.getElementById('prevBtn').addEventListener('click', function() {
        currentStep--;
        showStep(currentStep);
    });

    // Form submission
    document.getElementById('registrationForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!validateStep(currentStep)) {
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> SUBMITTING...';

        const formData = new FormData(this);

        try {
            const response = await fetch('utility/addUser.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                document.getElementById('successMessage').style.display = 'flex';
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Redirect to login after 3 seconds
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 3000);
            } else {
                alert('Error: ' + (result.message || 'Registration failed. Please try again.'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> SUBMIT REGISTRATION';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> SUBMIT REGISTRATION';
        }
    });
});

function showStep(step) {
    const steps = document.querySelectorAll('.form-step');
    const stepIndicators = document.querySelectorAll('.step');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');

    // Hide all steps
    steps.forEach(s => s.classList.remove('active'));
    
    // Show current step
    steps[step - 1].classList.add('active');

    // Update step indicators
    stepIndicators.forEach((indicator, index) => {
        indicator.classList.remove('active', 'completed');
        if (index + 1 < step) {
            indicator.classList.add('completed');
        } else if (index + 1 === step) {
            indicator.classList.add('active');
        }
    });

    // Update progress bar
    const progress = (step / totalSteps) * 100;
    progressFill.style.width = progress + '%';
    progressText.textContent = `Step ${step} of ${totalSteps}`;

    // Update buttons
    prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
    nextBtn.style.display = step === totalSteps ? 'none' : 'inline-block';
    submitBtn.style.display = step === totalSteps ? 'inline-block' : 'none';

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function validateStep(step) {
    const currentStepElement = document.querySelector(`.form-step[data-step="${step}"]`);
    const requiredInputs = currentStepElement.querySelectorAll('[required]');
    
    let isValid = true;
    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('error');
            isValid = false;
        } else {
            input.classList.remove('error');
        }
    });

    if (!isValid) {
        alert('Please fill in all required fields marked with *');
        // Focus on first invalid field
        const firstInvalid = currentStepElement.querySelector('.error');
        if (firstInvalid) {
            firstInvalid.focus();
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    return isValid;
}

// Add error styling on input
document.addEventListener('input', function(e) {
    if (e.target.matches('input[required], select[required], textarea[required]')) {
        if (e.target.value.trim()) {
            e.target.classList.remove('error');
        }
    }
});
