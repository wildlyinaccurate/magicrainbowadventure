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

    public function addModeratedComment(\Entity\Comment $comment)
    {
        $this->__load();
        return parent::addModeratedComment($comment);
    }

    public function getModeratedComment()
    {
        $this->__load();
        return parent::getModeratedComment();
    }

    public function setPassword($password)
    {
        $this->__load();
        return parent::setPassword($password);
    }

    public function hashPassword($password)
    {
        $this->__load();
        return parent::hashPassword($password);
    }

    public function checkPassword($password)
    {
        $this->__load();
        return parent::checkPassword($password);
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

    public function getDisplayName($fallbackToUsername = true)
    {
        $this->__load();
        return parent::getDisplayName($fallbackToUsername);
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

    public function addFavourite(\Entity\Entry $entry)
    {
        $this->__load();
        return parent::addFavourite($entry);
    }

    public function getFavourites()
    {
        $this->__load();
        return parent::getFavourites();
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

    public function addComment(\Entity\Comment $comment)
    {
        $this->__load();
        return parent::addComment($comment);
    }

    public function getComments()
    {
        $this->__load();
        return parent::getComments();
    }

    public function addUserSetting(\Entity\UserSetting $settings)
    {
        $this->__load();
        return parent::addUserSetting($settings);
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


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'username', 'password', 'email', 'display_name', 'created_date', 'modified_date', 'settings', 'entries', 'comments', 'favourites', 'moderated_entries', 'moderated_comments');
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
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}