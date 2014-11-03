<?php
use ddliu\Spider\Spider;
use ddliu\Spider\Parser\ParseHelper;

$spider = new Spider(array(
    'useragent' => '',
    'proxy' => '',
));

$spider->addTask(array(
    'id' => 1,
    'input' => array(),
    'url' => '',
    'processor' => function($spider, $input) {
        $url = $input['url'];
        $content = $spider->httpclient->get($url);
        $content = ParseHelper::mustExtract($content, '<div></div>');
        ParseHelper::mustExtractAll($content, '<li></li>');

        ParseHelper::mustExtractChain($content, '<div></div>', )

        $q = new RegexpQuery($content);

        $q->query('<div>(?P<content>.*)</div>')['content']->then(function($context))->then()
    }
))