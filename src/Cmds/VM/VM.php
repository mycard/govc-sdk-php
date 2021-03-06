<?php

namespace MisakaCloud\GoVC\Cmds\VM;

use MisakaCloud\GoVC\Cmds\VM\Network\Network;
use MisakaCloud\GoVC\Helper\ProcessHelper;
use MisakaCloud\GoVC\Helper\TypeHelper;

/**
 * Class VM
 * @package MisakaCloud\GoVC\Cmds\VM
 */
class VM
{
    protected $goVcBin;

    /**
     * VM constructor.
     */
    public function __construct()
    {
        global $globalGoVcBin;
        $this->goVcBin = $globalGoVcBin;
    }


    /**
     * @return Network
     */
    public function network()
    {
        $vmNetwork = new Network();
        return $vmNetwork;
    }

    /**
     * @param $vmTemplate
     * @param $vmSnapshot
     * @param $vmDestination
     * @param $useLink
     * @param $useSnapshot
     * @param $host
     * @param $mac
     * @param $dataStore
     * @param $powerOn
     */
    public function clone($vmTemplate, $vmSnapshot, $vmDestination, $useLink, $useSnapshot, $host, $mac, $dataStore, $powerOn)
    {
        // 基础命令
        // govc vm.clone -host dstHost -net.address= MACAddr -vm template-vm
        $vmPowerParameter = '-on=' . TypeHelper::boolToString($powerOn);
        $cmd = [$this->goVcBin, 'vm.clone', '-vm', $vmTemplate, '-host', $host, '-net.address=' . $mac, '-ds', $dataStore, $vmPowerParameter, '-dump=true'];
        $modeParameter = [];
        // 如果你不写快照 那么就禁止使用快照克隆
        if ($vmSnapshot == null) {
            $useSnapshot = false;
        }
        if ($useSnapshot == true & $useLink == true) {
            // 快照克隆模式 使用链接
            // -link -snapshot s-name
            $modeParameter = ['-link=' . TypeHelper::boolToString($useLink), '-snapshot', $vmSnapshot];
        } elseif ($useSnapshot == true & $useLink == false) {
            // 快照克隆模式 不使用链接
            // -snapshot s-name
            $modeParameter = ['-snapshot', $vmSnapshot];
        } elseif ($useSnapshot == false & $useLink == true) {
            // 普通克隆模式 使用链接
            // -link
            $modeParameter = ['-link=' . $useLink];
        } elseif ($useSnapshot == false & $useLink == false) {
            // 普通克隆模式 不使用链接
            //
            $modeParameter = [];
        }
        // 连接命令行 最后加入目标虚拟机名字
        // new-vm
        $cmd = array_merge($cmd, $modeParameter, [$vmDestination]);
        return ProcessHelper::runAsync($cmd);
    }

    /**
     * @param $vm
     * @param $showExtra
     * @param $showResource
     * @param $showToolsConfigInfo
     */
    public function info($vm, $showExtra, $showResource, $showToolsConfigInfo)
    {
        $showExtraParameter = '-e=' . TypeHelper::boolToString($showExtra);
        $showResourceParameter = '-r=' . TypeHelper::boolToString($showResource);
        $showToolsConfigInfoParameter = '-t=' . TypeHelper::boolToString($showToolsConfigInfo);
        // 查询需虚拟机信息
        // govc vm.info -e=false -g=true -r=false -t=false

        $cmd = [$this->goVcBin, 'vm.info', '-json', $showExtraParameter, $showResourceParameter, $showToolsConfigInfoParameter, $vm];
        return ProcessHelper::runAsync($cmd);
    }

