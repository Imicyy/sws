const fs = require('fs');
const { PDFDocument } = require('pdf-lib');

function readStdin() {
  return new Promise((resolve, reject) => {
    let data = '';
    process.stdin.setEncoding('utf8');
    process.stdin.on('data', (chunk) => {
      data += chunk;
    });
    process.stdin.on('end', () => resolve(data));
    process.stdin.on('error', reject);
  });
}

function getArgValue(flag) {
  const index = process.argv.indexOf(flag);
  if (index === -1) return null;
  return process.argv[index + 1] || null;
}

function setText(form, fieldName, value = '') {
  if (!fieldName) return;
  try {
    const field = form.getTextField(fieldName);
    field.setText(String(value || ''));
    field.setFontSize(10);
  } catch (_) {}
}

function setCheckbox(form, fieldName, checked) {
  if (!fieldName) return;
  try {
    const field = form.getCheckBox(fieldName);
    if (checked) field.check();
    else field.uncheck();
  } catch (_) {}
}

function setRadio(form, fieldName, option) {
  if (!fieldName) return;
  try {
    const radio = form.getRadioGroup(fieldName);
    if (option) radio.select(option);
    else radio.clear();
  } catch (_) {}
}

function formatDate(value) {
  if (!value) return '';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return '';
  const month = `${date.getMonth() + 1}`.padStart(2, '0');
  const day = `${date.getDate()}`.padStart(2, '0');
  const year = date.getFullYear();
  return `${month}/${day}/${year}`;
}

