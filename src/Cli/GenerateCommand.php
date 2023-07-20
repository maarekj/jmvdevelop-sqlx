<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Cli;

use JmvDevelop\Sqlx\Generator\QueryGenerator;
use JmvDevelop\Sqlx\Generator\SchemaGenerator;
use League\Flysystem\FilesystemException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function Psl\Type\instance_of;
use function Psl\Type\non_empty_string;

final class GenerateCommand extends Command
{
    public function __construct(string $name = null, private string $defaultConfig = './sqlx-config.php')
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addOption(
            'config',
            'c',
            InputOption::VALUE_REQUIRED,
            'The config file',
            $this->defaultConfig,
        );
    }

    /** @throws FilesystemException */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);
        $style->title('Generate query');

        $configPath = $input->getOption('config');
        $configPath = non_empty_string()->coerce($configPath);

        if (false === is_file($configPath)) {
            $style->error(sprintf('The config file %s not found', $configPath));

            return 1;
        }

        $config = (function () use ($configPath): GenerateCommandConfig {
            /** @psalm-suppress UnresolvableInclude */
            $config = require $configPath;
            instance_of(GenerateCommandConfig::class)->assert($config);

            return $config;
        })();

        $style->table(['option', 'value'], [
            ['config', $configPath],
            ['namespace', $config->getNamespace()],
        ]);

        $config->createFs()->deleteDirectory('/');
        $style->note('Deleting previous generated directory...');

        $style->title('Generation of queries');
        $queryGenerator = new QueryGenerator(
            typeMapper: $config->getTypeMapper(),
            describer: $config->getDescriber(),
            fs: $config->createFs('/Query/'),
            namespace: $config->getNamespace().'\\Query',
        );

        foreach ($config->getFinder()->findQueries() as $source) {
            $style->section($source->getName());
            $style->writeln($source->getRealPath());
            $queryGenerator->generate($source);
        }

        $style->title('Generation of Schema');
        $schemaGenerator = new SchemaGenerator(
            connection: $config->getConnection(),
            fs: $config->createFs('/Schema/'),
            namespace: $config->getNamespace().'\\Schema',
            typeMapper: $config->getTypeMapper(),
        );
        $schemaGenerator->generate();

        $style->success('Success');

        return 0;
    }
}
