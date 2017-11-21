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
     * @Flow\InjectConfiguration(path="dimensions")
     * @var array
     */
    protected $dimensionsSettings;

    /**
     * @Flow\Inject
     * @var Bootstrap
     */
    protected $bootstrap;

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
                    $dimensionConfiguration['defaultPreset'] = $this->dimensionsSettings[$dimensionName]['defaultPresetByHost'][$requestedHost];
                }
            }
        }
    }
}
