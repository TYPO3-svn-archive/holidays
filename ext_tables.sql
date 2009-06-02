#
# Table structure for table 'tx_holidays_holidays'
#
CREATE TABLE tx_holidays_holidays (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	type int(11) DEFAULT '0' NOT NULL,
	day int(11) DEFAULT '0' NOT NULL,
	name text,
	country_exclude text,
	country_only text,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_holidays_holidaynames'
#
CREATE TABLE tx_holidays_holidaynames (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	holiday_uid int(11) DEFAULT '0' NOT NULL,
	language_uid int(11) DEFAULT '0' NOT NULL,
	local_name tinytext,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);
