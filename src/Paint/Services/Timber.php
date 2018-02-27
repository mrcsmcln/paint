<?php

namespace Paint\Services;

use Timber\Image;
use Twig_Environment;
use Timber\Twig_Function;
use Paint\Hooks\Hookable;
use Timber\Timber as UpstatementTimber;

class Timber extends Hookable
{
    public $actions = [
        'after_setup_theme',
    ];

    public $filters = [
        'timber/twig',
        'timber/context',
    ];

    public function afterSetupThemeAction()
    {
        new UpstatementTimber;

        UpstatementTimber::$dirname ='src/twig';
    }

    public function timberContextFilter($context)
    {
        $context['post'] = UpstatementTimber::get_post();
        $context['posts'] = UpstatementTimber::get_posts();

        if (function_exists('get_fields')) {
            $context['options'] = get_fields('options');
        }

        return $context;
    }

    public function timberTwigFilter(Twig_Environment $twig)
    {
        foreach ([
            'svg',
            'image',
        ] as $name) {
            $twig->addFunction($this->getTwigFunction($name));
        }

        return $twig;
    }

    protected function getTwigFunction($name)
    {
        return new Twig_Function($name, [$this, $name.'Callable']);
    }

    public function svgCallable($path, $attributes = [])
    {
        if (!  (is_array($attributes) || is_string($attributes))) {
            throw new TypeError;
        }



        // Get rid of stylesheet directory URI in path
        $path = ltrim(
            str_replace(get_stylesheet_directory_uri(), '', $path),
            '/'
        );

        $uri = get_stylesheet_directory_uri().'/'.$path;
        $path = get_stylesheet_directory().'/'.$path;

        preg_match('/viewBox="(.*?)"/', file_get_contents($path), $matches);

        $svg = '<svg';

        if (is_string($attributes)) {
            $attributes = [ 'class' => $attributes ];
        }

        $attributes = array_merge($attributes, [
            'viewBox' => $matches[1] ?? '',
            // 'xmlns:xlink' => 'http://www.w3.org/1999/xlink',
        ]);

        foreach ($attributes as $name => $value) {
            $svg .= ' '.$name.($value === null ? '' : '="'.$value.'"');
        }

        $svg .= '><use xlink:href="'.$uri.'#svg"></use></svg>';

        return $svg;
    }

    public function imageCallable($image, $attributes = [])
    {
        if (!  (is_array($attributes) || is_string($attributes))) {
            throw new TypeError;
        }

        $image = new Image($image);

        $image->srcset = implode(', ', array_map(function ($size) use ($image) {
            $file = explode('/', $image->file);

            return str_replace($file[count($file) - 1], $size['file'], $image->src.' '.$size['width'].'w');
        }, $image->sizes));

        if (is_string($attributes)) {
            $attributes = [ 'class' => $attributes ];
        }

        $attributes = array_merge($attributes, [
            'src' => $image->src,
            'alt' => $image->alt,
        ]);

        if ($image->srcset) {
            $attributes['srcset'] = $image->srcset;
        }

        // $data = [
        //     'attributes' => $attributes,
        // ];

        $html = '<img';

        foreach ($attributes as $name => $value) {
            $html .= ' '.$name.($value === null ? '' : '="'.$value.'"');
        }

        $html .= '>';

        return $html;
    }
}
