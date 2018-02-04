<?php
namespace Carawebs\Loop;

/**
*
*/
class Query extends Base
{
    /**
    * Arguments to be passed to WP_Query()
    * @since    1.0.0
    * @var array
    */
    private $args;

    /**
    * Set up the default arguments for WP_Query.
    *
    * Pass in an array to override or extend the default arguments.
    *
    * @since    1.0.0
    * @param array $override Array of WP_Query arguments
    */
    public function __construct($override = [])
    {
        $this->setQueryArgs($override);
    }

    /**
    * Set up query arguments.
    *
    * Controller code:
    * $loopObject = new Carawebs\Loop\Query;
    * $postsData = $loopObject->post_objects($this->loop_arguments());
    *
    * @param array $override Arguments to override the defaults
    */
    public function setQueryArgs(array $override = NULL)
    {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $this->args = array_merge([
            'post_type' => ['post'],
            'post_status' => ['publish'],
            'posts_per_page' => '-1',
            'order' => 'DESC',
            'orderby' => 'date',
            'paged' => $paged,
            'pagination' => false,
        ], $override);
        return $this;
    }

    /**
    * Build a custom loop that returns an array of post objects
    *
    * @since   1.0.0
    * @uses    WP_Query()
    * @return  array
    */
    public function post_objects( $overrides = [] )
    {
        // Allow arguments to be added to this method directly. If none passed, use defaults
        $args = empty ($overrides) ? $this->args : array_merge( $this->args, $overrides );
        $object_array = [];
        $custom_query = new \WP_Query($args);

        if ($custom_query->have_posts()) {
            $posts = $custom_query->posts;
            foreach( $posts as $post ) {
                $object_array[] = $post;
            }
        }

        if ($custom_query->max_num_pages > 1 AND true === $this->args['pagination']) {
            ob_start();
            echo '<nav class="page">' . paginate_links($this->pagination_args($custom_query->max_num_pages)) . '</nav>';
            $this->pagination_links = ob_get_clean();
        }

        wp_reset_postdata();
        return $object_array;
    }
}
