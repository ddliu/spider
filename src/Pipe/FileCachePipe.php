<?php
namespace ddliu\spider\Pipe;
use ddliu\filecache\FileCache;

class FileCachePipe extends BasePipe {
    
    protected $pipe;

    protected $input;
    protected $output;

    protected $cache;


    /**
     * Constructor
     * @param mixed $pipe Pipe to cache
     * @param array $options
     *  - input: keys to use as input
     *  - output: output to store
     *  - ... FileCache options
     */
    public function __construct($pipe, $options) {
        $this->pipe = self::makePipe($pipe);

        $this->input = $options['input'];
        unset($options['input']);
        $this->output = $options['output'];
        unset($options['output']);
        if (!is_string($this->output)) {
            throw new PipeException('invalid output');
        }

        $this->cache = new FileCache($options);
    }

    public function run($spider, $task) {
        static $isCallable;
        if (null === $isCallable) {
            $isCallable = is_callable($this->input);
        }

        $key = null;
        if (!is_string($key) && $isCallable) {
            $key = call_user_func($this->input, $task);
        } elseif (is_string($this->input) && isset($task[$this->input])) {
            $key = $task[$this->input];
        }

        if (!is_string($key)) {
            throw new PipeException('Get cache key failed');
        }

        $result = $this->cache->get($key);
        if (!$result) {
            $this->pipe->run($spider, $task);
            if (isset($task[$this->output])) {
                $result = $task[$this->output];
                $this->cache->set($key, $result);
            }
        } else {
            $spider->logger->addDebug('cache hit: '.$key);
            $task[$this->output] = $result;
        }
    }
}