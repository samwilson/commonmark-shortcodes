<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use Samwilson\CommonMarkShortcodes\Shortcode;
use Samwilson\CommonMarkShortcodes\ShortcodeExtension;

$environment = new Environment([
    'shortcodes' => [
        'shortcodes' => [
            'inline-shortcode' => static function (Shortcode $shortcode) {
                return 'Inline shortcode (with ' . count($shortcode->getAttrs()) . ' params)';
            },
            'block-shortcode' => static function (Shortcode $shortcode) {
                return 'Block-level shortcode (with ' . count($shortcode->getAttrs()) . ' params):'
                    . '<pre>' . $shortcode->getBody() . '</pre>';
            },
            'quote' => static function (Shortcode $shortcode) {
                return '<blockquote>' . $shortcode->getBody() . '<cite>' . $shortcode->getAttr('cite') . '</cite></blockquote>';
            },
        ],
    ],
]);
$environment->addExtension(new CommonMarkCoreExtension());
$environment->addExtension(new ShortcodeExtension());
$converter = new MarkdownConverter($environment);
echo $converter->convert("
*Markdown* content with {inline-shortcode|foo|bar=''|baz=3} and…

{{{block-shortcode | name=test | unnamed-attr
Lorem ipsum content.

more
}}}

Postipsum.
")->getContent();

/* Output:

<p><em>Markdown</em> content with Inline shortcode (with 3 params) and…</p>
Block-level shortcode (with 3 params):<pre>
Lorem ipsum content.

more</pre>
<p>Postipsum.</p>

*/
