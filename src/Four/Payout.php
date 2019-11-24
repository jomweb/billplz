<?php

namespace Billplz\Four;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;
use Billplz\Contracts\Payout as Contract;

class Payout extends Request implements Contract
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
     * @param  int  $total
     *
     * @return \Billplz\Response
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
    ): Response {
        $body = \array_merge(\compact('name', 'description', 'total'), $optional);
        $body['mass_payment_instruction_collection_id'] = $collectionId;
        $body['bank_code'] = $bankCode;
        $body['bank_account_number'] = $bankAccountNumber;
        $body['identity_number'] = $identityNumber;

        return $this->send('POST', 'mass_payment_instructions', [], $body);
    }

    /**
     * Get mass payment instruction (mpi).
     *
     * @return \Billplz\Response
     */
    public function get(string $instructionId): Response
    {
        return $this->send('GET', "mass_payment_instructions/{$instructionId}", [], []);
    }
}
