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
//        print_r($process->getEnv());
//        print_r($process->getCommandLine());
        $process->run();

        while ($process->isRunning()) {
            echo "正在运行";
            echo $process->getOutput();
        }
        // 失败处理
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process->getOutput());
//            return $process->getOutput();
        }
        return $process->getOutput();
    }
}
