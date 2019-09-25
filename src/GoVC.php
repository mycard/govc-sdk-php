<?php


namespace MisakaCloud\GoVC;

use MisakaCloud\GoVC\Cmds\VM;

class GoVC
{
    private $goVcBin;
    private $goVcUrl;
    private $timeout;

    /**
     * GoVC constructor.
     * @param $goVcBin
     * @param $goVcUrl
     * @param $timeout
     */
    public function __construct($goVcBin, $goVcUrl, $timeout)
    {
        $this->goVcBin = $goVcBin;
        $this->goVcUrl = $goVcUrl;
        $this->timeout = $timeout;
    }


    /**
     * @return mixed
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param mixed $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }


    public function vm()
    {
        $vm = new VM($this->goVcBin, $this->goVcUrl, $this->timeout);
        return $vm;
    }


}

