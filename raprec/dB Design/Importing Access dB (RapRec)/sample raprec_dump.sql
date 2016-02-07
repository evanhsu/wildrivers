# Dump File
#
# Database is ported from MS Access
#--------------------------------------------------------
# Program Version 3.0.108

CREATE DATABASE IF NOT EXISTS `raprec`;
USE `raprec`;

#
# Table structure for table 'tbl_GenieList'
#

DROP TABLE IF EXISTS `tbl_GenieList`;

CREATE TABLE `tbl_GenieList` (
  `Genie_Number` VARCHAR(8) NOT NULL, 
  `Genie_BDate` DATETIME, 
  `Genie_EDate` DATETIME, 
  PRIMARY KEY (`Genie_Number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Dumping data for table 'tbl_GenieList'
#

INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('001', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('002', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('003', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('004', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('005', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('006', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('007', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('008', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('009', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('010', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('011', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('012', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('013', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('014', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('015', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('016', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('017', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('018', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('019', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('020', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('021', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('022', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('040', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('062 S', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('142 W', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('148 W', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('154 W', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_GenieList` (`Genie_Number`, `Genie_BDate`, `Genie_EDate`) VALUES ('177 W', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
# 28 records

#
# Table structure for table 'tbl_LetDown'
#

DROP TABLE IF EXISTS `tbl_LetDown`;

CREATE TABLE `tbl_LetDown` (
  `LetDown_Number` INTEGER NOT NULL AUTO_INCREMENT, 
  `Rappel_Number` INTEGER NOT NULL DEFAULT 0, 
  `LetDown` VARCHAR(7), 
  `LetDown_End` VARCHAR(1) NOT NULL, 
  INDEX (`Rappel_Number`), 
  PRIMARY KEY (`LetDown_Number`, `Rappel_Number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Dumping data for table 'tbl_LetDown'
#

INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (1, 123, '011', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (2, 123, '013', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (3, 130, '009', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (4, 130, '010', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (5, 130, '019', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (6, 141, '010', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (7, 141, '015', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (8, 144, '015', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (9, 145, '001', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (10, 150, '001', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (11, 158, '014', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (12, 159, '001', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (13, 189, '015', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (14, 189, '007', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (15, 166, '012', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (16, 188, '11', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (17, 188, '002', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (18, 188, '014', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (19, 185, '3', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (20, 185, '5', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (21, 184, '15', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (22, 183, '16', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (23, 182, '12', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (24, 182, '2', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (25, 191, '13', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (26, 191, '16', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (27, 195, '16', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (28, 195, '5', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (29, 195, '7', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (30, 195, '12', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (31, 204, '016', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (32, 204, '005', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (33, 204, '007', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (34, 204, '012', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (35, 205, '007', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (36, 206, '8', 'A');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (37, 206, '4', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (38, 220, '016', 'B');
INSERT INTO `tbl_LetDown` (`LetDown_Number`, `Rappel_Number`, `LetDown`, `LetDown_End`) VALUES (39, 221, '008', 'B');
# 39 records

#
# Table structure for table 'tbl_LetDownList'
#

DROP TABLE IF EXISTS `tbl_LetDownList`;

CREATE TABLE `tbl_LetDownList` (
  `LetDown_Number` VARCHAR(7) NOT NULL, 
  `LetDown_BDate` DATETIME, 
  `LetDown_EDate` DATETIME, 
  PRIMARY KEY (`LetDown_Number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Dumping data for table 'tbl_LetDownList'
#

INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('001', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('002', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('003', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('004', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('005', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('006', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('007', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('008', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('009', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('010', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('011', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('012', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('013', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('014', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('015', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('016', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('017', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('018', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('019', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('020', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('021', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_LetDownList` (`LetDown_Number`, `LetDown_BDate`, `LetDown_EDate`) VALUES ('022', '2008-05-23 00:00:00', '2009-01-01 00:00:00');
# 22 records

#
# Table structure for table 'tbl_NNumberList'
#

DROP TABLE IF EXISTS `tbl_NNumberList`;

CREATE TABLE `tbl_NNumberList` (
  `N_Number` VARCHAR(10) NOT NULL, 
  `N_BDate` DATETIME, 
  `N_EDate` DATETIME, 
  PRIMARY KEY (`N_Number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Dumping data for table 'tbl_NNumberList'
#

INSERT INTO `tbl_NNumberList` (`N_Number`, `N_BDate`, `N_EDate`) VALUES ('126', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_NNumberList` (`N_Number`, `N_BDate`, `N_EDate`) VALUES ('3KA', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_NNumberList` (`N_Number`, `N_BDate`, `N_EDate`) VALUES ('65H', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_NNumberList` (`N_Number`, `N_BDate`, `N_EDate`) VALUES ('73HJ', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_NNumberList` (`N_Number`, `N_BDate`, `N_EDate`) VALUES ('7HE', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_NNumberList` (`N_Number`, `N_BDate`, `N_EDate`) VALUES ('N21HX', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
# 6 records

#
# Table structure for table 'tbl_PilotList'
#

DROP TABLE IF EXISTS `tbl_PilotList`;

CREATE TABLE `tbl_PilotList` (
  `Pilot_ID` INTEGER NOT NULL AUTO_INCREMENT, 
  `Pilot_Name` VARCHAR(50), 
  `Pilot_BDate` DATETIME, 
  `Pilot_EDate` DATETIME, 
  INDEX (`Pilot_ID`), 
  PRIMARY KEY (`Pilot_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Dumping data for table 'tbl_PilotList'
#

INSERT INTO `tbl_PilotList` (`Pilot_ID`, `Pilot_Name`, `Pilot_BDate`, `Pilot_EDate`) VALUES (1, 'Stefan Drager', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_PilotList` (`Pilot_ID`, `Pilot_Name`, `Pilot_BDate`, `Pilot_EDate`) VALUES (2, 'Larry Doll', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_PilotList` (`Pilot_ID`, `Pilot_Name`, `Pilot_BDate`, `Pilot_EDate`) VALUES (3, 'Brad Atkinson', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_PilotList` (`Pilot_ID`, `Pilot_Name`, `Pilot_BDate`, `Pilot_EDate`) VALUES (4, 'Tom Angstadt', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_PilotList` (`Pilot_ID`, `Pilot_Name`, `Pilot_BDate`, `Pilot_EDate`) VALUES (5, 'Joe James', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_PilotList` (`Pilot_ID`, `Pilot_Name`, `Pilot_BDate`, `Pilot_EDate`) VALUES (6, 'Wagstaff', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_PilotList` (`Pilot_ID`, `Pilot_Name`, `Pilot_BDate`, `Pilot_EDate`) VALUES (7, 'Clayton', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_PilotList` (`Pilot_ID`, `Pilot_Name`, `Pilot_BDate`, `Pilot_EDate`) VALUES (8, 'C. Mitchell', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_PilotList` (`Pilot_ID`, `Pilot_Name`, `Pilot_BDate`, `Pilot_EDate`) VALUES (9, 'Matt Hart', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
# 9 records

#
# Table structure for table 'tbl_Rappeller'
#

DROP TABLE IF EXISTS `tbl_Rappeller`;

CREATE TABLE `tbl_Rappeller` (
  `Rappeller_Number` INTEGER NOT NULL AUTO_INCREMENT, 
  `Rappel_Number` INTEGER NOT NULL DEFAULT 0, 
  `Rappeller_Name` VARCHAR(50), 
  `Rope_Number` VARCHAR(50), 
  `Rope_End` VARCHAR(1), 
  `Genie_Number` VARCHAR(50), 
  INDEX (`Rappel_Number`), 
  PRIMARY KEY (`Rappeller_Number`, `Rappel_Number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Dumping data for table 'tbl_Rappeller'
#

INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (1, 1, 'Jared Nelson', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (2, 2, 'Jared Nelson', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (3, 3, 'Jared Nelson', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (4, 4, 'Jared Nelson', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (5, 5, 'Jared Nelson', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (6, 6, 'Jared Nelson', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (7, 7, 'Jared Nelson', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (8, 8, 'Jared Nelson', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (9, 9, 'Dan Quinones', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (10, 10, 'Evan Hsu', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (11, 11, 'Amy Kazmier', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (12, 12, 'Dan Quinones', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (13, 13, 'Dan Quinones', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (14, 14, 'Evan Hsu', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (15, 15, 'Evan Hsu', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (16, 16, 'Amy Kazmier', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (17, 17, 'Dan Quinones', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (18, 18, 'Amy Kazmier', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (19, 19, 'Amy Kazmier', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (20, 20, 'Evan Hsu', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (21, 21, 'George Yocom', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (22, 22, 'Jared Nelson', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (23, 21, 'Mary Cuddy', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (24, 23, 'Justin Ritshard', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (25, 23, 'Allison Dean', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (26, 24, 'Evan Hsu', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (27, 25, 'Dan Quinones', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (28, 26, 'Brandi Dutton', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (29, 27, 'Isiah Jimenez', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (30, 28, 'Brandi Dutton', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (31, 29, 'Sara Uvodich', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (32, 30, 'Jared Nelson', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (33, 31, 'Dax Herrera', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (34, 31, 'Andrew Hastings', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (35, 32, 'Mike Dake', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (36, 32, 'Matt Walch', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (37, 33, 'Justin Ritshard', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (38, 33, 'Allison Dean', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (39, 34, 'George Yocom', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (40, 34, 'Mary Cuddy', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (41, 35, 'Justin Ritshard', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (42, 35, 'Allison Dean', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (43, 36, 'George Yocom', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (44, 36, 'Mary Cuddy', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (45, 37, 'Dan Quinones', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (46, 38, 'Evan Hsu', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (47, 39, 'Amy Kazmier', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (48, 40, 'Dan Quinones', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (49, 41, 'Evan Hsu', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (50, 42, 'Amy Kazmier', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (51, 43, 'Dan Quinones', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (52, 44, 'Evan Hsu', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (53, 45, 'Amy Kazmier', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (54, 46, 'Dan Quinones', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (55, 47, 'Evan Hsu', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (56, 48, 'Amy Kazmier', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (57, 49, 'Justin Ritshard', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (58, 49, 'Allison Dean', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (59, 50, 'George Yocom', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (60, 50, 'Mary Cuddy', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (61, 51, 'Dax Herrera', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (62, 51, 'Andrew Hastings', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (63, 52, 'Mike Dake', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (64, 52, 'Matt Walch', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (65, 53, 'Mike Dake', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (66, 53, 'Matt Walch', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (67, 54, 'Dax Herrera', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (68, 54, 'Andrew Hastings', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (69, 55, 'George Yocom', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (70, 55, 'Mary Cuddy', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (71, 56, 'Justin Ritshard', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (72, 56, 'Allison Dean', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (73, 57, 'Mike Dake', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (74, 57, 'Matt Walch', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (75, 58, 'Dax Herrera', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (76, 58, 'Andrew Hastings', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (77, 59, 'George Yocom', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (78, 59, 'Mary Cuddy', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (79, 60, 'Justin Ritshard', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (80, 60, 'Allison Dean', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (81, 61, 'Dax Herrera', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (82, 61, 'Mike Dake', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (83, 61, 'Andrew Hastings', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (84, 61, 'Matt Walch', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (85, 61, 'George Yocom', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (86, 61, 'Justin Ritshard', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (87, 62, 'Mary Cuddy', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (88, 62, 'Allison Dean', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (89, 63, 'Dax Herrera', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (90, 63, 'Mike Dake', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (91, 63, 'Andrew Hastings', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (92, 63, 'Matt Walch', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (93, 63, 'George Yocom', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (94, 63, 'Justin Ritshard', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (95, 64, 'Mary Cuddy', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (96, 64, 'Allison Dean', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (97, 65, 'Isiah Jimenez', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (98, 66, 'Brandi Dutton', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (99, 67, 'Isiah Jimenez', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (100, 68, 'Sara Uvodich', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (101, 69, 'Isiah Jimenez', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (102, 70, 'Brandi Dutton', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (103, 71, 'Allison Dean', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (104, 72, 'Sara Uvodich', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (105, 73, 'Brandi Dutton', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (106, 74, 'Sara Uvodich', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (107, 75, 'Brandi Dutton', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (108, 76, 'Brandi Dutton', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (109, 77, 'Brandi Dutton', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (110, 78, 'Brandi Dutton', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (111, 79, 'Sara Uvodich', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (112, 80, 'Sara Uvodich', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (113, 81, 'Isiah Jimenez', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (114, 82, 'Isiah Jimenez', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (115, 83, 'Isiah Jimenez', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (116, 84, 'Brandi Dutton', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (117, 85, 'Sara Uvodich', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (118, 86, 'Mary Cuddy', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (119, 86, 'Allison Dean', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (120, 87, 'Dax Herrera', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (121, 87, 'Mike Dake', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (122, 87, 'Andrew Hastings', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (123, 87, 'Matt Walch', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (124, 87, 'George Yocom', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (125, 87, 'Justin Ritshard', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (126, 88, 'Mary Cuddy', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (127, 88, 'Allison Dean', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (128, 89, 'George Yocom', '011', 'B', '011');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (129, 89, 'Sara Uvodich', '001', 'B', '007');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (130, 90, 'Evan Hsu', '021', 'B', '002');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (131, 90, 'Matt Walch', '013', 'B', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (132, 91, 'Brandi Dutton', '009', 'B', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (133, 91, 'Mary Cuddy', '014', 'B', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (134, 92, 'Amy Kazmier', '012', 'B', '006');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (135, 92, 'Mike Dake', '018', 'B', '012');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (136, 93, 'Justin Ritshard', '003', 'B', '021');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (137, 93, 'Sara Uvodich', '006', 'B', '005');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (138, 94, 'Dax Herrera', '020', 'B', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (139, 94, 'Andrew Hastings', '017', 'A', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (140, 95, 'Jared Nelson', '010', 'B', '020');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (141, 95, 'Dan Quinones', '002', 'B', '019');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (142, 96, 'Dan Quinones', '009', 'B', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (143, 96, 'Isiah Jimenez', '010', 'B', '020');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (144, 97, 'Evan Hsu', '013', 'B', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (145, 97, 'Matt Walch', '001', 'B', '007');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (146, 98, 'Brandi Dutton', '005', 'B', '021');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (147, 98, 'Mike Dake', '013', 'B', '006');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (148, 99, 'JD Connall', '004', 'B', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (149, 99, 'Chris Fezer', '001', 'A', '007');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (150, 100, 'Jared Nelson', '013', 'A', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (151, 100, 'Dax Herrera', '011', 'B', '011');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (152, 101, 'Mary Cuddy', '005', 'A', '021');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (153, 101, 'Andrew Hastings', '019', 'B', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (154, 102, 'Allison Dean', '015', 'A', '017');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (155, 102, 'Justin Ritshard', '006', 'B', '005');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (156, 103, 'Sara Uvodich', '017', 'A', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (157, 103, 'Dan Quinones', '004', 'B', '002');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (158, 104, 'Isiah Jimenez', '009', 'A', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (159, 104, 'Evan Hsu', '022', 'B', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (160, 105, 'Matt Walch', '002', 'A', '019');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (161, 105, 'Brandi Dutton', '016', 'B', '009');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (162, 106, 'Mike Dake', '008', 'B', '001');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (163, 106, 'Allison Dean', '012', 'B', '012');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (164, 107, 'Matt Walch', '010', 'A', '020');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (165, 107, 'Mary Cuddy', '007', 'B', '022');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (166, 108, 'Jared Nelson', '008', 'A', '001');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (167, 108, 'Dax Herrera', '022', 'A', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (168, 109, 'Isiah Jimenez', '017', 'B', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (169, 109, 'Evan Hsu', '013', 'B', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (170, 110, 'Allison Dean', '018', 'A', '006');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (171, 110, 'Justin Ritshard', '014', 'B', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (172, 111, 'Sara Uvodich', '020', 'B', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (173, 111, 'Dan Quinones', '021', 'A', '002');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (174, 112, 'Andrew Hastings', '004', 'A', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (175, 112, 'Brandi Dutton', '009', 'B', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (176, 113, 'Mike Dake', '019', 'A', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (177, 113, 'Matt Walch', '016', 'A', '009');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (178, 114, 'Mike Dake', '022', 'B', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (179, 114, 'Andrew Hastings', '017', 'A', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (180, 115, 'Dax Herrera', '012', 'A', '012');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (181, 115, 'Brandi Dutton', '011', 'A', '011');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (182, 116, 'Justin Ritshard', '021', 'B', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (183, 116, 'Sara Uvodich', '004', 'B', '016');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (184, 117, 'Andrew Hastings', '009', 'A', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (185, 117, 'Mary Cuddy', '016', 'B', '009');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (186, 118, 'Isiah Jimenez', '014', 'A', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (187, 118, 'Evan Hsu', '013', 'A', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (188, 119, 'Matt Walch', '019', 'B', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (189, 119, 'Amy Kazmier', '010', 'B', '006');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (190, 120, 'Ruben Griego', '022', 'A', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (191, 120, 'Ryan Tucker', '019', 'A', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (192, 121, 'Jimmy Bickers', '011', 'B', '011');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (193, 121, 'Brian Milligan', '017', 'B', '012');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (194, 122, 'Dan Quinones', '013', 'B', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (195, 122, 'Sara Uvodich', '010', 'A', '006');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (196, 123, 'Jimmy Bickers', '010', 'A', '006');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (197, 123, 'Mike Dake', '017', 'A', '011');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (198, 124, 'Dan Quinones', '007', 'A', '022');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (199, 124, 'Sara Uvodich', '008', 'B', '001');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (200, 125, 'Evan Hsu', '022', 'B', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (201, 125, 'Matt Walch', '013', 'A', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (202, 126, 'Amy Kazmier', '019', 'B', '012');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (203, 126, 'Andrew Hastings', '012', 'A', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (204, 127, 'Justin Ritshard', '014', 'B', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (205, 127, 'Tanner Eccles', '018', 'B', '020');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (206, 128, 'Isiah Jimenez', '021', 'B', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (207, 128, 'Brandi Dutton', '004', 'A', '016');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (208, 129, 'Griff Williams', '001', 'B', '007');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (209, 129, 'Andrew Hastings', '011', 'A', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (210, 130, 'Dan Quinones', '007', 'B', '006');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (211, 130, 'Brian Milligan', '009', 'B', '009');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (212, 130, 'Ryan Tucker', '001', 'A', '011');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (213, 130, 'Dax Herrera', '002', 'B', '012');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (214, 130, 'Evan Hsu', '021', 'A', '002');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (215, 130, 'Isiah Jimenez', '020', 'A', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (216, 131, 'Matt Kernek', '011', 'B', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (217, 131, 'Andrew Hastings', '014', 'A', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (218, 132, 'Summer Myllymaki', '012', 'B', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (219, 132, 'Ryan Cherno', '020', 'A', '002');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (220, 133, 'Norm Sealing', '011', 'A', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (221, 133, 'Mike Dake', '020', 'B', '002');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (222, 134, 'Summer Myllymaki', '008', 'B', '011');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (223, 134, 'Mike Dake', '010', 'B', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (224, 135, 'Ryan Cherno', '012', 'A', '022');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (225, 135, 'Matt Kernek', '021', 'B', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (226, 136, 'Griff Williams', '016', 'B', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (227, 136, 'Amy Kazmier', '022', 'B', '007');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (228, 137, 'Jared Nelson', '008', 'A', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (229, 137, 'Allison Dean', '011', 'A', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (230, 138, 'Isiah Jimenez', '012', 'B', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (231, 138, 'Billy Turner', '010', 'A', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (232, 139, 'Sara Uvodich', '016', 'A', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (233, 139, 'Allison Dean', '008', 'B', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (234, 140, 'Dan Quinones', '020', 'A', '002');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (235, 140, 'Dax Herrera', '022', 'A', '007');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (236, 141, 'Brandi Dutton', '008', 'A', '007');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (237, 141, 'Matt Walch', '010', 'A', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (238, 141, 'Mary Cuddy', '016', 'A', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (239, 141, 'Justin Ritshard', '022', 'A', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (240, 142, 'Ben Woodward', '021', 'A', '011');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (241, 142, 'Steve Schrock', '011', 'B', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (242, 143, 'Nick Chivira', '018', 'A', '020');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (243, 143, 'Ethan Marakowski', '015', 'B', '017');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (244, 144, 'Matt Walch', '019', 'B', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (245, 144, 'Norm Sealing', '013', 'B', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (246, 145, 'Nick Chivira', '009', 'A', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (247, 145, 'Allison Dean', '014', 'B', '019');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (248, 146, 'Amy Kazmier', '006', 'A', '005');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (249, 146, 'Dan Quinones', '007', 'A', '012');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (250, 147, 'Mary Cuddy', '021', 'B', '020');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (251, 147, 'Justin Ritshard', '017', 'A', '022');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (252, 148, 'Dax Herrera', '015', 'A', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (253, 148, 'Andrew Hastings', '019', 'A', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (254, 149, 'Isiah Jimenez', '014', 'A', '019');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (255, 149, 'Matt Lovemark', '009', 'B', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (256, 150, 'Billy Turner', '017', 'B', '020');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (257, 150, 'Evan Hsu', '013', 'A', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (258, 151, 'Jared Nelson', '010', 'A', '020');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (259, 151, 'Dan Quinones', '002', 'A', '019');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (260, 152, 'Dax Herrera', '020', 'A', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (261, 152, 'Andrew Hastings', '017', 'B', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (262, 153, 'Justin Ritshard', '003', 'A', '021');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (263, 153, 'Sara Uvodich', '006', 'A', '005');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (264, 154, 'Amy Kazmier', '012', 'A', '006');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (265, 154, 'Mike Dake', '018', 'A', '012');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (266, 155, 'Brandi Dutton', '009', 'A', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (267, 155, 'Mary Cuddy', '014', 'A', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (268, 156, 'Evan Hsu', '021', 'A', '002');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (269, 156, 'Matt Walch', '013', 'A', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (270, 157, 'George Yocom', '011', 'A', '011');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (271, 157, 'Sara Uvodich', '001', 'A', '007');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (272, 158, 'Jared Nelson', '010', 'B', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (273, 158, 'Mike Dake', '018', 'B', '017');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (274, 159, 'Sara Brown', '008', 'A', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (275, 159, 'Mike Valenti', '017', 'A', '020');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (276, 160, 'Mary Cuddy', '009', 'A', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (277, 160, 'Evan Hsu', '006', 'B', '005');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (278, 161, 'Tanner Eccles', '013', 'B', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (279, 161, 'Matt Walch', '015', 'B', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (280, 163, 'Brandi Dutton', '006', 'A', '005');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (281, 163, 'Sara Uvodich', '014', 'B', '019');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (282, 164, 'Norm Sealing', '014', 'A', '019');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (283, 164, 'Justin Ritshard', '006', 'B', '005');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (284, 165, 'Matt Walch', '008', 'B', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (285, 165, 'Evan Hsu', '018', 'A', '017');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (286, 167, 'Amy Kazmier', '016', 'B', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (287, 167, 'Mary Cuddy', '012', 'A', '002');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (288, 168, 'Dan Quinones', '013', 'A', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (289, 168, 'Andrew Hastings', '009', 'B', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (290, 169, 'Darron Canfield', '006', 'A', '005');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (291, 169, 'Sebastion Macnab', '004', 'B', '016');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (292, 170, 'Mike Dake', '097', 'A', '040');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (293, 170, 'Isiah Jimenez', '099', 'B', '062');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (294, 171, 'Sarah Uvodich', '015', 'A', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (295, 171, 'Rob Kriegbaum', '005', 'B', '021');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (296, 172, 'Kelly Rudger', '005', 'A', '021');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (297, 172, 'Dax Herrera', '018', 'B', '017');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (298, 173, 'Eric Scholl', '012', 'B', '002');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (299, 173, 'Jared Nelson', '013', 'B', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (300, 174, 'Dan Quinones', '004', 'A', '016');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (301, 174, 'Justin Ritshard', '005', 'B', '017');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (302, 175, 'Sam Both', '018', 'A', '002');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (303, 175, 'Jamey Bachman', '012', 'A', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (304, 176, 'Justin Ritshard', '236', 'A', '148 W');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (305, 177, 'Mary Cuddy', '223', 'A', '142 W');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (306, 178, 'Andrew Hastings', '017', 'B', '020');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (307, 178, 'Sarah DeMay', '013', 'A', '021');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (308, 179, 'Jared Nelson', '018', 'B', '002');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (309, 179, 'Matt Walch', '015', 'B', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (310, 180, 'Evan Hsu', '006', 'B', '005');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (311, 180, 'Dax Herrera', '009', 'A', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (312, 181, 'Sarah DeMay', '016', 'A', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (313, 181, 'Andrew Hastings', '012', 'B', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (314, 182, 'Jared Nelson', '017', 'A', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (315, 182, 'Matt Walch', '012', 'A', '020');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (316, 183, 'Tanner Eccles', '021', 'B', '021');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (317, 183, 'Denison', '013', 'B', '005');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (318, 184, 'Evan Hsu', '001', 'A', '009');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (319, 184, 'Dave Ortlund', '020', 'B', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (320, 185, 'Andrew Hastings', '021', 'A', '022');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (321, 185, 'Sarah DeMay', '002', 'A', '006');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (322, 186, 'Brandon Nelson', '022', 'B', '012');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (323, 186, 'Mike Valenti', '007', 'B', '007');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (324, 187, 'Dan Quinones', '240', 'B', '177 W');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (325, 187, 'Sara Uvodich', '240', 'B', '154 W');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (326, 188, 'Jared Nelson', '021', 'B', '022');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (327, 188, 'Andrew Hastings', '002', 'B', '006');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (328, 188, 'Dan Balling', '022', 'A', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (329, 188, 'Evan Hsu', '020', 'A', '007');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (330, 166, 'Jimmy Bickers', '023', 'B', '012');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (331, 166, 'Mike Dake', '020', 'B', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (332, 189, 'Ryan Tucker', '017', 'B', '005');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (333, 189, 'Matt Walch', '007', 'A', '009');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (334, 190, 'Sara Brown', '11', 'A', '011');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (335, 190, 'Leshuk', '12', 'B', '020');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (336, 191, 'Brandi Dutton', '013', 'A', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (337, 191, 'Sara Uvodich', '005', 'B', '016');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (338, 192, 'Amy Kazmier', '019', 'B', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (339, 192, 'Joe Cochron', '017', 'A', '009');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (340, 193, 'Hiram Rooper', '014', 'B', '019');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (341, 193, 'Justin Ritshard', '006', 'A', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (342, 194, 'Ebin Babb', '015', 'A', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (343, 194, 'Matt Hampton', '016', 'B', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (344, 195, 'Hiram Rooper', '010', 'B', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (345, 195, 'Joey Fansler', '016', 'A', '019');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (346, 195, 'Denison', '008', 'B', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (347, 195, 'Johnson', '006', 'B', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (348, 196, 'Mary Cuddy', '014', 'A', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (349, 196, 'Brandi Dutton', '005', 'A', '009');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (350, 197, 'Rob Zeilke', '0221', 'B', '022');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (351, 197, 'Brian Milligan', '019', 'B', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (352, 198, 'Evan Hsu', '021', 'A', '022');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (353, 198, 'Sara Brown', '013', 'B', '016');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (354, 199, 'Matt Walch', '002', 'A', '006');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (355, 199, 'Ryan Tucker', '018', 'B', '002');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (356, 200, 'Jared Nelson', '019', 'A', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (357, 200, 'Sara Uvodich', '001', 'A', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (358, 201, 'Dan Quinones', '023', 'A', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (359, 201, 'Mike Dake', '09', 'B', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (360, 202, 'Amy Kazmier', '014', 'B', '009');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (361, 202, 'Kielsen', '002', 'A', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (362, 203, 'Tanner Eccles', '013', 'A', '016');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (363, 203, 'Justin Ritshard', '005', 'B', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (364, 204, 'Hiram Rooper', '010', 'B', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (365, 204, 'Johnson', '006', 'B', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (366, 204, 'Joey Fansler', '016', 'A', '019');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (367, 204, 'Denison', '008', 'B', '013');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (368, 205, 'Johnson', '008', 'A', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (369, 205, 'J Clark', '018', 'B', '009');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (370, 206, 'Hiram Rooper', '009', 'A', '021');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (371, 206, 'Mills', '004', 'A', '017');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (372, 207, 'Sara Brown', '021', 'B', '022');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (373, 207, 'Andrew Hastings', '013', 'B', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (374, 208, 'Tanner Eccles', '014', 'A', '009');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (375, 208, 'Rob Zeilke', '024', 'A', '007');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (376, 209, 'Dax Herrera', '010', 'A', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (377, 209, 'Norm Sealing', '008', 'B', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (378, 210, 'Justin Ritshard', '018', 'A', '005');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (379, 210, 'Sara Uvodich', '013', 'A', '003');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (380, 211, 'Andrew Hastings', '014', 'B', '007');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (381, 211, 'Isiah Jimenez', '021', 'A', '022');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (382, 212, 'Mike Davis', '015', 'B', '012');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (383, 212, 'Rob Zeilke', '022', 'B', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (384, 213, 'Andrew Hastings', '018', 'B', '005');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (385, 213, 'Chrissy Campenelli', '024', 'B', '009');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (386, 214, 'Mike Dake', '023', 'A', '001');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (387, 214, 'Mary Cuddy', '014', 'A', '007');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (388, 215, 'Matt Walch', '025', 'B', '018');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (389, 215, 'Justin Ritshard', '008', 'A', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (390, 216, 'Sara Uvodich', '027', 'A', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (391, 216, 'Garrett Harasek', NULL, NULL, NULL);
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (392, 217, 'Darren Canfield', '022', 'A', '014');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (393, 217, 'Jared Nelson', '010', 'B', '004');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (394, 218, 'Matt Mahoney', '015', 'A', '012');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (395, 218, 'Dan Balling', '006', 'B', '019');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (396, 219, 'Matt Valenti', '005', 'A', '010');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (397, 219, 'Mary Cuddy', '021', 'B', '022');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (398, 220, 'Darren Canfield', '016', 'B', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (399, 220, 'Sara Uvodich', '027', 'B', '017');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (400, 221, 'Mary Cuddy', '008', 'B', '008');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (401, 221, 'Mike Valenti', '024', 'A', '009');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (402, 222, 'Mike Davis', '026', 'B', '011');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (403, 222, 'Simon Driskell', '016', 'A', '015');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (404, 223, 'Dax Herrera', '027', 'A', '017');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (405, 223, 'Kristin Gross', '014', 'B', '022');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (406, 224, 'Evan Hsu', '004', 'A', '020');
INSERT INTO `tbl_Rappeller` (`Rappeller_Number`, `Rappel_Number`, `Rappeller_Name`, `Rope_Number`, `Rope_End`, `Genie_Number`) VALUES (407, 224, 'Chris Fezer', '005', 'B', '007');
# 407 records

#
# Table structure for table 'tbl_RappellerList'
#

DROP TABLE IF EXISTS `tbl_RappellerList`;

CREATE TABLE `tbl_RappellerList` (
  `Rappeller_ID` INTEGER NOT NULL AUTO_INCREMENT, 
  `Rappeller_Name` VARCHAR(50), 
  `Rappeller_BDate` DATETIME, 
  `Rappeller_EDate` DATETIME, 
  PRIMARY KEY (`Rappeller_ID`), 
  INDEX (`Rappeller_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Dumping data for table 'tbl_RappellerList'
#

INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (1, 'Amy Kazmier', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (2, 'Dan Quinones', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (3, 'Norm Sealing', '2004-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (4, 'Isiah Jimenez', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (5, 'Jared Nelson', '2006-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (6, 'Brandi Dutton', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (7, 'Evan Hsu', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (8, 'Sara Uvodich', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (9, 'Mary Cuddy', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (10, 'Mike Dake', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (11, 'Allison Dean', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (12, 'Andrew Hastings', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (13, 'Dax Herrera', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (14, 'Justin Ritshard', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (15, 'Matt Walch', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (16, 'George Yocom', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (17, 'JD Connall', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (18, 'Chris Fezer', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (19, 'Ruben Griego', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (20, 'Ryan Tucker', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (21, 'Jimmy Bickers', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (22, 'Brian Milligan', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (23, 'Tanner Eccles', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (24, 'Griff Williams', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (25, 'Billy Turner', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (26, 'Matt Kernek', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (27, 'Ryan Cherno', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (28, 'Summer Myllymaki', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (29, 'Ben Woodward', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (30, 'Steve Schrock', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (31, 'Nick Chivira', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (32, 'Ethan Marakowski', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (33, 'Matt Lovemark', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (34, 'Sara Brown', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (35, 'Mike Valenti', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (36, 'Kelly Rudger', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (37, 'Rob Zeilke', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (38, 'Darron Canfield', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (39, 'Sebastion Macnab', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (40, 'Rob Kriegbaum', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (41, 'Eric Scholl', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (42, 'Sam Both', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (43, 'Jamey Bachman', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (44, 'Sarah DeMay', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (45, 'Denison', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (46, 'Dave Ortlund', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (47, 'Brandon Nelson', '2008-06-01 00:00:00', '2008-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (48, 'Mike Valeti', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (49, 'Dan Balling', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (50, 'Jimmy Bickers', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (51, 'Ryan Tucker', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (52, 'Leshuk', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (53, 'Joe Cochron', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (54, 'Hiram Rooper', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (55, 'Ebin Babb', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (56, 'Matt Hampton', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (57, 'Joey Fansler', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (58, 'Johnson', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (59, 'Kielsen', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (60, 'J Clark', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (61, 'Mills', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_RappellerList` (`Rappeller_ID`, `Rappeller_Name`, `Rappeller_BDate`, `Rappeller_EDate`) VALUES (62, 'Mike Davis', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
# 62 records

#
# Table structure for table 'tbl_RappelLog'
#

DROP TABLE IF EXISTS `tbl_RappelLog`;

CREATE TABLE `tbl_RappelLog` (
  `Rappel_Number` INTEGER NOT NULL AUTO_INCREMENT, 
  `Entry_Date` DATETIME, 
  `Type` VARCHAR(25), 
  `Location` VARCHAR(25), 
  `N_Number` VARCHAR(7), 
  `Pilot_Name` VARCHAR(50), 
  `Spotter` VARCHAR(50), 
  `Height` INTEGER DEFAULT 0, 
  `Fire_Number` VARCHAR(10), 
  `Remarks` VARCHAR(100), 
  PRIMARY KEY (`Rappel_Number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Dumping data for table 'tbl_RappelLog'
#

INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (1, '2008-06-02 00:00:00', 'Re-Certification', 'John Day Helibase', '73HJ', 'Stefan Drager', 'Court Fent', NULL, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (2, '2008-06-03 00:00:00', 'Re-Certification', 'John Day Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (3, '2008-06-03 00:00:00', 'Re-Certification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (4, '2008-06-03 00:00:00', 'Re-Certification', 'John Day Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (5, '2008-06-04 00:00:00', 'Re-Certification', 'John Day Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (6, '2008-06-05 00:00:00', 'Re-Certification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (7, '2008-06-05 00:00:00', 'Re-Certification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (8, '2008-06-05 00:00:00', 'Re-Certification', 'John Day Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (9, '2008-06-05 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (10, '2008-06-05 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (11, '2008-06-05 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (12, '2008-06-05 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (13, '2008-06-05 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (14, '2008-06-05 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (15, '2008-06-05 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (16, '2008-06-05 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (17, '2008-06-05 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (18, '2008-06-05 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (19, '2008-06-05 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (20, '2008-06-05 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (21, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (22, '2008-06-06 00:00:00', 'Re-Certification', 'Lake Creek Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (23, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (24, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (25, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (26, '2008-06-06 00:00:00', 'Qualification', 'Lake creek Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (27, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (28, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (29, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (30, '2008-06-06 00:00:00', 'Re-Certification', 'John Day Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (31, '2008-06-06 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (32, '2008-06-06 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (33, '2008-06-06 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (34, '2008-06-06 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (35, '2008-06-06 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (36, '2008-06-06 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (37, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (38, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (39, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (40, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (41, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (42, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (43, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (44, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (45, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (46, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (47, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (48, '2008-06-06 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (49, '2008-06-06 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (50, '2008-06-06 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (51, '2008-06-06 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (52, '2008-06-06 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (53, '2008-06-07 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (54, '2008-06-07 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (55, '2008-06-07 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (56, '2008-06-07 00:00:00', 'Qualification', 'John day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (57, '2008-06-07 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (58, '2008-06-07 00:00:00', 'Qualification', 'Jonh Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (59, '2008-06-07 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (60, '2008-06-07 00:00:00', 'Qualification', 'John Day Helibase', 'N21HX', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (61, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '65H', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (62, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '65H', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (63, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '65H', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (64, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '65H', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (65, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (66, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (67, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (68, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (69, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (70, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '65H', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (71, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '65H', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (72, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (73, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (74, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (75, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (76, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '126', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (77, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (78, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (79, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (80, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (81, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (82, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (83, '2008-06-07 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (84, '2008-06-08 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (85, '2008-06-08 00:00:00', 'Qualification', 'Lake Creek Helibase', '73HJ', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (86, '2008-06-08 00:00:00', 'Qualification', 'Lake Creek Helibase', '65H', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (87, '2008-06-08 00:00:00', 'Qualification', 'Lake Creek Helibase', '65H', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (88, '2008-06-08 00:00:00', 'Qualification', 'Lake Creek Helibase', '65H', NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (89, '2008-06-12 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (90, '2008-06-12 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (91, '2008-06-12 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (92, '2008-06-12 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (93, '2008-06-12 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (94, '2008-06-12 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (95, '2008-06-12 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (96, '2008-06-14 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Brad Atkinson', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (97, '2008-06-14 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Brad Atkinson', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (98, '2008-06-14 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Brad Atkinson', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (99, '2008-06-19 00:00:00', '407 Proficiency', 'Pine Ridge HLB, Chiloquin', 'N21HX', 'Stefan Drager', 'Chris Sutherland', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (100, '2008-06-19 00:00:00', '407 Proficiency', 'Pine Ridge HLB, Chiloquin', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (101, '2008-06-19 00:00:00', '407 Proficiency', 'Pine Ridge HLB, Chiloquin', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (102, '2008-06-19 00:00:00', '407 Proficiency', 'Pine Ridge HLB, Chiloquin', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (103, '2008-06-19 00:00:00', '407 Proficiency', 'Pine Ridge HLB, Chiloquin', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (104, '2008-06-19 00:00:00', '407 Proficiency', 'Pine Ridge HLB, Chiloquin', NULL, 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (105, '2008-06-19 00:00:00', '407 Proficiency', 'Pine Ridge HLB, Chiloquin', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (106, '2008-06-19 00:00:00', '407 Proficiency', 'Pine Ridge HLB, Chiloquin', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (107, '2008-06-22 00:00:00', '407 Proficiency', 'Aerodome, Chiloquin', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (108, '2008-06-22 00:00:00', '407 Proficiency', 'Aerodome, Chiloquin', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (109, '2008-06-22 00:00:00', '407 Proficiency', 'Aerodome, Chiloquin', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (110, '2008-06-22 00:00:00', '407 Proficiency', 'Aerodome, Chiloquin', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (111, '2008-06-22 00:00:00', '407 Proficiency', 'Aerodome, Chiloquin', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (112, '2008-06-22 00:00:00', '407 Proficiency', 'Aerodome, Chiloquin', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (113, '2008-06-22 00:00:00', '407 Proficiency', 'Aerodome, Chiloquin', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (114, '2008-06-26 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (115, '2008-06-26 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (116, '2008-06-26 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (117, '2008-06-26 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (118, '2008-06-26 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (119, '2008-06-26 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (120, '2008-06-28 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (121, '2008-06-28 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (122, '2008-06-28 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Eric Bush', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (123, '2008-07-02 00:00:00', 'Operational', 'Deschutes NF', 'N21HX', 'Larry Doll', 'Griff Williams', 0, 'Inc #259', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (124, '2008-07-03 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Griff Williams', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (125, '2008-07-03 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Griff Williams', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (126, '2008-07-03 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Griff Williams', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (127, '2008-07-03 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Griff Williams', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (128, '2008-07-03 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Griff Williams', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (129, '2008-07-03 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (130, '2008-07-07 00:00:00', 'Operational', 'Deschutes NF', 'N21HX', 'Stefan Drager', 'Griff Williams', 0, 'Inc #314', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (131, '2008-07-08 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Griff Williams', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (132, '2008-07-08 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Griff Williams', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (133, '2008-07-11 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Griff Williams', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (134, '2008-07-11 00:00:00', 'Operational', 'Umpqua NF', 'N21HX', 'Stefan Drager', 'Griff Williams', 0, 'Inc #8041', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (135, '2008-07-12 00:00:00', 'Operational', 'Umpqua NF', 'N21HX', 'Stefan Drager', 'Griff Williams', 0, 'Inc #8041', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (136, '2008-07-13 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Brandon Culley', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (137, '2008-07-13 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Brandon Culley', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (138, '2008-07-14 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (139, '2008-07-14 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (140, '2008-07-14 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Billy Turner', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (141, '2008-07-10 00:00:00', 'Operational', 'Deschutes NF', 'N21HX', 'Stefan Drager', 'Griff Williams', 0, 'DEF Inc #3', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (142, '2008-07-12 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Billy Turner', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (143, '2008-07-21 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (144, '2008-07-21 00:00:00', 'Operational', 'Deschutes NF', 'N21HX', 'Stefan Drager', 'Billy Turner', 0, 'Inc #380', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (145, '2008-07-21 00:00:00', 'Operational', 'Deschutes NF', 'N21HX', 'Stefan Drager', 'Billy Turner', 0, 'Inc #381', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (146, '2008-07-24 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (147, '2008-07-24 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (148, '2008-07-24 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Billy Turner', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (149, '2008-07-24 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Billy Turner', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (150, '2008-07-24 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Kelly Rudger', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (151, '2008-06-12 00:00:00', '407 Proficiency', 'Big Summit', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (152, '2008-06-12 00:00:00', '407 Proficiency', 'Big Summit', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (153, '2008-06-12 00:00:00', '407 Proficiency', 'Big Summit', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (154, '2008-06-12 00:00:00', '407 Proficiency', 'Big Summit', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (155, '2008-06-12 00:00:00', '407 Proficiency', 'Big Summit', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (156, '2008-06-12 00:00:00', '407 Proficiency', 'Big Summit', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (157, '2008-06-12 00:00:00', '407 Proficiency', 'Big Summit', 'N21HX', 'Stefan Drager', 'JD Connall', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (158, '2008-07-23 00:00:00', 'Operational', 'Deschutes NF', 'N21HX', 'Stefan Drager', 'Billy Turner', 0, 'Inc #401', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (159, '2008-07-25 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Kelly Rudger', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (160, '2008-07-25 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Kelly Rudger', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (161, '2008-07-25 00:00:00', '407 Proficiency', 'Prineville Helibase', 'N21HX', 'Stefan Drager', 'Kelly Rudger', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (162, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (163, '2008-07-30 00:00:00', '407 Proficiency', 'P-Ville', 'N21HX', 'Larry Doll', 'Kelly Rudger', NULL, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (164, '2008-07-31 00:00:00', '407 Proficiency', 'P-Ville', 'N21HX', 'Stefan Drager', 'Kelly Rudger', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (165, '2008-07-31 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (166, '2008-08-03 00:00:00', 'Operational', NULL, '3KA', 'Wagstaff', 'Griff Williams', 0, '210', 'sled');
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (167, '2008-07-31 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (168, '2008-07-31 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (169, '2008-08-01 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Stefan Drager', 'Kelly Rudger', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (170, '2008-08-01 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Stefan Drager', 'Jason Lyman', 0, NULL, 'sled gear');
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (171, '2008-08-01 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Stefan Drager', 'Kelly Rudger', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (172, '2008-08-04 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Stefan Drager', 'Eric Scholl', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (173, '2008-08-04 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Stefan Drager', 'Kelly Rudger', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (174, '2008-08-07 00:00:00', 'Operational', NULL, 'N21HX', 'Stefan Drager', 'Eric Scholl', 0, '548', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (175, '2008-08-10 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Stefan Drager', 'Eric Scholl', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (176, '2008-08-10 00:00:00', 'Operational', NULL, '73HJ', 'Tom Angstadt', 'Chris Southerland', 0, '496', 'wenatchee');
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (177, '2008-08-12 00:00:00', 'Operational', NULL, '73HJ', 'Tom Angstadt', 'Chris Sutherland', 0, '513', 'wenatchee');
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (178, '2008-08-12 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Joe James', 'Eric Scholl', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (179, '2008-08-12 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Joe James', 'Eric Scholl', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (180, '2008-08-12 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Joe James', 'Eric Scholl', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (181, '2008-08-12 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Joe James', 'Eric Scholl', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (182, '2008-08-13 00:00:00', 'Operational', NULL, 'N21HX', 'Joe James', 'Brandon Culley', 0, '148 WNF', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (183, '2008-08-13 00:00:00', 'Operational', NULL, 'N21HX', 'Joe James', 'Brandon Culley', 0, '148', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (184, '2008-08-13 00:00:00', 'Operational', NULL, 'N21HX', 'Joe James', 'Brandon Culley', 0, '148', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (185, '2008-08-13 00:00:00', 'Operational', NULL, 'N21HX', 'Joe James', 'Brandon Culley', 0, '148', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (186, '2008-08-13 00:00:00', 'Operational', NULL, 'N21HX', 'Joe James', 'Brandon Culley', 0, '148', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (187, '2008-08-14 00:00:00', 'Operational', NULL, '73HJ', 'Tom Angstadt', 'Chris Sutherland', 0, '523', 'wenatchee');
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (188, '2008-08-18 00:00:00', 'Operational', NULL, 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, '778', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (189, '2008-08-18 00:00:00', 'Operational', NULL, 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, '772', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (190, '2008-08-18 00:00:00', 'Operational', NULL, 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, '780', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (191, '2008-08-19 00:00:00', 'Operational', NULL, 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (192, '2008-08-20 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (193, '2008-08-20 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Stefan Drager', 'Joe Cochron', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (194, '2008-08-20 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Stefan Drager', 'Joe Cochron', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (195, '2008-08-21 00:00:00', 'Operational', NULL, 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, '8189', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (196, '2008-08-21 00:00:00', 'A-Star Prof.', NULL, '7HE', 'Clayton', 'Steve Markenson', 0, NULL, 'teton');
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (197, '2008-08-21 00:00:00', 'A-Star Prof.', NULL, '7HE', 'Clayton', 'Steve Markenson', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (198, '2008-08-21 00:00:00', 'A-Star Prof.', NULL, '7HE', 'C. Mitchell', 'Steve Markenson', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (199, '2008-08-21 00:00:00', 'Operational', NULL, 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, '8174', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (200, '2008-08-21 00:00:00', 'A-Star Prof.', NULL, '7HE', 'Clayton', 'Steve Markenson', 0, NULL, 'Uvodich-teton gear');
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (201, '2008-08-22 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (202, '2008-08-22 00:00:00', 'A-Star Prof.', NULL, '7HE', 'C. Mitchell', 'Steve Markenson', 0, NULL, 'Kielsen-teton gear');
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (203, '2008-08-22 00:00:00', 'A-Star Prof.', NULL, '7HE', 'C. Mitchell', 'Steve Markenson', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (204, '2008-08-23 00:00:00', 'Operational', NULL, 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, '8189', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (205, '2008-08-24 00:00:00', 'Operational', NULL, 'N21HX', 'Stefan Drager', 'Court Fent', 0, '8215', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (206, '2008-08-24 00:00:00', 'Operational', NULL, 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, '8213', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (207, '2008-08-25 00:00:00', 'A-Star Prof.', NULL, '7HE', 'Clayton', 'Tracy Stull', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (208, '2008-08-25 00:00:00', 'A-Star Prof.', NULL, '7HE', 'Matt Hart', 'Steve Markenson', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (209, '2008-08-27 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Larry Doll', 'Joe Cochron', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (210, '2008-08-27 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Larry Doll', 'Joe Cochron', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (211, '2008-08-27 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Larry Doll', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (212, '2008-08-27 00:00:00', '407 Proficiency', NULL, 'N21HX', 'Larry Doll', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (213, '2008-09-16 00:00:00', '407 Proficiency', 'Glide Helibase', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (214, '2008-09-16 00:00:00', '407 Proficiency', 'Glide Helibase', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (215, '2008-09-16 00:00:00', '407 Proficiency', 'Glide Helibase', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (216, '2008-09-16 00:00:00', '407 Proficiency', 'Glide Helibase', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (217, '2008-09-16 00:00:00', '407 Proficiency', 'Glide Helibase', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (218, '2008-09-16 00:00:00', '407 Proficiency', 'Glide Helibase', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (219, '2008-09-16 00:00:00', '407 Proficiency', 'Glide Helibase', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (220, '2008-09-16 00:00:00', 'Operational', 'Diamnod Lake RD', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, 'UPF #8236', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (221, '2008-09-17 00:00:00', 'Operational', 'Diamond Lake RD', 'N21HX', 'Stefan Drager', 'Norm Sealing', 0, 'UPF #8236', NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (222, '2008-09-22 00:00:00', '407 Proficiency', 'Tokotee Helibase', 'N21HX', 'Stefan Drager', 'Garrett Harasek', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (223, '2008-09-22 00:00:00', '407 Proficiency', 'Tokotee Helibase', 'N21HX', 'Stefan Drager', 'Garrett Harasek', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (224, '2008-09-22 00:00:00', '407 Proficiency', 'Tokotee Helibase', 'N21HX', 'Stefan Drager', 'Garrett Harasek', 0, NULL, NULL);
INSERT INTO `tbl_RappelLog` (`Rappel_Number`, `Entry_Date`, `Type`, `Location`, `N_Number`, `Pilot_Name`, `Spotter`, `Height`, `Fire_Number`, `Remarks`) VALUES (225, '2009-01-28 00:00:00', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
# 225 records

#
# Table structure for table 'tbl_RopeList'
#

DROP TABLE IF EXISTS `tbl_RopeList`;

CREATE TABLE `tbl_RopeList` (
  `Rope_Number` VARCHAR(8) NOT NULL, 
  `Rope_BDate` DATETIME, 
  `Rope_EDate` DATETIME, 
  PRIMARY KEY (`Rope_Number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Dumping data for table 'tbl_RopeList'
#

INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('001', '2008-01-01 00:00:00', '2013-01-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('002', '2008-01-01 00:00:00', '2013-01-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('003', '2008-01-01 00:00:00', '2008-06-12 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('004', '2008-02-01 00:00:00', '2013-02-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('005', '2008-02-01 00:00:00', '2013-02-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('006', '2008-02-01 00:00:00', '2013-02-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('007', '2008-02-01 00:00:00', '2013-02-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('008', '2008-02-01 00:00:00', '2013-02-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('009', '2008-02-01 00:00:00', '2013-02-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('010', '2008-02-01 00:00:00', '2013-02-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('011', '2008-01-01 00:00:00', '2013-01-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('012', '2008-02-01 00:00:00', '2013-02-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('013', '2008-02-01 00:00:00', '2013-02-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('014', '2008-02-01 00:00:00', '2013-02-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('015', '2008-02-01 00:00:00', '2013-02-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('016', '2008-01-01 00:00:00', '2013-01-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('017', '2008-02-01 00:00:00', '2013-02-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('018', '2008-02-01 00:00:00', '2013-02-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('019', '2008-01-01 00:00:00', '2013-01-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('020', '2008-01-01 00:00:00', '2013-01-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('021', '2008-01-01 00:00:00', '2013-01-01 00:00:00');
INSERT INTO `tbl_RopeList` (`Rope_Number`, `Rope_BDate`, `Rope_EDate`) VALUES ('022', '2008-01-01 00:00:00', '2013-01-01 00:00:00');
# 22 records

#
# Table structure for table 'tbl_SpotterList'
#

DROP TABLE IF EXISTS `tbl_SpotterList`;

CREATE TABLE `tbl_SpotterList` (
  `Spotter_ID` INTEGER NOT NULL AUTO_INCREMENT, 
  `Spotter_Name` VARCHAR(50), 
  `Spotter_BDate` DATETIME, 
  `Spotter_EDate` DATETIME, 
  PRIMARY KEY (`Spotter_ID`), 
  INDEX (`Spotter_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Dumping data for table 'tbl_SpotterList'
#

INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (1, 'Norm Sealing', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (2, 'JD Connall', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (3, 'Eric Bush', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (4, 'Griff Williams', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (5, 'Billy Turner', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (6, 'Chris Sutherland', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (7, 'Brandon Culley', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (8, 'Kelly Rudger', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (9, 'Jason Lyman', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (10, 'Eric Scholl', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (11, 'Joe Cochron', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (12, 'Steve Markenson', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (13, 'Court Fent', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_SpotterList` (`Spotter_ID`, `Spotter_Name`, `Spotter_BDate`, `Spotter_EDate`) VALUES (14, 'Tracy Stull', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
# 14 records

#
# Table structure for table 'tbl_TypeList'
#

DROP TABLE IF EXISTS `tbl_TypeList`;

CREATE TABLE `tbl_TypeList` (
  `Type_Number` INTEGER NOT NULL AUTO_INCREMENT, 
  `Type` VARCHAR(20), 
  `Type_BDate` DATETIME, 
  `Type_EDate` DATETIME, 
  PRIMARY KEY (`Type_Number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Dumping data for table 'tbl_TypeList'
#

INSERT INTO `tbl_TypeList` (`Type_Number`, `Type`, `Type_BDate`, `Type_EDate`) VALUES (1, 'Qualification', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_TypeList` (`Type_Number`, `Type`, `Type_BDate`, `Type_EDate`) VALUES (2, 'Medium Proficiency', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_TypeList` (`Type_Number`, `Type`, `Type_BDate`, `Type_EDate`) VALUES (3, 'Operational', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_TypeList` (`Type_Number`, `Type`, `Type_BDate`, `Type_EDate`) VALUES (4, '407 Proficiency', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_TypeList` (`Type_Number`, `Type`, `Type_BDate`, `Type_EDate`) VALUES (5, 'Re-Certification', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_TypeList` (`Type_Number`, `Type`, `Type_BDate`, `Type_EDate`) VALUES (6, 'A-Star Prof.', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
INSERT INTO `tbl_TypeList` (`Type_Number`, `Type`, `Type_BDate`, `Type_EDate`) VALUES (7, 'A-Star Op.', '2008-06-01 00:00:00', '2009-01-01 00:00:00');
# 7 records

