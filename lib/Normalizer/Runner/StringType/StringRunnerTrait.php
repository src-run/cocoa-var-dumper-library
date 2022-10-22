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

/**
 * @internal
 */
trait StringRunnerTrait
{
    /**
     * {@inheritdoc}
     */
    public function supports($value): bool
    {
        return is_string($value);
    }

    private function replaceValueWithActionResultOnAllMatches(\Closure $action, string $regexp, string &$value): void
    {
        if (false !== preg_match_all($regexp, $value, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $value = str_replace($m[0], $action($m), $value);
            }
        }
    }

    private function returnMatchedOrGenericActionResult(\Closure $generalAction, \Closure $matchAction, string $regexp, string $search = null): string
    {
        if (null !== $search && 1 === preg_match($regexp, $search, $m)) {
            return $matchAction($m);
        }

        return $generalAction();
    }

    private function tryPathResolve(string $path): string
    {
        return realpath($path) ?: $path;
    }
}
