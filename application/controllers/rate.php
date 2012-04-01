<?php

class Rate_Controller extends Base_Controller {

    /**
     * @var array
     */
    private $errors = array();

	/**
	 * Valid rating methods
	 * @var array
	 */
	private $valid_methods = array('cute', 'funny');

	/**
	 * Constructor
	 */
    public function __construct()
    {
        parent::__construct();

		$this->load->library('ratings');
    }

	/**
	 * Override CodeIgniter's default URI mapping
	 *
	 * @param   string  $method
	 * @param   array   $parameters
	 * @return  void
	 */
	public function _remap($method, $parameters = array())
	{
		// We expect the URI to be something like /rate/type/entry_id (e.g. /rate/funny/123)
		$entry_id = $this->uri->segment(3);
		$rating_value = Input::get('value');

		// Make sure the method provided is valid
		if ( ! in_array($method, $this->valid_methods))
		{
			$this->errors[] = 'Sorry, we were unable to process your request.';
			$this->_display_errors();
		}

		// Make sure the entry exists
        if ( ! $entry = $this->em->getRepository('Entity\Entry')->find($entry_id))
        {
	        $this->errors[] = "The entry you rated doesn't seem to exist anymore!";
			$this->_display_errors();
        }

		// Get the EntryRating for this User/Entry. If none exists, a new one will be created
		$entry_rating = $this->ratings->find_by_entry($entry);

		switch ($method)
		{
			case 'cute':
				$entry_rating->setCute($rating_value);
				break;
			case 'funny':
				$entry_rating->setFunny($rating_value);
				break;
			default:
				// We shouldn't get here, because $method is validated!
				$this->errors[] = 'Sorry, we were unable to process your request.';
				$this->_display_errors();
		}

		// Save the changes
		$this->em->persist($entry_rating);
		$this->em->flush();

		// If the user isn't logged in, we need to store this EntryRating in session for them
		if ( ! $this->authenticated)
		{
			$this->ratings->save_rating($entry_rating);
		}

		// Success!
		if (IS_AJAX)
		{
			$this->output->send_ajax_response(array(
				'EntryRating' => array(
					'id' => $entry_rating->getId(),
					'entry_id' => $entry_rating->getEntry()->getId(),
					'cute' => $entry_rating->getCute(),
					'funny' => $entry_rating->getFunny(),
				)
			));
		}
		else
		{
			$entry = $entry_rating->getEntry();
			redirect("{$entry->getId()}/{$entry->getUrlTitle()}");
		}
	}

	/**
	 * Some errors occurred; either pass them back via AJAX or set a status message
	 * and redirect the user to the home page.
	 *
	 * NOTE: This method implies that the request itself was fine and should only return
	 * a 200 or 500 status code.
	 *
	 * @return void
	 */
	private function _display_errors()
	{
		if (IS_AJAX)
		{
			$this->output->send_ajax_response(array('errors' => $this->errors), TRUE);
		}
		else
		{
			set_message(implode('<br />', $this->errors), 'error');
			redirect('/');
		}
	}

}

/* End of file rate.php */
/* Location: ./application/controllers/rate.php */
