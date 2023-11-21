CREATE DATABASE  IF NOT EXISTS `db_tienda` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_tienda`;
-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: db_tienda
-- ------------------------------------------------------
-- Server version	8.0.34

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cestas`
--

DROP TABLE IF EXISTS `cestas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cestas` (
  `idCesta` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(12) NOT NULL,
  `precioTotal` decimal(7,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`idCesta`),
  KEY `fk_cestas_usuarios` (`usuario`),
  CONSTRAINT `fk_cestas_usuarios` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cestas`
--

LOCK TABLES `cestas` WRITE;
/*!40000 ALTER TABLE `cestas` DISABLE KEYS */;
INSERT INTO `cestas` VALUES (1,'admin',8354.04),(6,'Elliot',0.00),(7,'Jaime',0.00);
/*!40000 ALTER TABLE `cestas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lineaspedidos`
--

DROP TABLE IF EXISTS `lineaspedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lineaspedidos` (
  `lineapedido` int NOT NULL AUTO_INCREMENT,
  `idProducto` int DEFAULT NULL,
  `idPedido` int DEFAULT NULL,
  `precioUnitario` float DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  PRIMARY KEY (`lineapedido`),
  KEY `idProducto` (`idProducto`),
  KEY `idPedido` (`idPedido`),
  CONSTRAINT `lineaspedidos_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`),
  CONSTRAINT `lineaspedidos_ibfk_2` FOREIGN KEY (`idPedido`) REFERENCES `pedidos` (`idPedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lineaspedidos`
--

LOCK TABLES `lineaspedidos` WRITE;
/*!40000 ALTER TABLE `lineaspedidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `lineaspedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `idPedido` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) DEFAULT NULL,
  `precioTotal` float DEFAULT NULL,
  `fechaPedido` date DEFAULT (curdate()),
  PRIMARY KEY (`idPedido`),
  KEY `usuario` (`usuario`),
  CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `idProducto` int NOT NULL AUTO_INCREMENT,
  `nombreProducto` varchar(40) NOT NULL,
  `precio` decimal(7,2) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `cantidad` decimal(5,0) NOT NULL,
  `imagen` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idProducto`),
  CONSTRAINT `chk_cantidad` CHECK (((`cantidad` >= 0) and (`cantidad` <= 99999))),
  CONSTRAINT `chk_precio` CHECK (((`precio` >= _utf8mb4'0') and (`precio` <= _utf8mb4'99999.99')))
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (30,'4080',1392.34,'MSI GeForce RTX 4080 16GB GAMING X TRIO 16GB DDR6X',9,'images/productos/71aytYjILTL._AC_SL1500_.jpg'),(31,'3070',561.93,'GeForce RTX 3070 Gaming OC 8G NVIDIA 8 GB GDDR6',23,'images/productos/71oheR1WsxL._AC_SL1500_.jpg');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productoscestas`
--

DROP TABLE IF EXISTS `productoscestas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productoscestas` (
  `idProducto` int NOT NULL,
  `idCesta` int NOT NULL,
  `cantidad` decimal(2,0) NOT NULL,
  PRIMARY KEY (`idProducto`,`idCesta`),
  KEY `fk_productosCestas_cestas` (`idCesta`),
  CONSTRAINT `fk_productosCestas_cestas` FOREIGN KEY (`idCesta`) REFERENCES `cestas` (`idCesta`),
  CONSTRAINT `fk_productosCestas_productos` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productoscestas`
--

LOCK TABLES `productoscestas` WRITE;
/*!40000 ALTER TABLE `productoscestas` DISABLE KEYS */;
INSERT INTO `productoscestas` VALUES (30,1,6);
/*!40000 ALTER TABLE `productoscestas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `usuario` varchar(12) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fechaNacimiento` date NOT NULL,
  `rol` varchar(10) DEFAULT 'cliente',
  PRIMARY KEY (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES ('admin','$2y$10$dvJlsBCFh/FbiEzmmIlx.OVHSu85ikaCLdQMEwPvJ/JuEjtuIQPZi','2000-01-01','admin'),('Elliot','$2y$10$OhYe2DixPjYOLgT3E0I4OOk8xHhHdnn/hb6Ffb8ntl0W6oC1Dnz22','2000-01-01','cliente'),('Jaime','$2y$10$NeT8YMwAQM6riPbxQ8qm0.MjvavVffrEjtRdhEZm04CrgH4GNRp4q','2000-01-01','cliente');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-21 11:23:50
