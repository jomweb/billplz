<?php

namespace Billplz\Exceptions;

use Exception;
use Laravie\Codex\Exceptions\HttpException;

/**
 * @property \Billplz\Response $response
 */
class ExceedRequestLimits extends HttpException
{
    /**
     * Construct a new HTTP exception.
     */
    public function __construct(
        \Billplz\Response $response,
        ?string $message = null,
        ?Exception $previous = null,
        int $code = 0
    ) {
        parent::__construct(
            $response, $message ?: 'Too many requests', $previous, $code = 419
        );

        $this->setResponse($response);
    }

    /**
     * Get time remaining before rate limit reset.
     */
    public function timeRemaining(): int
    {
        return $this->response->rateLimitNextReset();
    }
}
