<?php
namespace ddliu\spider\Pipe;

class UniquePipe extends BasePipe {
    protected $keys = array();
    public function __construct($key = 'url') {
        $this->key = $key;
    }

    public function run($task) {
        $id = $task[$this->key];
        if (isset($this->keys[$id])) {
            $task->ignore();
        } else {
            $this->keys[$id] = true;
        }
    }
}