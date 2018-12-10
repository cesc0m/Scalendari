<?php
require_once __DIR__ . '/../../config/globalconfig.php';

$timeStamp = isset($_POST["timestamp"]) ? $_POST["timestamp"] : 0;
$area = isset($_POST["areaID"]) ? $_POST["areaID"] : NULL;

$dbConn = new PDO("mysql:host=$servername", $dbusername, $dbpassword); // opens the connection
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // throw exception on error

$dbConn->exec("USE $dbname;");

    // saving timestamp and year in Configuration
    $result = $dbConn->prepare("SELECT Timestamp FROM Configuration");
    $result->execute();
    $savedTimeStamp = $result->fetch(PDO::FETCH_ASSOC);
    if ($savedTimeStamp["Timestamp"] == $timeStamp) {
        $changed = false;
    } else {
        $changed = true;
    }
if ($changed) {
    if($area != NULL) {
        $result = $dbConn->prepare("SELECT ID, Done FROM Site where AreaID=:areaID");
        $result->bindParam(":areaID", $area);
        $result->execute();
        $sitesDone = $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $result = $dbConn->prepare("SELECT ID, Done, AreaID, Calendars FROM Site");
        $result->bindParam(":areaID", $area);
        $result->execute();
        $sitesDone = $result->fetchAll(PDO::FETCH_ASSOC);
    }
    print "{\"timestamp\":".$savedTimeStamp["Timestamp"].", \"sites\" : ".json_encode($sitesDone)."}";
}
$dbConn = null; # closes the connection