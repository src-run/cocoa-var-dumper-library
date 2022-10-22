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

class ClosureStringRunner extends AbstractRunner
{
    use StringRunnerTrait;

    protected function normalize(mixed $value): string
    {
        $this->replaceValueWithActionResultOnAllMatches(function (array $matchedValues) {
            return $this->normalizeClosure($matchedValues);
        }, '{Closure(?:\(\))? \{(#|@)(?<id>[0-9]+)(?<more>[^\}]+)?\}}', $value);

        return $value;
    }

    private function normalizeClosure(array $data): string
    {
        return $this->returnMatchedOrGenericActionResult(function () use ($data) {
            return sprintf('closure {#%d}', $data['id']);
        }, function (array $attr) use ($data) {
            return sprintf('closure {#%d @ %s:%d-%d}', $data['id'], $this->tryPathResolve($attr['file']), $attr['line_s'], $attr['line_e']);
        }, '{file:[^"]+"(?<file>[^"]+)" line:[^"]+"(?<line_s>[0-9]+) to (?<line_e>[0-9]+)"}', $data['more'] ?? null);
    }
}
