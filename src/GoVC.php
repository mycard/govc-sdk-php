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


    /**
     * GoVC constructor.
     * @param $goVcBin
     * @param $timeout
     */
    public function __construct()
    {
    }


    /**
     * @return VM
     */
    public function vm()
    {
        $vm = new VM();
        return $vm;
    }
}
