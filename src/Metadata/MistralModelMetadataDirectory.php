<?php

declare(strict_types=1);

namespace WordPress\MistralAiProvider\Metadata;

use WordPress\AiClient\Messages\Enums\ModalityEnum;
use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\DTO\Response;
use WordPress\AiClient\Providers\Http\Enums\HttpMethodEnum;
use WordPress\AiClient\Providers\Http\Exception\ResponseException;
use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use WordPress\AiClient\Providers\Models\DTO\SupportedOption;
use WordPress\AiClient\Providers\Models\Enums\CapabilityEnum;
use WordPress\AiClient\Providers\Models\Enums\OptionEnum;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleModelMetadataDirectory;
use WordPress\MistralAiProvider\Provider\MistralProvider;

/**
 * Class for the Mistral model metadata directory.
 *
 * @since 1.0.0
 *
 * @phpstan-type ModelsResponseData array{
 *     data: list<array{id: string}>
 * }
 */
class MistralModelMetadataDirectory extends AbstractOpenAiCompatibleModelMetadataDirectory
{
    /**
     * {@inheritDoc}
     *
     * @since 1.0.0
     */
    protected function createRequest(HttpMethodEnum $method, string $path, array $headers = [], $data = null): Request
    {
        return new Request(
            $method,
            MistralProvider::url($path),
            $headers,
            $data
        );
    }

    /**
     * {@inheritDoc}
     *
     * @since 1.0.0
     */
    protected function parseResponseToModelMetadataList(Response $response): array
    {
        /** @var ModelsResponseData $responseData */
        $responseData = $response->getData();
        if (!isset($responseData['data']) || !$responseData['data']) {
            throw ResponseException::fromMissingData('Mistral', 'data');
        }

        $mistralCapabilities = [
            CapabilityEnum::textGeneration(),
            CapabilityEnum::chatHistory(),
        ];

        $mistralBaseOptions = [
            new SupportedOption(OptionEnum::systemInstruction()),
            new SupportedOption(OptionEnum::candidateCount()),
            new SupportedOption(OptionEnum::maxTokens()),
            new SupportedOption(OptionEnum::temperature()),
            new SupportedOption(OptionEnum::topP()),
            new SupportedOption(OptionEnum::stopSequences()),
            new SupportedOption(OptionEnum::presencePenalty()),
            new SupportedOption(OptionEnum::frequencyPenalty()),
            new SupportedOption(OptionEnum::outputMimeType(), ['text/plain', 'application/json']),
            new SupportedOption(OptionEnum::outputSchema()),
            new SupportedOption(OptionEnum::functionDeclarations()),
            new SupportedOption(OptionEnum::customOptions()),
        ];

        $mistralTextOptions = array_merge($mistralBaseOptions, [
            new SupportedOption(OptionEnum::inputModalities(), [[ModalityEnum::text()]]),
            new SupportedOption(OptionEnum::outputModalities(), [[ModalityEnum::text()]]),
        ]);

        $mistralMultimodalInputOptions = array_merge($mistralBaseOptions, [
            new SupportedOption(
                OptionEnum::inputModalities(),
                [
                    [ModalityEnum::text()],
                    [ModalityEnum::text(), ModalityEnum::image()],
                ]
            ),
            new SupportedOption(OptionEnum::outputModalities(), [[ModalityEnum::text()]]),
        ]);

        $modelsData = (array) $responseData['data'];

        $models = array_values(
            array_map(
                static function (array $modelData) use (
                    $mistralCapabilities,
                    $mistralTextOptions,
                    $mistralMultimodalInputOptions
                ): ModelMetadata {
                    $modelId = $modelData['id'];

                    /*
                     * Mistral lists non-chat models (embeddings, moderation, OCR)
                     * through the same endpoint. They are not text generators, so
                     * they are exposed without text generation capability.
                     */
                    if (self::isNonTextModel($modelId)) {
                        $modelCaps = [];
                        $modelOptions = [];
                    } else {
                        $modelCaps = $mistralCapabilities;
                        $modelOptions = self::supportsMultimodalTextInput($modelId)
                            ? $mistralMultimodalInputOptions
                            : $mistralTextOptions;
                    }

                    return new ModelMetadata(
                        $modelId,
                        $modelId, // The Mistral API does not return a display name.
                        $modelCaps,
                        $modelOptions
                    );
                },
                $modelsData
            )
        );

        usort($models, [$this, 'modelSortCallback']);

        return $models;
    }

    /**
     * Checks whether a Mistral model is a non-text model (embeddings, moderation, or OCR).
     *
     * @since 1.0.0
     *
     * @param string $modelId The model ID.
     * @return bool True if the model is not a text generation model, false otherwise.
     */
    private static function isNonTextModel(string $modelId): bool
    {
        return str_contains($modelId, 'embed')
            || str_contains($modelId, 'moderation')
            || str_contains($modelId, 'ocr');
    }

    /**
     * Checks whether a Mistral text generation model supports multimodal (image) input.
     *
     * The Pixtral models are Mistral's vision-capable models.
     *
     * @since 1.0.0
     *
     * @param string $modelId The model ID.
     * @return bool True if the model supports multimodal text input, false otherwise.
     */
    private static function supportsMultimodalTextInput(string $modelId): bool
    {
        return str_contains($modelId, 'pixtral')
            || str_contains($modelId, 'vision');
    }

    /**
     * Callback function for sorting models by ID, to be used with `usort()`.
     *
     * This method expresses preferences for certain models or model families within the provider by putting them
     * earlier in the sorted list. The objective is not to be opinionated about which models are better, but to ensure
     * that more commonly used, more recent, or flagship models are presented first to users.
     *
     * @since 1.0.0
     *
     * @param ModelMetadata $a First model.
     * @param ModelMetadata $b Second model.
     * @return int Comparison result.
     */
    protected function modelSortCallback(ModelMetadata $a, ModelMetadata $b): int
    {
        $aId = $a->getId();
        $bId = $b->getId();

        // Push non-text utility models (embeddings, moderation, OCR) to the bottom.
        $aNonText = self::isNonTextModel($aId);
        $bNonText = self::isNonTextModel($bId);
        if ($aNonText !== $bNonText) {
            return $aNonText ? 1 : -1;
        }

        // Prefer the flagship 'mistral-' family over other families.
        $aMistral = str_starts_with($aId, 'mistral-');
        $bMistral = str_starts_with($bId, 'mistral-');
        if ($aMistral !== $bMistral) {
            return $aMistral ? -1 : 1;
        }

        // Within the 'mistral-' family, prefer large > medium > small tiers.
        if ($aMistral && $bMistral) {
            $aTier = self::familyTierRank($aId);
            $bTier = self::familyTierRank($bId);
            if ($aTier !== $bTier) {
                return $aTier <=> $bTier;
            }
        }

        // Fallback: Sort alphabetically.
        return strcmp($aId, $bId);
    }

    /**
     * Returns a sort rank for a model's tier (large is preferred over medium over small).
     *
     * @since 1.0.0
     *
     * @param string $modelId The model ID.
     * @return int The tier rank (lower sorts earlier).
     */
    private static function familyTierRank(string $modelId): int
    {
        $tiers = ['large' => 0, 'medium' => 1, 'small' => 2];
        foreach ($tiers as $name => $rank) {
            if (str_contains($modelId, $name)) {
                return $rank;
            }
        }
        return 3;
    }
}
