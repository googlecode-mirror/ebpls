-- phpMyAdmin SQL Dump
-- version 2.9.0
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jul 20, 2010 at 02:51 PM
-- Server version: 4.1.19
-- PHP Version: 4.3.11
-- 
-- Database: 'ebpls'
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table 'ebpls_ctc_interest'
-- 

DROP TABLE IF EXISTS ebpls_ctc_interest;
CREATE TABLE ebpls_ctc_interest (
  id int(1) NOT NULL auto_increment,
  ctc_type varchar(255) default NULL,
  interest_rate int(3) default NULL,
  ceiling_rate decimal(10,2) NOT NULL default '0.00',
  penalty_date int(2) NOT NULL default '0',
  modified_date date default NULL,
  updated_by varchar(255) default NULL,
  PRIMARY KEY  (id)
)
ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table 'ebpls_ctc_interest'
-- 

DROP TABLE IF EXISTS ebpls_activity_log;
CREATE TABLE ebpls_activity_log (
  act_id int(11) NOT NULL auto_increment,
  user_log varchar(50) collate latin1_general_ci NOT NULL default '',
  logged text collate latin1_general_ci NOT NULL,
  date_input datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (act_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
