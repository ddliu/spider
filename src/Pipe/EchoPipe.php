<?php
namespace ddliu\spider\Pipe;

class EchoPipe extends BasePipe {
    protected $message;
    public function __construct($message) {
        $this->message = $message;
    }

    public function pipe($task) {
        echo $this->message;
    }
}