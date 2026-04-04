-- Run once on existing databases that already have senior_edit_logs without audit columns.
-- Safe to run if columns already exist: remove the statements that error, or use a migration tool.

ALTER TABLE `senior_edit_logs`
  ADD COLUMN `edited_by` varchar(255) DEFAULT NULL AFTER `new_value`;

ALTER TABLE `senior_edit_logs`
  ADD COLUMN `edited_at` datetime DEFAULT CURRENT_TIMESTAMP AFTER `edited_by`;
