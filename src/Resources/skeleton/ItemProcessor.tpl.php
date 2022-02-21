<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use RoachPHP\ItemPipeline\ItemInterface;
use RoachPHP\ItemPipeline\Processors\ItemProcessorInterface;
use RoachPHP\Support\Configurable;

final class <?= $class_name; ?> implements ItemProcessorInterface<?= "\n"; ?>
{
    use Configurable;

    public function processItem(ItemInterface $item): ItemInterface
    {
        // TODO: Implement processItem() method.
        return $item;
    }
}
