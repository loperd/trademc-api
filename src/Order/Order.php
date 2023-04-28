<?php

declare(strict_types=1);

namespace Loper\TrademcApi\Order;

final class Order
{
    public function __construct(public readonly int $total, public readonly int $cartId)
    {
    }
}