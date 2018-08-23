<?php

namespace ImportConfigManagerBundle\Command;

use Pimcore\Model\ImportConfig;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExportImporterConfigsCommand extends ContainerAwareCommand
{
    /**
     * @var OutputInterface
     */
    protected $output;

    protected function configure()
    {
        $this
            ->setName('export-importer-configs')
            ->setDescription('Exports importer configurations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $importerConfigNames = $this->getContainer()->getParameter('import_config_manager');

        if(empty($importerConfigNames)){
            $output->writeln("Configuration not found in config.yml");
            return;
        }

        $importConfigListing = new ImportConfig\Listing();
        $importConfigs = $importConfigListing->load();
        foreach ($importConfigs as $importConfig){
            if(in_array($importConfig->getName(), $importerConfigNames)){
                $this->saveConfigFile($importConfig);
            }
        }
    }

    private function saveConfigFile(ImportConfig $importConfig){
        $savePath = PIMCORE_PRIVATE_VAR.DIRECTORY_SEPARATOR.'import_config_manager'.DIRECTORY_SEPARATOR;
        if(!file_exists($savePath)){
            $result = mkdir($savePath, 0777, true);
            if(!$result){
                $this->output->writeln("<error>Can't create path to save files: $savePath</error>");
                return;
            }
        }

        $content = json_encode($importConfig);
        $fileName = md5($importConfig->getName()).".json";
        $fullPath = $savePath.$fileName;
        $result = file_put_contents($fullPath, $content);
        if(!$result){
            $this->output->writeln("<error>Can't write to : $fullPath</error>");
            return;
        }

        $this->output->writeln("<info>File successfully exported to $fullPath</info>");
    }

}
