

-- If sharing database with wordpress, drop these commands
-- DROP DATABASE IF EXISTS starrs;
-- CREATE DATABASE starrs;

--USE starrs; SPECIFY FINAL DATABASE USED IN IMPLEMENTATION

-- DOCUMENTATION:
-- Describes the structure of the STARRS database in the new format
-- Source this file to wipe the db and create a clean coppy
-- Author: Jacob tower, Will Gross
-- Date:3/11/18, 3/12/18
-- Version: 1.1
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

-- Dumping data for table 'vehicles'

LOCK TABLES `vehicles` WRITE ;
INSERT INTO `vehicles` VALUES
  (1,'tst',123456,22000,'ok','add','operational')
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

-- Table structure for table 'drivers'

DROP TABLE IF EXISTS `drivers`;
CREATE TABLE `drivers` (
	`id` int(5) NOT NULL auto_increment,
	`name` VARCHAR(30) NOT NULL,
	`userName` VARCHAR(15) NOT NULL,
	`email` VARCHAR(30) NOT NULL,
	`status` VARCHAR(100) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)
);


-- Dumping data for table 'drivers'

LOCK TABLES `drivers` WRITE ;
INSERT INTO `drivers` VALUES
  (1,'Will Gross', 'wlgross', 'wlgross@colby.edu', 'Not Cleared: not real driver')
  ;
UNLOCK TABLES;


-- Table structure for table 'shifts'

DROP TABLE IF EXISTS `shifts`;
CREATE TABLE `shifts` (
	 `id` INT(10) NOT NULL auto_increment,
	 `driver_ID` int(5) NOT NULL DEFAULT 0,
	 `vehicle_ID` int(2) NOT NULL DEFAULT 0,
	 `date` DATE(7) NOT NULL,
	 `day of week` VARCHAR(9),
	 `start time` DATETIME2 (7) NOT NULL,
	 `end time` DATETIME2 (7) NOT NULL,
	 PRIMARY KEY (`id`),
	 FOREIGN KEY (`driver_ID`) REFERENCES `drivers`(`id`),
	 FOREIGN KEY (`vehicle_ID`) REFERENCES `vehicles`(`id`)
	);

-- Dumping data for table 'shifts'

LOCK TABLES `shifts` WRITE ;
INSERT INTO `shifts` VALUES
  (1,1, 1, SYSDATE(), 'Wednesday', SYSDATETIME(), SYSDATETIME())
  ;
UNLOCK TABLES;


-- Table structure for table 'shift_info'

DROP TABLE IF EXISTS `shift_info`;
CREATE TABLE `shift_info` (
	`shift_ID` int(10) NOT NULL,
	`mileageStart` int(7) NOT NULL,
	`mileageFinish` int(7) NOT NUll,
	`fillUpGallons` DOUBLE(5,3) NOT NULL DEFAULT 0.0,
	`oilStatus` VARCHAR(3) NOT NULL DEFAULT '',
	`antifreezeStatus` VARCHAR (3) NOT NULL DEFAULT '',
	`comments` VARCHAR(250) NOT NULL DEFAULT '',
	FOREIGN KEY (`shift_ID`) REFERENCES `shifts`(`id`)
 );


-- Table structure for table 'ride_requests'

 DROP TABLE IF EXISTS `ride_requests`;
 CREATE TABLE `ride_requests`
 	(
 	`id` int(12) NOT NUll auto_increment,
 	`shift_ID` int(10) NOT NULL,
 	`timeOfCall` DATETIME2(7) NOT NULL,
 	`pickupTime` DATETIME2(7) NOT NULL,
 	`dropoffTime` DATETIME2(7) NOT NULL,
 	`pickupLocation` VARCHAR (100) NOT NULL,
 	`dropoffLocation` VARCHAR (100) NOT NULL,
 	`comments` VARCHAR(250) NOT NULL DEFAULT '',
  `numPeople` INT(2) NOT NULL,
 	`rideStatus` VARCHAR(8) NOT NULL,
 	PRIMARY KEY (`id`),
 	FOREIGN KEY (`shift_ID`) REFERENCES `shifts`(`id`)
  );
 	
 	
 	
 	
 	
 	
 	

	 
	 
	 