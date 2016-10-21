# ExtendedMeasure bundle

[![Build Status](https://travis-ci.org/akeneo/ExtendedMeasureBundle.svg?branch=master)](https://travis-ci.org/akeneo/ExtendedMeasureBundle)

## Requirements

| ExtendedMeasureBundle | Akeneo PIM Community Edition |
|:---------------------:|:----------------------------:|
| dev-master            | v1.6.*                       |

## What's new

- `alternative_units`: one measure can be identified with multiple units, to reflect the differences existing between measures systems. It can also be used to add diferent encoding of the same caracter like `µ` which can be encoded with the 'micro' UTF8 code or the greak 'mu' UTF8 code.
 
- `unece_code`: alphanumeric identifier of the UNECE convention. Used by CNET for example (see https://www.unece.org/fileadmin/DAM/cefact/recommendations/rec20/rec20_rev3_Annex2e.pdf)

- A lot of new measures. So we had to split the configuration in multiple yaml files inside the `Resources/config/measures` directory.

## Console commands

Because the number of measures is greatly increased, we provide some console command to help keeping this configuration clean and to find informations about measures.

- `pim:measures:check`:
parse all configuration files and check for the unicity of a unit inside the same family
 
- `pim:measures:find`:
find a measure by one of its unit and return some information.
