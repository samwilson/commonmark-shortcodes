<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes\Test;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\TestCase;
use Samwilson\CommonMarkShortcodes\Shortcode;
use Samwilson\CommonMarkShortcodes\ShortcodeExtension;

class ShortcodeExtensionTest extends TestCase
{
    /**
     * @dataProvider provideBasics()
     *
     * @param callable[] $shortcodeHandlers
     */
    public function testBasics(string $markdown, array $shortcodeHandlers, string $html): void
    {
        $environment = new Environment(['shortcodes' => ['shortcodes' => $shortcodeHandlers]]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new ShortcodeExtension());
        $converter = new MarkdownConverter($environment);
        $this->assertSame($html, $converter->convert($markdown)->getContent());
    }

    /**
     * @return mixed[]
     */
    public function provideBasics(): array
    {
        return [
            'simple-inline' => [
                'markdown' => 'Foo {bar} baz',
                'shortcodes' => [
                    'bar' => static function () {
                        return 'BAR';
                    },
                ],
                'output' => "<p>Foo \nBAR baz</p>\n",
            ],
            'simple-inline-params' => [
                'markdown' => 'Foo {bar a b=c d="e f"} baz',
                'shortcodes' => [
                    'bar' => static function (Shortcode $sc) {
                        return $sc->getAttr('d') . $sc->getAttr('b');
                    },
                ],
                'output' => "<p>Foo \ne fc baz</p>\n",
            ],
            'simple-block' => [
                'markdown' => "Foo\n{{{bar attr=foo\nbody here\n}}}\nbaz",
                'shortcodes' => [
                    'bar' => static function (Shortcode $sc) {
                        return '[' . $sc->getBody() . ']';
                    },
                ],
                'output' => "<p>Foo</p>\n[\nbody here]\n<p>baz</p>\n",
            ],
        ];
    }
}
