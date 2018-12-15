<?php
/**
 * area view
 * */
if (! (isset($_GET['areaID']))) {
    echo 'FATAL ERROR';
    die();
}

require_once '../../../config/globalconfig.php';
require_once ROOT_FOLDER . '/src/login_util.php';

if(checkLogin() != Privilege::ACCESS){
    doLogin();
}

require_once ROOT_FOLDER . '/src/area_content_builder.php';
require_once ROOT_FOLDER . '/src/site_content_builder.php';

$dbConn = new PDO("mysql:host=$servername", $dbusername, $dbpassword); // opens the connection
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // throw exception on error

try { // try to connect to database, if error is thrown, the database is not present
    $dbConn->exec("USE $dbname");
} catch (Exception $e) {
    echo "La consegna non è iniziata";
    die();
}

$areaID = $_GET["areaID"] . "";

$result = $dbConn->prepare("SELECT Name, Zone, Transports, Description FROM Area WHERE ID=:id");
$result->bindParam(":id", $areaID);
$result->execute();

$areaInfo = ($result->fetch(PDO::FETCH_ASSOC));
$areaName = $areaInfo["Name"];
$areaZone = $areaInfo["Zone"];
$areaTransports = $areaInfo["Transports"];
$areaDescription = $areaInfo["Description"];

$prepareResult = $dbConn->prepare("SELECT Site.Name AS SName, Site.Description AS SDescription, Site.Coords AS SCoords, Site.Calendars AS SCalendars, Site.Done AS SDone, Site.ID AS SID FROM Site WHERE AreaID=" . $areaID);
$prepareResult->execute();
$result = $prepareResult->fetchAll(PDO::FETCH_ASSOC);

$areaValue = $areaName;
$transports = str_replace(" ", "", $areaTransports);
$transports_array = explode(",", $transports);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<!-- title -->
<title><?php echo $areaName;?></title>

<!-- favicon and styles -->
<link rel="icon" href="../favicon.png" />
<link rel="stylesheet" type="text/css"
	href="../../../resources/styles/common.css" />
<link rel="stylesheet" type="text/css"
	href="../../../resources/styles/webApp_areaView.css" />
<link rel="stylesheet" type="text/css"
	href="../../../resources/styles/areaHeader.css" />
<link rel="stylesheet" type="text/css"
	href="../../../resources/styles/customAlert.css" />
<link rel="stylesheet" type="text/css"
	href="../../../resources/styles/map.css" />
<link rel="stylesheet" type="text/css"
	href="../../../resources/styles/footerCounter.css" />

<!-- scripts -->
<script type="text/javascript"
	src="../../../resources/scripts/common.js"></script>
<script type="text/javascript"
	src="../../../resources/scripts/customAlert.js"></script>
<script type="text/javascript"
	src="../../../resources/scripts/webApp_areaView.js"></script>
	
<?php include ROOT_FOLDER."/src/pwa_header.php" ?>
</head>

<body>
	<div id="toolbar">
		<div id="header" class="<?php echo $areaZone;?>">
			<img id="backArrow" src="../../../resources/img/backArrow.svg"
				onclick="window.history.back();" />
			<div class="area" id="backArrowAligned">
			<?php
$areaCoords = NULL;

$areaHeader = new AreaContentBuilder($areaName . " ." . $areaZone . ".", $areaZone, explode(",", str_replace(" ", "", $areaTransports)));
if ($areaCoords != NULL) {
    $areaHeader->setOnMapClick("openMap(" . $areaCoords . ")");
}

if ($areaDescription != NULL) {
    $areaHeader->setOnArrowClick("toggleExpanded(document.getElementById('backArrowAligned').parentNode.parentNode)");
}
$areaHeader->display();
?>
			</div>
		</div>
	<?php
if ($areaDescription != NULL) {
    ?>
    <div class="areaDescription"><?php echo $areaDescription;?></div>
    <?php
}
?>
	</div>
	<div id="content">
	<?php

foreach ($result as $row) {
    $siteContentBuilder = new SiteContentBuilder($row["SName"], $row["SDescription"], $row["SCalendars"]);
    if($row["SCoords"] != NULL) {
        $siteContentBuilder->setOnMapClick("openMap(".$row["SCoords"].")");
    }
    ?>
    <div id="site<?php echo $row["SID"]?>" class="site<?php if($row["SID"] == $_GET["siteID"]){echo " initialHighlight";} ?>" onclick="toggleSiteDone(<?php echo $row["SID"].", '".addslashes($row["SName"])."'";?>, this)">
    <?php 
    $siteContentBuilder->display();
    ?>
    </div>
    <?php 
}

?>
	</div>
	
	<div id="footer">
		<div id="common_bar">
			<div class="missing_indicator"><span id="the_end">fine</span><span id="count"></span></div>
		</div>
	</div>
	
	<script>
var siteID = <?php echo $_GET['siteID']?>;

if(siteID != -1) {
	var site = document.getElementById("site"+siteID);
	site.scrollIntoView();
}

function updateFunction(){
	countAreaCalendars();
	getAreaDone(<?php echo $areaID?>, function(json){
			var arr = json;
			var allDone = true;
			var howMuchDone = 0;
			var howMuch = 0;
			for (i in arr) {
				var key = arr[i].ID;
				var value = arr[i].Done;
				console.log(value);
				if(value=="1"){
					document.getElementById("site"+key).classList.add("done");
					howMuchDone++;
				} else {
					document.getElementById("site"+key).classList.remove("done");
				}
				howMuch++;
			}
			if(howMuch == howMuchDone) {
				document.getElementsByClassName("area")[0].classList.add("done");
				document.getElementById("header").classList.add("done");

				document.getElementById("the_end").style.display="block";
				document.getElementById("count").style.display="none";
				
				document.getElementById("common_bar").style.width="100%";
			} else {
				document.getElementsByClassName("area")[0].classList.remove("done");
				document.getElementById("header").classList.remove("done");

				document.getElementById("the_end").style.display="none";
				document.getElementById("count").style.display="block";

				document.getElementById("count").innerHTML=howMuch-howMuchDone;
				document.getElementById("common_bar").style.width=(100*howMuchDone/howMuch)+"%";
			}
});
}
updateFunction();
setInterval(updateFunction, 1000);
</script>
</body>

<?php
$dbConn = null; // closes the connection
?>