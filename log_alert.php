<?php
// Log Scanner
// This script is designed to scan through all backend log files and detect error keywords and alert the dev team
// Robert Shedd - robert (at) shedd (dot) us
// http://www.robertshedd.com

////////////////////////////////////////////////
//CONFIGURE SCRIPT HERE
////////////////////////////////////////////////

//send alert emails to this address
$alert_email_to = "user@domain.com";

//define list of errors to search for
$errors = array("Warning","Fatal error");//this list is good for PHP errors/warnings

//define list of files to search
$files = array("errors/log1.log","errors/log2.log");

//folder that holds the log files
$base_path = "/path/to/files/";

////////////////////////////////////////////////
////////////////////////////////////////////////

print "Starting run.\n\n";

define("SDIR",dirname(__FILE__));
require_once(SDIR."/functions.php");

//loop for each file to check
foreach($files as $file)
{
	//concat with base path
	$file = $base_path.$file;
	
	print "Starting $file...\n";
	
	//search the file for each error phrase
	foreach($errors as $error)
	{
		print "\n\ngrep $error $file\n";
		//run grep command
		$grepCmd   = `grep $error $file`;
		print $grepCmd."\n";
		
		if(strlen($grepCmd) > 0)
		{
			//the text was found
			$email_content = "Search term '$error' was found in log file $file.\r\n\r\n".$grepCmd;
			
			print "Sending alert email -- '$error' was found!\n\n";
			
			//send email alert
			send_email($alert_email_to,"ALERT: Backend Error Has Occurred!",$email_content);
		}
	}
	
	print "Finishing $file...\n";
}//end foreach files

print "\n\nEnd run.\n";
?>