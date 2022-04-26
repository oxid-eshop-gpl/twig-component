<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\Twig\Tests\Integration\Extensions;

use OxidEsales\Twig\Extensions\PhpFunctionsExtension;

final class PhpFunctionsExtensionTest extends AbstractExtensionTest
{
    /** @var PhpFunctionsExtension */
    protected \Twig\Extension\AbstractExtension $extension;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extension = new PhpFunctionsExtension();
    }

    public function dummyTemplateProvider(): array
    {
        return [
            ["{{ count({0:0, 1:1, 2:2}) }}", 3],
            ["{{ empty({0:0, 1:1}) }}", false],
            ["{{ empty({}) }}", true],
            ["{{ isset(foo) }}", false],
            ["{% set foo = 'bar' %} {{ isset(foo) }}", true],
        ];
    }

    /**
     * @dataProvider dummyTemplateProvider
     */
    public function testIfPhpFunctionsAreCallable(string $template, bool|int $expected): void
    {
        $this->assertEquals($expected, $this->getTemplate($template)->render([]));
    }
}