function fillPwd(form, pwdRecord) {
  const pick = (...values) => {
    for (const value of values) {
      if (value !== undefined && value !== null && String(value).trim() !== '') return value;
    }
    return '';
  };

  const civilStatusMap = {
    Single: 'Single',
    'Single but Head of the Family': 'Single',
    Separated: 'Separated',
    'Cohabitation (live-in)': 'Cohabitation livein',
    Married: 'Married',
    'Widow/er': 'Widower',
    Widowed: 'Widower'
  };

  const educationMap = {
    'Not Attended School': { radio: 'None', checks: [] },
    'Elementary Level': { radio: 'Elementary', checks: [] },
    'Elementary Graduate': { radio: 'Elementary', checks: [] },
    'High School Graduate': { radio: 'Junior High School', checks: ['Senior High School'] },
    Vocational: { radio: 'Junior High School', checks: ['Vocational'] },
    'College Level': { radio: 'Junior High School', checks: ['College'] },
    'College Graduate': { radio: 'Junior High School', checks: ['College'] },
    'Post Graduate': { radio: 'Junior High School', checks: ['College', 'Post Graduate'] }
  };

  const employmentStatusMap = {
    Employee: 'Employed',
    Employed: 'Employed',
    Unemployed: 'Unemployed',
    'Self-employed': 'Selfemployed',
    Selfemployed: 'Selfemployed'
  };

  const disabilityFieldMap = {
    'Deaf or Hard of Hearing': 'Deaf or Hard of Hearing',
    'Intellectual Disability': 'Intellectual Disability',
    'Learning Disability': 'Learning Disability',
    'Mental Disability': 'Mental Disablity',
    'Physical Disability (Orthopedic)': 'Physical Disability',
    'Psychosocial Disability': 'Psychosocial Disability',
    'Speech and Language Impairment': 'Speech and Language Impairment',
    'Visual Disability': 'Visual Disability',
    'Cancer (RA11215)': 'Cancer RA11215',
    'Rare Disease (RA10747)': 'Rare Disease RA10747'
  };

  const causeFieldMap = {
    'Congenital / Inborn': 'Congenital  Inborn',
    Acquired: 'Acquired',
    'Chronic Illness': 'Chronic Illness',
    Injury: 'Injury',
    Autism: 'Autism',
    ADHD: 'ADHD',
    'Cerebral Palsy': 'Cerebral Palsy',
    'Down Syndrome': 'Down Syndrome'
  };

  setText(form, 'LAST NAME', pick(pwdRecord.last_name, pwdRecord.lastName));
  setText(form, 'FIRST NAME', pick(pwdRecord.first_name, pwdRecord.firstName));
  setText(form, 'MIDDLE NAME', pick(pwdRecord.middle_name, pwdRecord.middleName, 'N/A'));
  setText(form, 'SUFFIX', '');
  setText(form, 'Barangay', [pick(pwdRecord.barangay), pick(pwdRecord.purok)].filter(Boolean).join(' / '));
  setText(form, 'Municipality', 'ENRIQUE B. MAGALONA');
  setText(form, 'Municipality*', 'ENRIQUE B. MAGALONA');
  setText(form, 'Province', 'NEGROS OCCIDENTAL');
  setText(form, 'Province*', 'NEGROS OCCIDENTAL');
  setText(form, 'Region', 'NIR');
  setText(form, 'Region*', 'NIR');
  setText(form, 'DATE OF BIRTH', formatDate(pick(pwdRecord.birthday, pwdRecord.date_of_birth)));

  const gender = pick(pwdRecord.gender);
  setCheckbox(form, 'Female', gender === 'Female');
  setCheckbox(form, 'Male', gender === 'Male');
  setRadio(form, '7 CIVIL STATUS', civilStatusMap[pick(pwdRecord.civil_status, pwdRecord.marital_status)] || '');

  const contacts = Array.isArray(pwdRecord.contacts) ? pwdRecord.contacts : [];
  const primaryContact = contacts.find((c) => c && c.type === 'primary' && c.phone)
    || contacts.find((c) => c && c.phone)
    || null;
  setText(form, 'Landline No', primaryContact?.phone || '');
  setText(form, 'Mobile No', primaryContact?.phone || '');
  setText(form, 'Email Address', primaryContact?.email || '');

  const educationSelection = educationMap[pick(pwdRecord.education_level)] || { radio: 'Junior High School', checks: [] };
  setRadio(form, '12 EDUCATIONAL ATTAINMENT', educationSelection.radio);
  ['Senior High School', 'College', 'Vocational', 'Post Graduate'].forEach((option) => {
    setCheckbox(form, option, educationSelection.checks.includes(option));
  });

  setRadio(form, '13 STATUS OF EMPLOYMENT', employmentStatusMap[pick(pwdRecord.employment_status)] || '');
  setRadio(form, '13 a CATEGORY OF EMPLOYMENT', pick(pwdRecord.employment_category));
  setText(form, 'Employment Category', pick(pwdRecord.employment_type));

  setText(form, 'SSS NO', pick(pwdRecord.sss_id));
  setText(form, 'GSIS NO', pick(pwdRecord.gsis_sss_no));
  setText(form, 'PAGIBIG NO', '');
  setText(form, 'PSN NO', pick(pwdRecord.psn_no));
  setText(form, 'PhilHealth NO', pick(pwdRecord.philhealth_no));

  setText(form, 'LAST NAMEFATHERS NAME', pick(pwdRecord.father_last_name, pwdRecord.fatherLastName));
  setText(form, 'FIRST NAMEFATHERS NAME', pick(pwdRecord.father_first_name, pwdRecord.fatherFirstName));
  setText(form, 'MIDDLE NAMEFATHERS NAME', pick(pwdRecord.father_middle_name, pwdRecord.fatherMiddleName));

  setText(form, 'LAST NAMEMOTHERS NAME', pick(pwdRecord.mother_last_name, pwdRecord.motherLastName));
  setText(form, 'FIRST NAMEMOTHERS NAME', pick(pwdRecord.mother_first_name, pwdRecord.motherFirstName));
  setText(form, 'MIDDLE NAMEMOTHERS NAME', pick(pwdRecord.mother_middle_name, pwdRecord.motherMiddleName));

  setCheckbox(form, 'APPLICANT', true);
  setCheckbox(form, 'GUARDIAN', false);
  setCheckbox(form, 'REPRESENTATTIVE', false);

  Object.values(disabilityFieldMap).forEach((fieldName) => setCheckbox(form, fieldName, false));
  if (Array.isArray(pwdRecord.disability)) {
    pwdRecord.disability.forEach((type) => {
      const targetField = disabilityFieldMap[type];
      if (targetField) setCheckbox(form, targetField, true);
    });
  }

  Object.values(causeFieldMap).forEach((fieldName) => setCheckbox(form, fieldName, false));
  if (Array.isArray(pwdRecord.cause_disability)) {
    pwdRecord.cause_disability.forEach((cause) => {
      const targetField = causeFieldMap[cause];
      if (targetField) setCheckbox(form, targetField, true);
    });
  }

  if (pick(pwdRecord.disability_other_text) || pick(pwdRecord.cause_other_text)) {
    const otherDetails = [
      pick(pwdRecord.disability_other_text) ? `Disability: ${pick(pwdRecord.disability_other_text)}` : null,
      pick(pwdRecord.cause_other_text) ? `Cause: ${pick(pwdRecord.cause_other_text)}` : null
    ]
      .filter(Boolean)
      .join(' | ');
    setText(form, '15 ORGANIZATION INFORMATION', otherDetails);
  }
}

