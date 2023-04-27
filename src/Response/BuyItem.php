<?php

declare(strict_types=1);

namespace Loper\TrademcApi\Response;

final class BuyItem
{
    public function __construct(
        public readonly int $id,
        public readonly int $type,
        public readonly string $name,
        public readonly int $cost,
        public readonly string $image,
        public readonly string $description,
    ) {
    }
}