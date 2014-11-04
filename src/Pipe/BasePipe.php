<?php
namespace ddliu\spider\Pipe;

abstract class BasePipe implements PipeInterface {
    public $spider;

    abstract public function pipe($task);
}