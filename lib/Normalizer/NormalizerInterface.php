<?php

/*
 * This file is part of the `src-run/cocoa-var-dumper-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Dumper\Normalizer;

interface NormalizerInterface
{
    public function __invoke(mixed $value): mixed;

    public function supports(mixed $value): bool;

    public function type(): string;
}
