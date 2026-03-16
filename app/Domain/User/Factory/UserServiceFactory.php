<?php

namespace App\Domain\User\Factory;

use App\Domain\Attribute\Contracts\AttributeAssignable;
use App\Domain\Skill\Contracts\SkillAssignable;
use App\Domain\User\Services\UserService;

class UserServiceFactory
{
    public function make(SkillAssignable | AttributeAssignable $user): UserService
    {
        return new UserService($user);
    }
}