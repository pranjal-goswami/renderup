<?php
/**
 * The contents of this file cannot be copied, distributed or modified without prior
 * consent of the author.
 *
 * Project : RenderUP
 * File :  config.php
 *
 * @author Pranjal Goswami <pranjal[dot]b[dot]goswami[at]gmail[dot]com>
 */


/**
 * Set the default timezone
 */
date_default_timezone_set("Asia/Kolkata");

/**
 * define the cache file location 
 * should be writable 
 */
define("CACHE_FILE_LOCATION","/tmp/cache/");

/**
 * PhantomJS binary location 
 * version 2.0.0
 */
define("PHANTOMJS_BIN_LOCATION","/usr/local/bin/phantomjs");


/**
 * PhantomJS binary location 
 * version 2.0.0
 */
define("RENDER_UP_JS_PATH","/tmp/render/renderHtml.js");

/**
 * RenderUp Log location 
 */
define("RENDER_UP_LOG_FILE","/tmp/render/render.log");
