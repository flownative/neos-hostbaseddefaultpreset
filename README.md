[![MIT license](http://img.shields.io/badge/license-MIT-brightgreen.svg)](http://opensource.org/licenses/MIT)
[![Packagist](https://img.shields.io/packagist/v/flownative/neos-hostbaseddefaultpreset.svg)](https://packagist.org/packages/flownative/neos-hostbaseddefaultpreset)
[![Maintenance level: Friendship](https://img.shields.io/badge/maintenance-%E2%99%A1%E2%99%A1-ff69b4.svg)](https://www.flownative.com/en/products/open-source.html)

# Host-based Content Dimension Default Presets for Neos

This allows to have the default preset for content dimensions change depending on the hostname of a site.

## Installation

`composer require flownative/neos-hostbaseddefaultpreset`

## Configuration

After setting up your content dimensions as usual, configure the default presets per content dimension like this:

    Flownative:
      Neos:
        HostBasedDefaultPreset:
          dimensions:
            'language':
              defaultPresetByHost:
                'acme.com': 'en'
                'acme.de': 'de'

If you like, you can configure the package to set the `uriSegment` for each default preset to an empty string:

    Flownative:
      Neos:
        HostBasedDefaultPreset:
          forceEmptyUriSegment: true

This way the default dimension value "disappears" from the URL. Make sure to set a segment for all values in the
"real" dimension configuration, to be able to switch on all hosts without issues!

## Credits

Development of this package has been sponsored by web&co OG, Vienna, Austria.
