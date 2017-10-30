<?php
/**
 * Created by PhpStorm.
 * User: khairul
 * Date: 29/10/2017
 * Time: 11:13 PM
 */

namespace Billplz\Three;

class Bank extends Request
{
    /**
     * Get list of bank for Bank Direct Feature
     *
     * @return \Laravie\Codex\Response
     */
    public function bankList()
    {
        return $this->send('GET', "fpx_banks");
    }
}