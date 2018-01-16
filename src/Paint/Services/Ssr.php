<?php

namespace Paint\Services;

use V8Js;
use GuzzleHttp\Client;
use Paint\Hooks\Hookable;

class Ssr extends Hookable
{
    public $actions = ['ssr'];

    protected $entry;

    protected $vars;

    public function __construct($entry, $vars)
    {
        $this->entry = $entry;

        $this->vars = $vars;

        parent::__construct();
    }

    public function ssrAction()
    {
        switch (WP_ENV) {
            case 'production':
                $v8js = new V8Js();

                $v8js->{'$_SERVER'} = $_SERVER;

                foreach ($this->vars as $key => $value) {
                    $v8js->$key = $value;
                }

                $v8js->client = new Client(['base_uri' => get_site_url()]);
                $v8js->executeString(file_get_contents($this->entry));

                break;
            case 'development':
                echo '<div id="app"></div>';

                break;
        }
    }
}
