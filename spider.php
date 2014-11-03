<?php
$container->logger;
$container->httpclient;

function page1($container, $input, $url) {

}

function page2($container, $input, $url) {
    $taskInfo['id'] = '';
    enqueue($taskInfo);
}

function run() {
    $url = 'http://example.com';
    $container = '';

}

// sched
// request
