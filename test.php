<?php
require __DIR__ . '/vendor/autoload.php';

use MisakaCloud\GoVC\GoVC;
use MisakaCloud\GoVc\Helper\CredentialHelper;
use MisakaCloud\GoVc\Helper\EnvironmentHelper;

$GOVC_BIN = "213";
$timeout = 30;
$dc = "虚拟数据中心";
$cred = new EnvironmentHelper("URL", "Administrator@vsphere.local", "123456", $GOVC_BIN, $dc, $timeout);
$govc = new GoVC();
$govc->vm()->network()->change();
