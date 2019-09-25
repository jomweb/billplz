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
     * @param  string  $name
     * @param  string  $email
     * @param  string  $phoneNumber
     * @param  string  $cardNumber
     * @param  string  $cvv
     * @param  string  $expiry
     *
     * @return \Billplz\Response
     */
    public function create(string $name, string $email, string $phoneNumber, string $cardNumber, string $cvv, string $expiry): Response
    {
        $body = \compact('name', 'email', 'cvv', 'expiry');
        $body['phone'] = $phoneNumber;
        $body['card_number'] = $cardNumber;

        return $this->send('POST', 'cards', [], $body);
    }

    /**
     * Activate the card.
     *
     * @param  string  $cardId
     * @param  string  $cardToken
     *
     * @return \Billplz\Response
     */
    public function activate(string $cardId, string $cardToken): Response
    {
        $body = [
            'token' => $cardToken,
            'active' => true,
        ];

        return $this->send('PUT', "cards/{$cardId}", [], $body);
    }

    /**
     * Deactivate the card.
     *
     * @param  string  $cardId
     * @param  string  $cardToken
     *
     * @return \Billplz\Response
     */
    public function deactivate(string $cardId, string $cardToken): Response
    {
        $body = [
            'token' => $cardToken,
            'active' => false,
        ];

        return $this->send('PUT', "cards/{$cardId}", [], $body);
    }
}
