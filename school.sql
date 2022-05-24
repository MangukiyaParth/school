/*
SQLyog Community v13.1.7 (64 bit)
MySQL - 10.4.22-MariaDB : Database - db_hrishi
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_hrishi` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `db_hrishi`;

/*Table structure for table `marksdetails` */

DROP TABLE IF EXISTS `marksdetails`;

CREATE TABLE `marksdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentid` int(11) NOT NULL,
  `examid` int(11) DEFAULT 0,
  `seatnumber` varchar(10) NOT NULL,
  `credit` int(11) NOT NULL,
  `isgrade` tinyint(1) NOT NULL,
  `papercode` varchar(10) NOT NULL,
  `papertitle` varchar(100) NOT NULL,
  `papertype` varchar(5) NOT NULL,
  `iselective` tinyint(1) NOT NULL,
  `internalpassingmarks` int(11) NOT NULL,
  `internalmarksobtained` varchar(11) NOT NULL,
  `externalpassingmarks` int(11) NOT NULL,
  `internaltotalmarks` varchar(11) NOT NULL,
  `externalsection1marks` varchar(11) NOT NULL,
  `externalsection2marks` varchar(11) NOT NULL,
  `externaltotalmarks` varchar(11) NOT NULL,
  `practicalmarksobtained` varchar(11) NOT NULL,
  `practicalmaxmarks` int(11) NOT NULL,
  `gracemarks` int(11) NOT NULL,
  `paperresult` varchar(15) NOT NULL,
  `gp` varchar(10) NOT NULL,
  `grade` varchar(2) NOT NULL,
  `attempt` varchar(10) NOT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  `externalmaxmarks` int(11) DEFAULT NULL,
  `RetryCount` int(11) DEFAULT NULL,
  `semester` int(10) unsigned NOT NULL DEFAULT 0,
  `year` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `studentid_2` (`studentid`,`papercode`),
  KEY `studentid` (`studentid`)
) ENGINE=InnoDB AUTO_INCREMENT=3437 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `studentdetails` */

DROP TABLE IF EXISTS `studentdetails`;

CREATE TABLE `studentdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admissionyear` int(11) NOT NULL,
  `collegeregistrationnumber` varchar(10) NOT NULL,
  `prn` varchar(20) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `fathername` varchar(50) DEFAULT NULL,
  `mothername` varchar(50) DEFAULT NULL,
  `stream` varchar(10) NOT NULL,
  `course` varchar(50) NOT NULL,
  `specialisation` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `students_tmp` */

DROP TABLE IF EXISTS `students_tmp`;

