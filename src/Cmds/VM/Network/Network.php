<?php

namespace MisakaCloud\GoVC\Cmds\VM\Network;

use MisakaCloud\GoVC\Helper\ProcessHelper;

/**
 * Class Network
 * @package MisakaCloud\GoVC\Cmds\VM\Network
 */
class Network
{
    /**
     * @var
     */
    private $goVcBin;


    /**
     * Network constructor.
     */
    public function __construct()
    {
        global $globalGoVcBin;
        $this->goVcBin = $globalGoVcBin;
    }


    /**
     * @param $vm
     * @param $portGroup
     * @param $networkAdapter
     * @param $macAddr
     * @param $device
     */
    public function change($vm, $portGroup, $networkAdapter, $macAddr, $device)
    {
        // Note that '-net' is currently required with '-net.address', even when not changing the VM network.
        // 注意 即使不修改虚拟机网络 '-net' 和 '-net.address' 也必须一起使用
        // vm.network.change -vm string -net string -net.adapter string -net.address string string
        $cmd = [$this->goVcBin, 'vm.network.change', '-vm', $vm, '-net', $portGroup, '-net.adapter', $networkAdapter, '-net.address', $macAddr, $device];
        ProcessHelper::runAsync($cmd);
    }

    /**
     * @param $vm
     * @param $portGroup
     * @param $networkAdapter
     * @param $macAddr
     */
    public function add($vm, $portGroup, $networkAdapter, $macAddr)
    {
        // vm.network.add -vm string -net string -net.adapter string -net.address string
        $cmd = [$this->goVcBin, 'vm.network.change', '-vm', $vm, '-net', $portGroup, '-net.adapter', $networkAdapter, '-net.address', $macAddr];
        ProcessHelper::runAsync($cmd);
    }
}
