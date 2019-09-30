<?php


namespace MisakaCloud\GoVC;

//use MisakaCloud\GoVC\Cmds\VM;
use MisakaCloud\GoVC\Cmds\VM\VM;

/**
 * Class GoVC
 * @package MisakaCloud\GoVC
 */
class GoVC
{
    private $goVcBin;
    private $goVcURL;
    private $timeout;
    private $dataCenter;

    /**
     * GoVC constructor.
     * @param $goVcBin
     * @param $goVcURL
     * @param $timeout
     * @param $dataCenter
     */
    public function __construct($goVcBin, $goVcURL, $timeout, $dataCenter)
    {
        $this->goVcBin = $goVcBin;
        $this->goVcURL = $goVcURL;
        $this->timeout = $timeout;
        $this->dataCenter = $dataCenter;
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
        $vm = new VM($this->goVcBin, $this->goVcURL, $this->timeout, $this->dataCenter);
        return $vm;
    }
}
