require("dotenv").config(); 
const express = require('express');
const bcrypt = require('bcrypt');
const saltrounds = 10;
const session = require('express-session');
const { query } = require("../model/databasesql");
const axios = require('axios');
const path = require("path");
const fs = require("fs");
const { PDFDocument } = require('pdf-lib');
const nodemailer = require('nodemailer');

// ---- ArcGIS map helpers (MySQL + GeoJSON name alignment) ----
// We generate marker coordinates from the same `all_barangays.geojson` used by the ArcGIS boundary,
// so the backend counts (by `barangay` string) reliably match map barangay names.
const ALL_BARANGAYS_GEOJSON_PATH = path.join(
  __dirname,
  "..",
  "files",
  "assets",
  "data",
  "all_barangays.geojson"
);

let barangayCentroidsCache = null;

function getRedirectPathByRole(user) {
  if (user.role === "Admin") return "/index";
  if (user.role === "Staff") {
    if (user.staff_classification === "OSCA") return "/Senior-form";
    return "/Pwd-form";
  }
  if (user.role === "Super Admin") return "/index-superadmin";
  if (user.role === "Barangay") return "/barangay";
  return "/index";
}

function generateVerificationCode() {
  return String(Math.floor(100000 + Math.random() * 900000));
}

async function sendLoginVerificationEmail(toEmail, code) {
  console.log(`--- Starting Email Process ---`);
  console.log(`Attempting to send code to: ${toEmail}`);
  console.log(`Using SMTP Host: ${process.env.SMTP_HOST} on Port: ${process.env.SMTP_PORT}`);

  const transporter = nodemailer.createTransport({
    host: process.env.SMTP_HOST,
    port: parseInt(process.env.SMTP_PORT), 
    secure: process.env.SMTP_SECURE === "true", 
    auth: {
      user: process.env.SMTP_USER,
      pass: process.env.SMTP_PASS 
    },
    tls: {
      rejectUnauthorized: false 
    }
  });

  try {
    // 1. Verify the connection configuration
    await transporter.verify();
    console.log("SMTP Configuration is correct. Connection established.");

    // 2. Attempt to send the mail
    const info = await transporter.sendMail({
      from: `"Social Welfare Office" <${process.env.SMTP_USER}>`,
      to: toEmail,
      subject: "Your login verification code",
      text: `Your verification code is ${code}.`,
      html: `<p>Your verification code is <strong>${code}</strong>.</p>`
    });

    console.log("Email sent successfully!");
    console.log("Message ID:", info.messageId);
    console.log(`--- Email Process Complete ---`);

  } catch (error) {
    console.error("--- SMTP ERROR ---");
    console.error("Error Code:", error.code);
    console.error("Error Message:", error.message);
    
    if (error.code === 'EAUTH') {
      console.error("DEBUG TIP: Authentication failed. This usually means your SMTP_PASS (App Password) is incorrect or your SMTP_USER email is wrong.");
    } else if (error.code === 'ETIMEDOUT') {
      console.error("DEBUG TIP: Connection timed out. Check if your firewall or ISP blocks port " + process.env.SMTP_PORT);
    }
    
    console.error("Full Error Stack:", error);
    console.error("--- End of Error Report ---");
    
    // Throw the error so your login route can handle the failure
    throw error; 
  }
}

function computePolygonCentroid(feature) {
  const geometry = feature?.geometry;
  if (!geometry) return null;

  // We approximate centroid using the average of all vertices in the first ring.
  // This is fast and good enough for map markers.
  let ring = null;
  if (geometry.type === "Polygon") {
    ring = geometry.coordinates?.[0];
  } else if (geometry.type === "MultiPolygon") {
    ring = geometry.coordinates?.[0]?.[0];
  }

  if (!Array.isArray(ring) || ring.length === 0) return null;

  let sumLon = 0;
  let sumLat = 0;
  let count = 0;

  for (const p of ring) {
    if (!Array.isArray(p) || p.length < 2) continue;
    const lon = Number(p[0]);
    const lat = Number(p[1]);
    if (!Number.isFinite(lon) || !Number.isFinite(lat)) continue;
    sumLon += lon;
    sumLat += lat;
    count += 1;
  }

  if (count === 0) return null;
  return { lon: sumLon / count, lat: sumLat / count };
}

function getBarangayCentroids() {
  if (barangayCentroidsCache) return barangayCentroidsCache;

  const raw = fs.readFileSync(ALL_BARANGAYS_GEOJSON_PATH, "utf8");
  const geojson = JSON.parse(raw);

  const list = [];
  for (const feature of geojson.features || []) {
    const name = feature?.properties?.ADM4_EN;
    if (!name) continue;

    const centroid = computePolygonCentroid(feature);
    if (!centroid) continue;

    list.push({
      name,
      lat: centroid.lat,
      lon: centroid.lon
    });
  }

  barangayCentroidsCache = list;
  return barangayCentroidsCache;
}


exports.createUser = async (req, res) => {
    try {
        const { name, email, password, confirm_password, role, barangay_id, staff_classification } = req.body;

       
        if (!name || !email || !password || !confirm_password || role=="user") {
            return res.status(400).json({ 
                success: false,
                error: "All fields are required" 
            });
        }
     

        if (password !== confirm_password) {
            return res.status(400).json({ 
                success: false,
                error: "Passwords do not match" 
            });
        }

        // Validate staff classification for Staff role
        let staffClassificationVal = null;
        if (role === "Staff") {
            if (!staff_classification) {
                return res.status(400).json({
                    success: false,
                    error: "Please select a staff classification (PDAO or OSCA)."
                });
            }
            const normalized = String(staff_classification).toUpperCase();
            if (normalized !== "PDAO" && normalized !== "OSCA") {
                return res.status(400).json({
                    success: false,
                    error: "Invalid staff classification. Must be PDAO or OSCA."
                });
            }
            staffClassificationVal = normalized;
        }

        let barangayIdVal = null;
        if (role === "Barangay") {
            if (barangay_id === undefined || barangay_id === null || String(barangay_id).trim() === "") {
                return res.status(400).json({
                    success: false,
                    error: "Please select a barangay for Barangay accounts.",
                });
            }
            const parsedId = parseInt(barangay_id, 10);
            if (Number.isNaN(parsedId)) {
                return res.status(400).json({ success: false, error: "Invalid barangay selection." });
            }
            const [br] = await query("SELECT id FROM barangays WHERE id = ? LIMIT 1", [parsedId]);
            if (!br.length) {
                return res.status(400).json({ success: false, error: "Invalid barangay selection." });
            }
            barangayIdVal = parsedId;
        }

        // Check if user already exists in MySQL
        const [existingRows] = await query("SELECT id FROM users WHERE email = ? LIMIT 1", [email]);
        if (existingRows.length > 0) {
            return res.status(400).json({ 
                success: false,
                error: "Email already exists" 
            });
        }

        // Hash the password before saving
        const hashedPassword = await bcrypt.hash(password, saltrounds);

        // Insert new user into MySQL (barangay_id NULL for non-Barangay roles, staff_classification only for Staff)
        const [result] = await query(
            "INSERT INTO users (name, email, password, role, status, barangay_id, staff_classification) VALUES (?, ?, ?, ?, 'Active', ?, ?)",
            [name, email, hashedPassword, role, barangayIdVal, staffClassificationVal]
        );

        // For security, don't return the hashed password in the response
        const userToReturn = { 
            id: result.insertId,
            name,
            email,
            role,
            status: "Active",
            barangay_id: barangayIdVal,
            staff_classification: staffClassificationVal,
        };

        res.status(201).json({ 
            success: true,
            message: "User created successfully", 
            user: userToReturn 
        });
    } catch (err) {
        res.status(400).json({ 
            success: false,
            error: err.message 
        });
    }
};

exports.login = async (req, res) => {
    try {
      const { email, password } = req.body;
  
      // Validate input
      if (!email || !password) {
        return res.status(400).json({
          success: false,
          error: "All fields are required",
        });
      }
  
      // Check if user exists in MySQL (barangay_id nullable — only set for Barangay role)
      const [rows] = await query(
        "SELECT id, name, email, password, role, status, barangay_id, staff_classification, is_verified FROM users WHERE email = ? LIMIT 1",
        [email]
      );
      if (rows.length === 0) {
        return res.status(401).json({
          success: false,
          error: "Invalid credentials",
        });
      }

      const user = rows[0];

      if (user.status !== "Active") {
      return res.status(403).json({
        success: false,
        error: "Account is not active. Please contact the administrator.",
      });
    }
  
      if (user) {
        console.log("---------------- DATABASE DEBUG ----------------");
        console.log("User found in DB:", user.username);
        console.log("Password string from DB:", user.password); 
        console.log("------------------------------------------------");

        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) {
            return res.status(401).json({ success: false, error: "Invalid credentials" });
        }
      }
      if (Number(user.is_verified) === 0) {
        const verificationCode = generateVerificationCode();
        const expiresAt = Date.now() + 10 * 60 * 1000;

        req.session.pendingVerification = {
          userId: user.id,
          email: user.email,
          role: user.role,
          barangay_id: user.barangay_id != null ? user.barangay_id : null,
          staff_classification: user.staff_classification || null,
          code: verificationCode,
          expiresAt
        };

        try {
          await sendLoginVerificationEmail(user.email, verificationCode);
        } catch (mailErr) {
          req.session.pendingVerification = null;
          return res.status(500).json({
            success: false,
            error: "Unable to send verification email. Please try again."
          });
        }

        return res.status(200).json({
          success: true,
          verificationRequired: true,
          message: "A verification code has been sent to your email."
        });
      }

      //Logs login
      // await query(
      //   "INSERT INTO login_logs (user_id) VALUES (?)",
      //   [user.id]
      // );
  
      // Store user data in session (excluding password)
      req.session.user = {
        _id: user.id,  // keep key name consistent for existing code
        email: user.email,
        role: user.role,  // Ensure 'role' exists in your database
        barangay_id: user.barangay_id != null ? user.barangay_id : null,
        staff_classification: user.staff_classification || null,
    };
    
      // Successful login response
      return res.redirect(getRedirectPathByRole(user));
  
    } catch (err) {
      res.status(500).json({
        success: false,
        error: err.message,
      });
    }
}

exports.verifyLoginCode = async (req, res) => {
  try {
    const { code } = req.body;
    const pending = req.session.pendingVerification;

    if (!pending) {
      return res.status(400).json({
        success: false,
        error: "No pending verification found. Please log in again."
      });
    }

    if (!code) {
      return res.status(400).json({
        success: false,
        error: "Verification code is required."
      });
    }

    if (Date.now() > pending.expiresAt) {
      req.session.pendingVerification = null;
      return res.status(400).json({
        success: false,
        error: "Verification code has expired. Please log in again."
      });
    }

    if (String(code).trim() !== String(pending.code)) {
      return res.status(401).json({
        success: false,
        error: "Invalid verification code."
      });
    }

    await query("UPDATE users SET is_verified = 1 WHERE id = ?", [pending.userId]);

    req.session.user = {
      _id: pending.userId,
      email: pending.email,
      role: pending.role,
      barangay_id: pending.barangay_id,
      staff_classification: pending.staff_classification
    };
    req.session.pendingVerification = null;

    return res.status(200).json({
      success: true,
      redirectUrl: getRedirectPathByRole(req.session.user)
    });
  } catch (err) {
    return res.status(500).json({
      success: false,
      error: err.message
    });
  }
}

