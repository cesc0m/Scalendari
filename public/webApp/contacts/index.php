<?php
/**
 * this is the main webApp PHP file
 */

/* disable cache */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/* include global config and the builder for area headers */
require_once '../../../config/globalconfig.php';
require_once ROOT_FOLDER . '/src/login_util.php';

if(checkLogin() != Privilege::ACCESS){
    doLogin();
}

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
<title>Contatti</title>

<!-- favicon and styles -->
<link rel="icon" href="../../favicon.png" />
<link rel="stylesheet" type="text/css"
	href="../../../resources/styles/common.css" />
<link rel="stylesheet" type="text/css"
	href="../../../resources/styles/contacts.css" />
<link rel="stylesheet" type="text/css"
	href="../../../resources/styles/customAlert.css" />
<link rel="stylesheet" type="text/css"
	href="../../../resources/styles/map.css" />

<!-- scripts -->
<script type="text/javascript"
	src="../../../resources/scripts/common.js"></script>
<script type="text/javascript"
	src="../../../resources/scripts/customAlert.js"></script>

<?php include ROOT_FOLDER."/src/pwa_header.php" # includes the files necessary for using this page as a webApp ?>
</head>

<body>
	<div id="toolbar">
		<div id="header">
			<img id="backArrow" src="../../../resources/img/backArrow.svg"
				onclick="window.history.back();">
			<div id="backArrowAligned">
				<h1>Contatti</h1>
			</div>
		</div>
	</div>
	<div id="content"></div>

</html>