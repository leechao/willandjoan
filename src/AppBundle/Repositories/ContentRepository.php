<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 12/09/2015
 * Time: 15:35
 */
namespace AppBundle\Repositories;
use AppBundle\Entity\Project;
use Doctrine\ORM\EntityRepository;
class ContentRepository extends EntityRepository
{

    public function getNewOrder(Project $project)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('MAX(c.order)')
            ->where('c.project = :project')
            ->setParameter('project', $project->getId());
        return $qb->getQuery()->getOneOrNullResult();
    }
}