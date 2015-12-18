<?php
namespace Mehulchaudhari\Geoip\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
class Geoip extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('mehulchaudhari:geoip');
        $this->setDescription('A cli playground for geoip commands');
        parent::configure();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getObjectManager();
        $object  = $manager->create('Mehulchaudhari\Geoip\Model\Geoip');
        $message = $object->run();
        $output->writeln($message);   
    }
}
