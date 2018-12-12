<?php
/**
 * reset form
 */
?>
<html>

<head>

<!-- title -->
<title>Reset Scalendari</title>

<!-- favicon and styles -->
<link rel="icon" href="../favicon.png" />
<link rel="stylesheet" type="text/css"
	href="../../resources/styles/common.css" />
<link rel="stylesheet" type="text/css"
	href="../../resources/styles/login.css" />
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
		<form action="reset.php" method="POST">
			<div>
				<label><b>Username</b></label>
			</div>
			<div>
				<input type="text" name="calendariresetusername" autocomplete="off"
					placeholder="inserisci username" value="" />
			</div>
			<div>
				<label><b>Password</b></label>
			</div>
			<div>
				<input type="text" name="calendariresetpassword" autocomplete="off"
					placeholder="inserisci password" value="" />
			</div>
			<div>
				<input type="submit" value="RESETTA" />
			</div>
		</form>
	</div>
</body>

</html>