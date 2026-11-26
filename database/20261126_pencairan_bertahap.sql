ALTER TABLE tpencairan DROP INDEX uq_pencairan_awal, ADD INDEX idx_pencairan_spr (projid,sprno,id);
