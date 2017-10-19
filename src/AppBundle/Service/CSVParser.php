<?php

namespace AppBundle\Service;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CSVParser
{

    public static function extract($fileName, InputInterface $input, OutputInterface $output, $delimiter = ';')
    {
        if (!file_exists($fileName) || !is_readable($fileName)) {
            $output->writeln("<error>Le fichier '$fileName' n'a pas été trouvé</error>");
            return [];
        }

        $header = NULL;
        $data = array();

        if (($handle = fopen($fileName, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }
}
