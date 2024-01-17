<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes;

trait Shortcode
{
    private string $name;

    /** @var string[] */
    private array $attrs = [];

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
        $parts        = \preg_split('/(?<!\\\\)\\|/', $string);
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

            $this->setAttr(\trim((string) $name), \str_replace('\\|', '|', \trim($value)));
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