exports.logout = (req, res) => {
    req.session.destroy((err) => {
      if (err) {
        console.error('Session destruction error:', err);
        return res.redirect('/'); // Still redirect to root even on error
      }
      res.clearCookie('connect.sid'); // Clear the session cookie
      res.redirect('/'); // Explicit redirect to homepage
    });
  };


 //senior citizen form
  exports.createResident = async (req, res) => {
 
  
    try {
      const body = req.body;
  
      const firstName = body.identifying_information?.name?.first_name || body.first_name;
      const lastName = body.identifying_information?.name?.last_name || body.last_name;
      const dateOfBirthRaw = body.identifying_information?.date_of_birth || body.birthday || body.date_of_birth;

      if (!firstName || !lastName || !dateOfBirthRaw) {
        return res.status(400).json({
          success: false,
          alert: {
            title: 'Validation Error',
            text: 'Name and date of birth are required',
            icon: 'error',
            showConfirmButton: true
          }
        });
      }

      const dob = new Date(dateOfBirthRaw);
      if (Number.isNaN(dob.getTime())) {
        return res.status(400).json({
          success: false,
          alert: {
            title: 'Validation Error',
            text: 'Invalid date of birth',
            icon: 'error',
            showConfirmButton: true
          }
        });
      }

      const startOfDay = new Date(dob.getFullYear(), dob.getMonth(), dob.getDate());
      const endOfDay = new Date(dob.getFullYear(), dob.getMonth(), dob.getDate() + 1);

      const [existingRows] = await query(
        `SELECT id FROM senior_citizens 
         WHERE first_name = ? AND last_name = ? 
           AND date_of_birth >= ? AND date_of_birth < ? 
           AND status = 'Active'
         LIMIT 1`,
        [firstName, lastName, startOfDay, endOfDay]
      );

      if (existingRows.length > 0) {
        return res.status(400).json({
          success: false,
          alert: {
            title: 'Duplicate Record Found',
            text: `A Senior Citizen record with the name "${firstName} ${lastName}" and date of birth "${dob.toLocaleDateString()}" already exists in the system.`,
            icon: 'warning',
            showConfirmButton: true
          },
          isDuplicate: true
        });
      }
  
      const barangay = body.identifying_information?.address?.barangay || body.barangay;
      const purok = body.identifying_information?.address?.purok || body.purok;
      const age = parseInt(body.identifying_information?.age || body.age, 10) || null;
      const maritalStatus = body.identifying_information?.marital_status || body.marital_status || body.civil_status;
      const gender = body.identifying_information?.gender || body.gender;

      const rawPlace = body.identifying_information?.place_of_birth || body.place_of_birth;
      const placeOfBirth = Array.isArray(rawPlace)
        ? rawPlace.filter(Boolean).join(', ')
        : (rawPlace || null);

      const middleName = body.identifying_information?.name?.middle_name || body.middle_name || null;
      const extension = body.identifying_information?.name?.extension || body.extension || null;

      const oscaId = body.identifying_information?.osca_id_number || body.osca_id || null;
      const gsisSss = body.identifying_information?.gsis_sss || body.gsis_sss_no || body.gsis_sss || null;
      const philhealth = body.identifying_information?.philhealth || body.philhealth_no || body.philhealth || null;
      const scAssociationId = body.identifying_information?.sc_association_org_id_no || body.sc_association_id || null;
      const tin = body.identifying_information?.tin || body.tin_no || body.tin || null;
      const serviceEmployment = body.identifying_information?.service_business_employment || body.service || body.service_business_employment || null;
      const currentPension = body.identifying_information?.current_pension || body.pension || body.current_pension || null;
      const capabilityToTravel = (body.identifying_information?.capability_to_travel || body.capability_to_travel) === 'Yes' ? 'Yes' : 'No';

      const spouseName = body.family_composition?.spouse?.name || null;
      const fatherLast = body.family_composition?.father?.last_name || null;
      const fatherFirst = body.family_composition?.father?.first_name || null;
      const fatherMiddle = body.family_composition?.father?.middle_name || null;
      const fatherExt = body.family_composition?.father?.extension || null;
      const motherLast = body.family_composition?.mother?.last_name || null;
      const motherFirst = body.family_composition?.mother?.first_name || null;
      const motherMiddle = body.family_composition?.mother?.middle_name || null;

      const communityServiceOther = body.community_service_other_text || null;

      const [result] = await query(
        `INSERT INTO senior_citizens (
          reference_code,
          last_name, first_name, middle_name, extension,
          barangay, purok,
          date_of_birth, age, place_of_birth,
          marital_status, gender,
          osca_id_number, gsis_sss, philhealth, sc_association_org_id_no, tin,
          other_govt_id,
          service_business_employment, current_pension, capability_to_travel,
          spouse_name,
          father_last_name, father_first_name, father_middle_name, father_extension,
          mother_last_name, mother_first_name, mother_middle_name,
          community_service_other_text,
          status, archive_reason, edited_by, edited_at
        ) VALUES (
          NULL,
          ?, ?, ?, ?,
          ?, ?, ?,
          ?, ?,
          ?, ?,
          ?, ?, ?, ?, ?,
          NULL,
          ?, ?, ?,
          ?,
          ?, ?, ?, ?,
          ?, ?, ?,
          ?,
          'Active', NULL, NULL, NULL
        )`,
        [
          lastName,
          firstName,
          middleName,
          extension,
          barangay,
          purok,
          dob,
          age,
          placeOfBirth,
          maritalStatus,
          gender,
          oscaId,
          gsisSss,
          philhealth,
          scAssociationId,
          tin,
          serviceEmployment,
          currentPension,
          capabilityToTravel,
          spouseName,
          fatherLast,
          fatherFirst,
          fatherMiddle,
          fatherExt,
          motherLast,
          motherFirst,
          motherMiddle,
          communityServiceOther
        ]
      );

      const seniorId = result.insertId;

      const rawContacts = Array.isArray(body.identifying_information?.contacts)
        ? body.identifying_information.contacts
        : Array.isArray(body.contacts)
          ? body.contacts
          : (body.contacts ? [body.contacts] : []);

      for (const contact of rawContacts) {
        if (!contact || !contact.name) continue;
        await query(
          `INSERT INTO senior_contacts (senior_id, type, name, relationship, phone, email)
           VALUES (?, ?, ?, ?, ?, ?)`,
          [
            seniorId,
            contact.type || 'primary',
            contact.name,
            contact.relationship || null,
            contact.phone || null,
            contact.email || null
          ]
        );
      }

      const children = Array.isArray(body.family_composition?.children)
        ? body.family_composition.children
        : [];

      for (const child of children) {
        if (!child || !child.full_name) continue;
        await query(
          `INSERT INTO senior_children (senior_id, full_name, occupation, income, age, working_status)
           VALUES (?, ?, ?, ?, ?, ?)`,
          [
            seniorId,
            child.full_name,
            child.occupation || null,
            child.income || null,
            child.age ? parseInt(child.age, 10) : null,
            child.working_status || null
          ]
        );
      }

      const eduAttain = Array.isArray(body.education_hr_profile?.educational_attainment)
        ? body.education_hr_profile.educational_attainment
        : body.education_hr_profile?.educational_attainment
          ? [body.education_hr_profile.educational_attainment]
          : [];

      for (const e of eduAttain) {
        if (!e) continue;
        await query(
          `INSERT INTO senior_education (senior_id, educational_attainment)
           VALUES (?, ?)`,
          [seniorId, e]
        );
      }

      const skillsArr = Array.isArray(body.education_hr_profile?.skills)
        ? body.education_hr_profile.skills
        : [];

      for (const s of skillsArr) {
        if (!s) continue;
        await query(
          `INSERT INTO senior_skills (senior_id, skill)
           VALUES (?, ?)`,
          [seniorId, s]
        );
      }

      const services = Array.isArray(body.community_service)
        ? body.community_service
        : body.community_service
          ? [body.community_service]
          : [];

      for (const svc of services) {
        if (!svc) continue;
        await query(
          `INSERT INTO senior_community_services (senior_id, service)
           VALUES (?, ?)`,
          [seniorId, svc]
        );
      }
  
      res.status(201).json({
        success: true,
        alert: {
          title: 'Success!',
          text: 'Senior citizen record created successfully',
          icon: 'success',
          showConfirmButton: false,
          timer: 3000
        },
        data: {
          id: seniorId,
          first_name: firstName,
          last_name: lastName,
          middle_name: middleName,
          barangay,
          purok,
          date_of_birth: dob,
          age,
          marital_status: maritalStatus,
          gender
        }
      });
  
    } catch (error) {
      console.error('Error creating resident:', error);
  
      res.status(500).json({
        success: false,
        alert: {
          title: 'Error',
          text: 'An unexpected error occurred',
          icon: 'error',
          showConfirmButton: true
        },
        error: process.env.NODE_ENV === 'development' ? {
          message: error.message,
          stack: error.stack
        } : undefined
      });
    }
  };

// Helper to map MySQL pwd row + related rows into an object similar to the old Mongoose model
async function getPwdByIdWithRelations(pwdId) {
  const id = parseInt(pwdId, 10);
  if (Number.isNaN(id)) {
    return null;
  }

  const [[pwdRow]] = await query("SELECT * FROM pwd WHERE id = ? LIMIT 1", [id]);
  if (!pwdRow) {
    return null;
  }

  const [editRows] = await query(
    "SELECT field, old_value, new_value, edited_by, edited_at FROM pwd_edit_logs WHERE pwd_id = ? ORDER BY id ASC",
    [id]
  );

  const [contactsRows] = await query(
    "SELECT type, name, relationship, phone, email FROM pwd_contacts WHERE pwd_id = ?",
    [id]
  );
  const [disabilityRows] = await query(
    "SELECT disability FROM pwd_disabilities WHERE pwd_id = ?",
    [id]
  );
  const [causeRows] = await query(
    "SELECT cause FROM pwd_disability_causes WHERE pwd_id = ?",
    [id]
  );

  const contacts = contactsRows.map(c => ({
    type: c.type,
    name: c.name,
    relationship: c.relationship,
    phone: c.phone,
    email: c.email
  }));

  const disability = disabilityRows.map(d => d.disability).filter(Boolean);
  const cause_disability = causeRows.map(c => c.cause).filter(Boolean);

  const tryParse = (val) => {
    if (val === null || val === undefined) return val;
    if (typeof val !== 'string') return val;
    const trimmed = val.trim();
    if (!trimmed) return '';
    if (
      (trimmed.startsWith('{') && trimmed.endsWith('}')) ||
      (trimmed.startsWith('[') && trimmed.endsWith(']'))
    ) {
      try {
        return JSON.parse(trimmed);
      } catch {
        return val;
      }
    }
    return val;
  };

  const changes = editRows.map(e => ({
    field: e.field,
    old_value: tryParse(e.old_value),
    new_value: tryParse(e.new_value)
  }));

  return {
    _id: pwdRow.id,
    ...pwdRow,
    contacts,
    disability,
    cause_disability,
    edit_log: {
      edited_by: editRows.length ? editRows[editRows.length - 1].edited_by : null,
      edited_at: editRows.length ? editRows[editRows.length - 1].edited_at : null,
      changes
    }
  };
}

// Helper to map MySQL senior_citizens row + related rows into an object similar to the old SeniorCitizen model
async function getSeniorByIdWithRelations(seniorId) {
  const id = parseInt(seniorId, 10);
  if (Number.isNaN(id)) {
    return null;
  }

  const [[row]] = await query("SELECT * FROM senior_citizens WHERE id = ? LIMIT 1", [id]);
  if (!row) {
    return null;
  }

  const [childrenRows] = await query(
    "SELECT full_name, occupation, income, age, working_status FROM senior_children WHERE senior_id = ?",
    [id]
  );
  const [educationRows] = await query(
    "SELECT educational_attainment FROM senior_education WHERE senior_id = ?",
    [id]
  );
  const [skillRows] = await query(
    "SELECT skill FROM senior_skills WHERE senior_id = ?",
    [id]
  );
  const [serviceRows] = await query(
    "SELECT service FROM senior_community_services WHERE senior_id = ?",
    [id]
  );
  const [contactRows] = await query(
    "SELECT type, name, relationship, phone, email FROM senior_contacts WHERE senior_id = ?",
    [id]
  );
  const [editRows] = await query(
    "SELECT field, old_value, new_value, edited_by, edited_at FROM senior_edit_logs WHERE senior_id = ? ORDER BY id ASC",
    [id]
  );

  const contacts = contactRows.map(c => ({
    type: c.type,
    name: c.name,
    relationship: c.relationship,
    phone: c.phone,
    email: c.email
  }));

  const children = childrenRows.map(child => ({
    full_name: child.full_name,
    occupation: child.occupation,
    income: child.income,
    age: child.age,
    working_status: child.working_status
  }));

  const educational_attainment = educationRows
    .map(e => e.educational_attainment)
    .filter(Boolean);

  const skills = skillRows
    .map(s => s.skill)
    .filter(Boolean);

  const community_service = serviceRows
    .map(s => s.service)
    .filter(Boolean);

  const changes = editRows.map(e => ({
    field: e.field,
    old_value: e.old_value,
    new_value: e.new_value,
    edited_by: e.edited_by,
    edited_at: e.edited_at
  }));

  return {
    _id: row.id,
    reference_code: row.reference_code,
    identifying_information: {
      name: {
        last_name: row.last_name,
        first_name: row.first_name,
        middle_name: row.middle_name,
        extension: row.extension
      },
      address: {
        barangay: row.barangay,
        purok: row.purok
      },
      date_of_birth: row.date_of_birth,
      age: row.age,
      marital_status: row.marital_status,
      gender: row.gender,
      place_of_birth: row.place_of_birth ? [row.place_of_birth] : [],
      contacts,
      osca_id_number: row.osca_id_number,
      gsis_sss: row.gsis_sss,
      philhealth: row.philhealth,
      sc_association_org_id_no: row.sc_association_org_id_no,
      tin: row.tin,
      other_govt_id: row.other_govt_id,
      service_business_employment: row.service_business_employment,
      current_pension: row.current_pension,
      capability_to_travel: row.capability_to_travel
    },
    family_composition: {
      spouse: {
        name: row.spouse_name
      },
      father: {
        last_name: row.father_last_name,
        first_name: row.father_first_name,
        middle_name: row.father_middle_name,
        extension: row.father_extension
      },
      mother: {
        last_name: row.mother_last_name,
        first_name: row.mother_first_name,
        middle_name: row.mother_middle_name
      },
      children
    },
    education_hr_profile: {
      educational_attainment,
      skills,
      skill_other_text: null
    },
    community_service,
    community_service_other_text: row.community_service_other_text,
    status: row.status,
    archive_reason: row.archive_reason,
    created_at: row.created_at,
    edit_log: {
      edited_by: row.edited_by,
      edited_at: row.edited_at,
      changes
    }
  };
}

exports.registerPwd = async (req, res) => {
  try {
   

    const birthday = new Date(req.body.birthday);
    if (Number.isNaN(birthday.getTime())) {
      return res.status(400).json({
        success: false,
        alert: {
          title: 'Invalid Data',
          text: 'Birthday is invalid',
          icon: 'error',
          showConfirmButton: true
        }
      });
    }

    const startOfDay = new Date(birthday.getFullYear(), birthday.getMonth(), birthday.getDate());
    const endOfDay = new Date(birthday.getFullYear(), birthday.getMonth(), birthday.getDate() + 1);

    const [existingRows] = await query(
      "SELECT id FROM pwd WHERE first_name = ? AND last_name = ? AND birthday >= ? AND birthday < ? LIMIT 1",
      [req.body.first_name, req.body.last_name, startOfDay, endOfDay]
    );

    if (existingRows.length > 0) {
      return res.status(400).json({
        success: false,
        alert: {
          title: 'Duplicate Record Found',
          text: `A PWD record with the name "${req.body.first_name} ${req.body.last_name}" and birthday "${birthday.toLocaleDateString()}" already exists in the system.`,
          icon: 'warning',
          showConfirmButton: true
        },
        isDuplicate: true
      });
    }

    const age = parseInt(req.body.age, 10);

    const [result] = await query(
      `INSERT INTO pwd (
        first_name, middle_name, last_name,
        barangay, purok,
        birthday, age, gender,
        place_of_birth, civil_status, spouse_name,
        fatherLastName, fatherFirstName, fatherMiddleName, fatherExtension,
        motherLastName, motherFirstName, motherMiddleName,
        sss_id, gsis_sss_no, psn_no, philhealth_no,
        education_level, employment_status, employment_category, employment_type,
        disability_other_text, cause_other_text, status, archive_reason
      ) VALUES (
        ?, ?, ?,
        ?, ?,
        ?, ?, ?,
        ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, 'Active', NULL
      )`,
      [
        req.body.first_name,
        req.body.middle_name || null,
        req.body.last_name,
        req.body.barangay,
        req.body.purok,
        birthday,
        Number.isNaN(age) ? null : age,
        req.body.gender,
        req.body.place_of_birth,
        req.body.civil_status,
        req.body.spouse_name || null,
        req.body.fatherLastName || null,
        req.body.fatherFirstName || null,
        req.body.fatherMiddleName || null,
        req.body.fatherExtension || null,
        req.body.motherLastName || null,
        req.body.motherFirstName || null,
        req.body.motherMiddleName || null,
        req.body.sss_id || null,
        req.body.gsis_sss_no || null,
        req.body.psn_no || null,
        req.body.philhealth_no || null,
        req.body.education_level,
        req.body.employment_status,
        req.body.employment_category || null,
        req.body.employment_type || null,
        req.body.disability_other_text || null,
        req.body.cause_other_text || null
      ]
    );

    const pwdId = result.insertId;

    const contacts = Array.isArray(req.body.contacts) ? req.body.contacts : [];
    for (const c of contacts) {
      if (!c || !c.name) continue;
      await query(
        `INSERT INTO pwd_contacts (pwd_id, type, name, relationship, phone, email)
         VALUES (?, ?, ?, ?, ?, ?)`,
        [pwdId, c.type || null, c.name, c.relationship || null, c.phone || null, c.email || null]
      );
    }

    const disabilities = Array.isArray(req.body.disability) ? req.body.disability : [];
    for (const d of disabilities) {
      if (!d) continue;
      await query(
        "INSERT INTO pwd_disabilities (pwd_id, disability) VALUES (?, ?)",
        [pwdId, d]
      );
    }

    const causes = Array.isArray(req.body.cause_disability) ? req.body.cause_disability : [];
    for (const c of causes) {
      if (!c) continue;
      await query(
        "INSERT INTO pwd_disability_causes (pwd_id, cause) VALUES (?, ?)",
        [pwdId, c]
      );
    }

    const savedPwd = await getPwdByIdWithRelations(pwdId);

    res.status(201).json({
      success: true,
      message: 'PWD registration successful',
      data: savedPwd
    });

  } catch (err) {
    console.error('Registration error:', err);

    res.status(500).json({
      success: false,
      message: 'Internal Server Error',
      error: err.message
    });
  }
};

