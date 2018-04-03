<?php

/*
 * This file is part of the `src-run/cocoa-var-dumper-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Dumper\Normalizer\Runner\StringType;

use SR\Dumper\Normalizer\Runner\AbstractRunner;

class ObjectStringRunner extends AbstractRunner
{
    use StringRunnerTrait;

    /**
     * @param string $value
     *
     * @return string
     */
    protected function normalize($value): string
    {
        $this->replaceValueWithActionResultOnAllMatches(function (array $matchedValues) {
            return $this->normalizeObject($matchedValues);
        }, '{(?<type>[^\s]+) \{(#|@)(?<id>[0-9]+) (?<more>[^@]{1}[^\}]+)\}}', $value);

        return $value;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function normalizeObject(array $data): string
    {
        return sprintf('%s {#%d}', $data['type'], $data['id']);
    }
}
