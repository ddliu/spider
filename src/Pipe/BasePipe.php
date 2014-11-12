<?php
/**
 * spider
 * @copyright 2014 Liu Dong <ddliuhb@gmail.com>
 * @license MIT
 */

namespace ddliu\spider\Pipe;

/**
 * The base pipe
 */
abstract class BasePipe implements PipeInterface {
    protected $pipes = array();

    abstract public function run($spider, $task);

    public function pipe($pipe) {
        $pipe = self::makePipe($pipe);

        return new CombinedPipe($this, $pipe);
    }

    public static function makePipe($pipe) {
        if ($pipe instanceof PipeInterface) {
            return $pipe;
        } elseif (is_callable($pipe)) {
            $pipe = new FunctionPipe($pipe);
            return $pipe;
        } else {
            throw new PipeException('Invalid pipe');
        }
    }
}