function fillSenior(form, seniorRecord) {
  const info = seniorRecord.identifying_information || {};
  const name = info.name || {};
  const address = info.address || {};
  const family = seniorRecord.family_composition || {};
  const education = seniorRecord.education_hr_profile || {};

  setText(form, 'LAST NAME', name.last_name || '');
  setText(form, 'FIRST NAME', name.first_name || '');
  setText(form, 'MIDDLE NAME', name.middle_name || '');
  setText(form, 'BARANGAY', address.barangay || '');
  setText(form, 'PUROK', address.purok || '');
  setText(form, 'PLACE OF BIRTH', Array.isArray(info.place_of_birth) ? info.place_of_birth.join(', ') : info.place_of_birth || '');
  setText(form, 'MARITAL STATUS', info.marital_status || '');
  setText(form, 'GENDER', info.gender || '');

  const dobFormatted = formatDate(info.date_of_birth);
  if (dobFormatted) {
    setText(form, 'Text Field129', dobFormatted);
    setText(form, 'Text Field128', dobFormatted);
    setText(form, 'Text Field127', dobFormatted);
    setText(form, 'BIRTHDATE', dobFormatted);
  }

  const primaryContact = Array.isArray(info.contacts) && info.contacts.length > 0 ? info.contacts[0] : null;
  setText(form, 'CONTACT', primaryContact?.phone || '');
  setText(form, 'EMAIL', primaryContact?.email || '');
  setText(form, 'RELIGION', '');

  setText(form, 'OSCA ID', info.osca_id_number || '');
  setText(form, 'GSIS/SSS', info.gsis_sss || '');
  setText(form, 'TIN', info.tin || '');
  setText(form, 'PHILHEALTH', info.philhealth || '');
  setText(form, 'OTHER ID', info.other_govt_id || '');

  setText(form, 'SERVICE BUSINESS EMPLOYMENT', info.service_business_employment || '');
  setText(form, 'CURRENT PENSION', info.current_pension || '');

  setText(form, 'NAME OF SPOUSE', family.spouse?.name || '');

  const father = family.father || {};
  setText(form, 'FATHER FIRST NAME', father.first_name || '');
  setText(form, 'FATHER LAST NAME', father.last_name || '');
  setText(form, 'FATHER MIDDLE NAME', father.middle_name || '');
  setText(form, 'FATHER EXTENSION', father.extension || '');

  const mother = family.mother || {};
  setText(form, 'MOTHER FIRST NAME', mother.first_name || '');
  setText(form, 'MOTHER LAST NAME', mother.last_name || '');
  setText(form, 'MOTHER MIDDLE NAME', mother.middle_name || '');

  if (Array.isArray(family.children) && family.children.length > 0) {
    const firstChild = family.children[0];
    setText(form, 'CHILDREN', firstChild.full_name || '');
    setText(form, 'CHILDREN OCCUPATION', firstChild.occupation || '');
    setText(form, 'INCOME', firstChild.income || '');
    setText(form, 'CHILD AGE', firstChild.age ? String(firstChild.age) : '');
    setText(form, 'WORKING/ NOT WORKING', firstChild.working_status || '');
  }

  setCheckbox(form, 'TRAVEL YES', info.capability_to_travel === 'Yes' || info.capability_to_travel === 'Capable');
  setCheckbox(form, 'TRAVEL NO', info.capability_to_travel === 'No' || info.capability_to_travel === 'Not Capable');

  const educationLevels = Array.isArray(education.educational_attainment) ? education.educational_attainment : [];
  setCheckbox(form, 'ELEMENTARY LEVEL', educationLevels.some((e) => e.includes('Elementary Level')));
  setCheckbox(form, 'ELEMENTARY GRADUATE', educationLevels.some((e) => e.includes('Elementary Graduate')));
  setCheckbox(form, 'HIGHSCHOOL LEVEL', educationLevels.some((e) => e.includes('High School Level')));
  setCheckbox(form, 'HIGHSCHOOL GRADUATE', educationLevels.some((e) => e.includes('High School Graduate')));
  setCheckbox(form, 'COLLEGE LEVEL', educationLevels.some((e) => e.includes('College Level')));
  setCheckbox(form, 'COLLEGE GRADUATE', educationLevels.some((e) => e.includes('College Graduate')));
  setCheckbox(form, 'POST GRADUATE', educationLevels.some((e) => e.includes('Post Graduate')));
  setCheckbox(form, 'VOCATIONAL', educationLevels.some((e) => e.includes('Vocational')));
  setCheckbox(form, 'NOT ATTENDED SCHOOL', educationLevels.some((e) => e.includes('Not Attended')));

  const skills = Array.isArray(education.skills) ? education.skills : [];
  const skillMap = {
    FISHING: 'Fishing',
    ENGINEERING: 'Engineering',
    BARBER: 'Barber',
    EVANGELIZATION: 'Evangelization',
    MILWRIGHT: 'Milwright',
    TEACHING: 'Teaching',
    COUNSELING: 'Counseling',
    COOKING: 'Cooking',
    CARPENTER: 'Carpenter',
    MASON: 'Mason',
    TAILOR: 'Tailor',
    FARMING: 'Farming',
    ARTS: 'Arts',
    PLUMBER: 'Plumber',
    SAPATERO: 'Sapatero',
    'CHEF/COOK': 'Chef/Cook',
    'DENTAL SKILLS': 'Dental',
    'MEDICAL SKILLS': 'Medical',
    'LEGAL SERVICES SKILLS': 'Legal Services'
  };

  Object.keys(skillMap).forEach((pdfField) => {
    const skillName = skillMap[pdfField];
    setCheckbox(form, pdfField, skills.some((s) => s && s.toLowerCase().includes(skillName.toLowerCase())));
  });

  if (education.skill_other_text) {
    setText(form, 'SKILL OTHER TEXT', education.skill_other_text);
    setCheckbox(form, 'OTHERS TECHNICAL SKILLS', true);
  }

  const communityServices = Array.isArray(seniorRecord.community_service) ? seniorRecord.community_service : [];
  setCheckbox(form, 'MEDICAL COMMUNITY SERVICE', communityServices.some((s) => s && s.toLowerCase().includes('medical')));
  setCheckbox(form, 'COMMUNITY/ ORGANIZATION LEADER', communityServices.some((s) => s && s.toLowerCase().includes('leader')));
  setCheckbox(form, 'NEIGHBORHOOD SUPPORT SERVICES', communityServices.some((s) => s && s.toLowerCase().includes('neighborhood')));
  setCheckbox(form, 'COUNSELING / REFERRAL', communityServices.some((s) => s && s.toLowerCase().includes('counseling')));
  setCheckbox(form, 'RESOURCE VOLUNTEER', communityServices.some((s) => s && s.toLowerCase().includes('volunteer')));
  setCheckbox(form, 'DENTAL COMMUNITY SERVICE', communityServices.some((s) => s && s.toLowerCase().includes('dental')));
  setCheckbox(form, 'LEAGAL SERVICES COMMUNITY SERVICE', communityServices.some((s) => s && s.toLowerCase().includes('legal')));
  setCheckbox(form, 'COMMUNITY BEAUTIFICATION', communityServices.some((s) => s && s.toLowerCase().includes('beautification')));
  setCheckbox(form, 'FRIENDLY VISIT', communityServices.some((s) => s && s.toLowerCase().includes('friendly')));
  setCheckbox(form, 'RELIGIOUS', communityServices.some((s) => s && s.toLowerCase().includes('religious')));

  if (seniorRecord.community_service_other_text) {
    setText(form, 'COMMUNITY SERVICE OTHER TEXT', seniorRecord.community_service_other_text);
    setCheckbox(form, 'OTHERS COMMUNITY SERCVICE', true);
    setCheckbox(form, 'OTHERS COMMUNITY SERVICE', true);
  }
}

(async () => {
  try {
    const type = (process.argv[2] || '').toLowerCase();
    const inputFile = getArgValue('--input');
    const outputFile = getArgValue('--output');
    const raw = inputFile ? fs.readFileSync(inputFile, 'utf8') : await readStdin();
    const payload = raw ? JSON.parse(raw) : {};

    const templatePath = payload.templatePath;
    const record = payload.record || {};

    if (!templatePath || !fs.existsSync(templatePath)) {
      throw new Error('Template PDF not found.');
    }

    const templateBytes = fs.readFileSync(templatePath);
    const pdfDoc = await PDFDocument.load(templateBytes);
    const form = pdfDoc.getForm();

    if (type === 'pwd') fillPwd(form, record);
    else if (type === 'senior') fillSenior(form, record);
    else throw new Error('Unsupported type');

    try {
      form.flatten();
    } catch (_) {}

    const bytes = Buffer.from(await pdfDoc.save());
    if (outputFile) {
      fs.writeFileSync(outputFile, bytes);
    } else {
      process.stdout.write(bytes);
    }
  } catch (err) {
    process.stderr.write((err && err.message ? err.message : String(err)) + '\n');
    process.exit(1);
  }
})();
