CREATE TABLE IF NOT EXISTS tpencairan (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 projid INT NOT NULL,
 sprno INT NOT NULL,
 plafon DECIMAL(18,0) NOT NULL,
 potongan_kpp DECIMAL(18,0) NOT NULL,
 total_retensi DECIMAL(18,0) NOT NULL,
 jumlah DECIMAL(18,0) NOT NULL,
 request_key CHAR(32) NOT NULL,
 created_by VARCHAR(10) NOT NULL,
 created_at DATETIME NOT NULL,
 UNIQUE KEY uq_pencairan_awal (projid,sprno),
 UNIQUE KEY uq_pencairan_request (request_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS tpencairan_retensi (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 pencairan_id BIGINT UNSIGNED NOT NULL,
 kategori_id INT UNSIGNED NOT NULL,
 nama_retensi VARCHAR(150) NOT NULL,
 nilai DECIMAL(18,0) NOT NULL,
 UNIQUE KEY uq_pencairan_kategori (pencairan_id,kategori_id),
 CONSTRAINT fk_pencairan_retensi FOREIGN KEY (pencairan_id) REFERENCES tpencairan(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
