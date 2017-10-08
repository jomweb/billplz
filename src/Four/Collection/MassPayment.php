<?php

namespace Billplz\Four\Collection;

use Billplz\Four\Request;

class MassPayment extends Request
{
    /**
     * Create a new mass payment instruction (mpi) collection.
     *
     * @param  string  $title
     *
     * @return \Laravie\Codex\Response
     */
    public function create($title)
    {
        return $this->send('POST', 'mass_payment_instruction_collections', [], compact('title'));
    }

    /**
     * Get mass payment instruction (mpi) collection.
     *
     * @param  string  $mpiCollectionId
     *
     * @return \Laravie\Codex\Response
     */
    public function get($mpiCollectionId)
    {
        return $this->send('GET', "mass_payment_instruction_collections/{$mpiCollectionId}", [], []);
    }
}
