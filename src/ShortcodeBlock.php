<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes;

use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\RawMarkupContainerInterface;

class ShortcodeBlock extends AbstractBlock implements RawMarkupContainerInterface
{
    use Shortcode;

    private string $body = '';

    public function addToBody(string $line): void
    {
        $this->body .= "\n" . $line;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
