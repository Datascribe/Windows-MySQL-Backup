Windows-MySQL-Backup
====================

We use MySQL and PHP a lot with our systems. Though our local network runs on Windows Server. Here's the script we use to backup MySQL on the Windows server.

This script uses mysqldump and iterates through the array of databases specified at the top of sqlbackup.php, passing them to mysqldump.
Then based on the defined backup location, a new directory is created in that location. The name of that directory is a Date/Time Stamp YYYYMMDD-HHMMSS e.g.
20121016-221302. We tend to use the External Drive we back up to as the location.
Inside that directory will be the .sql files named by their database name.
The results of each backup are outputted to SQLLog.html

Requirements
------------
This script has only been ran using PHP 5.3 but you can try it with other versions. With earlier versions, there may be issues with the 'const' varibles.

Usage and Setup
---------------
Firstly, make sure mysqldump.exe is in the same directory as the sqlbackup.php

In the sqlbackup.php file:
* Go to Line 17 and edit the location where you want to create your backups. Be Sure to include the trailing forward-slash in the path.
  Also make sure this path actually exists.
* Go to Line 23 and edit the array to include the databases you would like to backup.
* Go to Line 26 and edit the host in which the MySQL Server Resides.
* Go to Line 27 and edit the username to one that has permission to access all of the databases you want to backup.
* Go to Line 28 and edit the password for the above MySQL User.
That's it for the sqlbackup.php file

Now open the start.cmd file, this needs to be the path of your PHP executable followed by the path of sqlbackup.php
You can see the example given in the file itself. Once you have done this, double click the file and you'll see the backup running.
We also stick the start.cmd into a Scheduled Task on Windows and just check the SQLLog.html everyday.

Footnote & Possible Issues
--------------------------
The reason this script is done in PHP rather than as a batch file is because we love being able to write something once and it run anywhere.
It's fairly quick and simple to modify sqlbackup.php to run on any platform where PHP and mysqldump has been compiled for.

Also this script makes use of passthru in order to run mysqldump which means no error checking is actually done on mysqldump itself.
The Error checking mainly refers to if the backup directory can be created or not.
Instead any mysqldump errors will appear in the .sql file in your backup. So be sure to run this once manually to ensure it runs the way you
expect it too.

License
-------
 "THE BEER-WARE LICENSE" (Revision 42):  
 Adam Prescott <adam.prescott@datascribe.co.uk> wrote this file.  
 As long as you retain this notice, you can do whatever you want with this  
 stuff. If we meet some day, and you think this stuff is worth it,   
 you can buy me a beer (or whisky) in return, Adam Prescott.