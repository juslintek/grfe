<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://estrategai.lt
 * @since      1.0.0
 *
 * @package    Grfe
 * @subpackage Grfe/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Grfe
 * @subpackage Grfe/public
 * @author     Linas Jusys <linas@estrategai.lt>
 */

class Grfe_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The options name to be used in this plugin
     *
     * @since  	1.0.0
     * @access 	private
     * @var  	string 		$option_name 	Option name of this plugin
     */
    private $option_name = 'grfe_control';

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version     The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Grfe_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Grfe_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/grfe-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Grfe_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Grfe_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/grfe-public.js', array('jquery'), $this->version, false);

    }

    public function embed_product_remarketing_tag(){
        global $product;
        //var_dump($product);

        if($product->is_type('variable')){
            $regular_price = !empty($product->price)?$product->price:get_post_meta($product->ID, '_min_variation_regular_price', true);
            //$sales_price = get_post_meta($product->ID, '_min_variation_sale_price', true);
        } else {
            $regular_price = !empty($product->price)?$product->price:get_post_meta($product->ID, '_price', true);
            //$sales_price = $product->get_sale_price();
        }
        ?>
        <!-- „Google“ pakartotinės rinkodaros žymos kodas -->
        <!--------------------------------------------------
        Pakartotinių rinkodaros žymų negalima susieti su tapatybe identifikuojančia informacija arba dėti puslapiuose, susijusiuose su delikataus turinio kategorijomis. Žr. daugiau informacijos ir nurodymų, kaip nustatyti žymą: http://google.com/ads/remarketingsetup
        --------------------------------------------------->
        <script type="text/javascript">
            var google_tag_params = {
                dynx_itemid: '<?php the_ID() ?>',
                dynx_itemid2: '<?php echo $product->get_sku() ?>',
                dynx_pagetype: 'offerdetail',
                dynx_totalvalue: '<?php echo $regular_price ?>',
            };
        </script>
        <script type="text/javascript">
            /* <![CDATA[ */
            var google_conversion_id = 877965005;
            var google_custom_params = window.google_tag_params;
            var google_remarketing_only = true;
            /* ]]> */
        </script>
        <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
        </script>
        <noscript>
            <div style="display:inline;">
                <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/879856871/?value=0&amp;guid=ON&amp;script=0"/>
            </div>
        </noscript>
        <?php
    }

    public function wc_price_no_html( $price ){
            $currency          = '';
            $decimal_separator  = wc_get_price_decimal_separator();
            $thousand_separator = wc_get_price_thousand_separator();
            $decimals           = wc_get_price_decimals();
            $price_format       = get_woocommerce_price_format();

        $negative        = $price < 0;
        $price           = floatval( $negative ? $price * -1 : $price );
        $price           = number_format( $price, $decimals, $decimal_separator, $thousand_separator );

        if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $decimals > 0 ) {
            $price = wc_trim_zeros( $price );
        }

        $formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, get_woocommerce_currency_symbol( $currency ), $price );
        $return          = $formatted_price;

        return $return;
    }

    public function export_feed()
    {
        if (isset($_GET['remarketing']) && $_GET['remarketing'] == 'run') {

            $productsArray = array(array("ID", "ID2", "Item title", "Item subtitle", "Final URL", "Image URL", "Item description", "Item category", "Price", "Sale price", "Item address"));

            $params = array(
                'posts_per_page' => -1,
                'post_type' => array('product'),
                'meta_query' => array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock'
                    ),
                    array(
                        'key' => '_backorders',
                        'value' => 'no'
                    )
                )
            );

            $products = new WP_Query($params);

            while ( $products->have_posts() ) {

                $products->the_post();

                global $product;

                $sku = $product->get_sku();
                $item_title = mb_substr(html_entity_decode($product->get_title(), ENT_QUOTES, 'UTF-8'), 0, 25);
                $final_url = $product->get_permalink();
                $item_image_url = wp_get_attachment_image_src($product->get_image_id(), 'single-post-thumbnail')[0];

                if(!empty($product->post->post_excerpt)){
                    $subtitle_content = $product->post->post_excerpt;
                } else {
                    $subtitle_content = $product->post->post_content;
                }

                if(!empty($product->post->post_content)){
                    $content = $product->post->post_content;
                } else {
                    $content = $product->post->post_excerpt;
                }

                $subtitle_content = wp_strip_all_tags(html_entity_decode($subtitle_content, ENT_QUOTES, 'UTF-8'), true);
                $content = wp_strip_all_tags(html_entity_decode($content, ENT_QUOTES, 'UTF-8'), true);

                $item_subtitle = mb_substr($subtitle_content, 0, 25);
                $item_description = mb_substr($content, 0, 25);

                $item_category = current(get_the_terms($product->post->ID, 'product_cat'))->name;

                if($product->is_type('variable')){
                    $regular_price = get_post_meta($products->post->ID, '_min_variation_regular_price', true);
                    $sales_price = get_post_meta($products->post->ID, '_min_variation_sale_price', true);
                } else {
                    $regular_price = $product->get_regular_price();
                    $sales_price = $product->get_sale_price();
                }

                if(!empty($regular_price)) {
                    $item_price = html_entity_decode($this->wc_price_no_html($regular_price), ENT_QUOTES, 'UTF-8');
                } else {
                    $item_price = '';
                }

                if(!empty($sales_price)) {
                    $item_sale = html_entity_decode($this->wc_price_no_html($sales_price), ENT_QUOTES, 'UTF-8');
                } else {
                    $item_sale = '';
                }

                if(!empty($regular_price)) {
                    $productsArray[] = array(
                        "ID" => $products->post->ID, // ID
                        "ID2" => !empty($sku) ? $sku : $products->post->ID, // ID2/SKU
                        "Item title" => $item_title, //Item title
                        "Item subtitle" => $item_subtitle, //Item title
                        "Final URL" => $final_url, // Final URL
                        "Image URL" => $item_image_url, // Image URL
                        "Item description" => $item_description, // Item Description
                        "Item category" => $item_category, // Item Category
                        "Price" => $item_price, // Price
                        "Sale price" => $item_sale, // Sale Price
                        "Item address" => get_option($this->option_name . '_address') // Item address
                    );
                }

            }

            wp_reset_query();

            date_default_timezone_set(get_option('timezone_string'));

            if (isset($_GET['format']) && $_GET['format'] == 'csv') {
                header('Content-Type: text/html; charset=UTF-8');
                header("Content-type: text/csv");
                header("Content-Disposition: attachment; filename=\"grizas_feed_" . date('Y-m-d_H:i:s') . ".csv\"");
                header("Pragma: no-cache");
                header("Expires: 0");
                $out = fopen("php://output", 'w');

                foreach ($productsArray as $row) {
                    fputcsv($out, $row, ",");
                }
                fclose($out);

            }
            else {
                require_once(GRFE_PATH . 'lib/phpexcel/PHPExcel.php');

                $doc = new PHPExcel();
                $doc->setActiveSheetIndex(0);
                $doc->getActiveSheet()->fromArray($productsArray, null, 'A1');
                header('Content-Type: text/html; charset=UTF-8');
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="grizas_feed_' . date('Y-m-d_His') . '.xls');
                header('Cache-Control: max-age=0');

                $writer = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

                $writer->save('php://output');
            }

            exit();

        }
    }

}
