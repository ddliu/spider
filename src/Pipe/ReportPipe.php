<?php
namespace ddliu\spider\Pipe;

/**
 * Report loop
 */
class ReportPipe extends BasePipe {

    /**
     * Options:
     *  - count
     *  - seconds
     * @var array
     */
    protected $options;

    public function __construct($options = array()) {
        $this->options = $options;
    }

    public function run($spider, $task) {
        static $count = 0;
        static $time = 0;
        if (isset($this->options['count'])) {
            $count++;
            if ($count >= $this->options['count']) {
                $spider->report();
                $count = 0;
            }
        } elseif (isset($this->options['seconds'])) {
            $now = time();
            if ($time === 0) {
                $time = $now;
            }

            if ($now - $time >= $this->options['seconds']) {
                $spider->report();
                $time = $now;
            }
        }
    }
}