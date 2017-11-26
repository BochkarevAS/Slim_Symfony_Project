<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SubFamilyRepository extends EntityRepository {

    public function createAlphabeticalQueryBuilder() {

        return $this->createQueryBuilder('sf')
            ->orderBy('sf.name', 'ASC');
    }
}