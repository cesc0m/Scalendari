<?php
define('ROOT_FOLDER' , __DIR__.'/../');
define('SOURCE_DIR', ROOT_FOLDER."sources/");

$servername = "localhost";
$dbname = "USERNAME";

$dbusername = "DB_USERNAME";
$dbpassword = "DB_PASSWORD";

/**
 * returns the relative path to access $to from $from
 * @param string $from, the source directory (use getcwd() to get the current one)
 * @param string $to, the destination file / directory
 * @return string the relative path
 */
function getRelativePath($from, $to)
{
    $from = realpath($from);
    $to = realpath($to);
    // some compatibility fixes for Windows paths
    $from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
    $to   = is_dir($to)   ? rtrim($to, '\/') . '/'   : $to;
    $from = str_replace('\\', '/', $from);
    $to   = str_replace('\\', '/', $to);
    
    $from     = explode('/', $from);
    $to       = explode('/', $to);
    $relPath  = $to;
    
    foreach($from as $depth => $dir) {
        // find first non-matching dir
        if($dir === $to[$depth]) {
            // ignore this directory
            array_shift($relPath);
        } else {
            // get number of remaining dirs to $from
            $remaining = count($from) - $depth;
            if($remaining > 1) {
                // add traversals up to first matching dir
                $padLength = (count($relPath) + $remaining - 1) * -1;
                $relPath = array_pad($relPath, $padLength, '..');
                break;
            } else {
                $relPath[0] = './' . $relPath[0];
            }
        }
    }
    return implode('/', $relPath);
}

class Privilege{
    const ADMIN = "ADMIN";
    const ACCESS = "ACCESS";
}

/**
 * returns the kind of user or null if user does not exists / the password is wrong
 * @param string username the username
 * @param string password the password
 * @return string the privilege or null
 */
function checkUser($username, $password) {

}
