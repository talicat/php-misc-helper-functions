<?php
/**
 * \file            filesystem_functions.php
 * \brief           Wrappers and functions for dealing with the filesystem
 * \since           22 July 2015
 */

// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// INCLUDES AND REQUIRES
// Note creative uses ( Examples 4,5,6 ): http://www.php.net/manual/en/function.include.php
require_once    __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';
require_once    __DIR__ . DIRECTORY_SEPARATOR . 'datetime_functions.php';




// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// GLOBAL CONSTANTS
//      http://www.php.net/manual/en/language.constants.predefined.php



// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// GLOBAL FUNCTIONS

/// Provides human readable file sizes similar to `ls -lh`
/// \param  <Integer>   $int    The size (in bytes) of the file
/// \return <Boolean>   false   You passed something that is not a number as a parameter.
/// \return <String>            The size of the file (rounded to the two decimal places) in: 
///                             - "B"   bytes (rounded to three decimal places)
///                             - "KB"  kilobytes
///                             - "MB"  megabytes
///                             - "GB"  gigabytes
function    getHumanReadableFileSize( $int )    {
    if( FALSE === is_nan( $int ) )  {
        switch( strlen( abs( $int ) ) )   {
            default     :   // fall thru
            case    1   :
            case    2   :   
                return  round( abs( $int ), 3 ) . ' B';
            case    3   :   // fall thru
            case    4   :
            case    5   :
            case    6   :   
                return  round( ( abs( $int ) / 1024 ), 2 )  . ' KB';
            case    7   :   // fall thru
            case    8   :
            case    9   :   
                return  round( ( abs( $int ) / 1048576 ), 2 )  . ' MB';
            case    10  :   // fall thru
            case    11  :
            case    12  :
            case    13  :
                return  round( abs( $int ) / 1073741824 , 2 )  . ' GB';
        }
    }
    return '--';
}



