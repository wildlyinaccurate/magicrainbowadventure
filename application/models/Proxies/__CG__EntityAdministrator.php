<?php

namespace Entity\Proxy\__CG__\Entity;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Administrator extends \Entity\Administrator implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function serialize()
    {
        $this->__load();
        return parent::serialize();
    }

    public function unserialize($data)
    {
        $this->__load();
        return parent::unserialize($data);
    }

    public function addModeratedEntry(\Entity\Entry $entry)
    {
        $this->__load();
        return parent::addModeratedEntry($entry);
    }

    public function getModeratedEntries()
    {
        $this->__load();
        return parent::getModeratedEntries();
    }

    public function setPassword($password)
    {
        $this->__load();
        return parent::setPassword($password);
    }

    public function authenticate()
    {
        $this->__load();
        return parent::authenticate();
    }

    public function isAdmin()
    {
        $this->__load();
        return parent::isAdmin();
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setUsername($username)
    {
        $this->__load();
        return parent::setUsername($username);
    }

    public function getUsername()
    {
        $this->__load();
        return parent::getUsername();
    }

    public function getPassword()
    {
        $this->__load();
        return parent::getPassword();
    }

    public function setEmail($email)
    {
        $this->__load();
        return parent::setEmail($email);
    }

    public function getEmail()
    {
        $this->__load();
        return parent::getEmail();
    }

    public function setDisplayName($displayName)
    {
        $this->__load();
        return parent::setDisplayName($displayName);
    }

    public function getDisplayName()
    {
        $this->__load();
        return parent::getDisplayName();
    }

    public function addSetting(\Entity\UserSetting $setting)
    {
        $this->__load();
        return parent::addSetting($setting);
    }

    public function getSettings()
    {
        $this->__load();
        return parent::getSettings();
    }

    public function addEntry(\Entity\Entry $entry)
    {
        $this->__load();
        return parent::addEntry($entry);
    }

    public function getEntries()
    {
        $this->__load();
        return parent::getEntries();
    }

    public function addEntryRating(\Entity\EntryRating $entry_rating)
    {
        $this->__load();
        return parent::addEntryRating($entry_rating);
    }

    public function getEntryRatings()
    {
        $this->__load();
        return parent::getEntryRatings();
    }

    public function setCreatedDate()
    {
        $this->__load();
        return parent::setCreatedDate();
    }

    public function getCreatedDate()
    {
        $this->__load();
        return parent::getCreatedDate();
    }

    public function setModifiedDate()
    {
        $this->__load();
        return parent::setModifiedDate();
    }

    public function getModifiedDate()
    {
        $this->__load();
        return parent::getModifiedDate();
    }

    public function toArray()
    {
        $this->__load();
        return parent::toArray();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'username', 'password', 'email', 'display_name', 'created_date', 'modified_date', 'settings', 'entries', 'entry_ratings', 'moderated_entries');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}