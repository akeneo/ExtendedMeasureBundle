<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Mapper;

use InvalidArgumentException;

/**
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class UnresolvableMeasureCollection
{
    /** @var string */
    protected $unit;

    /** @var UnresolvableMeasure[] */
    protected $unresolvableMeasures = [];

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @return UnresolvableMeasure[]
     */
    public function getUnresolvableMeasures()
    {
        return $this->unresolvableMeasures;
    }

    /**
     * @param UnresolvableMeasure $unresolvableMeasure
     *
     * @throws InvalidArgumentException
     */
    public function addUnresolvableMeasure(UnresolvableMeasure $unresolvableMeasure)
    {
        if (null === $this->unit) {
            $this->unit = $unresolvableMeasure->getUnit();
        }

        if ($this->unit !== $unresolvableMeasure->getUnit()) {
            throw new InvalidArgumentException('A unresolvable unit collection can only manage one unit.');
        }

        $this->unresolvableMeasures[] = $unresolvableMeasure;
    }
}
