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

## Credits

Development of this package has been sponsored by web&co OG, Vienna, Austria.
