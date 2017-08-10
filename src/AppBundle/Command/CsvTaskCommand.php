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
use AppBundle\Entity\StockQuotes;

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
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln('<info>Run Task</info>');
        $em = $this->getContainer()->get('doctrine')->getManager();

        $dir = __DIR__."/../../../web/uploads/documents/";

        if (!file_exists($dir) && !is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $FILE_SIZE = filesize($dir."stock.csv");

        $progress = new ProgressBar($output, $FILE_SIZE);
        $progress->setFormat('debug');
        $row = 1;

        // Read data
        $CHUNK_SIZE = 512;

        if (($handle = fopen($dir."stock.csv", "r")) !== FALSE) {
            // Set batchSize and Iterate for bulk inserts
//            $batchSize = 20;
//            $iterate = 0;

            while (($data = fgetcsv($handle, $CHUNK_SIZE, ",")) !== FALSE) {
//                $progress->advance($CHUNK_SIZE);

                // Start from second row, because first row does not contain any data
                if ($row > 1) {
                    // Convert charset of any string in data array
                    foreach ($data as &$item) {
                        mb_convert_encoding($item, 'UTF-8', 'auto');
                    }
                }

//                if (($iterate % $batchSize) === 0) {
//                    $em->flush(); // Executes all updates.
//                    $em->clear(); // Detaches all objects from Doctrine!
//                }
                // Inc iterate value
//                ++$iterate;
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