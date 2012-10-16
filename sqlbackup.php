<?php
/*
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" (Revision 42):
 * Adam Prescott <adam.prescott@datascribe.co.uk> wrote this file. 
 * As long as you retain this notice, you can do whatever you want with this
 * stuff. If we meet some day, and you think this stuff is worth it, 
 * you can buy me a beer (or whisky) in return, Adam Prescott.
 * ----------------------------------------------------------------------------
 */

/**
 * The location in which you want to store backups.
 * The folder/path you specify must already exist. 
 * Be Sure to Include the trailing /
 */
const BackupLocation = 'F:/DBBackups/';

/**
 * The Databases which you want to backup go inside this array.
 * The ones below are just some examples
 */
$databases = array('bugtracking','compdata','datascribe_mainsys','datascribeprojects','datascribeservice','dssugarcrm');

/* SQL Database Settings */
const SQLHost = 'localhost'; // Hostname or IP of the MySQL Server
const SQLUsername = 'root'; // Username to use for the MySQL Server
const SQLPassword = ''; // Password to use for the MySQL Server

/**
 * The Command for mysqldump
 */
const dumpcmd = 'mysqldump.exe --max_allowed_packet=1G --extended-insert=FALSE --complete-insert=TRUE --port=3306 --default-character-set=utf8';

/* DO NOT change anything below this line (unless you know what you're doing) */

function getTime()
{
    $a = explode (' ',microtime());
    return(double) $a[0] + $a[1];
}

function actionlog($update) {
	$script_location = substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/')).'/';
	$handler = fopen($script_location."SQLLog.html",'a');
	if(!$handler) {
		echo 'ACTION LOG ERROR: Could not bind to or create the file';
	} else {
		$append = fwrite($handler,$update);
		if(!$append) {
			echo 'ACTION LOG ERROR: Could not write to the file';
		} else { return true; }
	}
}
$begin = getTime();

	echo "Starting backup\n";
	actionlog('<font color="#0000ff"><b>'.date("d/m/Y H:i:s").' Starting New Backup</b></font><br />');
	$SubDir = BackupLocation.date("Ymd-His").'/';
	if(!mkdir($SubDir)) {
		actionlog('<font color="#ff0000"><b>'.date("d/m/Y H:i:s").' Backup Directory Creation Failed</b></font><br />');
		die('Backup Directory Creation Failed');
	} else {
		foreach($databases as $db) {
			$fullcmd = dumpcmd.' --user='.SQLUsername.' --password='.SQLPassword.' "'.$db.'"';
			echo "Backing up \"{$db}\"...\n";
			passthru($fullcmd." > ".$SubDir.$db.".sql");
			echo "Backup of \"{$db}\" completed.\n";
			actionlog('<font color="#00ff00"><b>'.date("d/m/Y H:i:s").' Backup Of "'.$db.'" Completed</b></font><br />');
		}
	}
	
$finish = getTime();
actionlog('<font color="#0000ff"><b>'.date("d/m/Y H:i:s").' Backup of '.count($databases).' Databases complete in '. number_format(($finish - $begin),2).' secs</b></font><br />');
echo "Backup Completed in: ". number_format(($finish - $begin),2)." secs";
exit();
?>