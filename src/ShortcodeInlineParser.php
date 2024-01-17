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
     * @param ShortcodeInline[] $shortcodes
     */
    public function __construct(array $shortcodes)
    {
        $this->shortcodes = $shortcodes;
    }

    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::join(
            InlineParserMatch::string('{'),
            InlineParserMatch::oneOf(...\array_keys($this->shortcodes)),
            InlineParserMatch::regex('.*?'),
            InlineParserMatch::string('}')
        );
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $shortcode = new ShortcodeInline($inlineContext->getMatches()[2]);
        $shortcode->loadAttrsFromString($inlineContext->getMatches()[3]);
        $inlineContext->getCursor()->advanceBy($inlineContext->getFullMatchLength());
        $inlineContext->getContainer()->appendChild($shortcode);

        return true;
    }
}
