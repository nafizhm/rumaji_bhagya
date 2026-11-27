ALTER TABLE tpengajuan_akad ADD COLUMN projid INT NULL AFTER id,
 ADD INDEX idx_pengajuan_akad_project (projid);

-- Existing single-project kloters can be assigned without guessing or removing participants.
UPDATE tpengajuan_akad a
JOIN (SELECT pengajuan_id,MIN(projid) projid FROM tpengajuan_akad_peserta
      GROUP BY pengajuan_id HAVING COUNT(DISTINCT projid)=1) p ON p.pengajuan_id=a.id
SET a.projid=p.projid WHERE a.projid IS NULL;
