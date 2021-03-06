CREATE TABLE `wp_advertisements` (
`ID` mediumint(9) NOT NULL AUTO_INCREMENT ,
`group_id` mediumint(9) NOT NULL ,
`post_id` mediumint(9) NOT NULL ,
`attachment_id` mediumint(9) NOT NULL ,
`name` text NOT NULL ,
`status` varchar(40) NOT NULL default 'pending' ,
`slug` text NOT NULL,
`url` text NOT NULL ,
`order` tinyint NOT NULL ,
`open` datetime NOT NULL default '0000-00-00 00:00:00' ,
`close` datetime NOT NULL default '0000-00-00 00:00:00' ,
`content` text NOT NULL,
`file_path` text NOT NULL,
`file_url` text NOT NULL,
`file_type` text NOT NULL,
`created_at` datetime DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;;

CREATE TABLE `wp_advertisements_groups` (
`ID` mediumint(9) NOT NULL AUTO_INCREMENT ,
`name` text NOT NULL ,
`status` varchar(40) NOT NULL default 'pending' ,
`slug` text NOT NULL,
`order` tinyint NOT NULL ,
`content` text NOT NULL,
`created_at` datetime DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;;