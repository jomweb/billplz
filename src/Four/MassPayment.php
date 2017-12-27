<?php

namespace Billplz\Four;

use Billplz\Request;
use Laravie\Codex\Contracts\Response as ResponseContract;

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
     * @param  string  $collectionId
     * @param  string  $bankCode
     * @param  string  $bankAccountNumber
     * @param  string  $identityNumber
     * @param  string  $name
     * @param  string  $description
     * @param  int  $total
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function create(
        string $collectionId,
        string $bankCode,
        string $bankAccountNumber,
        string $identityNumber,
        string $name,
        string $description,
        $total,
        array $optional = []
    ): ResponseContract {
        $body = array_merge(compact('title', 'name', 'description', 'total'), $optional);
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
    public function get(string $instructionId): ResponseContract
    {
        return $this->send('GET', "mass_payment_instructions/{$instructionId}", [], []);
    }
}
