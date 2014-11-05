<?php
namespace ddliu\spider\Pipe;

/**
 * The request pipe.
 * TODO: use guzzle as the request engine.
 */
class RequestPipe extends BasePipe {
    /**
     * Constructor
     * @param array $options
     *  - cookie:
     *  - auto_referer: 
     *  - referer
     *  - useragent
     */
    public function __construct($options = array()) {
        $this->options = array_merge($this->getDefaultOptions(), $options);
    }

    protected function getDefaultOptions() {
        return array(
            'useragent' => 'ddliu/spider',
            'auto_referer' => true,
        );
    }

    public function pipe($task) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $task['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->options['useragent'],
        ]);

        if ($this->options['auto_referer'] && $task->parent) {
            $referer = $task->parent['url'];
            curl_setopt($curl, CURLOPT_REFERER, $referer);
        }

        $result = curl_exec($curl);

        // TODO: validation & stats

        $task['content'] = $result;
    }
}