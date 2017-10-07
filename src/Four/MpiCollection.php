<?php

namespace Billplz\Four;

class MpiCollection extends Request
{

    /**
     * Create a new mass payment instruction (mpi) collection.
     *
     * @param  string  $title
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Response
     */
    public function create($title)
    {
        $body = compact('title');
        return $this->send('POST', 'mass_payment_instruction_collections', [], $body);
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
