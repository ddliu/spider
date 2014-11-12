<?php
/**
 * spider
 * @copyright 2014 Liu Dong <ddliuhb@gmail.com>
 * @license MIT
 */

namespace ddliu\spider\Pipe;
use ddliu\requery\Context;

/**
 * Create a requery context from the task content.
 */
class RequeryPipe extends BasePipe {
    public function run($spider, $task) {
        $task['$requery'] = new Context($task['content']);
    }
}