<?php
/**
 * spider
 * @copyright 2014 Liu Dong <ddliuhb@gmail.com>
 * @license MIT
 */

namespace ddliu\spider\Pipe;

class IgnorePipe extends BasePipe {
    public function run($spider, $task) {
        $task->ignore();
    }
}