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

use SR\Utilities\IO\Buffered\Output\OutputBufferedInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Rob Frawley 2nd <rmf@src.run>
 */
interface DumperInterface extends DumperOptionsInterface, DumperCharsetInterface
{
    public static function create(int ...$options): self;

    public function getBuffer(): OutputBufferedInterface;

    public function hasBuffer(): bool;

    public function setBuffer(OutputBufferedInterface $buffer = null, bool $create = true): self;

    public function getOutput(): ?OutputInterface;

    public function hasOutput(): bool;

    public function setOutput(OutputInterface $output = null): self;

    /**
     * @return int[]
     */
    public function getOptions(): array;

    public function hasOptions(): bool;

    public function setOptions(int ...$options): self;

    public function getCharset(): ?string;

    public function hasCharset(): bool;

    public function setCharset(string $charset = null): self;

    /**
     * @param mixed $variable
     */
    public function dump($variable): ?string;
}
