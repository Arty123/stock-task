<?php
/**
 * Created by PhpStorm.
 * User: a.abelyan
 * Date: 10.08.2017
 * Time: 11:33
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
     * @param $data
     */
    protected function convertCharset(&$data)
    {
        // Stock.csv has ASCII charset and so we need to encode all of data in ASCII
        // Some products has strange symbols in name field in UTF-8 charset
        // Convert of this fields gives symbol '?', and I don't know what I should to do with it
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = trim(mb_convert_encoding($data[$i], 'ASCII', 'auto'));
        }
    }

    /**
     * @param $data
     */
    protected function formatPrice(&$data) {
        // Some prices has symbol '$'
        $symbol = strpos($data, '$');

        if ($symbol !== false) {
            $data = substr($data, ++$symbol);

        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln('<info>Run Task</info>');
        $em = $this->getContainer()->get('doctrine')->getManager();

        $pathToFile = __DIR__."/../../../web/uploads/documents/stock.csv";

        $FILE_SIZE = filesize($pathToFile);

        $progress = new ProgressBar($output, $FILE_SIZE);
        $progress->setFormat('debug');
        $row = 1;

        // Read data
        $CHUNK_SIZE = 512;

        if (($handle = fopen($pathToFile, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, $CHUNK_SIZE, ",")) !== FALSE) {
                $progress->advance($CHUNK_SIZE);

                // Start from second row, because first row does not contain any data
                if ($row > 1) {
                    // Valid csv row have to contain 6 fields
                    if (count($data) != 6) {
                        continue;
                    }

                    // Convert charset of any string in data array
                    $this->convertCharset($data);

                    $productData = $em->getRepository('AppBundle:ProductData')
                        ->findOneBy([
                            'strProductCode' => $data[0]
                            ]);

                    if (!$productData) {
                        $productData = new ProductData();
                    }

                    $productData->setStrProductCode($data[0]);
                    $productData->setStrProductName($data[1]);
                    $productData->setStrProductDesc($data[2]);

                    if ($data[3]) {
                        $productData->setIntStockLevel($data[3]);
                    }

                    $this->formatPrice($data[4]);
                    $productData->setDecPrice($data[4]);

                    if ($data[5] == 'yes') {
                        $productData->setDtmDiscounted(new \DateTime('now'));
                    }

                    $em->persist($productData);

                    $em->persist($productData);
                    $em->flush();
                }
                // Inc count of row
                $row++;
            }

            $progress->finish();

            fclose($handle);
        }
        $output->writeln('');
        $output->writeln('<info>Done!</info>');
    }
}