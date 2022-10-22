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

use SR\Output\Buffered\MemoryOutputBuffered;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\AbstractDumper;
use Symfony\Component\VarDumper\Dumper\CliDumper;

/**
 * @author Rob Frawley 2nd <rmf@src.run>
 */
final class HtmlDumper
{
    /**
     * @var int
     */
    private $flags;

    /**
     * @var string|null
     */
    private $charset;

    public function __construct(int $flags = null, string $charset = null)
    {
        $this->flags = $flags ?: AbstractDumper::DUMP_STRING_LENGTH | AbstractDumper::DUMP_LIGHT_ARRAY | AbstractDumper::DUMP_COMMA_SEPARATOR;
        $this->charset = $charset;
    }

    /**
     * @param mixed $value
     */
    public function dump($value): string
    {
        $buffer = new MemoryOutputBuffered();

        (new CliDumper(function (string $line) use ($buffer): void {
            $buffer->add(sprintf(' %s', $line));
        }, $this->charset, $this->flags))->dump((new VarCloner())->cloneVar($value));

        return trim($buffer->get());
    }
}
