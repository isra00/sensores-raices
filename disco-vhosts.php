<?php

define('TEMPODB_API_KEY',		'');
define('TEMPODB_API_SECRET',	'');

date_default_timezone_set("Europe/Madrid");

require 'tempodb-php/tempodb.php';

$cmd = 'cd /var/www/vhosts && ls -d */ | grep "\." | xargs du -s';

$cmd_output = array();
exec($cmd, $cmd_output);

$disk_vhosts = array();

foreach ($cmd_output as $line)
{
	$match = array();
	preg_match('/([0-9]+)\s([^\/]+)\//', $line, $match);
	$disk_vhosts[$match[2]] = intval($match[1]) / 1024;
}

$tdb = new TempoDB(TEMPODB_API_KEY, TEMPODB_API_SECRET);

foreach ($disk_vhosts as $domain=>$disk_space)
{
	$tdb->write_key($domain, array(
		new DataPoint(new DateTime(), $disk_space)
	));	
}