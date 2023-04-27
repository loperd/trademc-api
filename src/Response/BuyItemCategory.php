<?php

declare(strict_types=1);

namespace Loper\TrademcApi\Response;

final class BuyItemCategory
{
    public function __construct(public readonly int $id, public readonly string $name)
    {
    }
}