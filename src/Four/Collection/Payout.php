<?php

namespace Billplz\Four\Collection;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;
use Billplz\Contracts\Collection\Payout as Contract;

class Payout extends Request implements Contract
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v4';

    /**
     * Create a new mass payment instruction (mpi) collection.
     *
     * @return \Billplz\Response
     */
    public function create(string $title): Response
    {
        return $this->send('POST', 'mass_payment_instruction_collections', [], compact('title'));
    }

    /**
     * Get mass payment instruction (mpi) collection.
     *
     * @return \Billplz\Response
     */
    public function get(string $collectionId): Response
    {
        return $this->send('GET', "mass_payment_instruction_collections/{$collectionId}", [], []);
    }
}
