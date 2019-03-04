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
    /**
     * @param int ...$options
     *
     * @return self
     */
    public static function create(int ...$options): self;

    /**
     * @return OutputBufferedInterface
     */
    public function getBuffer(): OutputBufferedInterface;

    /**
     * @return bool
     */
    public function hasBuffer(): bool;

    /**
     * @param OutputBufferedInterface|null $buffer
     * @param bool                         $create
     *
     * @return self
     */
    public function setBuffer(OutputBufferedInterface $buffer = null, bool $create = true): self;

    /**
     * @return OutputInterface|null
     */
    public function getOutput(): ?OutputInterface;

    /**
     * @return bool
     */
    public function hasOutput(): bool;

    /**
     * @param OutputInterface|null $output
     *
     * @return self
     */
    public function setOutput(OutputInterface $output = null): self;

    /**
     * @return int[]
     */
    public function getOptions(): array;

    /**
     * @return bool
     */
    public function hasOptions(): bool;

    /**
     * @param int ...$options
     *
     * @return self
     */
    public function setOptions(int ...$options): self;

    /**
     * @return string|null
     */
    public function getCharset(): ?string;

    /**
     * @return bool
     */
    public function hasCharset(): bool;

    /**
     * @param string|null $charset
     *
     * @return self
     */
    public function setCharset(string $charset = null): self;

    /**
     * @param mixed $variable
     *
     * @return string|null
     */
    public function dump($variable): ?string;
}
