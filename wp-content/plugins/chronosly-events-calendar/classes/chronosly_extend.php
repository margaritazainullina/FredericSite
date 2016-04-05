<?php
if(!class_exists('Chronosly_Extend'))
{


    class Chronosly_Extend
	{
		/**
		 * Construct the plugin object
		 */


		public function __construct()
		{


			// register actions

            add_action('init', array(&$this, 'admin_init'), 11);

           add_action("chronosly_update_addons", array(&$this, 'update_addons'), 100, 1);
           add_action("chronosly_update_templates",array(&$this, 'update_templates'), 100, 1);


            add_action( 'widgets_init', array(&$this, 'register_widgets' ));

        //	add_action('admin_menu', array(&$this, 'extra_init'));
		//	add_action( 'admin_enqueue_scripts', array(&$this,'extra_init') );


		} // END public function __construct


        public function get_addons_feed(){

           $addons = json_decode(
'{
    "import_and_export":{
        "id":"import_and_export",
        "version":"2.0",
        "name":"Import and Export",
        "desc":"Import / Export your content (events, organizers, places and calendars) to iCal, .csv file (Excel or Numbers), Facebook or Google+" ,
        "img":"http://plugins.heimsveld.com/thumbnail-import-export-addon-230px.png",
        "price":27,
        "author":"Chronosly",
        "author_url":"http://www.chronosly.com",
        "view":"https://www.chronosly.com/faq/addons/import-export-events-organizers-places/?utm_source=clients&utm_medium=referral&utm_campaign=Addons2Import_ExportAddon_FAQ",
        "url":"https://www.chronosly.com/product/import-export/?utm_source=clients&utm_medium=referral&utm_campaign=Addons2Import_Export",
        "featured":0,
        "updated":1414467200,
        "dw":10,
        "rate":10
    },
    "filter_and_sort":{
        "id":"filter_and_sort",
        "version":"2.0",
        "name":"Filter and Sort",
        "desc":"Allow you to show your content based on given set of criteria" ,
        "img":"http://plugins.heimsveld.com/thumbnail-filters-sorting-addon-230px.png",
        "price":21,"author":"Chronosly",
        "author_url":"http://www.chronosly.com",
        "view":"https://www.chronosly.com/faq/addons/filter-sort-addon/?utm_source=clients&utm_medium=referral&utm_campaign=Addons2FilterSort_FAQ",
        "url":"https://www.chronosly.com/product/filter-sort-addon/?utm_source=clients&utm_medium=referral&utm_campaign=Addons2FilterSort",
        "featured":0,
        "updated":1414467200,
        "dw":10,
        "rate":10
    },
    "social_media_share":{
        "id":"social_media_share",
        "version":"1.9",
        "name":"Social Media Share",
        "desc":"Add social media buttons to your events for sharing, going viral or bigger potential audience" ,
        "img":"http://plugins.heimsveld.com/thumbnail-social-media-230px.png",
        "price":14,
        "author":"Chronosly",
        "author_url":"http://www.chronosly.com",
        "view":"https://www.chronosly.com/faq/addons/social-media-share-addon/?utm_source=clients&utm_medium=referral&utm_campaign=Addons2SMMShare_FAQ",
        "url":"https://www.chronosly.com/product/social-media-addon/?utm_source=clients&utm_medium=referral&utm_campaign=Addons2SMMShare",
        "featured":0,
        "updated":1412467200,
        "dw":1,
        "rate":8
    },
    "frontend_event_submission":{
        "id":"frontend_event_submission",
        "version":"1.8",
        "name":"Frontend Event Submission",
        "desc":"Frontend event submission addon allow users to send events for admin approval" ,
        "img":"http://plugins.heimsveld.com/thumbnail-frontend-event-submission-230px.png",
        "price":29,
        "author":"Chronosly",
        "author_url":"http://www.chronosly.com",
        "view":"https://www.chronosly.com/faq/addons/new-frontend-event-submission-addon/?utm_source=clients&utm_medium=referral&utm_campaign=Addons2EventSubmit_FAQ",
        "url":"https://www.chronosly.com/product/frontend-event-submission-addon/?utm_source=clients&utm_medium=referral&utm_campaign=Addons2EventSubmit",
        "featured":0,
        "updated":1412467200,
        "dw":1,
        "rate":8
    },
    "tickets_and_repeats_extended":{
        "id":"tickets_and_repeats_extended",
        "version":"1.4",
        "name":"Tickets and Repeat Enhancement ",
        "desc":"Enhance ADVANCED recurring and ticketing event features" ,
        "img":"http://plugins.heimsveld.com/thumbnail-recurring-events-enahancement-230px.png",
        "price":20,
        "author":"Chronosly",
        "author_url":"http://www.chronosly.com",
        "view":"https://www.chronosly.com/faq/addons/upcoming-recurring-events-ticketing/?utm_source=clients&utm_medium=referral&utm_campaign=Addons2Recurring_FAQ",
        "url":"https://www.chronosly.com/product/recurring-ticketing-enhancement-for-events-addon/?utm_source=clients&utm_medium=referral&utm_campaign=Addons2Recurring",
        "featured":0,
        "updated":1412467200,
        "dw":1,
        "rate":9
    },
    "map_navigation":{
        "id":"map_navigation",
        "version":"1.0",
        "name":"Map my events",
        "desc":"Display ALL your events (map pins) on a full-size Google map" ,
        "img":"https://www.chronosly.com/wp-content/uploads/2015/09/full-event-map-addon-DEF.png",
        "price":19,
        "author":"Chronosly",
        "author_url":"http://www.chronosly.com",
        "view":"https://www.chronosly.com/faq/addons/map-my-events/?utm_source=clients&utm_medium=referral&utm_campaign=Addons2Map_FAQ",
        "url":"https://www.chronosly.com/product/map-my-events/?utm_source=clients&utm_medium=referral&utm_campaign=Addons2Map",
        "featured":0,
        "updated":1412467200,
        "dw":1,
        "rate":9
    }
}');
            set_transient( "addons_marketplace", $addons, 60 * 60 * 24  );
            foreach($addons as $k=>$ad){
                if($settings = unserialize(get_option("chronosly_settings_{$k}"))) {
                    $settings["needed_version"] = $ad->version;
                    update_option("chronosly_settings_{$k}", serialize($settings));
                }
            }

            return $addons;

        }

        public function get_templates_feed(){

            $templates= json_decode(
'{
    "default":{"id":"default","version":"1.0","name":"Default","desc":"Default Template","img":"http://plugins.heimsveld.com/thumbnail-default-300px.jpg","price":0,"author":"Chronosly","author_url":"http://www.chronosly.com","view":"http://www.chronosly.com","url":"http://www.chronosly.com","featured":0,"updated":1412467200,"dw":1,"rate":8}, 
    "dark":{"id":"dark","version":"2.0","name":"Dark","desc":"This is the dark design version of Chronosly Default template, specially designed to match with darker theme sites.","img":"http://plugins.heimsveld.com/thumbnail-dark-300px.jpg","price":12,"author":"Chronosly","author_url":"http://www.chronosly.com", "view":"http://dark.chronosly.com/events/?utm_source=chronosly.com&utm_medium=referral&utm_campaign=Templates2DarkLiveDemo", "url":"https://www.chronosly.com/product/dark-template-addon/?utm_source=clients&utm_medium=referral&utm_campaign=Templates2Darkprofile","featured":0,"updated":1409356800,"dw":0,"rate":8}, 
    "grid":{"id":"grid","version":"2.0","name":"Grid","desc":"Responsive theme with 3 Column Grid based homepage layout.","img":"http://plugins.heimsveld.com/thumbnail-grid-300px.jpg","price":15,"author":"Chronosly","author_url":"http://www.chronosly.com", "view":"http://grid.chronosly.com/events/?utm_source=chronosly.com&utm_medium=referral&utm_campaign=Templates2GridLiveDemo", "url":"https://www.chronosly.com/product/grid-template/?utm_source=clients&utm_medium=referral&utm_campaign=Templates2Gridprofile","featured":0,"updated":1409356800,"dw":0,"rate":8},
    "tickets":{"id":"tickets","version":"1.4","name":"Ticketing","desc":"Modern and clean design, effective and convincing way to sell your tickets","img":"http://plugins.heimsveld.com/thumbnail-ticketing-300px.jpg","price":15,"author":"Chronosly","author_url":"http://www.chronosly.com", "view":"http://ticketing.chronosly.com/events/?utm_source=chronosly.com&utm_medium=referral&utm_campaign=Templates2TicketingLiveDemo", "url":"https://www.chronosly.com/product/ticketing-template/?utm_source=clients&utm_medium=referral&utm_campaign=Templates2Ticketingprofile","featured":0,"updated":1409356800,"dw":0,"rate":8}

}');
            set_transient( "templates_marketplace", $templates, 60 * 60 * 24  );
            return $templates;

        }

        public function register_widgets() {
            register_widget( 'Chronosly_Widget' );
        }


        public function update_addons($addon=""){

            //si se llama desde el admin ejecutar un reload par que lo cargue bien en la pantalla
            //

        }

        public function update_templates($template=""){


        }

        public function plugin_updated(){
            $file = CHRONOSLY_ADDONS_PATH.DIRECTORY_SEPARATOR."version.json";
            if ($f = @fopen($file, "r")) {
                $version =@fread($f, filesize($file));
                if($version != CHRONOSLY_VERSION) {
                    if($f1 = @fopen($file, "w+")){
                        fwrite($f1, CHRONOSLY_VERSION);
                        @fclose($f1);
                        return true;
                    } else return false;
                }
                 @fclose($f);
                 return false;
                

            } else {
                if($f = @fopen($file, "w+")){
                    fwrite($f, CHRONOSLY_VERSION);
                    @fclose($f);
                    return true;
                } else return false;
            }
        }
        
        public function copy_default_template(){
            $utils = new Chronosly_Utils();
            $files = scandir (CHRONOSLY_PATH.DIRECTORY_SEPARATOR."custom-templates");
            foreach ( $files as $file ) {
                if ($file != "." && $file != ".."){
                    $utils->rcopy( CHRONOSLY_PATH.DIRECTORY_SEPARATOR."custom-templates/$file", CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR."$file" );
                }
            }

        }

        public function rebuild_addons_files(){
            $utils = new Chronosly_Utils();
            if(is_dir(CHRONOSLY_ADDONS_PATH.DIRECTORY_SEPARATOR."organizers_and_places")){
                $files = scandir ( CHRONOSLY_ADDONS_PATH.DIRECTORY_SEPARATOR."organizers_and_places");
                foreach ( $files as $file ) {
                    if ($file != "." && $file != ".."){
                        $utils->rcopy ( CHRONOSLY_ADDONS_PATH.DIRECTORY_SEPARATOR."organizers_and_places/$file", CHRONOSLY_PATH."/$file" );
                    }
                }
                // $utils->rrmdir( $destination_path.DIRECTORY_SEPARATOR."organizers_and_places");
                $settings = unserialize(get_option("chronosly-settings"));
                $settings["chronosly_organizers_addon"] = 1;
                $settings["chronosly_places_addon"] = 1;
                update_option('chronosly-settings', serialize($settings));
            }
            if(is_dir(CHRONOSLY_ADDONS_PATH.DIRECTORY_SEPARATOR."extended_marketplace")) {
                $files = scandir ( CHRONOSLY_ADDONS_PATH.DIRECTORY_SEPARATOR."extended_marketplace");
                foreach ( $files as $file ) {
                    if ($file != "." && $file != ".."){
                        $utils->rcopy ( CHRONOSLY_ADDONS_PATH.DIRECTORY_SEPARATOR."extended_marketplace/$file",  CHRONOSLY_PATH."/$file" );
                    }
                }
            }

        }


        //Cuando un template se actualiza se pierden los añadidos de los addons
        public function rebuild_template_addons($template){
            global  $Post_Type_Chronosly;
            $addons = array();
            $addons_list = apply_filters("chronosly_addons_settings_item", $addons);
            if(!count($addons_list)) {
                Chronosly_Extend::init_addons();
                $addons_list = apply_filters("chronosly_addons_settings_item", $addons);
            }

            if(count($addons_list)){
                foreach($addons_list as $k=>$v){
                    if(has_filter("chronosly_update_template_{$k}")) $Post_Type_Chronosly->template->full_update_templates_by_addon($k,array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12), array($template));

                }
            }
        }


        public function get_external_content($url, $format=""){
            $response = wp_remote_post($url, array("timeout" => 60));
            $cont = array();
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                $cont["error"] = 1 ;
                $cont["message"] = "Something went wrong: $error_message";
            } else {
                if($response["response"]["code"] != 200){
                    $cont["error"] = 1 ;
                    $cont["message"] = $response["response"]["message"].": ".$response["body"];
                }
               else {
                   $cont["error"] = 0 ;
                   switch($format){
                       case "json":
                           $cont["content"] = json_decode($response["body"]);
                           break;
                       default:
                           $cont["content"] = $response["body"];
                           break;
                   }
               }
            }
           //print_r($response);
           // print_r($cont);
            return $cont;
        }

        //update code via donwloadding zip
        public function update_code($type, $url, $addon=""){
            global $Chronosly_Settings;
            switch($type){
                case "core":
                    $path = CHRONOSLY_PATH;
                break;
                case "addons":
                    $path = CHRONOSLY_ADDONS_PATH;
                break;
                case "templates":
                    $path = CHRONOSLY_TEMPLATES_PATH;

                break;
            }

            $cont = $this->get_external_content($url);
           // return;
            //save the zip temporaly
            
            if($cont["error"]) return 0;
            $zip = $cont["content"];
            $fp = fopen(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."temp.zip", "w");
            fwrite($fp, $zip);
            fclose($fp);
            require_once(ABSPATH .'/wp-admin/includes/file.php');
            WP_Filesystem();
            if(stripos($addon, "organizers_and_places") !== FALSE or stripos($addon, "extended_marketplace") !== FALSE) $path = CHRONOSLY_PATH;
            $unzipfile = unzip_file(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."temp.zip", $path);
            @unlink( CHRONOSLY_PATH.DIRECTORY_SEPARATOR."temp.zip");
            $utils = new Chronosly_Utils();

            if(stripos($addon, "organizers_and_places") !== FALSE and $unzipfile === true) {
                $files = scandir ( $path.DIRECTORY_SEPARATOR."organizers_and_places");
                foreach ( $files as $file ) {
                    if ($file != "." && $file != ".."){
                        $utils->rcopy( $path.DIRECTORY_SEPARATOR."organizers_and_places/$file", $destination_path.DIRECTORY_SEPARATOR."$file" );
                    }
                }
            }
            else if(stripos($addon, "extended_marketplace") !== FALSE and $unzipfile === true) {
                $files = scandir ( $path.DIRECTORY_SEPARATOR."extended_marketplace");
                foreach ( $files as $file ) {
                    if ($file != "." && $file != ".."){
                        $utils->rcopy( $path.DIRECTORY_SEPARATOR."extended_marketplace/$file", $destination_path.DIRECTORY_SEPARATOR."$file" );
                    }
                }
            }

            if ( $unzipfile === true) return 1;
            return 0;
        }

        public static function create_dad_field($data){
            global $ch_field_create_js_code, $ch_field_modify_js_code, $ch_js_style_fields, $ch_php_style_fields;
            if(!count($data)) return; //el data tiene que ser un array valido
            if(!$data["name"] or !$data["type"] or !$data["type"]) return; //aseguramos que tenemos los minimos
            if($data["el_type"] == "var" and (!$data["php_function"] or !$data["js_function"])) return;
            else if($data["el_type"] == "style" and (!$data["style_fields"] or !$data["js_style_values"] or !$data["php_style_values"])) return;
            $return = "";
            $return .= Chronosly_Extend::create_field($data);
            //llamamos a los fields associados a este value

            if(isset($data['fields_associated'])){
                foreach($data['fields_associated'] as $field){
                    if(has_filter("chronosly_field_".$field["name"])){
                        $default = $field;

                        //if(count($fields_array) and isset($fields_array[$field["name"]])) $default = array_merge($default, $fields_array[$field["name"]]); //setting values to fields
                        $return .= apply_filters("chronosly_field_".$field["name"], $default);
                    }
                }
            }

            if(isset($data['js_function'])){
                //si no la hemos añadido ya antes

                if(stripos($ch_field_create_js_code, $data["name"]."chseparator1") === FALSE){

                    //si existe la funcion llamamos para generar el array de funciones que imprimiremos en el js al crear el elemento o eliminarlo

                    if(!is_array($data['js_function'])){
                        if(function_exists($data['js_function'])){
                                $ch_field_create_js_code .= $data["name"]."chseparator1".call_user_func($data['js_function'], "create")."chseparator2";
                                $ch_field_modify_js_code .= $data["name"]."chseparator1".call_user_func($data['js_function'], "modify")."chseparator2";


                        }
                    } else {
                        if(class_exists($data['js_function'][0]) and method_exists ($data['js_function'][0], $data['js_function'][1])){
                            $ch_field_create_js_code .= $data["name"]."chseparator1".call_user_func($data['js_function'], "create")."chseparator2";
                            $ch_field_modify_js_code .= $data["name"]."chseparator1".call_user_func($data['js_function'], "modify")."chseparator2";
                        }
                    }

                }

            }
            //creamos el filter para hacer render en el frontend

            if(isset($data['php_function'])){
                if(!has_filter("chronosly_field_render_".$data['name'])){
                   //echo "chronosly_field_render_".$data["name"]." ";
                    //print_r($data['php_function']);echo "<br/>";
                    add_filter("chronosly_field_render_".$data["name"], $data['php_function'], 10, 4);
                }
            }

            //si es una field de estilos
            if(isset($data['style_fields']) and isset($data['js_style_values']) and isset($data['php_style_values'])){
                $ch_js_style_fields[$data["name"]] = array("fields" => $data['style_fields'], "values" => $data['js_style_values']);
                $ch_php_style_fields[$data["name"]] = array("fields" => $data['style_fields'], "values" => $data['php_style_values']);
            }

            return $return;

        }



        /* recibe un array del formato
        array(
            "label" => "Event title",
            "name" => "title",
            "type" => "hidden",
            "fields_associated" => array(
                array(
                    "name" => "shorten_text",
                    "label" => "Shorten title"
                ) ,
                array(
                    "name" => "readmore_check",
                    "label" => "Link title"
                ) ,
                array(
                    "name" => "readmore_action",
                    "label" => "Action when click link"
                ) ,
                array(
                    "name" => "target_blank"
                ) ,
                array(
                    "name" => "nofollow"
                )
            ) ,
            "data_field" => "post_title",
            "data_static function" => array(
                "Chronosly_Teplates",
                "get_title_content"
            )

           @inside_template para cargar los datos hidden en el template,
            si es 0 representa un elemento a hacer dad,
             si es 1 representa un elemento ya cargado en el template  con un value
        */
        public static function create_dad_buble($data, $inside_template = 0, $fields_array = array(), $style = array()){

            global $ch_bubble_create_js_code, $ch_bubble_modify_js_code;
            if(!count($data)) return; //el data tiene que ser un array valido
            if($data['name'] != "box" and (!$data["name"]  or !$data["box_name"]  or !$data["type"])) return; //aseguramos que tenemos los minimos
            $return = "";
            if(!$inside_template and isset($data["box_info"])) $return .= "<li class='draggable info' title='".$data["box_info"]."'> <span class='title'>".__($data["box_name"], "chronosly")."</span>";//añadimos el d&d si es una box
            else if(!$inside_template) $return .= "<li class='draggable'> <span class='title'>".$data["box_name"]."</span>";//añadimos el d&d si es una box
           if($data['name'] != "box") {
               $return .= "<div class='ev-hidden'>
                            <div class='vars'>";

                 if(isset($fields_array["bubble_value"])) $data["value"] = $fields_array["bubble_value"];
                            $return .= Chronosly_Extend::create_field($data, 1); //creamos el campo

                            if($data['js_function']){
                                //si no la hemos añadido ya antes
                                if(stripos($ch_bubble_create_js_code, $data["name"]."chseparator1") === FALSE){
                                    //si existe la funcion llamamos para generar el array de funciones que imprimiremos en el js al crear el elemento o eliminarlo
                                    if(!is_array($data['js_function'])){
                                        if(function_exists($data['js_function'])){
                                            $ch_bubble_create_js_code .= $data["name"]."chseparator1".call_user_func($data['js_function'], "create")."chseparator2";
                                            $ch_bubble_modify_js_code .= $data["name"]."chseparator1".call_user_func($data['js_function'], "modify")."chseparator2";
                                        }
                                    } else {
                                        if(class_exists($data['js_function'][0]) and method_exists ($data['js_function'][0], $data['js_function'][1])){
                                            $ch_bubble_create_js_code .= $data["name"]."chseparator1".call_user_func($data['js_function'], "create")."chseparator2";
                                            $ch_bubble_modify_js_code .= $data["name"]."chseparator1".call_user_func($data['js_function'], "modify")."chseparator2";
                                        }
                                    }
                                }
                            }



                //llamamos a los fields associados a este value
                 if(isset($data['fields_associated'])){
                     foreach($data['fields_associated'] as $field){
                        if(has_filter("chronosly_field_".$field["name"])){
                                    $default = $field;

                                    if(count($fields_array) and isset($fields_array[$field["name"]])) $default = array_merge($default, $fields_array[$field["name"]]); //setting values to fields
                                     $return .= apply_filters("chronosly_field_".$field["name"], $default);
                                }
                     }
                 }
                            $return .= "</div>";
           }
            /* STYLE BOXES */
                    $styleboxes = Chronosly_Paint::default_style_boxes();
                    foreach($styleboxes as $id=>$box){
                        $return .= "<div class='$id'>";
                        $cont = "";
                        $cont .= apply_filters("chronosly_style_box_".$id."_fields", $cont, $style);//genramos los inputs de los stylers
                        $return .= $cont;
                        $return .= "</div>";
                    }
            if($data['name'] != "box"){
                $return .= "</div>";


                if(!$inside_template) $return .= "</li>";

                //creamos el filter para hacer render en el frontend
                if($data['php_function']){
                    if(!has_filter("chronosly_bubble_render_".$data['name'])) add_filter("chronosly_bubble_render_".$data["name"], $data['php_function'], 10, 3);
                }
            }
            return $return;

        }

        private static function create_field($data, $order=0){
            $return = "";
            $ord = "";
            if($order) $ord = ' order="0" ';
            if(!isset($data['value'])) $data['value'] = "";
            switch($data['type']) {

                case "hidden":
                case "cont_box":
                    $return  .= '<input '.$ord.' class="'.$data['name'].'" name="'.$data['name'].'" type="hidden" value="'.$data['value'].'" />';
                    break;
                case "input":
                    $return  .= "<label>".$data['label'].'</label> <input '.$ord.' class="'.$data['name'].'" name="'.$data['name'].'" type="text" value="'.$data['value'].'" /><br/>';
                    break;
                case "time":
                    $return  .= "<label>".$data['label'].'</label> <input $ord extra="'.$data["extra"].'" class="'.$data['name'].'" name="'.$data['name'].'" type="text" value="'.$data['value'].'" /><br/>';
                    break;
                case "checkbox":
                    $checked ="";
                    if($data['value']) $checked = 'checked="checked"';
                    $return  .= "<label>".$data['label'].'</label> <input '.$ord.' class="'.$data['name'].'" name="'.$data['name'].'" type="checkbox" value="1" '.$checked.' /><br/>';
                    break;
                case "textarea":
                    $return .= "<label class='full'>".$data['label'].'</label><br/><textarea '.$ord.'  name="'.$data['name'].'" class="textarea2 '.$data['name'].'"  rows="4" cols="50" >'.$data['value'].'</textarea><br/>';
                    break;
                case "wyswyg":
                    $return  .= "<label class='full'>".$data['label'].':</label> <textarea '.$ord.'  name="'.$data['name'].'" class="textarea '.$data['name'].'"  rows="4" cols="50" >'.$data['value'].'</textarea><br/>';

                    break;
                case "image":
                    $return .= "<label>".$data['label'].'</label><br/> <input  '.$ord.'  class="upload_image" type="text" size="36" name="'.$data['name'].'" value="'.$data['value'].'" />
                                                    <input  class="upload_image_button" type="button" value="'.__('Upload Image', 'chronosly').'" /><br/>';

                    break;
                case "gallery":
                    $return .= "<label>".$data['label'].'</label><br/> <input  '.$ord.'  class="upload_gallery" type="text" size="36" name="'.$data['name'].'" value="'.$data['value'].'" /><input  class="upload_gallery_button" type="button" value="'.__("Update Gallery", "chronosly").'" /><br/>';

                    break;
                case "select":
                    $select = 0;
                    $return .= "<label>".$data['label']."</label> <select class='".$data['name']."' name='".$data['name']."'>";
                    $return .= "<option value=''></option>";
                    foreach($data["options"] as $id=>$op){
                        $sel = "";
                        if($data['value'] == $id and $data['value'] !== "") {
                            $select = 1;
                            $sel = "selected='selected'";
                        }
                        $return .= "<option value='$id' $sel>".__($op, "chronosly")."</option>";
                    }
                    $return .= "</select><br/>";
               break;
                case "link":
                    break;

                default:
                    //extendemos los tipos llamando al hook que toca
                    $return .= apply_filters("new_dad_buble_".$data['type'], $data);//se crea un hook especifico para llamar a la variable personalizada
                    break;
            }
            return $return;
        }

        /**
         * hook into WP's admin_init action hook
         */
       public  function admin_init()
        {



            $this->init_addons();
            do_action("chronosly_dad_vars_fields");




        }

        //cargamos los init de la carpeta addons
        private static function init_addons(){
            $addonspath = CHRONOSLY_ADDONS_PATH;
            if ($handle = opendir($addonspath)) {
                while (false !== ($entry = readdir($handle))) {
                    if($entry != "." and $entry != ".." and is_dir($addonspath.DIRECTORY_SEPARATOR.$entry)) {
                        if ($handle2 = opendir($addonspath.DIRECTORY_SEPARATOR.$entry)) {
                            while (false !== ($entry2 = readdir($handle2))) {
                                if($entry2 == "init.php") require_once($addonspath.DIRECTORY_SEPARATOR.$entry.DIRECTORY_SEPARATOR."init.php");
                            }
                            closedir($handle2);

                         }


                    }

                }

                closedir($handle);
            }
        }


		// END public function plugin_settings_page()



    } // END
} // END


