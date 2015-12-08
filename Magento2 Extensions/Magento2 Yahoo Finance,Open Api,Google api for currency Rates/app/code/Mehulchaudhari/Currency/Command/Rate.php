<?php
namespace Mehulchaudhari\Currency\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Rate extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('mehulchaudhari:currency');
        $this->setDescription('A cli playground for currency rate commands');
        parent::configure();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getObjectManager();
        $object  = $manager->create('Mehulchaudhari\Currency\Model\Yahoo');
        $message = $object->_convert('USD', 'INR', $retry=0);
        $output->writeln($message);   
    }
}
