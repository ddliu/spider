<?php
namespace ddliu\spider;
use ddliu\spider\Pipe\PipeInterface;
use ddliu\spider\Pipe\FunctionPipe;
use ddliu\spider\Pipe\CombinedPipe;
use Monolog\Logger;


class Spider {
    protected $pipes = array();
    protected $tasks = array();
    protected $counter = array();
    protected $limitCount = 0;
    protected $startTime;
    protected $stopped = false;
    public $logger;

    /**
     * Optins
     *  - limit: Maxmum tasks to run
     *  - depth: Task fork depth
     *  - timeout: Maxmum time to run
     * @var array
     */
    protected $options = array();

    public function __construct($options = array()) {
        $this->startTime = microtime(true);
        $this->pipe = new CombinedPipe();
        $this->options = $options;
        $this->logger = new Logger(isset($options['name'])?$options['name']:'ddliu.spider');
    }

    public function setLogger($logger) {
        $this->logger = $logger;
        return $this;
    }

    public function addTask($data) {
        if (!$data instanceof Task) {
            $task = new Task($data);
        } else {
            $task = $data;
        }

        $task->spider = $this;
        $this->tasks[] = $task;

        return $this;
    }

    public function run() {
        $this->logger->addInfo('spider started');
        // TODO: scheduller
        while(!$this->stopped && $task = array_shift($this->tasks)) {
            $this->process($task);
        }

        return $this;
    }

    public function stop($message = null) {
        if ($message) {
            echo $message."\n";
        }
        $this->stopped = true;
    }

    public function pipe($pipe) {
        $this->pipe->pipe($pipe);
        return $this;
    }

    protected function process($task) {
        // check for limit
        if (!empty($this->options['limit']) && $this->limitCount >= $this->options['limit']) {
            $this->logger->addWarning('Stopped after processing '.$this->options['limit'].' tasks');
            $this->stop();
            return;
        }

        $task->start();
        try {
            $this->pipe->run($this, $task);
        } catch (\Exception $e) {
            $task->fail($e);
        }
        if ($task->getStatus() === Task::STATUS_WORKING) {
            $task->done();
        }

        $status = $task->getStatus();

        // limit counter
        if ($status !== Task::STATUS_IGNORED) {
            $this->limitCount++;
        }

        if (!isset($this->counter[$status])) {
            $this->counter[$status] = 1;
        } else {
            $this->counter[$status]++;
        }
    }


    public function report() {
        $counter = $this->counter;
        $counter[Task::STATUS_PENDING] = isset($counter[Task::STATUS_PENDING])?$counter[Task::STATUS_PENDING]:0 + count($this->tasks);
        static $names = [
            Task::STATUS_PENDING => 'Pending',
            Task::STATUS_WORKING => 'Working',
            Task::STATUS_PAUSE => 'Paused',
            Task::STATUS_DONE => 'Done',
            Task::STATUS_RETRY => 'Retry',
            Task::STATUS_FAILED => 'Failed',
            Task::STATUS_IGNORED => 'Ignored',
        ];

        $line = str_repeat('=', 25).PHP_EOL;
        echo $line;
        printf("Spider running for %ds\n", microtime(true) - $this->startTime);
        echo $line;
        foreach ($counter as $status => $count) {
            echo $names[$status].': '.$count.PHP_EOL;
        }
        echo $line;

        return $this;
    }
}