CREATE TABLE `students_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SeatNumber` text DEFAULT NULL,
  `RollNumber` text DEFAULT NULL,
  `College_Registration_No_` text DEFAULT NULL,
  `LastName` text DEFAULT NULL,
  `FirstName` text DEFAULT NULL,
  `FatherName` text DEFAULT NULL,
  `MotherName` text DEFAULT NULL,
  `Course` text DEFAULT NULL,
  `Specialisation` text DEFAULT NULL,
  `Division` text DEFAULT NULL,
  `PRN` text DEFAULT NULL,
  `LD` text DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `PhoneNumber` text DEFAULT NULL,
  `PhotoPath` text DEFAULT NULL,
  `ExamType` text DEFAULT NULL,
  `Year` text DEFAULT NULL,
  `Semester` text DEFAULT NULL,
  `Paper1Appeared` text DEFAULT NULL,
  `Code1` text DEFAULT NULL,
  `InternalC1` text DEFAULT NULL,
  `ExternalSection1C1` text DEFAULT NULL,
  `ExternalSection2C1` text DEFAULT NULL,
  `GraceC1` text DEFAULT NULL,
  `PracticalMarksC1` text DEFAULT NULL,
  `Attempt1` text DEFAULT NULL,
  `Remarks1` text DEFAULT NULL,
  `Paper2Appeared` text DEFAULT NULL,
  `Code2` text DEFAULT NULL,
  `InternalC2` text DEFAULT NULL,
  `ExternalSection1C2` text DEFAULT NULL,
  `ExternalSection2C2` text DEFAULT NULL,
  `ExternalTotalC2` text DEFAULT NULL,
  `GraceC2` text DEFAULT NULL,
  `PracticalMarksC2` text DEFAULT NULL,
  `Attempt2` text DEFAULT NULL,
  `Remarks2` text DEFAULT NULL,
  `Paper3Appeared` text DEFAULT NULL,
  `Code3` text DEFAULT NULL,
  `InternalC3` text DEFAULT NULL,
  `ExternalSection1C3` text DEFAULT NULL,
  `ExternalSection2C3` text DEFAULT NULL,
  `ExternalTotalC3` text DEFAULT NULL,
  `GraceC3` text DEFAULT NULL,
  `PracticalMarksC3` text DEFAULT NULL,
  `Attempt3` text DEFAULT NULL,
  `Remarks3` text DEFAULT NULL,
  `Paper4Appeared` text DEFAULT NULL,
  `Code4` text DEFAULT NULL,
  `InternalC4` text DEFAULT NULL,
  `ExternalSection1C4` text DEFAULT NULL,
  `ExternalSection2C4` text DEFAULT NULL,
  `ExternalTotalC4` text DEFAULT NULL,
  `GraceC4` text DEFAULT NULL,
  `PracticalMarksC4` text DEFAULT NULL,
  `Attempt4` text DEFAULT NULL,
  `Remarks4` text DEFAULT NULL,
  `Paper5Appeared` text DEFAULT NULL,
  `Code5` text DEFAULT NULL,
  `InternalC5` text DEFAULT NULL,
  `ExternalSection1C5` text DEFAULT NULL,
  `ExternalSection2C5` text DEFAULT NULL,
  `GraceC5` text DEFAULT NULL,
  `PracticalMarksC5` text DEFAULT NULL,
  `Attempt5` text DEFAULT NULL,
  `Remarks5` text DEFAULT NULL,
  `Paper6Appeared` text DEFAULT NULL,
  `Code6` text DEFAULT NULL,
  `InternalC6` text DEFAULT NULL,
  `ExternalSection1C6` text DEFAULT NULL,
  `ExternalSection2C6` text DEFAULT NULL,
  `GraceC6` text DEFAULT NULL,
  `PracticalMarksC6` text DEFAULT NULL,
  `Attempt6` text DEFAULT NULL,
  `Remarks6` text DEFAULT NULL,
  `Paper7Appeared` text DEFAULT NULL,
  `Code7` text DEFAULT NULL,
  `InternalC7` text DEFAULT NULL,
  `ExternalSection1C7` text DEFAULT NULL,
  `ExternalSection2C7` text DEFAULT NULL,
  `GraceC7` text DEFAULT NULL,
  `PracticalMarksC7` text DEFAULT NULL,
  `Attempt7` text DEFAULT NULL,
  `Remarks7` text DEFAULT NULL,
  `Paper8Appeared` text DEFAULT NULL,
  `Code8` text DEFAULT NULL,
  `InternalC8` text DEFAULT NULL,
  `ExternalSection1C8` text DEFAULT NULL,
  `ExternalSection2C8` text DEFAULT NULL,
  `GraceC8` text DEFAULT NULL,
  `PracticalMarksC8` text DEFAULT NULL,
  `Attempt8` text DEFAULT NULL,
  `Remarks8` text DEFAULT NULL,
  `Paper9Appeared` text DEFAULT NULL,
  `Code9` text DEFAULT NULL,
  `InternalC9` text DEFAULT NULL,
  `ExternalSection1C9` text DEFAULT NULL,
  `ExternalSection2C9` text DEFAULT NULL,
  `ExternalTotalC9` text DEFAULT NULL,
  `GraceC9` text DEFAULT NULL,
  `PracticalMarksC9` text DEFAULT NULL,
  `Attempt9` text DEFAULT NULL,
  `Remarks9` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
