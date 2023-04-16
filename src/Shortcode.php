<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes;

use DOMDocument;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\RawMarkupContainerInterface;

class Shortcode extends AbstractBlock implements RawMarkupContainerInterface
{
    private string $name;

    /** @var string[] */
    private array $attrs = [];

    private string $body = '';

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function loadAttrsFromString(string $string): void
    {
        $doc = new DOMDocument();
        $doc->loadHTML('<body ' . $string . ' />');
        $body = $doc->getElementsByTagName('body')[0];
        for ($i = 0; $i < $body->attributes->length; ++$i) {
            $name  = $body->attributes->item($i)->name;
            $value = $body->getAttribute($name);
            $this->setAttr($name, $value);
        }
    }

    /**
     * @return string[]
     */
    public function getAttrs(): array
    {
        return $this->attrs;
    }

    public function getAttr(string $attr): ?string
    {
        return $this->attrs[$attr] ?? null;
    }

    public function setAttr(string $key, string $value): void
    {
        $this->attrs[$key] = $value;
    }

    public function addToBody(string $line): void
    {
        $this->body .= "\n" . $line;
    }

    public function getBody(): string
    {
        return $this->body;
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
