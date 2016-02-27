<?php

namespace Mpwarfwk\Component\Profiler;


class ProfilingAggregator implements Profiler
{
    private $profilers;

    public function __construct(array $profilers)
    {
        $this->profilers = $profilers;
    }

    public function displayInformation()
    {
        $info = "<div class='profiling'>";
        foreach ($this->profilers as $profiler) {
            $info .= $profiler->displayInformation();
        }
        $info .= "</div>";
    }
}