<?php
namespace Carawebs\Loop;

/**
*
*/
abstract class Base
{
    /**
    * Return pagination arguments.
    *
    * @param  string|int $pages Maximum number of pages (`$custom_query->max_num_pages`)
    * @return array Arguments for the `paginate_links()` function
    */
    protected function pagination_args( $pages ) {
        $big = 999999999; // need an unfeasibly high integer
        return [
            'base'        => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'      => '?paged=%#%',
            'current'     => max( 1, get_query_var('paged') ),
            'type'        => 'list',
            'total'       => $pages
        ];
    }
}
