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
            if(array_key_exists(md5($importConfig->getName()).".json", $configsAsArray)){
                $output->writeln("<info>Importing existing ".$importConfig->getName()."</info>");
                $importConfig->setConfig($configsAsArray[md5($importConfig->getName()).".json"]["config"]);
                $importConfig->save();
                unset($configsAsArray[md5($importConfig->getName()).".json"]);
            }
        }

        if(!empty($configsAsArray)){
            foreach ($configsAsArray as $configAsArray){

                if(empty($configAsArray["name"])){
                    $output->writeln("skipping configuration... ".print_r($configAsArray));
                    continue;
                }
                $output->writeln("<info>Importing new config ".$configAsArray["name"]."</info>");
                $importConfig = new ImportConfig();
                $importConfig->setName($configAsArray["name"]);
                $importConfig->setClassId($configAsArray["classId"]);
                $importConfig->setOwnerId($configAsArray["ownerId"]);
                $importConfig->setShareGlobally($configAsArray["shareGlobally"]);
                $importConfig->setConfig($configAsArray["config"]);
                $importConfig->setDescription($configAsArray["description"]);
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
            if($file === ".gitkeep"){
                continue;
            }
            $fileContent = file_get_contents($this->getSavePath().$file);
            $result[$file] = json_decode($fileContent, true);
        }

        return $result;
    }

    private function getSavePath(){
        return PIMCORE_PRIVATE_VAR.DIRECTORY_SEPARATOR.'import_config_manager'.DIRECTORY_SEPARATOR;
    }

}
