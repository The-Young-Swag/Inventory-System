<?php

/**
 * Script to create proper password hash for admin user
 * Run this once after database setup
 */

$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "Original Password: $password\n";
echo "Hashed Password: $hashed_password\n\n";

// SQL to update the admin password
echo "Run this SQL in your database:\n";
echo "UPDATE user_management SET password_hash = '$hashed_password' WHERE username = 'admin';\n";
?><?php
    /**
     * Script to create proper password hash for admin user
     * Run this once after database setup
     */

    $password = 'admin123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    echo "Original Password: $password\n";
    echo "Hashed Password: $hashed_password\n\n";

    // SQL to update the admin password
    echo "Run this SQL in your database:\n";
    echo "UPDATE user_management SET password_hash = '$hashed_password' WHERE username = 'admin';\n";
    ?>