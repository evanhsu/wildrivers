
-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: 97.74.149.194
-- Generation Time: Sep 21, 2015 at 07:38 AM
-- Server version: 5.0.96
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `siskiyou_general`
--

-- --------------------------------------------------------

--
-- Table structure for table `apparel`
--

CREATE TABLE `apparel` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `image_filename` varchar(250) NOT NULL,
  `image_thumb_filename` varchar(250) NOT NULL,
  `description` varchar(250) default NULL,
  `size` varchar(25) NOT NULL,
  `type` varchar(25) NOT NULL,
  `price` float NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1002 ;

-- --------------------------------------------------------

--
-- Table structure for table `apparel_orders`
--

CREATE TABLE `apparel_orders` (
  `id` int(11) NOT NULL auto_increment,
  `order_number` varchar(50) NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `item_name` varchar(50) NOT NULL,
  `item_description` varchar(200) NOT NULL,
  `item_type` varchar(50) NOT NULL,
  `item_size` varchar(50) NOT NULL,
  `item_price` decimal(10,0) NOT NULL,
  `item_qty` int(11) NOT NULL,
  `qty_for_free` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=296 ;

-- --------------------------------------------------------

--
-- Table structure for table `authentication`
--

CREATE TABLE `authentication` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(25) default NULL,
  `password` varchar(32) default NULL,
  `real_name` varchar(50) default NULL,
  `access_level` varchar(500) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- Table structure for table `chuck_norris_facts`
--

CREATE TABLE `chuck_norris_facts` (
  `id` smallint(6) NOT NULL auto_increment,
  `fact` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=132 ;

-- --------------------------------------------------------

--
-- Table structure for table `costs`
--

CREATE TABLE `costs` (
  `id` int(11) NOT NULL auto_increment,
  `Acres_TreatedRow1` int(11) default NULL,
  `Additional_Cost` float default NULL,
  `Additional_Cost_Rate` float default NULL,
  `Additional_Cost_Total` float default NULL,
  `Additional_Cost_2` float default NULL,
  `Additional_Cost_2_Rate` float default NULL,
  `Additional_Cost_2_Total` float default NULL,
  `Additional_Cost_3` float default NULL,
  `Additional_Cost_3_Rate` float default NULL,
  `Additional_Cost_3_Total` float default NULL,
  `Additional_Cost_4` float default NULL,
  `Additional_Cost_4_Rate` float default NULL,
  `Additional_Cost_4_Total` float default NULL,
  `Agency` varchar(15) default NULL,
  `AgencyRow1` varchar(15) default NULL,
  `AgencyRow2` varchar(15) default NULL,
  `AgencyRow3` varchar(15) default NULL,
  `AgencyRow4` varchar(15) default NULL,
  `Availability_Cost` float default NULL,
  `Availability_Hours_or_Day` float default NULL,
  `Availability_Rate` float default NULL,
  `CWN` tinyint(4) default NULL,
  `CostRow1` float default NULL,
  `CostRow2` float default NULL,
  `CostRow3` float default NULL,
  `CostRow4` float default NULL,
  `Daily_Grand_Total_Cost` float default NULL,
  `Date` date NOT NULL,
  `Driver_Extended_Standby` float default NULL,
  `Driver_Extended_Standby_Cost` float default NULL,
  `Driver_Extended_Standby_Rate` float default NULL,
  `Exclusive_Use_Checkbox` tinyint(4) default NULL,
  `Flight_Invoice_Reference_Numbers` varchar(30) default NULL,
  `Gallons_Helitorch_Gel_UsedRow1` float default NULL,
  `Helibase` varchar(30) default NULL,
  `Incident` varchar(30) default NULL,
  `Initial_Attack_Checkbox` tinyint(4) default NULL,
  `Large_Fire_Checkbox` tinyint(4) default NULL,
  `MakeModel` varchar(30) default NULL,
  `Managers_Name` varchar(40) default NULL,
  `Mechanic_Extended_Standby` float default NULL,
  `Mechanic_Extended_Standby_Cost` float default NULL,
  `Mechanic_Extended_Standby_Rate` float default NULL,
  `Mileage_Cost` float default NULL,
  `Mileage_Rate` float default NULL,
  `N` varchar(10) NOT NULL,
  `Other_Specify` varchar(30) default NULL,
  `PSD_Spheres_UsedRow1` float default NULL,
  `Per_Diem__of_Persons` tinyint(4) default NULL,
  `Per_Diem_Cost` float default NULL,
  `Per_Diem_Rate` float default NULL,
  `PercentRow1` float default NULL,
  `PercentRow2` float default NULL,
  `PercentRow3` float default NULL,
  `PercentRow4` float default NULL,
  `Pilot_Extended_Standby` float default NULL,
  `Pilot_Extended_Standby_Cost` float default NULL,
  `Pilot_Extended_Standby_Rate` float default NULL,
  `Project_Checkbox` tinyint(4) default NULL,
  `Revenue_Flight_Hour_Cost` float default NULL,
  `Revenue_Flight_Hour_Rate` float default NULL,
  `Revenue_Flight_Hours` float default NULL,
  `Service_Truck_Miles` float default NULL,
  `Total_Gallons_FoamRow1` float default NULL,
  `Total_Gallons_RetardantRow1` float default NULL,
  `Total_Gallons_WaterRow1` float default NULL,
  `Total_PAX_TransportedRow1` tinyint(4) default NULL,
  `Total_Pounds_CargoRow1` float default NULL,
  `Type_1` tinyint(4) default NULL,
  `Type_2` tinyint(4) default NULL,
  `Type_3` tinyint(4) default NULL,
  `Type_other` tinyint(4) default NULL,
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `form_version` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `Date` (`Date`),
  KEY `N` (`N`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `crewmembers`
--

CREATE TABLE `crewmembers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `firstname` varchar(25) default NULL,
  `lastname` varchar(25) default NULL,
  `headshot_filename` varchar(128) default NULL,
  `bio` varchar(1024) default NULL,
  `phone` varchar(14) default NULL,
  `email` varchar(128) default NULL,
  `street1` varchar(128) default NULL,
  `street2` varchar(128) default NULL,
  `city` varchar(128) default NULL,
  `state` varchar(2) default NULL,
  `zip` varchar(5) default NULL,
  `has_purchase_card` tinyint(4) NOT NULL default '0' COMMENT '0:No, 1:Yes',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=90 ;

-- --------------------------------------------------------

--
-- Table structure for table `current`
--

CREATE TABLE `current` (
  `name` varchar(50) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` text,
  PRIMARY KEY  (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `current_sticky`
--

CREATE TABLE `current_sticky` (
  `status` text,
  `date` datetime NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY  (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `id` int(11) NOT NULL auto_increment,
  `student_id` int(11) NOT NULL COMMENT 'The ID of the person attending',
  `scheduled_course_id` int(11) NOT NULL COMMENT 'The ID of the scheduled_course',
  `status` varchar(25) NOT NULL default 'nominated',
  `certificate_received` enum('no','yes','n/a') NOT NULL default 'no',
  `cost_tuition` float NOT NULL default '0',
  `cost_wages` float NOT NULL default '0' COMMENT 'Employee wages paid',
  `prework_received` enum('no','yes','n/a') NOT NULL default 'no',
  `payment_method` varchar(50) default NULL,
  `charge_code` varchar(6) default NULL,
  `override` varchar(4) default NULL,
  `travel_paid` tinyint(4) NOT NULL default '0' COMMENT '0:No, 1:Yes - Has the employee been reimbursed for travel expenses?',
  `cost_travel` float NOT NULL default '0',
  `cost_misc` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `flighthours`
--

CREATE TABLE `flighthours` (
  `id` int(11) NOT NULL default '0',
  `month` int(11) default NULL,
  `year` int(11) default NULL,
  `hours` double default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `idx` int(11) NOT NULL auto_increment,
  `date` datetime default NULL,
  `event_type` varchar(2) default NULL,
  `number` varchar(40) default NULL,
  `name` varchar(50) default NULL,
  `code` varchar(10) default NULL,
  `override` varchar(10) default NULL,
  `size` float(10,2) default NULL,
  `type` tinyint(3) unsigned default NULL,
  `fuel_models` varchar(25) default NULL,
  `description` text,
  `latitude_degrees` decimal(10,0) default NULL,
  `latitude_minutes` decimal(10,4) default NULL,
  `latitude_seconds` decimal(10,4) default NULL,
  `longitude_degrees` decimal(10,0) default NULL,
  `longitude_minutes` decimal(10,4) default NULL,
  `longitude_seconds` decimal(10,4) default NULL,
  `g_cal_eventUrl` varchar(100) default NULL,
  PRIMARY KEY  (`idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=311 ;

-- --------------------------------------------------------

--
-- Table structure for table `incident_files`
--

CREATE TABLE `incident_files` (
  `id` int(11) NOT NULL auto_increment,
  `incident_id` int(11) NOT NULL,
  `file_path` varchar(250) NOT NULL,
  `file_description` varchar(250) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Table structure for table `incident_roster`
--

CREATE TABLE `incident_roster` (
  `idx` int(11) default NULL,
  `crewmember_id` int(10) unsigned default NULL,
  `firstname` varchar(20) default NULL,
  `lastname` varchar(20) default NULL,
  `role` varchar(4) default NULL,
  `qt` varchar(1) default NULL,
  `shifts` tinyint(4) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL auto_increment,
  `serial_no` varchar(25) default NULL,
  `quantity` int(11) default NULL COMMENT 'Quantity of this item in stock (for consumable items)',
  `item_type` varchar(25) default NULL,
  `color` varchar(25) default NULL,
  `size` varchar(15) default NULL,
  `description` varchar(255) default NULL,
  `item_condition` varchar(255) default NULL,
  `checked_out_to_id` int(11) default '-1',
  `note` varchar(255) default NULL,
  `usable` tinyint(1) default '1',
  `restock_trigger` int(11) default NULL COMMENT 'Minimum stock quantity which triggers a restock purchase',
  `restock_to_level` int(11) default NULL COMMENT 'Desired stock quantity after a restock purchase',
  `item_source` varchar(500) default NULL COMMENT 'A reference number or web address to order more of this item',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2740 ;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_history`
--

CREATE TABLE `inventory_history` (
  `idx` int(11) NOT NULL auto_increment,
  `item_id` int(11) default NULL,
  `attribute` varchar(25) default NULL,
  `old_value` varchar(255) default NULL,
  `new_value` varchar(255) default NULL,
  `changed_by` varchar(50) default NULL,
  `date` datetime default NULL,
  PRIMARY KEY  (`idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12287 ;

-- --------------------------------------------------------

--
-- Table structure for table `job_vacancies`
--

CREATE TABLE `job_vacancies` (
  `name` varchar(50) NOT NULL,
  `date` datetime NOT NULL,
  `text` text,
  PRIMARY KEY  (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `paychecks`
--

CREATE TABLE `paychecks` (
  `id` int(11) NOT NULL auto_increment,
  `crewmember_id` int(11) NOT NULL,
  `payperiod` tinyint(4) default NULL,
  `year` mediumint(9) default NULL,
  `modified_by` varchar(30) default NULL,
  `status` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE `people` (
  `id` int(11) NOT NULL auto_increment,
  `iqcs_num` varchar(20) default NULL COMMENT 'A firefighter employee ID (Redcard Number)',
  `firstname` varchar(30) default NULL,
  `lastname` varchar(30) default NULL,
  `address_home_street_1` varchar(50) default NULL,
  `address_home_street_2` varchar(50) default NULL,
  `address_home_city` varchar(50) default NULL,
  `address_home_state` varchar(2) default NULL,
  `address_home_zip` varchar(10) default NULL,
  `address_work_street_1` varchar(50) default NULL,
  `address_work_street_2` varchar(50) default NULL,
  `address_work_city` varchar(50) default NULL,
  `address_work_state` varchar(2) default NULL,
  `address_work_zip` varchar(10) default NULL,
  `email` varchar(100) default NULL,
  `phone_personal_cell` varchar(25) default NULL,
  `phone_home` varchar(25) default NULL,
  `phone_work` varchar(25) default NULL,
  `phone_work_cell` varchar(25) default NULL,
  `fax` varchar(25) default NULL,
  `gender` enum('male','female') default NULL,
  `birthdate` date default NULL,
  `facebook_username` varchar(100) default NULL,
  `facebook_password` varchar(40) default NULL,
  `username` varchar(25) default NULL,
  `password` varchar(40) default NULL,
  `privileges` varchar(500) default NULL COMMENT 'A CSV list of webpages that this person has access to',
  `headshot_filename` varchar(150) default NULL,
  `has_purchase_card` tinyint(4) NOT NULL default '0' COMMENT '0:No, 1:Yes',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Table structure for table `person_quals`
--

CREATE TABLE `person_quals` (
  `id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `qualification_id` int(11) NOT NULL,
  `qualification_code` varchar(4) default NULL COMMENT 'e.g. "FFT1"',
  `trainee` tinyint(4) NOT NULL default '0' COMMENT '0:No, 1:Yes',
  `date_initial` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL auto_increment,
  `path` varchar(256) NOT NULL,
  `thumbpath` varchar(256) NOT NULL,
  `caption` varchar(512) default NULL,
  `year` smallint(5) unsigned NOT NULL,
  `height` smallint(6) default NULL,
  `width` smallint(6) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=326 ;

-- --------------------------------------------------------

--
-- Table structure for table `photo_of_the_week`
--

CREATE TABLE `photo_of_the_week` (
  `id` int(11) NOT NULL auto_increment,
  `path` varchar(256) NOT NULL,
  `thumbpath` varchar(256) NOT NULL,
  `photographer` varchar(100) NOT NULL,
  `location` varchar(256) NOT NULL,
  `description` varchar(256) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `last_date_used` date default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `qualifications`
--

CREATE TABLE `qualifications` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(4) NOT NULL,
  `title` varchar(150) NOT NULL,
  `refresher_course_id` int(11) default NULL COMMENT 'The ID of the class needed to renew this qualification',
  `supercedes` int(11) default NULL COMMENT 'The ID of the qualification which is REPLACED by this one',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `requisitions`
--

CREATE TABLE `requisitions` (
  `id` int(11) NOT NULL auto_increment,
  `vendor_info` text,
  `description` varchar(500) default NULL,
  `amount` float NOT NULL default '0',
  `date` date NOT NULL,
  `card_used` varchar(25) NOT NULL,
  `attachment1` varchar(250) default NULL COMMENT 'File attachment path',
  `attachment2` varchar(250) default NULL,
  `attachment3` varchar(250) default NULL,
  `comments` varchar(500) default NULL,
  `added_by` varchar(30) default NULL,
  `approved_by` varchar(30) default NULL,
  `priority` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1835 ;

-- --------------------------------------------------------

--
-- Table structure for table `requisitions_split`
--

CREATE TABLE `requisitions_split` (
  `id` int(11) NOT NULL auto_increment,
  `requisition_id` int(11) NOT NULL,
  `s_number` varchar(10) default NULL,
  `charge_code` varchar(6) NOT NULL,
  `override` varchar(4) NOT NULL,
  `amount` float NOT NULL,
  `received` varchar(10) default NULL COMMENT '''CHECKED'':This order has been physically received. [BLANK]:This has been ordered but not yet received.',
  `reconciled` varchar(10) default NULL COMMENT '''CHECKED'':This entry has been reconciled with a bank statement',
  `comments` varchar(500) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4758 ;

-- --------------------------------------------------------

--
-- Table structure for table `roster`
--

CREATE TABLE `roster` (
  `id` int(10) unsigned NOT NULL COMMENT 'id of crewmember',
  `year` mediumint(8) unsigned NOT NULL COMMENT 'Year of membership',
  `person_id` int(11) NOT NULL,
  `crew_id` int(11) NOT NULL,
  `bio` varchar(500) default NULL COMMENT 'A mini-biography that can be changed every year while retaining older versions'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Group crewmembers by membership year';

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_courses`
--

CREATE TABLE `scheduled_courses` (
  `id` int(11) NOT NULL auto_increment,
  `date_start` datetime NOT NULL COMMENT 'Date & time of the first class session',
  `date_end` date default NULL COMMENT 'Ending date of the course',
  `location` varchar(250) default NULL,
  `training_facility` int(11) default NULL,
  `name` varchar(25) NOT NULL COMMENT 'e.g. "S-212"',
  `g_cal_eventUrl` varchar(100) default NULL,
  `comments` varchar(250) default NULL COMMENT 'Any additional information',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL auto_increment,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_facilities`
--

CREATE TABLE `training_facilities` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(250) NOT NULL,
  `street` varchar(250) default NULL,
  `city` varchar(250) default NULL,
  `state` varchar(2) default NULL,
  `zip` varchar(10) default NULL,
  `contact_name` varchar(100) default NULL,
  `contact_phone` varchar(20) default NULL,
  `contact_email` varchar(250) default NULL,
  `notes` varchar(500) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `vip`
--

CREATE TABLE `vip` (
  `name` varchar(60) NOT NULL,
  `contact` varchar(256) NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
