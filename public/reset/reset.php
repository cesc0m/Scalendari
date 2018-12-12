<?php
/**
 * actual reset happens here
 */
require_once __DIR__ . '/../../config/globalconfig.php';
require_once ROOT_FOLDER . '/src/reset_util.php';
?>
<html>

<head>

<!-- title -->
<title>Reset Scalendari</title>

<!-- favicon and styles -->
<link rel="icon" href="../favicon.png" />
<link rel="stylesheet" type="text/css"
	href="../../resources/styles/common.css" />
<style type="text/css">
#toolbar {
	background-color: red;
}
#toolbar > #header > h1{
	color: white;
	font-size: 2rem;
	font-family: Verdana, Geneva, sans-serif;
	font-weight: bold;
}
#toolbar > #header {
    padding: 0 1rem;
}
.duplicate {
}
</style>

<!-- manifest for web applications -->
<link rel="manifest" href="./manifest.json" />
<meta name="theme-color" content="#ff7f00" />

</head>

<body>
	<div id="toolbar">

		<div id="header">
			<h1>Reset</h1>
		</div>
	</div>
	<div id="content">
		<br />
<?php
$user = $_POST["calendariresetusername"];
$password = $_POST["calendariresetpassword"];

if (password_verify($password, password_hash("password", PASSWORD_DEFAULT))) {
    ?>
    
<!-- right username and password -->
		<h1>&nbsp &nbsp reset successfully</h1>

		<script type="text/javascript">
	setTimeout(function (e){
		// location.href="../webApp/";
	}, 1000);
</script>
<?php
    $duplicates = resetDB();
    foreach($duplicates as $name=>$areas){
        echo "via duplicata: ".$name." presente in: ";
        foreach($areas as $area){
            echo $area." ";
        }
        echo "<br/>";
    }
} else {
    ?>
    
<!-- wrong username or password -->
		<h1>&nbsp &nbsp wrong username or password</h1>

		<script type="text/javascript">
	setTimeout(function (e){
		location.href="./";
	}, 1000);
</script>

<?php
}
?>

</div>
</body>
</html>