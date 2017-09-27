<?php
/**
 * \file            json_bourne.php
 * \brief           A facade with static functions that assists with json related stuff
 * \since           12 Aug 2013 
 *
 */

// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// INCLUDES AND REQUIRES
require_once    __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';



// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// GLOBAL CONSTANTS



// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// OBJECT DEFINITION

/// \copydoc    json_bourne.php
/// \pattern    Facade / Wrapper
/**
 *  \details    
 *      ##USAGE
 *      Beyond `encode()` and `decode()` (which are merely wrappers for `json_encode()` and `json_decode()`
 *      There are two primary methods that this class is used for:
 *      
 *      1. Sanitizing strings to prevent `json_decode()` errors
 *      1. Finding a useful meaning for an integer returned by `json_last_error()`
 *
 *      ### `JsonBourne::getErrorMessage( $int )`
 *          Take a look at the method document  and the purpose of this function will be self-evident
 *          (if it isn't already)
 *      
 *      ### `JsonBourne::sanitizeString( $str );`
 *          This function exists for making JSON configuration files more user friendly.
 *
 *          given the following JSON:
\verbatim

    // this is a comment
    //this is another comment
    /// a doxygen-style comment
    {
        "session_timeout"   :   {
            "value" :   "43200",
            "usage_instructions"	:   
                "Specifies the time-out period assigned to the PHP session object for the application, in seconds.<br />
                E.g: If the user does not refresh or request a page within the time-out period, the session ends.",
            "data_type" :       "Integer",
            "default_value" :   "43200"
        },
                // this is yet another comment
        "require_https" : {
            "value" :   "false",
            "usage_instructions"    : "A value to indicate whether a secure connection will be required. // this is not a comment and will not be removed",
    //        "data_type" :       "Boolean",
            "default_value" :   "false"
        }
    }

\endverbatim
 *
 *          ...the following would be reutrned: 
\verbatim

    '{"session_timeout" : {"value" : "43200","usage_instructions" : "Specifies the time-out period assigned to the PHP session object for the application, in seconds.<br />E.g: If the user does not refresh or request a page within the time-out period, the session ends.","data_type" : "Integer","default_value" : "43200"},"require_https" : {"value" : "false","usage_instructions" : "a value to indicate whether a secure connection will be // required.","default_value" : "false"}}'

\endverbatim
 *      
 *          #### Rules for `sanitizeString()`
 *          1. Only entire lines may be commented out.  You may **NOT** include comments
 *             at the end of a line:
 \verbatim  
        "some_key" : "Some value",  // this is a bad comment b/c it does not comment out the entire line
        // This is a good comment b/c it comments out the entire line
            // leading whitespace in comments is OK too.
        // Comments that block valid JSON are OK:
        //      "some_other_key" : "Some other value",
        //      "some_array"    : [ "foo",
        //          "bar", 
        //          "foobar"
        //      ]
 \endverbatim
        
 */
class   JsonBourne   {

    // ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
    // PUBLIC STUFF

    // ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░
    // ░ ░ ░ CLASS CONSTANTS


    // ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░
    // ░ ░ ░ PHP MAGIC CLASS FUNCTIONS

    // / Ctor: default
    // public function __construct()    {   }

    // / Dtor: default
    // public function __destruct() {   }

    // / clone: default
    // public function __clone() {  }

    // / sleep: default
    // public function  __sleep() { }

    // / wakeup: default
    // public function  __wakeup() {    }

    // / autoload: default
    // public function  __autoload() {  }

    // / toString: default
    // public function  __toString() {  }


    // ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░
    // ░ ░ ░ CLASS PROPERTY GETTERS AND SETTERS

    // / set: default
    // public function __set( $var, $value ){   }

    // / get: default
    // public function  __get( $var ){  }

    
    // ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░
    // ░ ░ ░ INHERITED AND OVERRIDDEN PUBLIC CLASS FUNCTIONS  (list static functions first!!)


    // ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░
    // ░ ░ ░ OTHER PUBLIC CLASS FUNCTIONS (list static functions first!!)

    /// Encodes json.  Returns either a valid json encoded string or an error code
    /// \param  <Mixed> $encodeMe   the string or array you wish to encode
    /// \return <String>    Encoding successful: Here's your json string
    /// \return <Int>       A value from json_last_error()
    public  static  function    encode( &$encodeMe ) {
        $rv     = json_encode( $encodeMe );
        $err    = json_last_error();
        return  ( JSON_ERROR_NONE === $err )   ?   ( $rv ) : ( $err );
    }


    /// Encodes a JSON string that has been sanitzed for HTML
    /// \param  <Mixed>     $encodeMe       the string or array you wish to encode
    /// \param  <BitMask>   $encodeFlags    default = ENT_QUOTES
    ///                                     See http://php.net/manual/en/function.htmlspecialchars.php
    ///                                     for a list of flags
    /// \return <String>    Encoding successful: Here's your json string
    /// \return <Int>       A value from json_last_error()
    public static   function    encodeHtmlSanitized( &$encodeMe, $encodeFlags = ENT_QUOTES )  {
        $rv     = json_encode( $encodeMe );
        $err    = json_last_error();
        return  ( JSON_ERROR_NONE === $err )   
                ?   ( htmlspecialchars( $rv, $encodeFlags ) ) 
                : ( $err );
    }


