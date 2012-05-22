<?php

namespace Entity;

/**
 * Administrator
 *
 * @Entity
 * @author	Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Administrator extends User
{

	/**
	 * @OneToMany(targetEntity="Entry", mappedBy="moderated_by", fetch="EXTRA_LAZY")
	 */
	protected $moderated_entries;

	/**
	 * @OneToMany(targetEntity="Comment", mappedBy="moderated_by", fetch="EXTRA_LAZY")
	 */
	protected $moderated_comments;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->moderated_entries = new \Doctrine\Common\Collections\ArrayCollection;
		$this->moderated_comments = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * Add approved entry
	 *
	 * @param   \Entity\Entry   $entry
	 * @return  \Entity\Administrator
	 */
	public function addModeratedEntry(\Entity\Entry $entry)
	{
		$this->moderated_entries[] = $entry;
		return $this;
	}

	/**
	 * Get all entries
	 *
	 * @return  Doctrine\Common\Collections\Collection $entries
	 */
	public function getModeratedEntries()
	{
		return $this->moderated_entries;
	}

	/**
	 * Add approved comment
	 *
	 * @param	\Entity\Comment 	$comment
	 * @return	\Entity\Administrator
	 */
	public function addModeratedComment(\Entity\Comment $comment)
	{
		$this->moderated_comments[] = $entry;
		return $this;
	}

	/**
	 * Get all comments
	 *
	 * @return	Doctrine\Common\Collections\Collection $comments
	 */
	public function getModeratedComment()
	{
		return $this->moderated_comments;
	}

}
