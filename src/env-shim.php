<?php declare(strict_types=1);

/**
 * This file is part of RESTSpeaker, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *  GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *  https://www.phpexperts.pro/
 *  https://github.com/phpexpertsinc/RESTSpeaker
 *
 * This file is licensed under the MIT License.
 */

namespace PHPExperts
{
    /**
     * This is a shim to make Laravel 5.8's env() as
     * backward compatible as possible with pre-5.8.
     *
     * Namely, it searches for `getenv()` if `env()
     * returns FALSE.
     *
     * See https://github.com/laravel/framework/issues/27949
     *
     * @param string $key
     * @param string $default
     * @return array|bool|false|string
     */
    function env(string $key, string $default='')
    {
        $setting = false;
        if (function_exists('env')) {
            $setting = env($key);
        }

        if ($setting === false) {
            $setting = getenv($key);

            if ($setting === false) {
                $setting = $default;
            }
        }

        return $setting;
    }
}

namespace {
    // Shim for non-Laravel projects where env() is not present.
    if (!function_exists('env')) {
        function env(string $key, string $default='')
        {
            return \PHPExperts\env($key, $default);
        }
    }
}

