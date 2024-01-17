<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes\Test;

use PHPUnit\Framework\TestCase;
use Samwilson\CommonMarkShortcodes\ShortcodeBlock;

class ShortcodeTest extends TestCase
{
    /**
     * @dataProvider provideLoadAttrsFromString()
     *
     * @param string[] $expected
     */
    public function testLoadAttrsFromString(string $name, string $string, array $expected): void
    {
        $shortcode = new ShortcodeBlock($name);
        $shortcode->loadAttrsFromString($string);
        $this->assertSame($expected, $shortcode->getAttrs());
    }

    /**
     * @return mixed[]
     */
    public function provideLoadAttrsFromString(): array
    {
        return [
            ['x', 'foo', [1 => 'foo']],
            ['x', '', []],
            ['ref', 'name = Foo| bar', ['name' => 'Foo', 1 => 'bar']],
            ['lorem', 'x=a\|b', ['x' => 'a|b']],
        ];
    }
}
