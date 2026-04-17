<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Senior Citizen FORM</title>
  <link rel="stylesheet" type="text/css" href="/files/assets/css/fill.css">
  <style>
    body {
      margin: 0;
      font-family: Open Sans, Segoe UI, Arial, sans-serif;
      background: #f3f6fb;
      color: #1f2937;
    }

    * {
      box-sizing: border-box;
    }

    html, body {
      width: 100%;
      min-height: 100%;
    }

    .page-shell {
      max-width: 1360px;
      margin: 0 auto;
      padding: 24px 16px 40px;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      margin-bottom: 16px;
      padding: 18px 20px;
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 16px;
      box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    }

    .topbar-left {
      display: flex;
      align-items: center;
      gap: 14px;
      flex-wrap: wrap;
    }

    .topbar-title {
      margin: 0;
      font-size: 28px;
      line-height: 1.1;
      color: #111827;
    }

    .helper {
      margin: 8px 0 0;
      color: #6b7280;
      font-size: 14px;
    }

    .step-counter {
      display: inline-flex;
      align-items: center;
      margin-top: 10px;
      padding: 4px 10px;
      border-radius: 999px;
      background: #ecfdf5;
      color: #065f46;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 0.3px;
      text-transform: uppercase;
    }

    .meta {
      color: #6b7280;
      font-size: 13px;
      text-align: right;
      max-width: 300px;
    }

    .topbar-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 10px 14px;
      border-radius: 8px;
      background: #0f766e;
      color: #ffffff;
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      border: none;
      white-space: nowrap;
      transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .topbar-btn:hover {
      background: #115e59;
      color: #ffffff;
      text-decoration: none;
      transform: translateY(-1px);
    }

    .form-panel {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 16px;
      box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
      padding: 20px;
    }

    .form-panel .progress-bar {
      margin-bottom: 24px;
      padding: 0 10px;
    }

    .form-panel .progress-bar::before {
      background: #d1d5db;
      top: 50%;
    }

    .form-panel .step {
      color: #6b7280;
      font-size: 14px;
    }

    .form-panel .step.active {
      color: #0f766e;
      font-weight: 700;
    }

    .form-panel .step::before {
      background-color: #d1fae5;
      border-color: #a7f3d0;
    }

    .form-panel .step.active::before {
      background-color: #0f766e;
      box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.16);
    }

    .form-panel fieldset {
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      padding: 26px;
      margin-bottom: 18px;
      background: #ffffff;
    }

    .form-panel fieldset.active {
      display: block;
      animation: fadeIn 0.25s ease;
    }

    .form-panel legend {
      padding: 0 10px;
      color: #0f766e;
      font-size: 18px;
      font-weight: 700;
    }

    .form-panel label {
      color: #374151;
      margin-left: 0;
      font-weight: 600;
    }

    .form-panel .form-row {
      gap: 18px;
      margin-bottom: 18px;
    }

    .form-panel .form-group {
      min-width: 220px;
      margin-bottom: 0;
    }

    .form-panel .form-group.full-width {
      flex: 0 0 100%;
    }

    .form-panel .form-row:last-child {
      margin-bottom: 0;
    }

    .form-panel input,
    .form-panel select,
    .form-panel .other-input {
      background: #ffffff;
      color: #111827;
      border: 1px solid #d1d5db;
      border-radius: 12px;
      box-shadow: none;
    }

    .form-panel input:focus,
    .form-panel select:focus,
    .form-panel .other-input:focus {
      border-color: #0f766e;
      box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
    }

    .form-panel input::placeholder {
      color: #9ca3af;
      text-transform: none;
    }

    .form-panel .form-group:hover {
      transform: none;
    }

    .form-panel .skills-section {
      background: #f9fafb;
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      padding: 20px;
      margin-bottom: 0;
    }

    .form-panel .section-title {
      margin: 0 0 6px;
      font-size: 16px;
      font-weight: 700;
      color: #111827;
    }

    .form-panel .section-subtitle {
      margin: 0 0 14px;
      color: #6b7280;
      font-size: 13px;
    }

    .form-panel .skill-item label {
      color: #374151;
      font-weight: 500;
    }

    .form-panel .skill-item input[type="checkbox"] {
      accent-color: #0f766e;
    }

    .form-panel .add-child-btn {
      background: #ecfdf5;
      color: #065f46;
      border: 1px solid #a7f3d0;
      border-radius: 12px;
      padding: 10px 18px;
    }

    .form-panel .add-child-btn:hover {
      background: #d1fae5;
    }

    .form-panel .delete-child {
      background: #fee2e2;
      color: #b91c1c;
      border: 1px solid #fecaca;
      border-radius: 12px;
    }

    .form-panel .delete-child:hover {
      background: #fecaca;
    }

    .navigation {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      margin-top: 28px;
      padding: 0 2px;
    }

    .navigation button {
      min-width: 140px;
      padding: 12px 18px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      border: 1px solid transparent;
      transition: transform 0.2s ease, background-color 0.2s ease;
    }

    #prevBtn {
      background: #f3f4f6;
      color: #374151;
      border-color: #d1d5db;
    }

    #nextBtn {
      background: #0f766e;
      color: #ffffff;
    }

    #prevBtn:hover {
      background: #e5e7eb;
    }

    #nextBtn:hover {
      background: #115e59;
    }

    @media (max-width: 960px) {
      .topbar {
        flex-direction: column;
        align-items: flex-start;
      }

      .meta {
        text-align: left;
        max-width: none;
      }
    }

    @media (max-width: 768px) {
      .form-panel {
        padding: 16px;
      }

      .form-panel fieldset {
        padding: 18px;
      }

      .form-row {
        flex-direction: column;
      }

      .form-group {
        width: 100%;
        margin-bottom: 15px;
      }

      .form-panel .form-row {
        gap: 12px;
        margin-bottom: 14px;
      }

      .form-panel .form-group {
        min-width: 100%;
      }

      .navigation {
        flex-direction: column;
      }

      .navigation button {
        width: 100%;
      }

      .skills-grid {
        flex-direction: column;
      }

      .skills-column {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="page-shell">
    <div class="topbar">
      <div class="topbar-left">
        <a class="topbar-btn" href="/osca-dashboard">← Back to Dashboard</a>
        <div>
          <h1 class="topbar-title" id="formTitle">Senior Citizen FORM</h1>
          <p class="helper">Complete the intake form below. The layout follows the same color palette and spacing as the dashboard.</p>
          <div class="step-counter" id="stepCounter">Step 1 of 6: Personal Information</div>
        </div>
      </div>
      <div class="meta">Barangay and purok options are loaded from the PHP controller.</div>
    </div>

    <form id="housingForm" class="form-panel" action="/add-data" method="post">
      <div class="progress-bar">
        <div class="step active"><span>Personal Information</span></div>
        <div class="step"><span>Contact Information</span></div>
        <div class="step"><span>Family Composition</span></div>
        <div class="step"><span>ID'S</span></div>
        <div class="step"><span>Education / HR Profile</span></div>
        <div class="step"><span>Community Service</span></div>
      </div>

      <fieldset id="personalInfo" class="active">
        <legend>Personal Information</legend>
        <div class="form-row">
          <div class="form-group"><label for="first_name">First Name</label><input type="text" id="first_name" name="first_name" required placeholder="First Name"></div>
          <div class="form-group"><label for="middle_name">Middle Name</label><input type="text" id="middle_name" name="middle_name" placeholder="Middle Name"></div>
          <div class="form-group"><label for="last_name">Last Name</label><input type="text" id="last_name" name="last_name" required placeholder="Last Name"></div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="barangay">Barangay</label>
            <select id="barangay" name="barangay" required onchange="updatePurokOptions()">
              <option value="" disabled selected>Select Barangay</option>
              <?php foreach (($barangays ?? []) as $barangayName => $purokList): ?>
                <option value="<?= htmlspecialchars((string) $barangayName, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $barangayName, ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group"><label for="purok">Purok</label><select id="purok" name="purok" required><option value="" disabled selected>Select Purok</option></select></div>
          <div class="form-group"><label for="gender">Gender</label><select id="gender" name="gender" required><option value="">-- Select One --</option><option value="Male">Male</option><option value="Female">Female</option><option value="Other">Other</option><option value="Prefer not to say">Prefer not to say</option></select></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label for="birthday">Birthday</label><input type="date" id="birthday" name="birthday" required onchange="calculateAge()"></div>
          <div class="form-group"><label for="age">Age</label><input type="number" id="age" name="age" required placeholder="Age" readonly></div>
          <div class="form-group"><label for="religion">Religion</label><input type="text" id="religion" name="religion" required placeholder="Religion"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label for="pension">Current Pension (specify)</label><input type="number" id="pension" name="pension" placeholder="100,000"></div>
          <div class="form-group"><label for="service">Service/Business/Employment(specify)</label><input type="text" id="service" name="service" placeholder="Service"></div>
          <div class="form-group"><label for="capability_to_travel">Capability to Travel</label><select id="capability_to_travel" name="capability_to_travel" required><option value="">-- Select One --</option><option value="Yes">Yes</option><option value="No">No</option></select></div>
        </div>
        <div class="form-row"><div class="form-group"><label for="place_of_birth">Place of Birth</label><input type="text" id="place_of_birth" name="place_of_birth" required placeholder="Place of Birth"></div></div>
        <div class="form-row">
          <div class="form-group"><label for="civil_status">Civil Status</label><select id="civil_status" name="civil_status" required onchange="toggleSpouseInput()"><option value="Single but Head of the Family">Single but Head of the Family</option><option value="Single">Single</option><option value="Married">Married</option><option value="Widowed">Widowed</option></select></div>
          <div class="form-group" id="spouseGroup" style="display:none;"><label for="spouse_name">Name of Spouse</label><input type="text" id="spouse_name" name="spouse_name" placeholder="Name of Spouse"></div>
        </div>
      </fieldset>

      <fieldset id="contactInformation">
        <legend>CONTACT INFORMATION</legend>
        <div id="contactsContainer">
          <div class="contact-entry" data-contact-id="1">
            <div class="form-row">
              <div class="form-group"><label>Contact Type</label><select class="contact-type" name="contacts[1][type]"><option value="primary">Primary</option><option value="secondary">Secondary</option><option value="emergency">Emergency</option></select></div>
              <div class="form-group"><label>Full Name</label><input type="text" name="contacts[1][name]"></div>
            </div>
            <div class="form-row">
              <div class="form-group"><label>Relationship</label><input type="text" name="contacts[1][relationship]"></div>
              <div class="form-group"><label>Phone Number</label><input type="tel" name="contacts[1][phone]" maxlength="11" pattern="\d{11}" title="Please enter exactly 11 digits"></div>
            </div>
            <div class="form-row">
              <div class="form-group"><label>Email Address</label><input type="email" name="contacts[1][email]"></div>
              <div class="form-group"><button type="button" class="delete-child" disabled>Remove</button></div>
            </div>
          </div>
        </div>
        <div class="form-row"><div class="form-group"><button type="button" id="addContact" class="add-child-btn">+ Add Another Contact</button></div></div>
      </fieldset>

      <fieldset id="familyComposition">
        <legend>Family Composition</legend>
        <div class="form-row">
          <div class="form-group"><label for="fatherLastName">Father's Last Name</label><input type="text" id="fatherLastName" name="fatherLastName" placeholder="Last Name"></div>
          <div class="form-group"><label for="fatherFirstName">Father's First Name</label><input type="text" id="fatherFirstName" name="fatherFirstName" placeholder="First Name"></div>
          <div class="form-group"><label for="fatherMiddleName">Father's Middle Name</label><input type="text" id="fatherMiddleName" name="fatherMiddleName" placeholder="Middle Name"></div>
          <div class="form-group"><label for="fatherExtension">Extension (Jr/Sr)</label><input type="text" id="fatherExtension" name="fatherExtension" placeholder="Extension (Jr, Sr)"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label for="motherLastName">Mother's Last Name</label><input type="text" id="motherLastName" name="motherLastName" placeholder="Last Name"></div>
          <div class="form-group"><label for="motherFirstName">Mother's First Name</label><input type="text" id="motherFirstName" name="motherFirstName" placeholder="First Name"></div>
          <div class="form-group"><label for="motherMiddleName">Mother's Middle Name</label><input type="text" id="motherMiddleName" name="motherMiddleName" placeholder="Middle Name"></div>
        </div>
        <div class="form-row">
          <legend>Child(ren) Information</legend>
          <div id="childrenContainer">
            <div class="child-entry">
              <div class="form-row">
                <div class="form-group"><label>Full Name</label><input type="text" name="childFullName[]" placeholder="Full Name"></div>
                <div class="form-group"><label>Occupation</label><input type="text" name="childOccupation[]" placeholder="Occupation"></div>
                <div class="form-group"><label>Age</label><input type="number" name="childAge[]" placeholder="Age"></div>
                <div class="form-group"><label>Working/Not Working</label><select name="childWorkingStatus[]"><option value="not_working">Select</option><option value="not_working">Not Working</option><option value="working">Working</option></select></div>
                <div class="form-group"><label>Income</label><input type="number" name="childIncome[]" placeholder="Income"></div>
                <button type="button" class="delete-child" style="display:none;">Delete</button>
              </div>
            </div>
          </div>
          <button type="button" id="addChild" class="add-child-btn">➕ Add Another Child</button>
        </div>
      </fieldset>

      <fieldset id="requiredDocuments" class="hidden">
        <legend>ID'S</legend>
        <div class="form-row">
          <div class="form-group"><label for="osca_id">OSCA ID No</label><input type="number" id="osca_id" name="osca_id"></div>
          <div class="form-group"><label for="gsis_sss_no">GSIS/SSS No</label><input type="number" id="gsis_sss_no" name="gsis_sss_no"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label for="tin_no">TIN No</label><input type="number" id="tin_no" name="tin_no"></div>
          <div class="form-group"><label for="philhealth_no">Philhealth No</label><input type="number" id="philhealth_no" name="philhealth_no"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label for="sc_association_id">SC Association / Org ID No</label><input type="number" id="sc_association_id" name="sc_association_id"></div>
          <div class="form-group"><label for="other_govt_id">Other Gov't ID</label><input type="number" id="other_govt_id" name="other_govt_id"></div>
        </div>
      </fieldset>

      <fieldset id="educationalAttainment">
        <legend>Educational Attainment</legend>
        <div class="form-group"><label for="educational_attainment">Select Educational Level:</label><select id="educational_attainment" name="educational_attainment" required><option value="">-- Select One --</option><option value="Elementary Level">Elementary Level</option><option value="Elementary Graduate">Elementary Graduate</option><option value="High School Graduate">High School Graduate</option><option value="College Level">College Level</option><option value="College Graduate">College Graduate</option><option value="Post Graduate">Post Graduate</option><option value="Vocational">Vocational</option><option value="Not Attended School">Not Attended School</option></select></div>

        <div class="form-section skills-section">
          <h3 class="section-title">Areas of Specialization / Technical Skills</h3>
          <p class="section-subtitle">Check all applicable</p>
          <div class="skills-grid">
            <div class="skills-column">
              <div class="skill-item"><input type="checkbox" id="skill-medical" name="skills[]" value="Medical"><label for="skill-medical">Medical</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-dental" name="skills[]" value="Dental"><label for="skill-dental">Dental</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-fishing" name="skills[]" value="Fishing"><label for="skill-fishing">Fishing</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-engineering" name="skills[]" value="Engineering"><label for="skill-engineering">Engineering</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-barber" name="skills[]" value="Barber"><label for="skill-barber">Barber</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-evangelization" name="skills[]" value="Evangelization"><label for="skill-evangelization">Evangelization</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-millwright" name="skills[]" value="Millwright"><label for="skill-millwright">Millwright</label></div>
            </div>
            <div class="skills-column">
              <div class="skill-item"><input type="checkbox" id="skill-teaching" name="skills[]" value="Teaching"><label for="skill-teaching">Teaching</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-counseling" name="skills[]" value="Counseling"><label for="skill-counseling">Counseling</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-cooking" name="skills[]" value="Cooking"><label for="skill-cooking">Cooking</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-carpenter" name="skills[]" value="Carpenter"><label for="skill-carpenter">Carpenter</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-mason" name="skills[]" value="Mason"><label for="skill-mason">Mason</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-tailor" name="skills[]" value="Tailor"><label for="skill-tailor">Tailor</label></div>
            </div>
            <div class="skills-column">
              <div class="skill-item"><input type="checkbox" id="skill-legal" name="skills[]" value="Legal Services"><label for="skill-legal">Legal Services</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-farming" name="skills[]" value="Farming"><label for="skill-farming">Farming</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-arts" name="skills[]" value="Arts"><label for="skill-arts">Arts</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-plumber" name="skills[]" value="Plumber"><label for="skill-plumber">Plumber</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-sapatero" name="skills[]" value="Sapatero"><label for="skill-sapatero">Sapatero</label></div>
              <div class="skill-item"><input type="checkbox" id="skill-chef" name="skills[]" value="Chef/Cook"><label for="skill-chef">Chef/Cook</label></div>
            </div>
          </div>
          <div class="skill-item other-skill"><input type="checkbox" id="skill-other" name="skills[]" value="Other"><label for="skill-other">Others, specify:</label><input type="text" id="skill-other-text" name="skill_other_text" class="other-input"></div>
        </div>
      </fieldset>

      <fieldset id="service">
        <div class="form-section skills-section">
          <h3 class="section-title">Community Service and Involvement (Check all applicable)</h3>
          <div class="skills-grid">
            <div class="skills-column">
              <div class="skill-item"><input type="checkbox" id="service-medical" name="community_service[]" value="Medical"><label for="service-medical">Medical</label></div>
              <div class="skill-item"><input type="checkbox" id="service-community-leader" name="community_service[]" value="Community / Organization Leader"><label for="service-community-leader">Community / Organization Leader</label></div>
              <div class="skill-item"><input type="checkbox" id="service-neighborhood" name="community_service[]" value="Neighborhood Support Services"><label for="service-neighborhood">Neighborhood Support Services</label></div>
              <div class="skill-item"><input type="checkbox" id="service-counseling" name="community_service[]" value="Counseling / Referral"><label for="service-counseling">Counseling / Referral</label></div>
            </div>
            <div class="skills-column">
              <div class="skill-item"><input type="checkbox" id="service-resource" name="community_service[]" value="Resource Volunteer"><label for="service-resource">Resource Volunteer</label></div>
              <div class="skill-item"><input type="checkbox" id="service-dental" name="community_service[]" value="Dental"><label for="service-dental">Dental</label></div>
              <div class="skill-item"><input type="checkbox" id="service-legal" name="community_service[]" value="Legal Services"><label for="service-legal">Legal Services</label></div>
              <div class="skill-item"><input type="checkbox" id="service-sponsorship" name="community_service[]" value="Sponsorship"><label for="service-sponsorship">Sponsorship</label></div>
            </div>
            <div class="skills-column">
              <div class="skill-item"><input type="checkbox" id="service-beautification" name="community_service[]" value="Community Beautification"><label for="service-beautification">Community Beautification</label></div>
              <div class="skill-item"><input type="checkbox" id="service-visits" name="community_service[]" value="Friendly Visits"><label for="service-visits">Friendly Visits</label></div>
              <div class="skill-item"><input type="checkbox" id="service-religious" name="community_service[]" value="Religious"><label for="service-religious">Religious</label></div>
            </div>
          </div>
          <div class="skill-item"><input type="checkbox" id="service-other" name="community_service[]" value="Other"><label for="service-other">Others, specify:</label><input type="text" id="community-service-other-text" name="community_service_other_text" class="other-input"></div>
        </div>
      </fieldset>

      <div class="navigation"><button type="button" id="prevBtn" onclick="nextPrev(-1)">BACK</button><button type="button" id="nextBtn" onclick="nextPrev(1)">NEXT</button></div>
    </form>
  </div>

  <script>
    const puroks = <?= json_encode($barangays ?? [], JSON_UNESCAPED_UNICODE) ?>;

    function updatePurokOptions() {
      const barangay = document.getElementById('barangay').value;
      const purokSelect = document.getElementById('purok');
      purokSelect.innerHTML = '<option value="" disabled selected>Select Purok</option>';
      if (puroks[barangay]) {
        puroks[barangay].forEach(purok => {
          const option = document.createElement('option');
          option.value = purok;
          option.textContent = purok;
          purokSelect.appendChild(option);
        });
      }
    }

    function calculateAge() {
      const birthday = document.getElementById('birthday');
      const age = document.getElementById('age');
      if (!birthday || !age || !birthday.value) return;
      const dob = new Date(birthday.value);
      if (Number.isNaN(dob.getTime())) return;
      const diff = Date.now() - dob.getTime();
      age.value = new Date(diff).getUTCFullYear() - 1970;
    }

    function toggleSpouseInput() {
      const civilStatus = document.getElementById('civil_status');
      const spouseGroup = document.getElementById('spouseGroup');
      if (!civilStatus || !spouseGroup) return;
      spouseGroup.style.display = civilStatus.value === 'Married' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
      if (document.getElementById('barangay').value) {
        updatePurokOptions();
      }
      toggleSpouseInput();
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript" src="/files/assets/js/fill.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const stepCounter = document.getElementById('stepCounter');
      const stepLabels = [
        'Personal Information',
        'Contact Information',
        'Family Composition',
        "ID'S",
        'Education / HR Profile',
        'Community Service'
      ];

      if (typeof window.showTab === 'function') {
        const originalShowTab = window.showTab;
        window.showTab = function (n) {
          originalShowTab(n);
          if (stepCounter && stepLabels[n]) {
            stepCounter.textContent = 'Step ' + (n + 1) + ' of ' + stepLabels.length + ': ' + stepLabels[n];
          }
        };
      }

      if (typeof window.nextPrev === 'function') {
        const originalNextPrev = window.nextPrev;
        window.nextPrev = function (n) {
          const result = originalNextPrev(n);
          if (result !== false) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
          }
          return result;
        };
      }

      if (stepCounter) {
        stepCounter.textContent = 'Step 1 of ' + stepLabels.length + ': ' + stepLabels[0];
      }
    });
  </script>
</body>
</html>
