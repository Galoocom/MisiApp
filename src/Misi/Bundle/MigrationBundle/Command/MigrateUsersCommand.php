<?php

namespace Misi\Bundle\MigrationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sylius\Bundle\CoreBundle\Model\User;

class MigrateUsersCommand extends ContainerAwareCommand
{
    protected $em;
    protected $conn;
    
    protected function configure()
    {
        $this
                ->setName('misi:migrate:users')
                ->setDescription('Migrates users from legacy db')
                ->addOption('limit', 0, InputOption::VALUE_OPTIONAL, 'How many users to migrate? Default ALL')
                ->addOption('userId', 0, InputOption::VALUE_OPTIONAL, 'Id of the user to migrate')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em   = $this->getContainer()->get('doctrine')->getManager();
        $this->conn = $this->getContainer()->get('doctrine.dbal.legacy_connection');
        
        $userId = (int)$input->getOption('userId');
        $limit  = (int)$input->getOption('limit');
        $count  = 0;
        
        $sql = '
            SELECT * 
            FROM freetplclassified_members AS fm
            LEFT JOIN xf_user_authenticate AS xua ON fm.user_id = xua.user_id
        ';
        
        if (is_numeric($userId) && $userId != 0) {
            $sql .= " WHERE fm.user_id = {$userId}";
        }
        
        $stmt = $this->conn->query($sql);

        while ($row = $stmt->fetch()) {
            $output->writeln("Migrating user (#{$row['user_id']}) {$row['username']}");
            
            $user = new User();
        
            $user->setUsername($row['username']);
            $user->setEmail($row['user_email']);
            $user->setFirstName($row['fname']);
            $user->setLastName($row['lname']);
            
            if ($row['suspended'] == 'no' && $row['is_banned'] == '0') {
                $user->setEnabled(true);
            }
            
            if ($row['is_admin'] == '1') {
                $user->setRoles(array('ROLE_SYLIUS_ADMIN'));
            } else {
                $user->setRoles(array('ROLE_USER'));
            }
            
            $dateRegistered = new \DateTime();
            $dateRegistered->setTimestamp($row['register_date']);
            $user->setCreatedAt($dateRegistered);
            
            
            $authData = unserialize($row['data']);
            $user->setPassword($authData['hash']);
            
            if (array_key_exists('salt', $authData)) {
                $user->setSalt($authData['salt']);
            }
            
            if ($row['scheme_class'] == 'XenForo_Authentication_PhpBb3') {
                $user->setLegacyPassword(true);
            }
            
            $this->em->persist($user);
            
            $count++;
            
            if ($limit && $limit <= $count) {
                break;
            }
        }
        
        $this->em->flush();
    }
}
