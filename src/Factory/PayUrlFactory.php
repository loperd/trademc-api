<?php

declare(strict_types=1);

namespace Loper\TrademcApi\Factory;

use Loper\TrademcApi\Order\Order;

final class PayUrlFactory
{
    public const PAY_URL_FORMAT = 'https://pay.trademc.org';

    private string $pendingUrl;
    private string $failUrl;

    public function __construct(
        private readonly string $successUrl, 
        ?string $pendingUrl = null, 
        ?string $failUrl = null
    ) {
        $this->failUrl = $failUrl ?? $this->successUrl;
        $this->pendingUrl = $pendingUrl ?? $this->successUrl;
    }


    public function createPayUrl(Order $order): string
    {
        $params = [];
        $params['cart_id'] = $order->cartId;
        $params['success_url'] = $this->successUrl;
        $params['pending_url'] = $this->pendingUrl;
        $params['fail_url'] = $this->failUrl;

        return \implode('?', [self::PAY_URL_FORMAT, http_build_query($params)]);
    }
}