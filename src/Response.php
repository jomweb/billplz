<?php

namespace Billplz;

use Laravie\Codex\Response as BaseResponse;

class Response extends BaseResponse
{
    /**
     * Validate the response object.
     *
     * @return $this
     */
    public function validate()
    {
        $this->abortIfRequestUnauthorized();
        $this->abortIfRequestHasFailed();

        return $this;
    }
}