    /**
     * @param $annotation
     * @param $cpuHotAdd
     * @param $cpuLimit
     * @param $cpuPerformanceCounter
     * @param $cpuReservation
     * @param $cpus
     * @param $cpuShares
     * @param $guestOS
     * @param $memoryLimit
     * @param $memoryReservation
     * @param $memory
     * @param $memoryHotAdd
     * @param $memoryShare
     * @param $nestedHvEnabled
     * @param $syncTimeWithHost
     * @param $vm
     * @param array $extraConfig
     */
    public function change($vm, $annotation, $cpuHotAdd, $cpuLimit, $cpuPerformanceCounter, $cpuReservation, $cpus, $cpuShares, $guestOS, $memoryLimit, $memoryReservation, $memory, $memoryHotAdd, $memoryShare, $nestedHvEnabled, $syncTimeWithHost, array $extraConfig)
    {
        $cmd = [$this->goVcBin, 'vm.change', '-vm', $vm];

        // 虚拟机备注
        if (!empty($annotation)) {
            // -annotation string
            $annotationParameter = ['-annotation', $annotation];
            $cmd = array_merge($cmd, $annotationParameter);
        }

        // CPU热添加
        if (!empty($cpuHotAdd)) {
            // -cpu-hot-add-enabled bool
            $cpuHotAddParameter = ['-cpu-hot-add-enabled', $cpuHotAdd];
            $cmd = array_merge($cmd, $cpuHotAddParameter);
        }

        // CPU限制方面不填写 则设置为 -1 代表不受限制
        if (!empty($cpuLimit)) {
            // -cpu.limit int
            $cpuLimitParameter = ['-cpu.limit', $cpuLimit];
            $cmd = array_merge($cmd, $cpuLimitParameter);
        }

        // CPU虚拟化计数器
        if (!empty($cpuPerformanceCounter)) {
            // -vpmc-enabled bool
            $cpuPerformanceCounterParameter = ['-vpmc-enabled', $cpuPerformanceCounter];
            $cmd = array_merge($cmd, $cpuPerformanceCounterParameter);
        }

        // CPU保留 最少为 0
        if (!empty($cpuReservation)) {
            // -cpu.reservation int
            $cpuReservationParameter = ['-cpu.reservation', $cpuReservation];
            $cmd = array_merge($cmd, $cpuReservationParameter);
        }

        // CPU核心数
        if (!empty($cpus)) {
            // -c int
            $cpusParameter = ['-c', $cpus];
            $cmd = array_merge($cmd, $cpusParameter);
        }

        // CPU份额
        if (!empty($cpuShares)) {
            // -cpu.shares {normal,high,low}
            // -cpu.shares int
            $cpuSharesParameter = ['-cpu.shares', $cpuShares];
            $cmd = array_merge($cmd, $cpuSharesParameter);
        }

        // GuestOS 客户机系统类型
        if (!empty($guestOS)) {
            // -g string
            $guestOSParameter = ['-g', $guestOS];
            $cmd = array_merge($cmd, $guestOSParameter);
        }

        // 内存限制 同CPU
        if (!empty($memoryLimit)) {
            // -mem.limit int
            $memoryLimitParameter = ['-mem.limit', $memoryLimit];
            $cmd = array_merge($cmd, $memoryLimitParameter);
        }

        // 内存保留 同CPU
        if (!empty($memoryReservation)) {
            // -mem.reservation int
            $memoryReservationParameter = ['-mem.limit', $memoryReservation];
            $cmd = array_merge($cmd, $memoryReservationParameter);
        }

        // 内存大小 单位是MB
        if (!empty($memory)) {
            // -m int
            $memoryParameter = ['-m', $memory];
            $cmd = array_merge($cmd, $memoryParameter);
        }

        // 内存热添加
        if (!empty($memoryHotAdd)) {
            //  -memory-hot-add-enabled bool
            $memoryHotAddParameter = ['-memory-hot-add-enabled', $memoryHotAdd];
            $cmd = array_merge($cmd, $memoryHotAddParameter);
        }

        // 内存份额 同CPU
        if (!empty($memoryShare)) {
            // -mem.shares {normal,high,low}
            // -mem.shares int
            $memoryShareParameter = ['-mem.shares', $memoryShare];
            $cmd = array_merge($cmd, $memoryShareParameter);
        }

        // 嵌套虚拟化
        if (!empty($nestedHvEnabled)) {
            // -nested-hv-enabled bool
            $nestedHvEnabledParameter = ['-nested-hv-enabled', $nestedHvEnabled];
            $cmd = array_merge($cmd, $nestedHvEnabledParameter);
        }

        // 同步系统时间
        if (!empty($syncTimeWithHost)) {
            // -sync-time-with-host bool
            $syncTimeWithHostParameter = ['-sync-time-with-host', $syncTimeWithHost];
            $cmd = array_merge($cmd, $syncTimeWithHostParameter);
        }

        // 最后就是运行了哦
        return ProcessHelper::runAsync($cmd);
    }

    /**
     * @param $vm
     * @param $powerActionType
     * @param $force
     * @param $waitForComplete
     */
    public function power($vm, $powerActionType, $force, $waitForComplete)
    {
        // vm.power
        $cmd = [$this->goVcBin, 'vm.power'];
        $powerActionTypeParameter = [];
        switch ($powerActionType) {
            // 强制断电
            case "powerOff":
                $powerActionTypeParameter = ['-off'];
                break;
            // 开机
            case "powerOn":
                $powerActionTypeParameter = ['-on'];
                break;
            // 一般性重启 (装了Tools之后的软重启)
            case "rebootGuest":
                $powerActionTypeParameter = ['-r'];
                break;
            // 重置
            case "powerResetGuest":
                $powerActionTypeParameter = ['-reset'];
                break;
            // 软关机
            case "shutdownGuest":
                $powerActionTypeParameter = ['-s'];
                break;
            // 挂起
            case "suspendGuest":
                $powerActionTypeParameter = ['-suspend'];
                break;
        }

        $cmd = array_merge($cmd, $powerActionTypeParameter);

        if (!empty($waitForComplete)) {
            // -wait bool
            $waitForCompleteParameter = ['-wait', TypeHelper::boolToString($waitForComplete)];
            $cmd = array_merge($cmd, $waitForCompleteParameter);
        }

        if (!empty($force)) {
            // -force bool
            $forceParameter = ['-force=', TypeHelper::boolToString($force)];
            $cmd = array_merge($cmd, $forceParameter);
        }

        $cmd = array_merge($cmd, [$vm]);
        return ProcessHelper::runAsync($cmd);
    }

    /**
     * @param $vm
     */
    public function destroy($vm)
    {
        // vm.destroy
        $cmd = [$this->goVcBin, 'vm.destroy', $vm];
        return ProcessHelper::runAsync($cmd);
    }
}
