<?php

namespace Nodest\Framework\SessionAuthenticated;

interface SessionAuthInterface
{
    public function authenticate(string $email, string $password): bool;

    public function login(AuthUserInterface $user);

    public function logout(): void;

    public function getUser(): AuthUserInterface;

    public function check(): bool;
}
