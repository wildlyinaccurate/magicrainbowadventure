<?php

namespace MagicRainbowAdventure\Tools\Pagination;

/**
 * Magic Rainbow Adventure Paginator
 *
 * Extends the Laravel Paginator to provide custom HTML.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Paginator extends \Laravel\Paginator
{

	/**
	 * The "dots" element used in the pagination slider.
	 *
	 * @var string
	 */
	protected $dots = '<li class="disabled"><a href="#">...</a></li>';

	/**
	 * Build a range of numeric pagination links.
	 *
	 * For the current page, an HTML span element will be generated instead of a link.
	 *
	 * @param	int		$start
	 * @param	int		$end
	 * @return	string
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	protected function range($start, $end)
	{
		$pages = array();

		for ($page = $start; $page <= $end; $page++)
		{
			$class = ($this->page == $page) ? 'active' : '';
			$html = <<<HTML
			<li class="{$class}">{$this->link($page, $page, null)}</li>
HTML;

			$pages[] = $html;
		}

		return implode(' ', $pages);
	}

	/**
	 * Create a chronological pagination element, such as a "previous" or "next" link.
	 *
	 * @param	string	$element
	 * @param	int 	$page
	 * @param	string	$text
	 * @param	Closure	$disabled
	 * @return	string
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	protected function element($element, $page, $text, $disabled)
	{
		$class = "{$element}_page";

		if (is_null($text))
		{
			$text = \Laravel\Lang::line("pagination.{$element}")->get($this->language);
		}

		$class = ($disabled($this->page, $this->last)) ? 'disabled' : '';

		return <<<HTML
		<li class="{$class}">{$this->link($page, $text, null)}</li>
HTML;
	}

	/**
	 * Create the HTML pagination links.
	 *
	 * Typically, an intelligent, "sliding" window of links will be rendered based
	 * on the total number of pages, the current page, and the number of adjacent
	 * pages that should rendered. This creates a beautiful paginator similar to
	 * that of Google's.
	 *
	 * Example: 1 2 ... 23 24 25 [26] 27 28 29 ... 51 52
	 *
	 * If you wish to render only certain elements of the pagination control,
	 * explore some of the other public methods available on the instance.
	 *
	 * <code>
	 *		// Render the pagination links
	 *		echo $paginator->links();
	 *
	 *		// Render the pagination links using a given window size
	 *		echo $paginator->links(5);
	 * </code>
	 *
	 * @param	int		$adjacent
	 * @return	string
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function links($adjacent = 3)
	{
		if ($this->last <= 1) return '';

		// The hard-coded seven is to account for all of the constant elements in a
		// sliding range, such as the current page, the two ellipses, and the two
		// beginning and ending pages.
		//
		// If there are not enough pages to make the creation of a slider possible
		// based on the adjacent pages, we will simply display all of the pages.
		// Otherwise, we will create a "truncating" sliding window.
		if ($this->last < 7 + ($adjacent * 2))
		{
			$links = $this->range(1, $this->last);
		}
		else
		{
			$links = $this->slider($adjacent);
		}

		$content = $this->previous().' '.$links.' '.$this->next();

		return <<<HTML
			<div class="pagination">
				<ul>
					{$content}
				</ul>
			</div>
HTML;
	}

}
