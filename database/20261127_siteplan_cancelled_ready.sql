DELETE FROM msiteplan_status WHERE kode='batal';
ALTER TABLE tbooking
 DROP INDEX uq_booking_unit,
 ADD INDEX idx_booking_unit(projid,blok,no),
 ADD COLUMN active_unit VARCHAR(100) GENERATED ALWAYS AS (CASE WHEN status IN ('Baru','Pending','Disetujui') THEN CONCAT(projid,':',blok,':',no) ELSE NULL END) STORED,
 ADD UNIQUE KEY uq_booking_active_unit(active_unit);