exports.updatePwd = async (req, res) => {
  try {
   
    const { pwd_id, ...updateData } = req.body;
    
 
    if (!pwd_id) {
      return res.status(400).json({
        message: 'PWD ID is required',
        success: false
      });
    }

    const id = parseInt(pwd_id, 10);
    if (Number.isNaN(id)) {
      return res.status(400).json({
        message: 'Invalid PWD ID',
        success: false
      });
    }

    const before = await getPwdByIdWithRelations(id);
    if (!before) {
      return res.status(404).json({
        message: 'PWD record not found',
        success: false
      });
    }

    if (updateData.birthday) {
      updateData.birthday = new Date(updateData.birthday);
    }

    if (updateData.age) {
      updateData.age = parseInt(updateData.age, 10);
    }

    const fieldsToClean = ['middle_name', 'place_of_birth', 'spouse_name', 
                          'fatherFirstName', 'fatherMiddleName', 'fatherLastName', 'fatherExtension',
                          'motherFirstName', 'motherMiddleName', 'motherLastName',
                          'employment_category', 'employment_type',
                          'disability_other_text', 'cause_other_text'];
    
    fieldsToClean.forEach(field => {
      if (updateData[field] === '' || updateData[field] === undefined) {
        updateData[field] = null;
      }
    });

    const editorEmail = req.session?.user?.email || updateData.edited_by || 'Unknown';
    const editTimestamp = updateData.edited_at ? new Date(updateData.edited_at) : new Date();

    delete updateData.edited_by;
    delete updateData.edited_at;

    const setClauses = [];
    const params = [];

    const simpleFieldMap = {
      first_name: 'first_name',
      middle_name: 'middle_name',
      last_name: 'last_name',
      barangay: 'barangay',
      purok: 'purok',
      birthday: 'birthday',
      age: 'age',
      gender: 'gender',
      place_of_birth: 'place_of_birth',
      civil_status: 'civil_status',
      spouse_name: 'spouse_name',
      fatherLastName: 'fatherLastName',
      fatherFirstName: 'fatherFirstName',
      fatherMiddleName: 'fatherMiddleName',
      fatherExtension: 'fatherExtension',
      motherLastName: 'motherLastName',
      motherFirstName: 'motherFirstName',
      motherMiddleName: 'motherMiddleName',
      sss_id: 'sss_id',
      gsis_sss_no: 'gsis_sss_no',
      psn_no: 'psn_no',
      philhealth_no: 'philhealth_no',
      education_level: 'education_level',
      employment_status: 'employment_status',
      employment_category: 'employment_category',
      employment_type: 'employment_type',
      disability_other_text: 'disability_other_text',
      cause_other_text: 'cause_other_text',
      status: 'status',
      archive_reason: 'archive_reason'
    };

    Object.entries(simpleFieldMap).forEach(([key, column]) => {
      if (updateData[key] !== undefined) {
        setClauses.push(`${column} = ?`);
        params.push(updateData[key]);
      }
    });

    if (setClauses.length > 0) {
      params.push(id);
      const [result] = await query(
        `UPDATE pwd SET ${setClauses.join(", ")} WHERE id = ?`,
        params
      );

      if (result.affectedRows === 0) {
        return res.status(404).json({
          message: 'PWD record not found',
          success: false
        });
      }
    }

    if (Array.isArray(updateData.contacts)) {
      await query("DELETE FROM pwd_contacts WHERE pwd_id = ?", [id]);
      for (const c of updateData.contacts) {
        if (!c || !c.name) continue;
        await query(
          `INSERT INTO pwd_contacts (pwd_id, type, name, relationship, phone, email)
           VALUES (?, ?, ?, ?, ?, ?)`,
          [id, c.type || null, c.name, c.relationship || null, c.phone || null, c.email || null]
        );
      }
    }

    if (Array.isArray(updateData.disability)) {
      await query("DELETE FROM pwd_disabilities WHERE pwd_id = ?", [id]);
      for (const d of updateData.disability) {
        if (!d) continue;
        await query(
          "INSERT INTO pwd_disabilities (pwd_id, disability) VALUES (?, ?)",
          [id, d]
        );
      }
    }

    if (Array.isArray(updateData.cause_disability)) {
      await query("DELETE FROM pwd_disability_causes WHERE pwd_id = ?", [id]);
      for (const c of updateData.cause_disability) {
        if (!c) continue;
        await query(
          "INSERT INTO pwd_disability_causes (pwd_id, cause) VALUES (?, ?)",
          [id, c]
        );
      }
    }

    const updatedPwdRaw = await getPwdByIdWithRelations(id);

    const normalizeDate = (val) => {
      if (!val) return null;
      const d = new Date(val);
      if (Number.isNaN(d.getTime())) return null;
      const y = d.getFullYear();
      const m = `${d.getMonth() + 1}`.padStart(2, '0');
      const day = `${d.getDate()}`.padStart(2, '0');
      return `${y}-${m}-${day}`;
    };

    const extractForLog = (p) => ({
      first_name: p?.first_name ?? null,
      middle_name: p?.middle_name ?? null,
      last_name: p?.last_name ?? null,
      barangay: p?.barangay ?? null,
      purok: p?.purok ?? null,
      birthday: normalizeDate(p?.birthday),
      age: p?.age ?? null,
      gender: p?.gender ?? null,
      place_of_birth: p?.place_of_birth ?? null,
      civil_status: p?.civil_status ?? null,
      spouse_name: p?.spouse_name ?? null,
      fatherLastName: p?.fatherLastName ?? null,
      fatherFirstName: p?.fatherFirstName ?? null,
      fatherMiddleName: p?.fatherMiddleName ?? null,
      fatherExtension: p?.fatherExtension ?? null,
      motherLastName: p?.motherLastName ?? null,
      motherFirstName: p?.motherFirstName ?? null,
      motherMiddleName: p?.motherMiddleName ?? null,
      sss_id: p?.sss_id ?? null,
      gsis_sss_no: p?.gsis_sss_no ?? null,
      psn_no: p?.psn_no ?? null,
      philhealth_no: p?.philhealth_no ?? null,
      education_level: p?.education_level ?? null,
      employment_status: p?.employment_status ?? null,
      employment_category: p?.employment_category ?? null,
      employment_type: p?.employment_type ?? null,
      disability_other_text: p?.disability_other_text ?? null,
      cause_other_text: p?.cause_other_text ?? null,
      contacts: p?.contacts ?? [],
      disability: p?.disability ?? [],
      cause_disability: p?.cause_disability ?? []
    });

    const serialize = (val) => {
      if (val === null || val === undefined) return '';
      if (typeof val === 'string' || typeof val === 'number' || typeof val === 'boolean') return String(val);
      return JSON.stringify(val);
    };

    const beforeView = extractForLog(before);
    const afterView = extractForLog(updatedPwdRaw);

    const changeEntries = [];
    for (const [field, oldVal] of Object.entries(beforeView)) {
      const newVal = afterView[field];
      if (serialize(oldVal) !== serialize(newVal)) {
        changeEntries.push({
          field,
          old_value: oldVal,
          new_value: newVal
        });
      }
    }

    for (const change of changeEntries) {
      await query(
        `INSERT INTO pwd_edit_logs (pwd_id, field, old_value, new_value, edited_by, edited_at)
         VALUES (?, ?, ?, ?, ?, ?)`,
        [
          id,
          change.field,
          serialize(change.old_value),
          serialize(change.new_value),
          editorEmail,
          editTimestamp
        ]
      );
    }

    const updatedPwd = {
      ...updatedPwdRaw,
      edit_log: {
        edited_by: editorEmail,
        edited_at: editTimestamp,
        changes: changeEntries
      }
    };

    res.status(200).json({
      message: 'PWD record updated successfully',
      data: updatedPwd,
      success: true
    });
  } catch (err) {
    console.error('Error updating PWD:', err);

    res.status(500).json({
      message: 'Internal Server Error',
      success: false
    });
  }
};

// Archive PWD record
exports.archivePwd = async (req, res) => {
  try {
    const { pwd_id, reason } = req.body;
    
    if (!pwd_id) {
      return res.status(400).json({
        message: 'PWD ID is required',
        success: false
      });
    }

    const id = parseInt(pwd_id, 10);
    if (Number.isNaN(id)) {
      return res.status(400).json({
        message: 'Invalid PWD ID',
        success: false
      });
    }

    const [result] = await query(
      "UPDATE pwd SET status = 'Archived', archive_reason = ? WHERE id = ?",
      [reason || null, id]
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({
        message: 'PWD record not found',
        success: false
      });
    }

    const archivedPwd = await getPwdByIdWithRelations(id);

    res.status(200).json({
      success: true,
      message: 'PWD record archived successfully',
      data: archivedPwd
    });

  } catch (err) {
    console.error('Archive PWD error:', err);
    res.status(500).json({
      message: 'Internal Server Error',
      success: false,
      error: err.message
    });
  }
};

// Unarchive PWD record
exports.unarchivePwd = async (req, res) => {
  try {
    const { pwd_id } = req.body;
    
    if (!pwd_id) {
      return res.status(400).json({
        message: 'PWD ID is required',
        success: false
      });
    }

    const id = parseInt(pwd_id, 10);
    if (Number.isNaN(id)) {
      return res.status(400).json({
        message: 'Invalid PWD ID',
        success: false
      });
    }

    const [result] = await query(
      "UPDATE pwd SET status = 'Active', archive_reason = NULL WHERE id = ?",
      [id]
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({
        message: 'PWD record not found',
        success: false
      });
    }

    const unarchivedPwd = await getPwdByIdWithRelations(id);

    res.status(200).json({
      success: true,
      message: 'PWD record unarchived successfully',
      data: unarchivedPwd
    });

  } catch (err) {
    console.error('Unarchive PWD error:', err);
    res.status(500).json({
      message: 'Internal Server Error',
      success: false,
      error: err.message
    });
  }
};

exports.generatePwdApplicationPdf = async (req, res) => {
  try {
    const { id } = req.params;

    const pwdRecord = await getPwdByIdWithRelations(id);

    if (!pwdRecord) {
      return res.status(404).json({
        success: false,
        message: 'PWD record not found'
      });
    }

    const templatePath = path.join(__dirname, '../default/pdf/PWD-APPLICATION-FORMFIELD.pdf');
    const templateBytes = await fs.promises.readFile(templatePath);
    const pdfDoc = await PDFDocument.load(templateBytes);
    const form = pdfDoc.getForm();

    const shouldLogDebug = process.env.NODE_ENV !== 'production';
    const warnMissingField = (msg) => {
      if (shouldLogDebug) {
        console.warn(`[PWD PDF] ${msg}`);
      }
    };

    const CONSISTENT_FONT_SIZE = 8; // Set consistent font size for all text fields

    const setText = (fieldName, value = '') => {
      if (!fieldName) return;
      try {
        const field = form.getTextField(fieldName);
        field.setText(value || '');
        // Set consistent font size for this field
        field.setFontSize(CONSISTENT_FONT_SIZE);
      } catch (err) {
        warnMissingField(`Text field "${fieldName}" not found`);
      }
    };

    const setCheckbox = (fieldName, checked) => {
      if (!fieldName) return;
      try {
        const field = form.getCheckBox(fieldName);
        if (checked) {
          field.check();
        } else {
          field.uncheck();
        }
      } catch (err) {
        warnMissingField(`Checkbox "${fieldName}" not found`);
      }
    };

    const setRadio = (fieldName, option) => {
      if (!fieldName) return;
      try {
        const radio = form.getRadioGroup(fieldName);
        if (option) {
          radio.select(option);
        } else {
          radio.clear();
        }
      } catch (err) {
        warnMissingField(`Radio group "${fieldName}" not found`);
      }
    };

    const formatDate = (value) => {
      if (!value) return '';
      const date = new Date(value);
      if (Number.isNaN(date.getTime())) return '';
      const month = `${date.getMonth() + 1}`.padStart(2, '0');
      const day = `${date.getDate()}`.padStart(2, '0');
      const year = date.getFullYear();
      return `${month}/${day}/${year}`;
    };

    const civilStatusMap = {
      'Single': 'Single',
      'Single but Head of the Family': 'Single',
      'Separated': 'Separated',
      'Cohabitation (live-in)': 'Cohabitation livein',
      'Married': 'Married',
      'Widow/er': 'Widower',
      'Widowed': 'Widower'
    };

    const educationMap = {
      'Not Attended School': { radio: 'None', checks: [] },
      'Elementary Level': { radio: 'Elementary', checks: [] },
      'Elementary Graduate': { radio: 'Elementary', checks: [] },
      'High School Graduate': { radio: 'Junior High School', checks: ['Senior High School'] },
      'Vocational': { radio: 'Junior High School', checks: ['Vocational'] },
      'College Level': { radio: 'Junior High School', checks: ['College'] },
      'College Graduate': { radio: 'Junior High School', checks: ['College'] },
      'Post Graduate': { radio: 'Junior High School', checks: ['College', 'Post Graduate'] }
    };

    const employmentStatusMap = {
      'Employee': 'Employed',
      'Employed': 'Employed',
      'Unemployed': 'Unemployed',
      'Self-employed': 'Selfemployed',
      'Selfemployed': 'Selfemployed'
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
      'Acquired': 'Acquired',
      'Chronic Illness': 'Chronic Illness',
      'Injury': 'Injury',
      'Autism': 'Autism',
      'ADHD': 'ADHD',
      'Cerebral Palsy': 'Cerebral Palsy',
      'Down Syndrome': 'Down Syndrome'
    };

    // Personal information
    setText('LAST NAME', pwdRecord.last_name || '');
    setText('FIRST NAME', pwdRecord.first_name || '');
    setText('MIDDLE NAME', pwdRecord.middle_name || 'N/A');
    setText('SUFFIX', '');
    setText('Barangay', [pwdRecord.barangay, pwdRecord.purok].filter(Boolean).join(' / '));
    setText('DATE OF BIRTH', formatDate(pwdRecord.birthday));

    setCheckbox('Female', pwdRecord.gender === 'Female');
    setCheckbox('Male', pwdRecord.gender === 'Male');
    setRadio('7 CIVIL STATUS', civilStatusMap[pwdRecord.civil_status] || '');

    // Contact information
    const primaryContact = Array.isArray(pwdRecord.contacts) && pwdRecord.contacts.length > 0
      ? pwdRecord.contacts[0]
      : null;

    setText('Landline No', primaryContact?.phone || '');
    setText('Mobile No', primaryContact?.phone || '');
    setText('Email Address', primaryContact?.email || '');

    // Education & employment
    const educationSelection = educationMap[pwdRecord.education_level] || { radio: 'Junior High School', checks: [] };
    setRadio('12 EDUCATIONAL ATTAINMENT', educationSelection.radio);
    ['Senior High School', 'College', 'Vocational', 'Post Graduate'].forEach(option => {
      const shouldCheck = educationSelection.checks.includes(option);
      setCheckbox(option, shouldCheck);
    });

    setRadio('13 STATUS OF EMPLOYMENT', employmentStatusMap[pwdRecord.employment_status] || '');
    setRadio('13 a CATEGORY OF EMPLOYMENT', pwdRecord.employment_category || '');
    setText('Employment Category', pwdRecord.employment_type || '');

    // ID numbers
    setText('SSS NO', pwdRecord.sss_id || '');
    setText('GSIS NO', pwdRecord.gsis_sss_no || '');
    setText('PAGIBIG NO', '');
    setText('PSN NO', pwdRecord.psn_no || '');
    setText('PhilHealth NO', pwdRecord.philhealth_no || '');

    // Family information
    setText('LAST NAMEFATHERS NAME', pwdRecord.fatherLastName || '');
    setText('FIRST NAMEFATHERS NAME', pwdRecord.fatherFirstName || '');
    setText('MIDDLE NAMEFATHERS NAME', pwdRecord.fatherMiddleName || '');

    setText('LAST NAMEMOTHERS NAME', pwdRecord.motherLastName || '');
    setText('FIRST NAMEMOTHERS NAME', pwdRecord.motherFirstName || '');
    setText('MIDDLE NAMEMOTHERS NAME', pwdRecord.motherMiddleName || '');

    setCheckbox('APPLICANT', true);
    setCheckbox('GUARDIAN', false);
    setCheckbox('REPRESENTATTIVE', false);

    // Disability details
    Object.values(disabilityFieldMap).forEach(fieldName => setCheckbox(fieldName, false));
    if (Array.isArray(pwdRecord.disability)) {
      pwdRecord.disability.forEach(type => {
        const targetField = disabilityFieldMap[type];
        if (targetField) {
          setCheckbox(targetField, true);
        }
      });
    }

    Object.values(causeFieldMap).forEach(fieldName => setCheckbox(fieldName, false));
    if (Array.isArray(pwdRecord.cause_disability)) {
      pwdRecord.cause_disability.forEach(cause => {
        const targetField = causeFieldMap[cause];
        if (targetField) {
          setCheckbox(targetField, true);
        }
      });
    }

    // Optional "Other" text
    if (pwdRecord.disability_other_text || pwdRecord.cause_other_text) {
      const otherDetails = [
        pwdRecord.disability_other_text ? `Disability: ${pwdRecord.disability_other_text}` : null,
        pwdRecord.cause_other_text ? `Cause: ${pwdRecord.cause_other_text}` : null
      ].filter(Boolean).join(' | ');
      setText('15 ORGANIZATION INFORMATION', otherDetails);
    }

    // Try to flatten the form, but continue if it fails (some PDFs have broken references)
    try {
      form.flatten();
    } catch (flattenError) {
      console.warn('[PWD PDF] Could not flatten form, saving without flattening:', flattenError.message);
      // Continue without flattening - the form will still be filled
    }
    
    const pdfBytes = await pdfDoc.save();
    const safeLast = (pwdRecord.last_name || 'PWD').replace(/\s+/g, '-');
    const safeFirst = (pwdRecord.first_name || 'Record').replace(/\s+/g, '-');
    const filename = `PWD-${safeLast}-${safeFirst}.pdf`;

    res.setHeader('Content-Type', 'application/pdf');
    res.setHeader('Content-Disposition', `inline; filename="${encodeURIComponent(filename)}"`);
    res.setHeader('Content-Length', pdfBytes.length);
    return res.send(pdfBytes);
  } catch (error) {
    console.error('Error generating PWD PDF:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to generate PWD PDF'
    });
  }
};

