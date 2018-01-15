<?php

namespace Paint\Services;

use Stringy\Stringy;
use Paint\Hooks\Hookable;
use Doctrine\Common\Inflector\Inflector;

class PostType extends Hookable
{
    public $actions = [
        'init',
    ];

    public $filters = [
        'post_updated_messages',
        ['bulk_post_updated_messages', 10, 2],
    ];

    protected $postType;

    protected $args;

    protected $name;

    protected $singularName;

    protected $nameLowerCase;

    protected $singularNameLowerCase;

    public function __construct($postType, ...$args)
    {
        if (! is_array($postType)) {
            $postType = [$postType];
        }

        $this->postType = $postType[0];
        $restBase = $postType[1] ?? str_replace('_' , '-', str_plural($this->postType));
        $this->name = $postType[2] ?? Inflector::pluralize((string) Stringy::create($this->postType)->delimit(' ')->toTitleCase());
        $this->singularName = $postType[3] ?? Inflector::singularize($this->name);
        $this->nameLowerCase = $postType[4] ?? (string) Stringy::create($this->name)->toLowerCase();
        $this->singularNameLowerCase = $postType[5] ?? (string) Stringy::create($this->singularName)->toLowerCase();

        $menuIcon = is_string($args[0] ?? null) ? array_shift($args) : null;

        $this->args = array_replace_recursive(
            [
                'labels' => [
                    'name' => $this->name,
                    'singular_name' => $this->singularName,
                    'add_new_item' => 'Add New '.$this->singularName,
                    'edit_item' => 'Edit '.$this->singularName,
                    'new_item' => 'New '.$this->singularName,
                    'view_item' => 'View '.$this->singularName,
                    'view_items' => 'View '.$this->name,
                    'search_items' => 'Search '.$this->name,
                    'not_found' => 'No '.$this->nameLowerCase.' found.',
                    'not_found_in_trash' => 'No '.$this->nameLowerCase.' found in Trash.',
                    'parent_item_colon' => 'Parent '.$this->singularName,
                    'all_items' => 'All '.$this->name,
                    'archives' => $this->singularName.' Archives',
                    'attributes' => $this->singularName.' Attributes',
                    'insert_into_item' => 'Insert into '.$this->singularNameLowerCase,
                    'uploaded_to_this_item' => 'Uploaded to this '.$this->singularNameLowerCase,
                ],
                'public' => true,
                'show_in_rest' => true,
                'menu_icon' => $menuIcon,
                'rest_base' => $restBase,
                'rewrite' => [
                    'slug' => $restBase,
                ],
            ],
            $args[0] ?? []
        );

        parent::__construct();
    }

    protected function generateNames($names)
    {
        if (! is_array($names)) {
            $names = [$names];
        }

        $this->postType = $names[0];
        $this->name = $names[1] ?? Inflector::pluralize((string) Stringy::create($this->postType)->delimit(' ')->toTitleCase());
        $this->singularName = $names[2] ?? Inflector::singularize($this->name);
        $this->nameLowerCase = $names[3] ?? (string) Stringy::create($this->name)->toLowerCase();
        $this->singularNameLowerCase = $names[4] ?? (string) Stringy::create($this->singularName)->toLowerCase();
    }

    public function initAction()
    {
        register_post_type($this->postType, $this->args);
    }

    public function postUpdatedMessagesFilter(?array $messages)
    {
        global $post_type_object, $post;

        $postId = isset($post_ID) ? (int) $post_ID : 0;
        $permalink = get_permalink($postId);

        if (! $permalink) {
            $permalink = '';
        }

        $previewPostLinkHtml = $scheduledPostLinkHtml = $viewPostLinkHtml = '';
        $previewUrl = get_preview_post_link($post);
        $viewable = is_post_type_viewable($post_type_object);

        if ($viewable) {
            $previewPostLinkHtml = sprintf(
                ' <a target="_blank" href="%1$s">%2$s</a>',
                esc_url($previewUrl),
                __('Preview '.$this->singularNameLowerCase)
            );
            $scheduledPostLinkHtml = sprintf(
                ' <a target="_blank" href="%1$s">%2$s</a>',
                esc_url($permalink),
                __('Preview '.$this->singularNameLowerCase)
            );
            $viewPostLinkHtml = sprintf(
                ' <a href="%1$s">%2$s</a>',
                esc_url($permalink),
                __('View '.$this->singularNameLowerCase)
            );
        }

        $scheduledDate = date_i18n(
            __('M j, Y @ H:i'),
            strtotime($post->post_date)
        );

        $messages[$this->postType] = [
            0 => '',
            1 => __($this->singularName.' updated.').$viewPostLinkHtml,
            2 => __('Custom field updated.'),
            3 => __('Custom field deleted.'),
            4 => __($this->singularName.' updated.'),
            5 => isset($_GET['revision']) ? sprintf(
                    __($this->singularName.' restored to revision from %s.'),
                    wp_post_revision_title((int) $_GET['revision'], false)
                ) : false
            ,
            6 => __($this->singularName.' published.').$viewPostLinkHtml,
            7 => __($this->singularName.' saved.'),
            8 => __($this->singularName.' submitted.').$previewPostLinkHtml,
            9 => sprintf(
                __($this->singularName.' scheduled for: %s.'),
                '<strong>'.$scheduledDate.'</strong>'
            ).$scheduledPostLinkHtml,
            10 => __($this->singularName.' draft updated.').$previewPostLinkHtml,
        ];

        return $messages;
    }

    public function bulkPostUpdatedMessagesFilter(
        array $bulkMessages,
        array $bulkCounts
    ) {
        $bulkMessages[$this->postType] = [
            'updated' => _n(
                '%s '.$this->singularNameLowerCase.' updated.',
                '%s '.$this->nameLowerCase.' updated.',
                $bulkCounts['updated']
            ),
            'locked' => (1 === $bulkCounts['locked']) ? __(
                '1 '.$this->singularNameLowerCase.' not updated, somebody is editing it.'
            ) : _n(
                '%s '.$this->singularNameLowerCase.' not updated, somebody is editing it.',
                '%s '.$this->nameLowerCase.' not updated, somebody is editing them.',
                $bulkCounts['locked']
            ),
            'deleted' => _n(
                '%s '.$this->singularNameLowerCase.' permanently deleted.',
                '%s '.$this->nameLowerCase.' permanently deleted.',
                $bulkCounts['deleted']
            ),
            'trashed' => _n(
                '%s '.$this->singularNameLowerCase.' moved to the Trash.',
                '%s '.$this->nameLowerCase.' moved to the Trash.',
                $bulkCounts['trashed']
            ),
            'untrashed' => _n(
                '%s '.$this->singularNameLowerCase.' restored from the Trash.',
                '%s '.$this->nameLowerCase.' restored from the Trash.',
                $bulkCounts['untrashed']
            ),
        ];

        return $bulkMessages;
    }

    protected function cleanNames($data)
    {
        if (! is_array($data)) {
            $data = [$data];
        }

        return [
            $data[0],
            $data[1] ?? Inflector::pluralize((string) Stringy::create($taxonomy)->delimit(' ')->toTitleCase()),
            $data[2] ?? Inflector::singularize($name),
            $data[3] ?? (string) Stringy::create($name)->toLowerCase(),
            $data[4] ?? (string) Stringy::create($singularName)->toLowerCase(),
        ];
    }
}
