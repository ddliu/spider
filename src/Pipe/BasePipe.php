<?php
namespace ddliu\spider\Pipe;
use ddliu\spider\Task;

abstract class Pipe implements PipeInterface {
    protected $spider;
    public function __construct($spider) {
        $this->spider = $spider;
    }

    abstract public function pipe(Task $task);
}