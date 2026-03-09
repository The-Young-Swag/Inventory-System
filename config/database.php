<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| PDO Database Connection
|--------------------------------------------------------------------------
| Creates a PDO instance using configuration values.
| Returns a ready-to-use PDO object.
*/

$config=require __DIR__.'/config.php';
$db=$config['db'];

$dsn=
"{$db['driver']}:host={$db['host']};".
"port={$db['port']};".
"dbname={$db['database']};".
"charset={$db['charset']}";

$options=[
PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
PDO::ATTR_EMULATE_PREPARES=>false
];

return new PDO(
$dsn,
$db['username'],
$db['password'],
$options
);