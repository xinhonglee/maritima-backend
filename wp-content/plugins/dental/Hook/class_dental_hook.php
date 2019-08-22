<?php

if (!class_exists('Dental_Hook')) :

    class Dental_Hook
    {
        private $hook_base;

        public function __construct()
        {
            $this->hook_base = DENTAL_PLUGIN_NAME;

            add_filter($this->hook_base . '_get_treatments', array($this, 'getTreatments'), 10, 3);
            add_filter($this->hook_base . '_get_treatment', array($this, 'getTreatment'), 10, 1);
            add_filter($this->hook_base . '_get_treatment_categories', array($this, 'getTreatmentCategories'), 10, 2);
            add_filter($this->hook_base . '_get_products', array($this, 'getProducts'), 10, 2);
            add_filter($this->hook_base . '_get_news_list', array($this, 'getNewsList'), 10, 2);
            add_filter($this->hook_base . '_get_news', array($this, 'getNews'), 10, 1);
            add_filter($this->hook_base . '_get_home', array($this, 'getHomeData'), 10);

        }

        public function getTreatments($page = 1, $length = -1, $terms_slug = '')
        {
            try {
                $treatments = [];

                $_posts = new WP_Query(array(
                    'post_type' => 'treatment',
                    'posts_per_page' => $length,
                    'paged' => $page,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'treatment_category',
                            'field' => 'slug',
                            'terms' => $terms_slug,
                        )
                    ),
                ));

                if ($_posts->have_posts()):
                    while ($_posts->have_posts()) : $_posts->the_post();
                        $treatment = [];

                        $treatment['id'] = get_the_ID();
                        $treatment['category'] = $terms_slug;
                        $ml_contents = get_field('ml_contents');

                        $contents = [];
                        foreach ($ml_contents as $content) {
                            $contents[$content['language_code']] = array(
                                'title' => $content['title'],
                                'sub_title' => $content['sub_title'],
                                'short_description' => $content['short_description']
                            );
                        }
                        $treatment['contents'] = $contents;

                        array_push($treatments, $treatment);
                    endwhile;
                endif;
                wp_reset_postdata();

                return $treatments;

            } catch (\Exception $exception) {
                return false;
            }
        }

        public function getTreatment($id)
        {
            try {
                $treatment['id'] = $id;
                $terms = get_the_terms($id, 'treatment_category');
                $treatment['category'] = $terms[0]->slug;
                $treatment['related'] = [];

                $ml_contents = get_field('ml_contents', $id);
                $contents = [];
                foreach ($ml_contents as $content) {
                    $contents[$content['language_code']] = array(
                        'title' => $content['title'],
                        'sub_title' => $content['sub_title'],
                        'short_description' => $content['short_description'],
                        'long_description' => $content['long_description'],
                        'specs' => []
                    );

                    foreach ($content['specifics'] as $spec) {
                        array_push($contents[$content['language_code']]['specs'], $spec['item']);
                    }

                }
                $treatment['contents'] = $contents;

                $_posts = new WP_Query(array(
                    'post_type' => 'treatment',
                    'posts_per_page' => 4,
                    'paged' => 1,
                    'post__not_in' => array($treatment['id']),
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'treatment_category',
                            'field' => 'slug',
                            'terms' => $treatment['category'],
                        )
                    ),
                ));

                if ($_posts->have_posts()):
                    while ($_posts->have_posts()) : $_posts->the_post();
                        $related = [];
                        $related['id'] = get_the_ID();

                        $ml_contents = get_field('ml_contents');
                        $contents = [];
                        foreach ($ml_contents as $content) {
                            $contents[$content['language_code']] = array(
                                'title' => $content['title'],
                                'sub_title' => $content['sub_title'],
                                'short_description' => $content['short_description'],
                                'long_description' => $content['long_description'],
                                'specs' => []
                            );

                            foreach ($content['specifics'] as $spec) {
                                array_push($contents[$content['language_code']]['specs'], $spec['item']);
                            }

                        }
                        $related['contents'] = $contents;

                        array_push($treatment['related'], $related);
                    endwhile;
                endif;
                wp_reset_postdata();

                return $treatment;
            } catch (\Exception $exception) {
                return false;
            }
        }

        public function getTreatmentCategories($page = 1, $length = -1)
        {
            try {
                $custom_terms = get_terms('treatment_category');

                return $custom_terms;
            } catch (\Exception $exception) {
                return false;
            }
        }

        public function getProducts($page = 1, $length = -1)
        {
            try {
                $products = [];

                $_posts = new WP_Query(array(
                    'post_type' => 'product',
                    'posts_per_page' => $length,
                    'paged' => $page
                ));

                if ($_posts->have_posts()):
                    while ($_posts->have_posts()) : $_posts->the_post();
                        $product = [];

                        $product['id'] = get_the_ID();
                        $product['image'] = get_field('image');
                        $product['purchase_link'] = get_field('purchase_link');
                        $product['price'] = get_field('price');
                        $product['discount_price'] = get_field('discount_price');

                        $ml_contents = get_field('ml_contents');
                        $contents = [];
                        foreach ($ml_contents as $content) {
                            $contents[$content['language_code']] = array(
                                'title' => $content['title'],
                                'description' => $content['description']
                            );
                        }
                        $product['contents'] = $contents;

                        array_push($products, $product);
                    endwhile;
                endif;
                wp_reset_postdata();

                return $products;
            } catch (\Exception $exception) {
                return false;
            }
        }

        public function getNewsList($page, $length)
        {
            try {
                $news_list = [];

                $_posts = new WP_Query(array(
                    'post_type' => 'news',
                    'posts_per_page' => $length,
                    'paged' => $page
                ));

                if ($_posts->have_posts()):
                    while ($_posts->have_posts()) : $_posts->the_post();
                        $news = [];

                        $news['id'] = get_the_ID();
                        $news['image'] = get_field('image');
                        $news['published_at'] = get_field('published_at');
                        $news['author'] = get_field('author');

                        $ml_contents = get_field('ml_contents');
                        $contents = [];
                        foreach ($ml_contents as $content) {
                            $contents[$content['language_code']] = array(
                                'title' => $content['title'],
                                'short_description' => $content['short_description'],
                                'long_description' => $content['long_description']
                            );
                        }
                        $news['contents'] = $contents;

                        array_push($news_list, $news);
                    endwhile;
                endif;
                wp_reset_postdata();

                return $news_list;
            } catch (\Exception $exception) {
                return false;
            }
        }

        public function getNews($id)
        {
            try {

                $news['id'] = $id;
                $news['image'] = get_field('image', $id);
                $news['published_at'] = get_field('published_at', $id);
                $news['author'] = get_field('author', $id);
                $news['related'] = [];

                $ml_contents = get_field('ml_contents', $id);
                $contents = [];
                foreach ($ml_contents as $content) {
                    $contents[$content['language_code']] = array(
                        'title' => $content['title'],
                        'short_description' => $content['short_description'],
                        'long_description' => $content['long_description']
                    );
                }
                $news['contents'] = $contents;

                $_posts = new WP_Query(array(
                    'post_type' => 'news',
                    'posts_per_page' => 3,
                    'paged' => 1,
                    'post__not_in' => array($news['id'])
                ));

                if ($_posts->have_posts()):
                    while ($_posts->have_posts()) : $_posts->the_post();
                        $related = [];

                        $related['id'] = get_the_ID();
                        $related['image'] = get_field('image');
                        $related['published_at'] = get_field('published_at');
                        $related['author'] = get_field('author');

                        $ml_contents = get_field('ml_contents');
                        $contents = [];
                        foreach ($ml_contents as $content) {
                            $contents[$content['language_code']] = array(
                                'title' => $content['title'],
                                'short_description' => $content['short_description'],
                                'long_description' => $content['long_description']
                            );
                        }
                        $related['contents'] = $contents;

                        array_push($news['related'], $related);
                    endwhile;
                endif;
                wp_reset_postdata();

                return $news;
            } catch (\Exception $exception) {
                return false;
            }
        }

        public function getHomeData($msg)
        {
            try {
                $data = [];

                $data['categories'] = $this->getTreatmentCategories();
                $data['products'] = $this->getProducts(1, 4);
                $data['news'] = $this->getNewsList(1, 3);

                return $data;
            } catch (\Exception $exception) {
                return false;
            }
        }
    }

endif; // End if class_exists check