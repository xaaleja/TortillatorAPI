<?php
namespace TortillatorAPI\TortillatorBundle\Entity;

use Doctrine\ORM\EntityRepository;


class VotesRepository extends EntityRepository
{
  public function findNumVotesTortilla($idTortilla)
  {
      $em = $this->getEntityManager();

      $query = $em->createQuery('
        SELECT count(v.idTortilla)
        FROM TortillatorAPITortillatorBundle:Votes v
        WHERE v.idTortilla = :idTortilla');
      $query->setParameter('idTortilla', $idTortilla);

      return $query->getSingleScalarResult();
  }

}