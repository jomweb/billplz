<?php

namespace Billplz;

use Laravie\Codex\Contracts\Filterable;
use Laravie\Codex\Filter\WithSanitizer;
use Laravie\Codex\Response as BaseResponse;

class Response extends BaseResponse implements Filterable
{
    use WithSanitizer;

    /**
     * Validate the response object.
     *
     * @return $this
     */
    public function validate()
    {
        $this->abortIfRequestNotFound();
        $this->abortIfRequestUnauthorized();
        $this->abortIfRequestHasFailed();

        return $this;
    }
}
