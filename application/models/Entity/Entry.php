<?php

namespace Entity;

use MagicRainbowAdventure\Helpers\ImageHelper;

/**
 * Entry Model
 *
 * @Entity(repositoryClass="Entity\EntryRepository")
 * @Table(name="entry", indexes={@index(name="entry_type_idx", columns={"type"})})
 * @author	Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Entry extends TimestampedModel
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
	 * @Column(type="string", length=80, nullable=false)
	 */
	protected $file_path;

	/**
	 * @Column(type="string", length=6, nullable=false)
	 */
	protected $type;

	/**
	 * @Column(type="integer", nullable=false)
	 */
	protected $image_width;

	/**
	 * @Column(type="integer", nullable=false)
	 */
	protected $image_height;

	/**
	 * @Column(type="string", length=40, nullable=false)
	 */
	protected $hash;

	/**
	 * @Column(type="text", length=2000, nullable=true)
	 */
	protected $description;

	/**
	 * @Column(type="smallint", nullable=false)
	 */
	protected $approved = 0;

	/**
	 * @ManyToOne(targetEntity="User", inversedBy="entries", fetch="EAGER")
	 */
	protected $user;

	/**
	 * @ManyToOne(targetEntity="Administrator", inversedBy="moderated_entries", fetch="EXTRA_LAZY")
	 */
	protected $moderated_by;

	/**
	 * @ManyToMany(targetEntity="User", inversedBy="favourites", fetch="EXTRA_LAZY")
	 * @JoinTable(name="entry_favourites",
	 *      joinColumns={@JoinColumn(name="entry_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@JoinColumn(name="user_id", referencedColumnName="id")}
	 * )
	 */
	protected $favourited_by;

	/**
	 * @ManyToMany(targetEntity="Tag", inversedBy="entries")
	 * @JoinTable(name="entry_tags",
	 *      joinColumns={@JoinColumn(name="entry_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@JoinColumn(name="tag_id", referencedColumnName="id")}
	 * )
	 */
	protected $tags;

	/**
	 * Thumbnail tool for getting thumbnail paths and URLs
	 * @var	\MagicRainbowAdventure\Tools\EntryThumbnailTool
	 */
	private $thumbnail_tool;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->tags = new \Doctrine\Common\Collections\ArrayCollection;
		$this->favourited_by = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * Return the URL for a thumbnail. If no size is provided, the URL for
	 * the full-size image will be returned.
	 *
	 * @param	string	$size
	 * @return	string
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function getThumbnailUrl($size = null)
	{
		if ($this->thumbnail_tool === null)
		{
			$this->thumbnail_tool = new \MagicRainbowAdventure\Tools\EntryThumbnailTool($this);
		}

		return $this->thumbnail_tool->getThumbnailUrl($size);
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
	 * Set title and url_title
	 *
	 * @param string $title
	 * @return	\Entity\Entry
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		$this->setUrlTitle(\Str::slug($title));

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
	 * Set hash
	 *
	 * @param string $hash
	 * @return  \Entity\Entry
	 */
	public function setHash($hash)
	{
		$this->hash = $hash;
		return $this;
	}

	/**
	 * Get hash
	 *
	 * @return string
	 */
	public function getHash()
	{
		return $this->hash;
	}

	/**
	 * Set url_title
	 *
	 * @param string $urlTitle
	 * @return	\Entity\Entry
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
	 * Set file_path
	 *
	 * At the same time, determine whether this is an animated GIF
	 *
	 * @param string $filePath
	 * @return	\Entity\Entry
	 */
	public function setFilePath($filePath)
	{
		$this->file_path = $filePath;

		$base_path = \Config::get('magicrainbowadventure.entry_uploads_path');
		$this->type = (ImageHelper::isAnimatedGif($base_path . '/' . $filePath)) ? 'gif' : 'image';

		return $this;
	}

	/**
	 * Get file_path
	 *
	 * @return string
	 */
	public function getFilePath()
	{
		return $this->file_path;
	}

	/**
	 * Set image_width
	 *
	 * @param	int		$imageWidth
	 * @return	\Entity\Entry
	 */
	public function setimageWidth($imageWidth)
	{
		$this->image_width = $imageWidth;

		return $this;
	}

	/**
	 * Get image_width
	 *
	 * @return	int
	 */
	public function getimageWidth()
	{
		return $this->image_width;
	}

	/**
	 * Set image_height
	 *
	 * @param	int		$imageHeight
	 * @return	\Entity\Entry
	 */
	public function setImageHeight($imageHeight)
	{
		$this->image_height = $imageHeight;

		return $this;
	}

	/**
	 * Get image_height
	 *
	 * @return	int
	 */
	public function getImageHeight()
	{
		return $this->image_height;
	}

	/**
	 * Set type
	 *
	 * @param string $type
	 * @return	\Entity\Entry
	 */
	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * Get type
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Set description
	 *
	 * @param text $description
	 * @return	\Entity\Entry
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * Get description
	 *
	 * @return text
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set user
	 *
	 * @param	User	$user
	 * @return	Entry
	 */
	public function setUser(User $user)
	{
		$user->addEntry($this);
		$this->user = $user;
		return $this;
	}

	/**
	 * Get user
	 *
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Set approved
	 *
	 * @param boolean $approved
	 * @return	\Entity\Entry
	 */
	public function setApproved($approved)
	{
		$this->approved = $approved;
		return $this;
	}

	/**
	 * Get approved
	 *
	 * @return boolean
	 */
	public function getApproved()
	{
		return $this->approved;
	}

	/**
	 * Alias for getApproved()
	 *
	 * @return	boolean
	 */
	public function isApproved()
	{
		return (bool) $this->approved;
	}

	/**
	 * Add favourited_by
	 *
	 * @param Entity\User $user
	 * @return Entry
	 */
	public function addFavouritedBy(\Entity\User $user)
	{
		if ( ! $this->favourited_by->contains($user))
		{
			$this->favourited_by[] = $user;
			$user->addFavourite($this);
		}

		return $this;
	}

	/**
	 * Get favourited_by
	 *
	 * @return Doctrine\Common\Collections\Collection
	 */
	public function getFavouritedBy()
	{
		return $this->favourited_by;
	}

	/**
	 * Set moderated_by
	 *
	 * @param	\Entity\User $user
	 * @return	\Entity\Entry
	 */
	public function setModeratedBy(\Entity\User $user)
	{
		$this->moderated_by = $user;
		$user->addModeratedEntry($this);

		return $this;
	}

	/**
	 * Get moderated_by
	 *
	 * @return	\Entity\User
	 */
	public function getModeratedBy()
	{
		return $this->moderated_by;
	}

    /**
     * Add tags
     *
     * @param Entity\Tag $tags
     * @return Entry
     */
    public function addTag(\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
        return $this;
    }

    /**
     * Get tags
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

}
