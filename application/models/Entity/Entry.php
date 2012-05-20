<?php

namespace Entity;

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
	protected $type = 'image';

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
	 * @ManyToMany(targetEntity="User", inversedBy="favourites")
	 * @JoinTable(name="entry_favourites",
	 *      joinColumns={@JoinColumn(name="entry_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@JoinColumn(name="user_id", referencedColumnName="id")}
	 * )
	 */
	protected $favourited_by;

	/**
	 * @OneToMany(targetEntity="Comment", mappedBy="user", cascade={"persist", "remove"})
	 */
	protected $comments;

	/**
	 * @ManyToMany(targetEntity="Tag", inversedBy="entries")
	 * @JoinTable(name="entry_tags",
	 *      joinColumns={@JoinColumn(name="entry_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@JoinColumn(name="tag_id", referencedColumnName="id")}
	 * )
	 */
	protected $tags;

	/**
	 * When the Entry's file is set, thumbnails in these sizes will be retrieved
	 * from Dropbox and stored locally.
	 *
	 * @var array
	 */
	private static $thumbnail_sizes = array(
		array(
			'size' => 'medium',
			'types' => 'image|gif'
		),
		array(
			'size' => 'l',
			'types' => 'image'
		),
		array(
			'size' => 'xl',
			'types' => 'image'
		),
	);

	/**
	 * Format to use when saving and retrieving thumbnails.
	 * @var string
	 */
	private static $thumbnail_format = 'JPEG';

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->comments = new \Doctrine\Common\Collections\ArrayCollection;
		$this->tags = new \Doctrine\Common\Collections\ArrayCollection;
		$this->favourited_by = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * Return a cache key for this Entry's thumbnail
	 *
	 * @return	string
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	private function _getThumbnailCacheKey()
	{
		return "/Entity/Entry/Thumbnail/{$this->hash}";
	}

	/**
	 * Upload the Entry's file to Dropbox, and store various sized
	 * thumbnails locally.
	 *
	 * @param  string 	$file_path
	 * @param  string 	$extension
	 * @return Entry
	 */
	public function setFile($file_path, $extension)
	{
		$file_hash = hash_file('sha1', $file_path);
		$file_name = "{$file_hash}.{$extension}";
		$entry_file_path = \Config::get('magicrainbowadventure.dropbox_base_path') . '/' . date('Y/m');

		$this->setHash($file_hash)
			->setFilePath("{$entry_file_path}/{$file_name}");

		// Upload the file to dropbox
		$dropbox = \IoC::resolve('dropbox::api');
		$dropbox->putFile($file_path, $file_name, "Public/{$entry_file_path}");

		// Determine if the image is an animated GIF
		if (\Helpers\ImageHelper::isAnimatedGif($file_path))
		{
			$this->type = 'gif';
		}

		// Retrieve various thumbnails and store them locally
		foreach (self::$thumbnail_sizes as $thumbnail)
		{
			$thumbnail_types = explode('|', $thumbnail['types']);

			if (in_array($this->type, $thumbnail_types))
			{
				$this->_downloadThumbnail($thumbnail['size']);
			}
		}

		return $this;
	}

	/**
	 * Build the thumbnail directory from a base path
	 *
	 * @param	string	$base
	 * @param	string	$size
	 * @return	string
	 */
	private function _getThumbnailPath($base, $size)
	{
		return dirname($base . "/{$this->file_path}") . "/{$size}/{$this->getHash()}." . strtolower(self::$thumbnail_format);
	}

	/**
	 * Download a thumbnail from Dropbox and store it locally
	 *
	 * @param	string	$size
	 * @return	void
	 */
	private function _downloadThumbnail($size)
	{
		$dropbox = \IoC::resolve('dropbox::api');

		$thumbnail = $dropbox->thumbnails("Public/{$this->file_path}", self::$thumbnail_format, $size);
		$thumbnail_path = $this->_getThumbnailPath(\Config::get('magicrainbowadventure.thumbnail_cache_path'), $size);
		$thumbnail_dir = dirname($thumbnail_path);

		if ( ! is_dir($thumbnail_dir))
		{
			mkdir($thumbnail_dir, 0777, true);
		}

		file_put_contents($thumbnail_path, $thumbnail['data']);
	}

	/**
	 * Get the public Dropbox URL for an Entry
	 *
	 * @return	string
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function getDropboxURL()
	{
		return 'http://dl.dropbox.com/u/' . \Config::get('dropbox::config.access_token.uid') . '/' . $this->getFilePath();
	}

	/**
	 * Get the public URL for a thumbnail.
	 *
	 * @param	string	$size
	 * @return	string
	 */
	public function getThumbnailUrl($size)
	{
		foreach (self::$thumbnail_sizes as $thumbnail)
		{
			if ($thumbnail['size'] !== $size)
			{
				continue;
			}

			$thumbnail_types = explode('|', $thumbnail['types']);

			if (in_array($this->type, $thumbnail_types))
			{
				return $this->_getThumbnailPath(\Config::get('magicrainbowadventure.thumbnail_cache_url'), $size);
			}
		}

		// This entry doesn't have the requested thumbnail size; return the full-size URL
		return $this->getDropboxURL();
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
	 * @param string $filePath
	 * @return	\Entity\Entry
	 */
	public function setFilePath($filePath)
	{
		$this->file_path = $filePath;
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

	/**
	 * Add comment
	 *
	 * @param	\Entity\Comment 	$comment
	 * @return	\Entity\User
	 */
	public function addComment(\Entity\Comment $comment)
	{
		if ( ! $this->comments->contains($comment))
		{
			$this->comments[] = $comment;
			$comment->setUser($this);
		}

		return $this;
	}

	/**
	 * Get all comments
	 *
	 * @return	Doctrine\Common\Collections\Collection $comments
	 */
	public function getComments()
	{
		return $this->comments;
	}

}
