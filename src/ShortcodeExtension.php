<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\Config\ConfigurationBuilderInterface;
use Nette\Schema\Expect;

class ShortcodeExtension implements ConfigurableExtensionInterface
{
    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema('shortcodes', Expect::structure([
            'shortcodes' => Expect::array()->default([]),
        ]));
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $shortcodes = $environment->getConfiguration()->get('shortcodes.shortcodes');
        $environment
            ->addInlineParser(new ShortcodeInlineParser($shortcodes), 50)
            ->addBlockStartParser(new ShortcodeBlockStartParser($shortcodes), 50)
            ->addRenderer(ShortcodeInline::class, new ShortcodeRenderer($shortcodes), 0)
            ->addRenderer(ShortcodeBlock::class, new ShortcodeRenderer($shortcodes), 0);
    }
}