// Archive Senior Citizen record
exports.archiveSenior = async (req, res) => {
  try {
    const { senior_id, reason } = req.body;
    
    if (!senior_id) {
      return res.status(400).json({
        message: 'Senior Citizen ID is required',
        success: false
      });
    }

    const id = parseInt(senior_id, 10);
    if (Number.isNaN(id)) {
      return res.status(400).json({
        message: 'Invalid Senior Citizen ID',
        success: false
      });
    }

    const [result] = await query(
      "UPDATE senior_citizens SET status = 'Archived', archive_reason = ? WHERE id = ?",
      [reason || null, id]
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({
        message: 'Senior Citizen record not found',
        success: false
      });
    }

    const archivedSenior = await getSeniorByIdWithRelations(id);

    res.status(200).json({
      success: true,
      message: 'Senior Citizen record archived successfully',
      data: archivedSenior
    });

  } catch (err) {
    console.error('Archive Senior error:', err);
    res.status(500).json({
      message: 'Internal Server Error',
      success: false,
      error: err.message
    });
  }
};

// Unarchive Senior Citizen record
exports.unarchiveSenior = async (req, res) => {
  try {
    const { senior_id, reason } = req.body;
    
    if (!senior_id) {
      return res.status(400).json({
        message: 'Senior Citizen ID is required',
        success: false
      });
    }

    const id = parseInt(senior_id, 10);
    if (Number.isNaN(id)) {
      return res.status(400).json({
        message: 'Invalid Senior Citizen ID',
        success: false
      });
    }

    const [result] = await query(
      "UPDATE senior_citizens SET status = 'Active', archive_reason = NULL WHERE id = ?",
      [id]
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({
        message: 'Senior Citizen record not found',
        success: false
      });
    }

    const unarchivedSenior = await getSeniorByIdWithRelations(id);

    res.status(200).json({
      success: true,
      message: 'Senior Citizen record unarchived successfully',
      data: unarchivedSenior
    });

  } catch (err) {
    console.error('Unarchive Senior error:', err);
    res.status(500).json({
      message: 'Internal Server Error',
      success: false,
      error: err.message
    });
  }
};






// Generate Senior Citizen Application PDF
exports.generateSeniorApplicationPdf = async (req, res) => {
  try {
    const { id } = req.params;

    const seniorId = parseInt(id, 10);
    if (Number.isNaN(seniorId)) {
      return res.status(400).json({
        success: false,
        message: 'Invalid Senior Citizen ID'
      });
    }

    const seniorRecord = await getSeniorByIdWithRelations(seniorId);

    if (!seniorRecord) {
      return res.status(404).json({
        success: false,
        message: 'Senior Citizen record not found'
      });
    }

    const templatePath = path.join(__dirname, '../default/pdf/SENIOR-FORMFIELD.pdf');
    const templateBytes = await fs.promises.readFile(templatePath);
    const pdfDoc = await PDFDocument.load(templateBytes);
    const form = pdfDoc.getForm();

    const shouldLogDebug = process.env.NODE_ENV !== 'production';
    const warnMissingField = (msg) => {
      if (shouldLogDebug) {
        console.warn(`[Senior PDF] ${msg}`);
      }
    };

    const CONSISTENT_FONT_SIZE = 8; // Set consistent font size for all text fields

    const setText = (fieldName, value = '') => {
      if (!fieldName) return;
      try {
        const field = form.getTextField(fieldName);
        field.setText(String(value || ''));
        // Set consistent font size for this field
        field.setFontSize(CONSISTENT_FONT_SIZE);
      } catch (err) {
        warnMissingField(`Text field "${fieldName}" not found`);
      }
    };

    const setCheckbox = (fieldName, checked) => {
      if (!fieldName) return;
      try {
        const field = form.getCheckBox(fieldName);
        if (checked) {
          field.check();
        } else {
          field.uncheck();
        }
      } catch (err) {
        warnMissingField(`Checkbox "${fieldName}" not found`);
      }
    };

    const formatDate = (value) => {
      if (!value) return '';
      const date = new Date(value);
      if (Number.isNaN(date.getTime())) return '';
      const month = `${date.getMonth() + 1}`.padStart(2, '0');
      const day = `${date.getDate()}`.padStart(2, '0');
      const year = date.getFullYear();
      return `${month}/${day}/${year}`;
    };

    const info = seniorRecord.identifying_information || {};
    const name = info.name || {};
    const address = info.address || {};
    const family = seniorRecord.family_composition || {};
    const education = seniorRecord.education_hr_profile || {};

    // Basic Information - Using actual PDF field names
    setText('LAST NAME', name.last_name || '');
    setText('FIRST NAME', name.first_name || '');
    setText('MIDDLE NAME', name.middle_name || '');
    setText('BARANGAY', address.barangay || '');
    setText('PUROK', address.purok || '');
    setText('PLACE OF BIRTH', Array.isArray(info.place_of_birth) ? info.place_of_birth.join(', ') : (info.place_of_birth || ''));
    setText('MARITAL STATUS', info.marital_status || '');
    setText('GENDER', info.gender || '');
    
    // Date of Birth - using numbered field (likely Text Field129 or similar)
    // Try multiple possible date fields
    const dobFormatted = formatDate(info.date_of_birth);
    if (dobFormatted) {
      setText('Text Field129', dobFormatted);
      setText('Text Field128', dobFormatted);
      setText('Text Field127', dobFormatted);
    setText('BIRTHDATE', dobFormatted); // Add BIRTHDATE field
    }
     
    // Contact Information
    const primaryContact = Array.isArray(info.contacts) && info.contacts.length > 0 ? info.contacts[0] : null;
    setText('CONTACT', primaryContact?.phone || '');
    setText('EMAIL', primaryContact?.email || '');
    setText('RELIGION', ''); // Not in schema
    
    // ID Information
    setText('OSCA ID', info.osca_id_number || '');
    setText('GSIS/SSS', info.gsis_sss || '');
    setText('TIN', info.tin || '');
    setText('PHILHEALTH', info.philhealth || '');
    setText('OTHER ID', info.other_govt_id || '');
    
    // Employment and Pension
    setText('SERVICE BUSINESS EMPLOYMENT', info.service_business_employment || '');
    setText('CURRENT PENSION', info.current_pension || '');
    
    // Family Composition
    setText('NAME OF SPOUSE', family.spouse?.name || '');
    
    const father = family.father || {};
    setText('FATHER FIRST NAME', father.first_name || '');
    setText('FATHER LAST NAME', father.last_name || '');
    setText('FATHER MIDDLE NAME', father.middle_name || '');
    setText('FATHER EXTENSION', father.extension || '');
    
    const mother = family.mother || {};
    setText('MOTHER FIRST NAME', mother.first_name || '');
    setText('MOTHER LAST NAME', mother.last_name || '');
    setText('MOTHER MIDDLE NAME', mother.middle_name || '');
    
    // Children - handle first child if available
    if (Array.isArray(family.children) && family.children.length > 0) {
      const firstChild = family.children[0];
      setText('CHILDREN', firstChild.full_name || '');
      setText('CHILDREN OCCUPATION', firstChild.occupation || '');
      setText('INCOME', firstChild.income || '');
      setText('CHILD AGE', firstChild.age ? String(firstChild.age) : '');
      setText('WORKING/ NOT WORKING', firstChild.working_status || '');
    }
    
    // Travel capability
    setCheckbox('TRAVEL YES', info.capability_to_travel === 'Yes' || info.capability_to_travel === 'Capable');
    setCheckbox('TRAVEL NO', info.capability_to_travel === 'No' || info.capability_to_travel === 'Not Capable');
    
    // Education Attainment
    const educationLevels = Array.isArray(education.educational_attainment) ? education.educational_attainment : [];
    setCheckbox('ELEMENTARY LEVEL', educationLevels.some(e => e.includes('Elementary Level')));
    setCheckbox('ELEMENTARY GRADUATE', educationLevels.some(e => e.includes('Elementary Graduate')));
    setCheckbox('HIGHSCHOOL LEVEL', educationLevels.some(e => e.includes('High School Level')));
    setCheckbox('HIGHSCHOOL GRADUATE', educationLevels.some(e => e.includes('High School Graduate')));
    setCheckbox('COLLEGE LEVEL', educationLevels.some(e => e.includes('College Level')));
    setCheckbox('COLLEGE GRADUATE', educationLevels.some(e => e.includes('College Graduate')));
    setCheckbox('POST GRADUATE', educationLevels.some(e => e.includes('Post Graduate')));
    setCheckbox('VOCATIONAL', educationLevels.some(e => e.includes('Vocational')));
    setCheckbox('NOT ATTENDED SCHOOL', educationLevels.some(e => e.includes('Not Attended')));
    
    // Skills
    const skills = Array.isArray(education.skills) ? education.skills : [];
    const skillMap = {
      'FISHING': 'Fishing',
      'ENGINEERING': 'Engineering',
      'BARBER': 'Barber',
      'EVANGELIZATION': 'Evangelization',
      'MILWRIGHT': 'Milwright',
      'TEACHING': 'Teaching',
      'COUNSELING': 'Counseling',
      'COOKING': 'Cooking',
      'CARPENTER': 'Carpenter',
      'MASON': 'Mason',
      'TAILOR': 'Tailor',
      'FARMING': 'Farming',
      'ARTS': 'Arts',
      'PLUMBER': 'Plumber',
      'SAPATERO': 'Sapatero',
      'CHEF/COOK': 'Chef/Cook',
      'DENTAL SKILLS': 'Dental',
      'MEDICAL SKILLS': 'Medical',
      'LEGAL SERVICES SKILLS': 'Legal Services'
    };
    
    Object.keys(skillMap).forEach(pdfField => {
      const skillName = skillMap[pdfField];
      setCheckbox(pdfField, skills.some(s => s && s.toLowerCase().includes(skillName.toLowerCase())));
    });
    
    if (education.skill_other_text) {
      setText('SKILL OTHER TEXT', education.skill_other_text);
      setCheckbox('OTHERS TECHNICAL SKILLS', true);
    }
    
    // Community Service
    const communityServices = Array.isArray(seniorRecord.community_service) ? seniorRecord.community_service : [];
    setCheckbox('MEDICAL COMMUNITY SERVICE', communityServices.some(s => s && s.toLowerCase().includes('medical')));
    setCheckbox('COMMUNITY/ ORGANIZATION LEADER', communityServices.some(s => s && s.toLowerCase().includes('leader')));
    setCheckbox('NEIGHBORHOOD SUPPORT SERVICES', communityServices.some(s => s && s.toLowerCase().includes('neighborhood')));
    setCheckbox('COUNSELING / REFERRAL', communityServices.some(s => s && s.toLowerCase().includes('counseling')));
    setCheckbox('RESOURCE VOLUNTEER', communityServices.some(s => s && s.toLowerCase().includes('volunteer')));
    setCheckbox('DENTAL COMMUNITY SERVICE', communityServices.some(s => s && s.toLowerCase().includes('dental')));
    setCheckbox('LEAGAL SERVICES COMMUNITY SERVICE', communityServices.some(s => s && s.toLowerCase().includes('legal')));
    setCheckbox('COMMUNITY BEAUTIFICATION', communityServices.some(s => s && s.toLowerCase().includes('beautification')));
    setCheckbox('FRIENDLY VISIT', communityServices.some(s => s && s.toLowerCase().includes('friendly')));
    setCheckbox('RELIGIOUS', communityServices.some(s => s && s.toLowerCase().includes('religious')));
    
    if (seniorRecord.community_service_other_text) {
      setText('COMMUNITY SERVICE OTHER TEXT', seniorRecord.community_service_other_text);
      setCheckbox('OTHERS COMMUNITY SERCVICE', true);
      setCheckbox('OTHERS COMMUNITY SERVICE', true);
    }

    // Try to flatten the form, but continue if it fails (some PDFs have broken references)
    try {
      form.flatten();
    } catch (flattenError) {
      console.warn('[Senior PDF] Could not flatten form, saving without flattening:', flattenError.message);
      // Continue without flattening - the form will still be filled
    }    const pdfBytes = await pdfDoc.save();
    const safeLast = (name.last_name || 'Senior').replace(/\s+/g, '-');
    const safeFirst = (name.first_name || 'Record').replace(/\s+/g, '-');
    const filename = `Senior-${safeLast}-${safeFirst}.pdf`;

    res.setHeader('Content-Type', 'application/pdf');
    res.setHeader('Content-Disposition', `inline; filename="${encodeURIComponent(filename)}"`);
    res.setHeader('Content-Length', pdfBytes.length);
    return res.send(pdfBytes);
  } catch (error) {
    console.error('Error generating Senior PDF:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to generate Senior PDF'
    });
  }
};



  
// Analytics: OSCA (Senior Citizens) counts by barangay
exports.getOscaAnalytics = async (req, res) => {
  try {
    let barangayFilter = "";
    const barangayParams = [];
    const su = req.session?.user;
    if (su?.role === "Barangay") {
      const scope = await fetchBarangayScopeForSessionUser(su);
      if (!scope) {
        return res
          .status(403)
          .json({ success: false, message: "Barangay account has no assigned barangay." });
      }
      barangayFilter = " AND barangay = ?";
      barangayParams.push(scope.name);
    }

    const [rows] = await query(
      `SELECT
         barangay AS name,
         COUNT(*) AS oscaCount
       FROM senior_citizens
       WHERE status <> 'Archived'${barangayFilter}
       GROUP BY barangay
       ORDER BY barangay ASC`,
      barangayParams
    );

    const data = (rows || [])
      .filter(r => r.name)
      .map((r, idx) => ({
        id: idx + 1,
        name: r.name,
        oscaCount: Number(r.oscaCount) || 0
      }));

    res.json({ success: true, data });
  } catch (err) {
    console.error('OSCA analytics error:', err);
    res.status(500).json({ success: false, message: 'Failed to load OSCA analytics' });
  }
};

