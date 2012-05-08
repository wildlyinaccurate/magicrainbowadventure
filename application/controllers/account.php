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

		$this->filter('before', 'auth')->except(array(
			'login',
			'signup',
		));
	}

	/**
	 * Account Index
	 *
	 * @return  void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_index()
	{
		$this->layout->title = Lang::line('general.account');
		$this->layout->content = View::make('account/index', array(
			'user' => $this->user,
		));
	}

	/**
	 * List all User's Entries
	 *
	 * @return  void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_my_entries()
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
	 *
	 * @return  void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_settings()
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
	 *
	 * @return  void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_change_password()
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

			return Redirect::to('account');
		}

		$this->layout->title = Lang::line('account.change_password');
		$this->layout->content = View::make('account/change-password', array(
			'user' => $this->user
		));
	}

	/**
	 * Show the login form
	 *
	 * @return	Laravel\Redirect
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_login()
	{
		if (Auth::check())
		{
			return Redirect::to('account');
		}

		$this->layout->title = Lang::line('general.log_in');
		$this->layout->content = View::make('account/login', array(
			'error' => Session::get('error'),
			'referrer' => Session::get('auth_referrer') ?: Request::referrer(),
		));
	}

	/**
	 * Log into the system
	 *
	 * @return  Laravel\Redirect
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function post_login()
	{
		// Attempt to log in
		$identifier = Input::get('identifier');
		$password = Input::get('password');

		if ( ! Auth::attempt($identifier, $password, true))
		{
			return Redirect::to('login')->with_input()->with('error', Lang::line('account.login_failed'));
		}
		else
		{
			// Successful login - redirect to the previous page (if it is set)
			if ($return = Input::get('referrer'))
			{
				return Redirect::to($return);
			}

			// No return URI was set; redirect to the default user page
			return Redirect::to('/');
		}
	}

	/**
	 * Log out of the system
	 *
	 * @return	Laravel\Redirect
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_logout()
	{
		Auth::logout();

		return Redirect::home();
	}

	/**
	 * Show the registration form
	 *
	 * @return	\Laravel\Redirect
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_signup()
	{
		// If a user is already signed in, redirect them to the account index
		if (Auth::check())
		{
			return Redirect::to('account');
		}

		$this->layout->title = Lang::line('general.sign_up');
		$this->layout->content = View::make('account.signup');
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
			'display_name' => 'max:160',
		);

		$validation_messages = array(
			'password_same' => Lang::line('account.validation_password_same'),
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

			$this->em->persist($user);
			$this->em->flush();

			// Log the new user in
			Auth::login($user->getId());

			return Redirect::to('account')->with('message', Lang::line('account.welcome_message'));
		}
	}

}
