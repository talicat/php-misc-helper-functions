<?php
/**
 * \file            datetime_functions.php
 * \brief           Wrappers and functions for dealing with the filesystem
 * \since           22 July 2015
 *
 */

// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// INCLUDES AND REQUIRES
// Note creative uses ( Examples 4,5,6 ): http://www.php.net/manual/en/function.include.php
require_once    __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';



// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// GLOBAL CONSTANTS
//      http://www.php.net/manual/en/language.constants.predefined.php



// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// GLOBAL FUNCTIONS

/// Indicates if a timezone identifier string is a valid PHP timezone.
/// See:
///     - http://stackoverflow.com/a/5823217
///     - http://php.net/manual/en/datetimezone.construct.php
/// \param  <String>    $timezone   The timezone identifier you wish to use
/// \return <Boolean>       true:   $timezone is a valid PHP timezone
///                         false:  $timezone is not a valid PHP timezone
function    isValidPhpTimezone( $timezone ) {
    try{
        new DateTimeZone( $timezone );
    }
    catch( Exception $e )   {
        return false;
    }

    return true;
}



/// Provides a timestamp similar to the functionality provided by the `timestamp` shell script
/// see:
///     - http://php.net/manual/en/datetime.format.php
///     - http://php.net/manual/en/timezones.php
///     - http://php.net/manual/en/function.date.php
/// \param  <String>    tz      A valid PHP timezone from http://php.net/manual/en/timezones.php
///                             DEFAULT = 'UTC'
/// \return <String>    The timestamp; format: ( YYYY-MM-DD_hh-mm-ss_TimezoneAbbreviation_UtcOffset )
///                         2015-12-01_15.05.45_PDT-0700
///                         2015-08-20_07.09.10_AEST_plus1000
function    getTimestamp( $tz = 'UTC' )  {
    $tzone      = ( true === isValidPhpTimezone( $tz ) )  ?  $tz  :  'UTC';
    $dateObj    = new DateTime( 'now', new DateTimeZone( $tzone ) );
     
    switch( strtolower( $tzone ) )  {
        case    'utc'       :   // intentional fall-thru (why does php need this many for zulu?!?)
        case    'etc/gmt'   :
        case    'etc/gmt'   :
        case    'etc/gmt+0' :
        case    'etc/gmt0'  :
        case    'etc/greenwich' :
        case    'etc/uct'   :
        case    'etc/universal' :
        case    'etc/utc'   :
        case    'etc/zulu'  :
        case    'factory'   :
        case    'gmt'       :
        case    'gmt-0'     :
        case    'gmt0'      :
        case    'universal' :
        case    'zulu'      :
            return  str_ireplace( '+', 'plus', $dateObj->format( 'Y-m-d_H.i.s_T' ) );
            break;  // LOL!
        default :
            return  str_ireplace( '+', 'plus', $dateObj->format( 'Y-m-d_H.i.s_TO' ) );
    }
}


// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// NAMESPACES ( php > 5.3 )
// http://www.php.net/manual/en/language.namespaces.php

