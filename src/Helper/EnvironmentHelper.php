<?php


namespace MisakaCloud\GoVc\Helper;


class EnvironmentHelper
{

    /**
     * EnvironmentHelper constructor.
     */
    public function __construct($url, $username, $password, $goVcBin, $dataCenter, $timeout)
    {
        global $globalGoVcURL;
        global $globalGoVcBin;
        global $globalGoVcDataCenter;
        global $globalProcessTimeout;

        $encodedPassword = urlencode($password);
        $globalGoVcURL = "https://" . $username . ":" . $encodedPassword . "@" . $url . "/sdk";
        $globalGoVcBin = $goVcBin;
        $globalGoVcDataCenter = $dataCenter;
        $globalProcessTimeout = $timeout;
    }
}
