-- DROP DATABASE IF EXISTS starrs;
-- CREATE DATABASE starrs;
USE starrs;

-- DOCUMENTATION:
-- Describes the structure of the STARRS database
-- Source this file to wipe the db and create a clean coppy
-- Author: Will Gross
-- Date:1/30/18
-- Version: 1
-- -----------------------------------------------------

-- Table structure for table 'vehicle'

DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE `vehicles`  (
  `id` int(2) NOT NULL auto_increment, -- for database use
  `name` char(3) NOT NULL default '', -- vehicle identifier
  `plateNum` int(10) NOT NULL DEFAULT 000000, -- vehicle license plate
  `mileage` int(7) NOT NULL,
  `oilStatus` VARCHAR (3) NOT NULL DEFAULT '', -- values ok or add
  `antifreezeStatus` VARCHAR (3) NOT NULL DEFAULT '', -- values ok or add
  `operationalStatus` VARCHAR (50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE (`name`),
  UNIQUE (`plateNum`)
);

-- Dumping data for table 'vehicle'

LOCK TABLES `vehicles` WRITE ;
INSERT INTO `vehicles` VALUES
  (1,'TST',123456,22000,'ok','add','operational')
  -- ,(2,'j01',PLATENUM,0,'ok','ok','operational'),
  -- (3,'s01',PLATENUM,0,'ok','ok','operational')
  ;
UNLOCK TABLES;



-- Table structure for table 'location'

DROP TABLE IF EXISTS  `location`;
CREATE TABLE `location` (
  -- Ask about how to use tie breaker: `id` int(13) NOT NULL auto_increment,
  `vehicle_ID` int(2) NOT NULL default 00,
  `latitude` DOUBLE(11,7) NOT NULL DEFAULT 0.0,
  `longitude` DOUBLE(11,7) NOT NULL DEFAULT 0.0,
  `time` BIGINT(20) NOT NULL,-- in milliseconds since epoch
  PRIMARY KEY (`time`), -- most recent time will always reflect most recent real life condition
  -- Ask about how to use tie breaker: SECONDARY KEY (`id`), -- if two events have the same time, resort to which was catalogued first
  FOREIGN KEY (`vehicle_ID`) REFERENCES `vehicles`(`id`)
);