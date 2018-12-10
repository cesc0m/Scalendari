<?php
require_once __DIR__.'/../../config/globalconfig.php';

$dbConn = new PDO("mysql:host=$servername", $dbusername, $dbpassword); # opens the connection
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # throw exception on error

$dbConn->exec("USE $dbname;"); # creates the database (reset)

$dbConn->exec("START TRANSACTION");

# saving timestamp and year in Configuration
$timeStamp = time();
$dbConn->exec("UPDATE Configuration SET TimeStamp=".$timeStamp);
$dbConn->exec("UPDATE Site SET Done=".$_POST['Done']." WHERE ID=".$_POST['siteID']);

$dbConn->exec("COMMIT");

$dbConn = null; # closes the connection

include ROOT_FOLDER.'/public/actions/getDone.php';