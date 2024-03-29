<?php

/*
 * This file is part of the `src-run/cocoa-var-dumper-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Dumper\Normalizer\Runner;

use SR\Dumper\Normalizer\NormalizerInterface;

abstract class AbstractRunner implements NormalizerInterface
{
    public function __invoke(mixed $value): mixed
    {
        return $this->supports($value) ? $this->normalize($value) : $value;
    }

    public function type(): string
    {
        return static::class;
    }

    abstract protected function normalize(mixed $value): mixed;
}
