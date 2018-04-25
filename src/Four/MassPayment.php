<?php

namespace Billplz\Four;

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
     * Create a new mass payment instruction (mpi).
     *
     * @param  string      $collectionId
     * @param  string      $bankCode
     * @param  string|int  $bankAccountNumber
     * @param  string|int  $identityNumber
     * @param  string      $name
     * @param  string      $description
     * @param  int         $total
     * @param  array       $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function create(
        $collectionId,
        $bankCode,
        $bankAccountNumber,
        $identityNumber,
        $name,
        $description,
        $total,
        array $optional = []
    ) {
        $body = array_merge(compact('name', 'description', 'total'), $optional);
        $body['mass_payment_instruction_collection_id'] = $collectionId;
        $body['bank_code'] = $bankCode;
        $body['bank_account_number'] = $bankAccountNumber;
        $body['identity_number'] = $identityNumber;

        return $this->send('POST', 'mass_payment_instructions', [], $body);
    }

    /**
     * Get mass payment instruction (mpi).
     *
     * @param  string  $instructionId
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get($instructionId)
    {
        return $this->send('GET', "mass_payment_instructions/{$instructionId}", [], []);
    }
}
