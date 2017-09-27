<?php
/**
 * \file    constants.php
 * \brief   A single file to define all constants used in this directory/package.
 * \since   22 July 2014
 */

if( false === defined( 'IS_MOBILE_BROWSER' ) ) :
    define( 'IS_MOBILE_BROWSER', 
    (boolean)require_once __DIR__ . DIRECTORY_SEPARATOR . 'detect-mobile-browsers.php', 
    true );
endif;

