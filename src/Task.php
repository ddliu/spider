<?php
namespace ddliu\Spider;

class Task implements \ArrayAccess {

    const STATUS_PENDING = 0;
    const STATUS_WORKING = 1;
    const STATUS_PAUSE = 2;
    const STATUS_DONE = 3;
    const STATUS_CANCLED = 4;
    const STATUS_TERMINATED = 5;

    protected $status
    protected $data;
    protected $oldData;

    public function __construct($data) {
        $this->data = $data;
        $this->oldData = $data;
    }

    public function set($key, $data) {

    }

    public function get($key) {

    }

    public function start() {
        $this->status = self::
    }

    public function done() {
        $this->status = self::STATUS_DONE;
    }

    public function isEnded() {
        return $this->status === self::STATUS_DONE || $this->status === self::STATUS_CANCLED || $this->status === self::STATUS_TERMINATED;
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
}