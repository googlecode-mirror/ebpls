-- phpMyAdmin SQL Dump
-- version 2.9.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Dec 06, 2007 at 01:42 AM
-- Server version: 5.0.24
-- PHP Version: 5.1.6
-- 
-- Database: 'ebpls'
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table 'ebpls_payment_details'
-- 

DROP TABLE IF EXISTS ebpls_payment_details;
CREATE TABLE ebpls_payment_details (
  payment_details_id int(21) NOT NULL auto_increment,
  payment_details varchar(255) NOT NULL default '',
  or_no varchar(255) NOT NULL default '',
  owner_id int(21) NOT NULL default '0',
  business_id int(21) NOT NULL default '0',
  natureid int(21) NOT NULL default '0',
  tfoid int(21) NOT NULL default '0',
  amount decimal(15,2) NOT NULL default '0.00',
  payment_part int(1) NOT NULL default '0',
  date_create datetime NOT NULL default '0000-00-00 00:00:00',
  created_by varchar(255) NOT NULL default '',
  PRIMARY KEY  (payment_details_id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
