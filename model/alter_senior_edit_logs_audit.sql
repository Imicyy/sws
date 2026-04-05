-- Align senior_edit_logs with pwd_edit_logs audit columns (edited_by, edited_at).
-- Run once against an existing database that was created from an older schema dump.

ALTER TABLE `senior_edit_logs`
  ADD COLUMN `edited_by` varchar(255) DEFAULT NULL AFTER `new_value`,
  ADD COLUMN `edited_at` datetime NULL DEFAULT CURRENT_TIMESTAMP AFTER `edited_by`;