/// Provides functionality similar to `ls -l`.  RETURNS ONLY FILE NAMES, NOT DIRECTORIES!
/// \param  <String>    $pathFilter     The path (including filename filters) to return information about
/// \param  <String>    $timezone       The timezone to use for file data.
///
/// \param  <Boolean>   $useHumanReadableFileSizes  Flag to indicate whether file sizes should be
///                                                 returned with suffixes as described in getHumanReadableFileSize()
/// \param  <Boolean>   $includeMd5Checksum         Flag to indicate whether to include an MD5 checksum in the return
/// \param  <String>    $sortField          Flag to indicate how returned array should be sorted:
///                                             'filename'  (DEFAULT) Sort the array by filename
///                                             'extension' Sort the array by file extension
///                                             'mtime'     Sort the array by file modified time
///                                             'ctime'     Sort the array by file creation time
///                                             'size'      Sort the array by file size
///                                         NOTES:
///                                             - Natural sort will be used!
///                                             - 'Sort by field' MAY OR MAY NOT be the first index in the returned array.
/// \param  <Boolean>   $sortDescending     Flag to indicate whether the sort done on the return array should be 
///                                         ascending (default) or descending
///                                         - true  Use a descending sort
///                                         - false (DEFAULT) Use an ascending sort
///
/// \return <Boolean>   false   Your path filter was not usable.  See http://php.net/manual/en/function.glob.php
/// \return <Array>             Details about the path:
///                                 'filename'  => The filename without a path
///                                 'extension' => The file extension
///                                 'mtime'     => File modify time( Mon, 13 Jul 2015 17:38:41 (America/New_York [UTC -0400]) )
///                                 'unix_mtime'    => File modify time (unix time)
///                                 'ctime'     => File create time( Mon, 13 Jul 2015 17:38:41 (America/New_York [UTC -0400]) )
///                                 'unix_ctime'    => File modify time (unix time)
///                                 'size'      => Size of file
///                                                 <Integer>   size in bytes
///                                                 <String>    human readable size w/ suffix
///                                 'size_bytes'    => Size of file in bytes
///                                 'md5_checksum'  => The MD5 checksum of th file
function    getFileListing( $pathFilter, $timezone, $useHumanReadableFileSizes, $includeMd5Checksum, $sortField = 'filename', $sortDescending = false )  {
    $files   = glob( $pathFilter, GLOB_ERR );
    if( false === is_array( $files ) )  {
        return  false;
    }

    $ro = Array();
    reset( $files );
    while( list( $key, $value ) = each( $files ) )  {
        if( true === is_file( $value ) )    {
            $stat       = stat( $value );
            $dateObj    = new DateTime( 'now', new DateTimeZone( 'UTC' ) );
            $info       = pathinfo( $value );
            
            $file   = array();
            
            
            if( true === isValidPhpTimezone( $timezone ) )  {
                $dateObj->setTimeZone( new DateTimeZone( $timezone ) );
                
                $dateObj->setTimestamp( $stat['mtime'] );
                $mtime  = $dateObj->format( 'D, d M Y H:i:s (e [\U\T\C O])' );

                $dateObj->setTimestamp( $stat['ctime'] );
                $ctime  = $dateObj->format( 'D, d M Y H:i:s (e [\U\T\C O])' );
            }
            else    {
                $dateObj->setTimestamp( $stat['mtime'] );
                $mtime  = $dateObj->format( 'D, d M Y H:i:s (e)' );

                $dateObj->setTimestamp( $stat['ctime'] );
                $ctime  = $dateObj->format( 'D, d M Y H:i:s (e)' );
            }
            

            // Return array sorting: set the first field to be the one that is used for sorting
            switch( strtolower( $sortField ) )  {
                case    'mtime' :
                    $file['unix_mtime'] = $stat['mtime'];
                    $file['mtime']      = $mtime;
                    $file['unix_ctime'] = $stat['ctime'];
                    $file['ctime']      = $ctime;
                    $file['filename']   = $info['basename'];
                    $file['extension']  = $info['extension'];
                    $file['size_bytes'] = $stat['size'];
                    $file['size']   = ( true === $useHumanReadableFileSizes )   ?
                                getHumanReadableFileSize( $stat['size'] )  :   $stat['size'];
                    break;
                case    'ctime' :
                    $file['unix_ctime'] = $stat['ctime'];
                    $file['ctime']      = $ctime;
                    $file['unix_mtime'] = $stat['mtime'];
                    $file['mtime']      = $mtime;
                    $file['filename']   = $info['basename'];
                    $file['extension']  = $info['extension'];
                    $file['size_bytes'] = $stat['size'];
                    $file['size']   = ( true === $useHumanReadableFileSizes )   ?
                                getHumanReadableFileSize( $stat['size'] )  :   $stat['size'];
                    break;
                case    'size'  :
                    $file['size_bytes'] = $stat['size'];
                    $file['size']   = ( true === $useHumanReadableFileSizes )   ?
                                getHumanReadableFileSize( $stat['size'] )  :   $stat['size'];
                    $file['filename']   = $info['basename'];
                    $file['extension']  = $info['extension'];
                    $file['unix_ctime'] = $stat['ctime'];
                    $file['ctime']      = $ctime;
                    $file['unix_mtime'] = $stat['mtime'];
                    $file['mtime']      = $mtime;
                    break;
                case    'ext'   :       // intentional fall thru
                case    'extension' :
                    $file['extension']  = $info['extension'];
                    $file['filename']   = $info['basename'];
                    $file['size_bytes'] = $stat['size'];
                    $file['size']   = ( true === $useHumanReadableFileSizes )   ?
                                getHumanReadableFileSize( $stat['size'] )  :   $stat['size'];
                    $file['unix_ctime'] = $stat['ctime'];
                    $file['ctime']      = $ctime;
                    $file['unix_mtime'] = $stat['mtime'];
                    $file['mtime']      = $mtime;
                    break;
                case    'filename'  :   // intentional fall thru
                default :
                    $file['filename']   = $info['basename'];
                    $file['extension']  = $info['extension'];
                    $file['size_bytes'] = $stat['size'];
                    $file['size']   = ( true === $useHumanReadableFileSizes )   ?
                                getHumanReadableFileSize( $stat['size'] )  :   $stat['size'];
                    $file['unix_ctime'] = $stat['ctime'];
                    $file['ctime']      = $ctime;
                    $file['unix_mtime'] = $stat['mtime'];
                    $file['mtime']      = $mtime;
                    break;
            }
                        
            if( true === $includeMd5Checksum )  {
                $file['md5_checksum']        = md5_file( $value );
            }

            array_push( $ro, $file );
        }
    }
    
    // sort the return array
    $sortOrder  = ( true === $sortDescending )  ?   SORT_DESC  :  SORT_ASC;
    switch( strtolower( $sortField ) )  {
        case    'filename'  :
        case    'ext'       :
        case    'extension' :
            array_multisort( $ro, $sortOrder, SORT_NATURAL | SORT_FLAG_CASE );
            break;
        case    'size'  :
        case    'mtime' :
        case    'ctime' :
        default :
            array_multisort( $ro, $sortOrder, SORT_REGULAR );
            break;
    }

    return  $ro;
}


/// Downloads a file from the system
/// See  http://stackoverflow.com/a/7263943
/// \param  <String>    $path   The path and filename that you want to download
function    downloadFile( $path )   {
    if( true === file_exists( $path ) ) {
        header( 'Content-Description: File Transfer' );
        header( 'Content-Type: application/zip' );
        header( 'Cache-Control: must-revalidate' );
        header( 'Pragma: public' );
        header( 'Content-Length: ' . filesize( $path ) );
        readfile( $path );
        echo '<script>window.close();</script>';
        exit;
    }
}

// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// NAMESPACES ( php > 5.3 )
// http://www.php.net/manual/en/language.namespaces.php

