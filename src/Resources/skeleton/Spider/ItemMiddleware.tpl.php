<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use RoachPHP\Spider\Middleware\ItemMiddlewareInterface;
use RoachPHP\Http\Response;
use RoachPHP\ItemPipeline\ItemInterface;
use RoachPHP\Support\Configurable;

final class <?= $class_name; ?> implements ItemMiddlewareInterface<?= "\n"; ?>
{
    use Configurable;

    /**
     * Handles an item that got emitted while parsing $response.
     */
    public function handleItem(
        ItemInterface $item,
        Response $response,
    ): ItemInterface
    {
        // TODO: Implement handleItem() method.
        return $item;
    }

    private function defaultOptions(): array
    {
        return [];
    }
}
