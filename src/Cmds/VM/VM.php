<?php

namespace MisakaCloud\GoVC\Cmds;

use MisakaCloud\GoVC\GoVC;
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

    public function clone($vmTemplate, $vmSnapshot, $vmDestination, $useLink, $useSnapshot, $host, $mac)
    {
        // 如果你不写快照 那么就禁止使用快照克隆
        if ($vmSnapshot == null) {
            $useSnapshot = false;
        }
        if ($useSnapshot == true & $useLink == true) {
            // 快照克隆模式 使用链接
            // govc vm.clone -host dstHost -net.address= MACAddr -vm template-vm -link -snapshot s-name new-vm
            $cmd = [$this->GovcBin, 'vm.clone', '-host=', $host, '-net.address=', $mac, '-vm', $vmTemplate, '-link', '-snapshot', $vmSnapshot, $vmDestination];

        } elseif ($useSnapshot == true & $useLink == false) {
            // 快照克隆模式 不使用链接
            // govc vm.clone -host dstHost -vm template-vm -snapshot s-name new-vm
            $cmd = [$this->GovcBin, 'vm.clone', '-host=', $host, '-net.address=', $mac, '-vm', $vmTemplate, '-snapshot', $vmSnapshot, $vmDestination];
        } elseif ($useSnapshot == false & $useLink == true) {
            // 普通克隆模式 使用链接
            // govc vm.clone -host dstHost -vm template-vm -link new-vm
            $cmd = [$this->GovcBin, 'vm.clone', '-host=', $host, '-net.address=', $mac, '-vm', $vmTemplate, '-link', $vmDestination];
        } elseif ($useSnapshot == false & $useLink == false) {
            // 普通克隆模式 不使用链接
            // govc vm.clone -host dstHost -vm template-vm new-vm
            $cmd = [$this->GovcBin, 'vm.clone', '-host=', $host, '-net.address=', $mac, '-vm', $vmTemplate, $vmDestination];
        }
        $process = new Process($cmd, null, ['ENV_VAR_NAME' => $this->GovcURL]);
        $process->run();

        // 失败处理
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $process->getOutput();
    }

    public function info($vm, $showExtra, $showResource, $showToolsConfigInfo)
    {
        $showExtraParameter = '-e=' . $showExtra;
        $showResourceParameter = '-r=' . $showResource;
        $showToolsConfigInfoParameter = '-t=' . $showToolsConfigInfo;
        // 查询需虚拟机信息
        // govc vm.info -e=false -g=true -r=false -t=false

        $cmd = [$this->GovcBin, 'vm.info', '-json', $showExtraParameter, $showResourceParameter, $showToolsConfigInfoParameter, $vm];

    }
}

