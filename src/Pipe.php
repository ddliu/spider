<?php
namespace ddliu\spider;

abstract class Pipe {
    protected $spider;
    public function __construct($spider) {
        $this->spider = $spider;
    }

    abstract public function pipe(Task $task) {
    }
}