<?php
namespace ddliu\spider\Pipe;
use ddliu\spider\Task;

interface PipeInterface {
    public function pipe(Task $task);
}