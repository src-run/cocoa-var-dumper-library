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
use SR\Dumper\Normalizer\Runner\NullType\NullRunner;

final class NullNormalizer extends AbstractNormalizer
{
    /**
     * @param NormalizerInterface ...$normalizers
     */
    public function __construct(NormalizerInterface ...$normalizers)
    {
        parent::__construct(...array_merge([
            new NullRunner(),
        ], $normalizers));
    }
}
