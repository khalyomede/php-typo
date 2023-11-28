<?php

declare(strict_types=1);

namespace Khalyomede\PhpTypo\Commands;

use Khalyomede\PhpTypo\Checker;
use Khalyomede\PhpTypo\Exceptions\ConfigFileInvalidJsonFormatException;
use Khalyomede\PhpTypo\Exceptions\ConfigFileNotFoundException;
use Khalyomede\PhpTypo\Exceptions\ConfigWriteException;
use Khalyomede\PhpTypo\Exceptions\FileNotReadableException;
use Khalyomede\PhpTypo\Exceptions\FileOrFolderNotAString;
use Khalyomede\PhpTypo\Exceptions\FileOrFolderNotFoundException;
use Khalyomede\PhpTypo\Exceptions\FolderScanException;
use Khalyomede\PhpTypo\Exceptions\NotAFileException;
use Khalyomede\PhpTypo\Exceptions\WordsFileInvalidJsonFormatException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: "check"
)]
final class Check extends Command
{
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

        return Checker::foundTypos()
            ? 1
            : 0;
    }

    protected function configure(): void
    {
        $this->setDescription("Checks file for typos.");
    }
}
