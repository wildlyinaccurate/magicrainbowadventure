<ul>
	<li><?=HTML::link('account', Auth::user()->getDisplayName(), array('class' => 'display-name text-overflow'))?></li>
	<li><?=HTML::link('account/logout', Lang::line('general.log_out'))?></li>
</ul>
