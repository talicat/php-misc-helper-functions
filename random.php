<?php
/**
 * \file            helper_functions.php
 * \brief           Global functions for the info site
 * \since           17 June 2015
 */


// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// INCLUDES AND REQUIRES
require_once    __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';



// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// GLOBAL FUNCTIONS

/// Used to determine if the length is both an integer *and* between 8 - 99 characters long\
/// \param  $value  <mixed>     The item requiring validation
/// \return <boolean>   `TRUE`:     `$value` is an integer greater than 7 and less than 100
///                     `FALSE`:    `$value` is not an integer 
///                                 *or* is less than 8 
///                                 *or* is greater than 99
function    is_valid_random_string_length( $value )   {
    if( false === is_numeric( $value ) ) {
        return false;
    }
    else    {
        return  ( 7 < $value  &&  100 > $value )  ?   true   :  false;
    }
}



/// Used to generate a random string
/// \param  <Integer>   $length             The length (8-99) of the password
/// \param  <Boolean>   $alphanumeric_only  Flag to indicate that only alphanumeric characters should be returned
/// \return <string>        The random string
/// \return <Boolean>   FALSE   You have an invalid parameter somewhere.
function    generate_random_character_string( $length = 30, $alphanumeric_only = false )    {
    if(     true === is_valid_random_string_length( $length ) 
        &&  true === is_bool( $alphanumeric_only )
    )   {

/* using openssl thru base 64 (limits the chars)
        $system_command = '/usr/bin/openssl rand -base64 200';


        $system_command .= ( true === $alphanumeric_only )
                ?   "  | grep -o '[[:alnum:]]'"
                :   "  | grep -o '[[:print:]]'"; 

        $system_command .= "  | tr -d '" . '\n' ."' | head -c $length" ;
*/
        $system_command = '< /dev/urandom  tr -dc';
        $system_command .= ( true === $alphanumeric_only )
                ?   " '[:alnum:]'"
                :   " '[:print:]'";
        $system_command .=  " | head -c $length";

//        var_dump( $system_command); echo '<br>';
        
        ob_start();
        $system_return_value    = null;
        $system_output  = system( "$system_command", $system_return_value );
        ob_end_clean();

        return  (string)$system_output;
    }
    else    {
        return  false;
    }
}

