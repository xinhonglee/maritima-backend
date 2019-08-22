<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Dental_RESTApi')) :

    class Dental_RESTApi
    {

        private $rest_base;

        public function __construct()
        {
            $this->rest_base = DENTAL_PLUGIN_NAME;
            add_action('rest_api_init', array($this, 'registerRoutes'));
        }

        public function registerRoutes()
        {
            register_rest_route(
                $this->rest_base,
                '/news',
                array(
                    'methods' => 'GET',
                    'callback' => array($this, 'getNews'),
                )
            );
            register_rest_route(
                $this->rest_base,
                '/products',
                array(
                    'methods' => 'GET',
                    'callback' => array($this, 'getProducts'),
                )
            );
            register_rest_route(
                $this->rest_base,
                '/treatments',
                array(
                    'methods' => 'GET',
                    'callback' => array($this, 'getTreatments'),
                )
            );
            register_rest_route(
                $this->rest_base,
                '/treatment/categories',
                array(
                    'methods' => 'GET',
                    'callback' => array($this, 'getTreatmentCategories'),
                )
            );
            register_rest_route(
                $this->rest_base,
                '/home',
                array(
                    'methods' => 'GET',
                    'callback' => array($this, 'getHomeData'),
                )
            );
            register_rest_route(
                $this->rest_base,
                '/contact',
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'sendContact'),
                )
            );
        }

        public function getNews(WP_REST_Request $request)
        {

            try {
                $post_id = $request->get_param('id');
                if (!empty($post_id)) {
                    $news = apply_filters(DENTAL_PLUGIN_NAME . '_get_news', $post_id);
                    wp_send_json_success($news);
                } else {
                    $page = $request->get_param('page');
                    $size = $request->get_param('size');
                    $news = apply_filters(DENTAL_PLUGIN_NAME . '_get_news_list', $page, $size);
                    wp_send_json_success($news);
                }

                wp_die();
            } catch (\Exception $exception) {
                wp_send_json_error(array(
                    'message' => $exception->getMessage()
                ));
                wp_die();
            }
        }

        public function getTreatments(WP_REST_Request $request)
        {

            try {
                $post_id = $request->get_param('id');
                if (!empty($post_id)) {
                    $treatment = apply_filters(DENTAL_PLUGIN_NAME . '_get_treatment', $post_id);
                    wp_send_json_success($treatment);
                } else {
                    $category = $request->get_param('category');
                    $page = $request->get_param('page');
                    $size = $request->get_param('size');
                    $treatments = apply_filters(DENTAL_PLUGIN_NAME . '_get_treatments', $page, $size, $category);
                    wp_send_json_success($treatments);
                }
                wp_die();
            } catch (\Exception $exception) {
                wp_send_json_error(array(
                    'message' => $exception->getMessage()
                ));
                wp_die();
            }
        }

        public function getTreatmentCategories(WP_REST_Request $request)
        {

            try {
                $categories = apply_filters(DENTAL_PLUGIN_NAME . '_get_treatment_categories', 1, -1);
                wp_send_json_success($categories);
                wp_die();
            } catch (\Exception $exception) {
                wp_send_json_error(array(
                    'message' => $exception->getMessage()
                ));
                wp_die();
            }
        }

        public function getProducts(WP_REST_Request $request)
        {

            try {
                $page = $request->get_param('page');
                $size = $request->get_param('size');
                $products = apply_filters(DENTAL_PLUGIN_NAME . '_get_products', $page, $size);
                wp_send_json_success($products);
                wp_die();
            } catch (\Exception $exception) {
                wp_send_json_error(array(
                    'message' => $exception->getMessage()
                ));
                wp_die();
            }
        }

        public function getHomeData(WP_REST_Request $request)
        {

            try {
                $data = apply_filters(DENTAL_PLUGIN_NAME . '_get_home', '');
                wp_send_json_success($data);
                wp_die();
            } catch (\Exception $exception) {
                wp_send_json_error(array(
                    'message' => $exception->getMessage()
                ));
                wp_die();
            }
        }

        public function sendContact(WP_REST_Request $request)
        {
            try {
                $first_name = $request->get_param('firstName');
                $last_name = $request->get_param('lastName');
                $user_email = $request->get_param('email');
                $phone_number = $request->get_param('phone');
                $message = $request->get_param('message');
                $status = $request->get_param('status');

                if($status !== 'accepted' ) {
                    wp_send_json_error(array(
                        'message' => 'Not accepted'
                    ));
                    wp_die();
                }

                $content = '<h3>Dental Contact</h3>
                            <hr>
                            <div>
                              <p>User Name: ' . $first_name . ' ' . $last_name . '</p>
                              <p>User Email: ' . $user_email . '</p>
                              <p>Phone Number: ' . $phone_number . '</p>
                              <p>Message:</p>
                              <p>' . $message . '</p>
                            </div>
                            <br>
                            <p>Thank you for your business!</p>';


                $subject = 'Dental Contact Information';
                $to = 'simon@hiway.io';

                $headers = array('Content-Type: text/html; charset=UTF-8');
                $result = wp_mail($to, $subject, $content, $headers);

                wp_send_json_success($result);
                wp_die();
            } catch (\Exception $exception) {
                wp_send_json_error(array(
                    'message' => $exception->getMessage()
                ));
                wp_die();
            }
        }
    }

endif; // End if class_exists check