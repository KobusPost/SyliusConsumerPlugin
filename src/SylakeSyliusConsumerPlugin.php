<?php

declare(strict_types=1);

namespace Sylake\SyliusConsumerPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SylakeSyliusConsumerPlugin extends Bundle
{
    use SyliusPluginTrait;
}
