<?php
/**
 * this is the main webApp PHP file
 */

/* disable cache */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/* include global config and the builder for area headers */
require_once '../../config/globalconfig.php';
require_once ROOT_FOLDER . '/src/login_util.php';

if(checkLogin() != Privilege::ACCESS){
    doLogin();
}

require_once ROOT_FOLDER . '/src/area_content_builder.php';

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
<title>Scalendari</title>

<!-- favicon and styles -->
<link rel="icon" href="../../resources/img/icons/ic_192.png" />
<link rel="stylesheet" type="text/css"
	href="../../resources/styles/common.css" />
<link rel="stylesheet" type="text/css"
	href="../../resources/styles/webApp_main.css" />
<link rel="stylesheet" type="text/css"
	href="../../resources/styles/areaHeader.css" />
<link rel="stylesheet" type="text/css"
	href="../../resources/styles/customAlert.css" />
<link rel="stylesheet" type="text/css"
	href="../../resources/styles/map.css" />
<link rel="stylesheet" type="text/css"
	href="../../resources/styles/fab.css" />
<link rel="stylesheet" type="text/css"
	href="../../resources/styles/searchBar.css" />
<link rel="stylesheet" type="text/css"
	href="../../resources/styles/footerCounter.css" />

<!-- scripts -->
<script type="text/javascript" src="../../resources/scripts/common.js"></script>
<script type="text/javascript"
	src="../../resources/scripts/webApp_main.js"></script>
<script type="text/javascript"
	src="../../resources/scripts/customAlert.js"></script>
<script type="text/javascript"
	src="../../resources/scripts/searchBar.js"></script>

<?php include ROOT_FOLDER."/src/pwa_header.php" # includes the files necessary for using this page as a webApp ?>
</head>

<body>
	<div id="toolbar">
		<div id="header">
			<h1>Scalendari</h1>

			<div id="searchbar_container">
				<input id="searchbar" onfocus="enableSearch()"
					oninput="search()" type="text" name="search"
					placeholder="Search..." /> <img id="searchbar_clear"
					src="../../resources/img/clear.svg" onclick="disableSearch()" />
			</div>
		</div>
	</div>
	<div id="content">
<?php

function displaySite($siteID, $areaID, $name, $calendars, $done)
{
    if ($calendars == NULL) {
        $calendars = "?";
    }
    ?>
    <div calendars="<?php echo $calendars; ?>"
			id="site<?php echo $siteID; ?>"
			onclick="openArea(<?php echo $areaID?>, <?php echo $siteID;?>)"
			value="<?php echo $name;?>"
			class="site searchable hidden <?php if($done) echo "done "?>">
			<div class="title"><?php echo $name; ?></div>
		</div>
    <?php
}

// get Site name, zone and done, ordered by the second word of the name in ascending order
$areaQuery = $dbConn->prepare("SELECT ID, Name, Description, Zone, Transports FROM Area ORDER BY Name ASC");
$areaQuery->execute();
$areaResult = $areaQuery->fetchAll(PDO::FETCH_ASSOC);

$siteQuery = $dbConn->prepare("SELECT ID, Name, Description, Coords, Calendars, Done FROM Site WHERE AreaID=:areaID");
$siteQuery->bindParam(":areaID", $currentArea);

foreach ($areaResult as $area) {
    ?>
	<div class="container">
	<?php
    $area["Coords"] = NULL;
    
    $areaHeader = new AreaContentBuilder($area["Name"] . " ." . $area["Zone"] . ".", $area["Zone"], explode(",", str_replace(" ", "", $area["Transports"])));
    if ($area["Coords"] != NULL) {
        $areaHeader->setOnMapClick("openMap(" . $area["Coords"] . ")");
    }
    if ($area["Description"] != NULL) {
        $areaHeader->setOnArrowClick("toggleExpanded(document.getElementById('area" . $area["ID"] . "').parentNode)");
    }
    ?>
	<div class="area <?php echo $area["Zone"]; ?>"
				id="area<?php echo $area["ID"]?>"
				onclick="openArea(<?php echo $area["ID"]?>, -1)"
				value="<?php echo $area["Name"]." .".$area["Zone"]."."?>">
	<?php
    $areaHeader->display();
    ?>
	</div>
	<?php
    if ($area["Description"] != NULL) {
        ?>
        <div class="areaDescription"><?php echo $area["Description"]; ?> </div>
        <?php
    }
    $currentArea = $area["ID"];
    
    $siteQuery->execute();
    $siteResult = $siteQuery->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($siteResult as $site) {
        displaySite($site["ID"], $currentArea, $site["Name"], $site["Calendars"], $site["Done"]);
    }
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
<?php

require_once ROOT_FOLDER . 'src/fab_builder.php';
$button = new FabButton("../../resources/img/home.svg");
$button->setOnClickFunction("location.href=&quot;./contacts/&quot;");
$buttonContainer = new FabBuilder(array(
    $button
));
$buttonContainer->display();
?>
</body>
<script type="text/javascript">
	function updateFunction(){
		countAreaCalendars();
		getAllDone(function(json){
				var arr = json;
				var areaValues = {};
				var done = 0; // quante fatte
				var total = 0; // quante in totale
				for (i in arr) {
					var key = arr[i].ID;
					var value = arr[i].Done;
					if(value=="1"){
						done++;
						document.getElementById("site"+key).classList.add("done");
					} else {
						document.getElementById("site"+key).classList.remove("done");
					}
					total++;
		}
		countAreaCalendars();
		document.getElementById("common_bar").style.width=(done*100/total)+"%";
		document.getElementById("count").innerHTML=total - done;

		if(done == total){
			document.getElementById("count").style.display="none";
			document.getElementById("the_end").style.display="block";
		} else {
			document.getElementById("count").style.display="block";
			document.getElementById("the_end").style.display="none";
		}
	});
	}
	updateFunction();
	setInterval(updateFunction, 1000);
	
</script>
</html>

<?php
$dbConn = null; # closes the connection
?>