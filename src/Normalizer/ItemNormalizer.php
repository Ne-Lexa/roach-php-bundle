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

namespace Nelexa\RoachPhpBundle\Normalizer;

use RoachPHP\ItemPipeline\ItemInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ItemNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof ItemInterface;
    }

    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        /** @psalm-suppress all */
        return $object->all();
    }
}
