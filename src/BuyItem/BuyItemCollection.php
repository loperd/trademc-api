<?php

declare(strict_types=1);

namespace Loper\TrademcApi\BuyItem;

final class BuyItemCollection
{
    public function __construct(public readonly BuyItemCategory $category, public readonly iterable $items)
    {
    }
}