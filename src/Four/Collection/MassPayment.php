<?php

namespace Billplz\Four\Collection;

use Billplz\Request;

class MassPayment extends Request
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
     * @param  string  $title
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function create($title)
    {
        return $this->send('POST', 'mass_payment_instruction_collections', [], compact('title'));
    }

    /**
     * Get mass payment instruction (mpi) collection.
     *
     * @param  string  $collectionId
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get($collectionId)
    {
        return $this->send('GET', "mass_payment_instruction_collections/{$collectionId}", [], []);
    }
}
