<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

class ShortcodeRenderer implements NodeRendererInterface
{
    /** @var callable[] */
    private array $shortcodeHandlers;

    /**
     * @param callable[] $shortcodeHandlers
     */
    public function __construct(array $shortcodeHandlers)
    {
        $this->shortcodeHandlers = $shortcodeHandlers;
    }

    /**
     * {@inheritDoc}
     *
     * @suppress PhanUndeclaredMethod
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        Shortcode::assertInstanceOf($node);

        if (! isset($this->shortcodeHandlers[$node->getName()])) {
            return "[Error: shortcode '" . $node->getName() . "' not found.]";
        }

        return $this->shortcodeHandlers[$node->getName()]($node);
    }
}
