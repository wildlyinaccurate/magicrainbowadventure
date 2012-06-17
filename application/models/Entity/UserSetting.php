<?php

namespace Entity;

/**
 * UserSetting
 *
 * @Entity
 * @Table(name="user_setting")
 * @author	Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class UserSetting extends TimestampedModel
{

	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @Column(type="string", length=32, nullable=false)
	 */
	protected $name;

	/**
	 * @Column(type="string", length=16, nullable=false)
	 */
	protected $type;

	/**
	 * @Column(type="text", length=512, nullable=true)
	 */
	protected $value;

	/**
	 * @ManyToOne(targetEntity="User", inversedBy="settings")
	 */
	protected $user;

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
     * Set name
     *
     * @param	string $name
     * @return	\Entity\UserSetting
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return	string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param	string $type
     * @return	\Entity\UserSetting
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get name
     *
     * @return	string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set value
	 *
	 * Requires a type to be set first
     *
     * @param	string $value
     * @return	\Entity\UserSetting
     */
    public function setValue($value)
    {
		if ( ! $this->getType())
		{
			throw new \Entity\Exception('You cannot call UserSetting::setValue() until a type has been set with UserSetting::setType()');
		}

		$this->value = $this->setAsType($value, $this->getType());

        return $this;
    }

    /**
     * Get value
     *
     * @return	string $value
     */
    public function getValue()
    {
        return $this->getAsType($this->value, $this->getType());
    }

    /**
     * Set user
     *
     * @param	\Entity\User $user
     * @return	\Entity\UserSetting
     */
    public function setUser(\Entity\User $user)
    {
        $this->user = $user;
		$user->getSettings()->add($this);

        return $this;
    }

    /**
     * Get user
     *
     * @return	\Entity\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

	/**
	 * Return a typecasted variable
	 *
	 * @param	mixed	$value
	 * @param	string	$type
	 * @param	bool	$getting	Set as TRUE to treat this as a getter and unserialize() rather than serialize()
	 * @return	mixed
	 */
	private function setAsType($value, $type, $getting = FALSE)
	{
		switch ($type)
		{
			case 'bool':
			case 'boolean':
				$value = (bool) $value;
				break;
			case 'int':
			case 'integer':
				$value = (int) $value;
				break;
			case 'string':
				$value = (string) $value;
				break;
			case 'object':
			case 'array':
			case 'serialized':
				if ($getting)
				{
					$value = unserialize($value);
				}
				else
				{
					$value = serialize($value);
				}
				break;
			default:
        		$value = $value;
				break;
		}

		return $value;
	}

	/**
	 * Return a typecasted variable (alias for setAsType with $getter == TRUE)
	 *
	 * @param	mixed	$value
	 * @param	string	$type
	 * @return	mixed
	 */
	private function getAsType($value, $type)
	{
		return $this->setAsType($value, $type, TRUE);
	}

}
