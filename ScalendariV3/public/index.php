<?php
/**
 * this is the main PHP file for people who want to see if their street had been done or not
 */
require_once '../config/globalconfig.php';

$dbConn = new PDO("mysql:host=$servername", $dbusername, $dbpassword); // opens the connection
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // throw exception on error

try { // try to connect to database, if error is thrown, the database is not present
    $dbConn->exec("USE $dbname");
} catch (Exception $e) {
    echo "La consegna non è iniziata";
    die();
}

$result = $dbConn->query("SELECT TimeStamp, Year FROM Configuration");
$configuration = ($result->fetch()); // the configuration table only has one line
$year = $configuration["Year"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<!-- title -->
<title>Calendari Consegnati</title>

<!-- favicon and styles -->
<link rel="icon" href="../favicon.png" />
<link rel="stylesheet" type="text/css"
	href="../resources/styles/common.css" />
<link rel="stylesheet" type="text/css"
	href="../resources/styles/main.css" />
<link rel="stylesheet" type="text/css"
	href="../resources/styles/searchBar.css" />

<!-- scripts -->
<script type="text/javascript" src="../resources/scripts/main.js"></script>
<script type="text/javascript" src="../resources/scripts/searchBar.js"></script>
</head>
<body>
	<div id="toolbar">
		<div id="header">
			<h1>Calendari Consegnati <?php echo $year; ?></h1>

			<div id="searchbar_container">
				<input id="searchbar" onfocus="enableSearch()" oninput="search()" type="text"
					name="search" placeholder="Search.." />
				 <img id="searchbar_clear"
					src="../resources/img/clear.svg" onclick="disableSearch()" />
			</div>
		</div>
	</div>
	<div id="content">
<?php

function displaySite($name, $zone, $done)
{
    if ($done == true) {
        echo '<div class="site done">';
    } else {
        echo '<div class="site">';
    }
    echo $name . "&nbsp(" . $zone . ")";
    echo '</div>';
}

# get Site name, zone and done, ordered by the second word of the name in ascending order
$prepareResult = $dbConn->prepare("SELECT Site.Name, Site.Done, Area.Zone FROM Site, Area WHERE Site.AreaID=Area.ID ORDER BY SUBSTRING(Site.Name, INSTR(Site.Name, ' ')) ASC");
$prepareResult->execute();
$result = $prepareResult->fetchAll(PDO::FETCH_ASSOC);

# eliminiamo i duplicati, una via è fatta se tutti i suoi pezzi sono fatti
$allSites = array();
foreach ($result as $row) {
    if(!isset($allSites[$row["Name"]])){
        $obj = new stdClass;
        $obj->done = $row['Done'];
        $obj->zone = $row['Zone'];
        $allSites[$row["Name"]] = $obj;
    } else {
        $allSites[$row["Name"]]->done &= $row['Done'];
    }
} 

foreach ($allSites as $name=>$characteristics){
    displaySite(str_replace(" (*)", "", $name), $characteristics->zone, $characteristics->done);
}

?>
	</div>
</body>
</html>
<?php
$dbConn = null; # closes the connection
?>