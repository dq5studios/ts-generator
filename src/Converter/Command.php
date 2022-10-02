<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Converter;

use Composer\InstalledVersions;
use DQ5Studios\TypeScript\Generator\File;
use DQ5Studios\TypeScript\Generator\Printer;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\Adapter\ReflectionClass;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\SourceStubber\ReflectionSourceStubber;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\Composer\Factory\MakeLocatorForComposerJson;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\PhpInternalSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Command extends SymfonyCommand
{
    protected static $defaultName = "convert";

    public const INPUT_PROJECT = 0;
    public const INPUT_FILE = 1;
    public const INPUT_DIR = 2;
    public const OUTPUT_FILE = 0;
    public const OUTPUT_DIR = 1;
    public const OUTPUT_NEW_FILE = 2;

    protected function configure(): void
    {
        $this->setName("convert");
        $this->setDescription("Convert PHP files to TypeScript definition files");
        $this->setDefinition(
            new InputDefinition([
                new InputOption("input", "i", InputOption::VALUE_OPTIONAL, "Input directory or file"),
                new InputOption("output", "o", InputOption::VALUE_OPTIONAL, "Output directory or file"),
                new InputOption("overwrite", null, InputOption::VALUE_NONE, "Overwrite existing output file(s)"),
                new InputArgument("input", InputArgument::OPTIONAL, "Input directory or file"),
            ])
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $input_loc = (string) ($input->getOption("input") ?? $input->getArgument("input"));
        $output_loc = (string) $input->getOption("output");

        $input_mode = null;
        if (empty($input_loc)) {
            $input_mode = Command::INPUT_PROJECT;
        } elseif (is_dir($input_loc)) {
            $input_mode = Command::INPUT_DIR;
        } elseif (is_file($input_loc)) {
            $input_mode = Command::INPUT_FILE;
        }
        if (is_null($input_mode)) {
            $io->error("Input directory/file not found");

            return Command::FAILURE;
        }

        $output_mode = Command::OUTPUT_NEW_FILE;
        if (is_dir($output_loc)) {
            $output_mode = Command::OUTPUT_DIR;
        } elseif (is_file($output_loc)) {
            $output_mode = Command::OUTPUT_FILE;
            $io->confirm("Output file already exists, overwrite?", true);
        }
        // TODO: If doesn't exist yet, figure out if file/dir
        // TODO: If not set, put files along side input?

        $io->info("Looking in {$input_loc}; writing to {$output_loc}");

        $ast_locator = (new BetterReflection())->astLocator();

        /** @psalm-suppress InternalClass, InternalMethod */
        $reflector = match ($input_mode) {
            Command::INPUT_PROJECT => new DefaultReflector(
                new AggregateSourceLocator([
                    (new MakeLocatorForComposerJson())(InstalledVersions::getRootPackage()["install_path"], $ast_locator),
                    new PhpInternalSourceLocator($ast_locator, new ReflectionSourceStubber()),
                ])
            ),
            Command::INPUT_FILE => new DefaultReflector(new SingleFileSourceLocator($input_loc, $ast_locator)),
            Command::INPUT_DIR => new DefaultReflector(new DirectoriesSourceLocator([$input_loc], $ast_locator)),
        };

        $classes = $reflector->reflectAllClasses();

        $file = new File();
        /** @var ReflectionClass $class */
        foreach ($io->progressIterate($classes) as $class) {
            if (!$class->isAnonymous()) {
                // $io->info("Converting {$class->getShortName()};" . get_class($class));
                $file->append(Convert::fromPHP($class));
                if (Command::OUTPUT_DIR === $output_mode) {
                    // Each input file gets its own output file
                    file_put_contents("{$output_loc}/{$class->getShortName()}.d.ts", Printer::print($file));
                    $file = new File();
                }
            }
        }
        if (Command::OUTPUT_FILE === $output_mode) {
            file_put_contents($output_loc, Printer::print($file));
        }

        $io->success("Finished!");

        return Command::SUCCESS;
    }
}
