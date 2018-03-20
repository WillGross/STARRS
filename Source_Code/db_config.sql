
-- USE _________; SPECIFY FINAL DATABASE USED IN IMPLEMENTATION

-- DOCUMENTATION:
-- Describes the structure of the new relational STARRS database
-- Source this file to wipe the db and create a clean coppy
-- Author: Jacob tower, Will Gross
-- Date:3/11/18, 3/12/18, 3/20/18
-- Version: 1.2
-- -----------------------------------------------------

-- drops tables in order of least dependency to most to avoid foreign key conflicts

 DROP TABLE IF EXISTS `ride_requests`;
 DROP TABLE IF EXISTS `shift_info`;
 DROP TABLE IF EXISTS  `location`;
 DROP TABLE IF EXISTS `shifts`;
 DROP TABLE IF EXISTS `drivers`;
 DROP TABLE IF EXISTS `vehicles`;



-- Table structure for table 'vehicle'

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

CREATE TABLE `shifts` (
	 `id` INT(10) NOT NULL auto_increment,
	 `driver_ID` int(5) NOT NULL DEFAULT 0,
	 `vehicle_ID` int(2) NOT NULL DEFAULT 0,
	 `date` DATE NOT NULL,
	 `dayOfWeek` VARCHAR(9),
	 `startTime` DATETIME NOT NULL,
	 `endTime` DATETIME NOT NULL,
	 PRIMARY KEY (`id`),
	 FOREIGN KEY (`driver_ID`) REFERENCES `drivers`(`id`),
	 FOREIGN KEY (`vehicle_ID`) REFERENCES `vehicles`(`id`)
	);

-- Dumping data for table 'shifts'

LOCK TABLES `shifts` WRITE ;
INSERT INTO `shifts` VALUES
  (1,1, 1, CURRENT_DATE (), 'Wednesday', CURRENT_TIMESTAMP (), CURRENT_TIMESTAMP ())
  ;
UNLOCK TABLES;


-- Table structure for table 'shift_info'

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

 CREATE TABLE `ride_requests`
 	(
 	`id` int(12) NOT NUll auto_increment,
 	`shift_ID` int(10) NOT NULL,
 	`timeOfCall` DATETIME NOT NULL,
 	`pickupTime` DATETIME NOT NULL,
 	`dropoffTime` DATETIME NOT NULL,
 	`pickupLocation` VARCHAR (100) NOT NULL,
 	`dropoffLocation` VARCHAR (100) NOT NULL,
 	`comments` VARCHAR(250) NOT NULL DEFAULT '',
  `numPeople` INT(2) NOT NULL,
 	`rideStatus` VARCHAR(8) NOT NULL,
 	PRIMARY KEY (`id`),
 	FOREIGN KEY (`shift_ID`) REFERENCES `shifts`(`id`)
  );
 	
 	
 	
 	
 	
 	
 	

	 
	 
	 