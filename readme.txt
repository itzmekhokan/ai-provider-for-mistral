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

== Changelog ==

= 1.0.0 =

* Initial release. Adds Mistral text generation support to the WordPress AI Client.
