<?php

namespace Entity;

/**
 * @Entity
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class EntrySeries extends TimestampedModel
{

	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @Column(type="string", length=140, nullable=false)
	 */
	protected $title;

	/**
	 * @Column(type="string", length=140, nullable=false)
	 */
	protected $url_title;

	/**
	 * @OneToMany(targetEntity="Entry", mappedBy="series")
	 */
	protected $entries;

	/**
	 * @Column(type="text", length=1000, nullable=true)
	 */
	protected $description;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->entries = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * Set title
	 *
	 * @param string $title
	 * @return EntrySeries
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Set url_title
	 *
	 * @param string $urlTitle
	 * @return EntrySeries
	 */
	public function setUrlTitle($urlTitle)
	{
		$this->url_title = $urlTitle;
		return $this;
	}

	/**
	 * Get url_title
	 *
	 * @return string
	 */
	public function getUrlTitle()
	{
		return $this->url_title;
	}

	/**
	 * Add entries
	 *
	 * @param Entity\Entry $entries
	 * @return EntrySeries
	 */
	public function addEntry(\Entity\Entry $entry)
	{
		$this->entries[] = $entry;
		return $this;
	}

	/**
	 * Get entries
	 *
	 * @return Doctrine\Common\Collections\Collection
	 */
	public function getEntries()
	{
		return $this->entries;
	}
}
