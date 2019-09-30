<?php

namespace MisakaCloud\GoVC\Cmds\VM\Network;

use MisakaCloud\GoVc\Helper\ProcessHelper;

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
     * @var
     */
    private $goVcURL;
    /**
     * @var
     */
    private $timeout;
    /**
     * @var
     */
    private $dataCenter;

    /**
     * Network constructor.
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
        ProcessHelper::runAsync($cmd, $this->goVcURL);
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
        ProcessHelper::runAsync($cmd, $this->goVcURL);
    }
}
