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
class User extends TimestampedModel implements \Serializable
{

	/**
	 *
	 * @var string
	 */
	protected static $encryption_key = 'q@.qi)h23~U`Yu&&yzy,R$/bR=5g^bGC0\"s`}0D$iY$XAa9tL</}UPX(N9Mm2#';

	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @Column(type="string", length=32, unique=true, nullable=false)
	 */
	protected $username;

	/**
	 * @Column(type="string", length=64, nullable=false)
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
	 * @OneToMany(targetEntity="Entry", mappedBy="user", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
	 */
	protected $entries;

	/**
	 * @OneToMany(targetEntity="EntryRating", mappedBy="user", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
	 */
	protected $entry_ratings;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->settings = new \Doctrine\Common\Collections\ArrayCollection;
        $this->entries = new \Doctrine\Common\Collections\ArrayCollection;
        $this->entry_ratings = new \Doctrine\Common\Collections\ArrayCollection;
    }

	/**
	 * Override the default behaviour when this object is serialized
	 *
	 * @return  string
	 */
	public function serialize()
	{
		return serialize(array(
			'id' => $this->getId(),
			'username' => $this->getUsername(),
			'email' => $this->getEmail(),
			'display_name' => $this->getDisplayName(),
			'settings' => $this->getSettings(),
			'entries' => $this->getEntries(),
			'entry_ratings' => $this->getEntryRatings()
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
		$this->username = $data['username'];
		$this->email = $data['email'];
		$this->display_name = $data['display_name'];
		$this->settings = $data['settings'];
		$this->entries = $data['entries'];
		$this->entry_ratings = $data['entry_ratings'];
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
		$encrypted_password = self::encryptPassword($password);

		$this->password = $encrypted_password;
        return $this;
	}

	/**
	 * Encrypt a Password
	 *
	 * @access	public
	 * @param	string	$password
	 */
	public static function encryptPassword($password)
	{
		return sha1($password . self::$encryption_key);
	}

	/**
	 * Authenticate this User by setting self::current to $this
	 *
	 * @return	void
	 */
	public function authenticate()
	{
		self::$current = $this;
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
     * Set display_name (make sure we encode UTF8 characters)
     *
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->display_name = utf8_encode($displayName);
    }

	/**
	 * Return the user's display name (display_name, or if it isn't set; username)
	 *
	 * @return	string
	 */
	public function getDisplayName()
	{
		if ($this->display_name)
		{
			return utf8_decode($this->display_name);
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
     * @param	\Entity\Entry 	$entry
     * @return	\Entity\User
     */
	public function addEntry(\Entity\Entry $entry)
	{
        $this->entries[] = $entry;
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
     * Add entry_rating
     *
     * @param	\Entity\EntryRating 	$entry_rating
     * @return	\Entity\User
     */
	public function addEntryRating(\Entity\EntryRating $entry_rating)
	{
        $this->entry_ratings[] = $entry_rating;
        return $this;
    }

    /**
     * Get all entry_ratings
     *
     * @return	Doctrine\Common\Collections\ArrayCollection $entry_ratings
     */
    public function getEntryRatings()
    {
        return $this->entry_ratings;
    }

}
