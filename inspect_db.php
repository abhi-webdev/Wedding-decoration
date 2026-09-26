<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = Illuminate\Support\Facades\DB::select('SHOW TABLES');
echo "Tables:\n";
foreach($tables as $t) {
    $var = get_object_vars($t);
    $tableName = array_values($var)[0];
    echo "- " . $tableName . "\n";
}
