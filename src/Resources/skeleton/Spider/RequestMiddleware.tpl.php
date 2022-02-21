<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use RoachPHP\Spider\Middleware\RequestMiddlewareInterface;
use RoachPHP\Http\Request;
use RoachPHP\Http\Response;
use RoachPHP\Support\Configurable;

final class <?= $class_name; ?> implements RequestMiddlewareInterface<?= "\n"; ?>
{
    use Configurable;

    public function handleRequest(Request $request, Response $response): Request
    {
        // TODO: Implement handleRequest() method.
        return $request;
    }

    private function defaultOptions(): array
    {
        return [];
    }
}
