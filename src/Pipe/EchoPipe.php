<?php
namespace ddliu\spider\Pipe;

/**
 * Echo message, for testing.
 */
class EchoPipe extends BasePipe {
    protected $message;
    public function __construct($message) {
        $this->message = $message;
    }

    public function run($task) {
        echo $this->message;
    }
}