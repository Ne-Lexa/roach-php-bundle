<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use RoachPHP\Downloader\Middleware\RequestMiddlewareInterface;
use RoachPHP\Http\Request;
use RoachPHP\Support\Configurable;

final class <?= $class_name; ?> implements RequestMiddlewareInterface<?= "\n"; ?>
{
    use Configurable;

    public function handleRequest(Request $request): Request
    {
        // TODO: Implement handleRequest() method.
        return $request;
    }

    private function defaultOptions(): array
    {
        return [];
    }
}
