<?php


namespace MisakaCloud\GoVC\Helper;


use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ProcessHelper
{
    public static function runAsync($cmd)
    {
        global $globalGoVcURL;
        global $globalGoVcDataCenter;
        global $globalProcessTimeout;
        $process = new Process($cmd, null, ['GOVC_URL' => $globalGoVcURL, 'GOVC_DATACENTER' => $globalGoVcDataCenter]);
        $process->enableOutput();
        $process->setTimeout($globalProcessTimeout);

        $process->run();
//        function ($type, $buffer) {
//            if (Process::ERR === $type) {
//                echo 'ERR > ' . $buffer;
//            } else {
//                echo 'OUT > ' . $buffer;
//            }
//        }


        while ($process->isRunning()) {
            echo "正在运行";
            $process->setTimeout(10);
        }
        // 失败处理
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
//            return $process->getOutput();
        }
        return $process;
    }
}
