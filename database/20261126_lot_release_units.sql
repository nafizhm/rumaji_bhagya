-- Existing Lot records stay unassigned until a project is selected in Edit.
-- No membership is inferred from the legacy mkav.lot column.
ALTER TABLE mlot
 ADD COLUMN projid INT NULL AFTER id,
 MODIFY kode_lot VARCHAR(50) NOT NULL,
 MODIFY tanggal_release DATE NULL DEFAULT NULL,
 DROP INDEX uq_lot_kode,
 ADD UNIQUE KEY uq_lot_project (projid, kode_lot);

CREATE TABLE IF NOT EXISTS mlot_unit (
 lot_id INT UNSIGNED NOT NULL,
 projid INT NOT NULL,
 blok VARCHAR(3) NOT NULL,
 no CHAR(4) NOT NULL,
 PRIMARY KEY (projid, blok, no),
 KEY idx_lot_unit_lot (lot_id),
 CONSTRAINT fk_lot_unit_release FOREIGN KEY (lot_id) REFERENCES mlot(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
