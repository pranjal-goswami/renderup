<?php

/**
 * The contents of this file cannot be copied, distributed or modified without prior
 * consent of the author.
 *
 * Project : RenderUP
 * File :  index.php
 *
 * @author Pranjal Goswami <pranjal[dot]b[dot]goswami[at]gmail[dot]com>
 */

require_once("config.php");
require_once("_lib/class.Logger.php");

$logger = Logger::getInstance(RENDER_UP_LOG_FILE);



$url = urldecode($_GET["url"]);


//Should force reload of page to cache
if(isset($_GET["force"]) && $_GET["force"]==true){
	$forceReload = true;
} else {
	$forceReload = false;
}

$url = strtok($url,"?");

//Check if URL is present
if(is_null($url) || $url == "" ){
	$logger->logError("No URI Specified");
	die("No URL Specified");

}


$cachedFilePath = getCacheFileLocation($url);


//Check if the file exists in cache : 
if(file_exists($cachedFilePath) && $forceReload == false){
	
	//echo $cachedFilePath;

	$file = fopen($cachedFilePath, "r") or die("Unable to open file!");
	echo fread($file,filesize($cachedFilePath));
	fclose($file);
	
	//Timer
	$time = round((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"])*1000);
	
	//Log time taken
	$logger->logInfo("Read from cache ".$url." in [".($time)."ms]");
	writeCSV("READ,".$time.",".$url.",".$cachedFilePath);

} else {

	

	//Generate Command to render using PhantomJS
	$cmd = PHANTOMJS_BIN_LOCATION.' '.RENDER_UP_JS_PATH.' '.$url.' > '.$cachedFilePath;
	
	$r = exec($cmd,$resp,$ret);

	$file = fopen($cachedFilePath, "r") or die("Unable to open file!");
	echo fread($file,filesize($cachedFilePath));
	fclose($file);
	
	$time = round((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"])*1000);

	$logger->logInfo("Cached ".$url." in [".($time)."ms]");
	writeCSV("CACHE,".$time.",".$url.",".$cachedFilePath);


}

function getCacheFileLocation($url){
	$urlHash = md5($url);
	$f1 = substr($urlHash,0,2);
	$f2 = substr($urlHash,2,2);
	$file = substr($urlHash,4);
	$dir  = CACHE_FILE_LOCATION.$f1."/".$f2;
	if (!file_exists($dir)) {
	    mkdir($dir, 0777, true);
	}
	return $dir."/".$file.".html";
}
	
	

function writeCSV($msg){
	$filehandle = fopen("/tmp/loaddata.csv", "a") or die("can't open file /tmp/loaddata.csv");
	fwrite($filehandle, $msg  . PHP_EOL);
	fclose($filehandle);

}

