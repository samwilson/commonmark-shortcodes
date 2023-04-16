<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes;

use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

final class ShortcodeInlineParser implements InlineParserInterface
{
    /** @var mixed[] */
    private array $shortcodes;

    /**
     * @param Shortcode[] $shortcodes
     */
    public function __construct(array $shortcodes)
    {
        $this->shortcodes = $shortcodes;
    }

    public function getMatchDefinition(): InlineParserMatch
    {
        foreach (\array_keys($this->shortcodes) as $code) {
            return InlineParserMatch::join(
                InlineParserMatch::string('{' . $code),
                InlineParserMatch::regex('.*?\}')
            );
        }
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $shortcode = new Shortcode(\substr($inlineContext->getMatches()[1], 1));
        $shortcode->loadAttrsFromString(\substr($inlineContext->getMatches()[2], 0, -1));
        // $doc = new DOMDocument();
        // $doc->loadHTML('<body ' . \substr($inlineContext->getMatches()[2], 0, -1) . ' />');
        // $body      = $doc->getElementsByTagName('body')[0];
        // for ($i = 0; $i < $body->attributes->length; ++$i) {
        //     $name  = $body->attributes->item($i)->name;
        //     $value = $body->getAttribute($name);
        //     $shortcode->setAttr($name, $value);
        // }
        $inlineContext->getCursor()->advanceBy($inlineContext->getFullMatchLength());
        $inlineContext->getContainer()->appendChild($shortcode);

        return true;
    }

    // /**
    //  * @inheritdoc
    //  */
    // public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart {

    //     $opening = $cursor->match('/^\{([^ ]+)/');
    //         dump(array_keys($this->shortcodes), $opening, $cursor);
    //     if ($opening === null) {
    //         return BlockStart::none();
    //     }
    //     return BlockStart::of(new ShortcodeParser(substr($opening, 1)))->at($cursor);
    // }
}
