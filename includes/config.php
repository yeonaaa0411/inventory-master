<?php
// Detect if the environment is local or deployed
$hostname = gethostname(); // Get the current machine's hostname

// Define database connection parameters based on the environment
if ($hostname === 'localhost') {
    // Local environment (XAMPP or local server)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', ''); // No password for localhost
    define('DB_NAME', 'sql12742917');
} else {
    // Deployed environment
    define('DB_HOST', 'sql12.freemysqlhosting.net');
    define('DB_USER', 'sql12742917');
    define('DB_PASS', 'YjQg1PGRWb');
    define('DB_NAME', 'sql12742917');
}

// Optional: Define the port for the MySQL connection
define('DB_PORT', 3306);
