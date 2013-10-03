<?php

/**
 * CreatePDOSessionTableCommand class
 *
 *
 */
namespace App\GeneralBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

/**
 * Create database table for storing session in database
 * if PDO session handler is enabled
 *
 *
 */
class CreatePDOSessionTableCommand extends ContainerAwareCommand
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this
            ->setName('app:create-pdo-session-table')
            ->setDescription('Create database session table if not exists');
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Console\Command\Command::execute()
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isInstance = $this->getContainer()->get('session.handler') instanceof PdoSessionHandler;
        if ($isInstance && $this->getContainer()->has('pdo')) {
            $output->writeln("Creating session database table if not exists.");
            $params = $this->getContainer()->getParameter('pdo.db_options');
            $pdo = $this->getContainer()->get('pdo');
            $sql = sprintf(
                "CREATE TABLE IF NOT EXISTS %s (%s VARCHAR(255) PRIMARY KEY, %s TEXT, %s INTEGER)",
                $params['db_table'],
                $params['db_id_col'],
                $params['db_data_col'],
                $params['db_time_col']
            );
            $pdo->exec($sql);
        }
    }
}
