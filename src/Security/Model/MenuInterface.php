<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface MenuInterface extends PermissionableInterface
{
    public function getParent(): ?self;

    public function getCode(): ?string;

    public function getName(): ?string;

    public function getSortOrder(): ?int;

    public function getRouteName(): ?string;

    public function getUrlPath(): ?string;

    public function setUrlPath(string $urlPath): self;

    public function getExtra(): ?string;

    public function isShowable(): ?bool;
}
