<?php

class Account_Controller extends Base_Controller {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Account Index
	 */
	public function action_index()
	{
		$this->auth->require_login();

		$this->layout->title(lang('account'))
			->build('account/index', array(
				'user' => $this->user
			));
	}

	/**
	 * List all User's Entries
	 */
	public function action_my_entries()
	{
		$entries = $this->user->getEntries();

		// Count the 'cute' and 'funny' ratings
		foreach ($entries as $entry)
		{
			$entry->funny_ratings = $entry->getRatings()->filter(function($entry_rating) {
				return $entry_rating->getFunny();
			});

			$entry->cute_ratings = $entry->getRatings()->filter(function($entry_rating) {
				return $entry_rating->getCute();
			});
		}

		$this->layout->title(lang('my_entries'))
			->build('account/my-entries', array(
				'entries' => $entries,
				'thumb_width' => $this->config->item('thumb_width'),
				'thumb_height' => $this->config->item('thumb_height')
			));
	}

	/**
	 * Update Account Settings
	 */
	public function action_settings()
	{
		$this->auth->require_login();

		$this->form_validation->set_rules('email', lang('field_email'), 'required|valid_email|callback__unique_email');
		$this->form_validation->set_rules('display_name', lang('display_name'), 'max_length[160]');
		$this->form_validation->set_rules('country', lang('field_country'), 'callback__valid_country');
		$this->form_validation->set_rules('language', lang('field_language'), 'callback__valid_language');

		if ($this->form_validation->run())
		{
			// Update the user's settings
			$this->user->setEmail(Input::get('email'));
			$this->user->setDisplayName(Input::get('display_name'));
			$this->user->setLanguage(Input::get('language'));

			// Find the selected country
			$country = $this->em->getRepository('Entity\Country')->find(Input::get('country'));
			$this->user->setCountry($country);

			$this->em->persist($this->user);
			$this->em->flush();

			// Set a success message and redirect the user
			set_message(lang('settings_saved'), 'success');
			redirect('account/settings');
		}

		$this->layout->title(lang('settings'))
			->build('account/settings', array(
				'user' => $this->user,
				'countries' => $this->em->getRepository('Entity\Country')->getAllCountries(),
				'languages' => $this->config->item('available_languages')
			));
	}

	/**
	 * Change the user's password
	 */
	public function action_change_password()
	{
		$this->auth->require_login();

		$this->form_validation->set_rules('password', lang('field_current_password'), 'required|callback__correct_password');
		$this->form_validation->set_rules('new_password', lang('field_new_password'), 'min_length[6]|matches[password_confirm]');
		$this->form_validation->set_message('matches', lang('validation_matches'));

		if ($this->form_validation->run())
		{
			// Update the user's settings
			$this->user->setPassword(Input::get('new_password'));
			$this->em->persist($this->user);
			$this->em->flush();

			// Set a success message and redirect the user
			set_status(lang('password_changed'), 'success');
			redirect('account');
		}

		$this->layout->title(lang('change_password'))
			->build('account/change-password', array(
				'user' => $this->user
			));
	}

	/**
	 * Log into the system
	 */
	public function action_login()
	{
		// Attempt to log in
		$identifier = Input::get('identifier');
		$password = Input::get('password');
		$login = FALSE; //Initial value

		if ( ! Auth::attempt($identifier, $password))
		{
			// The page title changes if the user has been redirected to the login page
			if (Input::get('return'))
			{
				$this->layout->title(lang('log_in_required'));
			}

			$this->layout
				 ->title(lang('log_in'))
				 ->build('account/login', array(
				 	'login' => $login,
				 ));
		}
		else
		{
			// Successful login - redirect to the previous page (if it is set)
			if ($return = Input::get('return'))
			{
				redirect($return);
			}

			// No return URI was set; redirect to the default user page
			redirect($this->config->item('default_user_page'));
		}
	}

	/**
	 * Log out of the system
	 */
	public function logout()
	{
		$this->auth->logout();
		redirect($this->config->item('default_guest_page'));
	}

