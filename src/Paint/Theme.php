<?php

namespace Paint;

use Stringy\Stringy;
use Paint\Hooks\Hookable;
use Paint\Concerns\DoesSsr;
use Paint\Concerns\AddsFeatures;
use Paint\Concerns\AddsImageSizes;
use Paint\Concerns\EnqueuesStyles;
use Paint\Concerns\EnqueuesScripts;
use Paint\Concerns\RegistersNavMenus;
use Paint\Concerns\RegistersPostTypes;
use Paint\Concerns\RegistersTaxonomies;
use Paint\Concerns\Acf\AddsOptionsPages;

abstract class Theme extends Hookable {
    use DoesSsr;
    use AddsFeatures;
    use AddsImageSizes;
    use EnqueuesStyles;
    use EnqueuesScripts;
    use AddsOptionsPages;
    use RegistersNavMenus;
    use RegistersPostTypes;
    use RegistersTaxonomies;

    protected $version;
    protected $stylesheetDirectory;
    protected $stylesheetDirectoryUri;
    protected $distDirectory;
    protected $distDirectoryUri;
    protected $cssDirectory;
    protected $cssDirectoryUri;
    protected $jsDirectory;
    protected $jsDirectoryUri;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->version = wp_get_theme()->get('Version');
        $this->stylesheetDirectory = get_stylesheet_directory();
        $this->stylesheetDirectoryUri = get_stylesheet_directory_uri();
        $this->distDirectory = $this->stylesheetDirectory.'/dist';
        $this->distDirectoryUri = $this->stylesheetDirectoryUri.'/dist';
        $this->cssDirectory = $this->distDirectory.'/css';
        $this->cssDirectoryUri = $this->distDirectoryUri.'/css';
        $this->jsDirectory = $this->distDirectory.'/js';
        $this->jsDirectoryUri = WP_ENV === 'development'
            ? Stringy::create($this->distDirectoryUri.'/js')
                ->replace(site_url(), 'http://localhost:8080')
            : $this->distDirectoryUri.'/js'
        ;

        $this->gatherStyles();
        $this->gatherScripts();

        $this->addFeatures();
        $this->addImageSizes();
        $this->enqueueStyles();
        $this->enqueueScripts();
        $this->registerNavMenus();
        $this->registerPostTypes();
        $this->registerTaxonomies();
        $this->doSsr($this->jsDirectory.'/server-bundle.js');
    }

    protected function gatherStyles(): void
    {
        if (WP_ENV === 'production') {
            return;
        }

        foreach (array_filter(
            scandir($this->cssDirectory),
            [$this, 'filterCss']
        ) as $filename) {
            $this->styles[] = [
                Stringy::create($filename)->removeRight('.css'),
                $this->cssDirectoryUri.'/'.$filename,
                [],
                $this->version
            ];
        }
    }

    protected function gatherScripts(): void
    {
        foreach (array_filter(
            scandir($this->jsDirectory),
            [$this, 'filterJs']
        ) as $filename) {
            $this->scripts[] = [
                Stringy::create($filename)->removeRight('.js'),
                $this->jsDirectoryUri.'/'.$filename,
                [],
                $this->version,
                true,
            ];
        }
    }

    protected function filterCss($filename): bool
    {
        return is_file($this->cssDirectory.'/'.$filename)
            && Stringy::create($filename)->endsWith('.css')
        ;
    }

    protected function filterJs($filename): bool
    {
        return is_file($this->jsDirectory.'/'.$filename)
            && Stringy::create($filename)->endsWith('.js')
            && $filename !== 'server-bundle.js'
        ;
    }

    // public function uploadMimes(array $t)
    // {
    //     $t['svg'] = 'image/svg+xml';

    //     return $t;
    // }

    // public function acfSettingsShowAdmin(bool $show)
    // {
    //     return WP_ENV === 'prod' ? false : $show;
    // }

    // public function intermediateImageSizesAdvanced(array $sizes)
    // {
    //     unset($sizes['thumbnail']);
    //     unset($sizes['medium_large']);

    //     return $sizes;
    // }
}