<?php
$editSenior = is_array($editSenior ?? null) ? $editSenior : null;
$isEditMode = !empty($isEditMode);
$isModal = isset($_GET['modal']) && $_GET['modal'] === '1';
$isViewMode = isset($_GET['view']) && $_GET['view'] === '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/logo-ebmag.png'), ENT_QUOTES) ?>">
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
      width: 100%;
      justify-content: center;
      position: relative;
    }

    .topbar-title {
      margin: 0;
      font-size: 28px;
      line-height: 1.1;
      color: #111827;
      text-align: center;
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
      position: absolute;
      left: 0;
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
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 24px;
      padding: 0 10px;
      position: relative;
      counter-reset: form-step;
      flex-wrap: nowrap;
      overflow-x: auto;
    }

    .form-panel .progress-bar::before {
      content: "";
      position: absolute;
      top: 18px;
      left: 34px;
      right: 34px;
      height: 2px;
      background: #d1d5db;
      z-index: 0;
    }

    .form-panel .step {
      color: #6b7280;
      font-size: 12px;
      cursor: default;
      text-align: center;
      min-width: 126px;
      position: relative;
      z-index: 1;
      white-space: nowrap;
      font-weight: 600;
    }

    .form-panel .step.active {
      color: #0f766e;
      font-weight: 700;
    }

    .form-panel .step::before {
      counter-increment: form-step;
      content: counter(form-step);
      display: grid;
      place-items: center;
      width: 36px;
      height: 36px;
      margin: 0 auto 8px;
      border-radius: 50%;
      background-color: #94a3b8;
      color: #ffffff;
      font-size: 13px;
      font-weight: 700;
      border: none;
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
      min-height: 420px;
      display: none;
    }

    .form-panel fieldset.active,
    .form-panel fieldset.active-step {
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
      justify-content: flex-end;
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

    #prevBtn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
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

      .topbar-left {
        justify-content: flex-start;
      }

      .topbar-btn {
        position: static;
      }

      .topbar-title {
        text-align: left;
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
        min-height: 0;
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

      .form-panel .progress-bar {
        justify-content: flex-start;
      }

      .form-panel .step {
        min-width: 110px;
      }
    }
  </style>
</head>
<body class="<?= $isModal ? 'modal-mode' : '' ?><?= $isViewMode ? ' view-mode' : '' ?>">
  <div class="page-shell">
    <div class="topbar">
      <div class="topbar-left">
        <?php if (!$isModal): ?>
          <a class="topbar-btn" href="/osca-dashboard">← Back to Dashboard</a>
        <?php endif; ?>
        <div>
          <h1 class="topbar-title" id="formTitle"><?= $isViewMode ? 'View Senior Citizen FORM' : ($isEditMode ? 'Edit Senior Citizen FORM' : 'Senior Citizen FORM') ?></h1>
        </div>
      </div>
    </div>

    <form id="housingForm" class="form-panel" action="<?= $isEditMode ? '/update-senior' : '/add-data' ?>" method="post">
      <input type="hidden" id="residentId" name="residentId" value="<?= (int) (($editSenior['_id'] ?? 0)) ?>">
      <div class="progress-bar">
        <div class="step active"><span>Personal Information</span></div>
        <div class="step"><span>Contact Information</span></div>
        <div class="step"><span>Family Composition</span></div>
        <div class="step"><span>IDs</span></div>
        <div class="step"><span>Education / HR Profile</span></div>
        <div class="step"><span>Community Service</span></div>
      </div>

      <fieldset id="personalInfo" class="active">
        <legend>Personal Information</legend>
        <div class="form-row">
          <div class="form-group"><label for="first_name">First Name</label><input type="text" id="first_name" name="first_name" required placeholder="First Name"></div>
          <div class="form-group"><label for="middle_name">Middle Name</label><input type="text" id="middle_name" name="middle_name" required placeholder="Middle Name"></div>
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
          <div class="form-group"><label for="pension">Current Pension (specify)</label><input type="number" id="pension" name="pension" required placeholder="100,000"></div>
          <div class="form-group"><label for="service">Service/Business/Employment(specify)</label><input type="text" id="service" name="service" required placeholder="Service"></div>
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
              <div class="form-group"><label>Contact Type</label><select class="contact-type" name="contacts[1][type]" required><option value="primary">Primary</option><option value="secondary">Secondary</option><option value="emergency">Emergency</option></select></div>
              <div class="form-group"><label>Full Name</label><input type="text" name="contacts[1][name]" required></div>
            </div>
            <div class="form-row">
              <div class="form-group"><label>Relationship</label><input type="text" name="contacts[1][relationship]" required></div>
              <div class="form-group"><label>Phone Number</label><input type="tel" name="contacts[1][phone]" maxlength="11" pattern="\d{11}" title="Please enter exactly 11 digits" required></div>
            </div>
            <div class="form-row">
              <div class="form-group"><label>Email Address</label><input type="email" name="contacts[1][email]" required></div>
              <div class="form-group"><button type="button" class="delete-child">Remove</button></div>
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
                <div class="form-group"><label>Working/Not Working</label><select class="child-working-status" name="childWorkingStatus[]"><option value="not_working">Select</option><option value="not_working">Not Working</option><option value="working">Working</option></select></div>
                <div class="form-group income-field" style="display:none;"><label>Income</label><input type="number" name="childIncome[]" placeholder="Income"></div>
                <button type="button" class="delete-child" style="display:none;">Delete</button>
              </div>
            </div>
          </div>
          <button type="button" id="addChild" class="add-child-btn">➕ Add Another Child</button>
        </div>
      </fieldset>

      <fieldset id="identificationDocuments">
        <legend>Identification Documents</legend>
        <div class="form-row">
          <div class="form-group"><label for="osca_id">OSCA/Senior Citizen ID No.</label><input type="text" id="osca_id" name="osca_id" class="numeric-only" data-max-digits="10" maxlength="10" pattern="\d{10}" title="Please enter exactly 10 digits" placeholder="e.g. 1234567890" inputmode="numeric"></div>
          <div class="form-group"><label for="gsis_id">GSIS</label><input type="text" id="gsis_id" name="gsis_id" class="numeric-only" data-max-digits="11" maxlength="11" pattern="\d{11}" title="Please enter exactly 11 digits" placeholder="e.g. 12345678901" inputmode="numeric"></div>
          <div class="form-group"><label for="sss_id">SSS</label><input type="text" id="sss_id" name="sss_id" class="numeric-only" data-max-digits="10" maxlength="10" pattern="\d{10}" title="Please enter exactly 10 digits" placeholder="e.g. 1234567890" inputmode="numeric"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label for="philhealth_id">Philhealth</label><input type="text" id="philhealth_id" name="philhealth_id" class="numeric-only" data-max-digits="12" maxlength="12" pattern="\d{12}" title="Please enter exactly 12 digits" placeholder="e.g. 123456789012" inputmode="numeric"></div>
          <div class="form-group full-width"><label for="other_id">Other's Please Specify</label><input type="text" id="other_id" name="other_id" placeholder="Other's Please Specify"></div>
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

      <div class="navigation"><button type="button" id="prevBtn">BACK</button><button type="button" id="nextBtn">NEXT</button></div>
    </form>
  </div>

  <script>
    const puroks = <?= json_encode($barangays ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const isEditMode = <?= $isEditMode ? 'true' : 'false' ?>;
    const isModal = <?= $isModal ? 'true' : 'false' ?>;
    const isViewMode = <?= $isViewMode ? 'true' : 'false' ?>;
    const editSenior = <?= json_encode($editSenior, JSON_UNESCAPED_UNICODE) ?>;

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

      const today = new Date();
      let years = today.getFullYear() - dob.getFullYear();
      const monthDiff = today.getMonth() - dob.getMonth();
      const birthdayNotReached = monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate());
      if (birthdayNotReached) {
        years -= 1;
      }

      age.value = years >= 0 ? years : '';

      if (years >= 0 && years < 60) {
        if (typeof window.Swal !== 'undefined' && typeof window.Swal.fire === 'function') {
          window.Swal.fire({
            icon: 'warning',
            title: 'Age Requirement',
            text: 'You must be 60 years old to fill this form',
            confirmButtonColor: '#0f766e'
          });
        } else {
          alert('You must be 60+ to fill this form');
        }
      }
    }

    function toggleSpouseInput() {
      const civilStatus = document.getElementById('civil_status');
      const spouseGroup = document.getElementById('spouseGroup');
      const spouseInput = document.getElementById('spouse_name');
      if (!civilStatus || !spouseGroup) return;
      const isMarried = civilStatus.value === 'Married';
      spouseGroup.style.display = isMarried ? 'block' : 'none';
      if (spouseInput) {
        spouseInput.required = isMarried;
        if (!isMarried) {
          spouseInput.value = '';
          spouseInput.style.borderColor = '';
        }
      }
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
      const form = document.getElementById('housingForm');
      const fieldsets = Array.from(document.querySelectorAll('#housingForm fieldset'));
      const stepElements = Array.from(document.querySelectorAll('.progress-bar .step'));
      const prevBtnNav = document.getElementById('prevBtn');
      const nextBtnNav = document.getElementById('nextBtn');
      const stepCounter = document.getElementById('stepCounter');
      const stepLabels = [
        'Personal Information',
        'Contact Information',
        'Family Composition',
        'IDs',
        'Education / HR Profile',
        'Community Service'
      ];

      let wizardIndex = 0;
      let isConfirmedSubmitInProgress = false;

      function showAgeWarningModal() {
        if (typeof window.Swal !== 'undefined' && typeof window.Swal.fire === 'function') {
          window.Swal.fire({
            icon: 'warning',
            title: 'Age Requirement',
            text: 'You must be 60+ to fill this form',
            confirmButtonColor: '#0f766e'
          });
          return;
        }

        alert('You must be 60+ to fill this form');
      }

      function isSeniorAgeValid(showModal) {
        const ageInput = document.getElementById('age');
        if (!ageInput) return true;

        const parsedAge = Number(ageInput.value);
        const validAge = Number.isFinite(parsedAge) && parsedAge >= 60;

        ageInput.setCustomValidity(validAge ? '' : 'You must be 60+ to fill this form');

        if (!validAge && showModal) {
          showAgeWarningModal();
          if (typeof ageInput.reportValidity === 'function') {
            ageInput.reportValidity();
          }
        }

        return validAge;
      }

      async function submitSeniorForm() {
        const submitButton = document.getElementById('nextBtn');
        const formData = new FormData(form);

        const requestData = {
          residentId: isEditMode ? Number(document.getElementById('residentId')?.value || 0) : undefined,
          first_name: formData.get('first_name'),
          middle_name: formData.get('middle_name'),
          last_name: formData.get('last_name'),
          barangay: formData.get('barangay'),
          purok: formData.get('purok'),
          birthday: formData.get('birthday'),
          age: parseInt(formData.get('age'), 10),
          place_of_birth: formData.get('place_of_birth'),
          marital_status: formData.get('civil_status'),
          gender: formData.get('gender'),
          osca_id_number: formData.get('osca_id'),
          gsis_sss: formData.get('gsis_id') || formData.get('sss_id') || formData.get('gsis_sss_no'),
          philhealth: formData.get('philhealth_id') || formData.get('philhealth_no'),
          tin: formData.get('tin_no'),
          other_govt_id: formData.get('other_id') || formData.get('other_govt_id'),
          service_business_employment: formData.get('service'),
          current_pension: formData.get('pension'),
          capability_to_travel: formData.get('capability_to_travel') === 'Yes' ? 'Yes' : 'No',
          spouse_name: formData.get('spouse_name') || undefined,
          father_last_name: formData.get('fatherLastName'),
          father_first_name: formData.get('fatherFirstName'),
          father_middle_name: formData.get('fatherMiddleName'),
          father_extension: formData.get('fatherExtension') || undefined,
          mother_last_name: formData.get('motherLastName'),
          mother_first_name: formData.get('motherFirstName'),
          mother_middle_name: formData.get('motherMiddleName'),
          educational_attainment: formData.get('educational_attainment') ? [formData.get('educational_attainment')] : [],
          community_service: Array.from(document.querySelectorAll('#service input[name="community_service[]"]:checked')).map(function (el) { return el.value; }),
          community_service_other_text: document.getElementById('community-service-other-text')?.value || undefined,
          identifying_information: {
            name: {
              first_name: formData.get('first_name'),
              middle_name: formData.get('middle_name'),
              last_name: formData.get('last_name')
            },
            address: {
              barangay: formData.get('barangay'),
              purok: formData.get('purok')
            },
            date_of_birth: formData.get('birthday'),
            age: parseInt(formData.get('age'), 10),
            place_of_birth: formData.get('place_of_birth'),
            marital_status: formData.get('civil_status'),
            gender: formData.get('gender'),
            osca_id_number: formData.get('osca_id'),
            gsis_sss: formData.get('gsis_id') || formData.get('sss_id') || formData.get('gsis_sss_no'),
            philhealth: formData.get('philhealth_id') || formData.get('philhealth_no'),
            tin: formData.get('tin_no'),
            other_govt_id: formData.get('other_id') || formData.get('other_govt_id'),
            service_business_employment: formData.get('service'),
            current_pension: formData.get('pension'),
            capability_to_travel: formData.get('capability_to_travel') === 'Yes' ? 'Yes' : 'No',
            religion: formData.get('religion'),
            contacts: []
          },
          family_composition: {
            spouse: {
              name: formData.get('spouse_name') || undefined
            },
            father: {
              last_name: formData.get('fatherLastName'),
              first_name: formData.get('fatherFirstName'),
              middle_name: formData.get('fatherMiddleName'),
              extension: formData.get('fatherExtension') || undefined
            },
            mother: {
              last_name: formData.get('motherLastName'),
              first_name: formData.get('motherFirstName'),
              middle_name: formData.get('motherMiddleName')
            },
            children: Array.from(document.querySelectorAll('.child-entry')).map(function (child) {
              return {
                full_name: child.querySelector('input[name="childFullName[]"]').value,
                occupation: child.querySelector('input[name="childOccupation[]"]').value,
                age: parseInt(child.querySelector('input[name="childAge[]"]').value, 10) || undefined,
                working_status: child.querySelector('select[name="childWorkingStatus[]"]').value,
                income: child.querySelector('input[name="childIncome[]"]').value || undefined
              };
            }).filter(function (child) {
              return child.full_name;
            })
          },
          education_hr_profile: {
            educational_attainment: formData.get('educational_attainment'),
            skills: Array.from(document.querySelectorAll('#educationalAttainment input[name="skills[]"]:checked')).map(function (el) {
              return el.value;
            }),
            skill_other_text: document.getElementById('skill-other-text')?.value || undefined
          },
          community_service: Array.from(document.querySelectorAll('#service input[name="community_service[]"]:checked')).map(function (el) { return el.value; }),
          community_service_other_text: document.getElementById('community-service-other-text')?.value || undefined
        };

        requestData.identifying_information.contacts = Array.from(document.querySelectorAll('.contact-entry')).map(function (contact) {
          return {
            type: contact.querySelector('.contact-type').value,
            name: contact.querySelector('input[name$="[name]"]').value,
            relationship: contact.querySelector('input[name$="[relationship]"]').value,
            phone: contact.querySelector('input[name$="[phone]"]').value,
            email: contact.querySelector('input[name$="[email]"]').value || undefined
          };
        });

        if (typeof window.Swal !== 'undefined' && typeof window.Swal.fire === 'function') {
          window.Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we save your information',
            allowOutsideClick: false,
            didOpen: function () {
              window.Swal.showLoading();
            }
          });
        }

        if (submitButton) {
          submitButton.disabled = true;
        }

        try {
          const response = await fetch(form.action, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestData)
          });

          const data = await response.json();

          if (!response.ok) {
            throw data;
          }

          if (isEditMode && isModal && window.parent && window.parent !== window) {
            window.parent.postMessage({ type: 'senior-edit-saved', data: data.data || null }, window.location.origin);
            return;
          }
          form.reset();
          window.location.href = '/osca-dashboard?refresh=' + Date.now();
        } catch (error) {
          console.error('Error:', error);
          if (typeof window.Swal !== 'undefined' && typeof window.Swal.fire === 'function') {
            if (error && error.alert) {
              window.Swal.fire(error.alert);
            } else {
              window.Swal.fire({
                title: 'Error',
                text: error?.message || 'An error occurred while saving the data',
                icon: 'error'
              });
            }
          } else {
            alert(error?.message || 'An error occurred while saving the data');
          }
          throw error;
        } finally {
          if (submitButton) {
            submitButton.disabled = false;
          }
        }
      }

      window.submitForm = submitSeniorForm;

      function confirmBeforeSubmit() {
        if (typeof window.Swal !== 'undefined' && typeof window.Swal.fire === 'function') {
          return window.Swal.fire({
            title: 'Submit Senior Citizen Form?',
            text: 'Please confirm all information before submitting.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#0f766e'
          }).then(function (result) {
            return !!result.isConfirmed;
          });
        }

        return Promise.resolve(window.confirm('Submit Senior Citizen Form?'));
      }

      function runConfirmedSubmit() {
        if (isConfirmedSubmitInProgress) return;
        isConfirmedSubmitInProgress = true;

        const submitResult = submitSeniorForm();
        if (submitResult && typeof submitResult.finally === 'function') {
          submitResult.finally(function () {
            isConfirmedSubmitInProgress = false;
          });
        } else {
          isConfirmedSubmitInProgress = false;
        }
      }

      function renderStep(index) {
        if (!fieldsets.length) return;
        wizardIndex = Math.max(0, Math.min(index, fieldsets.length - 1));

        fieldsets.forEach(function (fieldset, i) {
          const isActive = i === wizardIndex;
          fieldset.classList.toggle('active-step', isActive);
          fieldset.style.display = isActive ? 'block' : 'none';
          fieldset.setAttribute('aria-hidden', isActive ? 'false' : 'true');
        });

        stepElements.forEach(function (stepEl, i) {
          stepEl.classList.toggle('active', i === wizardIndex);
        });

        if (prevBtnNav) {
          prevBtnNav.style.display = 'inline-flex';
          prevBtnNav.disabled = wizardIndex === 0;
        }

        if (nextBtnNav) {
          nextBtnNav.textContent = wizardIndex === fieldsets.length - 1 ? 'SUBMIT' : 'NEXT';
        }

        if (stepCounter && stepLabels[wizardIndex]) {
          stepCounter.textContent = 'Step ' + (wizardIndex + 1) + ' of ' + stepLabels.length + ': ' + stepLabels[wizardIndex];
        }
      }

      function moveStep(delta) {
        if (isViewMode && delta > 0 && wizardIndex >= fieldsets.length - 1) {
          if (isModal && window.parent && window.parent !== window) {
            window.parent.postMessage({ type: 'senior-view-close' }, window.location.origin);
            return;
          }
          window.history.back();
          return;
        }

        if (delta > 0) {
          const currentFieldset = fieldsets[wizardIndex];
          const requiredFields = currentFieldset ? Array.from(currentFieldset.querySelectorAll('[required]')) : [];

          const hasInvalidRequired = requiredFields.some(function (field) {
            const isValid = typeof field.checkValidity === 'function' ? field.checkValidity() : String(field.value || '').trim() !== '';
            if (!isValid && typeof field.reportValidity === 'function') {
              field.reportValidity();
            }
            return !isValid;
          });

          if (hasInvalidRequired) {
            return;
          }

          if (wizardIndex === 0 && !isSeniorAgeValid(true)) {
            return;
          }

          if (typeof window.validateCurrentStep === 'function' && !window.validateCurrentStep(wizardIndex)) {
            return;
          }
        }

        const nextIndex = wizardIndex + delta;
        if (nextIndex >= fieldsets.length) {
          if (!isSeniorAgeValid(true)) {
            return;
          }

          confirmBeforeSubmit().then(function (confirmed) {
            if (!confirmed) return;

            runConfirmedSubmit();
          });
          return;
        }

        renderStep(nextIndex);
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }

      window.showTab = function (n) {
        renderStep(n);
      };

      window.nextPrev = function (n) {
        moveStep(n);
      };

      if (prevBtnNav) {
        prevBtnNav.addEventListener('click', function () {
          moveStep(-1);
        });
      }

      if (nextBtnNav) {
        nextBtnNav.addEventListener('click', function () {
          moveStep(1);
        });
      }

      if (form) {
        form.addEventListener('submit', function (event) {
          if (isConfirmedSubmitInProgress) {
            return;
          }

          event.preventDefault();

          if (!isSeniorAgeValid(true)) {
            return;
          }

          confirmBeforeSubmit().then(function (confirmed) {
            if (!confirmed) return;
            runConfirmedSubmit();
          });
        });
      }

      // Contact Management
      let contactCounter = 1;

      function updateDeleteButtonVisibility() {
        const contactEntries = document.querySelectorAll('.contact-entry');
        const deleteButtons = document.querySelectorAll('.contact-entry .delete-child');
        deleteButtons.forEach(function (btn) {
          btn.disabled = contactEntries.length <= 1;
          btn.style.display = contactEntries.length <= 1 ? 'none' : 'block';
        });
      }

      // Child Management
      let childCounter = 0;

      function toggleIncomeField(selectElement) {
        const isWorking = selectElement.value === 'working';
        const incomeField = selectElement.closest('.form-row').querySelector('.income-field');
        if (incomeField) {
          incomeField.style.display = isWorking ? 'block' : 'none';
          const incomeInput = incomeField.querySelector('input');
          if (incomeInput) {
            incomeInput.required = isWorking;
          }
        }
      }

      function updateDeleteChildButtonVisibility() {
        const childEntries = document.querySelectorAll('.child-entry');
        const deleteButtons = document.querySelectorAll('.child-entry .delete-child');
        deleteButtons.forEach(function (btn) {
          btn.style.display = childEntries.length <= 1 ? 'none' : 'block';
        });
      }

      function addChildEntry() {
        childCounter++;
        const childrenContainer = document.getElementById('childrenContainer');
        const newChild = document.createElement('div');
        newChild.className = 'child-entry';
        newChild.innerHTML = `
          <div class="form-row">
            <div class="form-group"><label>Full Name</label><input type="text" name="childFullName[]" placeholder="Full Name"></div>
            <div class="form-group"><label>Occupation</label><input type="text" name="childOccupation[]" placeholder="Occupation"></div>
            <div class="form-group"><label>Age</label><input type="number" name="childAge[]" placeholder="Age"></div>
            <div class="form-group"><label>Working/Not Working</label><select class="child-working-status" name="childWorkingStatus[]"><option value="not_working">Select</option><option value="not_working">Not Working</option><option value="working">Working</option></select></div>
            <div class="form-group income-field" style="display:none;"><label>Income</label><input type="number" name="childIncome[]" placeholder="Income"></div>
            <button type="button" class="delete-child">Delete</button>
          </div>
        `;
        childrenContainer.appendChild(newChild);

        // Attach working status change handler
        const workingStatusSelect = newChild.querySelector('.child-working-status');
        workingStatusSelect.addEventListener('change', function () {
          toggleIncomeField(this);
        });

        // Attach delete handler
        const deleteBtn = newChild.querySelector('.delete-child');
        deleteBtn.addEventListener('click', function () {
          newChild.remove();
          updateDeleteChildButtonVisibility();
        });

        updateDeleteChildButtonVisibility();
      }

      function addContactEntry() {
        contactCounter++;
        const contactsContainer = document.getElementById('contactsContainer');
        const newContact = document.createElement('div');
        newContact.className = 'contact-entry';
        newContact.setAttribute('data-contact-id', contactCounter);
        newContact.innerHTML = `
          <div class="form-row">
            <div class="form-group"><label>Contact Type</label><select class="contact-type" name="contacts[${contactCounter}][type]" required><option value="primary">Primary</option><option value="secondary">Secondary</option><option value="emergency">Emergency</option></select></div>
            <div class="form-group"><label>Full Name</label><input type="text" name="contacts[${contactCounter}][name]" required></div>
          </div>
          <div class="form-row">
            <div class="form-group"><label>Relationship</label><input type="text" name="contacts[${contactCounter}][relationship]" required></div>
            <div class="form-group"><label>Phone Number</label><input type="tel" name="contacts[${contactCounter}][phone]" maxlength="11" pattern="\\d{11}" title="Please enter exactly 11 digits" required></div>
          </div>
          <div class="form-row">
            <div class="form-group"><label>Email Address</label><input type="email" name="contacts[${contactCounter}][email]" required></div>
            <div class="form-group"><button type="button" class="delete-child">Remove</button></div>
          </div>
        `;
        contactsContainer.appendChild(newContact);

        // Attach delete handler to the new entry
        const deleteBtn = newContact.querySelector('.delete-child');
        deleteBtn.addEventListener('click', function () {
          newContact.remove();
          updateDeleteButtonVisibility();
        });

        updateDeleteButtonVisibility();
      }

      // Add Contact button handler
      const addContactBtn = document.getElementById('addContact');
      if (addContactBtn) {
        addContactBtn.addEventListener('click', function () {
          addContactEntry();
        });
      }

      // Attach delete handlers to initial contact entries
      document.querySelectorAll('.contact-entry .delete-child').forEach(function (btn) {
        btn.addEventListener('click', function () {
          btn.closest('.contact-entry').remove();
          updateDeleteButtonVisibility();
        });
      });

      updateDeleteButtonVisibility();

      // Add Child button handler
      const addChildBtn = document.getElementById('addChild');
      if (addChildBtn) {
        addChildBtn.addEventListener('click', function () {
          addChildEntry();
        });
      }

      // Attach working status change handlers to initial child entries
      document.querySelectorAll('.child-working-status').forEach(function (select) {
        select.addEventListener('change', function () {
          toggleIncomeField(this);
        });
      });

      // Attach delete handlers to initial child entries
      document.querySelectorAll('.child-entry .delete-child').forEach(function (btn) {
        btn.addEventListener('click', function () {
          btn.closest('.child-entry').remove();
          updateDeleteChildButtonVisibility();
        });
      });

      updateDeleteChildButtonVisibility();

      // Enforce digits-only input for ID fields while typing and pasting.
      document.querySelectorAll('.numeric-only').forEach(function (input) {
        input.addEventListener('input', function () {
          const maxDigits = parseInt(input.getAttribute('data-max-digits') || '0', 10);
          let digitsOnly = input.value.replace(/\D/g, '');
          if (maxDigits > 0) {
            digitsOnly = digitsOnly.slice(0, maxDigits);
          }
          input.value = digitsOnly;
        });
      });

      function setInputValue(id, value) {
        const el = document.getElementById(id);
        if (!el || value === undefined || value === null) return;
        el.value = String(value);
      }

      function setCheckboxValues(name, values) {
        if (!Array.isArray(values)) return;
        const set = new Set(values.map(function (v) { return String(v); }));
        document.querySelectorAll('input[name="' + name + '"]').forEach(function (cb) {
          cb.checked = set.has(cb.value);
        });
      }

      function createContactEntry(contact, idx) {
        if (idx > 0) addContactEntry();
        const entries = document.querySelectorAll('.contact-entry');
        const entry = entries[idx];
        if (!entry) return;
        const typeEl = entry.querySelector('.contact-type');
        const nameEl = entry.querySelector('input[name$="[name]"]');
        const relEl = entry.querySelector('input[name$="[relationship]"]');
        const phoneEl = entry.querySelector('input[name$="[phone]"]');
        const emailEl = entry.querySelector('input[name$="[email]"]');
        if (typeEl) typeEl.value = contact.type || 'primary';
        if (nameEl) nameEl.value = contact.name || '';
        if (relEl) relEl.value = contact.relationship || '';
        if (phoneEl) phoneEl.value = contact.phone || '';
        if (emailEl) emailEl.value = contact.email || '';
      }

      function createChildEntry(child, idx) {
        if (idx > 0) addChildEntry();
        const entries = document.querySelectorAll('.child-entry');
        const entry = entries[idx];
        if (!entry) return;
        const fullName = entry.querySelector('input[name="childFullName[]"]');
        const occupation = entry.querySelector('input[name="childOccupation[]"]');
        const age = entry.querySelector('input[name="childAge[]"]');
        const status = entry.querySelector('select[name="childWorkingStatus[]"]');
        const income = entry.querySelector('input[name="childIncome[]"]');
        if (fullName) fullName.value = child.full_name || '';
        if (occupation) occupation.value = child.occupation || '';
        if (age) age.value = child.age || '';
        if (status) {
          status.value = child.working_status || 'not_working';
          toggleIncomeField(status);
        }
        if (income) income.value = child.income || '';
      }

      function applyEditSeniorData() {
        if (!isEditMode || !editSenior || typeof editSenior !== 'object') return;
        const info = editSenior.identifying_information || {};
        const name = info.name || {};
        const address = info.address || {};
        const family = editSenior.family_composition || {};
        const father = family.father || {};
        const mother = family.mother || {};

        setInputValue('residentId', editSenior._id || '');
        setInputValue('first_name', name.first_name || '');
        setInputValue('middle_name', name.middle_name || '');
        setInputValue('last_name', name.last_name || '');
        setInputValue('barangay', address.barangay || '');
        if (address.barangay) updatePurokOptions();
        setInputValue('purok', address.purok || '');
        setInputValue('gender', info.gender || '');
        setInputValue('birthday', info.date_of_birth ? String(info.date_of_birth).slice(0, 10) : '');
        setInputValue('age', info.age || '');
        setInputValue('religion', info.religion || '');
        setInputValue('pension', info.current_pension || '');
        setInputValue('service', info.service_business_employment || '');
        setInputValue('capability_to_travel', info.capability_to_travel || '');
        setInputValue('place_of_birth', Array.isArray(info.place_of_birth) ? (info.place_of_birth[0] || '') : (info.place_of_birth || ''));
        setInputValue('civil_status', info.marital_status || '');
        setInputValue('spouse_name', (family.spouse && family.spouse.name) || '');
        setInputValue('fatherLastName', father.last_name || '');
        setInputValue('fatherFirstName', father.first_name || '');
        setInputValue('fatherMiddleName', father.middle_name || '');
        setInputValue('fatherExtension', father.extension || '');
        setInputValue('motherLastName', mother.last_name || '');
        setInputValue('motherFirstName', mother.first_name || '');
        setInputValue('motherMiddleName', mother.middle_name || '');
        setInputValue('osca_id', info.osca_id_number || '');
        setInputValue('gsis_id', info.gsis_sss || '');
        setInputValue('philhealth_id', info.philhealth || '');
        setInputValue('tin_no', info.tin || '');
        setInputValue('other_id', info.other_govt_id || '');

        const educational = editSenior.education_hr_profile || {};
        setInputValue('educational_attainment', Array.isArray(educational.educational_attainment) ? (educational.educational_attainment[0] || '') : (educational.educational_attainment || ''));
        setCheckboxValues('skills[]', educational.skills || []);
        setInputValue('skill-other-text', educational.skill_other_text || '');
        setCheckboxValues('community_service[]', editSenior.community_service || []);
        setInputValue('community-service-other-text', editSenior.community_service_other_text || '');

        const contacts = Array.isArray(info.contacts) ? info.contacts : [];
        if (contacts.length) {
          const first = document.querySelector('.contact-entry');
          if (first) first.remove();
          contacts.forEach(function (contact, idx) { createContactEntry(contact, idx); });
        }

        const children = Array.isArray(family.children) ? family.children : [];
        if (children.length) {
          const firstChild = document.querySelector('.child-entry');
          if (firstChild) firstChild.remove();
          children.forEach(function (child, idx) { createChildEntry(child, idx); });
        }

        toggleSpouseInput();
      }

      function applyViewMode() {
        if (!isViewMode) return;
        document.querySelectorAll('input, select, textarea, button').forEach(function (el) {
          if (el.id === 'prevBtn' || el.id === 'nextBtn') return;
          if (el.type === 'checkbox' || el.type === 'radio') {
            el.disabled = true;
          } else {
            el.readOnly = true;
            el.disabled = true;
          }
        });
        if (nextBtnNav) nextBtnNav.textContent = 'Close';
      }

      applyEditSeniorData();
      applyViewMode();

      renderStep(0);
    });
  </script>
</body>
</html>
