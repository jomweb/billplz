<?php

namespace Billplz\Four;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;

class Webhook extends Request
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v4';

    /**
     * Get webhook rank for the account.
     *
     * @return \Billplz\Response
     */
    public function rank(): Response
    {
        return $this->send('GET', 'webhook_rank', [], []);
    }
}
