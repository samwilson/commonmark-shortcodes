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
        // Sort shortcodes by length, so they match from longest to shortest in the regex.
        $sortedShortcodeNames = \array_keys($this->shortcodes);
        \usort($sortedShortcodeNames, static function ($a, $b) {
            return \strlen($b) - \strlen($a);
        });

        return InlineParserMatch::join(
            InlineParserMatch::string('{'),
            InlineParserMatch::oneOf(...$sortedShortcodeNames),
            // Match either two backslashes, a backslash-brace, or anything that's not a brace.
            InlineParserMatch::regex('(?:\\\\\\\\|\\\\}|[^}])*'),
            InlineParserMatch::string('}')
        );
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $shortcode = new Shortcode($inlineContext->getMatches()[2]);
        $shortcode->loadAttrsFromString($inlineContext->getMatches()[3]);
        $inlineContext->getCursor()->advanceBy($inlineContext->getFullMatchLength());
        $inlineContext->getContainer()->appendChild(new ShortcodeInline($shortcode));

        return true;
    }
}
