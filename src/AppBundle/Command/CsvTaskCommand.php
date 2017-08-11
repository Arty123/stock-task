<?php
/**
 * Created by PhpStorm.
 * User: a.abelyan
 * Date: 10.08.2017
 * Time: 11:33
 */

namespace AppBundle\Command;

use AppBundle\Service\Logger;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Console\Helper\Table;
use AppBundle\Entity\ProductData;

/**
 * Class TaskCommand
 * @package AppBundle\Command
 */
class CsvTaskCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('stock:task')
            // the short description shown while running "php app/console list"
            ->setDescription('Task start')
            ->addArgument('id', InputArgument::OPTIONAL, 'Task id')
            ->addArgument('start', InputArgument::OPTIONAL, 'Start position')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("Run tasks in Entity Task")
        ;
    }

    /**
     * @param Logger $logger
     * @return array
     */
    private function getFormatedOutputTable(Logger $logger)
    {
        $tempArray = [];
        $failImportRulesArray = $logger::$logger['fail']['fail_import_rules'];
        $failBrokenDataArray = $logger::$logger['fail']['fail_broken_data'];

        if (count($failImportRulesArray) > count($failBrokenDataArray)) {
            foreach ($failImportRulesArray as $item) {
                array_push($tempArray, [$item]);
            }

            for ($i = 0; $i < count($tempArray); $i++) {
                if (!empty($failBrokenDataArray[$i])) {
                    array_push($tempArray[$i], $failBrokenDataArray[$i]);
                } else {
                    array_push($tempArray[$i], 'NULL');
                }
            }
        } else {
            foreach ($failBrokenDataArray as $item) {
                array_push($tempArray, [$item]);
            }

            for ($i = 0; $i < count($tempArray); $i++) {
                if (!empty($failImportRulesArray[$i])) {
                    array_push($tempArray[$i], $failImportRulesArray[$i]);
                } else {
                    array_push($tempArray[$i], '');
                }
            }
        }

        return $tempArray;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Define services
        $validator = $this->getContainer()->get('app.validator');
        $logger = $this->getContainer()->get('app.logger');
        $converter = $this->getContainer()->get('app.converter');

        // Define EntityManager
        $em = $this->getContainer()->get('doctrine')->getManager();

        // Variable
        $pathToFile = __DIR__."/../../../web/uploads/documents/stock.csv";

        // Enabling progress bar
        $FILE_SIZE = filesize($pathToFile);
        $progress = new ProgressBar($output, $FILE_SIZE);
        $progress->setFormat('debug');

        // Enabling table class
        $table = new Table($output);

        // Equals 1, because 0 row contain headers of table
        $row = 1;

        $output->writeln('<info>Run Task</info>');

        // Read data
        if (($handle = fopen($pathToFile, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, null, ",")) !== FALSE) {
                $progress->advance($handle);

                // Start from second row, because first row does not contain any data
                if ($row > 1) {

                    // Always log any data, even not valid
                    $logger->init($data);

                    // Initialize validator with current data and check
                    if ($validator->init($data) && $validator->validateImportRules()) {

                        // Convert charset of any string in data array
                        $converter->convertCharset($data);

                        // Find existing item in database
                        $productData = $em->getRepository('AppBundle:ProductData')
                            ->findOneBy([
                                'strProductCode' => $data[0]
                            ]);

                        // If item doesn't exist, create it
                        if (!$productData) {
                            $productData = new ProductData();
                        }

                        // Set item's properties
                        $productData->setStrProductCode($data[0]);
                        $productData->setStrProductName($data[1]);
                        $productData->setStrProductDesc($data[2]);

                        // Some stock fields has no data
                        if ($data[3] != '') {
                            $productData->setIntStockLevel($data[3]);
                        }

                        $productData->setDecPrice($data[4]);

                        // Check discounted field
                        if ($data[5] == 'yes') {
                            $productData->setDtmDiscounted(new \DateTime('now'));
                        }

                        // Insert or update item
                        $em->persist($productData);
                        $em->flush();
                    }
                }

                // Clear EntityManager
                if ($row % 5 == 0) {
                    $em->clear();
                }

                // Inc count of row
                $row++;
            }

            $progress->finish();
            fclose($handle);

            // Rendering results total
            $output->writeln('');
            $table
                ->setHeaders(['Total', 'Success', 'Fail'])
                ->setRows([
                    [
                        $logger::$logger['total'], $logger::$logger['fail']['fail_total'], $logger::$logger['success']
                    ],
                ]);
            $table->render();

            // Rendering detail results
            $tableBody = $this->getFormatedOutputTable($logger);

            //
            $output->writeln('');
            $table
                ->setHeaders(['Fail Import Rules', 'Fail Validate data'])
                ->setRows($tableBody);
            $table->render();
        }

        $output->writeln('');
        $output->writeln('<info>Done!</info>');
    }
}