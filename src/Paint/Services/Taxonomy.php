<?php

namespace Paint\Services;

use Paint\Hooks\Hookable;

class Taxonomy extends Hookable
{
    protected $taxonomy;

    protected $objectType;

    public $actions = ['init'];

    public function __construct($taxonomy, $objectType, array $args = [])
    {
        $taxonomy = array_wrap($taxonomy);

        $this->taxonomy = $taxonomy[0];
        $this->objectType = $objectType;

        $taxonomyTitleCase = title_case(str_replace('_', ' ', $this->taxonomy));
        $restBase = $taxonomy[1] ?? str_plural($this->taxonomy);
        $name = $taxonomy[2] ?? str_plural($taxonomyTitleCase);
        $singularName = $taxonomy[3] ?? str_singular($taxonomyTitleCase);
        $nameLowerCase = $taxonomy[4] ?? mb_strtolower($name);
        $singularNameLowerCase = $taxonomy[5] ?? mb_strtolower($singularName);
        $hierarchical = $args['hierarchical'] ?? false;

        $this->args = array_replace_recursive(
            [
                'labels' => [
                    'name' => _x($name, 'taxonomy general name'),
                    'singular_name' => _x($singularName, 'taxonomy singular name' ),
                    'all_items' => __('All '.$name),
                    'edit_item' => __('Edit '.$singularName),
                    'view_item' => __('View '.$singularName),
                    'update_item' => __('Update '.$singularName),
                    'add_new_item' => __('Add New '.$singularName),
                    'new_item_name' => __('New '.$singularName.' Name'),
                    'parent_item' => $hierarchical ? null : __('Parent '.$singularName),
                    'parent_item_color' => $hierarchical ? null : __('Parent '.$singularName.':'),
                    'search_items' => __('Search '.$name),
                    'popular_items' => $hierarchical ? __('Popular '.$name) : null,
                    'separate_items_with_commas' => $hierarchical ? __('Separate '.$nameLowerCase.' with commas' ) : null,
                    'add_or_remove_items' => $hierarchical ? __('Add or remove '.$nameLowerCase) : null,
                    'choose_from_most_used' => $hierarchical ? __('Choose from the most used '.$nameLowerCase) : null,
                    'not_found' => __('No '.$nameLowerCase.' found.'),
                ],
                'show_in_rest' => true,
                'rest_base' => $restBase,
            ],
            $args
        );

        parent::__construct();
    }

    public function initAction()
    {
        register_taxonomy($this->taxonomy, $this->objectType, $this->args);
    }
}
