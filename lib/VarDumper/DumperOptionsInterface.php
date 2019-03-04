<?php

/*
 * This file is part of the `src-run/cocoa-var-dumper-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Dumper\VarDumper;

use Symfony\Component\VarDumper\Dumper\AbstractDumper;

interface DumperOptionsInterface
{
    /**
     * @var int
     */
    public const OPTIONS_LIGHT_ARRAY = AbstractDumper::DUMP_LIGHT_ARRAY;

    /**
     * @var int
     */
    public const OPTIONS_STRING_LENGTH = AbstractDumper::DUMP_STRING_LENGTH;

    /**
     * @var int
     */
    public const OPTIONS_COMMA_SEPARATOR = AbstractDumper::DUMP_COMMA_SEPARATOR;

    /**
     * @var int
     */
    public const OPTIONS_TRAILING_COMMA = AbstractDumper::DUMP_TRAILING_COMMA;

    /**
     * @var int[]
     */
    public const OPTIONS_DEFAULT = [
        self::OPTIONS_LIGHT_ARRAY,
        self::OPTIONS_STRING_LENGTH,
        self::OPTIONS_COMMA_SEPARATOR,
    ];
}
