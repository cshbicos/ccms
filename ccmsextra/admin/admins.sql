CREATE TABLE `admins` (
  `id` tinyint(4) NOT NULL auto_increment,
  `usrname` varchar(30) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `fullname` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
);
