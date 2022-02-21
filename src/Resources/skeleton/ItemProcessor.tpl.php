<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use RoachPHP\Events\ItemDropped;
use RoachPHP\Events\ItemScraped;
use RoachPHP\Events\RequestDropped;
use RoachPHP\Events\RequestScheduling;
use RoachPHP\Events\RequestSending;
use RoachPHP\Events\ResponseDropped;
use RoachPHP\Events\RunFinished;
use RoachPHP\Events\RunStarting;
use RoachPHP\Extensions\ExtensionInterface;
use RoachPHP\Support\Configurable;

final class <?= $class_name; ?> implements ExtensionInterface<?= "\n"; ?>
{
    use Configurable;

    private const PRIORITY = 50;

    public static function getSubscribedEvents(): array
    {
        return [
            RunStarting::NAME => ['onRunStarting', self::PRIORITY],
            RequestSending::NAME => ['onRequestSending', self::PRIORITY],
            RequestDropped::NAME => ['onRequestDropped', self::PRIORITY],
            RequestScheduling::NAME => ['onRequestScheduling', self::PRIORITY],
            ResponseDropped::NAME => ['onResponseDropped', self::PRIORITY],
            ItemDropped::NAME => ['onItemDropped', self::PRIORITY],
            ItemScraped::NAME => ['onItemScraped', self::PRIORITY],
            RunFinished::NAME => ['onRunFinished', self::PRIORITY],
        ];
    }

    public function onRunStarting(RunStarting $event): void
    {
        // TODO: Implement onRunStarting() method.
    }

    public function onRequestSending(RequestSending $event): void
    {
        // TODO: Implement onRequestSending() method.
    }

    public function onRequestDropped(RequestDropped $event): void
    {
        // TODO: Implement onRequestDropped() method.
    }

    public function onRequestScheduling(RequestScheduling $event): void
    {
        // TODO: Implement onRequestScheduling() method.
    }

    public function onResponseDropped(ResponseDropped $event): void
    {
        // TODO: Implement onResponseDropped() method.
    }

    public function onItemDropped(ItemDropped $event): void
    {
        // TODO: Implement onItemDropped() method.
    }

    public function onItemScraped(ItemScraped $event): void
    {
        // TODO: Implement onItemScraped() method.
    }

    public function onRunFinished(RunFinished $event): void
    {
        // TODO: Implement onRunFinished() method.
    }
}
