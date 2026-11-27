-- Tabel dan akses modul Pengajuan Retensi / Pengaturan Siteplan.
-- Tidak memuat data pengajuan atau transaksi dari komputer lokal.

CREATE TABLE IF NOT EXISTS `tpengajuan_retensi` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `projid` int NOT NULL,
  `tanggal_surat` date NOT NULL,
  `no_surat` varchar(100) NOT NULL,
  `tujuan_surat` varchar(150) NOT NULL,
  `no_rekening` varchar(50) NOT NULL,
  `kategori_id` int NOT NULL,
  `nama_retensi` varchar(150) NOT NULL,
  `created_by` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `kota_surat` varchar(100) NOT NULL DEFAULT 'Cirendeu',
  `nama_perusahaan` varchar(150) NOT NULL DEFAULT 'PT RAJA CAHAYA PROPERTINDO',
  `alamat_proyek` text,
  `bank_tujuan` varchar(150) NOT NULL DEFAULT 'PT. Bank Tabungan Negara (Persero) Tbk.',
  `alamat_tujuan` text,
  `atas_nama` varchar(150) NOT NULL DEFAULT '',
  `bank_rekening` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_pengajuan_retensi_surat` (`no_surat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `tpengajuan_retensi_detail` (
  `pengajuan_id` int unsigned NOT NULL,
  `projid` int NOT NULL,
  `sprno` int NOT NULL,
  `nama` varchar(200) NOT NULL,
  `blok` varchar(50) NOT NULL,
  `no_unit` varchar(50) NOT NULL,
  `jumlah` decimal(18,0) NOT NULL,
  `pencairan_id` int NOT NULL,
  `tanggal_cair` date DEFAULT NULL,
  `jumlah_cair` decimal(18,0) NOT NULL DEFAULT '0',
  `pencairan_bayar_id` bigint unsigned DEFAULT NULL,
  `dibayar_oleh` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`pengajuan_id`,`projid`,`sprno`),
  CONSTRAINT `fk_pengajuan_retensi_detail` FOREIGN KEY (`pengajuan_id`) REFERENCES `tpengajuan_retensi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `mpengajuan_retensi_akses_user` (
  `userid` varchar(10) NOT NULL,
  `c` tinyint(1) NOT NULL DEFAULT '0',
  `r` tinyint(1) NOT NULL DEFAULT '0',
  `u` tinyint(1) NOT NULL DEFAULT '0',
  `d` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `msiteplan_status` (
  `kode` varchar(30) NOT NULL,
  `nama` varchar(80) NOT NULL,
  `warna` char(7) NOT NULL,
  `urutan` int NOT NULL,
  `updated_by` varchar(10) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `msiteplan_status_akses_user` (
  `userid` varchar(10) NOT NULL,
  `c` tinyint(1) NOT NULL DEFAULT '0',
  `r` tinyint(1) NOT NULL DEFAULT '0',
  `u` tinyint(1) NOT NULL DEFAULT '0',
  `d` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT IGNORE INTO msiteplan_status(kode,nama,warna,urutan) VALUES
 ('tersedia','Ready','#ffffff',1),('belum_rilis','Belum Ready','#cbd5e1',2),('tidak_tersedia','Tidak Tersedia','#a8b0ba',3),
 ('booking','Booking','#f6cc5c',4),('spr','Proses SPR','#6db4ee',5),('akad','Sudah Akad','#76cb9a',6);

INSERT INTO mmenu (moid,mid,mdesc)
SELECT 'PJ','T003','Pengajuan Pencairan Retensi' WHERE NOT EXISTS (SELECT 1 FROM mmenu WHERE moid='PJ' AND mid='T003');
INSERT INTO mauth (roleid,moid,mid,c,r,u,d,crby,crdat,chby,chdat)
SELECT roleid,'PJ','T003',0,0,0,0,'system',CURRENT_DATE,'system',CURRENT_DATE FROM mrole roles
WHERE NOT EXISTS (SELECT 1 FROM mauth a WHERE a.roleid=roles.roleid AND a.moid='PJ' AND a.mid='T003');
INSERT INTO mpengajuan_retensi_akses_user (userid,c,r,u,d)
SELECT userid,1,1,1,1 FROM muser WHERE userid='faizal'
ON DUPLICATE KEY UPDATE c=1,r=1,u=1,d=1;

INSERT INTO mmenu(moid,mid,mdesc)
SELECT 'PJ','M009','Pengaturan Siteplan' WHERE NOT EXISTS(SELECT 1 FROM mmenu WHERE moid='PJ' AND mid='M009');
INSERT INTO mauth(roleid,moid,mid,c,r,u,d,crby,crdat,chby,chdat)
SELECT roleid,'PJ','M009',0,0,0,0,'system',CURRENT_DATE,'system',CURRENT_DATE FROM mrole roles
WHERE NOT EXISTS(SELECT 1 FROM mauth a WHERE a.roleid=roles.roleid AND a.moid='PJ' AND a.mid='M009');
INSERT INTO msiteplan_status_akses_user(userid,c,r,u,d)
SELECT userid,1,1,1,1 FROM muser WHERE userid='faizal'
ON DUPLICATE KEY UPDATE c=1,r=1,u=1,d=1;

