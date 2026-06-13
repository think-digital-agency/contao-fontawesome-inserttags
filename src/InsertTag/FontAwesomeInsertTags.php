<?php

declare(strict_types=1);

namespace ThinkDigital\ContaoFontAwesomeInsertTags\InsertTag;

use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\CoreBundle\InsertTag\Resolver\InsertTagResolverNestedResolvedInterface;

#[AsInsertTag('fa')]
#[AsInsertTag('fas')]
#[AsInsertTag('far')]
#[AsInsertTag('fab')]
final class FontAwesomeInsertTags implements InsertTagResolverNestedResolvedInterface
{
    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        // Name ist "fa" / "fas" / "far" / "fab"
        $name = strtolower($insertTag->getName());

        // Parameter: {{fas::rocket}} => "rocket"
        $icon = (string) $insertTag->getParameters()->get(0);
        $icon = strtolower(trim($icon));

        if ('' === $icon || 1 !== preg_match('/^[a-z0-9-]+$/', $icon)) {
            // Wenn Icon fehlt/invalid: leeren String zurück (oder false wäre hier nicht möglich)
            return new InsertTagResult('', OutputType::text);
        }

        // Optional: weitere Parameter als Modifiers -> Klassen
        // {{fas::rocket::spin::lg}} => rocket + [spin, lg]
        $classes = [$name, 'fa-'.$icon];

        $params = $insertTag->getParameters();
        for ($i = 1; null !== ($p = $params->get($i)); $i++) {
            $mod = strtolower(trim((string) $p));
            if ('' === $mod || 1 !== preg_match('/^[a-z0-9-]+$/', $mod)) {
                continue;
            }
            $classes[] = str_starts_with($mod, 'fa-') ? $mod : 'fa-'.$mod;
        }

        $classAttr = htmlspecialchars(implode(' ', $classes), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return new InsertTagResult(
            sprintf('<i class="%s" aria-hidden="true"></i>', $classAttr),
            OutputType::html
        );
    }
}
