<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Converter;

use Composer\InstalledVersions;
use DQ5Studios\TypeScript\Generator\File;
use DQ5Studios\TypeScript\Generator\Printer;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\SourceStubber\ReflectionSourceStubber;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\Composer\Factory\MakeLocatorForComposerJson;
use Roave\BetterReflection\SourceLocator\Type\ComposerSourceLocator;
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
use Symfony\Component\Finder\Finder;

class Command extends SymfonyCommand
{
    protected static $defaultName = "convert";

    protected function configure(): void
    {
        $this->setDescription("Convert PHP files to TypeScript definition files");
        $this->setDefinition(
            new InputDefinition([
                new InputOption("input", "i", InputOption::VALUE_OPTIONAL, "Input directory or file"),
                new InputOption("output", "o", InputOption::VALUE_OPTIONAL, "Output directory or file"),
                new InputArgument("input", InputArgument::OPTIONAL, "Input directory or file"),
            ])
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $input_loc = (string) ($input->getOption("input") ?? $input->getArgument("input"));
        $output_loc = (string) $input->getOption("output");

        $io = new SymfonyStyle($input, $output);
        $io->note("Looking in {$input_loc}; writing to {$output_loc}");

        if (!is_file($input_loc)) {
            $io->error("Only file input currently supported");

            return Command::FAILURE;
        }

        // $classLoader = require __DIR__ . "/../vendor/autoload.php";

        // $astLocator = (new BetterReflection())->astLocator();
        // $reflector = new DefaultReflector(new ComposerSourceLocator($classLoader, $astLocator));

        // Load composer
        $astLocator = (new BetterReflection())->astLocator();
        $reflector = new DefaultReflector(new AggregateSourceLocator([
            (new MakeLocatorForComposerJson())(InstalledVersions::getRootPackage()["install_path"], $astLocator),
            new PhpInternalSourceLocator($astLocator, new ReflectionSourceStubber()),
        ]));
        $classes = $reflector->reflectAllClasses();
        foreach ($classes as $class) {
            echo $class->getName(), PHP_EOL;
        }

        // Single File
        $astLocator = (new BetterReflection())->astLocator();
        $reflector = new DefaultReflector(new SingleFileSourceLocator($input_loc, $astLocator));

        // Single directory
        // $directoriesSourceLocator = new DirectoriesSourceLocator(['path/to/directory1'], $astLocator);
        // $reflector = new DefaultReflector($directoriesSourceLocator);

        $classes = $reflector->reflectAllClasses();

        $file = new File();
        foreach ($classes as $class) {
            $io->info("Converting {$class->getShortName()} to {$output_loc}");
            $file->append(Convert::fromPHP($class->getName()));
        }
        file_put_contents($output_loc, Printer::print($file));

        // $finder = new Finder();
        // $finder->files()->in(__DIR__)->name('/\.php$/');
        return Command::SUCCESS;
    }
}
