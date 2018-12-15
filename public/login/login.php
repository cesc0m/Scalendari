<?php
/**
 * actual login happens here
 */
require_once __DIR__ . '/../../config/globalconfig.php';
require_once ROOT_FOLDER . '/src/login_util.php';

if(isset( $_POST["calendariusername"]) && isset( $_POST["calendariusername"]) && isset( $_SESSION["previous_page"]) ){
$user = $_POST["calendariusername"];
$password = $_POST["calendaripassword"];

$privilege = checkUser($user, $password);
if ($privilege == Privilege::ACCESS || $privilege == Privilege::ADMIN) {
    $_SESSION["user"] = $user;
    $_SESSION["password"] = $password;
    
    $prevoius_page = $_SESSION['previous_page'];
    ?>
    <script type="text/javascript">
		if (typeof(Storage) !== "undefined") {
			localStorage.setItem("calendariusername", "<?php echo $user;?>");
			localStorage.setItem("calendaripassword", "<?php echo $password;?>");
		} else {
		  // Sorry! No Web Storage support..
		}
		location.href = "<?php echo $prevoius_page;?>";
    </script>
    <?php 
} else { 
    header("Location: ./");
    die;
}
} else {
    echo "internal error : ".$_POST["calendariusername"]."  ".$_SESSION["previous_page"];
}
?>