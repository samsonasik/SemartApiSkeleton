<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\DataFixtures;

use KejawenLab\ApiSkeleton\Entity\Menu;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class MenuFixture extends AbstractFixture
{
    protected function createNew()
    {
        return new Menu();
    }

    protected function getReferenceKey(): string
    {
        return 'menu';
    }
}
