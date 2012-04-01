<?php

namespace Entity;

use \Doctrine\ORM\EntityRepository;

/**
 * CountryRepository
 */
class CountryRepository extends EntityRepository
{
	public function getAllCountries()
	{
		return $this->_em->createQuery('SELECT c FROM Entity\Country c ORDER BY c.name')
						 ->getResult();
	}
}