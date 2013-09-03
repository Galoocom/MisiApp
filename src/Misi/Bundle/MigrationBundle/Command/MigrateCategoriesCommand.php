<?php

namespace Misi\Bundle\MigrationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sylius\Bundle\CoreBundle\Model\Taxon;
use \SplFileInfo;

class MigrateCategoriesCommand extends ContainerAwareCommand
{

    protected $categoryIdMap = array();
    protected $categoryIdMap2 = array();
    protected $categoryIdMap3 = array();
    
    protected $em;
    protected $conn;

    protected function configure()
    {
        $this
                ->setName('misi:migrate:categories')
                ->setDescription('Migrates categories from legacy db')
        ;
        
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em   = $this->getContainer()->get('doctrine')->getManager();
        $this->conn = $this->getContainer()->get('doctrine.dbal.legacy_connection');

        $taxonomy = $this->getContainer()
                         ->get('sylius.repository.taxonomy')
                         ->findOneBy(array('name' => 'Category'));

        $finder = new Finder();
        $uploader = $this->getContainer()->get('sylius.image_uploader');
        $path = $this->getContainer()->getParameter('kernel.root_dir').'/../migration/images';
        
        
        $stmt = $this->conn->query("SELECT * FROM freetplclassified_nav");

        while ($row = $stmt->fetch()) {
            $output->writeln("Migrating category (#{$row['user_id']}) {$row['cat_name']}");
            
            $taxon = new Taxon();
            
            $taxon->setName(htmlspecialchars_decode($row['cat_name']));
            $taxon->setPageTitle(htmlspecialchars_decode($row['title']));
            $taxon->setMetaTitle(htmlspecialchars_decode($row['meta_title']));
            $taxon->setMetaDescription(htmlspecialchars_decode($row['meta_desc']));
            $taxon->setMetaKeywords(htmlspecialchars_decode($row['meta_keywords']));
            
            $img = new SplFileInfo($path . '/' . $row['img']);
            if ($img->isFile()) {
                $taxon->setFile(new UploadedFile($img->getRealPath(), $img->getFilename()));
                $uploader->upload($taxon);
            }
            
            $taxonomy->addTaxon($taxon);

            $this->em->persist($taxonomy);
            $this->em->flush();

            $this->categoryIdMap[$row['user_id']] = $taxon->getId();

        }

        $stmt = $this->conn->query("SELECT * FROM freetplclassified_navcats");
        while ($row = $stmt->fetch()) {
            $output->writeln("Migrating subcategory (#{$row['user_id']}) {$row['cat_name']}");
            
            $taxon = new Taxon();
            
            $taxon->setName(htmlspecialchars_decode($row['cat_name']));
            $taxon->setPageTitle(htmlspecialchars_decode($row['title']));
            $taxon->setMetaTitle(htmlspecialchars_decode($row['meta_title']));
            $taxon->setMetaDescription(htmlspecialchars_decode($row['meta_desc']));
            $taxon->setMetaKeywords(htmlspecialchars_decode($row['meta_keywords']));

            if ($row['parent'] != 0) {
                if (!array_key_exists($row['parent'], $this->categoryIdMap)) {
                    $output->writeln("Missing parent for (#{$row['user_id']}) {$row['cat_name']}, skipping");
                    continue;
                }
                $parent_id = $this->categoryIdMap[$row['parent']];
                $parent = $this->getContainer()
                               ->get('sylius.repository.taxon')
                               ->find($parent_id);
                
                $taxon->setParent($parent);
            }

            $taxon->setTaxonomy($taxonomy);
            
            $img = new SplFileInfo($path . '/' . $row['img']);
            if ($img->isFile()) {
                $taxon->setFile(new UploadedFile($img->getRealPath(), $img->getFilename()));
                $uploader->upload($taxon);
            }
            
            
            $this->em->persist($taxon);
            $this->em->flush();
            
            $this->categoryIdMap2[$row['user_id']] = $taxon->getId();
        }
        
        $stmt = $this->conn->query("SELECT * FROM freetplclassified_navbaby");
        while ($row = $stmt->fetch()) {
            $output->writeln("Migrating subsubcategory (#{$row['user_id']}) {$row['cat_name']}");
            
            $taxon = new Taxon();
            
            $taxon->setName(htmlspecialchars_decode($row['cat_name']));
            $taxon->setPageTitle(htmlspecialchars_decode($row['title']));
            $taxon->setMetaTitle(htmlspecialchars_decode($row['meta_title']));
            $taxon->setMetaDescription(htmlspecialchars_decode($row['meta_desc']));
            $taxon->setMetaKeywords(htmlspecialchars_decode($row['meta_keywords']));

            if ($row['parent'] != 0) {
                if (!array_key_exists($row['parent'], $this->categoryIdMap2)) {
                    $output->writeln("Missing parent for (#{$row['user_id']}) {$row['cat_name']}, skipping");
                    continue;
                }
                $parent_id = $this->categoryIdMap2[$row['parent']];
                $parent = $this->getContainer()
                               ->get('sylius.repository.taxon')
                               ->find($parent_id);
                
                $taxon->setParent($parent);
            }

            $taxon->setTaxonomy($taxonomy);
            
            $img = new SplFileInfo($path . '/' . $row['img']);
            if ($img->isFile()) {
                $taxon->setFile(new UploadedFile($img->getRealPath(), $img->getFilename()));
                $uploader->upload($taxon);
            }
            
            $this->em->persist($taxon);
            $this->em->flush();
            
            $this->categoryIdMap3[$row['user_id']] = $taxon->getId();
        }
        
        $stmt = $this->conn->query("SELECT * FROM freetplclassified_navseed");
        while ($row = $stmt->fetch()) {
            $output->writeln("Migrating subsubsubcategory (#{$row['user_id']}) {$row['cat_name']}");
            
            $taxon = new Taxon();
            
            $taxon->setName(htmlspecialchars_decode($row['cat_name']));
            $taxon->setPageTitle(htmlspecialchars_decode($row['title']));
            $taxon->setMetaTitle(htmlspecialchars_decode($row['meta_title']));
            $taxon->setMetaDescription(htmlspecialchars_decode($row['meta_desc']));
            $taxon->setMetaKeywords(htmlspecialchars_decode($row['meta_keywords']));

            if ($row['parent'] != 0) {
                if (!array_key_exists($row['parent'], $this->categoryIdMap3)) {
                    $output->writeln("Missing parent for (#{$row['user_id']}) {$row['cat_name']}, skipping");
                    continue;
                }
                $parent_id = $this->categoryIdMap3[$row['parent']];
                $parent = $this->getContainer()
                               ->get('sylius.repository.taxon')
                               ->find($parent_id);
                
                $taxon->setParent($parent);
            }

            $taxon->setTaxonomy($taxonomy);
            
            $img = new SplFileInfo($path . '/' . $row['img']);
            if ($img->isFile()) {
                $taxon->setFile(new UploadedFile($img->getRealPath(), $img->getFilename()));
                $uploader->upload($taxon);
            }
            
            $this->em->persist($taxon);
            $this->em->flush();
            
            $this->categoryIdMap4[$row['user_id']] = $taxon->getId();
        }
        
        $output->writeln("\nMigration successfull, total of ".count($this->categoryIdMap) + count($this->categoryIdMap2) + count($this->categoryIdMap3) + count($this->categoryIdMap4)." categories migrated");
    }

}
