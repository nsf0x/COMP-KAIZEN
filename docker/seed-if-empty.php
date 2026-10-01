<?php

use Illuminate\Contracts\Console\Kernel;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = require dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$exitCode = $kernel->call('kaizen:bootstrap-production');
echo $kernel->output();

exit($exitCode);