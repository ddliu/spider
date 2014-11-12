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
     *  - timeout
     *  ... or CURLOPT_XXX
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

    public function run($spider, $task) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $task['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->options['useragent'],
            CURLOPT_TIMEOUT => isset($this->options['timeout'])?$this->options['timeout']:0,
        ]);

        foreach ($this->options as $key => $value) {
            // assume it's a curl option
            if (is_int($key)) {
                curl_setopt($curl, $key, $value);
            }
        }

        if ($this->options['auto_referer'] && $task->parent) {
            $referer = $task->parent['url'];
            curl_setopt($curl, CURLOPT_REFERER, $referer);
        }

        $result = curl_exec($curl);

        $errno = curl_errno($curl);
        if ($errno) {
            $err = curl_error($curl);
            throw new \Exception('Request failed: #'.$errno.' '.$err);
        }

        $info = curl_getinfo($curl);
        if ($info['http_code'] != 200) {
            curl_close($curl);
            throw new \Exception('Request failed with status code: '.$info['http_code']);
        }

        curl_close($curl);

        $task['content'] = $result;
    }
}