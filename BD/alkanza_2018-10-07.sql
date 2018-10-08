# ************************************************************
# Sequel Pro SQL dump
# Versión 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.21)
# Base de datos: alkanza
# Tiempo de Generación: 2018-10-08 03:33:43 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Volcado de tabla calculo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `calculo`;

CREATE TABLE `calculo` (
  `id_calculo` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` text,
  `lat` text,
  `lon` text,
  `radio` int(11) DEFAULT NULL,
  `valor` double DEFAULT NULL,
  `fecha_grab` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_calculo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `calculo` WRITE;
/*!40000 ALTER TABLE `calculo` DISABLE KEYS */;

INSERT INTO `calculo` (`id_calculo`, `nombre`, `lat`, `lon`, `radio`, `valor`, `fecha_grab`)
VALUES
	(1,'Calculo 1','4.6657','-74.0939',7339,5029.67,'2018-10-07 20:30:07'),
	(4,'Calculo 2','4.678743790693355','-74.10930633544923',3044,14712.24,'2018-10-07 21:11:40');

/*!40000 ALTER TABLE `calculo` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla centro
# ------------------------------------------------------------

DROP TABLE IF EXISTS `centro`;

CREATE TABLE `centro` (
  `id_centro` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` text,
  `lat` text,
  `lon` text,
  `balanceado` int(11) DEFAULT '1',
  PRIMARY KEY (`id_centro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `centro` WRITE;
/*!40000 ALTER TABLE `centro` DISABLE KEYS */;

INSERT INTO `centro` (`id_centro`, `nombre`, `lat`, `lon`, `balanceado`)
VALUES
	(13,'Centro 1','4.629810531745288','-74.11788940429688',1),
	(17,'Centro 2','4.690035596827192','-74.0581512451172',0),
	(19,'Centro 3','4.650342383235499','-74.06570434570314',1),
	(20,'Centro 4','4.683534276170673','-74.10415649414064',0),
	(21,'Centro 5','4.666767433658992','-74.11514282226564',0);

/*!40000 ALTER TABLE `centro` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
