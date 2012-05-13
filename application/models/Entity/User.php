<?php

namespace Entity;

/**
 * User
 *
 * @Entity(repositoryClass="Entity\UserRepository")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string", length=15)
 * @DiscriminatorMap({"user" = "User", "administrator" = "Administrator"})
 * @Table(name="user")
 * @author	Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class User extends TimestampedModel
{

	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	public $id;

	/**
	 * @Column(type="string", length=32, unique=true, nullable=false)
	 */
	protected $username;

	/**
	 * @Column(type="string", length=128, nullable=false)
	 */
	protected $password;

	/**
	 * @Column(type="string", length=255, unique=true, nullable=false)
	 */
	protected $email;

	/**
	 * @Column(type="string", length=160, nullable=true)
	 */
	protected $display_name;

	/**
	 * @OneToMany(targetEntity="UserSetting", mappedBy="user", cascade={"persist", "remove"})
	 */
	protected $settings;

	/**
	 * @OneToMany(targetEntity="Entry", mappedBy="user", cascade={"persist", "remove"})
	 */
	protected $entries;

	/**
	 * @OneToMany(targetEntity="Comment", mappedBy="user", cascade={"persist", "remove"})
	 */
	protected $comments;

	/**
	 * @ManyToMany(targetEntity="Entry", mappedBy="favourites", cascade={"persist", "remove"})
	 */
	protected $favourites;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->settings = new \Doctrine\Common\Collections\ArrayCollection;
        $this->entries = new \Doctrine\Common\Collections\ArrayCollection;
        $this->favourites = new \Doctrine\Common\Collections\ArrayCollection;
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection;
    }

	/**
	 * Encrypt the password before we store it
	 *
	 * @access	public
	 * @param	string	$password
	 * @return	User
	 */
	public function setPassword($password)
	{
		$this->password = $this->encryptPassword($password);
        return $this;
	}

	/**
	 * Encrypt the user's password, using their username as a salt
	 *
	 * @static
	 * @param	string	$password
	 * @return	string
	 * @throws \BadMethodCallException
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function encryptPassword($password)
	{
		if ( ! $this->username)
		{
			throw new \BadMethodCallException('User password cannot be encrypted until username has been set.');
		}

		return hash('sha512', $password . $this->username);
	}

	/**
	 * Returns TRUE if the user is an administrator
	 *
	 * @access	public
	 * @return	bool
	 */
	public function isAdmin()
	{
		return $this instanceof Administrator;
	}

    /**
     * Get id
     *
     * @return	integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param	string 	$username
     * @return	\Entity\User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return	string $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get password
     *
     * @return	string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param	string 	$email
     * @return	\Entity\User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return	string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set display_name
     *
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->display_name = $displayName;
    }

	/**
	 * Return the user's display name. If the user doesn't have a display
	 * name, their username can be returned instead.
	 *
	 * @param	bool	$fallbackToUsername
	 * @return	string
	 */
	public function getDisplayName($fallbackToUsername = true)
	{
		if ($this->display_name || $fallbackToUsername === false)
		{
			return $this->display_name;
		}
		else
		{
			return $this->getUsername();
		}
	}

    /**
     * Add settings
     *
     * @param	\Entity\UserSetting 	$setting
     * @return	\Entity\User
     */
	public function addSetting(\Entity\UserSetting $setting)
	{
        $this->settings[] = $setting;
        return $this;
    }

    /**
     * Get all settings
     *
     * @return	Doctrine\Common\Collections\Collection $settings
     */
    public function getSettings()
    {
        return $this->settings;
    }

	/**
	 * Add entries
	 *
	 * @param Entry $entry
	 * @return User
	 */
	public function addFavourite(\Entity\Entry $entry)
	{
		if ( ! $this->favourites->contains($entry))
		{
			$this->favourites[] = $entry;
			$entry->addFavouritedBy($this);
		}

		return $this;
	}

	/**
	 * Get favourites
	 *
	 * @return Doctrine\Common\Collections\Collection
	 */
	public function getFavourites()
	{
		return $this->favourites;
	}

    /**
     * Add entries
     *
     * @param	Entry 	$entry
     * @return	User
     */
	public function addEntry(Entry $entry)
	{
		if ( ! $this->entries->contains($entry))
		{
			$this->entries[] = $entry;
		}

        return $this;
    }

    /**
     * Get all entries
     *
     * @return	Doctrine\Common\Collections\Collection $entries
     */
    public function getEntries()
    {
        return $this->entries;
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

    /**
     * Add settings
     *
     * @param \Entity\UserSetting $settings
     * @return User
     */
    public function addUserSetting(\Entity\UserSetting $settings)
    {
        $this->settings[] = $settings;
        return $this;
    }
}
