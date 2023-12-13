<?php

/**
 * Start session
 *
 * @return void
 */
function session_register() {
  if( !session_id() ) {
    session_start();
  }
}
add_action('init', 'session_register');

/**
 * Disable the emoji's, guttenberg, etc
 * just boring 
 * 
 * @return void
 */
function disables(): void {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
    
    remove_action( 'wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles' );
    remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
    
    remove_action( 'wp_head', 'feed_links_extra', 3 ); 
    remove_action( 'wp_head', 'feed_links', 2 ); 
    remove_action( 'wp_head', 'wp_generator' );
    remove_action(' wp_head', 'rel_canonical');
    remove_action(' wp_head', 'alternate'); 

    remove_action( 'wp_footer', 'the_block_template_skip_link' );
}
add_action( 'init', 'disables' ); 


/**
 * Classes flter
 *
 * @param  mixed $wp_classes
 * @param  mixed $extra_classes
 * @return array
 */
function body_classes( $wp_classes, $extra_classes ): array {

    // List of the only WP generated classes allowed
    $whitelist = array('home');

    // Filter the body classes
    $wp_classes = array_intersect( $wp_classes, $whitelist );

    // Add the extra classes back untouched
    return array_merge( $wp_classes, (array) $extra_classes );
}
add_filter( 'body_class', 'body_classes', 10, 2 );


/**
 * Enqueue Jquery, Bootstrap, AJAX
 * theme JS and CSS
 *
 * @return void
 */
function enqueue_bootstrap(): void {

    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), null, true);
    wp_enqueue_script('shop-script', get_template_directory_uri() . '/assets/js/script.js', array('jquery'), null, true);

    wp_localize_script('shop-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

    wp_enqueue_style('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_style('fonts', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,800;1,300&display=swap');
    wp_enqueue_style('shop-style', get_template_directory_uri() . '/assets/css/style.css');

}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap');


/**
 * Handle 404 pages
 *
 * @return void
 */
function custom_handle_404(): void {

    global $wp, $wp_query;
    
    if ($wp->request == 'cart') {
        
        // Set a 200 OK status header
        status_header(200); 

        // Load the cart.php template
        include get_template_directory() . '/cart.php';
        exit();         

    }

    if ($wp_query->is_404) {
        
        // Set a 200 OK status header
        status_header(200); 

        // Load the index.php template
        include get_template_directory() . '/index.php';

        exit(); 
    }
}
add_action('template_redirect', 'custom_handle_404');

 
/**
 * Set a default custom title for all pages
 *
 * @return void
 */
function custom_page_title(): void {
    global $custom_page;

    $title = $custom_page['title'] ?: get_bloginfo(); 
    echo '<title>' . $title . '</title>' .PHP_EOL;
}
add_action('wp_head', 'custom_page_title', 0, 1);


/**
 * Set a default custom description for all pages
 *
 * @return void
 */
function custom_page_description(): void {

    $custom_description = 'This is a custom page description.'; 
    echo '<meta name="description" content="' . esc_attr($custom_description) . '">' .PHP_EOL;
}
add_action('wp_head', 'custom_page_description', 0, 1);


/**
 * Fetch the categories of products 
 * from a JSON endpoint
 *
 * @return array
 */
function get_cats(): array {
    
    $endpoint = get_option('shop_products_endpoint');

    // Fetch and parse JSON data
    $json_url = $endpoint . 'categories'; 
    $response = wp_remote_get($json_url);
    
    if (is_array($response) && !is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $categories = json_decode($body, true);
    } else {
        $categories = array();
    }

    return $categories;
}


/**
 * Generate menu HTML
 *
 * @return string
 */
function generate_menu(): string {
    
    $url_parts = parse_url_parts();

    $categories = get_cats();
    
    $menu_html = '<ul class="nav flex-column">' .PHP_EOL;
    if (count($categories)) {
        foreach ($categories as $category) {
            $href = esc_html($category);
            $text = ucfirst(str_replace('-', ' ', $href));
            $menu_html .=   '<li class="nav-item ' . (($href == esc_html($url_parts[0])) ? 'active' : false) . '">' 
                            . '<a class="nav-link" href="/' . $href .'">' . $text . '</a>'
                            .'</li>' .PHP_EOL;
        }
    } 
    $menu_html .= '</ul>' .PHP_EOL; 
    
    return $menu_html;        
}


/**
 * Parses the current URL and returns an array of its parts.
 *
 * @return array 
 */
function parse_url_parts(): array {
    global $wp;
    
    // Get the current URL with all query arguments
    $current_url = add_query_arg(array(), $wp->request);
    
    $url_parts = explode('/', $current_url);
    
    return $url_parts;
}


/**
 * Processes the different parts of a URL 
 * and generates a title and body for each page
 *
 * @return array
 */
