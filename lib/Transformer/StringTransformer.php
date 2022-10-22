<?php

/*
 * This file is part of the `src-run/cocoa-var-dumper-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Dumper\Transformer;

use SR\Dumper\Normalizer\Manager\StringNormalizer;
use SR\Dumper\Normalizer\NormalizerInterface;
use SR\Dumper\VarDumper\ReturnedCliDumper;

/**
 * @author Rob Frawley 2nd <rmf@src.run>
 */
final class StringTransformer
{
    private NormalizerInterface $normalizer;

    public function __construct(NormalizerInterface $normalizer = null)
    {
        $this->normalizer = $normalizer ?? new StringNormalizer();
    }

    public function __invoke(mixed $value): string
    {
        return ($this->normalizer)(self::stringify($value));
    }

    private static function stringify(mixed $value): string
    {
        if (
            (is_string($value) || is_int($value)) ||
            ((is_string($value) || is_object($value)) && !is_array($value) && method_exists($value, '__toString') && is_callable([$value, '__toString']))
        ) {
            return (string) $value;
        }

        if (is_array($value)) {
            return self::stringifyArray($value);
        }

        return self::stringifyComplex($value);
    }

    private static function stringifyArray(array $array): string
    {
        array_walk($array, function (&$v, $i) {
            $s = self::stringify($v);
            $v = sprintf('"%s" => (%s) "%s"', $i, self::shortInternalType($v, $s), $s);
        });

        return 0 === count($array)
            ? '[ ] (0)'
            : sprintf('[ %s ] (%d)', implode(', ', $array), count($array));
    }

    private static function stringifyComplex(mixed $value): string
    {
        return (new ReturnedCliDumper())->dump($value);
    }

    private static function shortInternalType(mixed $value, string $string): string
    {
        if (is_bool($value)) {
            return 'bool';
        }

        if (is_int($value)) {
            return 'int';
        }

        if (is_float($value)) {
            return 'float';
        }

        if (null === $value) {
            return 'null';
        }

        return self::normalizeInternalType($value, $string);
    }

    private static function normalizeInternalType(mixed $value, string $string): string
    {
        if (!is_string($value) && 0 === mb_strpos($string, 'Closed resource @')) {
            return 'resource';
        }

        return gettype($value);
    }
}
