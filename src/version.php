<?php

declare(strict_types=1);

namespace Thesis\Package;

use Composer\InstalledVersions;

/**
 * @param non-empty-string $package
 * @return non-empty-string
 */
function version(string $package): string
{
    /** @var array<non-empty-string, non-empty-string> */
    static $versions = [];

    if (isset($versions[$package])) {
        return $versions[$package];
    }

    if (InstalledVersions::isInstalled($package)) {
        $version = InstalledVersions::getPrettyVersion($package);

        if ($version !== null) {
            \assert($version !== '');

            return $versions[$package] = $version;
        }
    }

    throw new \RuntimeException("Package `{$package}` is not installed");
}
