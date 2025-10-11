<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes;

class Shortcode
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
        $parts = \preg_split('/(?<!\\\\)\\|/', $string);
        if (! $parts) {
            return;
        }

        $unnamedIndex = 1;
        foreach ($parts as $part) {
            if ($part === '') {
                continue;
            }

            if (\strpos($part, '=')) {
                [$name, $value] = \explode('=', $part);
            } else {
                $name  = $unnamedIndex;
                $value = $part;
                $unnamedIndex++;
            }

            $unescapedClosingBrace = \str_replace('\\}', '}', \trim($value));
            $unescapedPipes        = \str_replace('\\|', '|', $unescapedClosingBrace);
            $this->setAttr(\trim((string) $name), $unescapedPipes);
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
}
