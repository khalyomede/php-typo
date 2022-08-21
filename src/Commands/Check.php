<?php

declare(strict_types=1);

namespace PhpTypo\PhpTypo\Commands;

use PhpTypo\PhpTypo\Checker;
use PhpTypo\PhpTypo\Exceptions\ConfigFileInvalidJsonFormatException;
use PhpTypo\PhpTypo\Exceptions\ConfigFileNotFoundException;
use PhpTypo\PhpTypo\Exceptions\ConfigWriteException;
use PhpTypo\PhpTypo\Exceptions\FileNotReadableException;
use PhpTypo\PhpTypo\Exceptions\FileOrFolderNotAString;
use PhpTypo\PhpTypo\Exceptions\FileOrFolderNotFoundException;
use PhpTypo\PhpTypo\Exceptions\FolderScanException;
use PhpTypo\PhpTypo\Exceptions\NotAFileException;
use PhpTypo\PhpTypo\Exceptions\WordsFileInvalidJsonFormatException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Check extends Command
{
    protected static $defaultName = "check";

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            Checker::check($output);
        } catch (
            ConfigFileNotFoundException |
            ConfigFileInvalidJsonFormatException |
            ConfigWriteException |
            FileNotReadableException |
            FileOrFolderNotAString |
            FileOrFolderNotFoundException |
            FolderScanException |
            NotAFileException |
            WordsFileInvalidJsonFormatException $exception
        ) {
            $output->writeln($exception->getMessage());

            return 1;
        }

        return 0;
    }

    protected function configure(): void
    {
        $this->setDescription("Checks file for typos.");
    }
}
