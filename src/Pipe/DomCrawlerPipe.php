<?php
namespace ddliu\spider\Pipe;
use Symfony\Component\DomCrawler\Crawler;

class DomCrawlerPipe extends BasePipe {
    public function run($spider, $task)
    {
        $task['$dom'] = new Crawler($task['content'], $task['url']);
    }
}