<?php

declare(strict_types=1);

namespace Loper\TrademcApi\BuyItem\Field;

final class BuyItemField
{
    public const DIGITS = 'd';
    public const WORDS = 'w';
    public const SPACES = 's';
    public const ALL_SYMBOLS_WITHOUT_SPACES = 'o';

    public string $regex;

    public function __construct(public readonly string $id, public readonly string $placeholder, public readonly string $flags)
    {
        if ('' !== $flags) {
            $this->regex = FieldRegexBuilder::fromFlags($flags);
        }
    }

    public function getHelpers(): array
    {
        $various = [];

        if (str_contains($this->flags, self::ALL_SYMBOLS_WITHOUT_SPACES)) {
            return ['все символы, кроме пробелов'];
        }

        foreach (str_split($this->flags) as $flag) {
            $various[] = match ($flag) {
                self::DIGITS => 'цифры',
                self::SPACES => 'пробелы',
                self::WORDS => 'символы латинского алфавита',
            };
        }

        return $various;
    }
}