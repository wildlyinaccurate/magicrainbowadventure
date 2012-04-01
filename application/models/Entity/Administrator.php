<?php

namespace Entity;

/**
 * Administrator
 *
 * @Entity
 * @author	Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Administrator extends User implements \Serializable {

	/**
	 * @OneToMany(targetEntity="Entry", mappedBy="moderated_by", fetch="EXTRA_LAZY")
	 */
	protected $moderated_entries;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->moderated_entries = new \Doctrine\Common\Collections\ArrayCollection;
    }

	public function serialize() { return parent::serialize(); }
	public function unserialize($data) { return parent::unserialize($data); }


    /**
     * Add approved entry
     *
     * @param	\Entity\Entry 	$entry
     * @return	\Entity\Administrator
     */
	public function addModeratedEntry(\Entity\Entry $entry)
	{
        $this->moderated_entries[] = $entry;
        return $this;
    }

    /**
     * Get all entries
     *
     * @return	Doctrine\Common\Collections\Collection $entries
     */
    public function getModeratedEntries()
    {
        return $this->moderated_entries;
    }

}