<?php

declare(strict_types=1);

namespace Khalyomede\PhpTypo;

use Khalyomede\PhpTypo\Exceptions\FileNotReadableException;
use Khalyomede\PhpTypo\Exceptions\FileOrFolderNotFoundException;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use Symfony\Component\Console\Output\OutputInterface;

final class Checker
{
    public static string $currentFile = "";

    /**
     * @var array<Typo>
     */
    public static array $typos = [];

    public static function check(OutputInterface $output): int
    {
        self::reset();

        $startTime = microtime(true) * 1_000;

        $gettingConfigStartTime = microtime(true) * 1_000;

        $output->writeln("");
        $output->writeln("Getting config...");

        $gettingConfigDuration = round((microtime(true) * 1_000) - $gettingConfigStartTime, 2);

        $config = ConfigFinder::get();

        Words::setFromJsonFiles($config->whitelist);
        Words::ignore($config->ignore);

        $output->writeln("Config found ($gettingConfigDuration ms.). Getting file list...");

        $gettingFilesStartTime = microtime(true) * 1_000;

        $files = (new FileFinder())->setFilesAndFolders($config->include)->getFiles();

        $gettingFilesDuration = round(microtime(true) * 1_000 - $gettingFilesStartTime, 2);

        $numberOfFiles = count($files);

        $output->writeln("File list found with $numberOfFiles files to scan ($gettingFilesDuration ms.). Scanning...");
        $output->writeln("");

        foreach ($files as $file) {
            $numberOfTyposBefore = count(self::$typos);

            self::checkTypos($file);

            $typosFound = (count(self::$typos) - $numberOfTyposBefore) > 0;

            $output->write(
                $typosFound
                    ? "T"
                    : "."
            );
        }

        $output->writeln("");
        $output->writeln("");

        $duration = round((microtime(true) * 1_000) - $startTime, 2);
        $memory = round(memory_get_peak_usage(true) / 1_024 / 1_024, 2);
        $numberOfTypos = count(self::$typos);

        self::printTypos($output);

        $output->writeln("Total typos  $numberOfTypos");
        $output->writeln("Total files  $numberOfFiles");
        $output->writeln("Time spent   $duration ms.");
        $output->writeln("Memory used  $memory MB");
        $output->writeln("");

        return self::foundTypos()
            ? CheckStatus::TypoFound->value
            : CheckStatus::Ok->value;
    }

    public static function reset(): void
    {
        self::$typos = [];
    }

    public static function foundTypos(): bool
    {
        return count(self::$typos) > 0;
    }

    private static function checkTypos(string $path): void
    {
        if (!file_exists($path)) {
            throw new FileOrFolderNotFoundException("The following is not a file: $path");
        }

        if (!is_readable($path)) {
            throw new FileNotReadableException("The following file is not readable: $path");
        }

        $content = file_get_contents($path);

        if (!is_string($content)) {
            throw new FileNotReadableException("Could not open the following file: $path");
        }

        Checker::$currentFile = $path;

        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $ast = $parser->parse($content);

        if (!is_array($ast)) {
            return;
        }

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new AstVisitor());

        $traverser->traverse($ast);
    }

    private static function printTypos(OutputInterface $output): void
    {
        foreach (self::$typos as $typo) {
            $output->writeln("{$typo->file}:{$typo->line}");
            $output->writeln("  {$typo->type->value} \"{$typo->name}\" contains an unknown word \"{$typo->error}\".");
            $output->writeln("");
        }
    }
}
