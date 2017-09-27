<?php
/**
 * \file            uuid.php
 * \brief           Functions related to generating and managing GUID/UUIDs
 * \since           08 Aug 2015
 *
 */



// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// INCLUDES AND REQUIRES
// Note creative uses ( Examples 4,5,6 ): http://www.php.net/manual/en/function.include.php
require_once    __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';



// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// GLOBAL FUNCTIONS

/// Determines if an ID string is a GUID or not.
/// Adapted from http://stackoverflow.com/questions/1253373/php-check-for-valid-guid
/// \param  <string>    $am_i_guid    the subject of the test
/// \return <bool>  true:   $am_i_guid is a GUID
///                 false:  $am_i_guid is not a GUID
function isGuidId( $am_i_guid )    {
    $rv = ( preg_match( 
        '/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', strtoupper( $am_i_guid ) ) )
        ?   true  :  false;
    
    return $rv;
}


/// Determines if an ID string is numeric or not.  Broken out into separate function for logging
/// http://us2.php.net/is_numeric
/// \param  <string>    $am_i_numeric    the subject of the test
/// \return <bool>  true:   $am_i_numeric is a numeric id
///                 false:  $am_i_numeric is not a numeric id
function isNumericId( $am_i_numeric )    {
    return is_numeric( $am_i_numeric );
}



/// Generates a GUID; adapted from http://stackoverflow.com/a/15875555/293332
/// \return <String>    Here's your GUID
function createGUID()   {
        // NOTE: OpenSSL is the first of the Remote Link required modules, and has been
        // for some time.  
    $data = openssl_random_pseudo_bytes( 16 );
    $data[6] = chr( ord($data[6]) & 0x0f | 0x40 ); // set version to 0010
    $data[8] = chr( ord($data[8]) & 0x3f | 0x80 ); // set bits 6-7 to 10

    return vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split( bin2hex( $data ), 4 ) );
}



/// Alias for `createGUID()`
/// \copydoc createGUID()
function    createUUID()    {
    return  createGUID();
}

