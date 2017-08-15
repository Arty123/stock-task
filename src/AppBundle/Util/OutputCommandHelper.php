<?php

namespace AppBundle\Util;

use AppBundle\Service\Logger;
use Symfony\Component\Console\Helper\Table as Table;
use Symfony\Component\Console\Output\ConsoleOutput as Output;

/**
 * Class OutputCommandHelper.
 */
class OutputCommandHelper
{
    /**
     * Create two columns for output table.
     *
     * @param Logger $logger
     *
     * @return array
     */
    private function getFailItemsTableBody(Logger $logger)
    {
        $tempArray = [];
        $failImportRulesArray = $logger::$logger['fail']['fail_import_rules'];
        $failBrokenDataArray = $logger::$logger['fail']['fail_broken_data'];

        // If first column more than second
        if (count($failImportRulesArray) > count($failBrokenDataArray)) {
            foreach ($failImportRulesArray as $item) {
                // Create first column
                array_push($tempArray, [$item]);
            }

            for ($i = 0; $i < count($tempArray); ++$i) {
                if (!empty($failBrokenDataArray[$i])) {
                    array_push($tempArray[$i], $failBrokenDataArray[$i]);
                } else {
                    array_push($tempArray[$i], '');
                }
            }
        } else {
            foreach ($failBrokenDataArray as $item) {
                array_push($tempArray, [$item]);
            }

            for ($i = 0; $i < count($tempArray); ++$i) {
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
     * @param Logger $logger
     *
     * @return array
     */
    private function getDiscountedItemsTableBody(Logger $logger)
    {
        $tempArray = [];
        foreach ($logger::$logger['discounted_items'] as $item) {
            array_push($tempArray, [$item]);
        }

        return $tempArray;
    }

    /**
     * @param Logger $logger
     */
    public function printTables(Logger $logger)
    {
        $output = new Output();
        $table = new Table($output);

        // Rendering results total
        $output->writeln('');
        $table
            ->setHeaders(['Total', 'Fail', 'Success'])
            ->setRows([
                [
                    $logger::$logger['total'], $logger::$logger['fail']['fail_total'], $logger::$logger['success'],
                ],
            ]);
        $table->render();

        // Rendering detail results
        $tableBody = $this->getFailItemsTableBody($logger);
        $output->writeln('');
        $table
            ->setHeaders(['Fail Validate data', 'Fail Import Rules'])
            ->setRows($tableBody);
        $table->render();

        // Rendering detail results
        $tableBody = $this->getDiscountedItemsTableBody($logger);
        $output->writeln('');
        $table
            ->setHeaders(['Discounted Items'])
            ->setRows($tableBody);
        $table->render();
    }
}