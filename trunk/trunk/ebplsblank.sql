CREATE DATABASE ebpls;
USE ebpls;

DROP TABLE IF EXISTS `admin_board`;
CREATE TABLE IF NOT EXISTS `admin_board` (
  `rowid` int(11) NOT NULL auto_increment,
  `username` varchar(30) default NULL,
  `msg_date` datetime default NULL,
  `message` text,
  PRIMARY KEY  (`rowid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `admin_board`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `authen`
-- 

DROP TABLE IF EXISTS `authen`;
CREATE TABLE IF NOT EXISTS `authen` (
  `rowid` int(10) NOT NULL auto_increment,
  `username` varchar(30) default NULL,
  `passwd` varchar(50) default NULL,
  `type` varchar(30) default NULL,
  `owner_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`rowid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `authen`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `backups`
-- 

DROP TABLE IF EXISTS `backups`;
CREATE TABLE IF NOT EXISTS `backups` (
  `id` int(4) NOT NULL auto_increment,
  `backuptime` datetime NOT NULL default '0000-00-00 00:00:00',
  `data` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `backups`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `biz_application`
-- 

DROP TABLE IF EXISTS `biz_application`;
CREATE TABLE IF NOT EXISTS `biz_application` (
  `rowid` int(10) NOT NULL auto_increment,
  `username` varchar(20) default NULL,
  `bizname` varchar(100) default NULL,
  `biztype` varchar(50) default NULL,
  `bizstreet` varchar(60) default NULL,
  `bizbarangay` varchar(60) default NULL,
  `bizzone` varchar(20) default NULL,
  `bizdistrict` varchar(20) default NULL,
  `bizcity` varchar(30) default NULL,
  `bizprovince` varchar(30) default NULL,
  `biztel` varchar(30) default NULL,
  `app_date` datetime default NULL,
  `app_status_code` varchar(5) default NULL,
  `stat_date` datetime default NULL,
  PRIMARY KEY  (`rowid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `biz_application`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `biz_fee`
-- 

DROP TABLE IF EXISTS `biz_fee`;
CREATE TABLE IF NOT EXISTS `biz_fee` (
  `biz_id` int(3) NOT NULL auto_increment,
  `biz_desc` varchar(255) default NULL,
  `biz_type` int(1) default NULL,
  `biz_form` varchar(20) default NULL,
  `biz_cons` decimal(12,2) default NULL,
  `biz_trans` varchar(10) default NULL,
  `add_date` datetime default NULL,
  `input_by` varchar(20) default NULL,
  `active` int(1) default '1',
  PRIMARY KEY  (`biz_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `biz_fee`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `biz_range`
-- 

DROP TABLE IF EXISTS `biz_range`;
CREATE TABLE IF NOT EXISTS `biz_range` (
  `range_id` int(3) NOT NULL auto_increment,
  `biz_id` int(3) default NULL,
  `range_low` varchar(20) default NULL,
  `range_high` varchar(20) default NULL,
  `range_amt` varchar(20) default NULL,
  PRIMARY KEY  (`range_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `biz_range`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `boat_fee`
-- 

DROP TABLE IF EXISTS `boat_fee`;
CREATE TABLE IF NOT EXISTS `boat_fee` (
  `fee_id` int(10) NOT NULL auto_increment,
  `boat_type` int(11) default NULL,
  `range_lower` int(10) default NULL,
  `range_higher` int(10) default NULL,
  `unit_measure` varchar(50) default NULL,
  `amt` decimal(12,2) default NULL,
  `transaction` varchar(10) NOT NULL default '',
  `active` int(1) NOT NULL default '0',
  `fee_type` int(1) NOT NULL default '0',
  PRIMARY KEY  (`fee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `boat_fee`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `bus_fees_paid`
-- 

DROP TABLE IF EXISTS `bus_fees_paid`;
CREATE TABLE IF NOT EXISTS `bus_fees_paid` (
  `fee_id` int(10) NOT NULL auto_increment,
  `owner_id` int(10) NOT NULL default '0',
  `business_id` int(10) NOT NULL default '0',
  `tfoid` int(10) default NULL,
  `amt_paid` decimal(12,2) default NULL,
  `date_paid` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`fee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `bus_fees_paid`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `bus_grandamt`
-- 

DROP TABLE IF EXISTS `bus_grandamt`;
CREATE TABLE IF NOT EXISTS `bus_grandamt` (
  `gid` int(71) NOT NULL auto_increment,
  `owner_id` int(10) default NULL,
  `business_id` int(10) default NULL,
  `grandamt` decimal(12,2) default NULL,
  `totpenamt` decimal(12,2) default NULL,
  `si` decimal(12,2) default NULL,
  `penamt` decimal(12,2) default NULL,
  `origtax` decimal(12,2) default NULL,
  `penalty_acntcode` varchar(20) default NULL,
  `active` int(1) default '0',
  `transaction` varchar(20) default 'New',
  `waive` decimal(10,2) NOT NULL default '0.00',
  `ts` varchar(4) NOT NULL default '',
  `paymode` varchar(50) NOT NULL default '',
  `paypart` int(1) NOT NULL default '0',
  PRIMARY KEY  (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `bus_grandamt`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `cancel_or`
-- 

DROP TABLE IF EXISTS `cancel_or`;
CREATE TABLE IF NOT EXISTS `cancel_or` (
  `cancel_id` int(11) NOT NULL auto_increment,
  `old_or` varchar(20) NOT NULL default '',
  `new_or` varchar(20) NOT NULL default '',
  `reasoncan` varchar(250) NOT NULL default '',
  `date_input` datetime NOT NULL default '0000-00-00 00:00:00',
  `input_by` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`cancel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `cancel_or`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `chart_accounts`
-- 

DROP TABLE IF EXISTS `chart_accounts`;
CREATE TABLE IF NOT EXISTS `chart_accounts` (
  `caid` int(11) NOT NULL auto_increment,
  `tfoid` varchar(50) default NULL,
  `tfodesc` varchar(60) NOT NULL default '',
  `accnt_code` varchar(50) default NULL,
  `accnt_type` enum('DEBIT','CREDIT') default NULL,
  `input_by` varchar(20) default NULL,
  `date_modified` datetime default NULL,
  PRIMARY KEY  (`caid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `chart_accounts`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `comparative_statement`
-- 

DROP TABLE IF EXISTS `comparative_statement`;
CREATE TABLE IF NOT EXISTS `comparative_statement` (
  `cs_id` int(10) NOT NULL auto_increment,
  `owner_id` int(10) default NULL,
  `business_id` int(10) default NULL,
  `bus_code` varchar(10) default NULL,
  `bus_nature` varchar(255) default NULL,
  `payment_mode` varchar(255) default NULL,
  `payment_number` varchar(255) default NULL,
  `taxes` decimal(10,2) default '0.00',
  `fees` decimal(10,2) default '0.00',
  `penalty` decimal(10,2) default '0.00',
  `surcharge` decimal(10,2) default '0.00',
  `total` decimal(10,2) default '0.00',
  `exemption` decimal(10,2) default '0.00',
  `paid` int(1) NOT NULL default '0',
  `backtax` decimal(10,2) NOT NULL default '0.00',
  `for_year` varchar(4) NOT NULL default '',
  `or_no` varchar(50) NOT NULL default '',
  `ts` datetime NOT NULL default '0000-00-00 00:00:00',
  `month` char(2) NOT NULL default '',
  PRIMARY KEY  (`cs_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `comparative_statement`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `culture_fee`
-- 

DROP TABLE IF EXISTS `culture_fee`;
CREATE TABLE IF NOT EXISTS `culture_fee` (
  `culture_id` int(10) NOT NULL auto_increment,
  `culture_type` int(11) default NULL,
  `fee_type` int(1) default NULL,
  `formula_amt` varchar(250) default NULL,
  `const_amt` decimal(12,2) default NULL,
  `date_create` datetime default NULL,
  `unit_measure` varchar(50) default NULL,
  `transaction` varchar(20) default NULL,
  `active` int(1) default NULL,
  PRIMARY KEY  (`culture_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `culture_fee`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `culture_range`
-- 

DROP TABLE IF EXISTS `culture_range`;
CREATE TABLE IF NOT EXISTS `culture_range` (
  `fee_id` int(10) NOT NULL auto_increment,
  `culture_id` int(10) default NULL,
  `range_lower` int(10) default NULL,
  `range_higher` int(10) default NULL,
  `amt` decimal(12,2) default NULL,
  PRIMARY KEY  (`fee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `culture_range`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `drop_vehicle`
-- 

DROP TABLE IF EXISTS `drop_vehicle`;
CREATE TABLE IF NOT EXISTS `drop_vehicle` (
  `drop_id` int(21) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL default '0',
  `motorized_motor_id` int(11) NOT NULL default '0',
  `updated_by` varchar(50) NOT NULL default '',
  `date_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`drop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `drop_vehicle`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_activity_log`
-- 

DROP TABLE IF EXISTS `ebpls_activity_log`;
CREATE TABLE IF NOT EXISTS `ebpls_activity_log` (
  `id` bigint(20) NOT NULL auto_increment,
  `userid` int(11) default NULL,
  `userlevel` varchar(255) default NULL,
  `username` varchar(255) default NULL,
  `part_constant_id` varchar(255) default NULL,
  `querystring` varchar(255) default NULL,
  `postvarval` text,
  `action` varchar(255) default NULL,
  `remoteip` varchar(255) default NULL,
  `lastupdated` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_activity_log`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_announcement`
-- 

DROP TABLE IF EXISTS `ebpls_announcement`;
CREATE TABLE IF NOT EXISTS `ebpls_announcement` (
  `eaid` int(10) NOT NULL auto_increment,
  `announcements` text,
  `announced_by` varchar(255) NOT NULL default '',
  `date_modified` date default NULL,
  `modified_by` varchar(255) default NULL,
  `sms_send` int(1) default NULL,
  PRIMARY KEY  (`eaid`),
  UNIQUE KEY `eaid` (`eaid`),
  UNIQUE KEY `eaid_2` (`eaid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_announcement`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_barangay`
-- 

DROP TABLE IF EXISTS `ebpls_barangay`;
CREATE TABLE IF NOT EXISTS `ebpls_barangay` (
  `barangay_code` int(10) NOT NULL auto_increment,
  `barangay_desc` varchar(255) NOT NULL default '',
  `barangay_date_registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `barangay_date_updated` datetime default NULL,
  `updated_by` varchar(255) default NULL,
  `g_zone` int(1) NOT NULL default '0',
  `upper` varchar(255) NOT NULL default '',
  `blgf_code` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`barangay_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_barangay`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_business_category`
-- 

DROP TABLE IF EXISTS `ebpls_business_category`;
CREATE TABLE IF NOT EXISTS `ebpls_business_category` (
  `business_category_code` varchar(20) NOT NULL default '',
  `business_category_desc` varchar(255) NOT NULL default '',
  `business_category_date_registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `business_category_date_updated` datetime default NULL,
  `updated_by` varchar(255) default NULL,
  `tax_exemption` int(12) default '0',
  PRIMARY KEY  (`business_category_code`),
  UNIQUE KEY `id` (`business_category_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ebpls_business_category`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_business_enterprise`
-- 

DROP TABLE IF EXISTS `ebpls_business_enterprise`;
CREATE TABLE IF NOT EXISTS `ebpls_business_enterprise` (
  `business_id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL default '0',
  `business_name` varchar(255) NOT NULL default '',
  `business_branch` varchar(255) NOT NULL default '''',
  `business_permit_trans_type` varchar(255) NOT NULL default '',
  `business_lot_no` varchar(255) NOT NULL default '',
  `business_street` varchar(255) NOT NULL default '',
  `business_barangay_code` varchar(255) NOT NULL default '',
  `business_zone_code` varchar(255) NOT NULL default '',
  `business_barangay_name` varchar(255) NOT NULL default '',
  `business_district_code` varchar(255) NOT NULL default '',
  `business_city_code` varchar(255) NOT NULL default '',
  `business_province_code` varchar(255) NOT NULL default '',
  `business_zip_code` varchar(255) NOT NULL default '',
  `business_contact_no` varchar(255) default NULL,
  `business_fax_no` varchar(255) default NULL,
  `business_email_address` varchar(255) default NULL,
  `business_url` varchar(255) default NULL,
  `business_location_desc` varchar(255) default NULL,
  `business_building_name` varchar(255) default NULL,
  `business_phone_no` varchar(255) default NULL,
  `business_category_code` varchar(20) NOT NULL default '',
  `business_dot_acr_no` varchar(255) NOT NULL default '',
  `business_sec_reg_no` varchar(255) NOT NULL default '',
  `business_tin_reg_no` varchar(255) NOT NULL default '',
  `business_dti_reg_no` varchar(255) NOT NULL default '',
  `business_dti_reg_date` datetime default NULL,
  `business_date_established` datetime NOT NULL default '0000-00-00 00:00:00',
  `business_start_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `business_occupancy_code` varchar(20) NOT NULL default '',
  `business_offc_code` varchar(20) NOT NULL default '',
  `business_capital_investment` decimal(12,2) NOT NULL default '0.00',
  `employee_male` int(7) default NULL,
  `business_no_del_vehicles` int(5) NOT NULL default '0',
  `business_payment_mode` varchar(255) NOT NULL default '',
  `business_exemption_code` varchar(20) default NULL,
  `business_type_code` varchar(20) NOT NULL default '',
  `business_nso_assigned_no` varchar(255) NOT NULL default '',
  `business_nso_estab_id` varchar(255) NOT NULL default '',
  `business_industry_sector_code` varchar(20) NOT NULL default '',
  `business_remarks` varchar(255) default NULL,
  `business_status_code` varchar(20) NOT NULL default '',
  `business_status_remarks` varchar(255) default NULL,
  `business_application_status` varchar(255) NOT NULL default '',
  `business_application_status_rem` varchar(255) default NULL,
  `business_last_yrs_cap_invest` decimal(12,2) default '0.00',
  `business_last_yrs_no_employees` int(7) NOT NULL default '1',
  `business_last_yrs_no_employees_male` int(11) NOT NULL default '0',
  `business_last_yrs_no_employees_female` int(11) NOT NULL default '0',
  `business_last_yrs_dec_gross_sales` decimal(12,2) NOT NULL default '0.00',
  `business_retirement_date` datetime default NULL,
  `business_retirement_reason` varchar(255) default NULL,
  `business_application_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `business_validity_period` datetime NOT NULL default '0000-00-00 00:00:00',
  `business_req_code` varchar(20) NOT NULL default '',
  `business_nature_code` varchar(20) NOT NULL default '',
  `business_create_ts` datetime NOT NULL default '0000-00-00 00:00:00',
  `business_update_by` varchar(25) NOT NULL default '',
  `business_update_ts` datetime NOT NULL default '0000-00-00 00:00:00',
  `comment` longtext NOT NULL,
  `business_scale` enum('MICRO','COTTAGE','SMALL','MEDIUM','LARGE') default 'MICRO',
  `retire` int(1) default '0',
  `employee_female` int(7) default NULL,
  `blacklist` int(1) NOT NULL default '0',
  `biztype` varchar(15) NOT NULL default '',
  `subsi` int(1) NOT NULL default '0',
  `pcname` varchar(50) NOT NULL default '',
  `pcaddress` varchar(250) NOT NULL default '',
  `regname` varchar(100) NOT NULL default '',
  `paidemp` int(10) NOT NULL default '0',
  `ecoorg` int(10) NOT NULL default '0',
  `ecoarea` int(10) NOT NULL default '0',
  `business_plate` int(4) NOT NULL default '0',
  `branch_id` int(10) NOT NULL default '0',
  `edit_by` varchar(50) NOT NULL default '',
  `edit_locked` tinyint(1) NOT NULL default '0',
  `black_list_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `black_list_reason` text NOT NULL,
  PRIMARY KEY  (`business_id`,`business_name`),
  UNIQUE KEY `id` (`business_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_business_enterprise`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_business_enterprise_permit`
-- 

DROP TABLE IF EXISTS `ebpls_business_enterprise_permit`;
CREATE TABLE IF NOT EXISTS `ebpls_business_enterprise_permit` (
  `business_permit_id` int(15) NOT NULL auto_increment,
  `business_permit_code` varchar(25) default NULL,
  `business_id` int(11) NOT NULL default '0',
  `owner_id` int(11) NOT NULL default '0',
  `retirement_code` varchar(20) default NULL,
  `retirement_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `retirement_date_processed` datetime NOT NULL default '0000-00-00 00:00:00',
  `for_year` varchar(60) NOT NULL default '',
  `application_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `paid` int(1) default '0',
  `released` tinyint(1) default NULL,
  `input_by` varchar(50) default NULL,
  `transaction` varchar(20) default 'New',
  `steps` varchar(50) default NULL,
  `pin` varchar(10) default NULL,
  `active` int(1) default NULL,
  `pmode` enum('QUARTERLY','SEMI_ANNUAL','ANNUAL') NOT NULL default 'QUARTERLY',
  `released_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`business_permit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_business_enterprise_permit`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_approve`
-- 

DROP TABLE IF EXISTS `ebpls_buss_approve`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_approve` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) default NULL,
  `business_id` int(11) default NULL,
  `decision` int(1) default NULL,
  `dec_comment` text,
  `transaction` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_buss_approve`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_assessment`
-- 

DROP TABLE IF EXISTS `ebpls_buss_assessment`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_assessment` (
  `assid` int(11) NOT NULL auto_increment,
  `owner_id` int(11) default NULL,
  `business_id` int(11) default NULL,
  `natureid` int(11) default NULL,
  `taxfeeid` int(11) default NULL,
  `multi` int(10) default NULL,
  `amt` decimal(12,2) default NULL,
  PRIMARY KEY  (`assid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_buss_assessment`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_complex`
-- 

DROP TABLE IF EXISTS `ebpls_buss_complex`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_complex` (
  `compid` int(11) NOT NULL auto_increment,
  `complex_taxfeeid` int(11) default NULL,
  `complex_tfoid` int(11) default NULL,
  `var_complex` char(20) default NULL,
  `date_created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`compid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_buss_complex`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_monthlyref`
-- 

DROP TABLE IF EXISTS `ebpls_buss_monthlyref`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_monthlyref` (
  `id` int(4) NOT NULL auto_increment,
  `moid` char(2) default NULL,
  `modesc` varchar(15) default NULL,
  `createdate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_buss_monthlyref`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_nature`
-- 

DROP TABLE IF EXISTS `ebpls_buss_nature`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_nature` (
  `natureid` int(11) NOT NULL auto_increment,
  `naturedesc` varchar(255) default NULL,
  `naturestatus` char(1) default NULL,
  `natureoption` char(1) default NULL,
  `psiccode` varchar(15) default NULL,
  PRIMARY KEY  (`natureid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_buss_nature`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_penalty`
-- 

DROP TABLE IF EXISTS `ebpls_buss_penalty`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_penalty` (
  `id` int(11) NOT NULL auto_increment,
  `renewaldate` varchar(5) default NULL,
  `rateofpenalty` varchar(10) default NULL,
  `rateofinterest` varchar(10) default NULL,
  `indicator` char(1) default NULL,
  `status` char(1) default NULL,
  `remarks` varchar(255) default NULL,
  `createdate` datetime default NULL,
  `revdate` datetime default NULL,
  `surchargeoption` char(1) default NULL,
  `optsurcharge` char(3) default NULL,
  `surtype` int(1) NOT NULL default '0',
  `inttype` int(1) NOT NULL default '0',
  `feeonly` int(1) NOT NULL default '0',
  `optduedates` char(3) default NULL,
  `mdue` date default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_buss_penalty`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_penalty1`
-- 

DROP TABLE IF EXISTS `ebpls_buss_penalty1`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_penalty1` (
  `id` int(11) NOT NULL auto_increment,
  `mdue` date default NULL,
  `semdue1` varchar(5) default NULL,
  `semdue2` varchar(5) default NULL,
  `qtrdue1` varchar(5) default NULL,
  `qtrdue2` varchar(5) default NULL,
  `qtrdue3` varchar(5) default NULL,
  `qtrdue4` varchar(5) default NULL,
  `revdate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `ebpls_buss_penalty1`
-- 

INSERT INTO `ebpls_buss_penalty1` (`id`, `mdue`, `semdue1`, `semdue2`, `qtrdue1`, `qtrdue2`, `qtrdue3`, `qtrdue4`, `revdate`) VALUES (1, '0000-00-00', '', '', '', '', '', '', '2006-12-20 02:43:08');

-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_preference`
-- 

DROP TABLE IF EXISTS `ebpls_buss_preference`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_preference` (
  `lgucode` varchar(255) NOT NULL default '',
  `lguname` varchar(255) default NULL,
  `lguprovince` varchar(120) default NULL,
  `lgumunicipality` varchar(120) default NULL,
  `lguoffice` varchar(120) default NULL,
  `lguimage` varchar(255) default NULL,
  `spermit` char(1) default NULL,
  `sassess` char(1) default NULL,
  `sor` char(1) default NULL,
  `sbacktaxes` char(1) default NULL,
  `srequire` char(1) default NULL,
  `createdate` datetime default NULL,
  `revdate` datetime default NULL,
  `renewaldate` date default NULL,
  `rateofpenalty` varchar(5) default NULL,
  `rateofinterest` varchar(5) default NULL,
  `staxesfees` char(1) default NULL,
  `spayment` char(1) default NULL,
  `bodycolor` varchar(10) default NULL,
  `sdecimal` char(1) default NULL,
  `mp` int(12) default '0',
  `bt` int(12) default '0',
  `minhigh` char(1) default NULL,
  `or_print` char(1) default NULL,
  `spaywoapprov` char(1) NOT NULL default '',
  `swaivetax` char(1) NOT NULL default '',
  `swaivefee` char(1) NOT NULL default '',
  `lgu_add` varchar(100) NOT NULL default '',
  `lgu_tel` varchar(20) NOT NULL default '',
  `predcomp` int(1) NOT NULL default '0',
  `iReset` char(1) NOT NULL default '',
  PRIMARY KEY  (`lgucode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ebpls_buss_preference`
-- 

INSERT INTO `ebpls_buss_preference` (`lgucode`, `lguname`, `lguprovince`, `lgumunicipality`, `lguoffice`, `lguimage`, `spermit`, `sassess`, `sor`, `sbacktaxes`, `srequire`, `createdate`, `revdate`, `renewaldate`, `rateofpenalty`, `rateofinterest`, `staxesfees`, `spayment`, `bodycolor`, `sdecimal`, `mp`, `bt`, `minhigh`, `or_print`, `spaywoapprov`, `swaivetax`, `swaivefee`, `lgu_add`, `lgu_tel`, `predcomp`, `iReset`) VALUES ('', NULL, NULL, NULL, NULL, 'ebpls_logo.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, '', '', '', '', '', 0, '');

-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_requirements`
-- 

DROP TABLE IF EXISTS `ebpls_buss_requirements`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_requirements` (
  `reqid` int(11) NOT NULL auto_increment,
  `reqdesc` varchar(100) default NULL,
  `recstatus` char(1) default NULL,
  `reqindicator` char(1) default NULL,
  `datecreated` datetime default NULL,
  `revdate` datetime default NULL,
  `permit_type` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`reqid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_buss_requirements`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_taxfee`
-- 

DROP TABLE IF EXISTS `ebpls_buss_taxfee`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_taxfee` (
  `natureid` int(11) default NULL,
  `taxfeeid` int(11) NOT NULL auto_increment,
  `taxfeedesc` varchar(255) default NULL,
  `taxfeestatus` char(1) default NULL,
  `taxfeeoption` char(1) default NULL,
  `taxfeeind` char(1) default NULL,
  `taxfeetype` char(1) default NULL,
  `taxfeemode` char(1) default NULL,
  `taxfeeamtfor` varchar(12) default NULL,
  `datecreated` datetime default NULL,
  PRIMARY KEY  (`taxfeeid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_buss_taxfee`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_taxfee_option`
-- 

DROP TABLE IF EXISTS `ebpls_buss_taxfee_option`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_taxfee_option` (
  `optionid` int(4) NOT NULL auto_increment,
  `optiondesc` varchar(30) default NULL,
  `datecreated` datetime default NULL,
  PRIMARY KEY  (`optionid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_buss_taxfee_option`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_taxfeeother`
-- 

DROP TABLE IF EXISTS `ebpls_buss_taxfeeother`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_taxfeeother` (
  `taxfeeid` int(11) NOT NULL auto_increment,
  `natureid` int(11) default NULL,
  `tfo_id` int(11) default NULL,
  `basis` tinyint(1) default NULL,
  `indicator` tinyint(1) default NULL,
  `mode` tinyint(1) default NULL,
  `amtformula` varchar(60) default NULL,
  `datecreated` datetime default NULL,
  `taxtype` tinyint(1) default NULL,
  `uom` varchar(100) NOT NULL default '',
  `min_amt` decimal(12,2) NOT NULL default '0.00',
  PRIMARY KEY  (`taxfeeid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_buss_taxfeeother`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_taxfeetype`
-- 

DROP TABLE IF EXISTS `ebpls_buss_taxfeetype`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_taxfeetype` (
  `taxfeetype` int(11) NOT NULL auto_increment,
  `typedesc` varchar(20) default NULL,
  `datecreated` datetime default NULL,
  PRIMARY KEY  (`taxfeetype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `ebpls_buss_taxfeetype`
-- 

INSERT INTO `ebpls_buss_taxfeetype` (`taxfeetype`, `typedesc`, `datecreated`) VALUES (1, 'TAX', '2005-05-12 01:20:52');
INSERT INTO `ebpls_buss_taxfeetype` (`taxfeetype`, `typedesc`, `datecreated`) VALUES (2, 'REGULATORY FEE', '2005-05-12 01:21:01');
INSERT INTO `ebpls_buss_taxfeetype` (`taxfeetype`, `typedesc`, `datecreated`) VALUES (3, 'OTHER CHARGES', '2005-05-12 01:21:05');
INSERT INTO `ebpls_buss_taxfeetype` (`taxfeetype`, `typedesc`, `datecreated`) VALUES (4, 'SPECIAL FEE', '2005-07-11 14:04:13');

-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_taxrange`
-- 

DROP TABLE IF EXISTS `ebpls_buss_taxrange`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_taxrange` (
  `rangeid` int(11) NOT NULL auto_increment,
  `taxfeeid` int(11) default NULL,
  `rangelow` decimal(12,2) default NULL,
  `rangehigh` decimal(12,2) default NULL,
  `rangeamount` varchar(40) default NULL,
  `datecreated` datetime default NULL,
  PRIMARY KEY  (`rangeid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_buss_taxrange`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_buss_tfo`
-- 

DROP TABLE IF EXISTS `ebpls_buss_tfo`;
CREATE TABLE IF NOT EXISTS `ebpls_buss_tfo` (
  `tfoid` int(11) NOT NULL auto_increment,
  `tfodesc` varchar(80) default NULL,
  `tfostatus` char(1) default NULL,
  `tfoindicator` char(1) default NULL,
  `taxfeetype` int(11) default NULL,
  `datecreated` datetime default NULL,
  `defamt` decimal(12,2) default NULL,
  `or_print` char(3) default NULL,
  `counter` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`tfoid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_buss_tfo`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_citizenship`
-- 

DROP TABLE IF EXISTS `ebpls_citizenship`;
CREATE TABLE IF NOT EXISTS `ebpls_citizenship` (
  `cit_id` int(11) NOT NULL auto_increment,
  `cit_desc` varchar(255) default NULL,
  PRIMARY KEY  (`cit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_citizenship`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_city_municipality`
-- 

DROP TABLE IF EXISTS `ebpls_city_municipality`;
CREATE TABLE IF NOT EXISTS `ebpls_city_municipality` (
  `city_municipality_code` varchar(255) NOT NULL default '',
  `city_municipality_desc` varchar(255) NOT NULL default '',
  `city_municipality_date_registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `city_municipality_date_updated` datetime default NULL,
  `updated_by` varchar(255) default NULL,
  `upper` varchar(255) NOT NULL default '',
  `blgf_code` varchar(10) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ebpls_city_municipality`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_comm_tax_cert`
-- 

DROP TABLE IF EXISTS `ebpls_comm_tax_cert`;
CREATE TABLE IF NOT EXISTS `ebpls_comm_tax_cert` (
  `comm_tax_cert_code` varchar(20) NOT NULL default '',
  `comm_tax_cert_owner_first_name` varchar(60) NOT NULL default '',
  `comm_tax_cert_owner_middle_name` varchar(60) NOT NULL default '',
  `comm_tax_cert_owner_last_name` varchar(60) NOT NULL default '',
  `comm_tax_cert_owner_birth_date` varchar(60) NOT NULL default '',
  `comm_tax_cert_owner_address` varchar(60) NOT NULL default '',
  `comm_tax_cert_owner_gender` enum('MALE','FEMALE','UNSPECIFIED') NOT NULL default 'UNSPECIFIED',
  `comm_tax_cert_owner_civil_status` varchar(60) NOT NULL default '',
  `comm_tax_cert_last_gross` decimal(12,2) NOT NULL default '0.00',
  `comm_tax_cert_amount_due` decimal(12,2) NOT NULL default '0.00',
  `comm_tax_cert_amount_paid` decimal(12,2) NOT NULL default '0.00',
  `comm_tax_cert_acct_code` varchar(20) NOT NULL default '',
  `comm_tax_cert_place_issued` varchar(255) NOT NULL default '',
  `comm_tax_cert_date_issued` datetime NOT NULL default '0000-00-00 00:00:00',
  `for_year` varchar(60) NOT NULL default '',
  `comm_tax_cert_type` enum('INDIVIDUAL','BUSINESS') NOT NULL default 'INDIVIDUAL',
  PRIMARY KEY  (`comm_tax_cert_code`),
  UNIQUE KEY `id` (`comm_tax_cert_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ebpls_comm_tax_cert`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_ctc_business`
-- 

DROP TABLE IF EXISTS `ebpls_ctc_business`;
CREATE TABLE IF NOT EXISTS `ebpls_ctc_business` (
  `ctc_code` varchar(25) NOT NULL default '',
  `ctc_place_issued` varchar(64) default NULL,
  `ctc_date_issued` date NOT NULL default '0000-00-00',
  `ctc_for_year` int(11) default NULL,
  `ctc_company` varchar(255) NOT NULL default '',
  `ctc_business_id` int(11) NOT NULL auto_increment,
  `ctc_tin_no` varchar(25) NOT NULL default '',
  `ctc_organization_type` enum('CORPORATION','ASSOCIATION','PARTNERSHIP') NOT NULL default 'CORPORATION',
  `ctc_place_of_incorporation` varchar(64) NOT NULL default '',
  `ctc_business_nature` varchar(64) NOT NULL default '',
  `ctc_basic_tax` decimal(15,2) NOT NULL default '0.00',
  `ctc_additional_tax1` decimal(15,2) NOT NULL default '0.00',
  `ctc_additional_tax2` decimal(15,2) NOT NULL default '0.00',
  `ctc_tax_interest` decimal(15,2) NOT NULL default '0.00',
  `ctc_company_address` varchar(255) NOT NULL default '',
  `ctc_incorporation_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `ctc_tax_due` decimal(15,2) NOT NULL default '0.00',
  `ctc_acct_code` varchar(32) default NULL,
  PRIMARY KEY  (`ctc_code`),
  UNIQUE KEY `id` (`ctc_code`),
  KEY `dt_issued` (`ctc_date_issued`),
  KEY `ctc_business_id` (`ctc_business_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_ctc_business`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_ctc_individual`
-- 

DROP TABLE IF EXISTS `ebpls_ctc_individual`;
CREATE TABLE IF NOT EXISTS `ebpls_ctc_individual` (
  `ctc_code` varchar(20) NOT NULL default '',
  `ctc_first_name` varchar(60) NOT NULL default '',
  `ctc_owner_id` int(11) NOT NULL auto_increment,
  `ctc_middle_name` varchar(60) NOT NULL default '',
  `ctc_last_name` varchar(60) NOT NULL default '',
  `ctc_birth_date` varchar(60) NOT NULL default '',
  `ctc_address` varchar(255) NOT NULL default '',
  `ctc_gender` enum('M','F') NOT NULL default 'M',
  `ctc_civil_status` varchar(60) NOT NULL default '',
  `ctc_acct_code` varchar(20) NOT NULL default '',
  `ctc_place_issued` varchar(255) NOT NULL default '',
  `ctc_date_issued` date NOT NULL default '0000-00-00',
  `ctc_for_year` int(60) NOT NULL default '0',
  `ctc_tin_no` varchar(25) NOT NULL default '',
  `ctc_occupation` varchar(25) NOT NULL default '',
  `ctc_height` varchar(25) NOT NULL default '',
  `ctc_weight` varchar(25) NOT NULL default '',
  `ctc_icr_no` varchar(25) NOT NULL default '',
  `ctc_citizenship` varchar(25) NOT NULL default '',
  `ctc_application_fee` decimal(15,2) NOT NULL default '0.00',
  `ctc_place_of_birth` varchar(255) NOT NULL default '',
  `ctc_basic_tax` decimal(3,2) NOT NULL default '0.00',
  `ctc_additional_tax1` decimal(15,2) NOT NULL default '0.00',
  `ctc_additional_tax2` decimal(15,2) NOT NULL default '0.00',
  `ctc_additional_tax3` decimal(15,2) NOT NULL default '0.00',
  `ctc_tax_interest` decimal(15,2) NOT NULL default '0.00',
  `ctc_tax_exempted` tinyint(1) NOT NULL default '0',
  `ctc_tax_due` decimal(15,2) NOT NULL default '0.00',
  PRIMARY KEY  (`ctc_code`),
  UNIQUE KEY `id` (`ctc_code`),
  KEY `dt_issued` (`ctc_date_issued`),
  KEY `ctc_owner_id` (`ctc_owner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_ctc_individual`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_ctc_interest`
-- 

DROP TABLE IF EXISTS `ebpls_ctc_interest`;
CREATE TABLE IF NOT EXISTS `ebpls_ctc_interest` (
  `id` int(1) NOT NULL auto_increment,
  `ctc_type` varchar(255) default NULL,
  `interest_rate` int(3) default NULL,
  `ceiling_rate` decimal(10,2) NOT NULL default '0.00',
  `modified_date` date default NULL,
  `updated_by` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_ctc_interest`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_district`
-- 

DROP TABLE IF EXISTS `ebpls_district`;
CREATE TABLE IF NOT EXISTS `ebpls_district` (
  `district_code` int(10) NOT NULL auto_increment,
  `district_desc` varchar(255) NOT NULL default '',
  `district_date_registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `district_date_updated` datetime default NULL,
  `updated_by` varchar(255) default NULL,
  `g_zone` int(1) NOT NULL default '0',
  `upper` varchar(255) NOT NULL default '',
  `blgf_code` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`district_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_district`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_economic_area`
-- 

DROP TABLE IF EXISTS `ebpls_economic_area`;
CREATE TABLE IF NOT EXISTS `ebpls_economic_area` (
  `economic_area_id` int(10) NOT NULL auto_increment,
  `economic_area_code` varchar(10) NOT NULL default '',
  `economic_area_desc` varchar(50) NOT NULL default '',
  `updated_by` varchar(10) NOT NULL default '',
  `date_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`economic_area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_economic_area`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_economic_org`
-- 

DROP TABLE IF EXISTS `ebpls_economic_org`;
CREATE TABLE IF NOT EXISTS `ebpls_economic_org` (
  `economic_org_id` int(10) NOT NULL auto_increment,
  `economic_org_code` varchar(10) NOT NULL default '',
  `economic_org_desc` varchar(50) NOT NULL default '',
  `updated_by` varchar(10) NOT NULL default '',
  `date_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`economic_org_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_economic_org`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_engine_type`
-- 

DROP TABLE IF EXISTS `ebpls_engine_type`;
CREATE TABLE IF NOT EXISTS `ebpls_engine_type` (
  `engine_type_id` int(11) NOT NULL auto_increment,
  `engine_type_desc` varchar(255) default NULL,
  PRIMARY KEY  (`engine_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_engine_type`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_fee_exemption`
-- 

DROP TABLE IF EXISTS `ebpls_fee_exemption`;
CREATE TABLE IF NOT EXISTS `ebpls_fee_exemption` (
  `business_category_code` varchar(20) default NULL,
  `tfoid` int(11) default NULL,
  `taxfeetype` int(11) default NULL,
  `exempted` int(1) NOT NULL default '0',
  `date_modfied` date default NULL,
  `modified_by` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ebpls_fee_exemption`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_fees_paid`
-- 

DROP TABLE IF EXISTS `ebpls_fees_paid`;
CREATE TABLE IF NOT EXISTS `ebpls_fees_paid` (
  `fee_paid_id` int(11) NOT NULL auto_increment,
  `owner_id` int(10) default NULL,
  `fee_desc` varchar(255) default NULL,
  `fee_amount` int(10) default NULL,
  `multi_by` int(51) default NULL,
  `permit_type` varchar(255) default NULL,
  `permit_status` varchar(100) default NULL,
  `active` int(1) NOT NULL default '0',
  `input_by` varchar(50) default NULL,
  `input_date` datetime default NULL,
  PRIMARY KEY  (`fee_paid_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_fees_paid`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_fish_description`
-- 

DROP TABLE IF EXISTS `ebpls_fish_description`;
CREATE TABLE IF NOT EXISTS `ebpls_fish_description` (
  `fish_id` int(11) NOT NULL auto_increment,
  `fish_desc` varchar(255) default NULL,
  PRIMARY KEY  (`fish_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_fish_description`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_fish_owner`
-- 

DROP TABLE IF EXISTS `ebpls_fish_owner`;
CREATE TABLE IF NOT EXISTS `ebpls_fish_owner` (
  `owner_id` int(11) NOT NULL auto_increment,
  `owner_first_name` varchar(60) NOT NULL default '',
  `owner_middle_name` varchar(60) NOT NULL default '',
  `owner_last_name` varchar(60) NOT NULL default '',
  `owner_house_no` varchar(255) NOT NULL default '',
  `owner_street` varchar(255) NOT NULL default '',
  `owner_barangay_code` varchar(20) NOT NULL default '',
  `owner_zone_code` varchar(20) NOT NULL default '',
  `owner_district_code` varchar(20) NOT NULL default '',
  `owner_city_code` varchar(20) NOT NULL default '',
  `owner_province_code` varchar(20) NOT NULL default '',
  `owner_zip_code` varchar(20) default NULL,
  `owner_citizenship` varchar(255) NOT NULL default '',
  `owner_civil_status` varchar(255) NOT NULL default '',
  `owner_gender` enum('M','F','X') default 'X',
  `owner_tin_no` varchar(255) NOT NULL default '',
  `owner_icr_no` varchar(20) default NULL,
  `owner_phone_no` varchar(255) default NULL,
  `owner_gsm_no` varchar(255) default NULL,
  `owner_email_address` varchar(255) default NULL,
  `owner_others` varchar(255) default NULL,
  `owner_birth_date` datetime default NULL,
  `owner_reg_date` datetime default NULL,
  `owner_lastupdated` datetime default NULL,
  `owner_lastupdated_by` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`owner_id`),
  UNIQUE KEY `id` (`owner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_fish_owner`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_fishery_fees`
-- 

DROP TABLE IF EXISTS `ebpls_fishery_fees`;
CREATE TABLE IF NOT EXISTS `ebpls_fishery_fees` (
  `fee_id` int(11) NOT NULL auto_increment,
  `fee_desc` varchar(255) default NULL,
  `fee_amount` int(10) default NULL,
  `permit_type` varchar(20) default NULL,
  `lastupdated` datetime default NULL,
  `lastupdatedby` varchar(20) default NULL,
  `active` int(1) default '1',
  PRIMARY KEY  (`fee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_fishery_fees`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_fishery_permit`
-- 

DROP TABLE IF EXISTS `ebpls_fishery_permit`;
CREATE TABLE IF NOT EXISTS `ebpls_fishery_permit` (
  `ebpls_fishery_id` int(15) unsigned zerofill NOT NULL auto_increment,
  `ebpls_fishery_permit_code` varchar(25) NOT NULL default '',
  `owner_id` int(11) NOT NULL default '0',
  `ebpls_fishery_businessname` varchar(255) NOT NULL default '''''',
  `ebpls_fishery_permit_application_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `ebpls_fishery_local_name_fishing_gear` varchar(255) NOT NULL default '',
  `ebpls_fishery_in_english` varchar(255) NOT NULL default '',
  `ebpls_fishery_no_of_units` int(5) NOT NULL default '1',
  `ebpls_fishery_assess_value_fishing_gear` decimal(15,2) NOT NULL default '0.00',
  `ebpls_fishery_fishing_gear_size` decimal(15,2) NOT NULL default '0.00',
  `ebpls_fishery_area_size` decimal(15,2) NOT NULL default '0.00',
  `ebpls_fishery_no_of_crew` int(5) NOT NULL default '0',
  `ebpls_fishery_motorized` enum('YES','NO') NOT NULL default 'YES',
  `ebpls_fishery_registered` enum('YES','NO') NOT NULL default 'YES',
  `ebpls_fishery_boat_name` varchar(255) NOT NULL default '',
  `ebpls_fishery_registration_no` varchar(255) NOT NULL default '',
  `ebpls_fishery_ave_fish_catch_present` decimal(15,2) NOT NULL default '0.00',
  `ebpls_fishery_ave_fish_catch_2yrs_ago` decimal(15,2) NOT NULL default '0.00',
  `ebpls_fishery_location` varchar(255) default NULL,
  `ebpls_fishery_rc_no` varchar(255) default NULL,
  `ebpls_fishery_rc_issued_at` varchar(255) default NULL,
  `ebpls_fishery_rc_issued_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `transaction` varchar(20) default NULL,
  `for_year` varchar(20) default NULL,
  `paid` int(1) default NULL,
  `released` int(1) default NULL,
  `steps` varchar(50) default NULL,
  `pin` varchar(10) default NULL,
  `active` int(1) default NULL,
  PRIMARY KEY  (`ebpls_fishery_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_fishery_permit`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_franchise_fees`
-- 

DROP TABLE IF EXISTS `ebpls_franchise_fees`;
CREATE TABLE IF NOT EXISTS `ebpls_franchise_fees` (
  `fee_id` int(10) NOT NULL auto_increment,
  `fee_desc` varchar(255) default NULL,
  `fee_amount` int(11) default NULL,
  `permit_type` varchar(100) default NULL,
  `lastupdated` datetime default NULL,
  `lastupdatedby` varchar(20) default NULL,
  `active` int(1) NOT NULL default '1',
  PRIMARY KEY  (`fee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_franchise_fees`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_franchise_owner`
-- 

DROP TABLE IF EXISTS `ebpls_franchise_owner`;
CREATE TABLE IF NOT EXISTS `ebpls_franchise_owner` (
  `owner_id` int(11) NOT NULL auto_increment,
  `owner_first_name` varchar(60) NOT NULL default '',
  `owner_middle_name` varchar(60) NOT NULL default '',
  `owner_last_name` varchar(60) NOT NULL default '',
  `owner_house_no` varchar(255) NOT NULL default '',
  `owner_street` varchar(255) NOT NULL default '',
  `owner_barangay_code` varchar(20) NOT NULL default '',
  `owner_zone_code` varchar(20) NOT NULL default '',
  `owner_district_code` varchar(20) NOT NULL default '',
  `owner_city_code` varchar(20) NOT NULL default '',
  `owner_province_code` varchar(20) NOT NULL default '',
  `owner_zip_code` varchar(20) default NULL,
  `owner_citizenship` varchar(255) NOT NULL default '',
  `owner_civil_status` varchar(255) NOT NULL default '',
  `owner_gender` enum('M','F','X') default 'X',
  `owner_tin_no` varchar(255) NOT NULL default '',
  `owner_icr_no` varchar(20) default NULL,
  `owner_phone_no` varchar(255) default NULL,
  `owner_gsm_no` varchar(255) default NULL,
  `owner_email_address` varchar(255) default NULL,
  `owner_others` varchar(255) default NULL,
  `owner_birth_date` datetime default NULL,
  `owner_reg_date` datetime default NULL,
  `owner_lastupdated` datetime default NULL,
  `owner_lastupdated_by` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`owner_id`),
  UNIQUE KEY `id` (`owner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_franchise_owner`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_franchise_permit`
-- 

DROP TABLE IF EXISTS `ebpls_franchise_permit`;
CREATE TABLE IF NOT EXISTS `ebpls_franchise_permit` (
  `franchise_permit_id` int(15) NOT NULL auto_increment,
  `franchise_permit_code` varchar(25) NOT NULL default '',
  `owner_id` int(11) NOT NULL default '0',
  `retirement_code` varchar(20) default NULL,
  `retirement_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `retirement_date_processed` datetime NOT NULL default '0000-00-00 00:00:00',
  `requirement_code` varchar(20) NOT NULL default '',
  `for_year` varchar(60) NOT NULL default '',
  `application_date` date NOT NULL default '0000-00-00',
  `paid` int(1) default NULL,
  `released` int(1) default '0',
  `transaction` varchar(20) default NULL,
  `steps` varchar(50) default NULL,
  `pin` varchar(10) default NULL,
  `active` int(1) default '1',
  PRIMARY KEY  (`franchise_permit_id`),
  UNIQUE KEY `id` (`franchise_permit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_franchise_permit`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_industry_sector`
-- 

DROP TABLE IF EXISTS `ebpls_industry_sector`;
CREATE TABLE IF NOT EXISTS `ebpls_industry_sector` (
  `industry_sector_code` varchar(20) NOT NULL default '',
  `industry_sector_desc` varchar(255) NOT NULL default '',
  `industry_sector_date_registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `industry_sector_date_updated` datetime default NULL,
  `updated_by` varchar(255) default NULL,
  PRIMARY KEY  (`industry_sector_code`),
  UNIQUE KEY `id` (`industry_sector_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ebpls_industry_sector`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_mot_penalty`
-- 

DROP TABLE IF EXISTS `ebpls_mot_penalty`;
CREATE TABLE IF NOT EXISTS `ebpls_mot_penalty` (
  `ebpls_mot_penalty_id` int(51) NOT NULL auto_increment,
  `motorized_permit_id` int(21) NOT NULL default '0',
  `surcharge` decimal(12,2) NOT NULL default '0.00',
  `interest` decimal(12,2) NOT NULL default '0.00',
  `late` decimal(12,2) NOT NULL default '0.00',
  `backtax` decimal(12,2) NOT NULL default '0.00',
  `paid` int(1) NOT NULL default '0',
  `date_input` datetime NOT NULL default '0000-00-00 00:00:00',
  `input_by` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`ebpls_mot_penalty_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_mot_penalty`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_motorized_operator_permit`
-- 

DROP TABLE IF EXISTS `ebpls_motorized_operator_permit`;
CREATE TABLE IF NOT EXISTS `ebpls_motorized_operator_permit` (
  `motorized_operator_permit_id` int(15) unsigned zerofill NOT NULL auto_increment,
  `motorized_permit_code` varchar(25) NOT NULL default '',
  `owner_id` int(11) NOT NULL default '0',
  `motorized_operator_permit_application_date` datetime default NULL,
  `motorized_no_of_units` int(7) NOT NULL default '1',
  `motorized_motor_model` varchar(255) NOT NULL default '',
  `motorized_motor_no` varchar(255) NOT NULL default '',
  `motorized_chassis_no` varchar(255) NOT NULL default '',
  `motorized_plate_no` varchar(255) NOT NULL default '',
  `motorized_retirement_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `motorized_retirement_date_processed` datetime NOT NULL default '0000-00-00 00:00:00',
  `requirement_code` varchar(20) NOT NULL default '',
  `for_year` varchar(60) NOT NULL default '',
  `paid` int(1) default NULL,
  `released` int(1) default '0',
  `transaction` varchar(20) default NULL,
  `steps` varchar(50) default NULL,
  `pin` varchar(10) default NULL,
  `active` int(1) default '1',
  PRIMARY KEY  (`motorized_operator_permit_id`),
  UNIQUE KEY `id` (`motorized_operator_permit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_motorized_operator_permit`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_motorized_operators`
-- 

DROP TABLE IF EXISTS `ebpls_motorized_operators`;
CREATE TABLE IF NOT EXISTS `ebpls_motorized_operators` (
  `motorized_operator_id` int(15) NOT NULL auto_increment,
  `owner_id` int(12) default '0',
  `affiliations` varchar(255) default '''',
  `last_updated_ts` datetime default NULL,
  `created_ts` datetime default NULL,
  `admin` varchar(255) NOT NULL default '''',
  PRIMARY KEY  (`motorized_operator_id`),
  UNIQUE KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_motorized_operators`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_motorized_penalty`
-- 

DROP TABLE IF EXISTS `ebpls_motorized_penalty`;
CREATE TABLE IF NOT EXISTS `ebpls_motorized_penalty` (
  `id` int(11) NOT NULL auto_increment,
  `renewaltype` int(1) NOT NULL default '0',
  `renewaldate1` varchar(5) default NULL,
  `renewaldate2` varchar(5) NOT NULL default '',
  `renewaldate3` varchar(5) NOT NULL default '',
  `renewaldate4` varchar(5) NOT NULL default '',
  `renewaldate5` varchar(5) NOT NULL default '',
  `renewaldate6` varchar(5) NOT NULL default '',
  `renewaldate7` varchar(5) NOT NULL default '',
  `renewaldate8` varchar(5) NOT NULL default '',
  `renewaldate9` varchar(5) NOT NULL default '',
  `renewaldate0` varchar(5) NOT NULL default '',
  `rateofpenalty` varchar(10) default NULL,
  `rateofinterest` varchar(10) default NULL,
  `indicator` int(1) default NULL,
  `status` int(1) default NULL,
  `intype` int(1) NOT NULL default '0',
  `feeonly` int(1) NOT NULL default '0',
  `late_filing_fee` decimal(12,2) NOT NULL default '0.00',
  `f_status` int(1) NOT NULL default '0',
  `backtax` int(1) NOT NULL default '0',
  `permit_type` varchar(20) NOT NULL default '',
  `updated_by` varchar(50) NOT NULL default '',
  `date_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_motorized_penalty`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_motorized_vehicles`
-- 

DROP TABLE IF EXISTS `ebpls_motorized_vehicles`;
CREATE TABLE IF NOT EXISTS `ebpls_motorized_vehicles` (
  `motorized_motor_id` int(11) NOT NULL auto_increment,
  `motorized_operator_id` int(11) default NULL,
  `motorized_motor_model` varchar(255) NOT NULL default '''',
  `motorized_motor_no` varchar(25) default NULL,
  `motorized_chassis_no` varchar(25) default NULL,
  `motorized_plate_no` varchar(25) default NULL,
  `motorized_body_no` varchar(25) default NULL,
  `admin` varchar(32) NOT NULL default '''',
  `status` int(1) default NULL,
  `route` varchar(255) default NULL,
  `linetype` varchar(255) default NULL,
  `updated_ts` datetime default NULL,
  `create_ts` datetime default NULL,
  `permit_type` varchar(20) default NULL,
  `body_color` varchar(255) default NULL,
  `lto_number` varchar(255) default NULL,
  `cr_number` varchar(255) default NULL,
  `transaction` varchar(20) default NULL,
  `retire` int(1) default '0',
  `paid` int(1) NOT NULL default '0',
  PRIMARY KEY  (`motorized_motor_id`),
  UNIQUE KEY `motorized_chassis_no` (`motorized_chassis_no`),
  UNIQUE KEY `motorized_motor_no` (`motorized_motor_no`),
  UNIQUE KEY `motorized_plate_no` (`motorized_plate_no`),
  UNIQUE KEY `motorized_body_no` (`motorized_body_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_motorized_vehicles`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_mtop_fees`
-- 

DROP TABLE IF EXISTS `ebpls_mtop_fees`;
CREATE TABLE IF NOT EXISTS `ebpls_mtop_fees` (
  `fee_id` int(15) unsigned NOT NULL auto_increment,
  `fee_desc` varchar(255) NOT NULL default '',
  `fee_amount` int(11) default NULL,
  `lastupdatedby` varchar(255) default NULL,
  `lastupdated` datetime default NULL,
  `permit_type` varchar(255) default NULL,
  `active` int(1) default '1',
  `nyears` int(2) NOT NULL default '0',
  PRIMARY KEY  (`fee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_mtop_fees`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_mtop_owner`
-- 

DROP TABLE IF EXISTS `ebpls_mtop_owner`;
CREATE TABLE IF NOT EXISTS `ebpls_mtop_owner` (
  `owner_id` int(11) NOT NULL auto_increment,
  `owner_first_name` varchar(60) NOT NULL default '',
  `owner_middle_name` varchar(60) NOT NULL default '',
  `owner_last_name` varchar(60) NOT NULL default '',
  `owner_house_no` varchar(255) NOT NULL default '',
  `owner_street` varchar(255) NOT NULL default '',
  `owner_barangay_code` varchar(20) NOT NULL default '',
  `owner_zone_code` varchar(20) NOT NULL default '',
  `owner_district_code` varchar(20) NOT NULL default '',
  `owner_city_code` varchar(20) NOT NULL default '',
  `owner_province_code` varchar(20) NOT NULL default '',
  `owner_zip_code` varchar(20) default NULL,
  `owner_citizenship` varchar(255) NOT NULL default '',
  `owner_civil_status` varchar(255) NOT NULL default '',
  `owner_gender` enum('M','F','X') default 'X',
  `owner_tin_no` varchar(255) NOT NULL default '',
  `owner_icr_no` varchar(20) default NULL,
  `owner_phone_no` varchar(255) default NULL,
  `owner_gsm_no` varchar(255) default NULL,
  `owner_email_address` varchar(255) default NULL,
  `owner_others` varchar(255) default NULL,
  `owner_birth_date` datetime default NULL,
  `owner_lastupdated_by` varchar(255) NOT NULL default '',
  `owner_reg_date` datetime default NULL,
  `owner_lastupdated` datetime default NULL,
  PRIMARY KEY  (`owner_id`,`owner_first_name`,`owner_middle_name`,`owner_last_name`),
  UNIQUE KEY `id` (`owner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_mtop_owner`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_mtop_temp_fees`
-- 

DROP TABLE IF EXISTS `ebpls_mtop_temp_fees`;
CREATE TABLE IF NOT EXISTS `ebpls_mtop_temp_fees` (
  `temp_fee_id` int(31) NOT NULL auto_increment,
  `fee_id` int(15) NOT NULL default '0',
  `owner_id` int(11) NOT NULL default '0',
  `mid` int(11) default NULL,
  `vno` char(2) NOT NULL default '',
  `lastupdatedby` varchar(255) default NULL,
  `lastupdated` datetime default NULL,
  `active` int(1) default '1',
  `year` varchar(4) NOT NULL default '0',
  PRIMARY KEY  (`temp_fee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_mtop_temp_fees`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_occu_fees`
-- 

DROP TABLE IF EXISTS `ebpls_occu_fees`;
CREATE TABLE IF NOT EXISTS `ebpls_occu_fees` (
  `fee_id` int(11) NOT NULL auto_increment,
  `fee_desc` varchar(255) default NULL,
  `fee_amount` int(10) default NULL,
  `permit_type` varchar(20) default NULL,
  `lastupdated` datetime default NULL,
  `lastupdatedby` varchar(20) default NULL,
  `active` int(1) default '1',
  PRIMARY KEY  (`fee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_occu_fees`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_occu_owner`
-- 

DROP TABLE IF EXISTS `ebpls_occu_owner`;
CREATE TABLE IF NOT EXISTS `ebpls_occu_owner` (
  `owner_id` int(11) NOT NULL auto_increment,
  `owner_first_name` varchar(60) NOT NULL default '',
  `owner_middle_name` varchar(60) NOT NULL default '',
  `owner_last_name` varchar(60) NOT NULL default '',
  `owner_house_no` varchar(255) NOT NULL default '',
  `owner_street` varchar(255) NOT NULL default '',
  `owner_barangay_code` varchar(20) NOT NULL default '',
  `owner_zone_code` varchar(20) NOT NULL default '',
  `owner_district_code` varchar(20) NOT NULL default '',
  `owner_city_code` varchar(20) NOT NULL default '',
  `owner_province_code` varchar(20) NOT NULL default '',
  `owner_zip_code` varchar(20) default NULL,
  `owner_citizenship` varchar(255) NOT NULL default '',
  `owner_civil_status` varchar(255) NOT NULL default '',
  `owner_gender` enum('M','F','X') default 'X',
  `owner_tin_no` varchar(255) NOT NULL default '',
  `owner_icr_no` varchar(20) default NULL,
  `owner_phone_no` varchar(255) default NULL,
  `owner_gsm_no` varchar(255) default NULL,
  `owner_email_address` varchar(255) default NULL,
  `owner_others` varchar(255) default NULL,
  `owner_birth_date` datetime default NULL,
  `owner_reg_date` datetime default NULL,
  `owner_lastupdated` datetime default NULL,
  `owner_lastupdated_by` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`owner_id`),
  UNIQUE KEY `id` (`owner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_occu_owner`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_occupancy_type`
-- 

DROP TABLE IF EXISTS `ebpls_occupancy_type`;
CREATE TABLE IF NOT EXISTS `ebpls_occupancy_type` (
  `occupancy_type_code` varchar(20) NOT NULL default '',
  `occupancy_type_desc` varchar(255) NOT NULL default '',
  `occupancy_type_date_registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `occupancy_type_date_updated` datetime default NULL,
  `updated_by` varchar(255) default NULL,
  PRIMARY KEY  (`occupancy_type_code`),
  UNIQUE KEY `id` (`occupancy_type_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ebpls_occupancy_type`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_occupational_permit`
-- 

DROP TABLE IF EXISTS `ebpls_occupational_permit`;
CREATE TABLE IF NOT EXISTS `ebpls_occupational_permit` (
  `occ_permit_id` int(15) NOT NULL auto_increment,
  `occ_permit_code` varchar(25) NOT NULL default '',
  `owner_id` int(11) NOT NULL default '0',
  `occ_permit_application_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `occ_position_applied` varchar(255) NOT NULL default '',
  `occ_employer` varchar(255) NOT NULL default '',
  `occ_employer_trade_name` varchar(255) NOT NULL default '',
  `occ_employer_lot_no` varchar(255) NOT NULL default '',
  `occ_employer_street` varchar(255) NOT NULL default '',
  `occ_employer_barangay_code` varchar(20) NOT NULL default '',
  `occ_employer_zone_code` varchar(20) NOT NULL default '',
  `occ_employer_barangay_name` varchar(255) NOT NULL default '',
  `occ_employer_district_code` varchar(20) NOT NULL default '',
  `occ_employer_city_code` varchar(20) NOT NULL default '',
  `occ_employer_province_code` varchar(20) NOT NULL default '',
  `occ_employer_zip_code` varchar(20) NOT NULL default '',
  `for_year` varchar(60) NOT NULL default '',
  `paid` int(1) default NULL,
  `released` int(1) default '0',
  `transaction` varchar(20) default NULL,
  `steps` varchar(50) default NULL,
  `pin` varchar(10) default NULL,
  `active` int(1) default '1',
  `business_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`occ_permit_id`),
  UNIQUE KEY `id` (`occ_permit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_occupational_permit`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_other_penalty`
-- 

DROP TABLE IF EXISTS `ebpls_other_penalty`;
CREATE TABLE IF NOT EXISTS `ebpls_other_penalty` (
  `id` int(11) NOT NULL auto_increment,
  `renewaldate` varchar(5) default NULL,
  `rateofpenalty` varchar(10) default NULL,
  `rateofinterest` varchar(10) default NULL,
  `indicator` int(1) default NULL,
  `status` int(1) default NULL,
  `intype` int(1) NOT NULL default '0',
  `feeonly` int(1) NOT NULL default '0',
  `late_filing_fee` decimal(12,2) NOT NULL default '0.00',
  `f_status` int(1) NOT NULL default '0',
  `backtax` int(1) NOT NULL default '0',
  `permit_type` varchar(20) NOT NULL default '',
  `updated_by` varchar(50) NOT NULL default '',
  `date_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_other_penalty`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_other_penalty_amount`
-- 

DROP TABLE IF EXISTS `ebpls_other_penalty_amount`;
CREATE TABLE IF NOT EXISTS `ebpls_other_penalty_amount` (
  `id` int(21) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL default '0',
  `permit_type` varchar(50) NOT NULL default '',
  `permit_id` int(11) NOT NULL default '0',
  `amount` decimal(12,2) NOT NULL default '0.00',
  `bt` decimal(12,2) NOT NULL default '0.00',
  `year` varchar(4) NOT NULL default '',
  `updated_by` varchar(255) NOT NULL default '',
  `date_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_other_penalty_amount`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_owner`
-- 

DROP TABLE IF EXISTS `ebpls_owner`;
CREATE TABLE IF NOT EXISTS `ebpls_owner` (
  `owner_id` int(11) NOT NULL auto_increment,
  `owner_first_name` varchar(60) NOT NULL default '',
  `owner_middle_name` varchar(60) NOT NULL default '',
  `owner_last_name` varchar(60) NOT NULL default '',
  `owner_legal_entity` varchar(250) NOT NULL default '',
  `owner_house_no` varchar(255) NOT NULL default '',
  `owner_street` varchar(255) NOT NULL default '',
  `owner_barangay_code` varchar(20) NOT NULL default '',
  `owner_zone_code` varchar(20) NOT NULL default '',
  `owner_district_code` varchar(20) NOT NULL default '',
  `owner_city_code` varchar(20) NOT NULL default '',
  `owner_province_code` varchar(100) NOT NULL default '',
  `owner_zip_code` varchar(20) default NULL,
  `owner_citizenship` varchar(255) NOT NULL default '',
  `owner_civil_status` varchar(255) NOT NULL default '',
  `owner_gender` enum('M','F','X') default 'X',
  `owner_tin_no` varchar(255) NOT NULL default '',
  `owner_icr_no` varchar(20) default NULL,
  `owner_phone_no` varchar(255) default NULL,
  `owner_gsm_no` varchar(255) default NULL,
  `owner_email_address` varchar(255) default NULL,
  `owner_others` varchar(255) default NULL,
  `owner_birth_date` datetime default NULL,
  `owner_reg_date` datetime default NULL,
  `owner_lastupdated` datetime default NULL,
  `owner_lastupdated_by` varchar(255) NOT NULL default '',
  `edit_by` varchar(50) NOT NULL default '',
  `edit_locked` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`owner_id`,`owner_first_name`,`owner_middle_name`,`owner_last_name`),
  UNIQUE KEY `id` (`owner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_owner`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_peddlers_fees`
-- 

DROP TABLE IF EXISTS `ebpls_peddlers_fees`;
CREATE TABLE IF NOT EXISTS `ebpls_peddlers_fees` (
  `fee_id` int(11) NOT NULL auto_increment,
  `fee_desc` varchar(255) default NULL,
  `fee_amount` int(10) default NULL,
  `permit_type` varchar(20) default NULL,
  `lastupdated` datetime default NULL,
  `lastupdatedby` varchar(20) default NULL,
  `active` int(1) default '1',
  PRIMARY KEY  (`fee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_peddlers_fees`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_peddlers_permit`
-- 

DROP TABLE IF EXISTS `ebpls_peddlers_permit`;
CREATE TABLE IF NOT EXISTS `ebpls_peddlers_permit` (
  `peddlers_permit_id` int(15) unsigned zerofill NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL default '0',
  `merchandise_sold` varchar(255) NOT NULL default '',
  `peddlers_business_name` varchar(255) NOT NULL default '',
  `retirement_code` varchar(20) default NULL,
  `retirement_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `retirement_date_processed` datetime NOT NULL default '0000-00-00 00:00:00',
  `for_year` varchar(60) NOT NULL default '',
  `peddlers_permit_code` varchar(25) NOT NULL default '',
  `application_date` date NOT NULL default '0000-00-00',
  `paid` int(1) default '0',
  `transaction` varchar(100) default NULL,
  `released` int(1) default NULL,
  `steps` varchar(50) default NULL,
  `pin` varchar(10) default NULL,
  `active` int(1) default '1',
  PRIMARY KEY  (`peddlers_permit_id`),
  UNIQUE KEY `id` (`peddlers_permit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_peddlers_permit`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_province`
-- 

DROP TABLE IF EXISTS `ebpls_province`;
CREATE TABLE IF NOT EXISTS `ebpls_province` (
  `province_code` int(255) NOT NULL auto_increment,
  `province_desc` varchar(255) NOT NULL default '',
  `province_date_registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `province_date_updated` datetime default NULL,
  `updated_by` varchar(255) default NULL,
  `blgf_code` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`province_code`),
  UNIQUE KEY `id` (`province_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_province`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_psic_table`
-- 

DROP TABLE IF EXISTS `ebpls_psic_table`;
CREATE TABLE IF NOT EXISTS `ebpls_psic_table` (
  `psic_code` varchar(20) NOT NULL default '',
  `psic_desc` varchar(255) NOT NULL default '',
  `psic_account_code` varchar(20) NOT NULL default '',
  `psic_amount` decimal(12,2) NOT NULL default '0.00',
  `psic_percentage_factor` decimal(12,2) NOT NULL default '0.00',
  `psic_date_registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `psic_date_updated` datetime default NULL,
  PRIMARY KEY  (`psic_code`,`psic_account_code`),
  UNIQUE KEY `id` (`psic_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ebpls_psic_table`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_reports`
-- 

DROP TABLE IF EXISTS `ebpls_reports`;
CREATE TABLE IF NOT EXISTS `ebpls_reports` (
  `report_id` int(11) NOT NULL auto_increment,
  `report_desc` varchar(200) NOT NULL default '',
  `report_file` varchar(200) NOT NULL default '',
  `report_type` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`report_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

-- 
-- Dumping data for table `ebpls_reports`
-- 

INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (1, 'Blacklisted Business Establishment', 'ebpls_buslist_blacklist.php', 'Business');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (2, 'Activity Log List', 'ebpls_activity_log.php', 'System');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (3, 'Abstract of Collection', 'ebpls_abstractcoll.php', 'Collection');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (4, 'Business Masterlist', 'ebpls_bus_masterlist.php', 'Business');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (5, 'Business Permit', 'ebpls_buss_permit.php', 'Business');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (6, 'Comparative Annual Report', 'ebpls_comparative_annual.php', 'Collection');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (7, 'Comparative Quarterly Report', 'ebpls_comparative_rpts_quart.php', 'Collection');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (8, 'CTC Business Application Masterlist', 'ebpls_ctc_apply_masterlistbus.php', 'CTC');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (9, 'CTC Individual Application Masterlist', 'ebpls_ctc_apply_masterlist.php', 'CTC');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (10, 'Exempted Business Establishment', 'ebpls_bus_exemptedlist_full.php', 'Business');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (11, 'Fishery Permit', 'ebpls_fish_permit.php', 'Fishery');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (12, 'Franchise Registry', 'ebpls_franchise_list.php', 'Franchise');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (13, 'Individual Tax Delinquent List', 'ebpls_bus_indtaxdelinquent.php', 'Collection');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (14, 'List of Business Requirement Delinquent', 'ebpls_summary_busreq_delinquent.php', 'Business');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (15, 'Masterlist of Motorized Vehicles', 'ebpls_masterlist_motor.php', 'Motorized');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (16, 'Motorized Permit', 'ebpls_motor_permit.php', 'Motorized');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (17, 'Notice of Business Tax Collection', 'ebpls_notice_bustax_coll.php', 'Collection');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (18, 'Occupational Permit', 'ebpls_occ_permit.php', 'Occupational');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (19, 'Order of Payment', 'ebpls_orderpayment.php', 'Collection');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (20, 'Top Business Establishment', 'ebpls_bus_topestablishment.php', 'Business');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (21, 'List of Establishment Without Permit', 'ebpls_bus_woutpermit.php', 'Business');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (22, 'Peddler Masterlist', 'ebpls_peddler_list.php', 'Peddler');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (23, 'Occupational Registry', 'ebpls_occupational_list.php', 'Occupational');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (24, 'Peddlers Permit', 'ebpls_peddler_permit.php', 'Peddler');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (25, 'List of Establishment', 'ebpls_bus_wpermit.php', 'Business');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (26, 'Collections Summary', 'ebpls_collection_summary.php', 'Collection');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (27, 'Comparative Annual Graph', 'ebpls_comparative_annual_chart.php', 'Collection');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (28, 'Fishery Registry', 'ebpls_fishery_list.php', 'Fishery');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (29, 'Franchise Permit', 'ebpls_motor_permit.php', 'Franchise');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (30, 'Audit Trail', 'ebpls_audit_trail.php', 'Collection');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (31, 'Business Establishment Comparative', 'ebpls_comparative_business.php', 'Business');
INSERT INTO `ebpls_reports` (`report_id`, `report_desc`, `report_file`, `report_type`) VALUES (32, 'Business Profile', 'ebpls_business_profile.php', 'Business');

-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_tax_exemption`
-- 

DROP TABLE IF EXISTS `ebpls_tax_exemption`;
CREATE TABLE IF NOT EXISTS `ebpls_tax_exemption` (
  `tax_exemption_code` varchar(20) NOT NULL default '',
  `tax_exemption_desc` varchar(255) NOT NULL default '',
  `tax_exempt_account_code` varchar(20) NOT NULL default '',
  `tax_exemption_date_registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `tax_exemption_date_updated` datetime default NULL,
  PRIMARY KEY  (`tax_exemption_code`),
  UNIQUE KEY `id` (`tax_exemption_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ebpls_tax_exemption`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_transaction_payment_check`
-- 

DROP TABLE IF EXISTS `ebpls_transaction_payment_check`;
CREATE TABLE IF NOT EXISTS `ebpls_transaction_payment_check` (
  `check_id` int(15) NOT NULL auto_increment,
  `check_no` varchar(25) NOT NULL default '',
  `check_status` enum('PENDING','CLEARED','BOUNCED') default NULL,
  `ts_create` datetime NOT NULL default '0000-00-00 00:00:00',
  `ts_clear` datetime NOT NULL default '0000-00-00 00:00:00',
  `admin` varchar(25) NOT NULL default '',
  `ts_last_update` datetime NOT NULL default '0000-00-00 00:00:00',
  `check_name` varchar(25) NOT NULL default '',
  `check_issue_date` date default '0000-00-00',
  `check_amount` decimal(12,2) NOT NULL default '0.00',
  `trans_id` int(15) NOT NULL default '0',
  `or_no` varchar(25) NOT NULL default '',
  `remark` text,
  PRIMARY KEY  (`check_id`),
  UNIQUE KEY `check_no` (`check_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_transaction_payment_check`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_transaction_payment_or`
-- 

DROP TABLE IF EXISTS `ebpls_transaction_payment_or`;
CREATE TABLE IF NOT EXISTS `ebpls_transaction_payment_or` (
  `or_no` int(20) NOT NULL auto_increment,
  `payment_code` varchar(20) default NULL,
  `trans_id` int(15) default '0',
  `or_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `total_amount_due` decimal(12,2) NOT NULL default '0.00',
  `total_amount_less` decimal(12,2) NOT NULL default '0.00',
  `total_amount_paid` decimal(12,2) NOT NULL default '0.00',
  `ts_create` datetime default NULL,
  `payment_officer` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`or_no`),
  UNIQUE KEY `payment_code` (`payment_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_transaction_payment_or`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_transaction_payment_or_details`
-- 

DROP TABLE IF EXISTS `ebpls_transaction_payment_or_details`;
CREATE TABLE IF NOT EXISTS `ebpls_transaction_payment_or_details` (
  `or_detail_id` int(15) NOT NULL auto_increment,
  `or_no` varchar(20) NOT NULL default '',
  `trans_id` int(15) NOT NULL default '0',
  `payment_id` int(15) NOT NULL default '0',
  `fee_id` int(15) NOT NULL default '0',
  `tax_fee_code` varchar(64) NOT NULL default '''',
  `account_code` varchar(25) NOT NULL default '',
  `account_nature` enum('DEBIT','CREDIT') NOT NULL default 'CREDIT',
  `account_desc` varchar(255) NOT NULL default '',
  `amount_due` decimal(12,2) NOT NULL default '0.00',
  `or_entry_type` varchar(6) default NULL,
  `ts` datetime NOT NULL default '0000-00-00 00:00:00',
  `payment_part` int(1) default '1',
  `linepaid` int(1) default NULL,
  `nat_id` int(10) default NULL,
  `transaction` varchar(20) default NULL,
  `permit_type` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`or_detail_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_transaction_payment_or_details`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_transaction_requirements`
-- 

DROP TABLE IF EXISTS `ebpls_transaction_requirements`;
CREATE TABLE IF NOT EXISTS `ebpls_transaction_requirements` (
  `req_id` int(15) NOT NULL auto_increment,
  `trans_id` int(15) NOT NULL default '0',
  `permit_id` int(15) unsigned zerofill NOT NULL default '000000000000000',
  `permit_type` enum('BUS','OCC','PED','FRA','MOT','FIS') NOT NULL default 'BUS',
  `requirement_code` varchar(20) NOT NULL default '',
  `requirement_desc` varchar(255) default NULL,
  `reference_no` varchar(255) NOT NULL default '''',
  `status` enum('PENDING','SUBMITTED') NOT NULL default 'PENDING',
  `ts_submitted` datetime NOT NULL default '0000-00-00 00:00:00',
  `ts_create` datetime NOT NULL default '0000-00-00 00:00:00',
  `comment` varchar(255) NOT NULL default '',
  `last_updated_by` varchar(25) NOT NULL default '',
  `ts_update` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`req_id`),
  UNIQUE KEY `trans_permit_req` (`trans_id`,`permit_id`,`requirement_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_transaction_requirements`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_user`
-- 

DROP TABLE IF EXISTS `ebpls_user`;
CREATE TABLE IF NOT EXISTS `ebpls_user` (
  `id` int(11) NOT NULL auto_increment,
  `level` varchar(255) default NULL,
  `csgroup` int(11) default NULL,
  `username` varchar(255) default NULL,
  `password` varchar(255) default NULL,
  `lastname` varchar(255) default NULL,
  `firstname` varchar(255) default NULL,
  `designation` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `gsmnum` varchar(255) default NULL,
  `login` datetime default NULL,
  `logout` datetime default NULL,
  `lockout` datetime default NULL,
  `currthreads` int(11) default NULL,
  `roundrobinflag` datetime default NULL,
  `dateadded` datetime default NULL,
  `lastupdated` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `ebpls_user`
-- 

INSERT INTO `ebpls_user` (`id`, `level`, `csgroup`, `username`, `password`, `lastname`, `firstname`, `designation`, `email`, `gsmnum`, `login`, `logout`, `lockout`, `currthreads`, `roundrobinflag`, `dateadded`, `lastupdated`) VALUES (1, 'ã', NULL, 'ebpls', '°eæw', 'EBPLS', 'EBPLS', '', '', '', '2007-01-17 10:44:05', '2007-01-17 10:44:05', NULL, NULL, NULL, '2006-07-06 09:56:38', '2007-01-17 10:44:05');
INSERT INTO `ebpls_user` (`id`, `level`, `csgroup`, `username`, `password`, `lastname`, `firstname`, `designation`, `email`, `gsmnum`, `login`, `logout`, `lockout`, `currthreads`, `roundrobinflag`, `dateadded`, `lastupdated`) VALUES (2, 'ã', NULL, 'NCCFOO', '»]vìkL', 'FOO', 'NCC', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, '2006-07-06 09:57:11', '2006-12-20 02:44:05');
INSERT INTO `ebpls_user` (`id`, `level`, `csgroup`, `username`, `password`, `lastname`, `firstname`, `designation`, `email`, `gsmnum`, `login`, `logout`, `lockout`, `currthreads`, `roundrobinflag`, `dateadded`, `lastupdated`) VALUES (3, 'ã', NULL, 'admin', '°RrÿaA	4r', 'ebpls', 'administrator', 'ebpls administrator', '', '', '2007-01-17 10:43:47', '2007-01-17 10:43:47', NULL, NULL, NULL, '2007-01-17 10:43:47', '2007-01-17 10:43:47');

-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_user_sublevel`
-- 

DROP TABLE IF EXISTS `ebpls_user_sublevel`;
CREATE TABLE IF NOT EXISTS `ebpls_user_sublevel` (
  `title` varchar(255) NOT NULL default '',
  `menu` varchar(255) default NULL,
  `submenu` varchar(255) default NULL,
  `id` int(11) NOT NULL auto_increment,
  `rptvars` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx_duh` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=202 ;

-- 
-- Dumping data for table `ebpls_user_sublevel`
-- 

INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('CTC', 'Individual', '', 1, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('CTC', 'Business', '', 2, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('CTC', 'CTC Report', '', 3, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Application', 'New', 4, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Application', 'Renew', 5, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Application', 'Retire', 6, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Application', 'Search', 7, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Assessment', 'New', 8, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Assessment', 'Renew', 9, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Assessment', 'Retire', 10, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Assessment', 'Search', 11, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Payment', 'New', 12, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Payment', 'Renew', 13, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Payment', 'Retire', 14, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Payment', 'Search', 15, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Approval', 'New', 16, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Approval', 'Renew', 17, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Approval', 'Retire', 18, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Approval', 'Search', 19, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Releasing', 'New', 20, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Releasing', 'Renew', 21, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Releasing', 'Retire', 22, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Business Permit', 'Releasing', 'Search', 23, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Motorized Operator Permit', ' Application', 'New', 24, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Motorized Operator Permit', ' Application', 'Renew', 25, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Motorized Operator Permit', ' Application', 'Retire', 26, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Motorized Operator Permit', ' Application', 'Search', 27, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Motorized Operator Permit', ' Payment', 'New', 28, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Motorized Operator Permit', ' Payment', 'Renew', 29, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Motorized Operator Permit', ' Payment', 'Retire', 30, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Motorized Operator Permit', ' Payment', 'Search', 31, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Motorized Operator Permit', ' Releasing', 'New', 32, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Motorized Operator Permit', ' Releasing', 'Renew', 33, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Motorized Operator Permit', ' Releasing', 'Retire', 34, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Motorized Operator Permit', ' Releasing', 'Search', 114, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Occupational Permit', ' Application', 'New', 35, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Occupational Permit', ' Application', 'Renew', 36, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Occupational Permit', ' Application', 'Retire', 37, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Occupational Permit', ' Application', 'Search', 38, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Occupational Permit', ' Payment', 'New', 39, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Occupational Permit', ' Payment', 'Renew', 40, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Occupational Permit', ' Payment', 'Retire', 41, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Occupational Permit', ' Payment', 'Search', 42, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Occupational Permit', ' Releasing', 'New', 43, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Occupational Permit', ' Releasing', 'Renew', 44, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Occupational Permit', ' Releasing', 'Retire', 45, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Occupational Permit', ' Releasing', 'Search', 46, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Peddlers Permit', 'Application', 'New', 47, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Peddlers Permit', 'Application', 'Renew', 48, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Peddlers Permit', 'Application', 'Retire', 49, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Peddlers Permit', 'Application', 'Search', 50, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Peddlers Permit', 'Payment', 'New', 51, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Peddlers Permit', 'Payment', 'Renew', 52, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Peddlers Permit', 'Payment', 'Retire', 53, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Peddlers Permit', 'Payment', 'Search', 54, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Peddlers Permit', 'Releasing', 'New', 55, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Peddlers Permit', 'Releasing', 'Renew', 56, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Peddlers Permit', 'Releasing', 'Retire', 57, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Peddlers Permit', 'Releasing', 'Search', 58, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Franchise Permit', ' Application', 'New', 59, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Franchise Permit', ' Application', 'Renew', 60, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Franchise Permit', ' Application', 'Retire', 61, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Franchise Permit', ' Application', 'Search', 62, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Franchise Permit', ' Payment', 'New', 63, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Franchise Permit', ' Payment', 'Renew', 64, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Franchise Permit', ' Payment', 'Retire', 65, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Franchise Permit', ' Payment', 'Search', 66, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Franchise Permit', ' Releasing', 'New', 67, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Franchise Permit', ' Releasing', 'Renew', 68, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Franchise Permit', ' Releasing', 'Retire', 69, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Franchise Permit', ' Releasing', 'Search', 70, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Fishery Permit', 'Application', 'New', 71, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Fishery Permit', 'Application', 'Renew', 72, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Fishery Permit', 'Application', 'Retire', 73, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Fishery Permit', 'Application', 'Search', 74, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Fishery Permit', 'Payment', 'New', 75, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Fishery Permit', 'Payment', 'Renew', 76, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Fishery Permit', 'Payment', 'Retire', 77, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Fishery Permit', 'Payment', 'Search', 78, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Fishery Permit', 'Releasing', 'New', 79, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Fishery Permit', 'Releasing', 'Renew', 80, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Fishery Permit', 'Releasing', 'Retire', 81, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Fishery Permit', 'Releasing', 'Search', 82, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Business Permit', 'Tax/Fee/Other Charges', 83, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Business Permit', 'Business Nature', 84, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Business Permit', 'Business Requirements', 85, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'General Settings', '', 86, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Other Permit Fees', '', 87, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'LGU Listings', '', 88, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Zip Codes', '', 89, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Province Codes', '', 90, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'District Codes', '', 91, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Barangay Listings', '', 92, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Zone Listings', '', 93, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Ownership Types', '', 94, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Penalty Settings', '', 95, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Report Signatories', '', 96, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Settings', 'User Manager', '', 97, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Settings', 'Activity Logs', '', 98, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Settings', 'Color Scheme Preferences', '', 99, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Settings', 'System Settings', '', 100, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Collection', 'Order of Payment', 195, 'col7');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Occupancy Type', NULL, 102, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Industry Sector', NULL, 103, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Fishery Permit Fees', 'Boat Fee', 104, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Fishery Permit Fees', 'Fish Activities Fee', 105, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'CTC Settings', '', 106, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Pemit Number Format', '', 107, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Announcement', '', 108, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Chart of Accounts', '', 109, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Citizenship', NULL, 110, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Lot Pin', '', 111, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'FAQ', '', 112, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Links', '', 113, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Collection', 'Notice of Business Tax Collection', 194, 'col6');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Collection', 'Individual Tax Delinquent List', 193, 'col5');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Collection', 'Comparative Quarterly Report', 192, 'col4');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Collection', 'Comparative Annual Report', 191, 'col3');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Collection', 'Abstract of Collection', 190, 'col2');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'System', 'Activity Log List', 189, 'sys2');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'CTC', 'CTC Individual Application Masterlist', 188, 'ctl3');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Peddler', 'Peddlers Permit', 186, 'ppl3');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'CTC', 'CTC Business Application Masterlist', 187, 'ctl2');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Peddler', 'Peddler Masterlist', 185, 'ppl2');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Fishery', 'Fishery Registry', 184, 'fil3');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Occupational', 'Occupational Registry', 182, 'ocl3');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Fishery', 'Fishery Permit', 183, 'fil2');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Occupational', 'Occupational Permit', 181, 'ocl2');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Franchise', 'Franchise Permit', 179, 'fpl2');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Franchise', 'Franchise Registry', 180, 'fpl3');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Motorized', 'Motorized Permit', 178, 'mpl3');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Motorized', 'Masterlist of Motorized Vehicles', 177, 'mpl2');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Business', 'Business Establishment Comparative', 176, 'brm11');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Business', 'Business Profile', 175, 'brm10');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Business', 'List of Establishment', 174, 'brm9');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Business', 'List of Establishment Without Permit', 173, 'brm8');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Business', 'Top Business Establishment', 172, 'brm7');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Business', 'List of Business Requirement Delinquent', 171, 'brm6');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Business', 'Exempted Business Establishment', 170, 'brm5');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Business', 'Business Permit', 169, 'brm4');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Business', 'Business Masterlist', 168, 'brm3');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Business', 'Blacklisted Business Establishment', 167, 'brm2');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Collection', 'Collections Summary', 196, 'col8');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Collection', 'Comparative Annual Graph', 197, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Collection', 'Audit Trail', 198, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('Reports Manager', 'Collection', 'Abstract of CTC Issued', 199, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Engine Type', NULL, 200, '');
INSERT INTO `ebpls_user_sublevel` (`title`, `menu`, `submenu`, `id`, `rptvars`) VALUES ('References', 'Fishery Activity Description', NULL, 201, '');

-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_user_sublevel_listings`
-- 

DROP TABLE IF EXISTS `ebpls_user_sublevel_listings`;
CREATE TABLE IF NOT EXISTS `ebpls_user_sublevel_listings` (
  `user_id` int(11) NOT NULL default '0',
  `sublevel_id` int(11) NOT NULL default '0',
  UNIQUE KEY `user_id` (`user_id`,`sublevel_id`),
  KEY `idx_duh` (`user_id`,`sublevel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ebpls_user_sublevel_listings`
-- 

INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 1);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 2);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 3);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 4);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 5);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 6);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 7);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 8);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 9);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 10);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 11);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 12);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 13);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 14);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 15);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 16);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 17);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 18);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 19);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 20);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 21);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 22);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 23);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 24);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 25);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 26);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 27);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 28);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 29);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 30);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 31);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 32);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 33);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 34);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 35);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 36);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 37);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 38);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 39);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 40);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 41);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 42);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 43);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 44);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 45);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 46);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 47);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 48);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 49);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 50);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 51);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 52);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 53);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 54);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 55);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 56);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 57);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 58);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 59);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 60);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 61);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 62);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 63);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 64);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 65);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 66);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 67);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 68);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 69);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 70);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 71);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 72);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 73);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 74);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 75);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 76);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 77);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 78);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 79);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 80);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 81);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 82);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 83);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 84);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 85);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 86);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 87);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 88);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 89);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 90);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 91);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 92);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 93);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 94);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 95);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 96);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 97);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 98);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 99);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 100);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 102);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 103);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 104);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 105);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 106);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 107);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 108);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 109);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 110);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 111);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 112);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 113);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 114);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 167);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 168);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 169);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 170);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 171);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 172);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 173);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 174);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 175);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 176);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 177);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 178);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 179);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 180);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 181);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 182);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 183);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 184);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 185);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 186);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 187);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 188);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 189);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 190);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 191);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 192);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 193);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 194);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 195);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 196);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 197);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 198);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 199);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 200);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (1, 201);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 1);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 2);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 3);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 4);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 5);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 6);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 7);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 8);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 9);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 10);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 11);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 12);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 13);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 14);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 15);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 16);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 17);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 18);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 19);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 20);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 21);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 22);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 23);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 24);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 25);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 26);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 27);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 28);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 29);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 30);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 31);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 32);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 33);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 34);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 35);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 36);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 37);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 38);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 39);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 40);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 41);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 42);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 43);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 44);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 45);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 46);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 47);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 48);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 49);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 50);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 51);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 52);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 53);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 54);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 55);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 56);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 57);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 58);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 59);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 60);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 61);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 62);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 63);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 64);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 65);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 66);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 67);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 68);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 69);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 70);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 71);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 72);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 73);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 74);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 75);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 76);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 77);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 78);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 79);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 80);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 81);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 82);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 83);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 84);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 85);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 86);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 87);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 88);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 89);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 90);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 91);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 92);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 93);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 94);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 95);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 96);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 97);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 98);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 99);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 100);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 102);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 103);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 104);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 105);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 106);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 107);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 108);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 109);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 110);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 111);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 112);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 113);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 114);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 167);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 168);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 169);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 170);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 171);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 172);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 173);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 174);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 175);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 176);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 177);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 178);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 179);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 180);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 181);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 182);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 183);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 184);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 185);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 186);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 187);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 188);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 189);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 190);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 191);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 192);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 193);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 194);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 195);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 196);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 197);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 198);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 199);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 200);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (2, 201);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 1);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 2);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 3);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 4);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 5);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 6);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 7);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 8);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 9);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 10);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 11);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 12);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 13);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 14);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 15);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 16);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 17);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 18);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 19);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 20);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 21);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 22);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 23);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 24);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 25);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 26);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 27);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 28);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 29);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 30);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 31);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 32);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 33);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 34);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 35);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 36);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 37);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 38);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 39);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 40);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 41);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 42);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 43);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 44);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 45);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 46);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 47);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 48);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 49);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 50);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 51);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 52);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 53);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 54);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 55);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 56);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 57);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 58);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 71);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 72);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 73);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 74);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 75);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 76);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 77);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 78);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 79);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 80);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 81);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 82);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 83);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 84);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 85);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 86);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 87);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 88);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 89);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 90);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 91);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 92);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 93);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 94);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 95);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 96);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 97);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 98);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 99);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 100);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 102);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 103);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 104);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 105);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 106);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 107);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 108);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 109);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 110);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 111);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 112);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 113);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 114);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 167);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 168);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 169);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 170);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 171);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 172);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 173);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 174);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 175);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 176);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 177);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 178);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 179);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 180);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 181);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 182);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 183);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 184);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 185);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 186);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 187);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 188);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 189);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 190);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 191);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 192);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 193);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 194);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 195);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 196);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 197);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 198);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 199);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 200);
INSERT INTO `ebpls_user_sublevel_listings` (`user_id`, `sublevel_id`) VALUES (3, 201);

-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_zip`
-- 

DROP TABLE IF EXISTS `ebpls_zip`;
CREATE TABLE IF NOT EXISTS `ebpls_zip` (
  `zip_code` varchar(255) NOT NULL default '',
  `zip_desc` varchar(255) NOT NULL default '',
  `zip_date_registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `zip_date_updated` datetime default NULL,
  `updated_by` varchar(255) default NULL,
  `g_zone` int(1) NOT NULL default '0',
  `upper` varchar(255) NOT NULL default '',
  `blgf_code` varchar(10) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ebpls_zip`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ebpls_zone`
-- 

DROP TABLE IF EXISTS `ebpls_zone`;
CREATE TABLE IF NOT EXISTS `ebpls_zone` (
  `zone_code` int(10) NOT NULL auto_increment,
  `zone_desc` varchar(255) NOT NULL default '',
  `zone_date_registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `zone_date_updated` datetime default NULL,
  `updated_by` varchar(255) default NULL,
  `g_zone` int(1) NOT NULL default '0',
  `upper` varchar(255) NOT NULL default '',
  `blgf_code` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`zone_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ebpls_zone`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `faq`
-- 

DROP TABLE IF EXISTS `faq`;
CREATE TABLE IF NOT EXISTS `faq` (
  `faqid` int(10) NOT NULL auto_increment,
  `faq_question` text NOT NULL,
  `faq_answer` text NOT NULL,
  `last_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`faqid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `faq`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `fee_exempt`
-- 

DROP TABLE IF EXISTS `fee_exempt`;
CREATE TABLE IF NOT EXISTS `fee_exempt` (
  `ex_id` int(10) NOT NULL auto_increment,
  `business_category_code` varchar(20) default NULL,
  `tfoid` int(10) default NULL,
  `active` int(1) default NULL,
  PRIMARY KEY  (`ex_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `fee_exempt`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `fish_activity`
-- 

DROP TABLE IF EXISTS `fish_activity`;
CREATE TABLE IF NOT EXISTS `fish_activity` (
  `fish_id` int(10) NOT NULL auto_increment,
  `owner_id` int(10) default NULL,
  `act_fee` decimal(12,2) NOT NULL default '0.00',
  `transaction` varchar(20) NOT NULL default '',
  `date_created` datetime default NULL,
  `created_by` varchar(20) default NULL,
  `active` int(1) NOT NULL default '0',
  PRIMARY KEY  (`fish_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `fish_activity`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `fish_assess`
-- 

DROP TABLE IF EXISTS `fish_assess`;
CREATE TABLE IF NOT EXISTS `fish_assess` (
  `ass_id` int(10) NOT NULL auto_increment,
  `culture_id` int(10) default NULL,
  `owner_id` int(10) NOT NULL default '0',
  `amt` decimal(12,2) default NULL,
  `transaction` varchar(20) default NULL,
  `active` int(10) default NULL,
  PRIMARY KEY  (`ass_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `fish_assess`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `fish_boat`
-- 

DROP TABLE IF EXISTS `fish_boat`;
CREATE TABLE IF NOT EXISTS `fish_boat` (
  `boat_id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) default NULL,
  `boat_name` varchar(250) default NULL,
  `crew` int(10) default NULL,
  `engine_type` varchar(250) default NULL,
  `engine_cap` decimal(12,2) default NULL,
  `reg_no` varchar(250) default NULL,
  `date_created` datetime default NULL,
  `created_by` varchar(20) default NULL,
  `up_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `up_by` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`boat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `fish_boat`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `franchise_application`
-- 

DROP TABLE IF EXISTS `franchise_application`;
CREATE TABLE IF NOT EXISTS `franchise_application` (
  `rowid` int(10) NOT NULL auto_increment,
  `username` varchar(20) default NULL,
  `driver` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `toda` varchar(255) default NULL,
  `plate` varchar(20) default NULL,
  `body` varchar(30) default NULL,
  `app_date` datetime default NULL,
  `app_status_code` varchar(5) default NULL,
  `stat_date` datetime default NULL,
  PRIMARY KEY  (`rowid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `franchise_application`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `global_sign`
-- 

DROP TABLE IF EXISTS `global_sign`;
CREATE TABLE IF NOT EXISTS `global_sign` (
  `sign_id` int(10) NOT NULL auto_increment,
  `gs_name` varchar(255) default NULL,
  `gs_pos` varchar(255) default NULL,
  `gs_office` varchar(255) default NULL,
  PRIMARY KEY  (`sign_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `global_sign`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `havereq`
-- 

DROP TABLE IF EXISTS `havereq`;
CREATE TABLE IF NOT EXISTS `havereq` (
  `id` int(10) NOT NULL auto_increment,
  `reqid` int(10) default NULL,
  `owner_id` int(10) default NULL,
  `business_id` int(10) default NULL,
  `active` int(1) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `havereq`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `links`
-- 

DROP TABLE IF EXISTS `links`;
CREATE TABLE IF NOT EXISTS `links` (
  `linkid` int(10) NOT NULL auto_increment,
  `link` varchar(255) NOT NULL default '',
  `link_desc` varchar(255) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`linkid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `links`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `lot_pin`
-- 

DROP TABLE IF EXISTS `lot_pin`;
CREATE TABLE IF NOT EXISTS `lot_pin` (
  `pin_id` int(11) NOT NULL auto_increment,
  `lotpin` varchar(60) default NULL,
  `business_id` int(11) default NULL,
  `date_add` datetime default NULL,
  `user_add` varchar(20) default NULL,
  `parcel` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`pin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `lot_pin`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `main_branch`
-- 

DROP TABLE IF EXISTS `main_branch`;
CREATE TABLE IF NOT EXISTS `main_branch` (
  `branch_id` int(11) NOT NULL auto_increment,
  `business_main_offc_name` varchar(255) default NULL,
  `business_main_offc_lot_no` varchar(255) default NULL,
  `business_main_offc_street` varchar(255) default NULL,
  `business_main_offc_barangay_name` varchar(255) default NULL,
  `business_main_offc_barangay_code` varchar(20) default NULL,
  `business_main_offc_zone_code` varchar(20) default NULL,
  `business_main_offc_district_code` varchar(20) default NULL,
  `business_main_offc_city_code` varchar(20) default NULL,
  `business_main_offc_zip_code` varchar(20) default NULL,
  `business_main_offc_tin_no` varchar(255) default NULL,
  `main_offc_phone` varchar(20) NOT NULL default '',
  `main_office_prov` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`branch_id`),
  UNIQUE KEY `id` (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `main_branch`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `motor_application`
-- 

DROP TABLE IF EXISTS `motor_application`;
CREATE TABLE IF NOT EXISTS `motor_application` (
  `rowid` int(10) NOT NULL auto_increment,
  `username` varchar(20) default NULL,
  `driver` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `toda` varchar(255) default NULL,
  `plate` varchar(20) default NULL,
  `body` varchar(30) default NULL,
  `app_date` datetime default NULL,
  `app_status_code` varchar(5) default NULL,
  `stat_date` datetime default NULL,
  PRIMARY KEY  (`rowid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `motor_application`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `msg_board`
-- 

DROP TABLE IF EXISTS `msg_board`;
CREATE TABLE IF NOT EXISTS `msg_board` (
  `rowid` int(10) NOT NULL auto_increment,
  `username` varchar(30) default NULL,
  `msg_date` datetime default NULL,
  `message` text,
  PRIMARY KEY  (`rowid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `msg_board`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `permit_templates`
-- 

DROP TABLE IF EXISTS `permit_templates`;
CREATE TABLE IF NOT EXISTS `permit_templates` (
  `tempid` int(3) NOT NULL auto_increment,
  `permit_type` varchar(20) default NULL,
  `user` varchar(10) default NULL,
  `date_entered` datetime default NULL,
  `permit_header` varchar(10) default NULL,
  `permit_date` int(1) default NULL,
  `permit_sequence` int(11) unsigned zerofill default NULL,
  PRIMARY KEY  (`tempid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `permit_templates`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `renew_vehicle`
-- 

DROP TABLE IF EXISTS `renew_vehicle`;
CREATE TABLE IF NOT EXISTS `renew_vehicle` (
  `renew_id` int(21) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL default '0',
  `motorized_motor_id` int(11) NOT NULL default '0',
  `paid` int(1) NOT NULL default '0',
  `updated_by` varchar(50) NOT NULL default '',
  `date_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`renew_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `renew_vehicle`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `report_signatories`
-- 

DROP TABLE IF EXISTS `report_signatories`;
CREATE TABLE IF NOT EXISTS `report_signatories` (
  `rs_id` int(10) NOT NULL auto_increment,
  `report_file` varchar(100) default NULL,
  `sign_id` int(10) default NULL,
  `date_updated` datetime default NULL,
  `updated_by` varchar(20) default NULL,
  `sign_type` char(1) NOT NULL default '',
  PRIMARY KEY  (`rs_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `report_signatories`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `restore`
-- 

DROP TABLE IF EXISTS `restore`;
CREATE TABLE IF NOT EXISTS `restore` (
  `id` int(4) NOT NULL auto_increment,
  `restoretime` datetime NOT NULL default '0000-00-00 00:00:00',
  `data` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `restore`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `rpt_temp_abs`
-- 

DROP TABLE IF EXISTS `rpt_temp_abs`;
CREATE TABLE IF NOT EXISTS `rpt_temp_abs` (
  `rpt_id` int(10) NOT NULL auto_increment,
  `tfoid` int(10) default NULL,
  PRIMARY KEY  (`rpt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `rpt_temp_abs`
-- 

INSERT INTO `rpt_temp_abs` (`rpt_id`, `tfoid`) VALUES (1, 1);
INSERT INTO `rpt_temp_abs` (`rpt_id`, `tfoid`) VALUES (4, 4);
INSERT INTO `rpt_temp_abs` (`rpt_id`, `tfoid`) VALUES (3, 3);
INSERT INTO `rpt_temp_abs` (`rpt_id`, `tfoid`) VALUES (5, 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `sms`
-- 

DROP TABLE IF EXISTS `sms`;
CREATE TABLE IF NOT EXISTS `sms` (
  `smsid` int(10) NOT NULL auto_increment,
  `telnum` varchar(30) default NULL,
  `msg` text,
  `daterec` datetime default NULL,
  PRIMARY KEY  (`smsid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `sms`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `sms_archive`
-- 

DROP TABLE IF EXISTS `sms_archive`;
CREATE TABLE IF NOT EXISTS `sms_archive` (
  `archive_id` int(10) NOT NULL auto_increment,
  `telnum` varchar(20) default NULL,
  `msg` text,
  `datesend` datetime default NULL,
  PRIMARY KEY  (`archive_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `sms_archive`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `sms_message`
-- 

DROP TABLE IF EXISTS `sms_message`;
CREATE TABLE IF NOT EXISTS `sms_message` (
  `msgid` int(10) NOT NULL auto_increment,
  `keyword` varchar(50) default NULL,
  `full_message` text,
  `dateupdated` datetime default NULL,
  `updateby` varchar(10) default NULL,
  PRIMARY KEY  (`msgid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `sms_message`
-- 

INSERT INTO `sms_message` (`msgid`, `keyword`, `full_message`, `dateupdated`, `updateby`) VALUES (1, 'help', 'Available keywords: status <pin>, amountdue <pin>, duedate <pin> ', NULL, NULL);
INSERT INTO `sms_message` (`msgid`, `keyword`, `full_message`, `dateupdated`, `updateby`) VALUES (2, 'status', NULL, NULL, NULL);
INSERT INTO `sms_message` (`msgid`, `keyword`, `full_message`, `dateupdated`, `updateby`) VALUES (3, 'amountdue', NULL, NULL, NULL);
INSERT INTO `sms_message` (`msgid`, `keyword`, `full_message`, `dateupdated`, `updateby`) VALUES (4, 'duedate', NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `sms_send`
-- 

DROP TABLE IF EXISTS `sms_send`;
CREATE TABLE IF NOT EXISTS `sms_send` (
  `smsid` int(10) NOT NULL auto_increment,
  `telnum` varchar(15) default NULL,
  `message` text,
  `new_sms` int(1) default NULL,
  `datesent` datetime default NULL,
  PRIMARY KEY  (`smsid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `sms_send`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `tempassess`
-- 

DROP TABLE IF EXISTS `tempassess`;
CREATE TABLE IF NOT EXISTS `tempassess` (
  `assid` varchar(10) default NULL,
  `owner_id` int(10) default NULL,
  `business_id` int(10) default NULL,
  `natureid` int(10) default NULL,
  `taxfeeid` int(10) default NULL,
  `multi` decimal(10,2) default NULL,
  `amt` decimal(12,2) default NULL,
  `formula` varchar(50) default NULL,
  `isfee` int(1) default NULL,
  `compval` decimal(12,2) default NULL,
  `tfoid` int(10) default NULL,
  `active` int(1) NOT NULL default '0',
  `transaction` varchar(20) NOT NULL default '',
  `date_create` datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `tempassess`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `tempbusnature`
-- 

DROP TABLE IF EXISTS `tempbusnature`;
CREATE TABLE IF NOT EXISTS `tempbusnature` (
  `tempid` int(4) NOT NULL auto_increment,
  `bus_code` varchar(10) default NULL,
  `bus_nature` varchar(255) default NULL,
  `cap_inv` decimal(10,2) default NULL,
  `last_yr` decimal(10,2) default NULL,
  `owner_id` int(10) default NULL,
  `business_id` int(10) default NULL,
  `date_create` datetime default NULL,
  `linepaid` int(1) default '0',
  `active` int(1) default NULL,
  `retire` int(1) default NULL,
  `transaction` varchar(20) default NULL,
  `recpaid` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`tempid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `tempbusnature`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `tempfees`
-- 

DROP TABLE IF EXISTS `tempfees`;
CREATE TABLE IF NOT EXISTS `tempfees` (
  `fee_id` int(11) default NULL,
  `fee_desc` varchar(255) default NULL,
  `fee_amount` int(10) default NULL,
  `permit_type` varchar(255) default NULL,
  `owner_id` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `tempfees`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `temppayment`
-- 

DROP TABLE IF EXISTS `temppayment`;
CREATE TABLE IF NOT EXISTS `temppayment` (
  `payid` int(11) NOT NULL auto_increment,
  `payamt` decimal(12,2) default NULL,
  `owner_id` int(11) default NULL,
  `permit_type` varchar(255) default NULL,
  `pay_date` datetime default NULL,
  `status` int(1) default NULL,
  `pay_type` varchar(20) default NULL,
  `permit_status` varchar(20) default NULL,
  `or_no` varchar(20) default NULL,
  PRIMARY KEY  (`payid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `temppayment`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `tempsms`
-- 

DROP TABLE IF EXISTS `tempsms`;
CREATE TABLE IF NOT EXISTS `tempsms` (
  `msgid` int(3) NOT NULL auto_increment,
  `msg` text,
  PRIMARY KEY  (`msgid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `tempsms`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `trans_his`
-- 

DROP TABLE IF EXISTS `trans_his`;
CREATE TABLE IF NOT EXISTS `trans_his` (
  `his_id` int(10) NOT NULL auto_increment,
  `orig_owner` int(10) default NULL,
  `trans_to` int(10) NOT NULL default '0',
  `business_id` int(10) default NULL,
  `date_trans` datetime default NULL,
  PRIMARY KEY  (`his_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `trans_his`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `user_info`
-- 

DROP TABLE IF EXISTS `user_info`;
CREATE TABLE IF NOT EXISTS `user_info` (
  `rowid` int(10) NOT NULL auto_increment,
  `username` varchar(30) default NULL,
  `fname` varchar(50) default NULL,
  `lname` varchar(50) default NULL,
  `mname` varchar(50) default NULL,
  `bdate` datetime default NULL,
  `cstatus` varchar(8) default NULL,
  `gender` char(1) default NULL,
  `citizenship` varchar(30) default NULL,
  `tin` varchar(30) default NULL,
  `address` varchar(60) default NULL,
  `city` varchar(30) default NULL,
  `province` varchar(30) default NULL,
  `zip` varchar(6) default NULL,
  `email` varchar(30) default NULL,
  `ebpls_owner_id` int(11) default NULL,
  PRIMARY KEY  (`rowid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `user_info`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `user_session`
-- 

DROP TABLE IF EXISTS `user_session`;
CREATE TABLE IF NOT EXISTS `user_session` (
  `ses_id` int(11) NOT NULL auto_increment,
  `ip_add` varchar(20) NOT NULL default '',
  `ses_code` varchar(100) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `date_input` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ses_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `user_session`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `vehicle_transfer`
-- 

DROP TABLE IF EXISTS `vehicle_transfer`;
CREATE TABLE IF NOT EXISTS `vehicle_transfer` (
  `trans_id` int(10) NOT NULL auto_increment,
  `motor_id` int(11) NOT NULL default '0',
  `old_owner` int(11) NOT NULL default '0',
  `new_owner` int(11) NOT NULL default '0',
  `date_transfer` datetime NOT NULL default '0000-00-00 00:00:00',
  `input_by` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`trans_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `vehicle_transfer`
-- 

