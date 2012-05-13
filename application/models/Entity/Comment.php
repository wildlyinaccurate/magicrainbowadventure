<?php

namespace Entity;

/**
 * Comment Model
 *
 * @Entity
 * @Table(name="comment")
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Comment
{

	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @Column(type="text", length=2000, nullable=false)
	 */
	protected $content;

	/**
	 * @ManyToOne(targetEntity="User", inversedBy="comments")
	 */
	protected $user;

	/**
	 * @ManyToOne(targetEntity="Entry", inversedBy="comments")
	 */
	protected $entry;

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
	 * @param	string $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param	Entry $entry
	 */
	public function setEntry($entry)
	{
		$this->entry = $entry;
	}

	/**
	 * @return Entry
	 */
	public function getEntry()
	{
		return $this->entry;
	}

	/**
	 * @param	User $user
	 */
	public function setUser($user)
	{
		$this->user = $user;
	}

	/**
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}
}
