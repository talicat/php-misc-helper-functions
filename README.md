These are some miscellaneous helper functions that I've used
over the past few years.


USAGE
=====
These functions are intended to be used as a library.  Simply `include` or
`require` the `package.php` file and you have everything you need.

Alternatively, you can `include` or `require` individual files for efficiency.
Each individual file is setup to include everthing it needs to stand alone.

FILES
=====
Generally speaking, this directory structure is meant to be used
within an application.  Therefore, some files are listed here
that are blank -- but would be populated with data upon use.

I implemented my microframeworks as follows:

    DIRECTORY                   <--- Package name
    +--- package.php            <--- `include` this file, and you have everything you need.
    +--- constants.php          <--- Global constants for the package.
    +--- helper_functions.php   <--- global scope functions needed for the package
    +--- other-files_and-directories

`constants.php`
---------------
Global constants needed for the functions in the package.


`curl.php`
----------
I had a need to utilize some of PHP's curl functionality for client-side
troubleshooting.  These are intended to be wrappers for the toolset that was
required.


`datetime_functions.php`
------------------------
Functions related to dates.


`filesystem_functions.php`
--------------------------
Functions related to UNIX file systems.


`json_bourne.php`
-----------------
JSON Bourne ... GET IT? :-)

A smart-alec class meant to wrapper some JSON functionality.


`package.php`
-------------
The one-stop-shop file to get all the miscellaneous functions.  Include this in your controller,
and you've got everthing you need.


`random.php`
------------
If there is one thing that computers _don't_ do well, it's random numbers.  This includes
some functions to accomodate and reliably generate randomness.


`uuid.php`
----------
Functions related to Globally Unique IDentifiers (GUIDs) and Universally Unique IDentifiers (UUIDs)

