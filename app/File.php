<?php

declare(strict_types = 1);

namespace App;

use Exception;
use function Safe\{file_get_contents, file_put_contents};

/**
 * Ported from Laravel's illuminate/filesystem
 * @author Taylor Otwell
 * @see https://github.com/illuminate/filesystem
 */
final class File
{
    /**
     * Determine if a file or directory exists.
     *
     * @param  string  $path
     * @return bool
     */
    public static function exists($path)
    {
        return file_exists($path);
    }

    /**
     * Get the contents of a file.
     *
     * @param  string  $path
     * @param  bool  $lock
     * @return string
     *
     * @throws Exception
     */
    public static function get($path, $lock = false)
    {
        if (self::exists($path)) {
            return file_get_contents($path);
        }

        throw new Exception("File does not exist at path {$path}.");
    }

    /**
     * Write the contents of a file.
     *
     * @param  string  $path
     * @param  string  $contents
     * @param  bool  $lock
     * @return int|bool
     */
    public static function put($path, $contents, $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  array<int,string>|string  $search
     * @param  array<int,string>|string  $replace
     * @param  string  $path
     * @return void
     */
    public static function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * Delete the file at a given path.
     *
     * @param  string|array<int,string>  $paths
     * @return bool
     */
    public static function delete($paths)
    {
        $paths = is_array($paths) ? $paths : func_get_args();

        $success = true;

        foreach ($paths as $path) {
            try {
                /** @phpstan-ignore-next-line */
                if (unlink($path)) {
                    clearstatcache(false, strval($path));
                } else {
                    $success = false;
                }
            } catch (Exception $e) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Append to a file.
     *
     * @param  string  $path
     * @param  string  $data
     * @return int
     */
    public static function append($path, $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }
}
