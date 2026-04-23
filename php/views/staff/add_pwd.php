<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="/php/assets/images/logo-ebmag.png">
  <title>Person With Disability FORM</title>
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

    .helper { margin: 0 0 12px; color: #6b7280; font-size: 14px; }

    .logs-panel {
      margin-bottom: 14px;
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      padding: 14px;
    }

    .logs-title {
      margin: 0 0 10px;
      font-size: 16px;
      font-weight: 700;
      color: #0f766e;
    }

    .logs-wrapper {
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      overflow: auto;
      max-height: 240px;
      background: #ffffff;
    }

    .logs-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
    }

    .logs-table th,
    .logs-table td {
      border-bottom: 1px solid #e5e7eb;
      padding: 8px 10px;
      text-align: left;
      vertical-align: top;
    }

    .logs-table th {
      background: #f8fafc;
      color: #374151;
      font-weight: 700;
      position: sticky;
      top: 0;
      z-index: 1;
    }

    .logs-empty {
      color: #6b7280;
      font-size: 13px;
      padding: 10px 0 2px;
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
      scrollbar-width: none;
    }

    .form-panel .progress-bar::-webkit-scrollbar {
      display: none;
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
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0;
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

    .form-panel .step.completed {
      color: #15803d;
      font-weight: 700;
    }

    .form-panel .step.completed::before {
      background-color: #16a34a;
      box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.15);
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

    .form-panel .delete-child,
    .form-panel .remove-contact {
      background: #fee2e2;
      color: #b91c1c;
      border: 1px solid #fecaca;
      border-radius: 12px;
    }

    .form-panel .delete-child:hover,
    .form-panel .remove-contact:hover {
      background: #fecaca;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(8px); }
      to { opacity: 1; transform: translateY(0); }
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

    @media (max-width: 900px) {
      .progress-bar {
        justify-content: flex-start;
      }

      .step {
        min-width: 160px;
      }
    }

    @media (max-width: 640px) {
      .step {
        min-width: 140px;
      }

      .step span:last-child {
        font-size: 12px;
      }

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
    }

    @media (max-width: 640px) {
      .form-panel .step {
        min-width: 110px;
      }
    }
  </style>
</head>
<?php
$isEditMode = !empty($isEditMode);
$editPwd = is_array($editPwd ?? null) ? $editPwd : null;
$formAction = $isEditMode ? '/update-pwd' : '/register-pwd';
$isModal = isset($_GET['modal']) && $_GET['modal'] === '1';
$isViewMode = isset($_GET['view']) && $_GET['view'] === '1';
?>
<body class="<?= $isModal ? 'modal-mode' : '' ?><?= $isViewMode ? ' view-mode' : '' ?>">
  <div class="page-shell">
    <div class="topbar">
      <div class="topbar-left">
        <?php if ($isModal): ?>
          <a class="topbar-btn" href="#" id="closeModalBtn">Close</a>
        <?php else: ?>
          <a class="topbar-btn" href="/pdao-dashboard">← Back to Dashboard</a>
        <?php endif; ?>
        <div>
          <h1 class="topbar-title" id="formTitle"><?= $isViewMode ? 'View Person With Disability FORM' : ($isEditMode ? 'Edit Person With Disability FORM' : 'Person With Disability FORM') ?></h1>
          <p class="helper" id="formHelper"><?= $isViewMode ? 'Review each section using Next and Back.' : ($isEditMode ? 'Update the required fields, then submit to save changes.' : 'Complete all fields across the sections below. This mirrors the original intake form content.') ?></p>
        </div>
      </div>
      <div class="meta">Use the barangay dropdown to populate purok options.</div>
    </div>

    <?php if ($isViewMode): ?>
      <section class="logs-panel" id="editLogsPanel">
        <h2 class="logs-title">Edit Logs</h2>
        <div class="logs-wrapper" id="editLogsContainer">
          <div class="logs-empty">Loading edit logs...</div>
        </div>
      </section>
    <?php endif; ?>

    <form id="housingForm" class="form-panel" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>" method="post">
      <input type="hidden" id="pwd_id" name="pwd_id" value="<?= (int) (($editPwd['id'] ?? 0)) ?>">
      <div class="progress-bar">
        <div class="step active"><span class="step-number">1</span><span>Personal Information</span></div>
        <div class="step"><span class="step-number">2</span><span>Contact Information</span></div>
        <div class="step"><span class="step-number">3</span><span>Family Composition</span></div>
        <div class="step"><span class="step-number">4</span><span>IDs</span></div>
        <div class="step"><span class="step-number">5</span><span>Education / HR Profile</span></div>
        <div class="step"><span class="step-number">6</span><span>Types / Cause Disability</span></div>
      </div>

      <fieldset id="personalInfo" class="active">
        <legend>Personal Information</legend>
        <div class="form-row">
          <div class="form-group"><label for="first_name">First Name</label><input type="text" id="first_name" name="first_name" required placeholder="First Name" maxlength="25" pattern="[A-Za-z ]+"></div>
          <div class="form-group"><label for="middle_name">Middle Name</label><input type="text" id="middle_name" name="middle_name" placeholder="Middle Name" maxlength="25" pattern="[A-Za-z ]+"></div>
          <div class="form-group"><label for="last_name">Last Name</label><input type="text" id="last_name" name="last_name" required placeholder="Last Name" maxlength="25" pattern="[A-Za-z ]+"></div>
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
        </div>
        <div class="form-row">
          <div class="form-group"><label for="birthday">Birthday</label><input type="date" id="birthday" name="birthday" required onchange="calculateAge()"></div>
          <div class="form-group"><label for="age">Age</label><input type="number" id="age" name="age" required placeholder="Age" readonly></div>
          <div class="form-group"><label for="gender">Gender</label><select id="gender" name="gender" required><option value="">-- Select One --</option><option value="Male">Male</option><option value="Female">Female</option><option value="Other">Other</option><option value="Prefer not to say">Prefer not to say</option></select></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label for="place_of_birth">Place of Birth</label><input type="text" id="place_of_birth" name="place_of_birth" required placeholder="Place of Birth" maxlength="50"></div>
          <div class="form-group">
            <label for="civil_status">Civil Status</label>
            <select id="civil_status" name="civil_status" required onchange="toggleSpouseInput()">
              <option value="Single but Head of the Family">Single but Head of the Family</option>
              <option value="Single">Single</option>
              <option value="Married">Married</option>
            </select>
          </div>
          <div class="form-group" id="spouseGroup" style="display:none;"><label for="spouse_name">Name of Spouse</label><input type="text" id="spouse_name" name="spouse_name" placeholder="Name of Spouse" maxlength="25" pattern="[A-Za-z ]+"></div>
        </div>
      </fieldset>

      <fieldset id="contactInformation">
        <legend>CONTACT INFORMATION</legend>
        <div id="contactsContainer">
          <div class="contact-entry" data-contact-id="1">
            <div class="form-row">
              <div class="form-group"><label>Contact Type</label><select class="contact-type" name="contacts[1][type]" required><option value="primary">Primary</option><option value="secondary">Secondary</option><option value="emergency">Emergency</option></select></div>
              <div class="form-group"><label>Full Name</label><input type="text" name="contacts[1][name]" required maxlength="25"></div>
            </div>
            <div class="form-row">
              <div class="form-group"><label>Relationship</label><input type="text" name="contacts[1][relationship]" maxlength="25"></div>
              <div class="form-group"><label>Phone Number</label><input type="tel" name="contacts[1][phone]" maxlength="11" pattern="09\d{9}" title="Phone number must start with 09 and be 11 digits" required></div>
            </div>
            <div class="form-row">
              <div class="form-group"><label>Email Address</label><input type="email" name="contacts[1][email]" maxlength="100" pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$" title="Please enter a valid email address"></div>
              <div class="form-group"><button type="button" class="remove-contact" disabled>Remove</button></div>
            </div>
          </div>
        </div>
        <div class="form-row"><div class="form-group"><button type="button" id="addContact" class="add-child-btn">+ Add Another Contact</button></div></div>
      </fieldset>

      <fieldset id="familyComposition">
        <legend>Family Composition</legend>
        <div class="form-row">
          <div class="form-group"><label for="fatherLastName">Father's Last Name</label><input type="text" id="fatherLastName" name="fatherLastName" placeholder="Last Name" maxlength="25"></div>
          <div class="form-group"><label for="fatherFirstName">Father's First Name</label><input type="text" id="fatherFirstName" name="fatherFirstName" placeholder="First Name" maxlength="25"></div>
          <div class="form-group"><label for="fatherMiddleName">Father's Middle Name</label><input type="text" id="fatherMiddleName" name="fatherMiddleName" placeholder="Middle Name" maxlength="25"></div>
          <div class="form-group"><label for="fatherExtension">Extension (Jr/Sr)</label><input type="text" id="fatherExtension" name="fatherExtension" placeholder="Extension (Jr, Sr)" maxlength="25"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label for="motherLastName">Mother's Last Name</label><input type="text" id="motherLastName" name="motherLastName" placeholder="Last Name" maxlength="25"></div>
          <div class="form-group"><label for="motherFirstName">Mother's First Name</label><input type="text" id="motherFirstName" name="motherFirstName" placeholder="First Name" maxlength="25"></div>
          <div class="form-group"><label for="motherMiddleName">Mother's Middle Name</label><input type="text" id="motherMiddleName" name="motherMiddleName" placeholder="Middle Name" maxlength="25"></div>
        </div>
      </fieldset>

      <fieldset id="requiredDocuments" class="hidden">
        <legend>ID'S</legend>
        <div class="form-row">
          <div class="form-group"><label for="sss_id">SSS NO</label><input type="number" id="sss_id" name="sss_id" maxlength="10" title="10-digit SSS number" oninput="enforceMaxLength(this, 10)"></div>
          <div class="form-group"><label for="gsis_sss_no">GSIS NO</label><input type="number" id="gsis_sss_no" name="gsis_sss_no" maxlength="12" title="8-12 digit GSIS number" oninput="enforceMaxLength(this, 12)"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label for="psn_no">PSN NO</label><input type="number" id="psn_no" name="psn_no" maxlength="9" title="9-digit PSN number" oninput="enforceMaxLength(this, 9)"></div>
          <div class="form-group"><label for="philhealth_no">Philhealth No</label><input type="number" id="philhealth_no" name="philhealth_no" maxlength="12" title="12-digit PhilHealth number" oninput="enforceMaxLength(this, 12)"></div>
        </div>
      </fieldset>

      <fieldset id="educationalAttainment">
        <legend>Educational Attainment</legend>
        <div class="form-group">
          <label for="education_level">Select Educational Level:</label>
          <select id="education_level" name="education_level" required>
            <option value="">-- Select One --</option>
            <option value="Elementary Level">Elementary Level</option>
            <option value="Elementary Graduate">Elementary Graduate</option>
            <option value="High School Graduate">High School Graduate</option>
            <option value="College Level">College Level</option>
            <option value="College Graduate">College Graduate</option>
            <option value="Post Graduate">Post Graduate</option>
            <option value="Vocational">Vocational</option>
            <option value="Not Attended School">Not Attended School</option>
          </select>
        </div>

        <div class="form-group">
          <label for="employment_status">Status of Employment:</label>
          <select id="employment_status" name="employment_status" required>
            <option value="">-- Select One --</option>
            <option value="Employee">Employee</option>
            <option value="Unemployed">Unemployed</option>
            <option value="Self-employed">Self-employed</option>
          </select>
        </div>

        <div class="form-group" id="categoryGroup" style="display:none;"><label for="employment_category">Category of Employment:</label><select id="employment_category" name="employment_category"><option value="">-- Select One --</option><option value="Government">Government</option><option value="Private">Private</option></select></div>
        <div class="form-group" id="typeGroup" style="display:none;"><label for="employment_type">Type of Employment:</label><select id="employment_type" name="employment_type"><option value="">-- Select One --</option><option value="Permanent/Regular">Permanent/Regular</option><option value="Seasonal">Seasonal</option><option value="Casual">Casual</option><option value="Emergency">Emergency</option></select></div>
      </fieldset>

      <fieldset id="disability">
        <div class="form-section skills-section">
          <h3 class="section-title">Types Of Disability</h3>
          <div class="skills-grid">
            <div class="skills-column">
              <div class="skill-item"><input type="checkbox" id="disability-deaf" name="disability[]" value="Deaf or Hard of Hearing"><label for="disability-deaf">Deaf or Hard of Hearing</label></div>
              <div class="skill-item"><input type="checkbox" id="disability-intellectual" name="disability[]" value="Intellectual Disability"><label for="disability-intellectual">Intellectual Disability</label></div>
              <div class="skill-item"><input type="checkbox" id="disability-learning" name="disability[]" value="Learning Disability"><label for="disability-learning">Learning Disability</label></div>
              <div class="skill-item"><input type="checkbox" id="disability-mental" name="disability[]" value="Mental Disability"><label for="disability-mental">Mental Disability</label></div>
              <div class="skill-item"><input type="checkbox" id="disability-physical" name="disability[]" value="Physical Disability (Orthopedic)"><label for="disability-physical">Physical Disability (Orthopedic)</label></div>
            </div>
            <div class="skills-column">
              <div class="skill-item"><input type="checkbox" id="disability-psychosocial" name="disability[]" value="Psychosocial Disability"><label for="disability-psychosocial">Psychosocial Disability</label></div>
              <div class="skill-item"><input type="checkbox" id="disability-speech" name="disability[]" value="Speech and Language Impairment"><label for="disability-speech">Speech and Language Impairment</label></div>
              <div class="skill-item"><input type="checkbox" id="disability-visual" name="disability[]" value="Visual Disability"><label for="disability-visual">Visual Disability</label></div>
              <div class="skill-item"><input type="checkbox" id="disability-cancer" name="disability[]" value="Cancer (RA11215)"><label for="disability-cancer">Cancer (RA11215)</label></div>
              <div class="skill-item"><input type="checkbox" id="disability-rare" name="disability[]" value="Rare Disease (RA10747)"><label for="disability-rare">Rare Disease (RA10747)</label></div>
            </div>
          </div>
          <div class="skill-item"><input type="checkbox" id="disability-other" name="disability[]" value="Other"><label for="disability-other">Others, specify:</label><input type="text" id="disability-other-text" name="disability_other_text" class="other-input"></div>
        </div>

        <div class="form-section skills-section">
          <h3 class="section-title">Cause of Disability <span class="required">*</span></h3>
          <div class="skills-grid">
            <div class="skills-column">
              <div class="skill-item"><input type="checkbox" id="cause-congenital" name="cause_disability[]" value="Congenital / Inborn"><label for="cause-congenital">Congenital / Inborn</label></div>
              <div class="skill-item"><input type="checkbox" id="cause-autism" name="cause_disability[]" value="Autism"><label for="cause-autism">Autism</label></div>
              <div class="skill-item"><input type="checkbox" id="cause-adhd" name="cause_disability[]" value="ADHD"><label for="cause-adhd">ADHD</label></div>
              <div class="skill-item"><input type="checkbox" id="cause-cerebral-palsy" name="cause_disability[]" value="Cerebral Palsy"><label for="cause-cerebral-palsy">Cerebral Palsy</label></div>
              <div class="skill-item"><input type="checkbox" id="cause-down-syndrome" name="cause_disability[]" value="Down Syndrome"><label for="cause-down-syndrome">Down Syndrome</label></div>
            </div>
            <div class="skills-column">
              <div class="skill-item"><input type="checkbox" id="cause-acquired" name="cause_disability[]" value="Acquired"><label for="cause-acquired">Acquired</label></div>
              <div class="skill-item"><input type="checkbox" id="cause-chronic-illness" name="cause_disability[]" value="Chronic Illness"><label for="cause-chronic-illness">Chronic Illness</label></div>
              <div class="skill-item"><input type="checkbox" id="cause-cerebral-palsy2" name="cause_disability[]" value="Cerebral Palsy"><label for="cause-cerebral-palsy2">Cerebral Palsy</label></div>
              <div class="skill-item"><input type="checkbox" id="cause-injury" name="cause_disability[]" value="Injury"><label for="cause-injury">Injury</label></div>
            </div>
          </div>
          <div class="skill-item"><input type="checkbox" id="cause-other" name="cause_disability[]" value="Other"><label for="cause-other">Others, specify:</label><input type="text" id="cause-other-text" name="cause_other_text" class="other-input"></div>
        </div>
      </fieldset>

      <div class="navigation"><button type="button" id="prevBtn">BACK</button><button type="button" id="nextBtn">NEXT</button></div>
    </form>
  </div>

  <script>
    const barangays = <?= json_encode($barangays ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const isEditMode = <?= $isEditMode ? 'true' : 'false' ?>;
    const isModal = <?= $isModal ? 'true' : 'false' ?>;
    const isViewMode = <?= $isViewMode ? 'true' : 'false' ?>;
    const editPwd = <?= json_encode($editPwd, JSON_UNESCAPED_UNICODE) ?>;

    function updatePurokOptions() {
      const barangaySelect = document.getElementById('barangay');
      const purokSelect = document.getElementById('purok');
      const selectedBarangay = barangaySelect.value;

      while (purokSelect.options.length > 1) {
        purokSelect.remove(1);
      }

      if (selectedBarangay && barangays[selectedBarangay]) {
        barangays[selectedBarangay].forEach(purok => {
          const option = document.createElement('option');
          option.value = purok;
          option.textContent = purok;
          purokSelect.appendChild(option);
        });
      }
    }

    function enforceMaxLength(input, maxLength) {
      if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength);
      }
    }

    function calculateAge() {
      const birthday = document.getElementById('birthday');
      const age = document.getElementById('age');
      if (!birthday || !age || !birthday.value) return;
      const dob = new Date(birthday.value);
      if (Number.isNaN(dob.getTime())) return;
      const diff = Date.now() - dob.getTime();
      const years = new Date(diff).getUTCFullYear() - 1970;
      age.value = years;
    }

    function toggleSpouseInput() {
      const civilStatus = document.getElementById('civil_status');
      const spouseGroup = document.getElementById('spouseGroup');
      if (!civilStatus || !spouseGroup) return;
      spouseGroup.style.display = civilStatus.value === 'Married' ? 'block' : 'none';
    }

    document.getElementById('first_name').addEventListener('input', function () {
      this.value = this.value.replace(/[^A-Za-z ]/g, '');
    });

    document.addEventListener('DOMContentLoaded', function () {
      const closeModalBtn = document.getElementById('closeModalBtn');
      if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function (event) {
          event.preventDefault();
          if (window.parent && window.parent !== window) {
            window.parent.postMessage({ type: isViewMode ? 'pwd-view-close' : 'pwd-edit-cancel' }, window.location.origin);
            return;
          }
          window.location.href = '/pdao-dashboard';
        });
      }

      if (document.getElementById('barangay').value) {
        updatePurokOptions();
      }
      toggleSpouseInput();
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('housingForm');
      const prevBtn = document.getElementById('prevBtn');
      const nextBtn = document.getElementById('nextBtn');
      const addContactBtn = document.getElementById('addContact');
      const contactsContainer = document.getElementById('contactsContainer');
      const fieldsets = Array.from(document.querySelectorAll('#housingForm fieldset'));
      const steps = Array.from(document.querySelectorAll('.progress-bar .step'));
      const employmentStatus = document.getElementById('employment_status');
      const categoryGroup = document.getElementById('categoryGroup');
      const typeGroup = document.getElementById('typeGroup');
      const categorySelect = document.getElementById('employment_category');
      const typeSelect = document.getElementById('employment_type');

      let currentStep = 0;
      let isSubmitting = false;

      function applyViewModeReadonly() {
        if (!isViewMode) return;

        form.querySelectorAll('input, select, textarea').forEach(function (el) {
          const isCheckbox = el.type === 'checkbox';
          if (isCheckbox) {
            el.disabled = true;
          } else {
            el.readOnly = true;
            el.disabled = true;
          }
        });

        form.querySelectorAll('button').forEach(function (btn) {
          if (btn.id !== 'prevBtn' && btn.id !== 'nextBtn') {
            btn.style.display = 'none';
          }
        });

        if (prevBtn) {
          prevBtn.disabled = false;
        }
        if (nextBtn) {
          nextBtn.disabled = false;
        }
      }

      function showModal(options) {
        if (window.Swal && typeof window.Swal.fire === 'function') {
          return window.Swal.fire(options);
        }

        const fallbackText = options.text || options.title || 'Notification';
        window.alert(fallbackText);
        return Promise.resolve({ isConfirmed: true });
      }

      function setInputValue(id, value) {
        const el = document.getElementById(id);
        if (!el) return;
        el.value = (value === null || value === undefined) ? '' : String(value);
      }

      function setCheckboxValues(name, values) {
        const list = Array.isArray(values) ? values : [];
        document.querySelectorAll(`input[name="${name}"]`).forEach(function (box) {
          box.checked = list.includes(box.value);
        });
      }

      function escapeHtml(value) {
        return String(value ?? '')
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#039;');
      }

      function formatEditedAt(value) {
        const raw = String(value || '').trim();
        if (!raw) {
          return 'N/A';
        }

        const date = new Date(raw.replace(' ', 'T') + '+08:00');
        if (Number.isNaN(date.getTime())) {
          return raw;
        }

        return new Intl.DateTimeFormat('en-PH', {
          timeZone: 'Asia/Manila',
          year: 'numeric',
          month: 'short',
          day: '2-digit',
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit',
          hour12: true
        }).format(date);
      }

      async function loadPwdEditLogs() {
        if (!isViewMode) return;

        const container = document.getElementById('editLogsContainer');
        if (!container) return;

        const pwdId = Number((editPwd && (editPwd.id || editPwd._id)) || 0);
        if (pwdId <= 0) {
          container.innerHTML = '<div class="logs-empty">No logs available: invalid PWD ID.</div>';
          return;
        }

        try {
          const response = await fetch('/api/pwd-edit-logs/' + encodeURIComponent(String(pwdId)), {
            headers: { Accept: 'application/json' }
          });
          const payload = await response.json();

          if (!response.ok || !payload || payload.success === false) {
            container.innerHTML = '<div class="logs-empty">Unable to load edit logs.</div>';
            return;
          }

          const logs = Array.isArray(payload.data) ? payload.data : [];
          if (logs.length === 0) {
            container.innerHTML = '<div class="logs-empty">No edits recorded yet.</div>';
            return;
          }

          container.innerHTML = '<table class="logs-table"><thead><tr><th>Field</th><th>Old Value</th><th>New Value</th><th>Editor</th><th>Date and Time</th></tr></thead><tbody>' +
            logs.map(function (log) {
              return '<tr>' +
                '<td>' + escapeHtml(log.field || '') + '</td>' +
                '<td>' + escapeHtml(log.old_value || 'N/A') + '</td>' +
                '<td>' + escapeHtml(log.new_value || 'N/A') + '</td>' +
                '<td>' + escapeHtml(log.edited_by || 'Unknown') + '</td>' +
                '<td>' + escapeHtml(formatEditedAt(log.edited_at)) + '</td>' +
              '</tr>';
            }).join('') +
            '</tbody></table>';
        } catch (error) {
          container.innerHTML = '<div class="logs-empty">Network error while loading edit logs.</div>';
        }
      }

      async function submitPwdForm() {
        if (isSubmitting) return;
        isSubmitting = true;

        if (nextBtn) {
          nextBtn.disabled = true;
        }

        try {
          const formData = new FormData(form);
          const response = await fetch(form.action, {
            method: 'POST',
            headers: {
              Accept: 'application/json'
            },
            body: formData
          });

          let payload = null;
          try {
            payload = await response.json();
          } catch (parseError) {
            payload = null;
          }

          if (!response.ok || !payload || payload.success === false) {
            if (payload && payload.isDuplicate) {
              await showModal({
                icon: 'warning',
                title: 'Duplicate PWD Record',
                text: payload.message || 'Duplicate PWD record found.',
                confirmButtonColor: '#0f766e'
              });
              return;
            }

            await showModal({
              icon: 'error',
              title: 'Submission Failed',
              text: (payload && payload.error)
                ? String(payload.error)
                : ((payload && payload.message) ? payload.message : 'An error occurred while submitting the form.'),
              confirmButtonColor: '#b91c1c'
            });
            return;
          }

          await showModal({
            icon: 'success',
            title: isEditMode ? 'Updated' : 'Submitted',
            text: payload.message || (isEditMode ? 'PWD record updated successfully.' : 'PWD registration successful.'),
            confirmButtonColor: '#0f766e'
          });

          if (isEditMode && isModal && window.parent && window.parent !== window) {
            window.parent.postMessage({ type: 'pwd-edit-saved', data: payload.data || null }, window.location.origin);
            return;
          }

          window.location.href = '/pdao-dashboard';
        } catch (error) {
          await showModal({
            icon: 'error',
            title: 'Network Error',
            text: 'Unable to submit right now. Please try again.',
            confirmButtonColor: '#b91c1c'
          });
        } finally {
          isSubmitting = false;
          if (nextBtn) {
            nextBtn.disabled = false;
          }
        }
      }

      function updateEmploymentFields() {
        if (isViewMode) return;
        if (!employmentStatus || !categoryGroup || !typeGroup || !categorySelect || !typeSelect) return;
        const isEmployee = employmentStatus.value === 'Employee';
        categoryGroup.style.display = isEmployee ? 'block' : 'none';
        typeGroup.style.display = isEmployee ? 'block' : 'none';
        categorySelect.required = isEmployee;
        typeSelect.required = isEmployee;
        if (!isEmployee) {
          categorySelect.value = '';
          typeSelect.value = '';
        }
      }

      function renderSteps() {
        fieldsets.forEach(function (fieldset, index) {
          const isActive = index === currentStep;
          fieldset.style.display = isActive ? 'block' : 'none';
        });

        steps.forEach(function (stepEl, index) {
          stepEl.classList.remove('active', 'completed');
          if (index < currentStep) {
            stepEl.classList.add('completed');
          } else if (index === currentStep) {
            stepEl.classList.add('active');
          }
        });

        if (prevBtn) {
          prevBtn.style.display = currentStep === 0 ? 'none' : 'inline-flex';
        }

        if (nextBtn) {
          const isLastStep = currentStep === fieldsets.length - 1;
          if (isViewMode) {
            nextBtn.textContent = isLastStep ? 'CLOSE' : 'NEXT';
          } else {
            nextBtn.textContent = isLastStep ? (isEditMode ? 'UPDATE' : 'SUBMIT') : 'NEXT';
          }
        }
      }

      function validateStep(index) {
        if (isViewMode) {
          return true;
        }

        const fieldset = fieldsets[index];
        if (!fieldset) return true;

        const requiredFields = Array.from(fieldset.querySelectorAll('[required]'));
        for (const field of requiredFields) {
          if (typeof field.checkValidity === 'function' && !field.checkValidity()) {
            field.reportValidity();
            return false;
          }
        }

        return true;
      }

      function goNext() {
        if (!validateStep(currentStep)) return;

        const isLastStep = currentStep >= fieldsets.length - 1;
        if (isLastStep) {
          if (isViewMode) {
            if (window.parent && window.parent !== window) {
              window.parent.postMessage({ type: 'pwd-view-close' }, window.location.origin);
            } else {
              window.location.href = '/pdao-dashboard';
            }
            return;
          }

          if (window.Swal && typeof window.Swal.fire === 'function') {
            window.Swal.fire({
              title: isEditMode ? 'Update PWD Form?' : 'Submit PWD Form?',
              text: isEditMode ? 'Please confirm that all changes are correct.' : 'Please confirm that all information is correct.',
              icon: 'question',
              showCancelButton: true,
              confirmButtonText: isEditMode ? 'Update' : 'Submit',
              cancelButtonText: 'Cancel',
              confirmButtonColor: '#0f766e'
            }).then(function (result) {
              if (result.isConfirmed) {
                submitPwdForm();
              }
            });
          } else {
            const confirmed = window.confirm(isEditMode ? 'Update PWD Form?' : 'Submit PWD Form?');
            if (confirmed) {
              submitPwdForm();
            }
          }
          return;
        }

        currentStep += 1;
        renderSteps();
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }

      function goBack() {
        if (currentStep <= 0) return;
        currentStep -= 1;
        renderSteps();
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }

      if (prevBtn) {
        prevBtn.addEventListener('click', function (event) {
          event.preventDefault();
          goBack();
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', function (event) {
          event.preventDefault();
          goNext();
        });
      }

      if (employmentStatus) {
        employmentStatus.addEventListener('change', updateEmploymentFields);
      }

      if (form) {
        form.addEventListener('submit', function (event) {
          event.preventDefault();
          goNext();
        });
      }

      // Contact add/remove handlers for this page.
      if (addContactBtn && contactsContainer) {
        let contactCounter = Array.from(contactsContainer.querySelectorAll('.contact-entry')).reduce(function (maxId, entry) {
          const id = Number(entry.getAttribute('data-contact-id') || 0);
          return Math.max(maxId, id);
        }, 1);

        function updateRemoveButtons() {
          const removeButtons = contactsContainer.querySelectorAll('.remove-contact');
          const shouldDisable = removeButtons.length <= 1;
          removeButtons.forEach(function (btn) {
            btn.disabled = shouldDisable;
          });
        }

        function buildContactEntry(contactId, contactData) {
          const contact = contactData && typeof contactData === 'object' ? contactData : {};
          const safeType = contact.type || 'primary';
          const safeName = contact.name || '';
          const safeRelationship = contact.relationship || '';
          const safePhone = contact.phone || '';
          const safeEmail = contact.email || '';
          const wrapper = document.createElement('div');
          wrapper.className = 'contact-entry';
          wrapper.setAttribute('data-contact-id', String(contactId));
          wrapper.innerHTML = `
            <div class="form-row">
              <div class="form-group"><label>Contact Type</label><select class="contact-type" name="contacts[${contactId}][type]" required><option value="primary" ${safeType === 'primary' ? 'selected' : ''}>Primary</option><option value="secondary" ${safeType === 'secondary' ? 'selected' : ''}>Secondary</option><option value="emergency" ${safeType === 'emergency' ? 'selected' : ''}>Emergency</option></select></div>
              <div class="form-group"><label>Full Name</label><input type="text" name="contacts[${contactId}][name]" required maxlength="25" value="${String(safeName).replace(/"/g, '&quot;')}"></div>
            </div>
            <div class="form-row">
              <div class="form-group"><label>Relationship</label><input type="text" name="contacts[${contactId}][relationship]" maxlength="25" value="${String(safeRelationship).replace(/"/g, '&quot;')}"></div>
              <div class="form-group"><label>Phone Number</label><input type="tel" name="contacts[${contactId}][phone]" maxlength="11" pattern="09\\d{9}" title="Phone number must start with 09 and be 11 digits" required value="${String(safePhone).replace(/"/g, '&quot;')}"></div>
            </div>
            <div class="form-row">
              <div class="form-group"><label>Email Address</label><input type="email" name="contacts[${contactId}][email]" maxlength="100" pattern="^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$" title="Please enter a valid email address" value="${String(safeEmail).replace(/"/g, '&quot;')}"></div>
              <div class="form-group"><button type="button" class="remove-contact">Remove</button></div>
            </div>
          `;
          return wrapper;
        }

        addContactBtn.addEventListener('click', function (event) {
          event.preventDefault();
          if (isViewMode) {
            return;
          }
          contactCounter += 1;
          const newEntry = buildContactEntry(contactCounter);
          contactsContainer.appendChild(newEntry);
          updateRemoveButtons();
        });

        contactsContainer.addEventListener('click', function (event) {
          const removeBtn = event.target.closest('.remove-contact');
          if (!removeBtn) return;
          event.preventDefault();

          if (isViewMode) {
            return;
          }

          const allEntries = contactsContainer.querySelectorAll('.contact-entry');
          if (allEntries.length <= 1) {
            if (window.Swal && typeof window.Swal.fire === 'function') {
              window.Swal.fire({
                title: 'Cannot Remove',
                text: 'At least one contact is required.',
                icon: 'warning',
                confirmButtonColor: '#0f766e'
              });
            }
            return;
          }

          const entry = removeBtn.closest('.contact-entry');
          if (entry) {
            entry.remove();
            updateRemoveButtons();
          }
        });

        updateRemoveButtons();

        if (isEditMode && editPwd && typeof editPwd === 'object') {
          if (form) {
            form.action = '/update-pwd';
          }

          setInputValue('pwd_id', editPwd.id || editPwd._id || '');
          setInputValue('first_name', editPwd.first_name);
          setInputValue('middle_name', editPwd.middle_name);
          setInputValue('last_name', editPwd.last_name);
          setInputValue('birthday', editPwd.birthday ? String(editPwd.birthday).slice(0, 10) : '');
          setInputValue('age', editPwd.age);
          setInputValue('gender', editPwd.gender);
          setInputValue('place_of_birth', editPwd.place_of_birth);
          setInputValue('civil_status', editPwd.civil_status);
          setInputValue('spouse_name', editPwd.spouse_name);
          setInputValue('fatherLastName', editPwd.fatherLastName);
          setInputValue('fatherFirstName', editPwd.fatherFirstName);
          setInputValue('fatherMiddleName', editPwd.fatherMiddleName);
          setInputValue('fatherExtension', editPwd.fatherExtension);
          setInputValue('motherLastName', editPwd.motherLastName);
          setInputValue('motherFirstName', editPwd.motherFirstName);
          setInputValue('motherMiddleName', editPwd.motherMiddleName);
          setInputValue('sss_id', editPwd.sss_id);
          setInputValue('gsis_sss_no', editPwd.gsis_sss_no);
          setInputValue('psn_no', editPwd.psn_no);
          setInputValue('philhealth_no', editPwd.philhealth_no);
          setInputValue('education_level', editPwd.education_level);
          setInputValue('employment_status', editPwd.employment_status);
          setInputValue('employment_category', editPwd.employment_category);
          setInputValue('employment_type', editPwd.employment_type);
          setInputValue('disability-other-text', editPwd.disability_other_text);
          setInputValue('cause-other-text', editPwd.cause_other_text);

          setInputValue('barangay', editPwd.barangay);
          updatePurokOptions();
          setInputValue('purok', editPwd.purok);

          setCheckboxValues('disability[]', editPwd.disability);
          setCheckboxValues('cause_disability[]', editPwd.cause_disability);

          if (Array.isArray(editPwd.contacts) && editPwd.contacts.length > 0) {
            contactsContainer.innerHTML = '';
            contactCounter = 0;
            editPwd.contacts.forEach(function (contact) {
              contactCounter += 1;
              contactsContainer.appendChild(buildContactEntry(contactCounter, contact));
            });
            updateRemoveButtons();
          }

          toggleSpouseInput();
          calculateAge();
          updateEmploymentFields();
        }

        applyViewModeReadonly();
      }

      updateEmploymentFields();
      renderSteps();
      loadPwdEditLogs();
    });
  </script>

  <style>
    body.modal-mode .page-shell {
      max-width: 100%;
      padding: 10px;
    }

    body.modal-mode .topbar {
      margin-bottom: 10px;
      padding: 12px 14px;
    }

    body.modal-mode .topbar-title {
      font-size: 20px;
    }

    body.view-mode .helper {
      color: #374151;
      font-weight: 600;
    }
  </style>
</body>
</html>
