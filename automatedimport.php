<?php
/*
 * Copyright (c) 2013 Georg Ehrke <oc.list <at> georgehrke <.> com>
 * This file is licensed under the MIT License
 * See the LICENSE file.
 * 
 * @repo: https://github.com/georgehrke/cl-calendarimport
 * 
 * Parameters:
 * <user of webserver> - checkout your distros doc
 * <name of this script> - should be automatedimport.php if you didn't rename it
 * <userid of calendar owner> - userid of the calendars owner, probably your userid
 * <id of calendar> - to get this id, take a look at the table %prefix%calendar_calendars in your database
 * <valid PHP timezone> - checkout http://www.php.net/manual/en/timezones.php
 * 
 * what todo before first run:
 * - set the correct path for OCROOT on line 25 (the path must end with a '/'!)
 * 
 * How to run this script from command line:
 * $ sudo -u <user of webserver> php <name of this script> <absolute path to calendar file> <userid of calendar owner> <id of calendar> <valid PHP timezone>
 * Example on debian:
 * $ sudo -u www-data php automatedimport.php /home/georg/somerandomicsfile.ics georg 7 Europe/Berlin
 */
//define the root path of ownCloud
define('OCROOT', '');
$nl = "\n";

if(OCROOT === ''){
	echo 'please set ownClouds root path in ' . $argv[0] . $nl;
	exit;
}

//check if all necessary parameters are given
if($argc < 5){
	echo 'Parameters:' . $nl;
	echo $argv[0] . ' <absolute path of calendar file> <userid> <id of calendar> <valid php timezone>' . $nl;
	exit;
}

//define vars
$path = (string) $argv[1];
$userid = (string) $argv[2];
$calendarid = (int) $argv[3];
$tz = (string) $argv[4];

//it's not necessary to load all apps
$RUNTIME_NOAPPS = true;
require_once(OCROOT . 'lib/base.php');

//set userid
OC_User::setUserId($userid);

//get content of calendar file
$ics = file_get_contents($path);

//check if calendar app is enabled and load calendar scripts
OC_Util::checkAppEnabled('calendar');
OC_App::loadApp('calendar');

//delete all old calendar entries
$stmt = OCP\DB::prepare( 'DELETE FROM `*PREFIX*calendar_objects` WHERE `calendarid` = ?' );
$stmt->execute(array($calendarid));

//initialize a new import object
$import = new OC_Calendar_Import($ics);
$import->setCalendarID($calendarid);
$import->setProgresskey(false);
$import->setTimeZone($tz);

//import calendar
$import->import();
