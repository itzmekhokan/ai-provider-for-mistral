# AI Provider for Mistral

AI Provider for Mistral for the [WordPress AI Client](https://github.com/WordPress/php-ai-client). Works as both a Composer package and a WordPress plugin.

This provider registers **Mistral** with the AI Client, exposing Mistral's models (Mistral Large, Medium, Small, the Ministral and Magistral families, and the vision-capable Pixtral models) for text generation through Mistral's OpenAI-compatible Chat Completions API.

## Requirements

- PHP 7.4+
- WordPress 6.9+ (when used as a plugin)
- The [`wordpress/php-ai-client`](https://github.com/WordPress/php-ai-client) SDK
- A Mistral API key from the [Mistral console](https://console.mistral.ai/api-keys)

## How it works

Mistral exposes an OpenAI-compatible API, so this provider is intentionally thin:

| Class | Responsibility |
| --- | --- |
| `Provider\MistralProvider` | Registers the provider, base URL (`https://api.mistral.ai/v1`), API-key auth, and metadata. |
| `Metadata\MistralModelMetadataDirectory` | Lists models from `GET /v1/models` and maps capabilities/options. |
| `Models\MistralTextGenerationModel` | Sends `POST /v1/chat/completions` via the shared OpenAI-compatible base class. |

Because Mistral speaks the Chat Completions format, `MistralTextGenerationModel` extends
`AbstractOpenAiCompatibleTextGenerationModel` from the SDK and only overrides request construction.

## Supported capabilities

- Text generation (chat) with the Mistral model family
- Multimodal (image) input for vision-capable models (e.g. Pixtral)
- Function calling and JSON / structured output

Mistral also serves embeddings, moderation, and OCR models; these are listed without
text generation capability since they are not chat models.

## Installation

### As a WordPress plugin

Copy this directory into `wp-content/plugins/` and activate it alongside the WordPress AI Client.

### As a Composer package

```bash
composer require itzmekhokan/ai-provider-for-mistral
```

## Author

[Khokan Sardar](https://profiles.wordpress.org/khokansardar/)

## License

GPL-2.0-or-later
