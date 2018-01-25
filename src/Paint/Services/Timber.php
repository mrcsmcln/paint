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
        $twig->addFunction(new Twig_Function('image', function ($image, $attributes) {
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

            $data = [
                'attributes' => $attributes,
            ];

            $html = '<img';

            foreach ($attributes as $name => $value) {
                $html .= $value === null
                    ? ' '.$name
                    : ' '.$name.'="'.$value.'"'
                ;
            }

            $html .= '>';

            return $html;
        }));

        return $twig;
    }
}
