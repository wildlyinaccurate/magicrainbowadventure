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

		$this->layout->title = Lang::line('account.my_entries');
		$this->layout->content = View::make('account/my-entries', array(
			'entries' => $entries,
		));
	}

	/**
	 * Show the account settings form
	 *
	 * @return	void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_settings()
	{
		$this->layout->title = Lang::line('account.settings');
		$this->layout->content = View::make('account/settings', array(
			'user' => $this->user,
		));
	}

	/**
	 * Validate and update the user's account settings
	 *
	 * @return  \Laravel\Redirect
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function post_settings()
	{
		$validation_rules = array(
			'username' => "required|alpha_dash|max:32|unique:user,username,{$this->user->getId()}",
			'email' => "required|email|unique:user,email,{$this->user->getId()}",
			'display_name' => 'max:160',
			'current_password' => "required|current_password_correct:{$this->user->getId()}",
		);

		$validation = \Validators\UserValidator::make(Input::all(), $validation_rules);

		if ($validation->fails())
		{
			return Redirect::to('account/settings')->with_input()->with_errors($validation);
		}
		else
		{
			// Update the user's settings
			$this->user->setUsername(Input::get('username'));
			$this->user->setEmail(Input::get('email'));
			$this->user->setDisplayName(Input::get('display_name'));

			// Re-set the password in case the username has changed
			$this->user->setPassword(Input::get('current_password'));

			$this->em->persist($this->user);
			$this->em->flush();

			// Set a success message and redirect the user
			return Redirect::to('account/settings')->with('success_message', Lang::line('account.settings_saved'));
		}
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
		$login_attempt = array(
			'username' => Input::get('identifier'),
			'password' => Input::get('password'),
			'remember' => true,
		);

		if ( ! Auth::attempt($login_attempt))
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
