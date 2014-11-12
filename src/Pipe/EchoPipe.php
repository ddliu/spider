<?php
/**
 * spider
 * @copyright 2014 Liu Dong <ddliuhb@gmail.com>
 * @license MIT
 */

namespace ddliu\spider\Pipe;

/**
 * Echo message, for testing.
 */
class EchoPipe extends BasePipe {
    protected $message;
    public function __construct($message) {
        $this->message = $message;
    }

    public function run($spider, $task) {
        echo $this->message;
    }
}