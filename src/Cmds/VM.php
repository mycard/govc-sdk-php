<?php

namespace MisakaCloud\GoVC\Cmds;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class  VM
{
    private $GovcBin;
    private $GovcURL;
    private $timeout;

    /**
     * GoVC constructor.
     * @param $GovcBin
     * @param $GovcURL
     * @param $timeout
     */
    public function __construct($GovcBin, $GovcURL, $timeout)
    {
        $this->GovcBin = $GovcBin;
        $this->GovcURL = $GovcURL;
        $this->timeout = $timeout;
    }

    public function clone($vmTemplate, $vmSnapshot, $vmDestination, $isLink, $isSnapshot, $host)
    {
        // 如果你不写快照 那么就禁止使用快照克隆
        if ($vmSnapshot == null) {
            $isSnapshot = false;
        }
        if ($isSnapshot == true & $isLink == true) {
            // 快照克隆模式 使用链接
            // govc vm.clone -host dstHost -vm template-vm -link -snapshot s-name new-vm
            $cmd = [$this->GovcBin, 'vm.clone', '-host', $host, '-vm', $vmTemplate, '-link', '-snapshot', $vmSnapshot, $vmDestination];

        } elseif ($isSnapshot == true & $isLink == false) {
            // 快照克隆模式 不使用链接
            // govc vm.clone -host dstHost -vm template-vm -snapshot s-name new-vm
            $cmd = [$this->GovcBin, 'vm.clone', '-host', $host, '-vm', $vmTemplate, '-snapshot', $vmSnapshot, $vmDestination];
        } elseif ($isSnapshot == false & $isLink == true) {
            // 普通克隆模式 使用链接
            // govc vm.clone -host dstHost -vm template-vm -link new-vm
            $cmd = [$this->GovcBin, 'vm.clone', '-host', $host, '-vm', $vmTemplate, '-link', $vmDestination];
        } elseif ($isSnapshot == false & $isLink == false) {
            // 普通克隆模式 不使用链接
            // govc vm.clone -host dstHost -vm template-vm new-vm
            $cmd = [$this->GovcBin, 'vm.clone', '-host', $host, '-vm', $vmTemplate, $vmDestination];
        }

    }
}

