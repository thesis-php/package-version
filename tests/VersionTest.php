<?php

declare(strict_types=1);

namespace Thesis\Package;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Thesis\Package\version')]
final class VersionTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        exec(\sprintf('composer install --working-dir %s 2>/dev/null', __DIR__), $output, $code);

        if ($code !== 0) {
            throw new \RuntimeException(implode("\n", $output));
        }

        require_once __DIR__ . '/../var/test-vendor/autoload.php';
    }

    /**
     * @param non-empty-string $package
     * @param non-empty-string $expected
     */
    #[TestWith(['thesis/package-version', '0.1.x-dev'])]
    #[TestWith(['thesis/clock', '0.1.0'])]
    #[TestWith(['thesis/endian', '0.2.x-dev'])]
    public function test(string $package, string $expected): void
    {
        self::assertSame($expected, version($package));
        // assert again to check static memoization
        self::assertSame($expected, version($package));
    }

    /**
     * @param non-empty-string $package
     */
    #[TestWith(['a/b'])]
    #[TestWith(['thesis/provides'])]
    #[TestWith(['thesis/replaces'])]
    public function testNotInstalled(string $package): void
    {
        $this->expectExceptionObject(new \RuntimeException("Package `{$package}` is not installed"));

        version($package);
    }
}
