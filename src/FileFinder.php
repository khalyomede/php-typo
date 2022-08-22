<?php

declare(strict_types=1);

namespace Khalyomede\PhpTypo;

use Khalyomede\PhpTypo\Exceptions\FileOrFolderNotAString;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class FileFinder
{
    /**
     * @var array<string>
     */
    private array $folders;

    /**
     * @var array<string>
     */
    private array $files;

    public function __construct()
    {
        $this->folders = [];
        $this->files = [];
    }

    /**
     * @param array<mixed> $filesAndFolders
     */
    public function setFilesAndFolders(array $filesAndFolders): self
    {
        foreach ($filesAndFolders as $fileOrFolder) {
            if (!is_string($fileOrFolder)) {
                $type = is_object($fileOrFolder)
                    ? get_class($fileOrFolder)
                    : gettype($fileOrFolder);

                throw new FileOrFolderNotAString("The following is not a file or folder: $type");
            }

            if (is_dir($fileOrFolder)) {
                $this->folders[] = $fileOrFolder;

                continue;
            }

            if (is_file($fileOrFolder)) {
                $this->files[] = $fileOrFolder;
            }
        }

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getFiles(): array
    {
        $files = $this->files;

        foreach ($this->folders as $folder) {
            $files = self::addFiles($files, [$folder]);
        }

        return $files;
    }

    /**
     * @return array<string>
     *
     * @see https://stackoverflow.com/a/24784020/3753055
     */
    private static function getFilesFromFolder(string $folder): array
    {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder));

        $files = [];

        foreach ($rii as $file) {
            assert($file instanceof SplFileInfo);

            if ($file->isDir()) {
                continue;
            }

            $files[] = $file->getPathname();
        }

        sort($files);

        return $files;
    }

    /**
     * @param array<string> $files
     * @param array<string> $filesOrFolders
     *
     * @return array<string>
     */
    private static function addFiles(array $files, array $filesOrFolders): array
    {
        foreach ($filesOrFolders as $fileOrFolder) {
            if (is_dir($fileOrFolder)) {
                $folderFiles = self::getFilesFromFolder($fileOrFolder);
                $files = self::addFiles($files, $folderFiles);
            }

            if (is_file($fileOrFolder)) {
                $files[] = $fileOrFolder;
            }
        }

        return $files;
    }
}
