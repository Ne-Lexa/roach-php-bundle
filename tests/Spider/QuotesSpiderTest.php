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

namespace Nelexa\RoachPhpBundle\Tests\Spider;

use Nelexa\RoachPhpBundle\Tests\App\Spider\QuotesSpider;
use RoachPHP\Roach;
use RoachPHP\Testing\FakeLogger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @medium
 *
 * @internal
 */
final class QuotesSpiderTest extends KernelTestCase
{
    private FakeLogger $logger;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = self::getContainer();

        $logger = $container->get('roach_php.logger');
        self::assertNotNull($logger);
        self::assertInstanceOf(FakeLogger::class, $logger);
        $this->logger = $logger;
    }

    public function testRunSpider(): void
    {
        Roach::startSpider(QuotesSpider::class);

        self::assertTrue($this->logger->messageWasLogged('info', 'Run starting'));
        self::assertTrue(
            $this->logger->messageWasLogged('info', 'Item scraped', [
                'text' => '“The world as we have created it is a process of our thinking. It cannot be changed without changing our thinking.”',
                'author' => 'Albert Einstein',
                'tags' => [
                    'change',
                    'deep-thoughts',
                    'thinking',
                    'world',
                ],
            ])
        );
        self::assertTrue(
            $this->logger->messageWasLogged('info', 'Item scraped', [
                'text' => '“... a mind needs books as a sword needs a whetstone, if it is to keep its edge.”',
                'author' => 'George R.R. Martin',
                'tags' => [
                    'books',
                    'mind',
                ],
            ])
        );
        self::assertTrue(
            $this->logger->messageWasLogged('info', 'Dispatching request', [
                'uri' => 'https://quotes.toscrape.com/page/9/',
            ])
        );
        self::assertTrue($this->logger->messageWasLogged('info', 'Run statistics'));
        self::assertTrue($this->logger->messageWasLogged('info', 'Run finished'));
    }
}
