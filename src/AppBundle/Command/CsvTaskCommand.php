<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class CsvTaskCommand.
 */
class CsvTaskCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('stock:task')
            // the short description shown while running "php app/console list"
            ->setDescription('Task start')
            ->addArgument('id', InputArgument::OPTIONAL, 'Task id')
            ->addArgument('start', InputArgument::OPTIONAL, 'Start position')
            // Test mode
            ->addOption(
                'test',
                null,
                InputOption::VALUE_OPTIONAL,
                'Test option',
                0
            )
            // Path to file option
            ->addOption(
                'path',
                null,
                InputOption::VALUE_REQUIRED,
                'Path to file option'
            )
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Run tasks in Entity Task')
        ;
    }

    /**
     * @param $pathToFile
     * @throws \Exception
     */
    private function checkFilePath($pathToFile)
    {
        if (!$pathToFile) {
            throw new \Exception('You need to set path to file, in --path option (--path=/path/to/file)');
        } elseif (!file_exists($pathToFile)) {
            throw new \Exception('File not found in '.$pathToFile);
        }
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get options
        $testMode = $input->getOption('test');
        $pathToFile = $input->getOption('path');

        // Check file existing
        $this->checkFilePath($pathToFile);

        // Define services
        $validator = $this->getContainer()->get('app.validator');
        $logger = $this->getContainer()->get('app.logger');
        $outputHelper = $this->getContainer()->get('app.output.command.helper');
        $dataManager = $this->getContainer()->get('app.data.manager');

        // Equals 1, because 0 row contain headers of table
        $row = 1;

        // Enabling progress bar
        $FILE_SIZE = filesize($pathToFile);
        $progress = new ProgressBar($output, $FILE_SIZE);
        $progress->setFormat('debug');

        $output->writeln('<info>Run Task</info>');

        // Read data
        if (($handle = fopen($pathToFile, 'r')) !== false) {
            while (($data = fgetcsv($handle, null, ',')) !== false) {
                $progress->advance($handle);
                // Start from second row, because first row does not contain any data
                if ($row > 1) {
                    // Always log any data, even not valid
                    $logger->init($data);

                    // Initialize validator with current data and check
                    if ($validator->init($data) && $validator->validateImportRules()) {
                        $dataManager->manageData($testMode, $data);
                    }
                }
                // Inc count of row
                ++$row;
            }
            
            $progress->finish();
            fclose($handle);
        }

        // Print tables
        $outputHelper->printTables($logger);

        $output->writeln('');
        $output->writeln('<info>Done!</info>');
    }
}
