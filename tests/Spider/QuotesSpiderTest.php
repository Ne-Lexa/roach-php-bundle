<?php

namespace Nelexa\RoachPhpBundle\Tests\Spider;

use Nelexa\RoachPhpBundle\Tests\App\Spider\QuotesSpider;
use RoachPHP\Roach;
use RoachPHP\Testing\FakeLogger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @medium
 */
class QuotesSpiderTest extends KernelTestCase
{
    private FakeLogger $logger;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = self::getContainer();

        $logger = $container->get('logger');
        static::assertNotNull($logger);
        $this->logger = $logger;
    }

    public function testRunSpider(): void
    {
        Roach::startSpider(QuotesSpider::class);

        self::assertTrue($this->logger->messageWasLogged('info', 'Run starting'));
        self::assertTrue(
            $this->logger->messageWasLogged('info', 'Item scraped', [
                "text" => "“The world as we have created it is a process of our thinking. It cannot be changed without changing our thinking.”",
                "author" => "Albert Einstein",
                "tags" => [
                    "change",
                    "deep-thoughts",
                    "thinking",
                    "world",
                ]
            ])
        );
        self::assertTrue(
            $this->logger->messageWasLogged('info', 'Item scraped', [
                "text" => "“... a mind needs books as a sword needs a whetstone, if it is to keep its edge.”",
                "author" => "George R.R. Martin",
                "tags" => [
                    "books",
                    "mind"
                ]
            ])
        );
        self::assertTrue(
            $this->logger->messageWasLogged('info', 'Dispatching request', [
                "uri" => "https://quotes.toscrape.com/page/9/"
            ])
        );
        self::assertTrue($this->logger->messageWasLogged('info', 'Run statistics'));
        self::assertTrue($this->logger->messageWasLogged('info', 'Run finished'));
    }
}
