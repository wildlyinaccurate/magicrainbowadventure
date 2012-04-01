<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ratings Class
 *
 * Allows guests to the website to rate Entries by storing the EntryRating objects
 * in the session.
 *
 * @package		CuteStuff
 * @subpackage	Libraries
 * @author	Joseph Wynn <joseph@wildlyinaccurate.com>
 * @since		Version 1.1
 */
class Ratings {

	/**
	 * CodeIgniter instance
	 * @var \CI_Controller
	 */
	private $CI;

	/**
	 * The userdata session key for temporary ratings
	 * @var string
	 */
	private $entry_rating_session_key;

	/**
	 * The temporary ratings currently in session
	 * @var array
	 */
	private $temporary_ratings = array();

	/**
	 * Constructor
	 */
	public function __construct($params = array())
	{
		$this->CI =& get_instance();

		$this->entry_rating_session_key = $this->CI->config->item('entry_rating_session_key');

		if ($temporary_ratings = $this->CI->session->userdata($this->entry_rating_session_key))
		{
			$this->temporary_ratings = $temporary_ratings;
		}
	}
	
	/**
	 * See if the User has a temporary rating for the given Entry
	 *
	 * @param   \Entity\Entry    $entry
	 * @return  bool|\Entity\EntryRating    FALSE if there is no temporary rating for the Entry
	 */
	public function find_by_entry(\Entity\Entry $entry)
	{
        if ($this->CI->authenticated)
	    {
		    // Check for an EntryRating on this Entry by the current User
			$entry_rating = $this->CI->em->getRepository('Entity\EntryRating')->findOneBy(array(
				'entry' => $entry->getId(),
				'user' => $this->CI->user->getId()
			));

		    // No EntryRating exists, so create a new one
			if ( ! $entry_rating)
			{
				$entry_rating = new \Entity\EntryRating;
				$entry_rating->setUser($this->CI->user)
					->setEntry($entry);
			}
	    }
	    else
	    {
		    // User isn't logged in. Check the session for an EntryRating on this Entry
		    if ( ! $entry_rating = $this->_find_temporary_rating($entry))
		    {
				// We didn't find an existing rating, so create a new one for this Entry
				$entry_rating = new \Entity\EntryRating;
				$entry_rating->setEntry($entry);
		    }
	    }

        return $entry_rating;
	}

	/**
	 * Save the EntryRating to this User's temporary ratings.
	 *
	 * @param   Entity\EntryRating  $entry_rating
	 * @return  void
	 */
	public function save_rating(\Entity\EntryRating $entry_rating)
	{
		// Save the rating
		$this->temporary_ratings[$entry_rating->getId()] = $entry_rating->getEntry()->getId();

		// Update the session with the current temporary ratings
		$this->CI->session->set_userdata($this->entry_rating_session_key, $this->temporary_ratings);
	}

	/**
	 * Assign all temporary ratings to a User, and then remove them from session
	 *
	 * @param   Entity\User $user
	 * @return  void
	 */
	public function assign_to_user(\Entity\User $user)
	{
		foreach ($this->temporary_ratings as $entry_rating_id => $entry_id)
		{
			$entry_rating = $this->CI->em->find('\Entity\EntryRating', $entry_rating_id);
			$entry_rating->setUser($user);
		}

		$this->CI->session->unset_userdata($this->entry_rating_session_key);
	}

	/**
	 * Find a temporary EntryRating for this User/Entry
	 *
	 * @param   \Entity\Entry    $entry
	 * @return  bool|\Entity\EntryRating
	 */
	private function _find_temporary_rating(\Entity\Entry $entry)
	{
		foreach ($this->temporary_ratings as $entry_rating_id => $entry_id)
		{
			if ($entry_id == $entry->getId())
			{
				// This user has already rated this entry, get the EntryRating from the DB and return it
				return $this->CI->em->find('\Entity\EntryRating', $entry_rating_id);
			}
		}

		// User doesn't have a temporary rating for this Entry
		return FALSE;
	}

}

/* End of file Temporary_ratings.php */
/* Location: ./application/libraries/Temporary_ratings.php */