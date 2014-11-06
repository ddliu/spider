<?php
namespace ddliu\spider\Pipe;

class IgnorePipe extends BasePipe {
    public function run($task) {
        $task->ignore();
    }
}