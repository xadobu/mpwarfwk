<?php

namespace Mpwarfwk\Component\Database;


use Mpwarfwk\Component\Profiler\Profiler;

class DBProfiler implements Profiler
{
    private $time_start;
    private $time_end;

    public function startTime()
    {
        $this->time_start = microtime(true);
    }

    public function endTime()
    {
        $this->time_end = microtime(true);
    }

    public function displayInformation()
    {
        $execution_time = ($this->time_end - $this->time_start);
        return '<b>Database Total Execution Time:</b> ' . $execution_time . ' seconds</br>';
    }
}