<?php

declare(strict_types=1);

namespace Khalyomede\PhpTypo;

use Khalyomede\PhpTypo\Exceptions\FileOrFolderNotAString;
use Khalyomede\PhpTypo\Exceptions\FolderScanException;

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
     * @param array<string> $filesAndFolders
     */
    public function setFilesAndFolders(array $filesAndFolders): self
    {
        foreach ($filesAndFolders as $fileOrFolder) {
            if (!is_string($fileOrFolder)) {
                $type = is_object($fileOrFolder)
                    ? get_class($fileOrFolder)
                    /** @phpstan-ignore-next-line Else branch is unreachable because ternary operator condition is always true. */
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
     */
    private static function getFilesFromFolder(string $folder): array
    {
        $scannedFilesOrFolders = scandir($folder);

        if ($scannedFilesOrFolders === false) {
            throw new FolderScanException("Could not scan for files and folders in the following folder: $folder");
        }

        return array_filter(
            $scannedFilesOrFolders,
            fn (string $fileOrFolder): bool => str_ends_with($fileOrFolder, ".php")
        );
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
                $folderFiles = array_map(
                    fn (string $subFileOrFolder): string => $fileOrFolder . DIRECTORY_SEPARATOR . $subFileOrFolder,
                    self::getFilesFromFolder($fileOrFolder)
                );

                $files = self::addFiles($files, $folderFiles);
            }

            if (is_file($fileOrFolder)) {
                $files[] = $fileOrFolder;
            }
        }

        return $files;
    }
}
