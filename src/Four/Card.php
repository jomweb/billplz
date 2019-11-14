<?php

namespace Billplz\Four;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;
use Billplz\Contracts\Card as Contract;

class Card extends Request implements Contract
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v4';

    /**
     * Create new card token.
     *
     * @return \Billplz\Response
     */
    public function create(
        string $name,
        string $email,
        string $phoneNumber,
        string $cardNumber,
        string $cvv,
        string $expiry
    ): Response {
        $body = \compact('name', 'email', 'cvv', 'expiry');
        $body['phone'] = $phoneNumber;
        $body['card_number'] = $cardNumber;

        return $this->send('POST', 'cards', [], $body);
    }

    /**
     * Activate the card.
     *
     * @return \Billplz\Response
     */
    public function activate(string $cardId, string $cardToken): Response
    {
        return $this->send('PUT', "cards/{$cardId}", [], [
            'token' => $cardToken,
            'active' => true,
        ]);
    }

    /**
     * Deactivate the card.
     *
     * @return \Billplz\Response
     */
    public function deactivate(string $cardId, string $cardToken): Response
    {
        return $this->send('PUT', "cards/{$cardId}", [], [
            'token' => $cardToken,
            'active' => false,
        ]);
    }
}
