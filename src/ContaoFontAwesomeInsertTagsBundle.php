<?php

declare(strict_types=1);

namespace ThinkDigital\ContaoFontAwesomeInsertTags;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoFontAwesomeInsertTagsBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
