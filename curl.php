<?php
/**
 * \file    curl.php
 * \breif   Functions related to using cURL with PHP
 * \since   22 Jul 2014
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'package.php';


/// A function that allows PHP to make AJAX-like calls.
/// \details    adapted from http://stackoverflow.com/q/2126300 
/// \param  <String>    $url    the target of your query 
/// \return <Array>     The data (or error) you seek.  
function    fetch( $url )  {
    $rv = Array();  // the value to return 

    // see https://php.net/manual/en/function.curl-setopt.php 
    $options    = Array(
        CURLOPT_RETURNTRANSFER  => true,    // return string on success; false on failure 
        CURLOPT_HEADER          => false,   // don't return headers 
        CURLOPT_NOBODY          => false,   // include body 
        CURLOPT_FOLLOWLOCATION  => true,    // follow redirects 
        CURLOPT_ENCODING        => '',      // handle all encodings 
        CURLOPT_USERAGENT       => $_SERVER['HTTP_USER_AGENT'],  // who am i 
        CURLOPT_AUTOREFERER     => true,    // set referer on redirect 
        CURLOPT_CONNECTTIMEOUT  => 10,     // timeout on connect 
        CURLOPT_TIMEOUT         => 10,     // timeout on response 
        CURLOPT_FRESH_CONNECT   => true,
    );
                                                                                                    
    $handler    = curl_init( $url );
    curl_setopt_array( $handler, $options );
                                                                                                            
    $rv['result']   = curl_exec( $handler );
    $rv['meta']     = var_export( curl_getinfo( $handler ), true );
    $rv['error_code']   = curl_errno( $handler );
    $rv['error_message'] = curl_error( $handler );
                                                                                                                            
    return  $rv;
}


/// A function to retreive HTTP Headers from a URL
/// \details    adapted from http://stackoverflow.com/q/2126300
/// \param  <String>    $url    the target of your query
/// \return <Array>    The HTTP headers -- or error that you seek. 
function    query_headers( $url )  {

    $rv = Array();
    
    // see https://php.net/manual/en/function.curl-setopt.php
    $options    = Array(
        CURLOPT_RETURNTRANSFER  => true,    // return string on success; false on failure
        CURLOPT_HEADER          => true,    // return headers
        CURLOPT_NOBODY          => true,    // exclude body.  we just want the headers
        CURLOPT_FOLLOWLOCATION  => false,   // follow redirects
        CURLOPT_ENCODING        => '',      // handle all encodings
        CURLOPT_USERAGENT       => $_SERVER['HTTP_USER_AGENT'],  // who am i
        CURLOPT_AUTOREFERER     => true,    // set referer on redirect
        CURLOPT_CONNECTTIMEOUT  => 120,      // timeout on connect
        CURLOPT_TIMEOUT         => 120,      // timeout on response
        CURLOPT_MAXREDIRS       => 0,       // stop after 0 redirects
        CURLOPT_FRESH_CONNECT   => true,
    );

    $handler    = curl_init( $url );
    curl_setopt_array( $handler, $options );

    $rv['result']   = curl_exec( $handler );
    $rv['meta']     = var_export( curl_getinfo( $handler ), true );
    $rv['error_code']   = curl_errno( $handler );
    $rv['error_mesage'] = curl_error( $handler );
    
    return  $rv;
}
