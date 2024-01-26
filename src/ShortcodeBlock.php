<?php

declare(strict_types=1);

namespace Samwilson\CommonMarkShortcodes;

use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\RawMarkupContainerInterface;

class ShortcodeBlock extends AbstractBlock implements RawMarkupContainerInterface
{
    use ShortcodeNode;
}
