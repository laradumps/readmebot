<?php

declare(strict_types = 1);

use Kint\Kint;

if (class_exists('Kint') && function_exists('dd') === false) {
    /**
     * Kint::dump() and die.
     */
    function dd(mixed ...$args): void
    {
        d(...$args);
        die;
    }

    Kint::$aliases[] = 'dd';
}
