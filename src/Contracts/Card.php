<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Card extends Request
{
    /**
     * Create new card token.
     */
    public function create(
        string $name,
        string $email,
        string $phoneNumber,
        string $cardNumber,
        string $cvv,
        string $expiry
    ): Response;

    /**
     * Activate the card.
     *
     * @return \Billplz\Response
     */
    public function activate(string $cardId, string $cardToken): Response;

    /**
     * Deactivate the card.
     *
     * @return \Billplz\Response
     */
    public function deactivate(string $cardId, string $cardToken): Response;
}
