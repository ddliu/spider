<?php
/**
 * spider
 * @copyright 2014 Liu Dong <ddliuhb@gmail.com>
 * @license MIT
 */

namespace ddliu\spider\Pipe;

interface PipeInterface {
    public function pipe($pipe);
    public function run($spider, $task);
}