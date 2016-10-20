# ExtendedMeasure bundle

## Requirements

| ExtendedMeasureBundle | Akeneo PIM Community Edition |
|:---------------------:|:----------------------------:|
| dev-master            | v1.6.*                       |

## What's new

- `alternative_units`: one measure can be identified with multiple units, to reflect the differences existing between measures systems. It can also be used to add diferent encoding of the same caracter like `µ` which can be encoded with the 'micro' UTF8 code or the greak 'mu' UTF8 code.
 
- `unece_code`: alphanumeric identifier of the UNECE convention. Used by CNET for example (see https://www.unece.org/fileadmin/DAM/cefact/recommendations/rec20/rec20_rev3_Annex2e.pdf)

- New command: `pim:measures:check`
 
- New command: `pim:measures:find`
