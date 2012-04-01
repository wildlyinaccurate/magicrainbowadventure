<?php

class Error_Controller extends Base_Controller {

	public function not_found()
	{
		$this->template->title(lang('not_found'))
			->layout('templates/error')
			->build('error/not-found');
	}

}