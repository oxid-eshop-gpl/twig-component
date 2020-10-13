<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\Twig\Resolver\TemplateChain;

use OxidEsales\Twig\Resolver\TemplateNameConverterInterface;
use OxidEsales\Twig\TwigContextInterface;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\PathUtil\Path;

class ShopTemplateChain implements TemplateChainInterface
{
    /** @var TwigContextInterface */
    private $twigContext;
    /** @var Filesystem */
    private $filesystem;
    /** @var TemplateNameConverterInterface */
    private $templateNameConverter;

    public function __construct(
        TwigContextInterface $twigContext,
        Filesystem $filesystem,
        TemplateNameConverterInterface $templateNameConverter
    ) {
        $this->twigContext = $twigContext;
        $this->filesystem = $filesystem;
        $this->templateNameConverter = $templateNameConverter;
    }

    /** @inheritDoc */
    public function getChain(string $templateName): array
    {
        $templateChain = [];
        if ($this->shopHasTemplate($templateName)) {
            $templateChain[] = $this->templateNameConverter->fillNamespace($templateName);
        }
        return $templateChain;
    }

    private function shopHasTemplate(string $templateName): bool
    {
        foreach ($this->twigContext->getTemplateDirectories() as $directory) {
            $path = Path::join($directory, $templateName);
            if ($this->filesystem->exists($path)) {
                return true;
            }
        }
        return false;
    }
}
