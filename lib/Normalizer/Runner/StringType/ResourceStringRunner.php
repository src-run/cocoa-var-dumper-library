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

class ResourceStringRunner extends AbstractRunner
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
            return $this->normalizeResource($matchedValues);
        }, '{(?<type>[^\s]+ resource) \{@(?<id>[0-9]+)(?<more>[^\}]+)\}}', $value);

        $this->replaceValueWithActionResultOnAllMatches(function (array $matchedValues) {
            return $this->normalizeResource($matchedValues);
        }, '{(?<type>[^\s]+ resource) @(?<id>[0-9]+)}', $value);

        return $value;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function normalizeResource(array $data): string
    {
        return $this->returnMatchedOrGenericActionResult(function () use ($data) {
            return sprintf('%s {#%d}', mb_strtolower($data['type']), $data['id']);
        }, function (array $attr) use ($data) {
            return sprintf('%s {#%d @ %s (%s)}', mb_strtolower($data['type']), $data['id'], $this->tryPathResolve($attr['uri']), mb_strtolower($attr['stream']));
        }, '{stream_type:[^"]+"(?<stream>[^"]+)"[^\}]+?uri:[^"]+"(?<uri>[^"]+)"}', $data['more'] ?? null);
    }
}
