<?php

namespace Entity;

/**
 * Tag Model
 *
 * @Entity
 * @Table(name="tag")
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Tag
{

	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @Column(type="string", length=40, nullable=false)
	 */
	protected $slug;

	/**
	 * @Column(type="string", length=40, nullable=false)
	 */
	protected $name;

	/**
	 * @ManyToMany(targetEntity="Entry", mappedBy="tags")
	 */
	protected $entries;

	public function __construct()
	{
		$this->entries = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @param $entries
	 */
	public function setEntries($entries)
	{
		$this->entries = $entries;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getEntries()
	{
		return $this->entries;
	}

	/**
	 * @param $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param $slug
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;
	}

	/**
	 * @return mixed
	 */
	public function getSlug()
	{
		return $this->slug;
	}

    /**
     * Add entries
     *
     * @param Entity\Entry $entries
     * @return Tag
     */
    public function addEntry(\Entity\Entry $entries)
    {
        $this->entries[] = $entries;
        return $this;
    }
}