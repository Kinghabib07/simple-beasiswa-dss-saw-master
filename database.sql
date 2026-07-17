-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2017 at 06:38 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `beasiswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `beasiswa`
--

DROP TABLE IF EXISTS `beasiswa`;
CREATE TABLE IF NOT EXISTS `beasiswa` (
  `kd_beasiswa` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`kd_beasiswa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `beasiswa`
--

INSERT INTO `beasiswa` (`kd_beasiswa`, `nama`) VALUES
(1, 'Beasiswa Prestasi Akademik (BPA)'),
(2, 'LazisMU');

-- --------------------------------------------------------

--
-- Table structure for table `hasil`
--

DROP TABLE IF EXISTS `hasil`;
CREATE TABLE IF NOT EXISTS `hasil` (
  `kd_hasil` int(11) NOT NULL AUTO_INCREMENT,
  `kd_beasiswa` int(11) NOT NULL,
  `nim` char(9) NOT NULL,
  `nilai` float DEFAULT NULL,
  `tahun` char(4) DEFAULT NULL,
  PRIMARY KEY (`kd_hasil`),
  KEY `fk_mahasiswa` (`nim`),
  KEY `fk_beasiswa` (`kd_beasiswa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `hasil`
--

INSERT INTO `hasil` (`kd_hasil`, `kd_beasiswa`, `nim`, `nilai`, `tahun`) VALUES
(1, 1, '230001603', 1, '2026'),
(2, 2, '231111', 0.9993, '2026'),
(3, 2, '2322222', 0.3354, '2026'),
(4, 1, '2333333', 0.9, '2026');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

DROP TABLE IF EXISTS `kriteria`;
CREATE TABLE IF NOT EXISTS `kriteria` (
  `kd_kriteria` int(11) NOT NULL AUTO_INCREMENT,
  `kd_beasiswa` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `sifat` enum('min','max') DEFAULT NULL,
  PRIMARY KEY (`kd_kriteria`),
  KEY `kd_beasiswa` (`kd_beasiswa`),
  KEY `kd_beasiswa_2` (`kd_beasiswa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`kd_kriteria`, `kd_beasiswa`, `nama`, `sifat`) VALUES
(1, 1, 'IPK', 'max'),
(2, 1, 'Penghasilan Orang Tua', 'min'),
(3, 1, 'Semester', 'max'),
(4, 1, 'Pengelompokkan Kesejahteraan', 'min'),
(5, 2, 'IPK', 'max'),
(6, 2, 'Penghasilan Orang Tua', 'min'),
(7, 2, 'Semester', 'max'),
(8, 2, 'Pengelompokkan Kesejahteraan', 'min');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `nim` char(9) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `tahun_mengajukan` char(4) NOT NULL,
  PRIMARY KEY (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`nim`, `nama`, `alamat`, `jenis_kelamin`, `tahun_mengajukan`) VALUES
('230001603', 'Linda Fitriani', 'Yogyakarta', 'Perempuan', '2026'),
('231111', 'Habib', 'Yogyakarta', 'Laki-laki', '2026'),
('2322222', 'Shandy', 'Yogyakarta', 'Laki-laki', '2026'),
('2333333', 'Alfi', 'Yogyakarta', 'Perempuan', '2026'),
('234444', 'Azka', 'Yogyakarta', 'Laki-laki', '2026');

-- --------------------------------------------------------

--
-- Table structure for table `model`
--

DROP TABLE IF EXISTS `model`;
CREATE TABLE IF NOT EXISTS `model` (
  `kd_model` int(11) NOT NULL AUTO_INCREMENT,
  `kd_beasiswa` int(11) NOT NULL,
  `kd_kriteria` int(11) NOT NULL,
  `bobot` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`kd_model`),
  KEY `fk_kriteria` (`kd_kriteria`),
  KEY `fk_beasiswa` (`kd_beasiswa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `model`
--

INSERT INTO `model` (`kd_model`, `kd_beasiswa`, `kd_kriteria`, `bobot`) VALUES
(1, 1, 1, '0.45'),
(2, 1, 2, '0.25'),
(3, 1, 3, '0.10'),
(4, 1, 4, '0.20'),
(5, 2, 5, '0.20'),
(6, 2, 6, '0.40'),
(7, 2, 7, '0.10'),
(8, 2, 8, '0.30');

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

DROP TABLE IF EXISTS `nilai`;
CREATE TABLE IF NOT EXISTS `nilai` (
  `kd_nilai` int(11) NOT NULL AUTO_INCREMENT,
  `kd_beasiswa` int(11) DEFAULT NULL,
  `kd_kriteria` int(11) NOT NULL,
  `nim` char(9) NOT NULL,
  `nilai` float DEFAULT NULL,
  PRIMARY KEY (`kd_nilai`),
  KEY `fk_kriteria` (`kd_kriteria`),
  KEY `fk_mahasiswa` (`nim`),
  KEY `fk_beasiswa` (`kd_beasiswa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`kd_nilai`, `kd_beasiswa`, `kd_kriteria`, `nim`, `nilai`) VALUES
(5, 1, 1, '230001603', 3),
(6, 1, 2, '230001603', 1000000),
(7, 1, 3, '230001603', 6),
(8, 1, 4, '230001603', 2),
(9, 2, 5, '231111', 2.89),
(10, 2, 6, '231111', 20000),
(11, 2, 7, '231111', 5),
(12, 2, 8, '231111', 1),
(13, 2, 5, '2322222', 2.65),
(14, 2, 6, '2322222', 3000000),
(15, 2, 7, '2322222', 5),
(16, 2, 8, '2322222', 1),
(17, 1, 1, '2333333', 4),
(18, 1, 2, '2333333', 10),
(19, 1, 3, '2333333', 6),
(20, 1, 4, '2333333', 4);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

DROP TABLE IF EXISTS `pengguna`;
CREATE TABLE IF NOT EXISTS `pengguna` (
  `kd_pengguna` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL,
  `status` enum('petugas','puket','mahasiswa') DEFAULT NULL,
  PRIMARY KEY (`kd_pengguna`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`kd_pengguna`, `username`, `password`, `status`) VALUES
(1, 'petugas', 'afb91ef692fd08c445e8cb1bab2ccf9c', 'petugas'),
(2, 'puket', 'b679a71646e932b7c4647a081ee2a148', 'puket');

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

DROP TABLE IF EXISTS `penilaian`;
CREATE TABLE IF NOT EXISTS `penilaian` (
  `kd_penilaian` int(11) NOT NULL AUTO_INCREMENT,
  `kd_beasiswa` int(11) DEFAULT NULL,
  `kd_kriteria` int(11) NOT NULL,
  `keterangan` varchar(20) NOT NULL,
  `bobot` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`kd_penilaian`),
  KEY `fk_kriteria` (`kd_kriteria`),
  KEY `fk_beasiswa` (`kd_beasiswa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`kd_penilaian`, `kd_beasiswa`, `kd_kriteria`, `keterangan`, `bobot`) VALUES
(1, 1, 1, '3.00 - 3.20', 1),
(2, 1, 1, '3.21 - 3.40', 2),
(3, 1, 1, '3.41 - 3.60', 3),
(4, 1, 1, '>= 3.61', 4),
(5, 1, 2, '<= 500000', 1),
(6, 1, 2, '600000 - 1500000', 2),
(7, 1, 2, '1600000 - 2500000', 3),
(8, 1, 2, '>= 2600000', 4),
(9, 1, 3, '2 - 3', 1),
(10, 1, 3, '4 - 5', 2),
(11, 1, 3, '6 - 7', 3),
(12, 1, 3, '8', 4),
(13, 1, 4, '1 - 2', 1),
(14, 1, 4, '3 - 4', 2),
(15, 1, 4, '5 - 6', 3),
(16, 1, 4, '>= 7', 4);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hasil`
--
ALTER TABLE `hasil`
  ADD CONSTRAINT `hasil_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hasil_ibfk_2` FOREIGN KEY (`kd_beasiswa`) REFERENCES `beasiswa` (`kd_beasiswa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD CONSTRAINT `fk_beasiswa` FOREIGN KEY (`kd_beasiswa`) REFERENCES `beasiswa` (`kd_beasiswa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `model`
--
ALTER TABLE `model`
  ADD CONSTRAINT `model_ibfk_1` FOREIGN KEY (`kd_kriteria`) REFERENCES `kriteria` (`kd_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `model_ibfk_2` FOREIGN KEY (`kd_beasiswa`) REFERENCES `beasiswa` (`kd_beasiswa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`kd_kriteria`) REFERENCES `kriteria` (`kd_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nilai_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nilai_ibfk_3` FOREIGN KEY (`kd_beasiswa`) REFERENCES `beasiswa` (`kd_beasiswa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `penilaian_ibfk_1` FOREIGN KEY (`kd_kriteria`) REFERENCES `kriteria` (`kd_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_2` FOREIGN KEY (`kd_beasiswa`) REFERENCES `beasiswa` (`kd_beasiswa`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
