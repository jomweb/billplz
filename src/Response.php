<?php

namespace Billplz;

use Laravie\Codex\Contracts\Filterable;
use Laravie\Codex\Filter\WithSanitizer;

class Response extends \Laravie\Codex\Response implements Filterable
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
