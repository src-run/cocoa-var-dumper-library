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
use SR\Dumper\Normalizer\Runner\StringType\ClosureStringRunner;
use SR\Dumper\Normalizer\Runner\StringType\ObjectStringRunner;
use SR\Dumper\Normalizer\Runner\StringType\ResourceStringRunner;

final class StringNormalizer extends AbstractNormalizer
{
    public function __construct(NormalizerInterface ...$normalizers)
    {
        parent::__construct(...array_merge([
            new ClosureStringRunner(),
            new ResourceStringRunner(),
            new ObjectStringRunner(),
        ], $normalizers));
    }
}
