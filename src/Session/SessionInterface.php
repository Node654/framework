<?php

namespace Nodest\Framework\Session;

interface SessionInterface
{
    public function set(string $key, mixed $value): void;

    public function get(string $key, mixed $default = null): mixed;

    public function has(string $key): bool;

    public function remove(string $key): void;

    public function setFlash(string $type, mixed $message): void;

    public function hasFlash(string $type): bool;

    public function getFlash(string $type): array;

    public function clearFlash(): void;
}
