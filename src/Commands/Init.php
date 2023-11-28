<?php

declare(strict_types=1);

namespace Khalyomede\PhpTypo\Commands;

use Khalyomede\PhpTypo\Config;
use Khalyomede\PhpTypo\Exceptions\ConfigWriteException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: "init",
)]
final class Init extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (Config::exists()) {
            $output->writeln("php-typo.json already exists.");

            return 1;
        }

        try {
            Config::write();
        } catch (ConfigWriteException $exception) {
            $output->writeln($exception->getMessage());

            return 1;
        }

        $output->writeln("Config file written at php-typo.json.");

        return 0;
    }

    protected function configure(): void
    {
        $this->setDescription("Create the php-typo.json config file.");
    }
}
