<?php
namespace ddliu\spider;

class Task implements \ArrayAccess {

    const STATUS_PENDING = 0;
    const STATUS_WORKING = 1;
    const STATUS_PAUSE = 2;
    const STATUS_DONE = 3;
    const STATUS_RETRY = -2;
    const STATUS_FAILED = -3;
    const STATUS_IGNORED = -1;

    protected $status = self::STATUS_PENDING;
    protected $data;
    public $parent;
    public $spider;

    public function __construct($data) {
        $this->data = $data;
    }

    public function start() {
        $this->status = self::STATUS_WORKING;
    }

    public function done() {
        $this->status = self::STATUS_DONE;
    }

    public function ignore() {
        $this->status = self::STATUS_IGNORED;
    }

    public function isEnded() {
        return $this->status === self::STATUS_DONE || 
               $this->status === self::STATUS_FAILED || 
               $this->status === self::STATUS_IGNORED;
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function fork($data) {
        $task = new Task($data);
        $task->parent = $this;
        $this->spider->addTask($task);
    }
}