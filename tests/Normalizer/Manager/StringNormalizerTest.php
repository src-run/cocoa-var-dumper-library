<?php

/*
 * This file is part of the `src-run/cocoa-var-dumper-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Dumper\Tests\Normalizer\Manager;

use PHPUnit\Framework\TestCase;
use SR\Dumper\Normalizer\Manager\StringNormalizer;
use SR\Dumper\Normalizer\NormalizerInterface;
use SR\Dumper\Normalizer\Runner\AbstractRunner;
use SR\Dumper\Transformer\StringTransformer;

/**
 * @covers \SR\Dumper\Normalizer\Manager\AbstractNormalizer
 * @covers \SR\Dumper\Normalizer\Manager\StringNormalizer
 * @covers \SR\Dumper\Normalizer\Runner\AbstractRunner
 * @covers \SR\Dumper\Normalizer\Runner\StringType\ClosureStringRunner
 * @covers \SR\Dumper\Normalizer\Runner\StringType\ObjectStringRunner
 * @covers \SR\Dumper\Normalizer\Runner\StringType\ResourceStringRunner
 * @covers \SR\Dumper\Normalizer\Runner\StringType\StringRunnerTrait
 */
class StringNormalizerTest extends TestCase
{
    /**
     * @dataProvider provideNormalizerData
     *
     * @param string     $expected
     * @param mixed|null $value
     */
    public function testNormalizer(string $expected, $value = null): void
    {
        $this->assertStringMatchesFormat($expected, (new StringTransformer(new StringNormalizer()))($value));
    }

    /**
     * @return \Iterator
     */
    public static function provideNormalizerData(): \Iterator
    {
        $closedResource = fopen('php://memory', 'r+');
        fclose($closedResource);

        yield ['a string value', 'a string value'];
        yield ['100', 100];
        yield ['100.0', 100.00];
        yield ['100.5', 100.50];
        yield ['100.333', 100.333];
        yield ['null', null];
        yield ['true', true];
        yield ['false', false];
        yield ['closure {#%d @ %s:%d-%d}', function () {}];
        yield ['@anonymous {#%d}', new class() {
        }];
        yield [sprintf('%s {#%%d}', __CLASS__), new static()];
        yield ['closed resource {#%d}', $closedResource];
        yield ['stream resource {#%d @ php://memory (memory)}', fopen('php://memory', 'r+')];
        yield ['stream resource {#%d @ %s (stdio)}', fopen(__FILE__, 'r+')];
        yield ['[ ] (0)', []];
        yield ['[ "a" => (string) "a string value", "b" => (int) "100", "c" => (float) "33.333", "d" => (null) "null", "e" => (bool) "true", "f" => (bool) "false" ] (6)', ['a' => 'a string value', 'b' => 100, 'c' => 33.333, 'd' => null, 'e' => true, 'f' => false]];
        yield [sprintf('[ "anonymous-function" => (object) "closure {#%%d @ %%s:%%d-%%d}", "anonymous-object" => (object) "@anonymous {#%%d}", "castable-object" => (object) "spl-object-hash:%%s", "defined-object" => (object) "%s {#%%d}", "open-stdio-resource" => (resource) "stream resource {#%%d @ %%s.php (stdio)}", "open-memory-resource" => (resource) "stream resource {#%%d @ php://memory (memory)}", "closed-resource" => (resource) "closed resource {#%%d}" ] (7)', __CLASS__), [
            'anonymous-function' => function () {},
            'anonymous-object' => new class() {
            },
            'castable-object' => new class() {
                public function __toString()
                {
                    return sprintf('spl-object-hash:%s', spl_object_hash($this));
                }
            },
            'defined-object' => new static(),
            'open-stdio-resource' => fopen(__FILE__, 'r+'),
            'open-memory-resource' => fopen('php://memory', 'r+'),
            'closed-resource' => $closedResource,
        ]];
    }

    public function testEmptyClosureNormalizer(): void
    {
        $this->assertStringMatchesFormat('closure {#391}', (new StringTransformer(new StringNormalizer()))('Closure {@391}'));
    }

    public function testGetType(): void
    {
        $this->assertSame(StringNormalizer::class, (new StringNormalizer())->type());
    }

    /**
     * @return \Iterator
     */
    public function provideSupportsData(): \Iterator
    {
        yield [true, 'string'];
        yield [false, 10];
        yield [false, new \stdClass()];
    }

    /**
     * @dataProvider provideSupportsData
     *
     * @param bool  $expected
     * @param mixed $provided
     */
    public function testSupports(bool $expected, $provided): void
    {
        $this->assertSame($expected, (new StringNormalizer())->supports($provided));
    }

    public function testCount(): void
    {
        $this->assertSame(3, (new StringNormalizer())->count());
    }

    public function testGetNormalizers(): void
    {
        $ns = (new StringNormalizer())->getNormalizers();

        $this->assertCount(3, $ns);

        foreach ($ns as $n) {
            $this->assertInstanceOf(NormalizerInterface::class, $n);
        }
    }

    public function testInvokeThrowsOnInvalidNormalizer(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new StringNormalizer(new class() extends AbstractRunner {
            /**
             * @param mixed $value
             *
             * @return bool
             */
            public function supports($value): bool
            {
                return false;
            }

            /**
             * @param string $value
             *
             * @return string
             */
            protected function normalize($value)
            {
                return $value;
            }
        }))('foobar');
    }
}
