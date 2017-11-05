<?php

namespace Billplz\TestCase;

use Billplz\Signature;
use PHPUnit\Framework\TestCase as PHPUnit;

class SignatureTest extends PHPUnit
{
    /** @test */
    public function it_can_verify_valid_hash()
    {
        $given = [
            'id' => 'W_79pJDk',
            'collection_id' => '599',
            'paid' => 'true',
            'state' => 'paid',
            'amount' => '200',
            'paid_amount' => '0',
            'due_at' => '2020-12-31',
            'email' => 'api@billplz.com',
            'mobile' => '+60112223333',
            'name' => 'MICHAEL API',
            'url' => 'http://billplz.dev/bills/W_79pJDk',
            'paid_at' => '2015-03-09 16:23:59 +0800',
        ];

        $stub = new Signature('foobar', [
            'amount', 'collection_id', 'due_at', 'email', 'id', 'mobile', 'name',
            'paid_amount', 'paid_at', 'paid', 'state', 'url',
        ]);

        $this->assertTrue($stub->verify($given, '01bdc1167f8b4dd1f591d8af7ada00061d39ca2b63e66c6588474a918a04796c'));
    }

    /** @test */
    public function it_cant_verify_invalid_hash()
    {
        $given = [
            'id' => 'A_79pJDk',
            'collection_id' => '599',
            'paid' => 'true',
            'state' => 'paid',
            'amount' => '200',
            'paid_amount' => '0',
            'due_at' => '2020-12-31',
            'email' => 'api@billplz.com',
            'mobile' => '+60112223333',
            'name' => 'MICHAEL API',
            'url' => 'http://billplz.dev/bills/A_79pJDk',
            'paid_at' => '2015-03-09 16:23:59 +0000',
        ];

        $stub = new Signature('foobar', [
            'amount', 'collection_id', 'due_at', 'email', 'id', 'mobile', 'name',
            'paid_amount', 'paid_at', 'paid', 'state', 'url',
        ]);

        $this->assertFalse($stub->verify($given, '01bdc1167f8b4dd1f591d8af7ada00061d39ca2b63e66c6588474a918a04796c'));
    }
}
