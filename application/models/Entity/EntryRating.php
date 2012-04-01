<?php

namespace Entity;

/**
 * EntryRating
 *
 * @Entity
 * @Table(name="entry_rating")
 * @author	Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class EntryRating extends TimestampedModel implements \Serializable {

	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="User", inversedBy="entry_ratings")
	 */
	protected $user;

	/**
	 * @ManyToOne(targetEntity="Entry", inversedBy="ratings")
	 */
	protected $entry;

    /**
     * @var bool
     * @Column(type="boolean", nullable=false)
     */
    protected $funny = FALSE;

    /**
     * @var bool
     * @Column(type="boolean", nullable=false)
     */
    protected $cute = FALSE;

	/**
	 * @var int
	 * @Column(type="smallint", nullable=false)
	 */
	protected $rating;

	/**
	 * Override the default behaviour when this object is serialized
	 *
	 * @return  string
	 */
	public function serialize()
	{
		return serialize(array(
			'id' => $this->getId(),
		    'entry' => $this->getEntry(),
		    'cute' => $this->getCute(),
		    'funny' => $this->getFunny(),
		    'rating' => $this->getRating(),
		));
	}

	/**
	 * Override the defeault behaviour when this object is unserialized
	 *
	 * @param   string  $data
	 * @return  void
	 */
	public function unserialize($data)
	{
		$data = unserialize($data);

		$this->id = $data['id'];
		$this->entry = $data['entry'];
		$this->cute = $data['cute'];
		$this->funny = $data['funny'];
		$this->rating = $data['rating'];
	}

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param	\Entity\User $user
	 * @return	\Entity\EntryRating
     */
    public function setUser(\Entity\User $user)
    {
        $this->user = $user;
		$user->addEntryRating($this);
		return $this;
    }

    /**
     * Get user
     *
     * @return	\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set entry
     *
     * @param	\Entity\Entry $entry
	 * @return	\Entity\EntryRating
     */
    public function setEntry(\Entity\Entry $entry)
    {
        $this->entry = $entry;
		$entry->addRating($this);
		return $this;
    }

    /**
     * Get entry
     *
     * @return	\Entity\Entry 
     */
    public function getEntry()
    {
        return $this->entry;
    }


    /**
     * Set rating
     *
     * @param smallint $rating
	 * @return	\Entity\EntryRating
     */
    public function setRating($rating)
    {
        if ((int) $rating < 1)
        {
            $rating = 1;
        }
        elseif ((int) $rating > 5)
        {
            $rating = 5;
        }

        $this->rating = (int) $rating;
		return $this;
    }

    /**
     * Get rating
     *
     * @return smallint
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set funny
     *
     * @param   bool    $funny
     * @return	\Entity\EntryRating
     */
    public function setFunny($funny)
    {
        $this->funny = (bool) $funny;
    }

    /**
     * Get funny
     *
     * @return  bool
     */
    public function getFunny()
    {
        return (bool) $this->funny;
    }

    /**
     * Set cute
     *
     * @param   bool    $cute
     * @return	\Entity\EntryRating
     */
    public function setCute($cute)
    {
        $this->cute = (bool) $cute;
    }

    /**
     * Get cute
     *
     * @return  bool
     */
    public function getCute()
    {
        return (bool) $this->cute;
    }
}