<?php


namespace MisakaCloud\GoVc\Helper;


use Symfony\Component\Process\Process;

class ProcessHelper
{
    public static function runAsync($cmd,$goVcURL)
    {
        $process = new Process($cmd, null, ['GOVC_URL' => $goVcURL]);
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
