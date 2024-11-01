<?php
use PISOL\EFRS\Package\PackageManager;

class pisol_efrs_package_manager{

    static $instance = false;

    public static function get_instance(){
        if ( ! self::$instance ) {
            self::$instance = new pisol_efrs_package_manager();
        }
        return self::$instance;
    }   

    function __construct(){
        add_filter('woocommerce_cart_shipping_packages', [$this, 'custom_shipping_packages'], 10);

        add_filter( 'woocommerce_shipping_package_name', [$this, 'custom_shipping_package_name'], 10, 3 );
    }

    function custom_shipping_packages($packages){

        $final_packages = [];
        if(count($packages) > 1) return $packages;

        $final_packages = $this->get_final_packages($packages[0]);

        if(count($final_packages) > 0){
            return $final_packages;
        }   

        return $packages;
    }

    function get_final_packages($package){
        $custom_packages = self::get_custom_packages();

        if(count($custom_packages) == 0){
            $final_packages[] = $package;
            return $final_packages;
        }

        $final_packages = [];

        foreach($custom_packages as $custom_package){
            $package_manager = new PackageManager($package, $custom_package);

            if($package_manager->is_valid_package()){
                $new_packages = $package_manager->get_package();
                $final_packages[] = $new_packages;
                $remaining_package = $package_manager->get_remaining_package();
                if($remaining_package !== false){
                    $package = $remaining_package;
                }else{
                    return $final_packages;
                }
            }
        }

        
        $final_packages[] = $package;
        return $final_packages;
        
    }

    static function get_custom_packages(){
        return get_posts(array(
            'post_type'=>'pi_efrs_package',
            'numberposts'      => -1,
            'fields' => 'ids',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'pi_priority', 
                    'compare' => 'EXISTS'
                ),
                array(
                    'key' => 'pi_status', 
                    'compare' => '==',
                    'value' => 'on'
                )
            ),
            'orderby'          => 'meta_value_num', 
            'meta_key'         => 'pi_priority',    
            'order'            => 'DESC'
        ));
    }

    function custom_shipping_package_name($name, $i, $package){
        
        if(isset($package['package_name'])){
            return $package['package_name'];
        }

        return $name;
    }

}

pisol_efrs_package_manager::get_instance();