SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE model;
TRUNCATE TABLE penilaian;
TRUNCATE TABLE nilai;
TRUNCATE TABLE hasil;
DELETE FROM kriteria;
DELETE FROM beasiswa;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO beasiswa (kd_beasiswa, nama) VALUES (1, 'Beasiswa Prestasi Akademik (BPA)');
INSERT INTO beasiswa (kd_beasiswa, nama) VALUES (2, 'LazisMU');

-- Kriteria for BPA (kd_beasiswa = 1)
INSERT INTO kriteria (kd_kriteria, kd_beasiswa, nama, sifat) VALUES (1, 1, 'IPK', 'max');
INSERT INTO kriteria (kd_kriteria, kd_beasiswa, nama, sifat) VALUES (2, 1, 'Penghasilan Orang Tua', 'min');
INSERT INTO kriteria (kd_kriteria, kd_beasiswa, nama, sifat) VALUES (3, 1, 'Semester', 'max');
INSERT INTO kriteria (kd_kriteria, kd_beasiswa, nama, sifat) VALUES (4, 1, 'Pengelompokkan Kesejahteraan', 'min');

-- Kriteria for LazisMU (kd_beasiswa = 2)
INSERT INTO kriteria (kd_kriteria, kd_beasiswa, nama, sifat) VALUES (5, 2, 'IPK', 'max');
INSERT INTO kriteria (kd_kriteria, kd_beasiswa, nama, sifat) VALUES (6, 2, 'Penghasilan Orang Tua', 'min');
INSERT INTO kriteria (kd_kriteria, kd_beasiswa, nama, sifat) VALUES (7, 2, 'Semester', 'max');
INSERT INTO kriteria (kd_kriteria, kd_beasiswa, nama, sifat) VALUES (8, 2, 'Pengelompokkan Kesejahteraan', 'min');

-- Bobot default untuk kriteria (semua 25%)
INSERT INTO model (kd_beasiswa, kd_kriteria, bobot) VALUES (1, 1, '25'), (1, 2, '25'), (1, 3, '25'), (1, 4, '25');
INSERT INTO model (kd_beasiswa, kd_kriteria, bobot) VALUES (2, 5, '25'), (2, 6, '25'), (2, 7, '25'), (2, 8, '25');
