=== AI Provider for Mistral ===

Contributors: khokansardar
Requires at least: 6.9
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://spdx.org/licenses/GPL-2.0-or-later.html
Tags: ai, mistral, pixtral, llm

AI Provider for Mistral for the WordPress AI Client.

== Description ==

This plugin provides the Mistral implementation for the WordPress AI Client. It registers Mistral as an available AI provider, allowing other plugins built on the WordPress AI Client to generate text using Mistral's models (Mistral Large, Medium, Small, the Ministral and Magistral families, and the vision-capable Pixtral models).

The plugin connects to Mistral's OpenAI-compatible Chat Completions API. Bring your own API key from the [Mistral console](https://console.mistral.ai/api-keys).

Supported capabilities:

* Text generation (chat) with the Mistral model family
* Multimodal (image) input for vision-capable models, such as Pixtral
* Function calling and JSON / structured output

== Frequently Asked Questions ==

= Does this plugin work on its own? =

No, this plugin requires the PHP AI Client (and a consuming AI feature plugin) to be installed and activated. It provides the Mistral-specific implementation that the PHP AI Client uses.

= Where do I get an API key? =

Create an API key in the [Mistral console](https://console.mistral.ai/api-keys). The key is configured through the AI Client / Connectors interface, not in this plugin directly.

== External services ==

This plugin connects to the Mistral API to provide text generation with Mistral's models. It is required for the plugin's core purpose: it registers Mistral as a provider for the WordPress AI Client so that AI features can generate responses.

The plugin sends a request to Mistral's API (https://api.mistral.ai/v1) each time a consuming AI feature generates content through the Mistral provider. Each request includes your Mistral API key (for authentication) and the input you submit for generation — such as the prompt text, any chat messages, and any image input you provide for vision-capable models. No request is sent unless an AI feature actively triggers a generation, and no data is sent until you have configured a Mistral API key. The plugin does not send any data on its own.

This service is provided by Mistral AI. Please review their terms and privacy policy:

* Terms of Service: https://legal.mistral.ai/terms
* Privacy Policy: https://legal.mistral.ai/terms/privacy-policy

== Changelog ==

= 1.0.0 =

* Initial release. Adds Mistral text generation support to the WordPress AI Client.
