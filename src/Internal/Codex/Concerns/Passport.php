<?php

namespace Laravie\Codex\Concerns;

trait Passport
{
    /**
     * API Client ID.
     *
     * @var string|null
     */
    protected $clientId;

    /**
     * API Client Secret.
     *
     * @var string|null
     */
    protected $clientSecret;

    /**
     * API Access Token.
     *
     * @var string|null
     */
    protected $accessToken;

    /**
     * Get Client ID.
     */
    final public function getClientId(): ?string
    {
        return $this->clientId;
    }

    /**
     * Set Client ID.
     *
     *
     * @return $this
     */
    final public function setClientId(?string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get Client Secret.
     */
    final public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    /**
     * Set Client Secret.
     *
     *
     * @return $this
     */
    final public function setClientSecret(?string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * Get access token.
     */
    final public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Set access token.
     *
     *
     * @return $this
     */
    final public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
