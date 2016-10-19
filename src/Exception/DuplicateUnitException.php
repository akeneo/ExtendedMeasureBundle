<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Exception;

/**
 * Exception thown when checking the unicity of a unit inside a family.
 *
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class DuplicateUnitException extends \RuntimeException
{
    protected $message = 'A unit cannot belong to more than one measure in the same family.';
}
