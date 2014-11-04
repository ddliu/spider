<?php
namespace ddliu\spider\Pipe;
use ddliu\spider\Pipe;

class EchoPipe extends Pipe {
    public function pipe($task) {
        echo 'task comming...';
    }
}