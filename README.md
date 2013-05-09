cl-calendarimport
=================

PHP command line script for importing calendar files into your ownCloud calendar

### License
This project is licensed under the terms of the MIT License.

### How to setup:
- set the correct path for OCROOT on line 25 (the path must end with a '/'!)

### Attention
- all existing events in the calendar will be deleted before importing the file

### Parameters
- user of webserver - checkout your distros doc
- name of this script - should be automatedimport.php if you didn't rename it
- userid of calendar owner - userid of the calendars owner, probably your userid
- id of calendar - to get this id, take a look at the table %prefix%calendar_calendars in your database
- valid PHP timezone - checkout [http://www.php.net/manual/en/timezones.php](http://www.php.net/manual/en/timezones.php)

### How to run this script from command line:
```bash
$ sudo -u <user of webserver> php <name of this script> <absolute path to calendar file> <userid of calendar owner> <id of calendar> <valid PHP timezone>
````
#### Example on debian:
```bash
$ sudo -u www-data php automatedimport.php /home/georg/somerandomicsfile.ics georg 7 Europe/Berlin
```
