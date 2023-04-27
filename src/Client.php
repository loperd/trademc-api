<?php

declare(strict_types=1);

namespace Loper\TrademcApi;

use Loper\TrademcApi\Response\BuyItem;
use Loper\TrademcApi\Response\BuyItemCategory;
use Loper\TrademcApi\Response\BuyItemCollection;
use Loper\TrademcApi\Response\Order;
use Loper\TrademcApi\Exception\TrademcApiErrorException;
use Nyholm\Psr7\Request;
use Psr\Http\Client\ClientInterface;

/**
 * @method array getOnline()
 *
 * @psalm-type BuyItemTypeArray = array{
 *     id: int,
 *     type: int,
 *     name: string,
 *     cost: int,
 *     description: string,
 *     image: string
 * }
 */
final class Client
{
    public const SHOP = 'shop';
    public const API_URI_TEMPLATE = 'https://api.trademc.org/%s.%s';

    public function __construct(
        private readonly int $shopId,
        private ?ClientInterface $client = null,
        private readonly int $version = 3,
    ) {
        $this->client ??= new \GuzzleHttp\Client();
    }

    public function __call(string $name, array $arguments)
    {
        $params = isset($arguments[0]) && \is_array($arguments[0])
            ? $arguments[0]
            : $arguments;

        return $this->sendRequest($name, $params);
    }

    /**
     * @return list<BuyItemCollection>
     *
     * @throws \JsonException
     */
    public function getItems(): array
    {
        $response = $this->sendRequest(__FUNCTION__);

        if (!$response['categories']) {
            throw TrademcApiErrorException::categoriesNotFound();
        }

        $result = [];
        foreach ($response['categories'] as $data) {
            $category = new BuyItemCategory($data['id'], $data['name']);
            $items = $this->hydrateItems($data['items']);
            $result[] = new BuyItemCollection($category, $items);
        }

        return $result;
    }

    /**
     * @param array $items Use as [$itemId => $count]
     */
    public function buyItems(
        array $items,
        string $buyer,
        ?string $coupon = null,
        array $userFields = [],
    ): Order {
        $params = [];
        $params['items'] = $this->formatBuyItems($items);
        $params['buyer'] = $buyer;
        null === $coupon ?: $params['coupon'] = $coupon;
        [] === $userFields ?: $params['user_fields'] = $userFields;

        $response = $this->sendRequest('buyItems', $params);

        return new Order($response['total'], $response['cart_id']);
    }

    public function buyItem(int $itemId, string $buyer, ?string $coupon = null, array $userFields = []): Order
    {
        return $this->buyItems([$itemId => 1], $buyer, $coupon, $userFields);
    }

    private function sendRequest(string $method, array $params = []): array
    {
        $params['shop'] = $this->shopId;
        $params['version'] = $this->version;

        $request = new Request('GET', $this->formatUri($method, $params));
        $response = $this->client->sendRequest($request);
        $responseBody = $response->getBody()->getContents();

        $responseData = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);

        if (isset($responseData['error'])) {
            throw new TrademcApiErrorException($responseData['error'], $method);
        }

        return $responseData['response'] ?? [];
    }

    private function formatUri(string $method, array $params = []): string
    {
        $resultUrl = \sprintf(self::API_URI_TEMPLATE, self::SHOP, $method);

        if ([] !== $params) {
            return \sprintf('%s?%s', $resultUrl, http_build_query($params));
        }

        return $resultUrl;
    }

    public function formatBuyItems(array $items): string
    {
        $itemConvertCallback = static fn($count, $itemId) => \sprintf('%d:%d', $itemId, $count);
        $formattedItems = \array_map($itemConvertCallback, $items, \array_keys($items));

        return \implode(',', $formattedItems);
    }

    /**
     * @param list<BuyItemTypeArray> $items
     *
     * @return array
     */
    private function hydrateItems(array $items): array
    {
        return \array_map(static function (array $item) {
            return new BuyItem(
                id: $item['id'],
                type: $item['type'],
                name: $item['name'],
                cost: $item['cost'],
                image: $item['image'],
                description: $item['description'],
            );
        }, $items);
    }
}