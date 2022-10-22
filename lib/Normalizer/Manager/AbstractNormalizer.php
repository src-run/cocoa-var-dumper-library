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

abstract class AbstractNormalizer implements NormalizerInterface, \Countable
{
    /**
     * @return NormalizerInterface[]
     */
    private array $normalizers;

    public function __construct(NormalizerInterface ...$normalizers)
    {
        $this->normalizers = $normalizers;
    }

    public function __invoke(mixed $value): mixed
    {
        if (!$this->supports($value)) {
            throw new \InvalidArgumentException(sprintf('One or more normalizers do not support input value of "%s" type.', gettype($value)));
        }

        foreach ($this->normalizers as $normalizer) {
            $value = $normalizer($value);
        }

        return $value;
    }

    public function supports(mixed $value): bool
    {
        foreach ($this->normalizers as $normalizer) {
            if (!$normalizer->supports($value)) {
                return false;
            }
        }

        return true;
    }

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

    public function type(): string
    {
        return static::class;
    }
}
