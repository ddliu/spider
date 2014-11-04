<?php
use ddliu\Spider\Spider;
use ddliu\Spider\Parser\ParseHelper;

$spider = new Spider(array(
    'useragent' => '',
    'proxy' => '',
    'autorefer' => true,
));

$spider
    ->pipe()
    ->pipe()


function indexPage($spider, $input) {
    $input['uri'];
    $input['content'];
    parseAll()
    each(function() {
        $spider->addTask($current, $input);
    });
}

function detailPage($spider, $input) {

}

$spider->addTask(array(
    'url' => 'http://example.com',
    'processor' => 'indexPage',
    'input' => array(
        'url' => 'xxx',
    ),
))->run();

processor vs pipe



$task => pipe => processor

task:
    id
    input:
        uri: xxx
        _plugin: {},
        _another_plugin: {},
