<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes;

trait ShortcodeNode
{
    private Shortcode $shortcode;

    public function __construct(Shortcode $shortcode)
    {
        $this->shortcode = $shortcode;
    }

    public function getShortcode(): Shortcode
    {
        return $this->shortcode;
    }

    /**
     * Not used.
     */
    public function getLiteral(): string
    {
        return '';
    }

    /**
     * Not used.
     */
    public function setLiteral(string $literal): void
    {
    }
}
