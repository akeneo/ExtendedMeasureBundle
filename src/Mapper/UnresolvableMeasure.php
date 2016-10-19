<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Mapper;

/**
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class UnresolvableMeasure
{
    /** @var string */
    protected $pimFamily;

    /** @var string */
    protected $pimMeasure;

    /** @var string */
    protected $unit;

    /**
     * @param string $pimFamily
     * @param string $pimMeasure
     * @param string $unit
     */
    public function __construct($pimFamily, $pimMeasure, $unit)
    {
        $this->pimFamily = $pimFamily;
        $this->pimMeasure = $pimMeasure;
        $this->unit = $unit;
    }

    /**
     * @return string
     */
    public function getPimFamily()
    {
        return $this->pimFamily;
    }

    /**
     * @return string
     */
    public function getPimMeasure()
    {
        return $this->pimMeasure;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('Family = "%s", Measure = "%s", Unit = "%s"', $this->pimFamily, $this->pimMeasure, $this->unit);
    }
}