// Get senior citizens data for report generation (with gender information)
exports.getSeniorCitizensForReport = async (req, res) => {
  try {
    const { month, year } = req.query; // Support month and year filters

    const filters = ["status <> 'Archived'"];
    const params = [];

    const su = req.session?.user;
    if (su?.role === "Barangay") {
      const scope = await fetchBarangayScopeForSessionUser(su);
      if (!scope) {
        return res
          .status(403)
          .json({ success: false, message: "Barangay account has no assigned barangay." });
      }
      filters.push("barangay = ?");
      params.push(scope.name);
    }

    // Add date filter if month is provided (for monthly reports)
    if (month) {
      const monthNum = parseInt(month, 10);
      const parsedYear = parseInt(year, 10);
      const yearNum = Number.isNaN(parsedYear) ? new Date().getFullYear() : parsedYear;
      const startDate = new Date(yearNum, monthNum - 1, 1);
      const endDate = new Date(yearNum, monthNum, 0, 23, 59, 59, 999);
      filters.push('created_at >= ?', 'created_at <= ?');
      params.push(startDate, endDate);
    } else if (year) {
      // For annual reports, filter by year
      const yearNum = parseInt(year, 10);
      const startDate = new Date(yearNum, 0, 1);
      const endDate = new Date(yearNum, 11, 31, 23, 59, 59, 999);
      filters.push('created_at >= ?', 'created_at <= ?');
      params.push(startDate, endDate);
    }

    const whereClause = filters.length ? `WHERE ${filters.join(' AND ')}` : '';

    // Keep the response shape compatible with the frontend logic:
    // frontend expects `senior.identifying_information.address.barangay` and `senior.identifying_information.gender`
    const [rows] = await query(
      `SELECT
         id,
         barangay,
         gender
       FROM senior_citizens
       ${whereClause}`,
      params
    );

    const data = (rows || []).map(r => ({
      _id: r.id,
      identifying_information: {
        address: { barangay: r.barangay },
        gender: r.gender
      }
    }));

    res.json({ success: true, data });
  } catch (err) {
    console.error('Error fetching senior citizens for report:', err);
    res.status(500).json({ success: false, message: 'Failed to load senior citizens data' });
  }
};

// Get senior citizens (essential fields) for a specific barangay
exports.getSeniorCitizensByBarangay = async (req, res) => {
  try {
    const { barangay } = req.params;
    const { month, year } = req.query; // Support month and year filters

    if (!barangay) {
      return res.status(400).json({ success: false, message: 'Barangay is required' });
    }

    const su = req.session?.user;
    if (su?.role === "Barangay") {
      const scope = await fetchBarangayScopeForSessionUser(su);
      if (!scope) {
        return res
          .status(403)
          .json({ success: false, message: "Barangay account has no assigned barangay." });
      }
      const requested = decodeURIComponent(String(barangay)).trim();
      if (requested !== String(scope.name).trim()) {
        return res.status(403).json({ success: false, message: "Forbidden" });
      }
    }

    const filters = ['s.barangay = ?', "s.status <> 'Archived'"];
    const params = [barangay];

    // Add date filter if month is provided (for monthly reports)
    if (month) {
      const monthNum = parseInt(month, 10);
      const parsedYear = parseInt(year, 10);
      const yearNum = Number.isNaN(parsedYear) ? new Date().getFullYear() : parsedYear;
      const startDate = new Date(yearNum, monthNum - 1, 1);
      const endDate = new Date(yearNum, monthNum, 0, 23, 59, 59, 999);
      filters.push('s.created_at >= ?', 's.created_at <= ?');
      params.push(startDate, endDate);
    } else if (year) {
      // For annual reports, filter by year
      const yearNum = parseInt(year, 10);
      const startDate = new Date(yearNum, 0, 1);
      const endDate = new Date(yearNum, 11, 31, 23, 59, 59, 999);
      filters.push('s.created_at >= ?', 's.created_at <= ?');
      params.push(startDate, endDate);
    }

    const whereClause = filters.length ? `WHERE ${filters.join(' AND ')}` : '';

    // Pick a "best" contact phone:
    // - prefer `type='primary'`
    // - otherwise take the first non-empty phone
    const [rows] = await query(
      `SELECT
         s.id,
         s.last_name,
         s.first_name,
         s.middle_name,
         s.extension,
         s.age,
         s.gender,
         (
           SELECT sc.phone
           FROM senior_contacts sc
           WHERE sc.senior_id = s.id
             AND sc.phone IS NOT NULL
             AND sc.phone <> ''
           ORDER BY
             CASE WHEN sc.type = 'primary' THEN 0 ELSE 1 END,
             sc.id ASC
           LIMIT 1
         ) AS contact
       FROM senior_citizens s
       ${whereClause}
       ORDER BY s.id DESC`,
      params
    );

    const data = (rows || []).map(s => {
      const fullName = [
        s.last_name,
        s.first_name,
        s.middle_name,
        s.extension
      ]
        .filter(Boolean)
        .join(' ')
        .trim();

      return {
        id: s.id,
        fullName: fullName || 'Unnamed',
        gender: s.gender || 'N/A',
        age: s.age ?? 'N/A',
        contact: s.contact || 'N/A'
      };
    });

    res.json({ success: true, data });
  } catch (err) {
    console.error('Error fetching senior citizens by barangay:', err);
    res.status(500).json({ success: false, message: 'Failed to load barangay senior citizens' });
  }
};

// Analytics: PDAO (PWD) counts and gender breakdown by barangay
exports.getPdaoAnalytics = async (req, res) => {
  try {
    let barangayFilter = "";
    const barangayParams = [];
    const su = req.session?.user;
    if (su?.role === "Barangay") {
      const scope = await fetchBarangayScopeForSessionUser(su);
      if (!scope) {
        return res
          .status(403)
          .json({ success: false, message: "Barangay account has no assigned barangay." });
      }
      barangayFilter = " AND barangay = ?";
      barangayParams.push(scope.name);
    }

    const [rows] = await query(
      `SELECT 
         barangay AS name,
         COUNT(*) AS pdaoCount,
         SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) AS maleCount,
         SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) AS femaleCount
       FROM pwd
       WHERE status <> 'Archived'${barangayFilter}
       GROUP BY barangay
       ORDER BY barangay ASC`,
      barangayParams
    );

    const data = rows
      .filter(r => r.name)
      .map((r, idx) => ({
        id: idx + 1,
        name: r.name,
        pdaoCount: r.pdaoCount,
        maleCount: r.maleCount,
        femaleCount: r.femaleCount
      }));

    res.json({ success: true, data });
  } catch (err) {
    console.error('PDAO analytics error:', err);
    res.status(500).json({ success: false, message: 'Failed to load PDAO analytics' });
  }
};

// Get PWDs (essential fields) for a specific barangay
exports.getPwdsByBarangay = async (req, res) => {
  try {
    const { barangay } = req.params;
    const { month, year } = req.query; // Support month and year filters

    if (!barangay) {
      return res.status(400).json({ success: false, message: 'Barangay is required' });
    }

    const su = req.session?.user;
    if (su?.role === "Barangay") {
      const scope = await fetchBarangayScopeForSessionUser(su);
      if (!scope) {
        return res
          .status(403)
          .json({ success: false, message: "Barangay account has no assigned barangay." });
      }
      const requested = decodeURIComponent(String(barangay)).trim();
      if (requested !== String(scope.name).trim()) {
        return res.status(403).json({ success: false, message: "Forbidden" });
      }
    }

    const filters = ['p.barangay = ?', "p.status <> 'Archived'"];
    const params = [barangay];

    if (month) {
      const monthNum = parseInt(month, 10);
      const yearNum = parseInt(year, 10) || new Date().getFullYear();
      const startDate = new Date(yearNum, monthNum - 1, 1);
      const endDate = new Date(yearNum, monthNum, 0, 23, 59, 59, 999);
      filters.push('p.created_at >= ?', 'p.created_at <= ?');
      params.push(startDate, endDate);
    } else if (year) {
      const yearNum = parseInt(year, 10);
      const startDate = new Date(yearNum, 0, 1);
      const endDate = new Date(yearNum, 11, 31, 23, 59, 59, 999);
      filters.push('p.created_at >= ?', 'p.created_at <= ?');
      params.push(startDate, endDate);
    }

    const whereClause = filters.length ? `WHERE ${filters.join(' AND ')}` : '';

    const [rows] = await query(
      `SELECT 
         p.id,
         p.first_name,
         p.middle_name,
         p.last_name,
         p.age,
         p.gender,
         c.phone AS primary_phone,
         GROUP_CONCAT(DISTINCT d.disability ORDER BY d.disability SEPARATOR ', ') AS disabilities
       FROM pwd p
       LEFT JOIN pwd_contacts c 
         ON c.pwd_id = p.id AND c.type = 'primary'
       LEFT JOIN pwd_disabilities d
         ON d.pwd_id = p.id
       ${whereClause}
       GROUP BY p.id, p.first_name, p.middle_name, p.last_name, p.age, p.gender, c.phone`,
      params
    );

    const data = rows.map((pwd) => {
      const fullName = [
        pwd.last_name,
        pwd.first_name,
        pwd.middle_name
      ]
        .filter(Boolean)
        .join(' ')
        .trim();

      const disabilities = pwd.disabilities || 'N/A';

      return {
        id: pwd.id,
        fullName: fullName || 'Unnamed',
        gender: pwd.gender || 'N/A',
        age: pwd.age ?? 'N/A',
        contact: pwd.primary_phone || 'N/A',
        disability: disabilities
      };
    });

    res.json({ success: true, data });
  } catch (err) {
    console.error('Error fetching PWDs by barangay:', err);
    res.status(500).json({ success: false, message: 'Failed to load barangay PWDs' });
  }
};

// Fetch barangays and their puroks from the database
async function fetchBarangays() {
  // MySQL-backed barangays + puroks (keeps shape expected by EJS: { [barangayName]: string[] })
  const [barangayRows] = await query(`
    SELECT 
      b.id,
      b.barangay,
      GROUP_CONCAT(p.purok ORDER BY p.purok) AS puroks
    FROM barangays b
    LEFT JOIN puroks p ON b.id = p.barangay_id
    GROUP BY b.id, b.barangay
    ORDER BY b.barangay
  `);

  if (!barangayRows || barangayRows.length === 0) {
    return null;
  }

  const barangays = {};
  for (const row of barangayRows) {
    barangays[row.barangay] = row.puroks ? row.puroks.split(',') : [];
  }

  return barangays;
}

/** Barangay role: resolve DB barangay name from users.barangay_id (nullable for other roles). */
async function fetchBarangayScopeForSessionUser(sessionUser) {
  if (!sessionUser || sessionUser.role !== "Barangay") return null;
  const bid = sessionUser.barangay_id;
  if (bid == null || bid === "") return null;
  const [rows] = await query("SELECT id, barangay FROM barangays WHERE id = ? LIMIT 1", [bid]);
  if (!rows || !rows.length) return null;
  return { id: rows[0].id, name: rows[0].barangay };
}

exports.renderRegister = async (req, res) => {
  try {
    const [rows] = await query("SELECT id, barangay FROM barangays ORDER BY barangay ASC");
    res.render("register", { barangayList: rows || [] });
  } catch (err) {
    console.error("renderRegister:", err);
    res.render("register", { barangayList: [] });
  }
};

exports.renderSeniorForm = async (req, res) => {
 try {
    const barangays = await fetchBarangays();

    let whereClause = '';
    const params = [];

    if (req.query.status === 'archived') {
      whereClause = "WHERE status = 'Archived'";
    } else if (req.query.status === 'all') {
      whereClause = '';
    } else {
      whereClause = "WHERE status <> 'Archived'";
    }

    const [rows] = await query(
      `SELECT id FROM senior_citizens ${whereClause} ORDER BY created_at DESC`,
      params
    );

    const seniorCitizens = await Promise.all(
      rows.map(row => getSeniorByIdWithRelations(row.id))
    );

    if (!barangays) {
      return res.status(404).send('No barangays found');
    }

    res.render('staff/staff_senior', {
      barangays: barangays || {},
      seniorCitizens: seniorCitizens || {},
      user: req.session?.user || null
    });
  } catch (err) {
    console.error('Error fetching barangays or seniors:', err);
    res.status(500).send('Internal Server Error');
  }
  };

