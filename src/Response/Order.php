<?php

declare(strict_types=1);

namespace Loper\TrademcApi\Response;

final class Order
{
    public function __construct(public readonly int $total, public readonly int $cartId)
    {
    }
}