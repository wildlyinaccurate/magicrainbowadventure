<?php

namespace Entity;

/**
 * Country
 *
 * @Entity(repositoryClass="Entity\CountryRepository", readOnly=true)
 * @Table(name="country")
 * @author	Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Country
{
    /**
     * @var string $iso
     *
     * @Column(name="iso", type="string", length=2, nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    protected $iso;

    /**
     * @var string $name
     *
     * @Column(name="name", type="string", length=80, nullable=false)
     */
    protected $name;

    /**
     * @var string $printableName
     *
     * @Column(name="printable_name", type="string", length=80, nullable=false)
     */
    protected $printableName;

    /**
     * @var string $iso3
     *
     * @Column(name="iso3", type="string", length=3, nullable=true)
     */
    protected $iso3;

    /**
     * @OneToMany(targetEntity="User", mappedBy="country", fetch="EXTRA_LAZY")
     */
    protected $users;

	/**
	 * Constructor
	 */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection;
    }

    public function getName()
    {
    	return ucwords(strtolower($this->name));
    }

    /**
     * Get iso
     *
     * @return	string $iso
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * Get printableName
     *
     * @return	string $printableName
     */
    public function getPrintableName()
    {
        return $this->printableName;
    }

    /**
     * Get iso3
     *
     * @return	string $iso3
     */
    public function getIso3()
    {
        return $this->iso3;
    }

    /**
     * Get users
     *
     * @return	Doctrine\Common\Collections\Collection $users
     */
    public function getUsers()
    {
        return $this->users;
    }


    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set printableName
     *
     * @param string $printableName
     */
    public function setPrintableName($printableName)
    {
        $this->printableName = $printableName;
    }

    /**
     * Set iso3
     *
     * @param string $iso3
     */
    public function setIso3($iso3)
    {
        $this->iso3 = $iso3;
    }

    /**
     * Add users
     *
     * @param	\Entity\User $user
     */
    public function addUser(\Entity\User $user)
    {
        $this->users[] = $user;
    }
}