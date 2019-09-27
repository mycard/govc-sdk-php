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

    protected function runAsync($cmd)
    {
        $process = new Process($cmd, null, ['ENV_VAR_NAME' => $this->GovcURL]);
        $process->run();

        // 失败处理
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
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
        $this->runAsync($cmd);
    }

    public function info($vm, $showExtra, $showResource, $showToolsConfigInfo)
    {
        $showExtraParameter = '-e=' . $showExtra;
        $showResourceParameter = '-r=' . $showResource;
        $showToolsConfigInfoParameter = '-t=' . $showToolsConfigInfo;
        // 查询需虚拟机信息
        // govc vm.info -e=false -g=true -r=false -t=false

        $cmd = [$this->GovcBin, 'vm.info', '-json', $showExtraParameter, $showResourceParameter, $showToolsConfigInfoParameter, $vm];
        $this->runAsync($cmd);
    }

    public function change($annotation, $cpuHotAdd, $cpuLimit, $cpuPerformanceCounter, $cpuReservation, $cpus, $cpuShares, $guestOS, $memoryLimit, $memory, $memoryHotAdd, $memoryShare, $nestedHvEnabled, $syncTimeWithHost, $vm, array $extraConfig)
    {
        $cmd = [$this->GovcBin, 'vm.change', '-vm', $vm];
        // 虚拟机备注
        if ($annotation != null) {
            // -annotation string
            $annotationParameter = ['-annotation', $annotation];
            array_merge($cmd, $annotationParameter);
        }
        // CPU热添加
        if ($cpuHotAdd != null) {
            // -cpu-hot-add-enabled bool
            $cpuHotAddParameter = ['-cpu-hot-add-enabled', $cpuHotAdd];
            array_merge($cmd, $cpuHotAddParameter);
        }
        // CPU资源限制方面不填写 则设置为 -1 代表不受限制
        if ($cpuLimit != null) {
            // -cpu.limit int
            $cpuLimitParameter = ['-cpu.limit', $cpuLimit];
            array_merge($cmd, $cpuLimitParameter);
        }
        // CPU虚拟化计数器
        if ($cpuPerformanceCounter != null) {
            // -vpmc-enabled bool
            $cpuPerformanceCounterParameter = ['-vpmc-enabled', $cpuPerformanceCounter];
            array_merge($cmd, $cpuPerformanceCounterParameter);
        }
        // CPU保留 最少为 0
        if ($cpuReservation != null) {
            // -cpu.reservation int
            $cpuReservationParameter = ['-cpu.reservation', $cpuReservation];
            array_merge($cmd, $cpuReservationParameter);
        }
        // CPU核心数
        if ($cpus != null) {
            // -c int
            $cpusParameter = ['-c', $cpus];
            array_merge($cmd, $cpusParameter);
        }
        // CPU份额
        if ($cpuShares != null) {
            // -cpu.shares {normal,high,low}
            // -cpu.shares int
            $cpuSharesParameter = ['-cpu.shares', $cpuShares];
            array_merge($cmd, $cpuSharesParameter);
        }
    }
}