exports.updateSenior = async (req, res) => {
  try {
    const { residentId, ...updateData } = req.body;
    
    if (!residentId) {
      return res.status(400).json({
        success: false,
        error: "Resident ID is required"
      });
    }

    const id = parseInt(residentId, 10);
    if (Number.isNaN(id)) {
      return res.status(400).json({
        success: false,
        error: "Invalid Resident ID"
      });
    }

    // Snapshot before state for edit log
    const before = await getSeniorByIdWithRelations(id);
    if (!before) {
      return res.status(404).json({
        success: false,
        error: "Senior citizen not found"
      });
    }

    const fields = [];
    const params = [];

    if (updateData.first_name) {
      fields.push("first_name = ?");
      params.push(updateData.first_name);
    }
    if (updateData.middle_name !== undefined) {
      fields.push("middle_name = ?");
      params.push(updateData.middle_name || null);
    }
    if (updateData.last_name) {
      fields.push("last_name = ?");
      params.push(updateData.last_name);
    }
    if (updateData.barangay) {
      fields.push("barangay = ?");
      params.push(updateData.barangay);
    }
    if (updateData.purok) {
      fields.push("purok = ?");
      params.push(updateData.purok);
    }
    if (updateData.gender) {
      fields.push("gender = ?");
      params.push(updateData.gender);
    }
    if (updateData.birthday) {
      const dob = new Date(updateData.birthday);
      if (!Number.isNaN(dob.getTime())) {
        fields.push("date_of_birth = ?");
        params.push(dob);
      }
    }
    if (updateData.age) {
      fields.push("age = ?");
      params.push(parseInt(updateData.age, 10));
    }
    if (updateData.marital_status) {
      fields.push("marital_status = ?");
      params.push(updateData.marital_status);
    }
    if (updateData.osca_id) {
      fields.push("osca_id_number = ?");
      params.push(updateData.osca_id);
    }
    if (updateData.gsis_sss) {
      fields.push("gsis_sss = ?");
      params.push(updateData.gsis_sss);
    }
    if (updateData.philhealth) {
      fields.push("philhealth = ?");
      params.push(updateData.philhealth);
    }
    if (updateData.tin) {
      fields.push("tin = ?");
      params.push(updateData.tin);
    }
    if (updateData.spouse_name !== undefined) {
      fields.push("spouse_name = ?");
      params.push(updateData.spouse_name || null);
    }

    if (updateData.father_name) {
      const fatherParts = updateData.father_name.trim().split(' ');
      if (fatherParts.length >= 2) {
        fields.push("father_first_name = ?");
        params.push(fatherParts[0]);
        fields.push("father_last_name = ?");
        params.push(fatherParts[fatherParts.length - 1]);
        if (fatherParts.length > 2) {
          fields.push("father_middle_name = ?");
          params.push(fatherParts.slice(1, -1).join(' '));
        }
      }
    }

    if (updateData.mother_name) {
      const motherParts = updateData.mother_name.trim().split(' ');
      if (motherParts.length >= 2) {
        fields.push("mother_first_name = ?");
        params.push(motherParts[0]);
        fields.push("mother_last_name = ?");
        params.push(motherParts[motherParts.length - 1]);
        if (motherParts.length > 2) {
          fields.push("mother_middle_name = ?");
          params.push(motherParts.slice(1, -1).join(' '));
        }
      }
    }

    if (updateData.community_service_other_text !== undefined) {
      fields.push("community_service_other_text = ?");
      params.push(updateData.community_service_other_text || null);
    }

    const editorEmail = req.session?.user?.email || updateData.edited_by || 'Unknown';
    const editTimestamp = updateData.edited_at ? new Date(updateData.edited_at) : new Date();

    fields.push("edited_by = ?", "edited_at = ?");
    params.push(editorEmail, editTimestamp);

    params.push(id);

    if (fields.length > 0) {
      const [result] = await query(
        `UPDATE senior_citizens SET ${fields.join(", ")} WHERE id = ?`,
        params
      );

      if (result.affectedRows === 0) {
        return res.status(404).json({
          success: false,
          error: "Senior citizen not found"
        });
      }
    }

    if (updateData.contacts && Array.isArray(updateData.contacts)) {
      await query("DELETE FROM senior_contacts WHERE senior_id = ?", [id]);
      for (const contact of updateData.contacts) {
        if (!contact || !contact.name) continue;
        await query(
          `INSERT INTO senior_contacts (senior_id, type, name, relationship, phone, email)
           VALUES (?, ?, ?, ?, ?, ?)`,
          [
            id,
            contact.type || 'primary',
            contact.name,
            contact.relationship || null,
            contact.phone || null,
            contact.email || null
          ]
        );
      }
    }

    if (updateData.family_composition?.children && Array.isArray(updateData.family_composition.children)) {
      await query("DELETE FROM senior_children WHERE senior_id = ?", [id]);
      for (const child of updateData.family_composition.children) {
        if (!child || !child.full_name) continue;
        await query(
          `INSERT INTO senior_children (senior_id, full_name, occupation, income, age, working_status)
           VALUES (?, ?, ?, ?, ?, ?)`,
          [
            id,
            child.full_name,
            child.occupation || null,
            child.income || null,
            child.age ? parseInt(child.age, 10) : null,
            child.working_status || null
          ]
        );
      }
    }

    if (updateData.education_hr_profile?.educational_attainment) {
      await query("DELETE FROM senior_education WHERE senior_id = ?", [id]);
      const eduArr = Array.isArray(updateData.education_hr_profile.educational_attainment)
        ? updateData.education_hr_profile.educational_attainment
        : [updateData.education_hr_profile.educational_attainment];
      for (const e of eduArr) {
        if (!e) continue;
        await query(
          `INSERT INTO senior_education (senior_id, educational_attainment)
           VALUES (?, ?)`,
          [id, e]
        );
      }
    }

    if (updateData.education_hr_profile?.skills) {
      await query("DELETE FROM senior_skills WHERE senior_id = ?", [id]);
      const skillsArr = Array.isArray(updateData.education_hr_profile.skills)
        ? updateData.education_hr_profile.skills
        : [updateData.education_hr_profile.skills];
      for (const s of skillsArr) {
        if (!s) continue;
        await query(
          `INSERT INTO senior_skills (senior_id, skill)
           VALUES (?, ?)`,
          [id, s]
        );
      }
    }

    if (updateData.community_service) {
      await query("DELETE FROM senior_community_services WHERE senior_id = ?", [id]);
      const services = Array.isArray(updateData.community_service)
        ? updateData.community_service
        : [updateData.community_service];
      for (const svc of services) {
        if (!svc) continue;
        await query(
          `INSERT INTO senior_community_services (senior_id, service)
           VALUES (?, ?)`,
          [id, svc]
        );
      }
    }

    // Build edit log changes by comparing before/after snapshots
    const after = await getSeniorByIdWithRelations(id);

    const normalizeDate = (val) => {
      if (!val) return null;
      const d = new Date(val);
      if (Number.isNaN(d.getTime())) return null;
      const y = d.getFullYear();
      const m = `${d.getMonth() + 1}`.padStart(2, '0');
      const day = `${d.getDate()}`.padStart(2, '0');
      return `${y}-${m}-${day}`;
    };

    const extractForLog = (s) => ({
      first_name: s?.identifying_information?.name?.first_name ?? null,
      middle_name: s?.identifying_information?.name?.middle_name ?? null,
      last_name: s?.identifying_information?.name?.last_name ?? null,
      barangay: s?.identifying_information?.address?.barangay ?? null,
      purok: s?.identifying_information?.address?.purok ?? null,
      date_of_birth: normalizeDate(s?.identifying_information?.date_of_birth),
      age: s?.identifying_information?.age ?? null,
      gender: s?.identifying_information?.gender ?? null,
      marital_status: s?.identifying_information?.marital_status ?? null,
      place_of_birth: s?.identifying_information?.place_of_birth ?? null,
      osca_id_number: s?.identifying_information?.osca_id_number ?? null,
      gsis_sss: s?.identifying_information?.gsis_sss ?? null,
      philhealth: s?.identifying_information?.philhealth ?? null,
      tin: s?.identifying_information?.tin ?? null,
      spouse_name: s?.family_composition?.spouse?.name ?? null,
      father_name: s?.family_composition?.father
        ? [s.family_composition.father.first_name, s.family_composition.father.middle_name, s.family_composition.father.last_name]
            .filter(Boolean).join(" ")
        : null,
      mother_name: s?.family_composition?.mother
        ? [s.family_composition.mother.first_name, s.family_composition.mother.middle_name, s.family_composition.mother.last_name]
            .filter(Boolean).join(" ")
        : null,
      contacts: s?.identifying_information?.contacts ?? [],
      educational_attainment: s?.education_hr_profile?.educational_attainment ?? [],
      skills: s?.education_hr_profile?.skills ?? [],
      community_service: s?.community_service ?? [],
      community_service_other_text: s?.community_service_other_text ?? null
    });

    const beforeView = extractForLog(before);
    const afterView = extractForLog(after);

    const serialize = (val) => {
      if (val === null || val === undefined) return '';
      if (typeof val === 'string' || typeof val === 'number' || typeof val === 'boolean') {
        return String(val);
      }
      return JSON.stringify(val);
    };

    const changeEntries = [];
    for (const [field, oldVal] of Object.entries(beforeView)) {
      const newVal = afterView[field];
      if (serialize(oldVal) !== serialize(newVal)) {
        changeEntries.push({
          field,
          old_value: serialize(oldVal),
          new_value: serialize(newVal)
        });
      }
    }

    for (const change of changeEntries) {
      await query(
        `INSERT INTO senior_edit_logs (senior_id, field, old_value, new_value, edited_by, edited_at)
         VALUES (?, ?, ?, ?, ?, ?)`,
        [id, change.field, change.old_value, change.new_value, editorEmail, editTimestamp]
      );
    }

    const updatedSenior = {
      ...after,
      edit_log: {
        edited_by: editorEmail,
        edited_at: editTimestamp,
        changes: changeEntries
      }
    };

    res.status(200).json({
      success: true,
      message: "Senior citizen updated successfully",
      data: updatedSenior
    });

  } catch (error) {
    console.error('Error updating senior citizen:', error);
    res.status(500).json({
      success: false,
      error: error.message || "Internal server error"
    });
  }
};

  exports.renderPWDForm = async (req, res) => {
 try {
    const barangays = await fetchBarangays();
    let statusFilter = '';
    const params = [];

    if (req.query.status === 'archived') {
      statusFilter = "WHERE status = 'Archived'";
    } else if (req.query.status === 'all') {
      statusFilter = '';
    } else {
      statusFilter = "WHERE status <> 'Archived'";
    }

    const [rows] = await query(
      `SELECT * FROM pwd ${statusFilter} ORDER BY created_at DESC`,
      params
    );

    const pwds = await Promise.all(rows.map(r => getPwdByIdWithRelations(r.id)));

    res.render('staff/staff_pwd', {
      barangays: barangays || {},
      pwds: pwds || [],
      user: req.session?.user || null
    });
  } catch (err) {
    console.error('Error fetching barangays or PWDs:', err);
    res.status(500).send('Internal Server Error');
  }
  };

  exports.renderAddSenior = async (req, res) => {
 try {
    const barangays = await fetchBarangays();
    
    if (!barangays) {
      return res.status(404).send('No barangays found');
    }
  
    // Pass the barangays data to the EJS template
    
    res.render('staff/staff_addSenior', {
      barangays: barangays || {},
    });
  } catch (err) {
    console.error('Error fetching barangays:', err);
    res.status(500).send('Internal Server Error');
  }
  };

   exports.renderAddPWD = async (req, res) => {
 try {
    const barangays = await fetchBarangays();
    // if (!barangays) {
    //   return res.status(404).send('No barangays found');
    // }
  
    // Pass the barangays data to the EJS template
   
    res.render('staff/staff_addPwd', {
      barangays: barangays || {}
    });
  } catch (err) {
    console.error('Error fetching barangays:', err);
    res.status(500).send('Internal Server Error');
  }
  };

   exports.renderSuperAdminUser = async (req, res) => {
try {
   const [users] = await query(
     "SELECT id, name, email, role, status, barangay_id FROM users ORDER BY id ASC"
   );
   const [barangayRows] = await query(
     "SELECT id, barangay FROM barangays ORDER BY barangay ASC"
   );
   const barangayList = (barangayRows || []).map((row) => ({
     id: row.id,
     barangay: row.barangay
   }));
    if (!users || users.length === 0) {
      console.log('No users found');
    }
  
    res.render('superadmin/superadmin_users', {
      users: users || [],
      barangayList: barangayList || []
    });
  } catch (err) {
    console.error(err);
    res.status(500).send('Internal Server Error');
  }
  };

   exports.renderSuperAdminIndex = async (req, res) => {
 try {
    const barangays = await fetchBarangays();
    
    // if (!barangays) {
    //   return res.status(404).send('No barangays found');
    // }
  
    // Pass the barangays data to the EJS template
   
    res.render('superadmin/admin_super_admin', {
      barangays: barangays || {}
    });
  } catch (err) {
    console.error(err);
    res.status(500).send('Internal Server Error');
  }
  };

  
exports.renderSuperAdminIndex = async (req, res) => {
 try {
    const barangays = await fetchBarangays();
    
    if (!barangays) {
      return res.status(404).send('No barangays found');
    }
  
    // Pass the barangays data to the EJS template
   
    res.render('superadmin/admin_super_admin', {
      barangays: barangays || {}
    });
  } catch (err) {
    console.error(err);
    res.status(500).send('Internal Server Error');
  }
  };


// Send SMS via external API
exports.sendSms = async (req, res) => {
  const sentBy = req.user ? (req.user.email || req.user.name || 'Unknown') : 'Unknown';

  try {
    const { recipients, message } = req.body;

    if (!process.env.API_TOKEN) {
      return res.status(500).json({ success: false, message: 'API_TOKEN not configured on server' });
    }

    if (!message || !recipients || !Array.isArray(recipients) || recipients.length === 0) {
      return res.status(400).json({ success: false, message: 'Recipients and message are required' });
    }

    const apiUrl = 'https://sms.iprogtech.com/api/v1/sms_messages';

    const results = [];
    const historyRecords = [];

    // Send messages sequentially to avoid rate issues; can be parallelized if needed
    for (const r of recipients) {
      const phone = r.phone || '';
      const name = r.name || '';
      const recordId = r.record_id || '';
      const recipientType = r.recipient_type || 'PWD';

      if (!phone) {
        results.push({ phone, name, status: 'skipped', reason: 'no phone' });
        // Still save to history even if skipped
        if (recordId) {
          historyRecords.push({
            recipient_type: recipientType,
            record_id: recordId,
            phone_number: phone,
            first_name: r.first_name || '',
            middle_name: r.middle_name || '',
            last_name: r.last_name || '',
            barangay: r.barangay || '',
            purok: r.purok || '',
            message: message,
            status: 'skipped',
            sent_by: sentBy,
            received: false
          });
        }
        continue;
      }

      const body = {
        api_token: process.env.API_TOKEN,
        phone_number: phone,
        message: message
      };

      let smsStatus = 'error';
      try {
        const resp = await axios.post(apiUrl, body, {
          headers: { 'Content-Type': 'application/json' },
          timeout: 15000
        });

        // Log full response for debugging
        console.log(`SMS API response for ${phone}: status=${resp.status}`);
        console.log('headers:', resp.headers);
        console.log('data:', resp.data);

        smsStatus = 'sent';
        results.push({ phone, name, status: 'sent', response: resp.data });
      } catch (err) {
        // Log detailed error
        if (err.response) {
          console.error(`SMS send error for ${phone}: status=${err.response.status}`, err.response.data);
        } else {
          console.error(`SMS send error for ${phone}:`, err.message);
        }
        results.push({ phone, name, status: 'error', error: err && err.response ? err.response.data : err.message });
      }

      // Save to history
      if (recordId) {
        historyRecords.push({
          recipient_type: recipientType,
          record_id: recordId,
          phone_number: phone,
          first_name: r.first_name || '',
          middle_name: r.middle_name || '',
          last_name: r.last_name || '',
          barangay: r.barangay || '',
          purok: r.purok || '',
          message: message,
          status: smsStatus,
          sent_by: sentBy,
          received: false
        });
      }
    }

    // Save all history records to database
    if (historyRecords.length > 0) {
      try {
        const values = historyRecords.map(r => ([
          r.recipient_type,
          r.record_id,
          r.phone_number,
          r.first_name,
          r.middle_name || '',
          r.last_name,
          r.barangay,
          r.purok,
          r.message,
          r.status,
          r.sent_by,
          r.received ? 1 : 0
        ]));

        await query(
          `INSERT INTO sms_history (
            recipient_type,
            record_id,
            phone_number,
            first_name,
            middle_name,
            last_name,
            barangay,
            purok,
            message,
            status,
            sent_by,
            received
          ) VALUES ?`,
          [values]
        );
      
      } catch (historyErr) {
        console.error('Error saving SMS history:', historyErr);
        // Don't fail the request if history save fails
      }
    }

    // If any send failed, return 503 so frontend can show service-down message
    const allSent = results.every(r => r.status === 'sent');
    if (allSent) {
      return res.status(200).json({ success: true, results });
    } else {
      return res.status(503).json({ success: false, message: 'SMS service is down at the moment', results });
    }
  } catch (err) {
    console.error('sendSms error:', err);
    res.status(500).json({ success: false, message: 'Internal server error' });
  }
};

