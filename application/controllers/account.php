<?php

/**
 * Account Controller
 *
 * Sign up, log in, view submitted entries.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Account_Controller extends Base_Controller
{

	public $restful = true;

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

		$this->layout->title = Lang::line('general.account');
		$this->layout->content = View::make('account/index', array(
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

		$this->layout->title = Lang::line('general.my_entries');
		$this->layout->content = View::make('account/my-entries', array(
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

		$this->form_validation->set_rules('email', Lang::line('account.field_email'), 'required|valid_email|callback__unique_email');
		$this->form_validation->set_rules('display_name', Lang::line('account.display_name'), 'max_length[160]');
		$this->form_validation->set_rules('country', Lang::line('account.field_country'), 'callback__valid_country');
		$this->form_validation->set_rules('language', Lang::line('account.field_language'), 'callback__valid_language');

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
			set_message(Lang::line('account.settings_saved'), 'success');
			Redirect::to('account/settings');
		}

		$this->layout->title = Lang::line('account.settings');
		$this->layout->content = View::make('account/settings', array(
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

		$this->form_validation->set_rules('password', Lang::line('account.field_current_password'), 'required|callback__correct_password');
		$this->form_validation->set_rules('new_password', Lang::line('account.field_new_password'), 'min_length[6]|matches[password_confirm]');
		$this->form_validation->set_message('matches', Lang::line('account.validation_matches'));

		if ($this->form_validation->run())
		{
			// Update the user's settings
			$this->user->setPassword(Input::get('new_password'));
			$this->em->persist($this->user);
			$this->em->flush();

			// Set a success message and redirect the user
			set_status(Lang::line('account.password_changed'), 'success');
			Redirect::to('account');
		}

		$this->layout->title = Lang::line('account.change_password');
		$this->layout->content = View::make('account/change-password', array(
			'user' => $this->user
		));
	}

	/**
	 * Show the login form
	 *
	 * @return	void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_login()
	{
		$this->layout->title = Lang::line('general.log_in');
		$this->layout->content = View::make('account/login');
	}

	/**
	 * Log into the system
	 */
	public function post_login()
	{
		// Attempt to log in
		$identifier = Input::get('identifier');
		$password = Input::get('password');

		if ( ! Auth::attempt($identifier, $password))
		{
			// The page title changes if the user has been redirected to the login page
			if (Input::get('return'))
			{
				$this->layout->title = 'Login Required';
			}

			$this->layout->title = Lang::line('account.log_in');
			$this->layout->content = View::make('account/login', array(
			 ));
		}
		else
		{
			// Successful login - redirect to the previous page (if it is set)
			if ($return = Input::get('return'))
			{
				Redirect::to($return);
			}

			// No return URI was set; redirect to the default user page
			Redirect::to($this->config->item('default_user_page'));
		}
	}

	/**
	 * Log out of the system
	 */
	public function get_logout()
	{
		$this->auth->logout();
		Redirect::to($this->config->item('default_guest_page'));
	}

	/**
	 * Show the registration form
	 *
	 * @return	void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_signup()
	{
		$this->layout->title = Lang::line('general.sign_up');
		$this->layout->content = View::make('account/signup');
	}

	/**
	 * Register a new user
	 *
	 * @return	Laravel\Redirect
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function post_signup()
	{
		// If a user is already signed in, redirect them to the account index
		if (Auth::check())
		{
			return Redirect::to('account');
		}

		$validation_rules = array(
			'username' => 'required|alpha_dash|max:32|unique:user',
			'password' => 'required|min:5|same:password_confirm',
			'email' => 'required|email|unique:user',
			'display_name' => 'required|max:160',
		);

		$validation_messages = array(
			'password_same' => 'The passwords you enter don\'t match',
		);

		$validation = Validator::make(Input::all(), $validation_rules, $validation_messages);

		if ($validation->fails())
		{
			return Redirect::to('account/signup')->with_input()->with_errors($validation);
		}
		else
		{
			$user = new Entity\User;
			$user->setUsername(Input::get('username'))
				->setPassword(Input::get('password'))
				->setEmail(Input::get('email'))
				->setDisplayName(Input::get('display_name'));

			// Transfer over any temporary entry ratings
//			$this->load->library('ratings');
//			$this->ratings->assign_to_user($user);

			$this->em->persist($user);
			$this->em->flush();

			// Authenticate the User
			Auth::login($user->getId());

			$this->layout->title = Lang::line('account.welcome');
			$this->layout->content = View::make('account/welcome');
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
		$this->form_validation->set_message('_correct_password', Lang::line('general.validation_current_password'));
		return FALSE;
	}
}

/* End of file account.php */
/* Location: ./application/controllers/account.php */
