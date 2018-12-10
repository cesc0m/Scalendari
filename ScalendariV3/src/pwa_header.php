<?php 
require_once __DIR__.'/../config/globalconfig.php';

$path = getRelativePath(getcwd(), ROOT_FOLDER."/resources/pwa_sources/chrome.json");
?>
<meta name="theme-color" content="#ff7f00" />
<meta name="viewport" content="width=device-width, user-scalable=no" />
<link rel="manifest" href="<?php echo $path; ?>" />