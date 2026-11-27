CREATE TABLE IF NOT EXISTS tpengajuan_retensi (
 id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 projid INT NOT NULL,
 tanggal_surat DATE NOT NULL,
 no_surat VARCHAR(100) NOT NULL,
 tujuan_surat VARCHAR(150) NOT NULL,
 no_rekening VARCHAR(50) NOT NULL,
 kategori_id INT NOT NULL,
 nama_retensi VARCHAR(150) NOT NULL,
 created_by VARCHAR(10) NOT NULL,
 created_at DATETIME NOT NULL,
 updated_at DATETIME NOT NULL,
 UNIQUE KEY uq_pengajuan_retensi_surat (no_surat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE IF NOT EXISTS tpengajuan_retensi_detail (
 pengajuan_id INT UNSIGNED NOT NULL,
 projid INT NOT NULL,
 sprno INT NOT NULL,
 nama VARCHAR(200) NOT NULL,
 blok VARCHAR(50) NOT NULL,
 no_unit VARCHAR(50) NOT NULL,
 jumlah DECIMAL(18,0) NOT NULL,
 pencairan_id INT NOT NULL,
 PRIMARY KEY (pengajuan_id,projid,sprno),
 CONSTRAINT fk_pengajuan_retensi_detail FOREIGN KEY (pengajuan_id) REFERENCES tpengajuan_retensi(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE IF NOT EXISTS mpengajuan_retensi_akses_user (
 userid VARCHAR(10) NOT NULL PRIMARY KEY,
 c TINYINT(1) NOT NULL DEFAULT 0,r TINYINT(1) NOT NULL DEFAULT 0,u TINYINT(1) NOT NULL DEFAULT 0,d TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
INSERT INTO mmenu (moid,mid,mdesc)
SELECT 'PJ','T003','Pengajuan Pencairan Retensi' WHERE NOT EXISTS (SELECT 1 FROM mmenu WHERE moid='PJ' AND mid='T003');
INSERT INTO mauth (roleid,moid,mid,c,r,u,d,crby,crdat,chby,chdat)
SELECT roleid,'PJ','T003',0,0,0,0,'system',CURRENT_DATE,'system',CURRENT_DATE FROM mrole roles
WHERE NOT EXISTS (SELECT 1 FROM mauth a WHERE a.roleid=roles.roleid AND a.moid='PJ' AND a.mid='T003');
INSERT INTO mpengajuan_retensi_akses_user (userid,c,r,u,d)
SELECT userid,1,1,1,1 FROM muser WHERE userid='faizal'
ON DUPLICATE KEY UPDATE c=1,r=1,u=1,d=1;
