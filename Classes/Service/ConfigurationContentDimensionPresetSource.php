<?php
namespace Flownative\Neos\HostBasedDefaultPreset\Service;

/*
 * This file is part of the Flownative.Neos.HostBasedDefaultPreset package.
 *
 * (c) 2017 Karsten Dambekalns, Flownative GmbH
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Http\HttpRequestHandlerInterface;
use Neos\Neos\Domain\Service\ConfigurationContentDimensionPresetSource as NeosConfigurationContentDimensionPresetSource;

/**
 * A Dimension Preset Source that gets presets from settings and the overrides defaultPreset
 * based on the request host.
 *
 * @Flow\Scope("singleton")
 */
class ConfigurationContentDimensionPresetSource extends NeosConfigurationContentDimensionPresetSource
{
    /**
     * @Flow\InjectConfiguration(package="Neos.ContentRepository", path="contentDimensions")
     * @var array
     */
    protected $configuration;

    /**
     * @Flow\InjectConfiguration(path="forceEmptyUriSegment")
     * @var array
     */
    protected $forceEmptyUriSegment;

    /**
     * @Flow\InjectConfiguration(path="dimensions")
     * @var array
     */
    protected $dimensionsSettings;

    /**
     * @Flow\Inject
     * @var Bootstrap
     */
    protected $bootstrap;

    /**
     * Adjusts the incoming configuration as needed when the object is instantiated.
     *
     * @return void
     */
    protected function initializeObject()
    {
        if ($this->dimensionsSettings === []) {
            return;
        }

        // set default preset according to request host
        $activeRequestHandler = $this->bootstrap->getActiveRequestHandler();
        if ($activeRequestHandler instanceof HttpRequestHandlerInterface) {
            $requestedHost = $activeRequestHandler->getHttpRequest()->getUri()->getHost();

            foreach ($this->configuration as $dimensionName => &$dimensionConfiguration) {
                if (isset(
                    $this->dimensionsSettings[$dimensionName],
                    $this->dimensionsSettings[$dimensionName]['defaultPresetByHost'][$requestedHost]
                )) {
                    $newDefaultPreset = $this->dimensionsSettings[$dimensionName]['defaultPresetByHost'][$requestedHost];

                    $dimensionConfiguration['defaultPreset'] = $newDefaultPreset;

                    if (isset(
                        $dimensionConfiguration['presets'][$newDefaultPreset],
                        $dimensionConfiguration['presets'][$newDefaultPreset]['values']
                    )) {
                        $dimensionConfiguration['default'] = implode(',', $dimensionConfiguration['presets'][$newDefaultPreset]['values']);
                    }

                    if ($this->forceEmptyUriSegment === true) {
                        $dimensionConfiguration['presets'][$newDefaultPreset]['uriSegment'] = '';
                    }
                }
            }
        }
    }
}