    /// Decodes json.  Returns either an array or an error code
    /// \param  <Mixed> $decodeMe   the json string you wish to decode
    /// \return <Array>     Decoding successful: here's your Array
    /// \return <Int>       A value from json_last_error()
    public  static  function    decode( &$decodeMe ) {
        $rv     = json_decode( $decodeMe, true );
        $err    = json_last_error();
        return  ( JSON_ERROR_NONE === $err )   ?   ( $rv ) : ( $err );
    }


    /// Decodes a JSON string that has been sanitized for HTML
    /// \param  <Mixed>     $decodeMe       The JSON string you wish to decode
    /// \param  <BitMask>   $decodeFlags    default = ENT_QUOTES
    ///                                     See http://us3.php.net/manual/en/function.htmlspecialchars-decode.php
    ///                                     for a list of flags
    /// \return <Array>     Decoding successful: here's your Array
    /// \return <Int>       A value from json_last_error() to pass to JsonBoure::getErrorMessage()
    public static   function    decodeHtmlSanitized( $decodeMe, $decodeFlags = ENT_QUOTES )  {
        return  self::decode( htmlspecialchars_decode ( $decodeMe, $decodeFlags ) );
    }


    /// Returns a meaningful message from a json error code
    /// \param  <Int>   $jsonErrorCode  The error code you want a message for
    /// \return <String>    The error code message
    public  static  function    getErrorMessage( $code )    {
        switch( $code ) {
            default :   
                return  'I have no idea what error code "' . var_export( $code, true ) . '" means.';
            case    JSON_ERROR_NONE :
                return  var_export( $code, true ) . ' JSON_ERROR_NONE: No error has occurred';	 
            case    JSON_ERROR_DEPTH :
                return  var_export( $code, true ) . ' JSON_ERROR_DEPTH: The maximum stack depth has been exceeded';	 
            case    JSON_ERROR_STATE_MISMATCH :
                return  var_export( $code, true ) . ' JSON_ERROR_STATE_MISMATCH: Invalid or malformed JSON';	 
            case    JSON_ERROR_CTRL_CHAR    :
                return  var_export( $code, true ) . ' JSON_ERROR_CTRL_CHAR: Control character error, possibly incorrectly encoded';	 
            case    JSON_ERROR_SYNTAX   :
                return  var_export( $code, true ) . ' JSON_ERROR_SYNTAX: Syntax error';	 
            case    JSON_ERROR_UTF8 :
                return  var_export( $code, true ) . ' JSON_ERROR_UTF8: Malformed UTF-8 characters, possibly incorrectly encoded';
        }
    }

    /// Sanitizes JSON strings; useful for reading in config files that have some human readable text inside
    /**
     *  \details    Specifically, does the following to a string:
     *              1. Strips out C-Style line comments
     *              1. Strips out leading tabs and spaces.
     *              1. Strips out extra spaces 
     *              1. Strips out carriage returns
     *
     *  \param  <String>    The string to sanitize
     *  \return     <String>    A sanitized string that *should* be safe for json_decode()
     */
    public  static  function    sanitizeString( $dirtyStr ) {
        $searchPatterns = Array();
        $replacements   = Array();
        
        // C-Style line comments; see http://www.php.net/manual/en/reference.pcre.pattern.modifiers.php
        array_push( $searchPatterns,    '%^\s*//.*\R%m' );
        array_push( $replacements,      "" );

        // pre-ceeding spaces 
        array_push( $searchPatterns, '%\R\011+[ ]*%');
        array_push( $searchPatterns, '%\R[ ]+%' );
        array_push( $replacements,      "" );
        array_push( $replacements,      "" );

        // extra spaces
        array_push( $searchPatterns, '%\011%');
        array_push( $searchPatterns, '%[ ]{2,}%' );
        array_push( $replacements,      " " );
        array_push( $replacements,      " " );

        // carriage returns
        array_push( $searchPatterns,    '%\R%' );
        array_push( $replacements,      '' );

        return  preg_replace( $searchPatterns, $replacements, $dirtyStr );
    }


    

    // ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
    // PROTECTED STUFF

    // ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░
    // ░ ░ ░ INHERITED AND OVERRIDDEN PRIVATE CLASS FUNCTIONS  (list static functions first!!)


    // ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░
    // ░ ░ ░ OTHER PROTECTED CLASS FUNCTIONS (list static functions first!!)



    // ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
    // PRIVATE STUFF

    // ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░
    // ░ ░ ░ MEMBER VARIABLES/PROPERTIES  (list static vars first!!)
    

    // ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░
    // ░ ░ ░ FUNCTIONS USED BY `__set( $var, $value )` and `__get( $var )` 
    // ░ ░ ░ (list static functions first!!)


    // ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░ ░
    // ░ ░ ░ OTHER PRIVATE CLASS FUNCTIONS (list static functions first!!)
    

    // ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
    // END OBJECT DEFINITION
    // ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
}

