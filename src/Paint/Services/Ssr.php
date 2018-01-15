<?php

namespace Paint\Services;

use V8Js;
use GuzzleHttp\Client;
use Paint\Hooks\Hookable;

class Ssr extends Hookable
{
    public $actions = ['ssr'];

    protected $entry;

    public function __construct(string $entry)
    {
        $this->entry = $entry;

        parent::__construct();
    }

    public function ssrAction()
    {
        switch (WP_ENV) {
            case 'production':
                $v8js = new V8Js();

                $v8js->{'$_SERVER'} = $_SERVER;
                // var_dump($_SERVER);
                // die();
                $v8js->client = new Client(['base_uri' => get_site_url()]);

                // $v8js->setTimeLimit(3000);
                // $v8js->executeString('const $_SERVER = '.json_encode($_SERVER).';');
                $v8js->executeString(file_get_contents($this->entry));

                break;
            case 'development':
                echo '<div id="app"></div>';

                break;
        }
    }
}
