<?php

declare(strict_types=1);

/*
 * Copyright (c) 2022 Ne-Lexa <alexey@nelexa.ru>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/Ne-Lexa/roach-php-bundle
 */

namespace Nelexa\RoachPhpBundle\Tests\App\Spider;

use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @see https://docs.scrapy.org/en/latest/intro/tutorial.html#a-shortcut-for-creating-requests
 */
final class QuotesSpider extends BasicSpider
{
    /** @var string[] */
    public array $startUrls = [
        'https://quotes.toscrape.com/page/1/',
    ];

    public function parse(Response $response): \Generator
    {
        /** @var array<array> $items */
        $items = $response
            ->filter('div.quote')
            ->each(static function (Crawler $crawler) {
                return [
                    'text' => $crawler->filter('span.text')->text(),
                    'author' => $crawler->filter('span small')->text(),
                    'tags' => $crawler
                        ->filter('div.tags a.tag')
                        ->each(static fn (Crawler $crawler) => $crawler->text()),
                ];
            })
        ;

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
