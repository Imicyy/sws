-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2026 at 01:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ebmag`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `id` int(11) NOT NULL,
  `barangay` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `puroks`
--

CREATE TABLE `puroks` (
  `id` int(11) NOT NULL,
  `barangay_id` int(11) NOT NULL,
  `purok` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pwd`
--

CREATE TABLE `pwd` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `purok` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female','Other','Prefer not to say') NOT NULL,
  `place_of_birth` varchar(255) NOT NULL,
  `civil_status` enum('Single but Head of the Family','Single','Married') NOT NULL,
  `spouse_name` varchar(255) DEFAULT NULL,
  `fatherLastName` varchar(255) DEFAULT NULL,
  `fatherFirstName` varchar(255) DEFAULT NULL,
  `fatherMiddleName` varchar(255) DEFAULT NULL,
  `fatherExtension` varchar(50) DEFAULT NULL,
  `motherLastName` varchar(255) DEFAULT NULL,
  `motherFirstName` varchar(255) DEFAULT NULL,
  `motherMiddleName` varchar(255) DEFAULT NULL,
  `sss_id` varchar(100) DEFAULT NULL,
  `gsis_sss_no` varchar(100) DEFAULT NULL,
  `psn_no` varchar(100) DEFAULT NULL,
  `philhealth_no` varchar(100) DEFAULT NULL,
  `education_level` enum('Elementary Level','Elementary Graduate','High School Graduate','College Level','College Graduate','Post Graduate','Vocational','Not Attended School') NOT NULL,
  `employment_status` enum('Employee','Unemployed','Self-employed') NOT NULL,
  `employment_category` enum('Government','Private') DEFAULT NULL,
  `employment_type` enum('Permanent/Regular','Seasonal','Casual','Emergency') DEFAULT NULL,
  `disability_other_text` text DEFAULT NULL,
  `cause_other_text` text DEFAULT NULL,
  `status` enum('Active','Archived') DEFAULT 'Active',
  `archive_reason` text DEFAULT NULL,
  `edited_by` varchar(255) DEFAULT NULL,
  `edited_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pwd_contacts`
--

