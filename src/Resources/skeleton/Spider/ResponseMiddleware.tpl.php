<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use RoachPHP\Spider\Middleware\ResponseMiddlewareInterface;
use RoachPHP\Http\Response;
use RoachPHP\Support\Configurable;

final class <?= $class_name; ?> implements ResponseMiddlewareInterface<?= "\n"; ?>
{
    use Configurable;

    public function handleResponse(Response $response): Response
    {
        // TODO: Implement handleResponse() method.
        return $response;
    }

    private function defaultOptions(): array
    {
        return [];
    }
}
