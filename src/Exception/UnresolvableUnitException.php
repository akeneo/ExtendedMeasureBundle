<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Exception;

use Exception;
use Pim\Bundle\ExtendedMeasureBundle\Mapper\UnresolvableMeasureCollection;

/**
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class UnresolvableUnitException extends \RuntimeException
{
    /** @var string */
    protected $message = 'Unable to resolve the unit "%s".';

    /** @var UnresolvableMeasureCollection */
    protected $unresolvableMeasureCollection;

    public function __construct(
        UnresolvableMeasureCollection $unresolvableMeasures,
        $message = '',
        $code = 0,
        Exception $previous = null
    ) {
        $this->unresolvableMeasureCollection = $unresolvableMeasures;
        if ('' === $message) {
            $message = sprintf($this->message, $unresolvableMeasures->getUnit());
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return UnresolvableMeasureCollection
     */
    public function getUnresolvableMeasures()
    {
        return $this->unresolvableMeasureCollection;
    }
}
