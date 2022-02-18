<?php

function mps_store_enqueue_scripts_func()
{
    wp_register_style('mps-store-style', MPS_STORE_LOCATOR_PLUGIN__URL__ . 'shortcode/static/style.css');
    wp_register_script('mps-store-init-script', MPS_STORE_LOCATOR_PLUGIN__URL__ . 'shortcode/static/init.js');
    wp_register_script('mps-store-g-maps', 'https://maps.googleapis.com/maps/api/js?key=' . get_option('mps_store_google_api') . '&callback=initMap', '', '', true);
    wp_register_script('mps-store-script', MPS_STORE_LOCATOR_PLUGIN__URL__ . 'shortcode/static/script.js', ['jquery']);
    wp_enqueue_style('mps-store-style');
}

    add_action('wp_enqueue_scripts', 'mps_store_enqueue_scripts_func');
    
    function enqueue_our_required_stylesheets(){
wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/5.4.0/css/font-awesome.min.css');
}
add_action('wp_enqueue_scripts','enqueue_our_required_stylesheets');

function mps_store_show_func($atts)
{    
    wp_enqueue_script('mps-store-init-script');
    wp_enqueue_script('mps-store-g-maps');
    wp_localize_script('mps-store-script', 'ajax', array(
        'url' => admin_url('admin-ajax.php')
    ));
    wp_enqueue_script('mps-store-script');


    ob_start();
?>
    <section class="shop-locator">
        <div class="wrapper-shop-locator">
            <div class="store">
                <h1>Find your store</h1>
                <form method="get">
                    <input type="text" class="" placeholder="Postcode">
                    <span><i class="fa fa-long-arrow-right"></i></span>
                </form>
                <ul id="mps-store-list">
                </ul>
            </div>
            <div class="map">
                <div id="mps-gmap"></div>
            </div>
        </div>

    </section>

<?php

    return ob_get_clean();
}

function mps_store_get_stores_callback()
{
    global $wpdb;
    $lat = isset($_POST['lat']) ? sanitize_text_field($_POST['lat']) : 0;
    $long = isset($_POST['long']) ? sanitize_text_field($_POST['long']) : 0;
    $tablePosts = $wpdb->prefix . 'posts';
    $tablePostMeta = $wpdb->prefix . 'postmeta';
    $query = "SELECT ID,post_title,meta_key,meta_value from $tablePosts join $tablePostMeta on $tablePostMeta.post_id=$tablePosts.ID where post_type='mps-store'";
    $query = "SELECT DISTINCT
    city_latitude.post_id,
    city_latitude.meta_key,
    city_latitude.meta_value as latitude,
    city_longitude.meta_value as longitude,
    ((ACOS(SIN($lat * PI() / 180) * SIN(city_latitude.meta_value * PI() / 180) + COS($lat * PI() / 180) * COS(city_latitude.meta_value * PI() / 180) * COS(($long - city_longitude.meta_value) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)*1.6 AS distance,
    $tablePosts.post_title
FROM 
    $tablePostMeta AS city_latitude
    LEFT JOIN $tablePostMeta as city_longitude ON city_latitude.post_id = city_longitude.post_id
    INNER JOIN $tablePosts ON $tablePosts.ID = city_latitude.post_id
WHERE city_latitude.meta_key = 'mps_stores_latlong' AND city_longitude.meta_key = 'mps_stores_latlong_long' and $tablePosts.post_status='publish'
ORDER BY distance ASC limit 25;";
    $results = $wpdb->get_results($query, ARRAY_A);
    $ret = [];
    // The Loop
    foreach ($results as $result) {
        $metas = get_post_meta($result['post_id']);
        $arr = [
            "id" => $result['post_id'],
            "title" => $result['post_title'],
            "address" => $metas['mps_stores_address'][0],
            "city" => $metas['mps_stores_city'][0],
            "country" => $metas['mps_stores_country'][0],
            "phone" => $metas['mps_stores_phone'][0],
            "website" => $metas['mps_stores_website'][0],
            "pincode" => $metas['mps_stores_pincode'][0],
            "distance" => $result["distance"],
            "latitude" => $result["latitude"],
            "longitude" => $result["longitude"],
        ];
        array_push($ret, $arr);
    }
    echo json_encode($ret);
    wp_die();
}

add_shortcode('mps-store-show', 'mps_store_show_func');
add_action('wp_ajax_mps_store_get_stores', 'mps_store_get_stores_callback');
// If you want not logged in users to be allowed to use this function as well, register it again with this function:
add_action('wp_ajax_nopriv_mps_store_get_stores', 'mps_store_get_stores_callback');
