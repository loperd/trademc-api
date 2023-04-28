<?php

declare(strict_types=1);

namespace Loper\TrademcApi\BuyItem\Field;

final class FieldRegexBuilder
{
    public const ALL_SYMBOLS_WITHOUT_SPACES_REGEX = '^[^\s]+$';

    public static function fromFlags(string $flags): string
    {
        if (str_contains(BuyItemField::ALL_SYMBOLS_WITHOUT_SPACES, $flags)) {
            return self::ALL_SYMBOLS_WITHOUT_SPACES_REGEX;
        }

        $self = new self();
        if (str_contains($flags, BuyItemField::DIGITS)) {
            $self->withDigits();
        }
        if (str_contains($flags, BuyItemField::WORDS)) {
            $self->withWords();
        }
        if (str_contains($flags, BuyItemField::SPACES)) {
            $self->withSpaces();
        }

        return $self->build();
    }
    private function __construct()
    {
    }
    private function __clone(): void
    {
    }
    public function __serialize(): array
    {
        return [];
    }
    public function __wakeup(): void
    {
    }

    private array $parts = [];

    private function withWords(): void
    {
        $this->parts[] = '\w';
    }

    private function withDigits(): void
    {
        $this->parts[] = '\d';
    }

    private function withSpaces(): void
    {
        $this->parts[] = '\s';
    }

    private function build(): string
    {
        return \sprintf('^[%s]+$', \implode('', $this->parts));
    }
}