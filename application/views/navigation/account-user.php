<ul>
	<li><?=HTML::link('account', Auth::user()->getDisplayName())?></li>
	<li><?=HTML::link('account/logout', Lang::line('general.log_out'))?></li>
</ul>
