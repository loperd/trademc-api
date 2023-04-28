<?php

declare(strict_types=1);

namespace Loper\TrademcApi\BuyItem;

use Loper\TrademcApi\BuyItem\Field\BuyItemField;

final class BuyItem
{
    /**
     * @param list<BuyItemField> $fields
     */
    public function __construct(
        public readonly int $id,
        public readonly int $type,
        public readonly string $name,
        public readonly int $cost,
        public readonly string $image,
        public readonly string $description,
        public readonly array $fields = [],
    ) {
    }
}