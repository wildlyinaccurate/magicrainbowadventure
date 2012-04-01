<?php

class Error extends Front_controller {

	public function not_found()
	{
		$this->template->title(lang('not_found'))
			->layout('templates/error')
			->build('error/not-found');
	}

}