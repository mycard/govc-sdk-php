<?php
require __DIR__ . '/vendor/autoload.php';

use MisakaCloud\GoVC\GoVC;

$GOVC_URL = "213";
$GOVC_BIN = "213";
$timeout = 30;
$govc = new GoVC($GOVC_URL, $GOVC_BIN, $timeout);
$govc->vm()->clone();
