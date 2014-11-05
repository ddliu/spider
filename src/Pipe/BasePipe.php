<?php
namespace ddliu\spider\Pipe;

abstract class BasePipe implements PipeInterface {
    public $spider;
    protected $pipes = array();

    abstract public function run($task);

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