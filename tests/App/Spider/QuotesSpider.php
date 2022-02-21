<?php

namespace Nelexa\RoachPhpBundle\Tests\App\Spider;

use Generator;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @see https://docs.scrapy.org/en/latest/intro/tutorial.html#a-shortcut-for-creating-requests
 */
final class QuotesSpider extends BasicSpider
{
    public array $startUrls = [
        'https://quotes.toscrape.com/page/1/',
    ];

    public function parse(Response $response): Generator
    {
        $items = $response
            ->filter('div.quote')
            ->each(function (Crawler $crawler) {
                return [
                    'text' => $crawler->filter('span.text')->text(),
                    'author' => $crawler->filter('span small')->text(),
                    'tags' => $crawler
                        ->filter('div.tags a.tag')
                        ->each(fn(Crawler $crawler) => $crawler->text())
                ];
            });

        foreach ($items as $item) {
            yield $this->item($item);
        }

        $nextPageCrawler = $response->filter('li.next a');
        if ($nextPageCrawler->count() > 0) {
            $nextPageUrl = $nextPageCrawler->link()->getUri();
            yield $this->request('GET', $nextPageUrl);
        }
    }
}