	/**
	 * Register a new user
	 */
	public function signup()
	{
		// If a user is already signed in, redirect them to the account index
		if ($this->authenticated)
		{
			redirect('account');
		}

		$this->form_validation->set_rules('username', lang('field_username'), 'required|alpha_dash|max_length[32]|callback__unique_username');
		$this->form_validation->set_rules('password', lang('field_password'), 'required|min_length[6]|matches[password_confirm]');
		$this->form_validation->set_rules('email', lang('field_email'), 'required|valid_email|callback__unique_email');
		$this->form_validation->set_rules('display_name', lang('field_display_name'), 'max_length[160]');
		$this->form_validation->set_rules('country', lang('field_country'), 'callback__valid_country');
		$this->form_validation->set_message('matches', lang('validation_matches'));

		if ($this->form_validation->run() === FALSE)
		{
			$this->layout
				 ->title(lang('sign_up'))
				 ->build('account/signup', array(
				 	'selected_country' => ! Input::get('country') ? $this->config->item('default_country') : Input::get('country'),
				 	'countries' => $this->em->getRepository('Entity\Country')->getAllCountries()
				 ));
		}
		else
		{
			// Create the new user
			$user = new \Entity\User;
			$user->setUsername(Input::get('username'));
			$user->setPassword(Input::get('password'));
			$user->setEmail(Input::get('email'));
			$user->setDisplayName(Input::get('display_name'));

			// Set the user's language
			$language_cookie = $this->input->cookie($this->config->item('language_cookie'));
			$default_language = $this->config->item('default_language');
			$user->setLanguage($language_cookie ? $language_cookie : $default_language);

			// Set the User's country
			$country = $this->em->getRepository('Entity\Country')->find(Input::get('country'));
			$user->setCountry($country);

			// Create the User's default settings
			$default_settings = $this->config->item('default_user_settings');

			foreach ($default_settings as $setting_name => $setting_data)
			{
				$setting = new \Entity\UserSetting;
				$setting->setName($setting_name);
				$setting->setType($setting_data['type']);
				$setting->setValue($setting_data['value']);
				$setting->setUser($user);
			}

			// Transfer over any temporary entry ratings
			$this->load->library('ratings');
			$this->ratings->assign_to_user($user);

			$this->em->persist($user);
			$this->em->flush();

			// Authenticate the User
			$this->auth->authenticate($user);

			redirect($this->config->item('default_user_page'));
		}
	}

	/**
	 * Validate a Username
	 *
	 * Check if a User with that username already exists
	 *
	 * @access	public
	 * @param	string	$username
	 * @return	bool
	 */
	public function _unique_username($username)
	{
		$user = $this->em->getRepository('Entity\User')->findOneByUsername($username);

		if ( ! $user)
		{
			// The username is free
			return TRUE;
		}

		$this->form_validation->set_message('_unique_username', 'That username is not available.');
		return FALSE;
	}

	/**
	 * Check that the email is not already in use
	 *
	 * If a user is currently logged in, we don't consider their own email to be in use
	 *
	 * @access	public
	 * @param	string	$email
	 * @return	bool
	 */
	public function _unique_email($email)
	{
		$user = $this->em->getRepository('Entity\User')->findOneByEmail($email);

		if ( ! $user || $this->authenticated && $this->user->getEmail() == $user->getEmail())
		{
			return TRUE;
		}

		// The email is already in use
		$this->form_validation->set_message('_unique_email', 'That email is already in use.');
		return FALSE;
	}

	/**
	 * Check that the language specified exists in the available_languages config item
	 *
	 * @access	public
	 * @param	string	$language
	 * @return	bool
	 */
	public function _valid_language($language)
	{
		if ( ! array_key_exists($language, $this->config->item('available_languages')))
		{
			// Invalid language specified
			$this->form_validation->set_message('_valid_country', 'Please select a language from the drop-down list.');
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Check that the specified country exists in the DB
	 *
	 * @access	public
	 * @param	string	$country
	 * @return	bool
	 */
	public function _valid_country($country)
	{
		$country = $this->em->getRepository('Entity\Country')->find($country);

		if ( ! $country)
		{
			// The country doesn't exist
			$this->form_validation->set_message('_valid_country', 'Please select a country from the drop-down list.');
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Check that the password provided is the current user's password
	 *
	 * @param	string	$password
	 * @return 	bool
	 */
	public function _correct_password($password)
	{
		if ($this->authenticated && \Entity\User::encryptPassword($password) == $this->user->getPassword())
		{
			return TRUE;
		}

		// Password is incorrect
		$this->form_validation->set_message('_correct_password', lang('validation_current_password'));
		return FALSE;
	}
}

/* End of file account.php */
/* Location: ./application/controllers/account.php */
