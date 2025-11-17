/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.0.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: agenda03
-- ------------------------------------------------------
-- Server version	12.0.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `interesse`
--

DROP TABLE IF EXISTS `interesse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `interesse` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interesse`
--

LOCK TABLES `interesse` WRITE;
/*!40000 ALTER TABLE `interesse` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `interesse` VALUES
(1,'Esportes'),
(2,'Música'),
(3,'Programação'),
(4,'Leitura'),
(5,'Viagens'),
(6,'Cinema'),
(7,'Fotografia'),
(8,'Gastronomia'),
(9,'teste'),
(10,'motocicletas');
/*!40000 ALTER TABLE `interesse` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `pessoa`
--

DROP TABLE IF EXISTS `pessoa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pessoa` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `data_nascimento` varchar(100) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) NOT NULL,
  `sexo` varchar(10) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pessoa`
--

LOCK TABLES `pessoa` WRITE;
/*!40000 ALTER TABLE `pessoa` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `pessoa` VALUES
(3,'Allanzin Atchim','testando@123.com','1990-01-01','pr','condado','feminino','9999','$2y$12$ZWti5wiMtY.sRb8ZEUorjOa36IpsSqz6O/JXJWa9IRWvdb0eHiS/i','690b5dd8c734b.png'),
(8,'allanzeira das cavernas','all@gmail.com','1333-02-01','sc','calçada','masculino','123','$2y$12$wkoVv8/XKeg71LwYUn3kHeRZ.KgPaNOuyqUP3osah2Q.jHtXuz7cS','6908e89c7259d.png'),
(16,'aaaaaa e','ee@e.com','2010-01-01','rs','akatsu','masculino','b1b1','$2y$12$7wbcjJj90ixWrHVN0ZuJgeMww7YU0MRnvlD4UKY/DOvkYbAT.zlQS','690b5de12c842.png'),
(18,'Admin Sistema','admin@teste.com','1990-01-01','sc','Rua Admin, 123','masculino','admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','690b5dcc4d692.png'),
(19,'doublin dobzeira','doob@dob.com','1010-10-10','sc','armario','masculino','1001','$2y$12$bxIULvQqJ7VeRH9YJ8R2/e47dmifmIgU7XrgL54A2SpkAA.JdIfee','690b5db755e4b.png'),
(20,'Mais UM teste','abc@def.com','1234-02-02','sp','do lado de la','feminino','101010','$2y$12$.aQgrv7EQZRmfdilFI44denp9JAvxpu/IO6wKhpyFJbOlhO923lja','691a78faaab4c.jpeg');
/*!40000 ALTER TABLE `pessoa` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `pessoa_interesse`
--

DROP TABLE IF EXISTS `pessoa_interesse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pessoa_interesse` (
  `fk_pessoa_cod` int(11) NOT NULL,
  `fk_interesse_cod` int(11) NOT NULL,
  PRIMARY KEY (`fk_pessoa_cod`,`fk_interesse_cod`),
  KEY `FK_pessoa_interesse_2` (`fk_interesse_cod`),
  CONSTRAINT `FK_pessoa_interesse_1` FOREIGN KEY (`fk_pessoa_cod`) REFERENCES `pessoa` (`cod`) ON DELETE CASCADE,
  CONSTRAINT `FK_pessoa_interesse_2` FOREIGN KEY (`fk_interesse_cod`) REFERENCES `interesse` (`cod`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pessoa_interesse`
--

LOCK TABLES `pessoa_interesse` WRITE;
/*!40000 ALTER TABLE `pessoa_interesse` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `pessoa_interesse` VALUES
(18,3),
(19,4),
(20,4),
(19,5),
(8,10);
/*!40000 ALTER TABLE `pessoa_interesse` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-11-16 22:36:38
