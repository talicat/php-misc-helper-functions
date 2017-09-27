<?php
/**
 * \file    package.php
 * \brief   A single include file so that all items within this directory
 *          may be included with only one `require_once` statement
 * \since   22 July 2014
 */
require_once    __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';
require_once    __DIR__ . DIRECTORY_SEPARATOR . 'curl.php';
require_once    __DIR__ . DIRECTORY_SEPARATOR . 'json_bourne.php';
require_once    __DIR__ . DIRECTORY_SEPARATOR . 'filesystem_functions.php';
require_once    __DIR__ . DIRECTORY_SEPARATOR . 'datetime_functions.php';
require_once    __DIR__ . DIRECTORY_SEPARATOR . 'random.php';
require_once    __DIR__ . DIRECTORY_SEPARATOR . 'uuid.php';

/// This is the `IS_MOBILE_BROWSER` constant (defined in `./constants.php`) defined as a function.
/**
 *  \details    Borrowed and adapted from http://detectmobilebrowsers.com/
 *  \return     <boolean>   TRUE:   Yeppers -- it's a mobile browser.
 *                          FALSE:  It's not a mobile browser -- or `$_SERVER['HTTP_USER_AGENT']`
 *                                  could not be read.
 */
function    is_mobile_browser() {
    return  (boolean)require_once __DIR__ . DIRECTORY_SEPARATOR . 'detect-mobile-browsers.php';
}

