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
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function __invoke($value);

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function supports($value): bool;

    /**
     * @return string
     */
    public function type(): string;
}
