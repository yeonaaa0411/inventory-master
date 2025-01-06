<?php

// Check if running on localhost or deployed server
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    // Local environment configuration
    define( 'DB_HOST', 'localhost' );          // Set database host for local
    define( 'DB_USER', 'root' );               // Set database user for local
    define( 'DB_PASS', 'p1r4sp' );             // Set database password for local
    define( 'DB_NAME', 'sql12755959' );        // Set database name for local
} else {
    // Deployed environment configuration using environment variables
    define( 'DB_HOST', getenv('DB_HOST') );    // Use environment variable for DB host
    define( 'DB_USER', getenv('DB_USER') );    // Use environment variable for DB user
    define( 'DB_PASS', getenv('DB_PASS') );    // Use environment variable for DB password
    define( 'DB_NAME', getenv('DB_NAME') );    // Use environment variable for DB name
    define( 'DB_PORT', getenv('DB_PORT') );    // Use environment variable for DB port
}

// Optionally, you can use this to check the connection
// echo 'DB_HOST is: ' . DB_HOST;  // To debug the active DB connection

?>