CREATE TABLE `pwd_contacts` (
  `id` int(11) NOT NULL,
  `pwd_id` int(11) DEFAULT NULL,
  `type` enum('primary','secondary','emergency') DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `relationship` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pwd_disabilities`
--

CREATE TABLE `pwd_disabilities` (
  `id` int(11) NOT NULL,
  `pwd_id` int(11) DEFAULT NULL,
  `disability` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pwd_disability_causes`
--

CREATE TABLE `pwd_disability_causes` (
  `id` int(11) NOT NULL,
  `pwd_id` int(11) DEFAULT NULL,
  `cause` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `senior_children`
--

CREATE TABLE `senior_children` (
  `id` int(11) NOT NULL,
  `senior_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `income` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `working_status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `senior_citizens`
--

CREATE TABLE `senior_citizens` (
  `id` int(11) NOT NULL,
  `reference_code` varchar(100) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `extension` varchar(50) DEFAULT NULL,
  `barangay` varchar(255) NOT NULL,
  `purok` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` int(11) NOT NULL,
  `marital_status` varchar(100) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `osca_id_number` varchar(100) DEFAULT NULL,
  `gsis_sss` varchar(100) DEFAULT NULL,
  `philhealth` varchar(100) DEFAULT NULL,
  `sc_association_org_id_no` varchar(100) DEFAULT NULL,
  `tin` varchar(100) DEFAULT NULL,
  `other_govt_id` varchar(100) DEFAULT NULL,
  `service_business_employment` varchar(255) DEFAULT NULL,
  `current_pension` varchar(255) DEFAULT NULL,
  `capability_to_travel` varchar(255) DEFAULT NULL,
  `spouse_name` varchar(255) DEFAULT NULL,
  `father_last_name` varchar(255) DEFAULT NULL,
  `father_first_name` varchar(255) DEFAULT NULL,
  `father_middle_name` varchar(255) DEFAULT NULL,
  `father_extension` varchar(50) DEFAULT NULL,
  `mother_last_name` varchar(255) DEFAULT NULL,
  `mother_first_name` varchar(255) DEFAULT NULL,
  `mother_middle_name` varchar(255) DEFAULT NULL,
  `community_service_other_text` text DEFAULT NULL,
  `status` enum('Active','Archived') DEFAULT 'Active',
  `archive_reason` text DEFAULT NULL,
  `edited_by` varchar(255) DEFAULT NULL,
  `edited_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `senior_community_services`
--

CREATE TABLE `senior_community_services` (
  `id` int(11) NOT NULL,
  `senior_id` int(11) DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `senior_contacts`
--

CREATE TABLE `senior_contacts` (
  `id` int(11) NOT NULL,
  `senior_id` int(11) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `relationship` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `senior_edit_logs`
--

CREATE TABLE `senior_edit_logs` (
  `id` int(11) NOT NULL,
  `senior_id` int(11) DEFAULT NULL,
  `field` varchar(255) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `senior_education`
--

CREATE TABLE `senior_education` (
  `id` int(11) NOT NULL,
  `senior_id` int(11) DEFAULT NULL,
  `educational_attainment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `senior_skills`
--

CREATE TABLE `senior_skills` (
  `id` int(11) NOT NULL,
  `senior_id` int(11) DEFAULT NULL,
  `skill` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_history`
--

CREATE TABLE `sms_history` (
  `id` int(11) NOT NULL,
  `recipient_type` enum('PWD','Youth','Senior') NOT NULL,
  `record_id` varchar(100) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `purok` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('sent','error','skipped') NOT NULL,
  `sent_by` varchar(255) NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `received` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Staff','Super Admin','Youth') NOT NULL,
  `status` enum('Active','Suspended','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`) VALUES
(1, 'Charles Ivan C. Monserate', 'ivancharles389@gmail.com', '$2b$10$gjcaBz4HskYRXoS.dIZw1em0HL6spJzR4XA9l1poOEg/1Lbcueib6', 'Staff', 'Active'),
(2, 'Monserate, Charles Ivan C.', 'admin@gmail.com', '$2b$10$ATcty0512XM8u8yU52FDeue.VtaLCG5qeKJqga5NPmMd7Ni8uqhMi', 'Admin', 'Active'),
(3, 'CHARLES IVAN C. MONSERATE', 'monseratecharles@gmail.com', '$2b$10$NbPP7BjfxP3gltzghii9decUyZMNxu7f58b752b1PNOTlg7.FEz4y', 'Staff', 'Active'),
(4, 'Jen Chome', 'jenchm@gmail.com', '$2b$10$f6.7.NBHSEygDBTWpYb4wOlus0PGaVIoPHSuuE0JBJPELnBxeo2QC', 'Admin', 'Active'),
(5, 'IC', 'staff_001@gmail.com', '$2b$10$LXSyuvM/3JvjVxQihdEuxOUHA99QdJ37iMTGi6/xXg/G1YdK/KKza', 'Staff', 'Active'),
(6, 'Jen', 'Admin_001@gmail.com', '$2b$10$PUm3UfFoBZKXmEr4HkxKR.PXhKMLNFgPYszoaGmXXqjIwss6IcqyC', 'Admin', 'Active'),
(7, 'Thea', 'Superadmin_001@gmail.com', '$2b$10$1RPYTaeTw2BXSNgq5kBLOOkan2JM6objE6OZCQ1e4oA6O3e30HIbK', 'Super Admin', 'Active'),
(8, 'Bebz', 'Youth_001@gmail.com', '$2b$10$UYw.Otjrgkm1ndUH5VIH.eZt171etrNIKTjgUq2JjdRMdv8hNk6Xu', 'Youth', 'Active'),
(9, 'Thea', 'thea@gmail.com', '$2b$10$gkQWxrWtfGrGs9nu.fCafOtN4Xj2hPs4wwM87KWmUy1yQbhBqx3ty', 'Youth', 'Active'),
(10, 'Van Dough', 'Van@gmail.com', '$2b$10$qWyL/X90cXJxQmlLvEvrtetg.LiKuFleIE6HF3i27oI1JpIt0BIE.', 'Staff', 'Active'),
(11, 'Angel Mae', 'angelmae@gmail.com', '$2b$10$ppGH9cGEf.NAqx2yHrdFNeztvyzk.W2BRyvIxLFCMnV7drdWublJ.', 'Staff', 'Active'),
(12, 'Test Admin 1761679925602', 'testadmin1761679925602@gmail.com', '$2b$10$A5EwpsAnzEUQ.Po9gx2eB.u1Rir92lMgElyA0lAhhDtpY2RXrZCJa', 'Admin', 'Active'),
(13, 'Test Youth 1761679930800', 'testyouth1761679930800@gmail.com', '$2b$10$JKJHFtLGz/R.3WYxcaeCn.NymkyULwnsbEQgDS885GvBNjSXH7UxG', 'Youth', 'Active'),
(14, 'Test Staff 1761679946629', 'teststaff1761679946629@gmail.com', '$2b$10$SKgFUryGoNZQVVC.IT1cJ.c043DkCOuD.ggyUO.dBmOOVhXHqESje', 'Staff', 'Active'),
(15, 'Test Admin 1761706590739', 'testadmin1761706590739@gmail.com', '$2b$10$tHt7VtL.YJeE.UV4ixiAqOV.N43Opqmk0tl7/4aPzvYPv1GmyqYGO', 'Admin', 'Active'),
(16, 'Alysa Mae Dizon', 'chuchuu.chm@gmail.com', '$2b$10$WrPDeTXx7A.PEn.A32LvPeQpJVUaTAZAArZh1dwnmnjXt3LrUQ2bC', 'Staff', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `youth`
--

CREATE TABLE `youth` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `purok` varchar(255) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female','Other','Prefer not to say') NOT NULL,
  `place_of_birth` varchar(255) NOT NULL,
  `education_level` enum('Elementary Level','Elementary Graduate','High School Graduate','College Level','College Graduate','Post Graduate','Vocational','Not Attended School') NOT NULL,
  `registered_sk` enum('Yes','No') NOT NULL,
  `voted_sk` enum('Yes','No') NOT NULL,
  `registered_national` enum('Yes','No') NOT NULL,
  `employment_status` enum('Employee','Unemployed','Self-employed') NOT NULL,
  `employment_category` enum('Government','Private') DEFAULT NULL,
  `employment_type` enum('Permanent/Regular','Seasonal','Casual','Emergency') DEFAULT NULL,
  `Assembly` enum('Yes','No') NOT NULL,
  `sk_times` enum('1-2','3-4','5+') DEFAULT NULL,
  `reason` enum('No KK Assembly Meeting','Not interested to attend') DEFAULT NULL,
  `youth_classification_other` text DEFAULT NULL,
  `youth_age_group_other` text DEFAULT NULL,
  `status` enum('Active','Archived') DEFAULT 'Active',
  `archive_reason` text DEFAULT NULL,
  `edited_by` varchar(255) DEFAULT NULL,
  `edited_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `youth_age_groups`
--

CREATE TABLE `youth_age_groups` (
  `id` int(11) NOT NULL,
  `youth_id` int(11) DEFAULT NULL,
  `age_group` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `youth_classifications`
--

CREATE TABLE `youth_classifications` (
  `id` int(11) NOT NULL,
  `youth_id` int(11) DEFAULT NULL,
  `classification` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `puroks`
--
ALTER TABLE `puroks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barangay_id` (`barangay_id`);

--
-- Indexes for table `pwd`
--
ALTER TABLE `pwd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pwd_contacts`
--
ALTER TABLE `pwd_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pwd_id` (`pwd_id`);

--
-- Indexes for table `pwd_disabilities`
--
ALTER TABLE `pwd_disabilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pwd_id` (`pwd_id`);

--
-- Indexes for table `pwd_disability_causes`
--
ALTER TABLE `pwd_disability_causes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pwd_id` (`pwd_id`);

--
-- Indexes for table `senior_children`
--
ALTER TABLE `senior_children`
  ADD PRIMARY KEY (`id`),
  ADD KEY `senior_id` (`senior_id`);

--
-- Indexes for table `senior_citizens`
--
ALTER TABLE `senior_citizens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `senior_community_services`
--
ALTER TABLE `senior_community_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `senior_id` (`senior_id`);

--
-- Indexes for table `senior_contacts`
--
ALTER TABLE `senior_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `senior_id` (`senior_id`);

--
-- Indexes for table `senior_edit_logs`
--
ALTER TABLE `senior_edit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `senior_id` (`senior_id`);

--
-- Indexes for table `senior_education`
--
ALTER TABLE `senior_education`
  ADD PRIMARY KEY (`id`),
  ADD KEY `senior_id` (`senior_id`);

--
-- Indexes for table `senior_skills`
--
ALTER TABLE `senior_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `senior_id` (`senior_id`);

--
-- Indexes for table `sms_history`
--
ALTER TABLE `sms_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `youth`
--
ALTER TABLE `youth`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `youth_age_groups`
--
ALTER TABLE `youth_age_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `youth_id` (`youth_id`);

--
-- Indexes for table `youth_classifications`
--
ALTER TABLE `youth_classifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `youth_id` (`youth_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `puroks`
--
ALTER TABLE `puroks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pwd`
--
ALTER TABLE `pwd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pwd_contacts`
--
ALTER TABLE `pwd_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pwd_disabilities`
--
ALTER TABLE `pwd_disabilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pwd_disability_causes`
--
ALTER TABLE `pwd_disability_causes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `senior_children`
--
ALTER TABLE `senior_children`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `senior_citizens`
--
ALTER TABLE `senior_citizens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `senior_community_services`
--
ALTER TABLE `senior_community_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `senior_contacts`
--
ALTER TABLE `senior_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `senior_edit_logs`
--
ALTER TABLE `senior_edit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `senior_education`
--
ALTER TABLE `senior_education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `senior_skills`
--
ALTER TABLE `senior_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_history`
--
ALTER TABLE `sms_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `youth`
--
ALTER TABLE `youth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `youth_age_groups`
--
ALTER TABLE `youth_age_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `youth_classifications`
--
ALTER TABLE `youth_classifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `puroks`
--
ALTER TABLE `puroks`
  ADD CONSTRAINT `puroks_ibfk_1` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pwd_contacts`
--
ALTER TABLE `pwd_contacts`
  ADD CONSTRAINT `pwd_contacts_ibfk_1` FOREIGN KEY (`pwd_id`) REFERENCES `pwd` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pwd_disabilities`
--
ALTER TABLE `pwd_disabilities`
  ADD CONSTRAINT `pwd_disabilities_ibfk_1` FOREIGN KEY (`pwd_id`) REFERENCES `pwd` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pwd_disability_causes`
--
ALTER TABLE `pwd_disability_causes`
  ADD CONSTRAINT `pwd_disability_causes_ibfk_1` FOREIGN KEY (`pwd_id`) REFERENCES `pwd` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `senior_children`
--
ALTER TABLE `senior_children`
  ADD CONSTRAINT `senior_children_ibfk_1` FOREIGN KEY (`senior_id`) REFERENCES `senior_citizens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `senior_community_services`
--
ALTER TABLE `senior_community_services`
  ADD CONSTRAINT `senior_community_services_ibfk_1` FOREIGN KEY (`senior_id`) REFERENCES `senior_citizens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `senior_contacts`
--
ALTER TABLE `senior_contacts`
  ADD CONSTRAINT `senior_contacts_ibfk_1` FOREIGN KEY (`senior_id`) REFERENCES `senior_citizens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `senior_edit_logs`
--
ALTER TABLE `senior_edit_logs`
  ADD CONSTRAINT `senior_edit_logs_ibfk_1` FOREIGN KEY (`senior_id`) REFERENCES `senior_citizens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `senior_education`
--
ALTER TABLE `senior_education`
  ADD CONSTRAINT `senior_education_ibfk_1` FOREIGN KEY (`senior_id`) REFERENCES `senior_citizens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `senior_skills`
--
ALTER TABLE `senior_skills`
  ADD CONSTRAINT `senior_skills_ibfk_1` FOREIGN KEY (`senior_id`) REFERENCES `senior_citizens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `youth_age_groups`
--
ALTER TABLE `youth_age_groups`
  ADD CONSTRAINT `youth_age_groups_ibfk_1` FOREIGN KEY (`youth_id`) REFERENCES `youth` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `youth_classifications`
--
ALTER TABLE `youth_classifications`
  ADD CONSTRAINT `youth_classifications_ibfk_1` FOREIGN KEY (`youth_id`) REFERENCES `youth` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
