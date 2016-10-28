<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Repository;

use Pim\Bundle\ExtendedMeasureBundle\Exception\UnknownUnitException;
use Pim\Bundle\ExtendedMeasureBundle\Exception\UnresolvableUnitException;

/**
 * Resolve a measure to a a PIM unit by its symbol.
 *
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class MeasureRepository implements MeasureRepositoryInterface
{
    /**
     * Dictionnary indexed by units
     *
     * @var array
     */
    private $unitsDictionnary = [];

    /**
     * Dictionnary indexed by symbols
     *
     * @var array
     */
    private $symbolsDictionnay = [];

    /** @var array */
    private $pimConfig;

    /**
     * @param array $pimConfig
     */
    public function __construct(array $pimConfig)
    {
        $this->pimConfig = $pimConfig;
        $this->buildDictionnaries($pimConfig['measures_config']);
    }

    /**
     * {@inheritdoc}
     */
    public function findBySymbol($symbol, $family = null)
    {
        return $this->findInDictionnary($this->symbolsDictionnay, $symbol, $family);
    }

    /**
     * {@inheritdoc}
     */
    public function findByUnit($unit, $family = null)
    {
        return $this->findInDictionnary($this->unitsDictionnary, $unit, $family);
    }

    /**
     * Find a unit in internal dictionnaries. We can add a filter on family
     *
     * @param array       $dictionnary
     * @param string      $search
     * @param string|null $family
     *
     * @return mixed
     */
    private function findInDictionnary(array $dictionnary, $search, $family)
    {
        if (!array_key_exists($search, $dictionnary)) {
            throw new UnknownUnitException($search);
        }
        if (count($dictionnary[$search]) === 1) {
            return $dictionnary[$search][0];
        }
        $message = sprintf('Unable to resolve the unit "%s" in', $search);
        if (null === $family) {
            foreach ($dictionnary[$search] as $unresolvables) {
                $message .= sprintf(' [family: %s, unit: %s]', $unresolvables['family'], $unresolvables['unit']);
            }
            throw new UnresolvableUnitException($message);
        }

        $foundConfig = null;
        $foundCount = 0;
        foreach ($dictionnary[$search] as $unitConfig) {
            $message .= sprintf(' [family: %s, unit: %s]', $unitConfig['family'], $unitConfig['unit']);
            if ($unitConfig['family'] === $family) {
                $foundConfig = $unitConfig;
                ++$foundCount;
            }
        }

        if ($foundCount > 1) {
            throw new UnresolvableUnitException($message);
        }

        return $foundConfig;
    }

    /**
     * Build units and symbls dictionnaries to optimize search
     *
     * @param array $measuresConfig
     */
    private function buildDictionnaries($measuresConfig)
    {
        foreach ($measuresConfig as $pimFamily => $units) {
            foreach ($units['units'] as $pimUnit => $unitConfig) {
                $unitConfig['family'] = $pimFamily;
                $unitConfig['unit'] = $pimUnit;
                $this->unitsDictionnary[$pimUnit][] = $unitConfig;
                $this->resolveUnit($unitConfig);
            }
        }
    }

    /**
     * Resolve one unit definition with its keys:
     *      CUBIC_MILLIMETER:
     *          unece_code: 'MMQ'
     *          convert: [{'mul': 0.000000001}]
     *          symbol: 'mm³'
     *          name: 'cubic millimeter'
     *          alternative_symbols: ['foo', 'bar']
     *
     * @param array $unitConfig
     */
    private function resolveUnit(array $unitConfig)
    {
        $this->symbolsDictionnay[$unitConfig['symbol']][] = $unitConfig;
        if (isset($unitConfig['unece_code'])) {
            $this->symbolsDictionnay[$unitConfig['unece_code']][] = $unitConfig;
        }
        if (isset($unitConfig['alternative_symbols'])) {
            foreach ($unitConfig['alternative_symbols'] as $alternativeSymbol) {
                // process UTF8 entities. Using json_decode is a bit hacky, but it is the simplest way
                if (strpos($alternativeSymbol, '\u') === 0) {
                    $alternativeSymbol = json_decode('"' . $alternativeSymbol . '"');
                }
                $this->symbolsDictionnay[$alternativeSymbol][] = $unitConfig;
            }
        }
    }
}
