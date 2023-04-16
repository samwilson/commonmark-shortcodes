<?php

require dirname( __DIR__ ) . '/vendor/autoload.php';

use League\CommonMark\MarkdownConverter;
use Samwilson\CommonMarkShortcodes\Shortcode;
use League\CommonMark\Environment\Environment;
use Samwilson\CommonMarkShortcodes\ShortcodeExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;

$environment = new Environment([
    'shortcodes' => [
        'shortcodes' => [
            'inline-shortcode' => function ( Shortcode $shortcode ) {
                return 'Inline shortcode (with ' . count( $shortcode->getAttrs() ) . ' params)';
            },
            'block-shortcode' => function ( Shortcode $shortcode ) {
                return 'Block-level shortcode (with ' . count( $shortcode->getAttrs() ) . ' params):'
                    .'<pre>' . $shortcode->getBody() . '</pre>';
            },
            'quote' => function ( Shortcode $shortcode ) {
                return '<blockquote>' . $shortcode->getBody() . '<cite>' . $shortcode->getAttr('cite') . '</cite></blockquote>';
            },
        ],
    ],
]);
$environment->addExtension(new CommonMarkCoreExtension());
$environment->addExtension(new ShortcodeExtension());
$converter = new MarkdownConverter($environment);
echo $converter->convert("
*Markdown* content with {inline-shortcode foo bar='' baz=3} and…

{{{block-shortcode name=test unnamed-attr
Lorem ipsum content.

more
}}}

Postipsum.

{{{quote cite='Example'
Lorem ipsum.
}}}
")->getContent();
