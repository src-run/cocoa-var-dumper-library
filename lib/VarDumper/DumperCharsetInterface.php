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

interface DumperCharsetInterface
{
    /**
     * @var int
     */
    public const CHARSET_UTF8 = 'UTF-8';

    /**
     * @var int
     */
    public const CHARSET_DEFAULT = self::CHARSET_UTF8;
}
