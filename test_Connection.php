<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Database Connection Test
|--------------------------------------------------------------------------
*/

try{

$pdo=require __DIR__.'/config/database.php';

echo "✅ Database connection successful.";

}catch(PDOException $exception){

echo "❌ Database connection failed<br>";
echo $exception->getMessage();

}