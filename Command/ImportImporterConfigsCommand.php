<?php

namespace ImportConfigManagerBundle\Command;

use Pimcore\Model\ImportConfig;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportImporterConfigsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('import-importer-configs')
            ->setDescription('Imports previously exported importer configurations');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = array_diff(scandir($this->getSavePath()), array('.', '..'));
        $configsAsArray = $this->convertFilesToAssocArrayByName($files);

        $importConfigListing = new ImportConfig\Listing();
        $importConfigs = $importConfigListing->load();
        /** @var ImportConfig $importConfig */
        foreach ($importConfigs as $importConfig){
            if(array_key_exists($importConfig->getName().".json", $configsAsArray)){
                $output->writeln("<info>".$importConfig->getName().".json</info>");
                $importConfig->setConfig($configsAsArray[$importConfig->getName().".json"]);
                $importConfig->save();
            }
        }
        $output->writeln("<info>Configuration import successfully completed</info>");
    }

    /**
     * @param $files
     * @return array
     */
    private function convertFilesToAssocArrayByName($files){
        $result = [];
        foreach ($files as $file){
            $fileContent = file_get_contents($this->getSavePath().$file);
            $result[$file] = $fileContent;
        }

        return $result;
    }

    private function getSavePath(){
        return PIMCORE_PRIVATE_VAR.DIRECTORY_SEPARATOR.'import_config_manager'.DIRECTORY_SEPARATOR;
    }

}
