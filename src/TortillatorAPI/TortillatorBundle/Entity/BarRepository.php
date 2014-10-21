<?php
namespace TortillatorAPI\TortillatorBundle\Entity;

use Doctrine\ORM\EntityRepository;


class BarRepository extends EntityRepository
{
    public function findBarsNearLocation($lat, $long)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
            SELECT b
            FROM TortillatorAPITortillatorBundle:Bar b
            WHERE b.latitude BETWEEN :latiT AND :latiH AND
                b.longitude BETWEEN :longT AND :longH');

        $query->setParameters(array('latiH' => $lat + 0.002, 'latiT' => $lat - 0.002, 'longH' => $long + 0.002, 'longT' => $long - 0.002));
        $query->setMaxResults(5);
        return $query->getResult();
    }

    public function findRecommendedBarsNearLocation($username, $lat, $long)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
            SELECT b
            FROM TortillatorAPITortillatorBundle:Bar b
            WHERE b.latitude BETWEEN :latiT AND :latiH AND
                b.longitude BETWEEN :longT AND :longH');

        $query->setParameters(array('latiH' => $lat + 0.002, 'latiT' => $lat - 0.002, 'longH' => $long + 0.002, 'longT' => $long - 0.002));
        $query->setMaxResults(5);
        return $query->getResult();
    }

    public function findVotedBarsNearLocation($username, $lat, $long)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
            SELECT b
            FROM TortillatorAPITortillatorBundle:Bar b
            WHERE b.latitude BETWEEN :latiT AND :latiH AND
                b.longitude BETWEEN :longT AND :longH');

        $query->setParameters(array('latiH' => $lat + 0.002, 'latiT' => $lat - 0.002, 'longH' => $long + 0.002, 'longT' => $long - 0.002));
        $query->setMaxResults(5);
        return $query->getResult();
    }
}