function process_url_parts(): array {

    // Main page title, <title>*</title>
    $title = '';
    // Page json
    $body = '';
    // Custom page title
    $page_title = 'All products';
    // Page body class
    $body_class = 'category';

    // Get settings
    $json_url = get_option('shop_products_endpoint');

    // Parse url string
    $url_parts = parse_url_parts();

    // Check the first URL part
    if (!empty($url_parts[0])) {

            // Process category URL
            $category_name = sanitize_text_field($url_parts[0]);
            
            // Make subquery to: https://dummyjson.com/products/category/[category name]
            $category_data = wp_remote_get( $json_url . 'category/' . $category_name);
            
            // Process and display category data
            if (is_array($category_data) && !is_wp_error($category_data)) {

                $body = wp_remote_retrieve_body($category_data);

                $category = json_decode($body, true);

                // Empty category or not exist
                if (!count($category['products'])) {
                    
                    // Get all products
                    $category_data = wp_remote_get( $json_url . '?limit=0');
                    $body = wp_remote_retrieve_body($category_data);


                } else {
                    $page_title = generate_custom_title($category_name);
                    $title = generate_title($page_title); 
                }

                // Add product links for SEO and pagination
                $full_category = generate_links($body); 
                $body = json_encode($full_category['products']);              

                if (isset($url_parts[1]) && in_array($url_parts[1], $full_category['links'])) {
                    
                    // Process product ID URL
                    $product_id = array_search($url_parts[1], $full_category['links']); 
                    
                    // Make subquery to: https://dummyjson.com/products/[product id]
                    $product_data = wp_remote_get($json_url . $product_id);
                    
                    // Process and display product data
                    if (is_array($product_data) && !is_wp_error($product_data)) {
                        $product = wp_remote_retrieve_body($product_data);
                        
                        // Make pagination
                        $body = generate_pagination($product, $body);
                        
                        // Make title
                        $product = json_decode($product, true);
                        $title = generate_title($product['title']);  
                    }
                }

            }
    
    } else {
        
        // Get all products
        $category_data = wp_remote_get( $json_url . '?limit=0');
        $body = wp_remote_retrieve_body($category_data);
        $category = json_decode($body, true);
        
        $full_category = generate_links($body); 
        $body = json_encode($full_category['products']);   

    }

    $html = array(
        'body'          => $body,
        'body_class'    => $body_class, 
        'title'         => $title, 
        'page_title'    => $page_title
    );

    return $html;
}

// Generate links and pagination for category products
function generate_links (string $json): array {

    $products = json_decode($json, true); 

    foreach ($products['products'] as $key => $item) {
        
        $url = make_url($item['title']);
        
        $products['products'][$key]['url'] = $links[$item['id']] = $url;
        
    }

    return array('products' => $products, 'links' => $links);
}

// Generate pagination for single products
function generate_pagination (string $product_json, string $category_json): string {

    $product = json_decode($product_json, true);
    $category = json_decode($category_json, true);
    
    foreach ($category['products'] as $key => $item) {
        $product['pagination'][$item['id']] = make_url($item['title']);
    } 
    
    $product['url'] = make_url($product['title']);

    return json_encode($product);
}


/**
 * Generate main page title
 *
 * @param  string $value
 * @param  string $type
 * @return string
 */
function generate_title( string $value, string $type = 'category'): string {

    $title = array( 
            get_option('shop_' . $type . '_prefix'),
            $value,
            get_option('shop_' . $type . '_postfix')
    );        

    return implode(' ', $title);
}

/**
 * Generate custom page title
 *
 * @param  string $value
 
 * @return string
 */
function generate_custom_title( string $value ): string {

    return ucfirst(str_replace('-', ' ', $value));
}


/**
 * Make SEO url
 *
 * @param  string $value
 * @return string
 */
function make_url (string $value): string {

    $value = str_replace(['"', "'"], '', $value);
    $value = str_replace(' ', '-', $value);
    $value = strtolower($value);
    $value = sanitize_title($value);
    
    return $value;
}


/**
 * Add to cart function
 *
 * @return void
 */
function add_to_cart_ajax_callback(): void {
    $product_id = intval($_POST['product_id']);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] ++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    $_SESSION['cart_dscription'][$product_id] = array(
        'title'     => esc_html($_POST['title']),
        'thumbnail' => esc_html($_POST['thumbnail']),
        'url'       => esc_html($_POST['url']),
    );

    $cart_count = array_sum($_SESSION['cart']);
    echo $cart_count;

    wp_die(); 
}
add_action('wp_ajax_add_to_cart', 'add_to_cart_ajax_callback');
add_action('wp_ajax_nopriv_add_to_cart', 'add_to_cart_ajax_callback'); // For non-logged in users


/**
 * Place order function
 *
 * @return void
 */
function place_order_ajax_callback(): void {

    $order = $_SESSION['cart_dscription'];
    $json = json_encode($order);
    echo $json;

    unset($_SESSION['cart_dscription']);
    unset($_SESSION['cart']);

    wp_die(); 
}
add_action('wp_ajax_place_order', 'place_order_ajax_callback');
add_action('wp_ajax_nopriv_place_order', 'place_order_ajax_callback'); 



?>