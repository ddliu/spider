<?php
require(__DIR__.'/../vendor/autoload.php');

use ddliu\spider\Spider;
use ddliu\spider\Pipe\NormalizeUrlPipe;
use ddliu\spider\Pipe\RequestPipe;
use ddliu\spider\Pipe\RequeryPipe;

(new Spider())
    ->pipe(new NormalizeUrlPipe())
    ->pipe(new RequestPipe())
    ->pipe(new RequeryPipe())
    ->pipe(function($spider, $task) {
        if (!strpos($task['url'], 'tab=repositories')) return;
        $task['$requery']->mustFindAll('#<h3 class="repo-list-name">\s*<a href="(.*)">#Us')
            ->each(function($context) use ($task) {
                $url = $context->extract(1);
                $task->fork([
                    'url' => $url
                ]);
            });
    })
    ->pipe(function($spider, $task) {
        if (!preg_match('#^https://github.com/[\w_]+/[\w_]+$#', $task['url'])) return;
        $issueCount = $task['$requery']
            ->mustFind('#<li class="commits">.*</li>#Us')
            ->mustFind('#>\s*(\d)+\s*</span>#s')
            ->extract(1);
        $spider->logger->addInfo($task['url'].' has '.$issueCount.' commits');
    })
    ->addTask([
        'url' => 'https://github.com/ddliu?tab=repositories',
    ])
    ->run()
    ->report();