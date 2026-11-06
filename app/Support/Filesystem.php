<?php

namespace App\Support;

use Illuminate\Filesystem\Filesystem as BaseFilesystem;
use RuntimeException;

class Filesystem extends BaseFilesystem
{
    /**
     * Replace the given file's contents, retrying transient Windows file locks.
     *
     * Windows denies rename() when the destination is briefly held open by a
     * concurrent Blade compile or antivirus scan. The base implementation
     * surfaces that as "Access is denied (code: 5)"; retrying avoids it.
     */
    public function replace($path, $content, $mode = null)
    {
        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;

        $tempPath = tempnam(dirname($path), basename($path));

        if ($tempPath === false) {
            throw new RuntimeException("Unable to create a temporary file for [{$path}].");
        }

        if (! is_null($mode)) {
            @chmod($tempPath, $mode);
        } else {
            @chmod($tempPath, 0777 - umask());
        }

        file_put_contents($tempPath, $content);

        if ($this->moveIntoPlace($tempPath, $path)) {
            return;
        }

        @unlink($tempPath);

        throw new RuntimeException("Unable to replace file at [{$path}].");
    }

    /**
     * Move a temporary file into place, tolerating the Windows rename race.
     */
    protected function moveIntoPlace(string $tempPath, string $path): bool
    {
        $attempts = PHP_OS_FAMILY === 'Windows' ? 10 : 1;

        for ($attempt = 0; $attempt < $attempts; $attempt++) {
            if (@rename($tempPath, $path)) {
                return true;
            }

            if (PHP_OS_FAMILY === 'Windows') {
                usleep(100_000);
            }
        }

        return @copy($tempPath, $path) && @unlink($tempPath);
    }
}
