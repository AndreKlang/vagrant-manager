<?php

namespace App\Command;

use App\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputOption;
use Herrera\Phar\Update\Manager;
use Herrera\Phar\Update\Manifest;
use Herrera\Phar\Update\Exception\FileException;

class SelfupdateCommand extends Command
{
    const MANIFEST_FILE = 'https://andreklang.github.io/vagrant-manager/manifest.json';

    protected function configure(){
        $this
            ->setName('self-update')
            ->setDescription('Update vagrant-manager to latest version')
            ->addOption('major', null, InputOption::VALUE_NONE, 'Allow major version update')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        try {
            $manifest = Manifest::loadFile(self::MANIFEST_FILE);
            $manager = new Manager($manifest);
        } catch (FileException $e) {
            $output->writeln('<error>Unable to search for updates</error>');
            return 1;
        }
        $currentVersion = $this->getApplication()->getVersion();
        $allowMajor = $input->getOption('major');
        if ($manager->update($currentVersion, $allowMajor)) {
            $output->writeln('<info>Updated to latest version</info>');
        } else {
            $output->writeln('<comment>Already up-to-date</comment>');
        }
    }
}
