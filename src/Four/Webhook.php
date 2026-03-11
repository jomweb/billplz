<?php

namespace Billplz\Four;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;

class Webhook extends Request
{
    /**
     * Version namespace.
     */
    protected string $version = 'v4';

    /**
     * Get webhook rank for the account.
     */
    public function rank(): Response
    {
        return $this->send('GET', 'webhook_rank', [], []);
    }
}