// Get SMS History
exports.getSmsHistory = async (req, res) => {
  try {
    const { recipient_type, limit = 100, page = 1 } = req.query;
    
    const limitInt = Math.max(1, parseInt(limit, 10) || 100);
    const pageInt = Math.max(1, parseInt(page, 10) || 1);
    const offset = (pageInt - 1) * limitInt;

    const validRecipientTypes = new Set(['PWD', 'Youth', 'Senior']);
    const hasRecipientType = recipient_type && validRecipientTypes.has(recipient_type);

    const whereClause = hasRecipientType ? 'WHERE recipient_type = ?' : '';
    const params = hasRecipientType ? [recipient_type] : [];

    const historySql = `
      SELECT
        id,
        recipient_type,
        record_id,
        phone_number,
        first_name,
        middle_name,
        last_name,
        barangay,
        purok,
        message,
        status,
        sent_by,
        sent_at,
        received
      FROM sms_history
      ${whereClause}
      ORDER BY sent_at DESC
      LIMIT ? OFFSET ?
    `;

    const [rows] = await query(historySql, [...params, limitInt, offset]);

    // Match EJS expectations: it uses record._id (not id)
    const history = rows.map(r => ({
      ...r,
      _id: r.id,
      received: Boolean(r.received),
    }));

    // Remove id to avoid confusion in templates
    history.forEach(r => { delete r.id; });

    const countSql = `SELECT COUNT(*) as total FROM sms_history ${whereClause}`;
    const [countRows] = await query(countSql, params);
    const total = countRows?.[0]?.total ?? 0;

    res.json({
      success: true,
      data: history,
      total: total,
      page: pageInt,
      limit: limitInt,
      totalPages: Math.ceil(total / limitInt)
    });
  } catch (err) {
    console.error('getSmsHistory error:', err);
    res.status(500).json({ success: false, message: 'Internal server error' });
  }
};

exports.getSilayBoundary = (req, res) => {
  try {
    const filePath = path.join(__dirname, "..", "files", "assets", "data", "Silay City.geojson");
    const geojson = JSON.parse(fs.readFileSync(filePath, "utf8"));
    res.json(geojson);
  } catch (err) {
    console.error(err);
    res.status(500).send("Failed to load Silay City boundary");
  }
};



// Debug endpoint to see what's in the database


// Get PWD count data by barangay for the map
exports.getAllPwds = async (req, res) => {
  try {
    const { month, year } = req.query; // Support month and year filters

    const filters = [];
    const params = [];

    const su = req.session?.user;
    if (su?.role === "Barangay") {
      const scope = await fetchBarangayScopeForSessionUser(su);
      if (!scope) {
        return res
          .status(403)
          .json({ success: false, error: "Barangay account has no assigned barangay." });
      }
      filters.push("barangay = ?");
      params.push(scope.name);
    }

    if (req.query.status !== 'all') {
      filters.push("status <> 'Archived'");
    }

    if (month) {
      const monthNum = parseInt(month, 10);
      const yearNum = parseInt(year, 10) || new Date().getFullYear();
      const startDate = new Date(yearNum, monthNum - 1, 1);
      const endDate = new Date(yearNum, monthNum, 0, 23, 59, 59, 999);
      filters.push('created_at >= ?', 'created_at <= ?');
      params.push(startDate, endDate);
    } else if (year) {
      const yearNum = parseInt(year, 10);
      const startDate = new Date(yearNum, 0, 1);
      const endDate = new Date(yearNum, 11, 31, 23, 59, 59, 999);
      filters.push('created_at >= ?', 'created_at <= ?');
      params.push(startDate, endDate);
    }

    const whereClause = filters.length ? `WHERE ${filters.join(' AND ')}` : '';

    const [rows] = await query(
      `SELECT * FROM pwd ${whereClause} ORDER BY created_at DESC`,
      params
    );

    const pwds = await Promise.all(rows.map(r => getPwdByIdWithRelations(r.id)));

    res.json({
      success: true,
      pwds: pwds
    });
  } catch (err) {
    console.error('Error fetching PWD data:', err);
    res.status(500).json({
      success: false,
      error: 'Failed to fetch PWD data'
    });
  }
};



exports.getPwdMapData = async (req, res) => {
  try {
 
    
    // First, let's see what barangay names are actually in the database
    const [allPwds] = await query(
      "SELECT DISTINCT barangay FROM pwd WHERE COALESCE(status,'Active') <> 'Archived' ORDER BY barangay"
    );
   
    
    // Get PWD count by barangay
    const [pwdCounts] = await query(`
      SELECT
        barangay AS _id,
        COUNT(*) AS pwdCount,
        SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) AS maleCount,
        SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) AS femaleCount
      FROM pwd
      WHERE COALESCE(status,'Active') <> 'Archived'
      GROUP BY barangay
      ORDER BY barangay
    `);

    // Get disability counts by barangay
    const [disabilityRows] = await query(`
      SELECT
        p.barangay AS _barangay,
        d.disability AS type,
        COUNT(*) AS count
      FROM pwd p
      INNER JOIN pwd_disabilities d ON d.pwd_id = p.id
      WHERE COALESCE(p.status,'Active') <> 'Archived'
        AND d.disability IS NOT NULL
        AND d.disability <> ''
      GROUP BY p.barangay, d.disability
      ORDER BY p.barangay, count DESC
    `);

    // Convert rows into the same structure the old Mongoose pipeline returned:
    // [{ _id: <barangay>, disabilities: [{ type, count }, ...] }, ...]
    const disabilityCountsMap = disabilityRows.reduce((acc, row) => {
      const barangayName = row._barangay;
      if (!acc[barangayName]) {
        acc[barangayName] = { _id: barangayName, disabilities: [] };
      }
      acc[barangayName].disabilities.push({
        type: row.type,
        count: row.count
      });
      return acc;
    }, {});
    const disabilityCounts = Object.values(disabilityCountsMap);

   

    // Define barangay coordinates and other data - Updated to match database names
const barangayData = [
  { name: "Alacaygan", lat: 10.823437, lon: 123.060737, population: 0 },
  { name: "Alicante", lat: 10.893360, lon: 123.030686, population: 0 },
  { name: "Batea", lat: 10.908044, lon: 122.990278, population: 0 },
  { name: "Canlusong", lat: 10.747461, lon: 123.166663, population: 0 },
  { name: "Consing", lat: 10.815041, lon: 123.099954, population: 0 },
  { name: "Cudangdang", lat: 10.863899, lon: 123.031139, population: 0 },
  { name: "Damgo", lat: 10.879931, lon: 123.016101, population: 0 },
  { name: "Gahit", lat: 10.891601, lon: 122.963708, population: 0 },
  { name: "Latasan", lat: 10.858859, lon: 122.951222, population: 0 },
  { name: "Madalag", lat: 10.898624, lon: 122.981409, population: 0 },
  { name: "Manta-angan", lat: 10.913613, lon: 123.002089, population: 0 },
  { name: "Nanca", lat: 10.843578, lon: 123.036181, population: 0 },
  { name: "Pasil", lat: 10.920744, lon: 123.035374, population: 0 },
  { name: "Barangay 1 (Poblacion I)", lat: 10.876753, lon: 122.977026, population: 0 },
  { name: "Barangay 2 (Poblacion II)", lat: 10.874002, lon: 122.977553, population: 0 },
  { name: "Barangay 3 (Poblacion III)", lat: 10.880531, lon: 122.980867, population: 0 },
  { name: "Santo Niño", lat: 10.863950, lon: 122.978790, population: 0 },
  { name: "San Isidro", lat: 10.782063, lon: 123.135637, population: 0 },
  { name: "San Jose", lat: 10.857730, lon: 122.980619, population: 0 },
  { name: "Tabigue", lat: 10.885875, lon: 122.991218, population: 0 },
  { name: "Tanza", lat: 10.837426, lon: 123.024104, population: 0 },
  { name: "Tomongtong", lat: 10.892834, lon: 122.955675, population: 0 },
  { name: "Tuburan", lat: 10.872425, lon: 122.958344, population: 0 }
];
    // Helper function to find matching barangay data
    const findMatchingData = (barangayName, dataArray) => {
      // Try exact match first
      let match = dataArray.find(item => item._id === barangayName);
      if (match) return match;
      
      // Try case-insensitive match
      match = dataArray.find(item => 
        item._id && item._id.toLowerCase() === barangayName.toLowerCase()
      );
      if (match) return match;
      
      // Try partial match for common variations
      match = dataArray.find(item => {
        if (!item._id) return false;
        const dbName = item._id.toLowerCase();
        const mapName = barangayName.toLowerCase();
        
        // Check for common variations
        return dbName.includes(mapName) || 
               mapName.includes(dbName) ||
               dbName.includes('hawaiian') && mapName.includes('hawaiian') ||
               dbName.includes('poblacion') && mapName.includes('poblacion');
      });
      
      return match || null;
    };

    // Merge database counts with barangay data
    const result = barangayData.map(barangay => {
      // Get PWD count data
      let countData = findMatchingData(barangay.name, pwdCounts);
      let pwdCount = 0;
      let maleCount = 0;
      let femaleCount = 0;
      
      if (countData) {
        pwdCount = countData.pwdCount;
        maleCount = countData.maleCount;
        femaleCount = countData.femaleCount;
      }
      
      // Get disability data
      let disabilityData = findMatchingData(barangay.name, disabilityCounts);
      let disabilities = [];
      
      if (disabilityData && disabilityData.disabilities) {
        // Filter out disabilities with count 0 and sort by count descending
        disabilities = disabilityData.disabilities
          .filter(d => d.count > 0)
          .sort((a, b) => b.count - a.count);
      }
      
     
      if (disabilities.length > 0) {
        console.log(`   Disabilities: ${disabilities.map(d => `${d.type} (${d.count})`).join(', ')}`);
      }
      
      return {
        ...barangay,
        pwdCount: pwdCount,
        maleCount: maleCount,
        femaleCount: femaleCount,
        disabilities: disabilities
      };
    });

  
    res.json({ success: true, data: result });
  } catch (err) {
    console.error('❌ Error fetching PWD map data:', err);
    res.status(500).json({ success: false, message: 'Failed to load PWD map data' });
  }
};




// Get senior count data by barangay for the map
exports.getSeniorMapData = async (req, res) => {
  try {
  
    
    // First, let's see what barangay names are actually in the database
    const [allSeniors] = await query(
      "SELECT DISTINCT barangay FROM senior_citizens WHERE COALESCE(status,'Active') <> 'Archived' ORDER BY barangay"
    );
  
    
    // Get senior count by barangay with gender breakdown
    const [seniorCounts] = await query(`
      SELECT
        barangay AS _id,
        COUNT(*) AS seniorCount,
        SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) AS maleCount,
        SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) AS femaleCount
      FROM senior_citizens
      WHERE COALESCE(status,'Active') <> 'Archived'
      GROUP BY barangay
      ORDER BY barangay
    `);



    // Barangay marker coordinates.
    // Keep this aligned with the barangay strings stored in MySQL (`senior_citizens.barangay`)
    // so the merge step can find matching counts.
    // (We use population: 0; the front-end will show "N/A" for percentages.)
    const barangayData = [
      { name: "Alacaygan", lat: 10.823437, lon: 123.060737, population: 0 },
      { name: "Alicante", lat: 10.893360, lon: 123.030686, population: 0 },
      { name: "Batea", lat: 10.908044, lon: 122.990278, population: 0 },
      { name: "Canlusong", lat: 10.747461, lon: 123.166663, population: 0 },
      { name: "Consing", lat: 10.815041, lon: 123.099954, population: 0 },
      { name: "Cudangdang", lat: 10.863899, lon: 123.031139, population: 0 },
      { name: "Damgo", lat: 10.879931, lon: 123.016101, population: 0 },
      { name: "Gahit", lat: 10.891601, lon: 122.963708, population: 0 },
      { name: "Latasan", lat: 10.858859, lon: 122.951222, population: 0 },
      { name: "Madalag", lat: 10.898624, lon: 122.981409, population: 0 },
      { name: "Manta-angan", lat: 10.913613, lon: 123.002089, population: 0 },
      { name: "Nanca", lat: 10.843578, lon: 123.036181, population: 0 },
      { name: "Pasil", lat: 10.920744, lon: 123.035374, population: 0 },
      { name: "Barangay 1 (Poblacion I)", lat: 10.876753, lon: 122.977026, population: 0 },
      { name: "Barangay 2 (Poblacion II)", lat: 10.874002, lon: 122.977553, population: 0 },
      { name: "Barangay 3 (Poblacion III)", lat: 10.880531, lon: 122.980867, population: 0 },
      { name: "Santo Niño", lat: 10.863950, lon: 122.978790, population: 0 },
      { name: "San Isidro", lat: 10.782063, lon: 123.135637, population: 0 },
      { name: "San Jose", lat: 10.857730, lon: 122.980619, population: 0 },
      { name: "Tabigue", lat: 10.885875, lon: 122.991218, population: 0 },
      { name: "Tanza", lat: 10.837426, lon: 123.024104, population: 0 },
      { name: "Tomongtong", lat: 10.892834, lon: 122.955675, population: 0 },
      { name: "Tuburan", lat: 10.872425, lon: 122.958344, population: 0 }
    ];

    // Merge database counts with barangay data
    const result = barangayData.map(barangay => {
      // Try exact match first
      let countData = seniorCounts.find(item => item._id === barangay.name);
      let seniorCount = 0;
      let maleCount = 0;
      let femaleCount = 0;
      
      if (countData) {
        seniorCount = countData.seniorCount;
        maleCount = countData.maleCount || 0;
        femaleCount = countData.femaleCount || 0;
      } else {
        // Try case-insensitive match
        countData = seniorCounts.find(item => 
          item._id && item._id.toLowerCase() === barangay.name.toLowerCase()
        );
        if (countData) {
          seniorCount = countData.seniorCount;
          maleCount = countData.maleCount || 0;
          femaleCount = countData.femaleCount || 0;
        } else {
          // Try partial match for common variations
          countData = seniorCounts.find(item => {
            if (!item._id) return false;
            const dbName = item._id.toLowerCase();
            const mapName = barangay.name.toLowerCase();
            
            // Check for common variations
            return dbName.includes(mapName) || 
                   mapName.includes(dbName) ||
                   dbName.includes('hawaiian') && mapName.includes('hawaiian') ||
                   dbName.includes('poblacion') && mapName.includes('poblacion');
          });
          if (countData) {
            seniorCount = countData.seniorCount;
            maleCount = countData.maleCount || 0;
            femaleCount = countData.femaleCount || 0;
          }
        }
      }
      
      
      return {
        ...barangay,
        seniorCount: seniorCount,
        maleCount: maleCount,
        femaleCount: femaleCount
      };
    });

 
    res.json({ success: true, data: result });
  } catch (err) {
    console.error('❌ Error fetching senior map data:', err);
    res.status(500).json({ success: false, message: 'Failed to load senior map data' });
  }
};


exports.editUserStatus = async (req, res) => {
  try {
    const { id } = req.body;

    if (!id) {
      return res.status(400).json({ message: 'User id is required' });
    }

    const [rows] = await query("SELECT status FROM users WHERE id = ? LIMIT 1", [id]);
    if (rows.length === 0) {
      return res.status(404).json({ message: 'User not found' });
    }

    const currentStatus = rows[0].status;
    const nextStatus = currentStatus === 'Inactive' ? 'Active' : 'Inactive';
    await query("UPDATE users SET status = ? WHERE id = ?", [nextStatus, id]);
    return res.redirect('/superadmin-users');
  } catch (err) {
    console.error(err);
    return res.status(500).json({ message: 'Internal Server Error' });
  }
};

