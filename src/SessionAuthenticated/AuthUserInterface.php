<?php

namespace Nodest\Framework\SessionAuthenticated;

interface AuthUserInterface
{
    public function getId(): ?int;

    public function getEmail(): string;

    public function getPassword(): string;
}
