Samwilson/CommonMarkShortcodes
==============================

An extension to [League/CommonMark](https://commonmark.thephpleague.com)
for adding 'shortcodes' to Markdown.

![Packagist Version](https://img.shields.io/packagist/v/samwilson/commonmark-shortcodes)
![Packagist License](https://img.shields.io/packagist/l/samwilson/commonmark-shortcodes)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/samwilson/commonmark-shortcodes/ci.yml?branch=main)

Shortcodes are bits of text in a Markdown document
that are delimited with one or three braces
and are replaced with whatever content is needed.
There are two types of shortcode, inline and block
(the same name cannot be used for both an inline and block shortcode;
i.e. you must decide when designing the shortcodes how each is to be used).

Inline shortcodes have one brace, `{name|attr=val}`, and appear within a paragraph or list item etc. For example, here `cite` is the name of the shortcode and it's got one attribute:

```
There are over 300 distinct breeds{cite|Kris2008} of goat.
```

Block shortcodes have three braces, `{{{name|attr=val}}}`, and an optional body;
their attributes are the same as for inline shortcodes.
Here `quotation` is the shortcode, it's got one attribute, and a two-line body:

```
{{{quotation|source=Q2934
The goat is a member of the animal family Bovidae and the tribe Caprini,
meaning it is closely related to the sheep.
}}}
```

Shortcodes can have zero or more attributes.
Attributes are separated by pipes `|`
and can be given a name by adding the equals sign
(without a name, they're numbered from 1).

When compiled, the shortcodes are replaced by the HTML (or other content)
that is specified in the shortcode handlers.
See [#Usage](#Usage) below for more information
about how to set up the CommonMark environment.

## Installation

Install with [Composer](https://getcomposer.org/):

```
$ composer require samwilson/commonmark-shortcodes
```

## Usage

Set up your CommonMark Environment object with a `shortcodes` configuration key:

```php
$environment = new Environment([
    'shortcodes' => [
        'shortcodes' => [
            'myshortcode' => /* callback one */
            'myshortcode2' => /* callback two */
        ],
    ],
]);
$environment->addExtension(new CommonMarkCoreExtension());
```

Then add the `ShortcodeExtension`, and start converting Markdown:

```php
$environment->addExtension(new ShortcodeExtension());
$converter = new MarkdownConverter($environment);
echo $converter->convert('Markdown *goes here*.')->getContent();
```

The callbacks are where you define your shortcodes' output.
Each takes a single parameter (a `Shortcode` object)
and returns a string that will be inserted into the output in place of the shortcode.

For example, Markdown like this:

```
Lorem {smallcaps|ipsum}.
```

could be paired with the following shortcode configuration:

```php
[
    'smallcaps' => function (Shortcode $shortcode) {
        return '<span style="font-variant:small-caps">'
            . $shortcode->getAttr(1)
            . '</span>';
    },
]
```

to produce this output:

```html
<p>Lorem <span style="font-variant:small-caps">ipsum</span></p>
```

See the [examples/ directory](/examples/) for functioning examples,
and if you have any issues please do
[report them](https://github.com/samwilson/commonmark-shortcodes/issues).

## License

Copyright © 2023 Sam Wilson https://samwilson.id.au/

This program is free software: you can redistribute it and/or modify it under the terms of
the GNU General Public License as published by the Free Software Foundation,
either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.
If not, see https://www.gnu.org/licenses/