exports.updateUser = async (req, res) => {
  try {
    const { id, name, email, role, status, password, confirm_password, barangay_id } = req.body;
    const normalizedPassword = (password || '').trim();
    const normalizedConfirmPassword = (confirm_password || '').trim();

    if (!id) {
      return res.status(400).json({ message: 'User id is required' });
    }

    const fields = [];
    const params = [];

    if (name) {
      fields.push("name = ?");
      params.push(name);
    }
    if (email) {
      fields.push("email = ?");
      params.push(email);
    }
    if (role) {
      fields.push("role = ?");
      params.push(role);
      if (role === 'Barangay') {
        fields.push("barangay_id = ?");
        params.push(barangay_id || null);
      } else {
        fields.push("barangay_id = ?");
        params.push(null);
      }
    }
    if (status) {
      fields.push("status = ?");
      params.push(status);
    }

    if (normalizedPassword || normalizedConfirmPassword) {
      if (!normalizedPassword || !normalizedConfirmPassword) {
        return res.status(400).json({ message: 'Both password and confirm_password are required' });
      }
      if (normalizedPassword.length < 6) {
        return res.status(400).json({ message: 'Password must be at least 6 characters' });
      }
      if (normalizedPassword !== normalizedConfirmPassword) {
        return res.status(400).json({ message: 'Passwords do not match' });
      }
      const hashedPassword = await bcrypt.hash(normalizedPassword, saltrounds);
      fields.push("password = ?");
      params.push(hashedPassword);
    }

    if (fields.length === 0) {
      return res.redirect('/superadmin-users');
    }

    params.push(id);

    try {
      const [result] = await query(
        `UPDATE users SET ${fields.join(", ")} WHERE id = ?`,
        params
      );
      if (result.affectedRows === 0) {
        return res.status(404).json({ message: 'User not found' });
      }
      return res.redirect('/superadmin-users');
    } catch (err) {
      console.error(err);
      return res.status(500).json({ message: 'Internal Server Error' });
    }
  } catch (err) {
    console.error(err);
    return res.status(500).json({ message: 'Internal Server Error' });
  }
};

// Render superadmin page with barangays data
exports.renderSuperAdmin = async (req, res) => {
  try {
    const [barangayRows] = await query(`
      SELECT
        b.id,
        b.barangay,
        GROUP_CONCAT(p.purok ORDER BY p.purok) AS puroks
      FROM barangays b
      LEFT JOIN puroks p ON b.id = p.barangay_id
      GROUP BY b.id, b.barangay
      ORDER BY b.barangay
    `);

    const barangays = {};
    const barangayList = (barangayRows || []).map((row) => {
      const puroks = row.puroks ? row.puroks.split(',') : [];
      barangays[row.barangay] = puroks;
      return {
        id: row.id,
        barangay: row.barangay,
        puroks
      };
    });

    res.render('superadmin/admin_super_admin', {
      barangays: barangays || {},
      barangayList: barangayList || []
    });
  } catch (err) {
    console.error('Error fetching barangays:', err);
    res.status(500).send('Internal Server Error');
  }
};

// API: Get all barangays
exports.getBarangays = async (req, res) => {
  try {
    // Get all barangays with their puroks using MySQL
    const [barangayRows] = await query(`
      SELECT 
        b.id,
        b.barangay,
        GROUP_CONCAT(p.purok ORDER BY p.purok) as puroks
      FROM barangays b
      LEFT JOIN puroks p ON b.id = p.barangay_id
      GROUP BY b.id, b.barangay
      ORDER BY b.barangay
    `);

    const barangays = {};
    const barangayList = barangayRows.map(row => {
      const puroksArray = row.puroks ? row.puroks.split(',') : [];
      const barangayObj = {
        id: row.id,
        barangay: row.barangay,
        puroks: puroksArray
      };
      barangays[row.barangay] = puroksArray;
      return barangayObj;
    });

    res.json({
      success: true,
      barangays: barangays,
      barangayList: barangayList
    });
  } catch (err) {
    console.error('Error fetching barangays:', err);
    res.status(500).json({ success: false, message: 'Internal Server Error' });
  }
};

// API: Create new barangay
exports.createBarangay = async (req, res) => {
  try {
    const { barangayName } = req.body;

    if (!barangayName || !barangayName.trim()) {
      return res.status(400).json({ success: false, message: 'Barangay name is required' });
    }

    const normalizedBarangay = barangayName.trim();

    // Check if barangay already exists (case-insensitive) in MySQL
    const [existingBarangays] = await query(
      `SELECT id FROM barangays WHERE LOWER(barangay) = LOWER(?) LIMIT 1`,
      [normalizedBarangay]
    );

    if (existingBarangays.length > 0) {
      return res.status(400).json({ success: false, message: 'Barangay already exists' });
    }

    // Create new barangay in MySQL
    const [insertResult] = await query(
      `INSERT INTO barangays (barangay) VALUES (?)`,
      [normalizedBarangay]
    );

    res.json({
      success: true,
      message: 'Barangay created successfully',
      barangay: {
        id: insertResult.insertId,
        barangay: normalizedBarangay,
        puroks: []
      }
    });
  } catch (err) {
    console.error('Error creating barangay:', err);
    res.status(500).json({ success: false, message: 'Internal Server Error' });
  }
};

// API: Add purok to barangay
exports.addPurok = async (req, res) => {
  try {
    const { barangayId, purokName } = req.body;

    if (!barangayId || !purokName || !purokName.trim()) {
      return res.status(400).json({ success: false, message: 'Barangay and purok name are required' });
    }

    const normalizedPurok = purokName.trim();

    // Ensure barangay exists in MySQL
    const [barangayRows] = await query(
      `SELECT id, barangay FROM barangays WHERE id = ? LIMIT 1`,
      [barangayId]
    );

    if (barangayRows.length === 0) {
      return res.status(404).json({ success: false, message: 'Barangay not found' });
    }

    // Check duplicate purok within selected barangay (case-insensitive)
    const [existingPuroks] = await query(
      `SELECT id FROM puroks WHERE barangay_id = ? AND LOWER(purok) = LOWER(?) LIMIT 1`,
      [barangayId, normalizedPurok]
    );

    if (existingPuroks.length > 0) {
      return res.status(400).json({ success: false, message: 'Purok already exists in this barangay' });
    }

    // Insert purok into MySQL
    await query(
      `INSERT INTO puroks (barangay_id, purok) VALUES (?, ?)`,
      [barangayId, normalizedPurok]
    );

    res.json({
      success: true,
      message: 'Purok added successfully',
      barangay: barangayRows[0]
    });
  } catch (err) {
    console.error('Error adding purok:', err);
    res.status(500).json({ success: false, message: 'Internal Server Error' });
  }
};


exports.renderAdminAlert = async (req, res) => {
  try {
    res.render('admin/admin_alert');
  } catch (error) {
    
  }
};

exports.sendAlert = async (req, res) => {
  try {
    const { message, room } = req.body;

    // Validate input
    if (!message || !room) {
      return res.status(400).json({
        success: false,
        error: "Message and room are required"
      });
    }

    // Validate room
    if (room !== 'staff' && room !== 'youth' && room !== 'barangay') {
      return res.status(400).json({
        success: false,
        error: "Invalid room. Must be 'staff', 'youth', or 'barangay'"
      });
    }

    // Emit alert to the specified room
    req.io.to(room).emit('receive-alert', {
      message: message,
      timestamp: new Date(),
      from: 'Admin'
    });

    res.status(200).json({
      success: true,
      message: `Alert sent to ${room} room successfully`
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      error: error.message
    });
  }
};

// Update SMS received status
exports.updateSmsReceived = async (req, res) => {
  try {
    const { smsId, received } = req.body;
    
    if (!smsId) {
      return res.status(400).json({ success: false, message: 'SMS ID is required' });
    }

    const receivedBool = Boolean(received);
    const smsIdInt = parseInt(smsId, 10);
    if (!Number.isFinite(smsIdInt)) {
      return res.status(400).json({ success: false, message: 'Invalid SMS ID' });
    }
    
    const [updateResult] = await query(
      `UPDATE sms_history SET received = ? WHERE id = ?`,
      [receivedBool ? 1 : 0, smsIdInt]
    );

    if (!updateResult || updateResult.affectedRows === 0) {
      return res.status(404).json({ success: false, message: 'SMS record not found' });
    }

    const [rows] = await query(
      `SELECT
        id,
        recipient_type,
        record_id,
        phone_number,
        first_name,
        middle_name,
        last_name,
        barangay,
        purok,
        message,
        status,
        sent_by,
        sent_at,
        received
      FROM sms_history
      WHERE id = ?
      LIMIT 1`,
      [smsIdInt]
    );

    const updatedRow = rows?.[0];
    const updatedRecord = updatedRow
      ? { ...updatedRow, _id: updatedRow.id, received: Boolean(updatedRow.received) }
      : null;
    
    res.json({
      success: true,
      message: 'SMS received status updated successfully',
      data: updatedRecord
    });
  } catch (err) {
    console.error('updateSmsReceived error:', err);
    res.status(500).json({ success: false, message: 'Internal server error' });
  }
};


exports.renderSuperAdminLogs = async (req, res) => {
  try {
    const [pwdLogs] = await query(
      `SELECT l.id, l.pwd_id, l.field, l.old_value, l.new_value, l.edited_by, l.edited_at,
              TRIM(CONCAT(IFNULL(p.first_name,''), ' ', IFNULL(p.middle_name,''), ' ', IFNULL(p.last_name,''))) AS record_name
       FROM pwd_edit_logs l
       LEFT JOIN pwd p ON p.id = l.pwd_id
       ORDER BY (l.edited_at IS NULL), l.edited_at DESC, l.id DESC`
    );

    let seniorLogs = [];
    try {
      const [rows] = await query(
        `SELECT l.id, l.senior_id, l.field, l.old_value, l.new_value, l.edited_by, l.edited_at,
                TRIM(CONCAT(IFNULL(s.first_name,''), ' ', IFNULL(s.middle_name,''), ' ', IFNULL(s.last_name,''))) AS record_name
         FROM senior_edit_logs l
         LEFT JOIN senior_citizens s ON s.id = l.senior_id
         ORDER BY (l.edited_at IS NULL), l.edited_at DESC, l.id DESC`
      );
      seniorLogs = rows || [];
    } catch (seniorErr) {
      console.warn(
        'renderSuperAdminLogs: senior_edit_logs query failed. Apply model/alter_senior_edit_logs_audit.sql if columns are missing.',
        seniorErr.message
      );
    }

    let loginLogs = [];
    try {
      const [rows] = await query(
        `SELECT l.id, l.user_id, l.created_at,
                u.name AS user_name, u.email AS user_email, u.role AS user_role, u.status AS user_status
         FROM login_logs l
         LEFT JOIN users u ON u.id = l.user_id
         ORDER BY l.created_at DESC, l.id DESC`
      );
      loginLogs = rows || [];
    } catch (loginErr) {
      console.warn(
        'renderSuperAdminLogs: login_logs query failed. Ensure the login_logs table exists and references users(id).',
        loginErr.message
      );
    }

    res.render('superadmin/superadmin_logs', {
      pwdLogs: pwdLogs || [],
      seniorLogs,
      loginLogs
    });
  } catch (error) {
    console.error('renderSuperAdminLogs:', error);
    res.status(500).send('Unable to load logs');
  }
};




exports.renderBarangay = async (req, res) => {
  try {
    const sessionUser = req.session?.user;
    if (!sessionUser) return res.redirect("/");
    if (sessionUser.role !== "Barangay") return res.status(403).send("Forbidden");
    const scope = await fetchBarangayScopeForSessionUser(sessionUser);
    res.render("barangay/barangay", {
      user: sessionUser,
      assignedBarangayName: scope ? scope.name : "",
    });
  } catch (error) {
    console.error("renderBarangay:", error);
    res.status(500).send("Unable to load barangay account");
  }
};

exports.renderBarangaySeniorDashboard = async (req, res) => {
  try {
    const sessionUser = req.session?.user;
    if (!sessionUser) return res.redirect("/");
    if (sessionUser.role !== "Barangay") return res.status(403).send("Forbidden");
    const scope = await fetchBarangayScopeForSessionUser(sessionUser);
    if (!scope) {
      return res
        .status(403)
        .send("This account is not linked to a barangay. Contact an administrator.");
    }
    res.render("barangay/barangay_senior_dashboard", {
      user: sessionUser,
      assignedBarangayName: scope.name,
    });
  } catch (error) {
    console.error("renderBarangaySeniorDashboard:", error);
    res.status(500).send("Unable to load barangay OSCA analytics");
  }
};

exports.renderBarangaySenior = async (req, res) => {
  try {
    const sessionUser = req.session?.user;
    if (!sessionUser) return res.redirect("/");
    if (sessionUser.role !== "Barangay") return res.status(403).send("Forbidden");
    const scope = await fetchBarangayScopeForSessionUser(sessionUser);
    if (!scope) {
      return res
        .status(403)
        .send("This account is not linked to a barangay. Contact an administrator.");
    }

    const barangays = await fetchBarangays();
    const filteredBarangays = { [scope.name]: barangays[scope.name] || [] };

    let statusSql;
    const params = [scope.name];
    if (req.query.status === "archived") {
      statusSql = "WHERE status = 'Archived' AND barangay = ?";
    } else if (req.query.status === "all") {
      statusSql = "WHERE barangay = ?";
    } else {
      statusSql = "WHERE status <> 'Archived' AND barangay = ?";
    }

    const [rows] = await query(
      `SELECT id FROM senior_citizens ${statusSql} ORDER BY created_at DESC`,
      params
    );

    const seniorCitizens = await Promise.all(
      rows.map((row) => getSeniorByIdWithRelations(row.id))
    );

    res.render("barangay/barangay_senior", {
      barangays: filteredBarangays,
      seniorCitizens: seniorCitizens || [],
      user: sessionUser,
      assignedBarangayName: scope.name,
    });
  } catch (error) {
    console.error("renderBarangaySenior:", error);
    res.status(500).send("Unable to load barangay OSCA list");
  }
};

exports.renderBarangayPwd = async (req, res) => {
  try {
    const sessionUser = req.session?.user;
    if (!sessionUser) return res.redirect("/");
    if (sessionUser.role !== "Barangay") return res.status(403).send("Forbidden");
    const scope = await fetchBarangayScopeForSessionUser(sessionUser);
    if (!scope) {
      return res
        .status(403)
        .send("This account is not linked to a barangay. Contact an administrator.");
    }

    const barangays = await fetchBarangays();
    const filteredBarangays = { [scope.name]: barangays[scope.name] || [] };

    let statusSql;
    const params = [scope.name];
    if (req.query.status === "archived") {
      statusSql = "WHERE status = 'Archived' AND barangay = ?";
    } else if (req.query.status === "all") {
      statusSql = "WHERE barangay = ?";
    } else {
      statusSql = "WHERE status <> 'Archived' AND barangay = ?";
    }

    const [rows] = await query(
      `SELECT * FROM pwd ${statusSql} ORDER BY created_at DESC`,
      params
    );
    const pwds = await Promise.all(rows.map((r) => getPwdByIdWithRelations(r.id)));

    res.render("barangay/barangay_pwd", {
      barangays: filteredBarangays,
      pwds: pwds || [],
      user: sessionUser,
      assignedBarangayName: scope.name,
    });
  } catch (error) {
    console.error("renderBarangayPwd:", error);
    res.status(500).send("Unable to load barangay PWD list");
  }
};

