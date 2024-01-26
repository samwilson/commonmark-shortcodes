<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes;

use League\CommonMark\Node\Inline\AbstractInline;
use League\CommonMark\Node\RawMarkupContainerInterface;

class ShortcodeInline extends AbstractInline implements RawMarkupContainerInterface
{
    use ShortcodeNode;
}
