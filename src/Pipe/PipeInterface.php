<?php
namespace ddliu\spider\Pipe;

interface PipeInterface {
    public function pipe($pipe);
    public function run($task);
}