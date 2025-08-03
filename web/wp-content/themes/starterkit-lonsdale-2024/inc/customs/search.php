<?php

class getCpts
{
    public $items = [];
    public $totalPosts = 0;
    public $itemPerPage = 8;
    public $paged = 1;
    public $tax_query = "";
    public $query = "";
    public $post_type;
    public $pager;
    public $args;

    public function items()
    {
        $this->args = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'posts_per_page' => $this->itemPerPage,
            'paged' => $this->paged,
            'orderby' => 'date',
            'order' => 'DESC',
            's' => $this->query,
            'tax_query' => array($this->tax_query),
            // 'meta_key' => 'note', // C'est ici qu'on indique quel est ce champ
        );
        $queryArticles = new WP_Query($this->args);

        if ($queryArticles->have_posts()) {
            while ($queryArticles->have_posts()) {
                $queryArticles->the_post();
                $this->items[] = get_the_ID();
            }
            wp_reset_postdata();
        }

        return  $this->items;
    }

    public function pager()
    {
        $this->args['posts_per_page'] = -1;
        $queryArticleCount = new WP_Query($this->args);
        $totalPages = ceil($queryArticleCount->post_count / $this->itemPerPage);
        $totalPosts = $queryArticleCount->post_count;

        $this->pager = [
            'current_page' => $this->paged,
            'total_pages' => $totalPages,
            'total_posts' => $totalPosts
        ];

        return $this->pager;
    }

    public function __construct($post_type = "news", $paged, $query)
    {
        $this->query = !empty(get_query_var('s')) ? htmlspecialchars(get_query_var('s')) : $query;
        $this->paged =  get_query_var('paged') ? get_query_var('paged') : $paged;
        $this->itemPerPage =get_option('posts_per_page');
        $this->post_type = $post_type;
        $this->items($this);
        $this->pager();
    }
}
