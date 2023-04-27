<?php

declare(strict_types=1);

namespace Loper\TrademcApi\Exception;

final class TrademcApiErrorException extends \RuntimeException
{
    public readonly string $originalMessage;
    public readonly int $originalCode;
    public readonly string $apiMethod;

    /**
     * @param array{code: int, message: string} $errorData
     */
    public function __construct(array $errorData, string $method)
    {
        $this->apiMethod = $method;
        $this->originalCode = $errorData['code'];
        $this->originalMessage = $errorData['message'];
        parent::__construct(\sprintf('Error occurred while sending method "%s": %s', $method, $errorData['message']));
    }

    public static function categoriesNotFound(): TrademcApiErrorException
    {
        return new self(['message' => 'Categories not found.', 'code' => 500], 'getItems');
    }
}