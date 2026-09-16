<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <title>Philippine Red Cross - Volunteer Registration</title>
  <link rel="stylesheet" href="./style/register_new.css">
</head>
<body>
  <div class="header">
    <img src="img/Philippine_Red_Cross_logo.jpg" alt="Red Cross">
    <h2>PHILIPPINE RED CROSS VOLUNTEER REGISTRATION FORM</h2>
    <p>Complete all sections accurately. Fields marked with * are required.</p>
  </div>

  <form id="registrationForm" class="form-table" enctype="multipart/form-data">
    
    <!-- Progress Indicator -->
    <div class="progress-indicator">
      <div class="step-indicator">
        <div class="step active" data-step="1"><span>1</span> Personal Info</div>
        <div class="step" data-step="2"><span>2</span> Medical</div>
        <div class="step" data-step="3"><span>3</span> Family</div>
        <div class="step" data-step="4"><span>4</span> Education</div>
        <div class="step" data-step="5"><span>5</span> Skills</div>
        <div class="step" data-step="6"><span>6</span> Experience</div>
        <div class="step" data-step="7"><span>7</span> Red Cross</div>
        <div class="step" data-step="8"><span>8</span> References</div>
        <div class="step" data-step="9"><span>9</span> Certification</div>
      </div>
      <div class="progress-bar">
        <div class="progress-fill" id="progressFill"></div>
      </div>
      <span class="progress-text" id="progressText">Step 1 of 9</span>
    </div>

    <!-- STEP 1: PERSONAL INFORMATION -->
    <div class="form-step active" data-step="1">
      <h3><i class="fas fa-user"></i> I. PERSONAL INFORMATION</h3>
      <div class="grid">
        <div><label>Family Name (Last Name) *</label><input name="family_name" type="text" required></div>
        <div><label>Given Name (First Name) *</label><input name="given_name" type="text" required></div>
        <div><label>Middle Name</label><input name="middle_name" type="text"></div>
        <div><label>Nick Name</label><input name="nick_name" type="text"></div>
        
        <div><label>Sex *</label>
          <select name="sex" required>
            <option value="">-- Select --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </div>
        
        <div><label>Date of Birth (mm/dd/yyyy) *</label><input name="dob" type="date" id="dob" required></div>
        <div><label>Age</label><input name="age" type="number" id="age" readonly></div>
        <div><label>Birth Place *</label><input name="birth_place" type="text" required></div>
        <div><label>Religion</label><input name="religion" type="text"></div>
        <div><label>Height (in cm)</label><input name="height" type="number" min="100" max="250"></div>
        <div><label>Weight (in kilos)</label><input name="weight" type="number" step="0.01" min="30" max="200"></div>
        
        <div><label>Civil Status *</label>
          <select name="civil_status" id="civil_status" required>
            <option value="">-- Select --</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widowed">Widowed</option>
            <option value="Separated">Separated</option>
            <option value="Divorced">Divorced</option>
          </select>
        </div>
        
        <div id="spouse_field" style="display:none;"><label>If married; Name of Spouse</label><input name="spouse_name" type="text"></div>
        <div><label>Contact Number (Personal)</label><input name="contact_number_personal" type="text"></div>
        <div><label>Number of Children</label><input name="number_of_children" type="number" min="0" value="0"></div>
        <div><label>Mobile Number *</label><input name="mobile_number" type="text" required></div>
        <div><label>Landline Number</label><input name="landline_number" type="text"></div>
        <div class="wide"><label>Email</label><input name="email" type="email"></div>
      </div>

      <h4><i class="fas fa-map-marker-alt"></i> Address Information</h4>
      <div class="grid">
        <div><label>House No.</label><input name="house_no" type="text"></div>
        <div><label>Street/Block/Lot</label><input name="street_block_lot" type="text"></div>
        <div><label>District/Barangay/Village *</label><input name="district_barangay_village" type="text" required></div>
        <div><label>Municipality/City *</label><input name="municipality_city" type="text" required></div>
        <div><label>Province *</label><input name="province" type="text" required></div>
        <div><label>ZIP Code</label><input name="zip_code" type="text" maxlength="4"></div>
      </div>
    </div>

    <!-- STEP 2: MEDICAL HISTORY -->
    <div class="form-step" data-step="2">
      <h3><i class="fas fa-heartbeat"></i> II. MEDICAL HISTORY</h3>
      <div class="grid">
        <div class="wide">
          <label>Pre-existing Medical or Health Conditions/ Disability/ Allergies (if any)</label>
          <textarea name="medical_conditions" rows="3" placeholder="Write 'None' if not applicable"></textarea>
        </div>
        <div class="wide">
          <label>Current Medications Taken (if any)</label>
          <textarea name="current_medications" rows="2" placeholder="Write 'None' if not applicable"></textarea>
        </div>
        <div><label>Blood Type</label>
          <select name="blood_type">
            <option value="">-- Select --</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
          </select>
        </div>
      </div>

      <h4><i class="fas fa-phone-alt"></i> Emergency Contact 1 (Immediate family) *</h4>
      <div class="grid">
        <div><label>Name *</label><input name="emergency_contact1_name" type="text" required></div>
        <div><label>Relationship to you *</label><input name="emergency_contact1_relationship" type="text" required></div>
        <div><label>Landline Number</label><input name="emergency_contact1_landline" type="text"></div>
        <div><label>Mobile Number *</label><input name="emergency_contact1_mobile" type="text" required></div>
      </div>

      <h4><i class="fas fa-phone-alt"></i> Emergency Contact 2 (Other than Immediate Family)</h4>
      <div class="grid">
        <div><label>Name</label><input name="emergency_contact2_name" type="text"></div>
        <div><label>Relationship to you</label><input name="emergency_contact2_relationship" type="text"></div>
        <div><label>Landline Number</label><input name="emergency_contact2_landline" type="text"></div>
        <div><label>Mobile Number</label><input name="emergency_contact2_mobile" type="text"></div>
      </div>
    </div>

    <!-- STEP 3: FAMILY BACKGROUND -->
    <div class="form-step" data-step="3">
      <h3><i class="fas fa-users"></i> III. FAMILY BACKGROUND</h3>
      
      <h4><i class="fas fa-male"></i> Father's Information</h4>
      <div class="grid">
        <div><label>Father's Name</label><input name="father_name" type="text"></div>
        <div><label>Father's Age</label><input name="father_age" type="number" min="0" max="120"></div>
        <div><label>Father's Occupation</label><input name="father_occupation" type="text"></div>
      </div>

      <h4><i class="fas fa-female"></i> Mother's Information</h4>
      <div class="grid">
        <div><label>Mother's Name</label><input name="mother_name" type="text"></div>
        <div><label>Mother's Age</label><input name="mother_age" type="number" min="0" max="120"></div>
        <div><label>Mother's Occupation</label><input name="mother_occupation" type="text"></div>
      </div>

      <h4><i class="fas fa-child"></i> Siblings Information</h4>
      <div class="grid">
        <div><label>Number of brothers and sisters</label><input name="number_of_siblings" type="number" min="0"></div>
        <div><label>Your Position in the Family</label><input name="position_in_family" type="text" placeholder="e.g., Eldest, 2nd, Youngest"></div>
      </div>
    </div>

    <!-- STEP 4: EDUCATIONAL BACKGROUND -->
    <div class="form-step" data-step="4">
      <h3><i class="fas fa-graduation-cap"></i> IV. EDUCATIONAL BACKGROUND</h3>
      
      <h4>Elementary:</h4>
      <div class="grid">
        <div><label>School Name</label><input name="elementary_school" type="text"></div>
        <div><label>Year Graduated</label><input name="elementary_year_graduated" type="text"></div>
        <div class="wide"><label>Honors/Awards</label><input name="elementary_honors" type="text"></div>
      </div>

      <h4>High School:</h4>
      <div class="grid">
        <div><label>School Name</label><input name="highschool_school" type="text"></div>
        <div><label>Year Graduated</label><input name="highschool_year_graduated" type="text"></div>
        <div class="wide"><label>Honors/Awards</label><input name="highschool_honors" type="text"></div>
      </div>

      <h4>College:</h4>
      <div class="grid">
        <div><label>School Name</label><input name="college_school" type="text"></div>
        <div><label>Course</label><input name="college_course" type="text"></div>
        <div><label>Year Graduated</label><input name="college_year_graduated" type="text"></div>
        <div class="wide"><label>Honors/Awards</label><input name="college_honors" type="text"></div>
      </div>

      <h4>Vocational:</h4>
      <div class="grid">
        <div><label>School Name</label><input name="vocational_school" type="text"></div>
        <div><label>Year Graduated</label><input name="vocational_year_graduated" type="text"></div>
        <div class="wide"><label>Honors/Awards</label><input name="vocational_honors" type="text"></div>
      </div>

      <h4>Higher Studies:</h4>
      <div class="grid">
        <div><label>School Name</label><input name="higher_studies_school" type="text"></div>
        <div><label>Year Graduated</label><input name="higher_studies_year_graduated" type="text"></div>
        <div class="wide"><label>Honors/Awards</label><input name="higher_studies_honors" type="text"></div>
      </div>
    </div>

    <!-- STEP 5: TALENTS AND SKILLS -->
    <div class="form-step" data-step="5">
      <h3><i class="fas fa-star"></i> IX. TALENTS AND SKILLS</h3>
      <div class="grid">
        <div class="wide">
          <label>What would you consider as your talent(s)?</label>
          <textarea name="talents" rows="3" placeholder="E.g., Public speaking, Arts, Music, etc."></textarea>
        </div>
        <div class="wide">
          <label>What are some skill(s) you possess?</label>
          <textarea name="skills" rows="3" placeholder="E.g., First Aid, Computer skills, Leadership, etc."></textarea>
        </div>
        <div class="wide">
          <label>Language(s) & dialect(s) you can speak, read and understand fluently</label>
          <textarea name="languages_dialects" rows="2" placeholder="E.g., English, Filipino, Bisaya, etc."></textarea>
        </div>
      </div>
    </div>

    <!-- STEP 6: INVOLVEMENTS & WORK EXPERIENCE -->
    <div class="form-step" data-step="6">
      <h3><i class="fas fa-handshake"></i> V. SOCIO-CIVIC, CULTURAL & RELIGIOUS INVOLVEMENTS</h3>
      
      <h4>Involvement 1:</h4>
      <div class="grid">
        <div><label>Organization/Activity</label><input name="involvement1_organization" type="text"></div>
        <div><label>Position</label><input name="involvement1_position" type="text"></div>
        <div><label>Year</label><input name="involvement1_year" type="text"></div>
      </div>

      <h4>Involvement 2:</h4>
      <div class="grid">
        <div><label>Organization/Activity</label><input name="involvement2_organization" type="text"></div>
        <div><label>Position</label><input name="involvement2_position" type="text"></div>
        <div><label>Year</label><input name="involvement2_year" type="text"></div>
      </div>

      <h3><i class="fas fa-briefcase"></i> VI. WORK EXPERIENCE</h3>
      
      <h4>Experience 1:</h4>
      <div class="grid">
        <div><label>Company Name</label><input name="work_exp1_company" type="text"></div>
        <div><label>Title/Position</label><input name="work_exp1_position" type="text"></div>
        <div><label>Year</label><input name="work_exp1_year" type="text" placeholder="e.g., 2020-2022"></div>
      </div>

      <h4>Experience 2:</h4>
      <div class="grid">
        <div><label>Company Name</label><input name="work_exp2_company" type="text"></div>
        <div><label>Title/Position</label><input name="work_exp2_position" type="text"></div>
        <div><label>Year</label><input name="work_exp2_year" type="text" placeholder="e.g., 2018-2020"></div>
      </div>
    </div>

    <!-- STEP 7: RED CROSS EXPERIENCE -->
    <div class="form-step" data-step="7">
      <h3><i class="fas fa-plus-circle"></i> VII. RED CROSS EXPERIENCE</h3>
      <div class="grid">
        <div><label>Are you a Red Cross Volunteer? *</label>
          <select name="rc_is_volunteer" id="rc_is_volunteer" required>
            <option value="">-- Select --</option>
            <option value="YES">YES</option>
            <option value="NO">NO</option>
          </select>
        </div>
        <div id="rc_fields" style="display:none;">
          <div class="grid">
            <div><label>Month and Year Started</label><input name="rc_month_year_started" type="text" placeholder="e.g., January 2023"></div>
            <div><label>Do you have a Membership with Accident Assistance Benefits?</label>
              <select name="rc_has_maab" id="rc_has_maab">
                <option value="NO">NO</option>
                <option value="YES">YES</option>
              </select>
            </div>
          </div>
          
          <div id="maab_fields" style="display:none;" class="grid">
            <div><label>MAAB Serial No.</label><input name="rc_maab_serial_no" type="text"></div>
            <div><label>Validity Period</label><input name="rc_maab_validity" type="text" placeholder="e.g., Jan 2023 - Jan 2024"></div>
          </div>

          <div class="grid">
            <div><label>Underwent Basic Volunteer Orientation Course?</label>
              <select name="rc_basic_orientation" id="rc_basic_orientation">
                <option value="NO">NO</option>
                <option value="YES">YES</option>
              </select>
            </div>
            <div id="basic_year_field" style="display:none;">
              <label>If yes, what year?</label><input name="rc_basic_orientation_year" type="text">
            </div>

            <div><label>Underwent Basic RC143 Orientation Training?</label>
              <select name="rc_rc143_training" id="rc_rc143_training">
                <option value="NO">NO</option>
                <option value="YES">YES</option>
              </select>
            </div>
            <div id="rc143_year_field" style="display:none;">
              <label>If yes, what year?</label><input name="rc_rc143_training_year" type="text">
            </div>
          </div>

          <div class="grid">
            <div class="wide"><label>Other Red Cross Training/ Courses Acquired</label>
              <textarea name="rc_other_trainings" rows="3" placeholder="List other trainings"></textarea>
            </div>
            <div class="wide"><label>Exclusive Dates</label>
              <textarea name="rc_training_dates" rows="2" placeholder="Training dates"></textarea>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- STEP 8: REFERENCES -->
    <div class="form-step" data-step="8">
      <h3><i class="fas fa-user-friends"></i> IX. REFERENCES</h3>
      
      <h4>Reference 1: *</h4>
      <div class="grid">
        <div><label>Complete Name *</label><input name="reference1_name" type="text" required></div>
        <div><label>Contact Number(s) *</label><input name="reference1_contact" type="text" required></div>
        <div><label>Company/Institution/Organization</label><input name="reference1_company" type="text"></div>
        <div><label>Position</label><input name="reference1_position" type="text"></div>
      </div>

      <h4>Reference 2:</h4>
      <div class="grid">
        <div><label>Complete Name</label><input name="reference2_name" type="text"></div>
        <div><label>Contact Number(s)</label><input name="reference2_contact" type="text"></div>
        <div><label>Company/Institution/Organization</label><input name="reference2_company" type="text"></div>
        <div><label>Position</label><input name="reference2_position" type="text"></div>
      </div>

      <h3><i class="fas fa-file-upload"></i> Supporting Documents</h3>
      <div class="grid">
        <div class="wide">
          <label>Upload Certificates / ID / Documents</label>
          <input type="file" name="documents[]" id="documents" multiple accept="image/*,.pdf,.doc,.docx">
          <small>You can upload multiple files (Max 10MB total)</small>
        </div>
      </div>
    </div>

    <!-- STEP 9: WAIVERS & CERTIFICATION -->
    <div class="form-step" data-step="9">
      <h3><i class="fas fa-file-signature"></i> VOLUNTEER WAIVER</h3>
      <div class="waiver-box">
        <p><strong>I hereby volunteer my services to the Philippine Red Cross</strong> and understand that I will not receive monetary compensation. I acknowledge the risks involved and agree to follow all safety protocols.</p>
      </div>
      <div class="grid">
        <div><label>Complete Name *</label><input name="waiver_signatory_name" type="text" required></div>
        <div><label>Date and Place *</label><input name="waiver_date_place" type="text" required placeholder="e.g., December 9, 2025 - Virac, Catanduanes"></div>
      </div>

      <h3><i class="fas fa-shield-alt"></i> CHILD PROTECTION POLICY & CODE OF CONDUCT</h3>
      <div class="waiver-box">
        <p><strong>I acknowledge and agree to comply with</strong> the Philippine Red Cross Child Protection Policy and Code of Conduct. I will maintain the highest standards of ethical behavior.</p>
      </div>
      <div class="grid">
        <div><label>Complete Name *</label><input name="policy_signatory_name" type="text" required></div>
        <div><label>Date/Place *</label><input name="policy_date_place" type="text" required placeholder="e.g., December 9, 2025 - Virac, Catanduanes"></div>
      </div>

      <h3><i class="fas fa-certificate"></i> CERTIFICATION & CONFIDENTIALITY</h3>
      <div class="waiver-box">
        <p><strong>I certify that all information provided</strong> in this form is true and correct. I understand that any false information may result in disqualification or termination of my volunteer service.</p>
      </div>
      <div class="grid">
        <div><label>Complete Name *</label><input name="cert_signatory_name" type="text" required></div>
        <div><label>Date *</label><input name="cert_date" type="date" required></div>
      </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="form-navigation">
      <button type="button" class="btn-secondary" id="prevBtn" style="display:none;">
        <i class="fas fa-chevron-left"></i> Previous
      </button>
      <button type="button" class="btn-primary" id="nextBtn">
        Next <i class="fas fa-chevron-right"></i>
      </button>
      <button type="submit" class="submit-btn" id="submitBtn" style="display:none;">
        <i class="fas fa-paper-plane"></i> SUBMIT REGISTRATION
      </button>
    </div>
    
    <div class="success-message" id="successMessage">
      <i class="fas fa-check-circle"></i>
      Registration submitted successfully! You will be notified once reviewed.
    </div>
  </form>

  <script src="script/register_new.js"></script>
</body>
</html>
