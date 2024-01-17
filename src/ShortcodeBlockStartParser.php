<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes;

use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;

final class ShortcodeBlockStartParser implements BlockStartParserInterface
{
    /** @var mixed[] */
    private array $shortcodeHandlers;

    /**
     * @param callable[] $shortcodeHandlers
     */
    public function __construct(array $shortcodeHandlers)
    {
        $this->shortcodeHandlers = $shortcodeHandlers;
    }

    public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
    {
        $braceCount = 3;
        foreach (\array_keys($this->shortcodeHandlers) as $code) {
            $pattern = '/^\{{' . $braceCount . '}' . \preg_quote($code) . '([^ \}]?)(\}{' . $braceCount . '})?\s*/';
            $opening = $cursor->match($pattern);
            if ($opening === null) {
                continue;
            }

            $shortcode = new ShortcodeBlock($code);
            // If the block is closed on the same line as the attributes, strip the trailing braces.
            $attrsString = \substr(\trim($cursor->getLine()), \strlen($code) + $braceCount);
            $isClosed    = \substr($attrsString, -$braceCount) === \str_repeat('}', $braceCount);
            if ($isClosed) {
                $attrsString = \substr($attrsString, 0, -$braceCount);
            }

            $shortcode->loadAttrsFromString($attrsString);
            $startParser = new ShortcodeBlockContinueParser($shortcode, $isClosed);

            return BlockStart::of($startParser)->at($cursor);
        }

        return BlockStart::none();
    }
}
