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
     * @param  string      $massPaymentInstructionCollectionId
     * @param  string      $bankCode
     * @param  string|int  $bankAccountNumber
     * @param  string|int  $identityNumber
     * @param  string      $name
     * @param  string      $description
     * @param  int         $total
     * @param  array       $optional
     *
     * @return \Laravie\Codex\Response
     */
    public function create(
        $massPaymentInstructionCollectionId,
        $bankCode,
        $bankAccountNumber,
        $identityNumber,
        $name,
        $description,
        $total,
        array $optional = []
    ) {
        $body = array_merge(compact('title', 'name', 'description', 'total'), $optional);
        $body['mass_payment_instruction_collection_id'] = $massPaymentInstructionCollectionId;
        $body['bank_code'] = $bankCode;
        $body['bank_account_number'] = $bankAccountNumber;
        $body['identity_number'] = $identityNumber;

        return $this->send('POST', 'mass_payment_instructions', [], $body);
    }

    /**
     * Get mass payment instruction (mpi).
     *
     * @param  string  $massPaymentInstructionId
     *
     * @return \Laravie\Codex\Response
     */
    public function get($massPaymentInstructionId)
    {
        return $this->send('GET', "mass_payment_instructions/{$massPaymentInstructionId}", [], []);
    }
}
