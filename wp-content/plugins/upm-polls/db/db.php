<?php 
global 	$wpdb;

##########################################################################################################################
$sql[ 'pppm_polls' ] = "CREATE TABLE IF NOT EXISTS `" . PPPM_PREFIX . "pppm_polls` (
`id` INT NOT NULL AUTO_INCREMENT ,
`question` VARCHAR( 255 ) NOT NULL ,
`start` INT NOT NULL ,
`end` INT NOT NULL ,
`post` INT NOT NULL ,
`meta` LONGTEXT NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;";

##########################################################################################################################
$sql[ 'pppm_polls_items' ] = "CREATE TABLE IF NOT EXISTS `" . PPPM_PREFIX . "pppm_polls_items` (
`id` INT NOT NULL AUTO_INCREMENT ,
`qid` INT NOT NULL ,
`answer` MEDIUMTEXT NOT NULL ,
`meta` LONGTEXT NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;";

##########################################################################################################################
$sql[ 'pppm_polls_votes' ] = "CREATE TABLE IF NOT EXISTS `" . PPPM_PREFIX . "pppm_polls_votes` (
`id` INT NOT NULL AUTO_INCREMENT ,
`qid` INT NOT NULL ,
`item_id` INT NOT NULL ,
`user_id` INT NOT NULL ,
`ip` VARCHAR( 255 ) NOT NULL ,
`time` INT NOT NULL ,
`meta` LONGTEXT NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;";

##########################################################################################################################

$sql_un[ 'pppm_polls' ] = "DROP TABLE `" . PPPM_PREFIX . "pppm_polls`;";
$sql_un[ 'pppm_polls_items' ] = "DROP TABLE `" . PPPM_PREFIX . "pppm_polls_items`;";
$sql_un[ 'pppm_polls_votes' ] = "DROP TABLE `" . PPPM_PREFIX . "pppm_polls_votes`;";

			
?>