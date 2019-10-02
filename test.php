<?php
require __DIR__ . '/vendor/autoload.php';

use MisakaCloud\GoVC\GoVC;
use MisakaCloud\GoVc\Helper\CredentialHelper;

$cred = new CredentialHelper("URL", "Administrator@vsphere.local", "123456");
//$GOVC_URL=$cred->getGoVcURL();
$GOVC_BIN = "213";
$timeout = 30;
$dc="虚拟数据中心";
$govc = new GoVC($GOVC_URL, $GOVC_BIN, $timeout,$dc);
$govc->vm()->network()->change();
