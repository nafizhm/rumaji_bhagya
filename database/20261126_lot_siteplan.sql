ALTER TABLE mlot ADD COLUMN siteplan_active TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE tbooking ADD COLUMN lot_release_id INT UNSIGNED NULL DEFAULT NULL,
 ADD INDEX idx_booking_release (lot_release_id);
