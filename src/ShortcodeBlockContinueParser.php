<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes;

use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Cursor;

final class ShortcodeBlockContinueParser extends AbstractBlockContinueParser
{
    private Shortcode $shortcode;

    private bool $isClosed;

    public function __construct(Shortcode $shortcode, bool $isClosed)
    {
        $this->shortcode = $shortcode;
        $this->isClosed  = $isClosed;
    }

    public function getBlock(): AbstractBlock
    {
        return $this->shortcode;
    }

    public function canHaveLazyContinuationLines(): bool
    {
        return true;
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        if ($this->isClosed || $cursor->getLine() === '}}}') {
            return BlockContinue::finished();
        }

        $this->shortcode->addToBody($cursor->getLine());

        return BlockContinue::at($cursor);
    }
}
