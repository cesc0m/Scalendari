<?php
/**
 * this is the login PHP file
 */

/* disable cache */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/* include global config and the builder for area headers */
require_once '../../config/globalconfig.php';

if (! isset($_SESSION["previous_page"])) {
    $_SESSION["previous_page"] = "../webApp/";
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<!-- title -->
<title>Login</title>

<!-- favicon and styles -->
<link rel="icon" href="../../resources/img/icons/ic_192.png" />
<link rel="stylesheet" type="text/css"
	href="../../resources/styles/common.css" />
<link rel="stylesheet" type="text/css"
	href="../../resources/styles/login.css" />
<style type="text/css">
#toolbar {
	background-color: #ff7700;
}

#toolbar>#header>h1 {
	color: white;
	font-size: 2rem;
	font-family: Verdana, Geneva, sans-serif;
	font-weight: bold;
}

#toolbar>#header {
	padding: 0 1rem;
}
</style>

<!-- scripts -->
<script type="text/javascript" src="../../resources/scripts/common.js"></script>

<?php include ROOT_FOLDER."/src/pwa_header.php" # includes the files necessary for using this page as a webApp ?>
</head>

<body>
	<div id="toolbar">
		<div id="header">
			<h1>Login</h1>
			<h1>
			<?php
echo $_SESSION["previous_page"];
?>
			</h1>
		</div>
	</div>
	<div id="content">
		<br />
		<form action="login.php" method="POST">
			<div>
				<label><b>Username</b></label>
			</div>
			<div>
				<input type="text" name="calendariusername" autocomplete="off"
					placeholder="inserisci username" value="username" />
			</div>
			<div>
				<label><b>Password</b></label>
			</div>
			<div>
				<input type="text" name="calendaripassword" autocomplete="off"
					placeholder="inserisci password" value="password" />
			</div>
			<div>
				<input type="submit" value="LOGIN" />
			</div>
		</form>
	</div>
	<script type="text/javascript">
		var form = document.getElementsByTagName("form")[0];
		var usernameform = document.getElementsByName("calendariusername")[0];
 		var passwordform = document.getElementsByName("calendaripassword")[0];
 		
 		if (typeof(Storage) !== "undefined") {
 	 		var saveduser = localStorage.getItem("calendariusername");
 	 		var savedpassword = localStorage.getItem("calendaripassword");
 	 		if(saveduser != null && savedpassword != null){
 	 	 		usernameform.value = saveduser;
 	 	 		passwordform.value = savedpassword;

 	 	 		form.submit();
 	 		}
 		} else {
 		  // Sorry! No Web Storage support..
 		}
	</script>
</body>
</html>