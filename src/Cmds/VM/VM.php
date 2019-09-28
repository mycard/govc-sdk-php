<?php

namespace MisakaCloud\GoVC\Cmds;

use MisakaCloud\GoVC\GoVC;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class VM
{
    private $goVcBin;
    private $goVcURL;
    private $timeout;
    private $dataCenter;

    /**
     * VM constructor.
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


    protected function runAsync($cmd)
    {
        $process = new Process($cmd, null, ['GOVC_URL' => $this->goVcURL]);
        $process->run();

        // 失败处理
        if (!$process->isSuccessful()) {
//            throw new ProcessFailedException($process);
            return $process->getErrorOutput();
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
            $cmd = [$this->goVcBin, 'vm.clone', '-host=', $host, '-net.address=', $mac, '-vm', $vmTemplate, '-link', '-snapshot', $vmSnapshot, $vmDestination];
        } elseif ($useSnapshot == true & $useLink == false) {
            // 快照克隆模式 不使用链接
            // govc vm.clone -host dstHost -vm template-vm -snapshot s-name new-vm
            $cmd = [$this->goVcBin, 'vm.clone', '-host=', $host, '-net.address=', $mac, '-vm', $vmTemplate, '-snapshot', $vmSnapshot, $vmDestination];
        } elseif ($useSnapshot == false & $useLink == true) {
            // 普通克隆模式 使用链接
            // govc vm.clone -host dstHost -vm template-vm -link new-vm
            $cmd = [$this->goVcBin, 'vm.clone', '-host=', $host, '-net.address=', $mac, '-vm', $vmTemplate, '-link', $vmDestination];
        } elseif ($useSnapshot == false & $useLink == false) {
            // 普通克隆模式 不使用链接
            // govc vm.clone -host dstHost -vm template-vm new-vm
            $cmd = [$this->goVcBin, 'vm.clone', '-host=', $host, '-net.address=', $mac, '-vm', $vmTemplate, $vmDestination];
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

        $cmd = [$this->goVcBin, 'vm.info', '-json', $showExtraParameter, $showResourceParameter, $showToolsConfigInfoParameter, $vm];
        $this->runAsync($cmd);
    }

    public function change($annotation, $cpuHotAdd, $cpuLimit, $cpuPerformanceCounter, $cpuReservation, $cpus, $cpuShares, $guestOS, $memoryLimit, $memoryReservation, $memory, $memoryHotAdd, $memoryShare, $nestedHvEnabled, $syncTimeWithHost, $vm, array $extraConfig)
    {
        $cmd = [$this->goVcBin, 'vm.change', '-vm', $vm];

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

        // CPU限制方面不填写 则设置为 -1 代表不受限制
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

        // GuestOS 客户机系统类型
        if ($guestOS != null) {
            // -g string
            $guestOSParameter = ['-g', $guestOS];
            array_merge($cmd, $guestOSParameter);
        }

        // 内存限制 同CPU
        if ($memoryLimit != null) {
            // -mem.limit int
            $memoryLimitParameter = ['-mem.limit', $memoryLimit];
            array_merge($cmd, $memoryLimitParameter);
        }

        // 内存保留 同CPU
        if ($memoryReservation != null) {
            // -mem.reservation int
            $memoryReservationParameter = ['-mem.limit', $memoryReservation];
            array_merge($cmd, $memoryReservationParameter);
        }

        // 内存大小 单位是MB
        if ($memory != null) {
            // -m int
            $memoryParameter = ['-m', $memory];
            array_merge($cmd, $memoryParameter);
        }

        // 内存热添加
        if ($memoryHotAdd != null) {
            //  -memory-hot-add-enabled bool
            $memoryHotAddParameter = ['-memory-hot-add-enabled', $memoryHotAdd];
            array_merge($cmd, $memoryHotAddParameter);
        }

        // 内存份额 同CPU
        if ($memoryShare != null) {
            // -mem.shares {normal,high,low}
            // -mem.shares int
            $memoryShareParameter = ['-mem.shares', $memoryShare];
            array_merge($cmd, $memoryShareParameter);
        }

        // 嵌套虚拟化
        if ($nestedHvEnabled != null) {
            // -nested-hv-enabled bool
            $nestedHvEnabledParameter = ['-nested-hv-enabled', $nestedHvEnabled];
            array_merge($cmd, $nestedHvEnabledParameter);
        }

        // 同步系统时间
        if ($syncTimeWithHost != null) {
            // -sync-time-with-host bool
            $syncTimeWithHostParameter = ['-sync-time-with-host', $syncTimeWithHost];
            array_merge($cmd, $syncTimeWithHostParameter);
        }

        // 最后就是运行了哦
        $this->runAsync($cmd);
    }
}
