
CREATE TABLE  IF NOT EXISTS `#__custom_properties` (
  `id` int(11) NOT NULL auto_increment,
  `ref_table` varchar(100) NOT NULL default 'content',
  `content_id` int(11) NOT NULL default '0',
  `field_id` int(11) NOT NULL default '0',
  `value_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `t_c_f_v` (`ref_table`,`content_id`,`field_id`,`value_id`),
  KEY `content_id` (`content_id`),
  KEY `cp_field_id` (`field_id`),
  KEY `cp_value_id` (`value_id`),
  KEY `ref_table` (`ref_table`)
) ENGINE=MyISAM  AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__custom_properties_fields` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(50) NOT NULL,
  `label` varchar(255) NOT NULL,
  `field_type` char(50) NOT NULL,
  `modules` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `access` int(11) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `state` (`published`),
  KEY `access` (`access`),
  KEY `checked_out` (`checked_out`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__custom_properties_values` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL default '0',
  `name` char(50) NOT NULL,
  `label` varchar(255) NOT NULL,
  `priority` tinyint(4) NOT NULL default '0',
  `default` tinyint(1) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`),
  KEY `name` (`name`),
  KEY `priority` (`priority`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

