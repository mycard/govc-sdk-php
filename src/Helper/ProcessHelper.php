<?php


namespace MisakaCloud\GoVC\Helper;


use Symfony\Component\Process\Process;

class ProcessHelper
{
    public static function runAsync($cmd)
    {
        global $globalGoVcURL;
        global $globalGoVcDataCenter;
        global $globalProcessTimeout;
        $process = new Process($cmd, null, ['GOVC_URL' => $globalGoVcURL, 'GOVC_DATACENTER' => $globalGoVcDataCenter]);
        $process->setTimeout($globalProcessTimeout);
        $process->run();

        while ($process->isRunning()) {
            echo "正在执行任务";
            sleep(4);
        }
        // 失败处理
        if (!$process->isSuccessful()) {
//            throw new ProcessFailedException($process);
            return $process->getErrorOutput();
        }

        return $process->getOutput();
    }
}
