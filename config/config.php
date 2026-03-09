<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Application Configuration
|--------------------------------------------------------------------------
| Pure configuration file.
| Returns an associative array used by the application.
*/

return [

'app'=>[
'name'=>'InventoryProfitSystem',
'env'=>'local',
'timezone'=>'Asia/Manila'
],

'db'=>[
'driver'=>'mysql',
'host'=>'localhost',
'port'=>3306,
'database'=>'inventory_profit_system',
'username'=>'root',
'password'=>'',
'charset'=>'utf8mb4'
]

];