<?php

/*
 * This file is part of the `src-run/cocoa-var-dumper-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Dumper\Normalizer\Manager;

use SR\Dumper\Normalizer\NormalizerInterface;
use SR\Exception\Logic\InvalidArgumentException;

abstract class AbstractNormalizer implements NormalizerInterface, \Countable
{
    /**
     * @var NormalizerInterface[]
     */
    private $normalizers;

    /**
     * @param NormalizerInterface ...$normalizers
     */
    public function __construct(NormalizerInterface ...$normalizers)
    {
        $this->normalizers = $normalizers;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function __invoke($value)
    {
        if (!$this->supports($value)) {
            throw new InvalidArgumentException('One or more normalizers do not support input value of "%s" type.', gettype($value));
        }

        foreach ($this->normalizers as $normalizer) {
            $value = $normalizer($value);
        }

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function supports($value): bool
    {
        foreach ($this->normalizers as $normalizer) {
            if (!$normalizer->supports($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->normalizers);
    }

    /**
     * @return NormalizerInterface[]
     */
    public function getNormalizers(): array
    {
        return $this->normalizers;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return get_called_class();
    }
}
