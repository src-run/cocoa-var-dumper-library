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

use SR\Utilities\IO\Buffered\Output\MemoryOutputBuffered;
use SR\Utilities\IO\Buffered\Output\OutputBufferedInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\AbstractDumper;

/**
 * @author Rob Frawley 2nd <rmf@src.run>
 */
trait DumperTrait
{
    /**
     * @var OutputBufferedInterface|null
     */
    private $buffer;

    /**
     * @var OutputInterface|null
     */
    private $output;

    /**
     * @var int[]
     */
    private $options;

    /**
     * @var string|null
     */
    private $charset;

    /**
     * @param int[] $options
     */
    public function __construct(array $options = [], string $charset = null, OutputInterface $output = null, OutputBufferedInterface $buffer = null)
    {
        $this->setOptions($options ?? DumperInterface::OPTIONS_DEFAULT);
        $this->setCharset($charset ?? DumperInterface::CHARSET_DEFAULT);
        $this->setOutput($output);
        $this->setBuffer($buffer, false);
    }

    /**
     * @return DumperTrait|DumperInterface
     */
    public static function create(int ...$options): DumperInterface
    {
        return new static($options);
    }

    public function getBuffer(bool $create = false): OutputBufferedInterface
    {
        if (true === $create) {
            $this->setBuffer(null, true);
        }

        return $this->buffer;
    }

    public function hasBuffer(): bool
    {
        return null !== $this->buffer;
    }

    /**
     * @return DumperTrait|DumperInterface
     */
    public function setBuffer(OutputBufferedInterface $buffer = null, bool $create = true): DumperInterface
    {
        $this->buffer = null === $this->buffer && ($create || null !== $buffer) ? $buffer ?? new MemoryOutputBuffered() : null;

        return $this;
    }

    public function newBuffer(): self
    {
        $this->buffer = new ($this->getBufferClassQualified())();

        return $this;
    }

    public function getOutput(): ?OutputInterface
    {
        return $this->output;
    }

    public function hasOutputs(): bool
    {
        return null !== $this->getOutput();
    }

    /**
     * @return DumperTrait|DumperInterface
     */
    public function setOutput(OutputInterface $output = null): DumperInterface
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function hasOptions(): bool
    {
        return !empty($this->getOptions());
    }

    /**
     * @return DumperTrait|DumperInterface
     */
    public function setOptions(int ...$options): DumperInterface
    {
        $this->options = $options;

        return $this;
    }

    public function getCharset(): ?string
    {
        return $this->charset;
    }

    public function hasCharset(): bool
    {
        return null !== $this->getCharset();
    }

    /**
     * @return DumperTrait|DumperInterface
     */
    public function setCharset(string $charset = null): DumperInterface
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * @param mixed $variable
     */
    public function dump($variable): ?string
    {
        $this->getSymfonyDumperInstance()->dump(
            (new VarCloner())->cloneVar($variable)
        );

        return $this->hasBuffer() ? trim($this->getBuffer()->get()) : null;
    }

    abstract protected function getSymfonyDumperInstance(): AbstractDumper;

    private function getBufferClassQualified(): string
    {
        return $this->hasBuffer() ? get_class($this->buffer) : MemoryOutputBuffered::class;
    }
}
