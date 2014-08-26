<?php
namespace TortillatorAPI\TortillatorBundle\Entity;

use Doctrine\ORM\EntityRepository;


class TortillaRepository extends EntityRepository
{
   /*public function findTortillas()
   {
      $em = $this->getEntityManager();

      $consulta = $em->createQuery('
          SELECT t
          FROM TortillatorAPITortillatorBundle:Tortilla t');
      return $consulta->getResult();

   }
   public function findTortilla($id)
   {
      $em = $this->getEntityManager();

      $consulta = $em->createQuery('
        SELECT t
        FROM TortillatorAPITortillatorBundle:Tortilla t
        WHERE t.id=:id');
      $consulta->setParameter('id', $id);
      return $consulta->getOneOrNullResult();
   }
   public function findTortillasVotes($id)
   {
      $em = $this->getEntityManager();

      $consulta = $em->createQuery('
        SELECT v.rating
        FROM TortillatorAPITortillatorBundle:Votes v
        WHERE v.idTortilla=:id');
      $consulta->setParameter('id', $id);

      return $consulta->getResult();
   }
   public function findTortillasComments($id)
   {
      $em = $this->getEntityManager();

      $consulta = $em->createQuery('
        SELECT c.text
        FROM TortillatorAPITortillatorBundle:Comments c
        WHERE c.idTortilla=:id');
      $consulta->setParameter('id', $id);

      return $consulta->getResult();
   }*/

  public function findUsersTortillas($username)
  {
      $em = $this->getEntityManager();

      $query = $em->createQuery('
        SELECT t
        FROM TortillatorAPITortillatorBundle:Tortilla t, TortillatorAPITortillatorBundle:Votes v
        WHERE v.user = :username AND t.id = v.idTortilla');
      $query->setParameter('username', $username);

      return $query->getResult();
  }

  public function findRecommendations($username)
  {
      $em = $this->getEntityManager();

      $query = $em->createQuery('
        SELECT t
        FROM TortillatorAPITortillatorBundle:Tortilla t,TortillatorAPITortillatorBundle:Votes v
        WHERE t.id = v.idTortilla AND v.idTortilla NOT IN (SELECT vo.idTortilla FROM TortillatorAPITortillatorBundle:Votes vo WHERE vo.user = :username)
        GROUP BY v.idTortilla
        ORDER BY t.average DESC');
      $query->setParameter('username', $username);
      $query->setMaxResults(5);
      return $query->getResult();
  }

}