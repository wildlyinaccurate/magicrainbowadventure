<?php

class Error_Controller extends Base_Controller {

	public function not_found()
	{
		$this->layout->title(lang('not_found'))
			->layout('layouts/error')
			->build('error/not-found');
	}

}
