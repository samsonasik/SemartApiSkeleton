<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Service;

use KejawenLab\Semart\ApiSkeleton\Security\Annotation\Permission as Annotation;
use KejawenLab\Semart\ApiSkeleton\Security\Model\MenuRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class Authorization
{
    private $menuRepository;

    private $checker;

    public function __construct(MenuRepositoryInterface $menuRepository, AuthorizationCheckerInterface $checker)
    {
        $this->menuRepository = $menuRepository;
        $this->checker = $checker;
    }

    public function authorize(Annotation $permission): bool
    {
        $menu = $this->menuRepository->findByCode($permission->getMenu());
        if (!$menu) {
            return false;
        }

        foreach ($permission->getActions() as $value) {
            if ($this->checker->isGranted($value, $menu)) {
                return true;
            }
        }

        return false;
    }
}
