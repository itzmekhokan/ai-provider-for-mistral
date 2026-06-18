<?php

declare(strict_types=1);

namespace WordPress\MistralAiProvider\Models;

use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\Enums\HttpMethodEnum;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleTextGenerationModel;
use WordPress\MistralAiProvider\Provider\MistralProvider;

/**
 * Class for a Mistral text generation model using the Chat Completions API.
 *
 * Mistral exposes an OpenAI-compatible `chat/completions` endpoint, so the shared
 * OpenAI-compatible base class handles request and response transformation. Only
 * the provider-specific request construction needs to be implemented here.
 *
 * @since 1.0.0
 */
class MistralTextGenerationModel extends AbstractOpenAiCompatibleTextGenerationModel
{
    /**
     * {@inheritDoc}
     *
     * @since 1.0.0
     */
    protected function createRequest(
        HttpMethodEnum $method,
        string $path,
        array $headers = [],
        $data = null
    ): Request {
        return new Request(
            $method,
            MistralProvider::url($path),
            $headers,
            $data,
            $this->getRequestOptions()
        );
    }
}
