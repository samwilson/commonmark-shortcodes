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
            $pattern = '/^\{{' . $braceCount . '}' . \preg_quote($code) . '([^ ]?)/';
            $opening = $cursor->match($pattern);
            if ($opening !== null) {
                $shortcode = new Shortcode($code);
                $shortcode->loadAttrsFromString(\substr($cursor->getLine(), \strlen($code) + $braceCount));
                $startParser = new ShortcodeBlockContinueParser($shortcode);

                return BlockStart::of($startParser)->at($cursor);
            }
        }

        return BlockStart::none();
    }
}
