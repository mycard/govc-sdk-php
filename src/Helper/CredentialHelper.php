<?php


namespace MisakaCloud\GoVc\Helper;


/**
 * Class CredentialHelper
 * @package MisakaCloud\GoVc\Helper
 */
class CredentialHelper
{

    /**
     * CredentialHelper constructor.
     * @param $url
     * @param $username
     * @param $password
     */
    function __construct($url, $username, $password)
    {
        global $globalGoVcURL;
        $encodedPassword = urlencode($password);
        $globalGoVcURL = "https://" . $username . ":" . $encodedPassword . "@" . $url . "/sdk";
        // 下面是错误的编码方式
        // $goVcURL = "https://" . $userName . ":" . $passWord . "@" . $url . "/sdk";
        // $goVcURL = urlencode($goVcURL);
        // $this->goVcURL = $goVcURL;
    }
}
