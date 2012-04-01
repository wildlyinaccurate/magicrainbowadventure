<?php

/**
 * Page Controller
 *
 * Delivers the more plain, 'static' pages
 */
class Page extends Front_controller {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * About Magic Rainbow Adventure
     */
    public function about()
    {
        $this->template->title('About Magic Rainbow Adventure')
            ->build('page/about');
    }

    /**
     * Privacy Statement
     */
    public function privacy()
    {
        $this->template->title('Privacy Statement')
            ->build('page/privacy');
    }

}

/* End of file rate.php */
/* Location: ./application/controllers/rate.php */