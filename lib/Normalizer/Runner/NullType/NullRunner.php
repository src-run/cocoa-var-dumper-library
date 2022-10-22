<?php

/*
 * This file is part of the `src-run/cocoa-var-dumper-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Dumper\Normalizer\Runner\NullType;

use SR\Dumper\Normalizer\Runner\AbstractRunner;

class NullRunner extends AbstractRunner
{
    /**
     * @param mixed $value
     */
    public function supports($value): bool
    {
        return true;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function normalize($value)
    {
        return $value;
    }
}
