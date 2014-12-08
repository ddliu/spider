<?php
/**
 * spider
 * @copyright 2014 Liu Dong <ddliuhb@gmail.com>
 * @license MIT
 */

namespace ddliu\spider\Pipe;

/**
 * Retry pipe
 */
class RetryPipe extends BasePipe {
    protected $pipe;
    protected $options;

    /**
     * Constructor
     * @param ddliu\\spider\\Pipe $pipe Pipe to be wrapped
     * @param array $options
     *  - count: retry count
     */
    public function __construct($pipe, $options) {
        $this->pipe = self::makePipe($pipe);
        if (!isset($options['count'])) {
            throw new PipeException('invalid option');
        }
        $this->options = $options;
    }

    public function run($spider, $task) {
        $count = $this->options['count'];
        $retries = 0;
        while ($count > 0) {
            $success = false;
            try {
                $this->pipe->run($spider, $task);
                $success = true;
            } catch (\Exception $e) {
                $spider->logger->addError($e);
            }

            if ($success) {
                return;
            }

            $count--;
            $retries++;
        }

        throw new PipeException('Failed after tried '.$retries.' times');
    }
}