<?php

declare(strict_types=1);

namespace Loper\TrademcApi\BuyItem;

final class BuyItemCategory
{
    public function __construct(public readonly int $id, public readonly string $name)
    {
    }
}