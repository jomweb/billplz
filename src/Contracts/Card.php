<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Card extends Request
{
    /**
     * Create new card token.
     *
     * @param  string  $name
     * @param  string  $email
     * @param  string  $phoneNumber
     * @param  string  $cardNumber
     * @param  string  $cvv
     * @param  string  $expiry
     *
     * @return \Laravie\Codex\Contracts\Response
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
     * @param  string  $cardId
     * @param  string  $cardToken
     *
     * @return \Billplz\Response
     */
    public function activate(string $cardId, string $cardToken): Response;

    /**
     * Deactivate the card.
     *
     * @param  string  $cardId
     * @param  string  $cardToken
     *
     * @return \Billplz\Response
     */
    public function deactivate(string $cardId, string $cardToken): Response;
}
