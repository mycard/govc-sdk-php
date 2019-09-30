<?php


namespace MisakaCloud\GoVc\Helper;


class CredentialHelper
{
    public $goVcURL;

    /**
     * @return string
     */
    public function getGoVcURL(): string
    {
        return $this->goVcURL;
    }

    function __construct($url, $username, $password)
    {
        $encodedPassword = urlencode($password);
        $goVcURL = "https://" . $username . ":" . $encodedPassword . "@" . $url . "/sdk";
        // 下面是错误的编码方式
        // $goVcURL = "https://" . $userName . ":" . $passWord . "@" . $url . "/sdk";
        // $goVcURL = urlencode($goVcURL);
        $this->goVcURL = $goVcURL;
    }
}
