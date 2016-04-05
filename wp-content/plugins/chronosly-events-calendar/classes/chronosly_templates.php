<?php


if(!class_exists('Chronosly_Templates')){


    class Chronosly_Templates{

        /*public $file = "";
        public $chronosly_templates = array("default", "dad_framework");¨*/
        public $vars ;
        public $dadcats ;
        public $settings;

        public function __construct($post =""){
            $this->vars = new Chronosly_Templates_Vars();
            $this->settings = unserialize(get_option("chronosly-settings"));


            if(is_admin()){
                if($post) $this->vars->post = $post;
                if(isset($post->ID)) $this->vars->pid = $post->ID;
                add_action( 'wp_ajax_chronosly_render_template', array(&$this, 'js_render_template' ));
                add_action( 'wp_ajax_chronosly_save_template', array(&$this, 'js_save_template' ));
                add_action( 'wp_ajax_chronosly_delete_template', array(&$this, 'js_delete_template' ));
                add_action( 'wp_ajax_chronosly_get_tipo_template', array(&$this, 'js_get_tipo_template' ));
                add_action( 'wp_ajax_chronosly_clear_cache', array(&$this, 'js_clear_cache' ));
                add_action( 'wp_ajax_chronosly_save_all_html_templates', array(&$this, 'save_all_html_templates' ));
                //add_filter( 'heartbeat_received', array(&$this, 'js_preview_theme' ), 10, 3 );
               //load init vars for templates
                $this->load_template_settings($this->get_templates_options(1));


            }

            add_filter("chronosly_dad_tabs", array("Chronosly_Templates", "chronosly_get_default_dad_tabs"), 5, 3);//get de default tabs for dad
            add_filter("chronosly_dad_tabs_content_event", array($this, "chronosly_default_dad_content_event"), 5, 3);//get de dad bubbles of event
            add_filter("chronosly_dad_tabs_content_organizer", array($this, "chronosly_default_dad_content_organizer"), 5, 3);
            add_filter("chronosly_dad_tabs_content_place", array($this, "chronosly_default_dad_content_place"), 5, 3);
            add_filter("chronosly_dad_tabs_content_categories", array($this, "chronosly_default_dad_content_categories"), 5, 3);
            add_filter("chronosly_dad_tabs_content_other", array($this, "chronosly_default_dad_content_other"), 5, 3);
            add_filter("chronosly_dad_tabs_content_time", array($this, "chronosly_default_dad_content_time"), 5, 3);
            add_filter("chronosly_dad_tabs_content_image", array($this, "chronosly_default_dad_content_image"), 5, 3);
            add_filter("chronosly_dad_tabs_content_tickets", array($this, "chronosly_default_dad_content_tickets"), 5, 3);
            $this->enqueu_css_templates();

        }

        public function js_clear_cache(){
            Chronosly_Cache::clear_cache();
            echo __("Cache cleared", "chronosly");
            die();
        }

        //definimos el post del que cargamos el template
        public function set_post($post){
            if($post) $this->vars->post = $post;
            if($post->ID) $this->vars->pid = $post->ID;

        }

        //Put the templates css needed.
        public function enqueu_css_templates(){
            add_action("chronosly_custom_frontend_css", array(&$this, "set_front_css"), 20);
            add_action("chronosly_custom_backend_css", array(&$this, "set_front_css"), 20);
        }

        public function set_front_css(){
            
            if($handle = opendir(CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR)) {
                while (false !== ($entry = readdir($handle))) {
                    if($entry != "." and $entry != ".." and $entry != "") {
                        if(is_dir( CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR.$entry)) continue;
                         $name =  str_replace(".css", "", $entry);
                        wp_register_style( "chronosly_$name", CHRONOSLY_TEMPLATES_URL.'/css/'.$name.'.css');
                        wp_enqueue_style("chronosly_$name");
                    }

                }

                closedir($handle);
            }

        }

           public function set_shortcode_template_css($template="default"){
            wp_register_style( "chronosly_$template", CHRONOSLY_TEMPLATES_URL.'/css/'.$template.'.css');
            wp_print_styles("chronosly_$template");


        }

        //load de json with tmplate info
        public function load_template_settings($templates, $array = 0){

            $return = "";
            foreach($templates as $t){
                $name = str_replace(" ","_",$t);
                $file = CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR.$name.".json";
                if ($f = @fopen($file, "r")) {
                    $content =str_replace(array('\\"', "\\'"), array('"',"'"),@fread($f, filesize(CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR.$k)));
                    @fclose($f);
                    $content = json_decode($content);
                    if($array) $return[$name] = $content;
                    else $this->set_template_vars($content);

                }
            }
            if($array) return $return;
        }

        //set the info of the template
        public function set_template_vars($vars){
            if(isset($vars->addons_version) and is_object($vars->addons_version)){
                foreach($vars->addons_version as $addon=>$version){
                    if($addons_sets = unserialize(get_option("chronosly_settings_".$addon))){
                       if(floatval($addons_sets["needed_version"]) < floatval($version)){
                           $addons_sets["needed_version"] = $version;
                           update_option("chronosly_settings_".$addon, serialize($addons_sets));
                       }
                    }
                }
            }
        }


        public function js_delete_template(){
            $path = CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR;
            $name = (isset($_REQUEST['name']))?$_REQUEST['name']:"";
            if(file_exists($path.$name.".json")){
                 $ret = array(
                            "error"=> 1,
                            "message" => __("This template is a predefined template, you can't delete it", "chronosly")
                        );

                echo json_encode($ret);
              // echo '{"error":1, "message":"'.__("This template is a predefined template, you can't delete it", "chronosly").'"}';
                die(1);
            }
           $dads = array("dad1","dad2","dad3","dad4","dad5","dad6","dad7","dad8","dad9","dad10","dad11","dad12");
            $removed = unlink($path."css".DIRECTORY_SEPARATOR.$name."css");
            foreach($dads as $tid){
               $removed = unlink($path.$tid.DIRECTORY_SEPARATOR.$name.".php");
                if(!$removed){
                    $ret = array(
                                "error"=> 1,
                                "message" => __("An error ocurred when try to delete this template", "chronosly")
                            );

                    echo json_encode($ret);
                    // echo '{"error":1, "message":"'.__("An error ocurred when try to delete this template", "chronosly").'"}';
                    die(1);
                }
            }

            echo '{"error":0, "message":"'.$name.' '.__("removed", "chronosly").'"}';
            die(1);
        }

        //guardamos template como fichero mediante js
        public function js_save_template(){
            $path = CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR;
            $action = (isset($_REQUEST['task']))?$_REQUEST['task']:"";
            $tid = (isset($_REQUEST['id']))?$_REQUEST['id']:"";
            switch($action){
                case "update":
                    $name = (isset($_REQUEST['name']))?$_REQUEST['name']:"";
                    $html = (isset($_REQUEST['html']))?$_REQUEST['html']:"";
                       if(file_exists($path.$name.".json") and !CHRONOSLY_DEBUG){
                        $ret = array(
                                "error"=> 1,
                                "message" => __("This template is a predefined template, you can't modify it because further template autoupdates and addons upload will be automaticly.", "chronosly")."<br/>".__("Please, if you want to change this template, clone it and modify the copy.", "chronosly")
                            );
                        echo json_encode($ret);
                      // echo '{"error":1, "message":"'.__("This template is a predefined template, you can't modify it because further template autoupdates and addons upload will be automaticly.", "chronosly")."<br/>".__("Please, if you want to change this template, clone it and modify the copy.", "chronosly").'"}';
                        die(1);
                    }
                    $f = fopen($path.$tid.DIRECTORY_SEPARATOR.$name.".php", "w");
                    fwrite( $f , $html);
                    fclose($f);
                    // echo "hola";
                    $this->template_2_html($name, $tid);
                    /*
                     * esto servia para hacer paso a paso el addon template, ahora es automatico para los del marketplace
                     * if(isset($_REQUEST['addon'])){
                      $addons_sets = unserialize(get_option("chronosly_settings_".$_REQUEST['addon']));
                        if(!@in_array($name, $addons_sets["templates_auto_updated"][$tid])){
                            $addons_sets["templates_auto_updated"][$tid][] = $name;
                            update_option("chronosly_settings_".$_REQUEST['addon'], serialize($addons_sets));
                        }
                    }*/
                    echo '{"error":0, "message":"'.$name.' '.__("updated", "chronosly").'"}';
                    die(1);
                    break;
                case "save":
                    $name = (isset($_REQUEST['name']))?$_REQUEST['name']:"";
                    $html = (isset($_REQUEST['html']))?$_REQUEST['html']:"";
                    //si no existe el template generamos todas las vistas
                    if(!in_array($name, $this->get_templates_options(1))) $this->build_all_views($name);
                    $f = fopen($path.$tid.DIRECTORY_SEPARATOR.$name.".php", "w");
                    fwrite( $f , $html);
                    fclose($f);
                    break;
                case "duplicate":
                    $name = (isset($_REQUEST['name']))?$_REQUEST['name']:"";
                    $duplicate = (isset($_REQUEST['html']))?$_REQUEST['html']:"";
                    $this->duplicate_template($duplicate, $name);

                    break;
                case "save-bd":
                    $html = (isset($_REQUEST['html']))?$_REQUEST['html']:"";
                    $temp = (isset($_REQUEST['template']))?$_REQUEST['template']:"";
                    if($temp != "dad11" and $temp != "dad12") $ret = update_post_meta($tid, $temp, $html);
                    else $ret = update_option('chronosly-taxonomy-'.$temp.'_'.$tid, $html);
                    die($ret);
                    break;
                default: ;
            }
        }

        /* esta funcion permitira a los addons definir si un template modificado es igual que su template base, al menos estructuralmente y en la parte de
         codigo que necesita el addon para aádir su codigo */
        public function check_if_modified($template, $vista, $code, $level){
            $base = $this->get_template_vars( CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR.$vista.DIRECTORY_SEPARATOR.$template.".php");
            $new =str_replace(array('\\"', "\\'"), array('"',"'"),$code);
            $new = json_decode(stripslashes($code));
            if(count($base->boxes) > count($new->boxes)) return false;//new is different from base
            for($i = 0; $i < count($base->boxes); ++$i) {
                if($i == $level) return true;
                if($this->check_is_box_modified($base->boxes[$i], $new->boxes[$i])) return false;

            }
            return true;//the skeleton of modified is the same
        }

        public function check_is_box_modified($box1, $box2){
            //funcion que recorreria cada box y cont_box para determinar si un elemento es igual que el padre
        }

        //construye todas las vistas de un template cuando guardamos
        public function build_all_views($name){
            $path = CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR;
            $dads = array("dad1","dad2","dad3","dad4","dad5","dad6","dad7","dad8","dad9","dad10","dad11","dad12");
            foreach($dads as $d){
                $f = fopen($path.$d.DIRECTORY_SEPARATOR.$name.".php", "w");
                fwrite( $f , "");
                fclose($f);
            }

        }

         //construye todas las vistas de un template cuando guardamos
        public function duplicate_template($duplicate, $name){
            $path = CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR;
            $dads = array("dad1","dad2","dad3","dad4","dad5","dad6","dad7","dad8","dad9","dad10","dad11","dad12");
            //copy views
            ini_set('track_errors', 1);
            foreach($dads as $d){
                if(!copy($path.$d.DIRECTORY_SEPARATOR.$duplicate.".php", $path.$d.DIRECTORY_SEPARATOR.$name.".php")) echo "Error cloning template $php_errormsg";
            }
            //copy css
            @copy($path."css".DIRECTORY_SEPARATOR.$duplicate.".css", $path."css".DIRECTORY_SEPARATOR.$name.".css");
            //replace css ch-template_name
            $content = file_get_contents($path."css".DIRECTORY_SEPARATOR.$name.".css");
            $content = str_replace("ch-$duplicate", "ch-$name", $content);
            file_put_contents($path."css".DIRECTORY_SEPARATOR.$name.".css", $content);

        }
        //devuelve si el template es de un fichero o de la bd, si esta en blanco, new event, no devolvemos nada y lo debemos tratar con settings

        public function get_tipo_template($id, $vista){
            // echo "$id, $vista";
            $vars = unserialize(get_option("chronosly-settings"));
            if($vista != "dad11" and $vista != "dad12") $template = @get_post_meta($id, $vista, true);
            else {
                $template = @get_option('chronosly-taxonomy-'.$vista.'_'.$id);
                if($template2 = unserialize($template)) $template = $template2;
                if(count($template)) $template = $template[0];
            }
            if($template) {
                // echo "entra";
                if(strlen($template) < 50 and stripos( $template,"template-")!== FALSE) return str_replace("template-", "", $template);
                else return "chbd";
            }
            return $vars['chronosly_template_default'];

        }

        public function get_tipo_template_base($id, $vista){
            $vars = unserialize(get_option("chronosly-settings"));
            if($vista != "dad11" and $vista != "dad12") $template = @get_post_meta($id, $vista, true);
            else {
                $template = @get_option('chronosly-taxonomy-'.$vista.'_'.$id);
                 $template = unserialize($template);
                if(count($template)) $template = $template[0];
            }
            if($template) {
                if(strlen($template) < 50 and stripos( $template,"template-")!== FALSE) return str_replace("template-", "", $template);
                else  {
                    $content = str_replace(array('\\"', "\\'"), "'", $template);
                    if(!json_decode(stripslashes($content))) {
                        $content =json_decode(stripslashes(str_replace(array('\\"', "\\'"), array('"',"'"),$template)));
                    }
                    return $content->base;
                }
            }
            return $vars['chronosly_template_default'];

        }

        public function get_template($id, $vista){
            if($vista != "dad11" and $vista != "dad12") return get_post_meta($id, $vista, true);
            return get_option('chronosly-taxonomy-'.$vista.'_'.$id);
        }

        //devuelve los nombres de los templates disponibles
        public function get_file_templates($id, $vista, $perfil){
            if ($handle = opendir(CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR.$vista.DIRECTORY_SEPARATOR)) {
               if($perfil == 2 and $this->get_tipo_template($id, $vista)== "chbd") $custom_templates []= __("template edited", "chronosly");//si estamos como promode debemos poner el load template de la bd
                while (false !== ($entry = readdir($handle))) {
                    if($entry != "." and $entry != ".." and !stripos($entry, ".html") )
                        $custom_templates[] = str_replace(array(".php","_"), array("", " "),$entry);

                }

                closedir($handle);
            }
            return $custom_templates;
        }



        public function js_get_tipo_template(){
            $id = (isset($_REQUEST['id']))?$_REQUEST['id']:"";
            $vista = (isset($_REQUEST['view']))?$_REQUEST['view']:"";
            echo $this->get_tipo_template($id ,$vista);
            die();
        }


        //llamamos al render segun parametros de js
        public function js_render_template(){
            //$style = $_REQUEST['style'];//front or back

            $template = (isset($_REQUEST['template']))?$_REQUEST['template']:"";
            $template = str_replace(" ", "_",$template);
            $id = (isset($_REQUEST['id'])?$_REQUEST['id']:1);
            $vista = (isset($_REQUEST['view'])?$_REQUEST['view']:"dad1");
            $perfil = (isset($_REQUEST['perfil'])?$_REQUEST['perfil']:1);
            $style = (isset($_REQUEST['style'])?$_REQUEST['style']:"back");
            if(stripos($style, "|" ) !== FALSE) {
                $st = explode("|", $style);
                $style = $st[0];
                $_REQUEST['addon'] = $st[1];
            }
            ob_start();
            $this->print_template($id, $vista, "", $template, $style);
            $response['template'] = ob_get_clean(); //aqui va el html del template
            ob_start();
            $this->print_template($id, $vista, "",$template, "custom-css"); // aqui el campo extra de css
            $response['css'] = ob_get_clean();
            $selected_template = $this->get_tipo_template($id, $vista);
            $response['template_name'] = $selected_template;
            $response['template_base'] = $this->get_tipo_template_base($id, $vista);
            if($selected_template == "chbd")  $selected_template = "template_edited";
            $response['select'] = $this->build_templates_select($this->get_file_templates($id, $vista,$perfil), $selected_template); //aqui los options del select template, solo util para el cambio de vista
            echo json_encode($response);
            die();
        }

        //construye los options del select template
        public function build_templates_select($options, $select){
            $ret ="";

            foreach($options as $c) {
                $ret .="<option";
                if(str_replace(" ","_",$c) == str_replace(" ","_",$select)) $ret .=" selected='selected' ";
                $ret .= " value='";
                if(trim($c) == "template edited") $ret .="chbd";
                else $ret .= str_replace(" ","_",$c);
                $ret .= "'>$c</option>";

            }
            return $ret;
        }


        //construye los options del default template, tienen que estar en todos los dad para poder escogerlo
            public function get_templates_options($array = 0, $selected="default"){
                $dads = array("dad1","dad2","dad3","dad4","dad5","dad6","dad7","dad8","dad9","dad10","dad11","dad12");
               $templates =array();
                $temp = array();
                foreach($dads as $d) {
                   if(!count($templates)) $templates = $this->get_file_templates("", $d, 1);//rellenamos el array de templates base
                    else $temp = $this->get_file_templates("", $d, 1);
                  //  print_r($templates);echo " --- ";
                   // print_r($temp);echo "<br/>";
                   if(count($temp)) {
                       foreach($templates as $k=>$t){
                            if(is_array($temp) and !in_array($t, $temp)) unset($templates[$k]);
                       }
                   }
                }
                if(!$array)  return $this->build_templates_select($templates, $selected);
                else return $templates;
            }

        //muestra los templates que estamos utilizando en cada evento para tener un control
        public function template_status(){
            //metemos los tipo evento
            $return="";
            $args  = array(
                'posts_per_page'   => -1,
                'numberposts'       => -1,

                'offset'           => 0,
                'category'         => '',
                'orderby'          => 'post_date',
                'order'            => 'DESC',
                'include'          => '',
                'exclude'          => '',
                'meta_key'         => '',
                'meta_value'       => '',
                'post_type'        => 'chronosly',
                'post_mime_type'   => '',
                'post_parent'      => '',
                'post_status'      =>'publish',
                'suppress_filters' => true );
            if ( is_user_logged_in() ) $args["post_status"] = array('publish', 'private');
                $post = get_posts($args);

            $dads = array("dad1","dad2","dad3","dad4","dad5","dad6","dad7","dad8","dad9","dad10","dad11","dad12");
            foreach($post as $p){
                $return[$p->ID] = get_post_meta($p->ID);
                $return[$p->ID]["title"] =$p->post_title;
                foreach($return[$p->ID] as $k => $meta){
                    if(in_array($k, $dads)) $return[$p->ID][$k] = $this->get_tipo_template($p->ID, $k);
                }
                $cats = wp_get_object_terms($p->ID , "chronosly_category");
                foreach($cats as $cat){
                    $return[$p->ID]['cats_vars'][$cat->term_id] = $cat;
                }

            }
            //metemos los tipo organizer
            $args  = array(
                'posts_per_page'   => -1,
                'numberposts'       => -1,

                'offset'           => 0,
                'category'         => '',
                'orderby'          => 'post_date',
                'order'            => 'DESC',
                'include'          => '',
                'exclude'          => '',
                'meta_key'         => '',
                'meta_value'       => '',
                'post_type'        => 'chronosly_organizer',
                'post_mime_type'   => '',
                'post_parent'      => '',
                'post_status'      => 'publish',
                'suppress_filters' => true );
            if ( is_user_logged_in() ) $args["post_status"] = array('publish', 'private');

            $post = get_posts($args);
            $dads = array("dad1","dad2","dad3","dad4","dad5","dad6","dad7","dad8","dad9","dad10","dad11","dad12");
            foreach($post as $p){
                $return[$p->ID] = get_post_meta($p->ID);
                $return[$p->ID]["title"] =$p->post_title;
                foreach($return[$p->ID] as $k => $meta){
                    if(in_array($k, $dads)) $return[$p->ID][$k] = $this->get_tipo_template($p->ID, $k);
                }


            }
            //metemos los tipo place
            $args  = array(
                'posts_per_page'   => -1,
                'numberposts'       => -1,
                'offset'           => 0,
                'category'         => '',
                'orderby'          => 'post_date',
                'order'            => 'DESC',
                'include'          => '',
                'exclude'          => '',
                'meta_key'         => '',
                'meta_value'       => '',
                'post_type'        => 'chronosly_places',
                'post_mime_type'   => '',
                'post_parent'      => '',
                'post_status'      => 'publish',
                'suppress_filters' => true );
            if ( is_user_logged_in() ) $args["post_status"] = array('publish', 'private');

            $post = get_posts($args);
            $dads = array("dad1","dad2","dad3","dad4","dad5","dad6","dad7","dad8","dad9","dad10","dad11","dad12");
            foreach($post as $p){
                $return[$p->ID] = get_post_meta($p->ID);
                $return[$p->ID]["title"] =$p->post_title;
                foreach($return[$p->ID] as $k => $meta){
                    if(in_array($k, $dads)) $return[$p->ID][$k] = $this->get_tipo_template($p->ID, $k);
                }

            }
            //metemos los tipo category
            $post = get_terms( 'chronosly_category' );
            foreach($post as $p){
                $metas["dad11"] = $this->get_tipo_template($p->term_id, "dad11");
                $metas["dad12"] = $this->get_tipo_template($p->term_id, "dad12");
                $return["c".$p->term_id] = $metas;
                $return["c".$p->term_id]["title"] = $p->name;

            }
            //faltan los calendars
            return $return;
        }


        //muestra los options del currency, con la moneda seleccionada
        public function currency_selector($selected){
            /*
             * ₠    8352    ₠   20A0        EURO-CURRENCY SIGN   (see 8364 for euro)
            ₡   8353    ₡   20A1        COLON SIGN
            ₢   8354    ₢   20A2        CRUZEIRO SIGN
            ₣   8355    ₣   20A3        FRENCH FRANC SIGN   (present in WGL4)
            ₤   8356    ₤   20A4        LIRA SIGN   (present in WGL4)   [pound sign is £ (&pound;)]
            ₥   8357    ₥   20A5        MILL SIGN
            ₦   8358    ₦   20A6        NAIRA SIGN
            ₧   8359    ₧   20A7        PESETA SIGN   (present in WGL4)
            ₨   8360    ₨   20A8        RUPEE SIGN
            ₩   8361    ₩   20A9        WON SIGN
            ₪   8362    ₪   20AA        NEW SHEQEL SIGN
            ₫   8363    ₫   20AB        DONG SIGN
            €   8364    €   20AC    &euro;  EURO SIGN   (present in WGL4, ANSI and MacRoman, and in Symbol font)
            ₭   8365    ₭   20AD        KIP SIGN
            ₮   8366    ₮   20AE        TUGRIK SIGN
            ₯   8367    ₯   20AF        DRACHMA SIGN
            ₰   8368    ₰   20B0        GERMAN PENNY SYMBOL
            ₱   8369    ₱   20B1        PESO SIGN
            ₲   8370    ₲   20B2        GUARANI SIGN
            ₳   8371    ₳   20B3        AUSTRAL SIGN
            ₴   8372    ₴   20B4        HRYVNIA SIGN
            ₵   8373    ₵   20B5        CEDI SIGN
            ₶   8374    ₶   20B6        LIVRE TOURNOIS SIGN
            ₷   8375    ₷   20B7        SPESMILO SIGN
            ₸   8376    ₸   20B8        TENGE SIGN
            ₹   8377    ₹   20B9        INDIAN RUPEE SIGN
            The following currency symbols are not in the Currency Symbols range:

            Character
            (decimal)   Decimal Character
                        (hex)   Hex Entity  Name
            $   36  $   0024        DOLLAR SIGN   (present in WGL4, ANSI and MacRoman)
            ¢   162 ¢   00A2    &cent;  CENT SIGN   (present in WGL4, ANSI and MacRoman)
            £   163 £   00A3    &pound; POUND SIGN   (present in WGL4, ANSI and MacRoman)   [lira sign is ₤ (&#8356;)]
            ¤   164 ¤   00A4    &curren;    CURRENCY SIGN   (present in WGL4 and ANSI)
            ¥   165 ¥   00A5    &yen;   YEN SIGN   (present in WGL4, ANSI and MacRoman)
            ƒ   402 ƒ   0192    &fnof;  LATIN SMALL LETTER F WITH HOOK   (known as Florin or Guilder in Symbol font; present in WGL4, ANSI and MacRoman)
            ֏   1423    ֏   058F        ARMENIAN DRAM SIGN
            ৲   2546    ৲   09F2        BENGALI RUPEE MARK
            ৳   2547    ৳   09F3        BENGALI RUPEE SIGN
            ૱   2801    ૱   0AF1        GUJARATI RUPEE SIGN
            ௹   3065    ௹   0BF9        TAMIL RUPEE SIGN
            ฿   3647    ฿   0E3F        THAI CURRENCY SYMBOL BAHT
            ៛   6107    ៛   17DB        KHMER CURRENCY SYMBOL RIEL
            ㍐   13136   ㍐   3350        SQUARE YUAN
            元   20803   元   5143        [Yuan, in China]
            円   20870   円   5186        [Yen]
            圆   22278   圆   5706        [Yen/Yuan variant]
            圎   22286   圎   570E        [Yen/Yuan variant]
            圓   22291   圓   5713        [Yuan, in Hong Kong and Taiwan ]
            圜   22300   圜   571C        [Yen/Yuan variant]
            ꠸   43064   ꠸   A838        NORTH INDIC RUPEE MARK
            원   50896   원   C6D0        [Won]
            ﷼   65020   ﷼   FDFC        RIAL SIGN
            ＄   65284   ＄   FF04        FULLWIDTH DOLLAR SIGN"&#x0024","&#x20AC","&#x00A2","&#x00A3","&#xFF04","&#xFFE1",
            ￠   65504   ￠   FFE0        FULLWIDTH CENT SIGN
            ￡   65505   ￡   FFE1        FULLWIDTH POUND SIGN
            ￥   65509   ￥   FFE5        FULLWIDTH YEN SIGN
            ￦   65510   ￦   FFE6        FULLWIDTH WON SIGN */
            $currency = array("$","&#x20AC;","&#x20A4;","&#xFFE0;","&#x20A1;","&#x20A2;","&#x20A3;","&#x20A5;","&#x20A6;","&#x20A7;","&#x20A8;","&#x20A9;","&#x20AA;",
                "&#x20AB;","&#x20AD;","&#x20AE;","&#x20AF;","&#x20B0;","&#x20B1;","&#x20B2;","&#x20B3;","&#x20B4;",
                "&#x20B5;","&#x20B6;","&#x20B7;","&#x20B8;","&#x20B9;","&#x00A5;","&#x058F;","&#x09F2;","&#x09F3;",
                "&#x0AF1;","&#x0BF9;","&#x0E3F;","&#x17DB;","&#x3350;","&#x5143;","&#x5186;","&#x5706;","&#x570E;","&#x5713;","&#xA838;","&#xC6D0;",
                "&#xFDFC;","&#xFFE5;","&#xFFE6;", "CHF", "Руб"
            );
            $settings = unserialize(get_option("chronosly-settings"));
            $selected = $settings["chronosly_currency"];
            $ret = "";
            foreach($currency as $k=>$c){
                $ret .="<option";

                if(bin2hex(html_entity_decode($c,null,'UTF-8')) == bin2hex($selected)){
                    $ret .=" selected='selected' ";
                }
                $ret .= " value='$c'>$c</option>";
            }
            return $ret;
        }

        //printamos template, encapsulando parametros para el render, en args añadimos argumentso extras como start end time de las repetitions
        public function print_template($id, $vista, $draganddropels="", $template ="", $style="back", $args = array(), $html2=0){
            $vars = $this->settings;
            $data = "";
            $this->vars->pid = $id;
            $this->vars->vista = $vista;
            $this->vars->args = $args;
            $itid = $id;
            if($args["start"] and $args["end"]) $itid .= "_{$args["start"]}_{$args["end"]}";
            if($args["h"] and $args["m"]) $itid .= "_{$args["h"]}_{$args["m"]}";

            //controlamos para hacerlo por html o por json

            $path = CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR;
            if(isset($vars['chronosly_template_default_active']) and $vars['chronosly_template_default_active'] and stripos($_SERVER['HTTP_REFERER'], "chronosly_edit_templates") === FALSE ) $template = $vars['chronosly_template_default'];
            if($_REQUEST["force_template"]) $template = $_REQUEST["force_template"];

            if(!$template) $template = $this->get_tipo_template($id, $vista);
            if (!$template) $template = $vars['chronosly_template_default'];
            if($style=="front" and !$html2 and file_exists($path.$vista.DIRECTORY_SEPARATOR.$template.".html")) {
                // echo "html";
                return $this->print_template_html($id, $vista, $draganddropels, $template, $style, $args, $html2);
            }
            // echo $path.$vista.DIRECTORY_SEPARATOR.$template.".html";

            //si lo tenemos en la cache lo pintamos, si no lo generamos y lo guardamos
            if($style == "front" and $id != 1 and $html = Chronosly_Cache::load_item($itid, $vista)) {
               echo $html;
            }
            else {

                ob_start();

                if (!$template) {
                    //si no hay template en la bd estamos cargando el default
                    $this->render_template($vars['chronosly_template_default'], $style, $vista, "", $draganddropels, $html2);
                } else {

                    if($template == "chbd"){
                        //se trata de un template editado, cargamos los datos del template
                        $data = str_replace(array('\\"', "\\'"), "'", $this->get_template($id, $vista));
                        if(!json_decode(stripslashes($data))) {
                            $data =str_replace(array('\\"', "\\'"), array('"',"'"),$this->get_template($id, $vista));
                        }
                    }

                    $this->render_template($template, $style, $vista, json_decode(stripslashes($data)), $draganddropels, $html2 );

                }
                $cont= str_replace("{{notprev}}", "",ob_get_clean());
                echo $cont;

                if($style == "front") Chronosly_Cache::save_item($itid, $vista, $cont);
            }



        }

        //recibimos el template ,la vista, las variables del template(en caso de ser editado) y las variables de draganddrop
        public function render_template($template, $style = "back", $vista = "", $vars="", $dadcats ="", $html2=0){
            if($template != "chbd"){

                $path = CHRONOSLY_TEMPLATES_PATH;//ruta hacia los templates del user
                $vars = $this->get_template_vars($path.DIRECTORY_SEPARATOR.$vista.DIRECTORY_SEPARATOR.$template.".php");//ruta hacia el dad que toca

            }
            if(($style == "back-addon" or $style == "back-addon-js") and isset($_REQUEST['addon']) and has_filter("chronosly_update_template_".$_REQUEST['addon'])) {
                /*$addons_sets = unserialize(get_option("chronosly_settings_".$_REQUEST['addon']));
                if(!@in_array($template, $addons_sets["templates_auto_updated"][$vista])){
                    $vars = apply_filters("chronosly_update_template_".$_REQUEST['addon'],  $vars, $template, str_replace("dad", "",$vista));
                }*/
            }
            $id = $this->vars->pid;
            $args = $this->vars->args;
            if($vars) $this->vars = $vars;
            if($dadcats) $this->dadcats = $dadcats;
            $this->vars->pid = $id;
            $this->vars->vista = $vista;
            $this->vars->args = $args;


            if(stripos($style,"back") !== FALSE){
                $this->build_admin_template($vista, $template,$style, $html2);
            }
            else if ($style == "front"){

                $this->build_front_template($vista,$template, $html2);

            } else if ($style == "custom-css"){

                echo  urldecode($this->vars->style);

            }

        }

        //retrieve template vars from file
        private function get_template_vars($file){
            $f = @fopen($file, "r");
            $content =str_replace(array('\\"', "\\'"), "'",@fread($f, filesize($file)));
            if(!json_decode(stripslashes($content))) {
                @fclose($f);
                $f = @fopen($file, "r");
                $content =str_replace(array('\\"', "\\'"), array('"',"'"),@fread($f, filesize($file)));
            }

            @fclose($f);
            //no chuta el json decode, hay que mirar

            return json_decode(stripslashes($content));
            //print_r($this->vars);
        }

        //cargamos las variables necesarias para pintar el template, las metas de organizer, place, etc..
        private function get_all_vars($style){

            global $pastformat;
            if(isset($this->vars->args["id"]) and $this->vars->args["id"]) {
                if($fin = stripos($this->vars->args["id"], "_")) $pid = substr($this->vars->args["id"], 0, $fin);
                else $pid = $this->vars->args["id"];
                $this->vars->pid = $pid;
            }
            if($this->vars->vista == "dad11" or $this->vars->vista == "dad12" ){
                $this->vars->post = get_term( $this->vars->pid, "chronosly_category" );
                if(!$this->vars->post) $this->vars->post = get_term( $this->vars->pid, "chronosly_tag" );
                $this->vars->post->post_title = $this->vars->post->name;
                $this->vars->post->post_content = $this->vars->post->description;
               // print_r($this->vars->post);
                $this->vars->metas = unserialize(get_option('chronosly-taxonomy_'.$this->vars->pid));//assing metas vars
                //if($this->vars->metas->["cat-color"]) $global_cat_color =  $this->vars->metas->["cat-color"];
              //  $this->vars->metas['dad11'] = get_option('chronosly-taxonomy-dad11_'.$this->vars->pid);
              //  $this->vars->metas['dad12'] = get_option('chronosly-taxonomy-dad12_'.$this->vars->pid);
                //return;
            }
            else if($this->vars->pid) $post = get_posts("p=".$this->vars->pid."&post_type=any&post_status=publish,private");//assing post vars
            if((!isset($this->vars->post) or !is_object($this->vars->post)) and isset($post[0])) $this->vars->post = $post[0];
            if(!isset($this->vars->pid) and isset($post[0])) $this->vars->pid = $post[0]->ID;

            if($this->vars->vista != "dad11" and $this->vars->vista != "dad12" ) $this->vars->metas = get_post_meta($this->vars->pid);//assing metas vars

            if(isset($this->vars->metas['organizer']) && $this->vars->metas['organizer'][0]){
                foreach($this->vars->metas['organizer'] as $orgs){
                    $orgs = unserialize($orgs);

                    if(isset($orgs) and $orgs != ""){
                        foreach($orgs as $org){
                            if(function_exists("icl_object_id")) $org = icl_object_id($org, "chronosly_organizer");

                            $post = get_posts('post_type=chronosly_organizer&p='.$org);
                            $this->vars->metas['organizer_vars'][] = array("post" =>  $post[0], "metas" => get_post_meta($org));

                        }
                    }
                }
                //ordenamos por order o featured
                @usort($this->vars->metas['organizer_vars'], array("Chronosly_Templates", "ordenar_org"));

            } else if($this->vars->vista == "dad7" or $this->vars->vista == "dad8" ){
                $this->vars->metas['organizer_vars'][] = array("post" =>  (isset($this->vars->post)?$this->vars->post:""), "metas" => $this->vars->metas);
            }

            if(isset($this->vars->metas['places']) && $this->vars->metas['places'][0]){
                foreach($this->vars->metas['places'] as $orgs){
                    $orgs = unserialize($orgs);
                    if(isset($orgs) and $orgs != ""){
                        foreach($orgs as $org){
                            if(function_exists("icl_object_id")) $org = icl_object_id($org, "chronosly_places");
                            $post = get_posts('post_type=chronosly_places&p='.$org);
                            $this->vars->metas['places_vars'][] = array("post" =>  $post[0], "metas" => get_post_meta($org));
                        }
                    }
                }
                //ordenamos por order o featured
                @usort($this->vars->metas['places_vars'], array("Chronosly_Templates", "ordenar_org"));
            }else if($this->vars->vista == "dad9" or $this->vars->vista == "dad10" ){
                $this->vars->metas['places_vars'][] = array("post" =>  (isset($this->vars->post)?$this->vars->post:""), "metas" => $this->vars->metas);
            }

            if($this->vars->vista != "dad11" and $this->vars->vista != "dad12" ){
                $cats = wp_get_object_terms( $this->vars->pid , "chronosly_category" ,array('order' => 'DESC'));

                foreach($cats as $cat){
                    $this->vars->metas['cats_vars'][$cat->term_id] = $cat;
                    $this->vars->metas['cats_vars'][$cat->term_id]->metas = unserialize(get_option('chronosly-taxonomy_'.$cat->term_id));//assing metas vars

                }
            } else {
                $this->vars->metas['cats_vars'][0] = $this->vars->post;
                $this->vars->metas['cats_vars'][0]->metas = $this->vars->metas;//assing metas vars
            }

            //ordenamos segun orden
            if(isset($this->vars->metas['cats_vars'])) usort($this->vars->metas['cats_vars'], array("Chronosly_Templates", "ordenar_cats"));

            if($this->vars->vista != "dad11" and $this->vars->vista != "dad12" ){

                $tags = wp_get_object_terms($this->vars->pid, "chronosly_tag");
                foreach($tags as $tag){
                    $this->vars->metas['tags_vars'][$tag->term_id] = $tag;
                    //faltan los metas
                }

                $vars = "";
                if(isset($this->vars->metas['tickets'])) $vars = json_decode($this->vars->metas['tickets'][0]);

               if(isset($vars->tickets) and count($vars->tickets)){
                   for($i = 1; $i < count($vars->tickets);++$i){
                       $tickets = $vars->tickets[$i];
                       $ticket = array();
                       foreach($tickets as $t) $ticket[$t->name] = $t->value;
                       $this->vars->metas['tickets_vars'][$i] =  $ticket;// array("name" =>$ticket["t,"price" => array("value"=>$ticket[1]->value ),"capacity" =>$ticket[2]->value,"min" =>$ticket[3]->value,"max" =>$ticket[4]->value,"start-time" =>array("value" => $ticket[5]->value),"end-time" =>array("value" => $ticket[6]->value),"link" =>$ticket[7]->value, "notes" =>$ticket[8]->value);

                   }

               }

                $this->vars->link = get_post_permalink($this->vars->pid);

            }


            //modificamos las vars que pasamos al template como parametro
            if(isset($this->vars->args)){

                foreach($this->vars->args as $k => $v){
                    
                    switch($k){
                        case "id":
                            $this->vars->pid = $v;
                            if(!isset($this->vars->post)) $this->vars->post = new stdClass();
                             $this->vars->post->ID = $v;
                            break;
                        case "start":
                        // echo $v."<br>";
                            $this->vars->metas['ev-from'][0] = date("Y-m-d",$v);
                            //echo date("Y-m-d",$v)." ".$v;
                            $this->vars->metas['repeat'] = $v;
                            if(substr($this->vars->link, -1) != "/") $this->vars->link .="/";
                            $this->vars->link .= (get_option('permalink_structure')?"repeat_$v":"&repeat=$v");
                            break;
                        case "end":
                            $this->vars->metas['ev-to'][0] = date("Y-m-d",$v);
                            $this->vars->metas['repeat'] .= "_$v";
                            $this->vars->link .=  "_$v";

                            break;
                        case "h":
                            $this->vars->metas['ev-from-h'][0] = $v;
                            $this->vars->metas['repeat'] .= "_$v";
                            $this->vars->link .=  "_$v";

                            break;
                         case "m":
                            $this->vars->metas['ev-from-m'][0] = $v;
                            $this->vars->metas['repeat'] .= "_$v";
                            $this->vars->link .=  "_$v";

                            break;
                        case "eh":
                            $this->vars->metas['ev-to-h'][0] = $v;


                            break;
                         case "em":
                            $this->vars->metas['ev-to-m'][0] = $v;


                            break;

                    }

                }
            }

            //cargariamos el hook de data_field

            $this->set_default_text($style);


        }

        //ordena las cats por el atributo order
        private static function ordenar_cats($a, $b){
            if(!$a->metas) return 1;
            else if(!$b->metas) return -1;
            else if ($a->metas['order'] <  $b->metas['order']) return -1;
            else if($a->metas['order'] > $b->metas['order']) return 1;
            else if(isset($b->metas['featured']) and $b->metas['featured']) return 1;
            return -1;
        }

        //ordena las organizers y places por el atributo order
        private static function ordenar_org($a, $b){

            if(!$a['metas']) return 1;
            if(!$b['metas']) return -1;
            if($a['metas']['order'][0] == "") $a['metas']['order'][0] = 0;
            if($b['metas']['order'][0] == "") $b['metas']['order'][0] = 0;
            if ($a['metas']['order'][0] <  $b['metas']['order'][0]) return -1;
            if($a['metas']['order'][0] > $b['metas']['order'][0]) return 1;
            if($b['metas']['featured'][0] != "") return 1;
            return -1;
        }

        //ponemos los datos por defecto en caso que no tengamos datos
        public function set_default_text($style){
           // if($style == "back" or $style == "back-addon" or $style == "back-js"){
                //echo "<pre>";print_r($this->vars);
                if(!isset($this->vars->post)) $this->vars->post = new stdClass();
                if(!isset($this->vars->post->post_title) or !$this->vars->post->post_title) @$this->vars->post->post_title = "<span class='lorem'></span>Lorem ipsum";
                if(!isset($this->vars->post->post_content) or !$this->vars->post->post_content) $this->vars->post->post_content = "<span class='lorem'></span><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ultrices, ante commodo elementum posuere, velit justo fermentum enim, placerat tincidunt massa leo convallis purus. Praesent cursus arcu non tortor iaculis, eleifend lacinia enim euismod. Etiam consequat porttitor sapien, vitae feugiat nunc accumsan at. Mauris ultrices pretium lacus, vitae dapibus enim pharetra ut. Curabitur gravida vel lorem quis sollicitudin. Fusce commodo, mi et bibendum elementum, eros enim dapibus arcu, gravida rhoncus felis nulla ut eros. Suspendisse consequat ligula rhoncus erat gravida, et mollis quam placerat. Morbi in diam vestibulum, tempus ante molestie, pharetra nibh. Etiam nulla risus, imperdiet eu tincidunt a, hendrerit a elit. Nullam ut tincidunt augue. Aenean vehicula nulla sed ipsum bibendum, non congue risus mollis. Etiam dolor diam, semper nec sem et, convallis gravida urna.</p><p>Mauris porttitor, diam gravida dignissim iaculis, tellus dui commodo lectus, ac posuere metus odio nec lectus. Vestibulum scelerisque lorem imperdiet felis ornare, vitae volutpat erat aliquet. Fusce consequat eros sit amet lorem imperdiet faucibus. Vivamus commodo tempus varius. Etiam sodales sed enim ut pretium. Proin at lorem eleifend, euismod tellus eu, facilisis nunc. Fusce accumsan eget erat non commodo. Ut laoreet cursus risus, ac venenatis ipsum congue id. In elementum facilisis leo sed mattis. Curabitur in mauris tortor. Donec felis sapien, porta et pulvinar nec, gravida in purus. Aliquam tempus aliquet erat id interdum.</p><p>Fusce arcu sapien, laoreet ac dolor ac, rutrum lobortis purus. Sed mattis aliquam dignissim. Integer condimentum enim non orci auctor, quis dapibus velit pharetra. Pellentesque quis pulvinar quam, eu vehicula eros. Proin odio turpis, viverra vel odio et, malesuada ultricies lorem. Aliquam erat volutpat. Integer in varius enim. Donec sed aliquet purus, non blandit sem. In in tempus ipsum, sed fermentum libero. Vivamus luctus ligula vitae velit hendrerit consectetur. In imperdiet odio nec sem lacinia, nec tincidunt odio dignissim.</p>";
                if(!isset($this->vars->post->post_excerpt) or !$this->vars->post->post_excerpt) $this->vars->post->post_excerpt = "<span class='lorem'></span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ultrices, ante commodo elementum posuere, velit justo fermentum enim, placerat tincidunt massa leo convallis purus. Praesent cursus arcu non tortor iaculis, eleifend lacinia enim euismod. Etiam consequat porttitor sapien, vitae feugiat nunc accumsan at. Mauris ultrices pretium lacus, vitae dapibus enim pharetra ut. Curabitur gravida vel lorem quis sollicitudin. Fusce commodo, mi et bibendum elementum, eros enim dapibus arcu, gravida rhoncus felis nulla ut eros. Suspendisse consequat ligula rhoncus erat gravida, et mollis quam placerat. Morbi in diam vestibulum, tempus ante molestie, pharetra nibh. Etiam nulla risus, imperdiet eu tincidunt a, hendrerit a elit. Nullam ut tincidunt augue. Aenean vehicula nulla sed ipsum bibendum, non congue risus mollis. Etiam dolor diam, semper nec sem et, convallis gravida urna.";
                if(!isset($this->vars->metas)) $this->vars->metas = array();
                if(!isset($this->vars->metas["cats_vars"]) or isset($this->vars->metas["cats_vars"][0]->errors)) {
                    $this->vars->metas["cats_vars"] = array();
                    $this->vars->metas["cats_vars"][0] = new stdClass();
                    $this->vars->metas["cats_vars"][0]->term_id = 1;
                    $this->vars->metas["cats_vars"][0]->name = "<span class='lorem'></span>Lorem";
                    $this->vars->metas["cats_vars"][0]->description = "<span class='lorem'></span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ultrices, ante commodo elementum posuere, velit justo fermentum enim, placerat tincidunt massa leo convallis purus. Praesent cursus arcu non tortor iaculis, eleifend lacinia enim euismod. Etiam consequat porttitor sapien, vitae feugiat nunc accumsan at. Mauris ultrices pretium lacus, vitae dapibus enim pharetra ut. Curabitur gravida vel lorem quis sollicitudin. Fusce commodo, mi et bibendum elementum, eros enim dapibus arcu, gravida rhoncus felis nulla ut eros. Suspendisse consequat ligula rhoncus erat gravida, et mollis quam placerat. Morbi in diam vestibulum, tempus ante molestie, pharetra nibh. Etiam nulla risus, imperdiet eu tincidunt a, hendrerit a elit. Nullam ut tincidunt augue. Aenean vehicula nulla sed ipsum bibendum, non congue risus mollis. Etiam dolor diam, semper nec sem et, convallis gravida urna.";
                    $this->vars->metas["cats_vars"][0]->metas["featured"] = 0;
                    $this->vars->metas["cats_vars"][0]->metas["order"] = 0;
                    $this->vars->metas["cats_vars"][0]->metas["cat-color"] = $this->settings["chronosly_category_color"];
                    $this->vars->metas["cats_vars"][1] = new stdClass();
                    $this->vars->metas["cats_vars"][1]->term_id = 1;
                    $this->vars->metas["cats_vars"][1]->name = "Ipsum";
                    $this->vars->metas["cats_vars"][1]->metas["featured"] = 0;
                    $this->vars->metas["cats_vars"][1]->metas["order"] = 1;
                    $this->vars->metas["cats_vars"][1]->metas["cat-color"] = $this->settings["chronosly_category_color"];
                }
                if(!isset($this->vars->metas["tags_vars"])){
                    $this->vars->metas["tags_vars"] = array();
                    $this->vars->metas["tags_vars"][0] = new stdClass();
                    $this->vars->metas["tags_vars"][0]->term_id = 1;
                    $this->vars->metas["tags_vars"][0]->name = "<span class='lorem'></span>Lorem";
                    $this->vars->metas["tags_vars"][1] = new stdClass();
                    $this->vars->metas["tags_vars"][1]->term_id = 1;
                    $this->vars->metas["tags_vars"][1]->name = "Ipsum";
                }

                if(!isset($this->vars->metas["organizer_vars"]) or !isset($this->vars->metas["organizer_vars"][0]["post"]) or !isset($this->vars->metas["organizer_vars"][0]["post"]->ID)){
                    $this->vars->metas["organizer_vars"] = array();
                    $this->vars->metas["organizer_vars"][0]["post"] = new stdClass();
                    $this->vars->metas["organizer_vars"][0]["post"]->ID = 1;
                }
                if(!isset($this->vars->metas["organizer_vars"][0]["post"]->post_title) or !$this->vars->metas["organizer_vars"][0]["post"]->post_title) $this->vars->metas["organizer_vars"][0]["post"]->post_title = "<span class='lorem'></span>Organizer Lorem ipsum";
                if(!isset($this->vars->metas["organizer_vars"][0]["post"]->post_content) or !$this->vars->metas["organizer_vars"][0]["post"]->post_content) $this->vars->metas["organizer_vars"][0]["post"]->post_content = "<span class='lorem'></span><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ultrices, ante commodo elementum posuere, velit justo fermentum enim, placerat tincidunt massa leo convallis purus. Praesent cursus arcu non tortor iaculis, eleifend lacinia enim euismod. Etiam consequat porttitor sapien, vitae feugiat nunc accumsan at. Mauris ultrices pretium lacus, vitae dapibus enim pharetra ut. Curabitur gravida vel lorem quis sollicitudin. Fusce commodo, mi et bibendum elementum, eros enim dapibus arcu, gravida rhoncus felis nulla ut eros. Suspendisse consequat ligula rhoncus erat gravida, et mollis quam placerat. Morbi in diam vestibulum, tempus ante molestie, pharetra nibh. Etiam nulla risus, imperdiet eu tincidunt a, hendrerit a elit. Nullam ut tincidunt augue. Aenean vehicula nulla sed ipsum bibendum, non congue risus mollis. Etiam dolor diam, semper nec sem et, convallis gravida urna.</p><p>Mauris porttitor, diam gravida dignissim iaculis, tellus dui commodo lectus, ac posuere metus odio nec lectus. Vestibulum scelerisque lorem imperdiet felis ornare, vitae volutpat erat aliquet. Fusce consequat eros sit amet lorem imperdiet faucibus. Vivamus commodo tempus varius. Etiam sodales sed enim ut pretium. Proin at lorem eleifend, euismod tellus eu, facilisis nunc. Fusce accumsan eget erat non commodo. Ut laoreet cursus risus, ac venenatis ipsum congue id. In elementum facilisis leo sed mattis. Curabitur in mauris tortor. Donec felis sapien, porta et pulvinar nec, gravida in purus. Aliquam tempus aliquet erat id interdum.</p><p>Fusce arcu sapien, laoreet ac dolor ac, rutrum lobortis purus. Sed mattis aliquam dignissim. Integer condimentum enim non orci auctor, quis dapibus velit pharetra. Pellentesque quis pulvinar quam, eu vehicula eros. Proin odio turpis, viverra vel odio et, malesuada ultricies lorem. Aliquam erat volutpat. Integer in varius enim. Donec sed aliquet purus, non blandit sem. In in tempus ipsum, sed fermentum libero. Vivamus luctus ligula vitae velit hendrerit consectetur. In imperdiet odio nec sem lacinia, nec tincidunt odio dignissim.</p>";
                if(!isset($this->vars->metas["organizer_vars"][0]["post"]->post_excerpt) or !$this->vars->metas["organizer_vars"][0]["post"]->post_excerpt) $this->vars->metas["organizer_vars"][0]["post"]->post_excerpt = "<span class='lorem'></span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ultrices, ante commodo elementum posuere, velit justo fermentum enim, placerat tincidunt massa leo convallis purus. Praesent cursus arcu non tortor iaculis, eleifend lacinia enim euismod. Etiam consequat porttitor sapien, vitae feugiat nunc accumsan at. Mauris ultrices pretium lacus, vitae dapibus enim pharetra ut. Curabitur gravida vel lorem quis sollicitudin. Fusce commodo, mi et bibendum elementum, eros enim dapibus arcu, gravida rhoncus felis nulla ut eros. Suspendisse consequat ligula rhoncus erat gravida, et mollis quam placerat. Morbi in diam vestibulum, tempus ante molestie, pharetra nibh. Etiam nulla risus, imperdiet eu tincidunt a, hendrerit a elit. Nullam ut tincidunt augue. Aenean vehicula nulla sed ipsum bibendum, non congue risus mollis. Etiam dolor diam, semper nec sem et, convallis gravida urna.";
                if(!isset($this->vars->metas["organizer_vars"][0]["post"]->post_excerpt) or !$this->vars->metas["organizer_vars"][0]["post"]->post_excerpt) $this->vars->metas["organizer_vars"][0]["post"]->post_excerpt = "<span class='lorem'></span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ultrices, ante commodo elementum posuere, velit justo fermentum enim, placerat tincidunt massa leo convallis purus. Praesent cursus arcu non tortor iaculis, eleifend lacinia enim euismod. Etiam consequat porttitor sapien, vitae feugiat nunc accumsan at. Mauris ultrices pretium lacus, vitae dapibus enim pharetra ut. Curabitur gravida vel lorem quis sollicitudin. Fusce commodo, mi et bibendum elementum, eros enim dapibus arcu, gravida rhoncus felis nulla ut eros. Suspendisse consequat ligula rhoncus erat gravida, et mollis quam placerat. Morbi in diam vestibulum, tempus ante molestie, pharetra nibh. Etiam nulla risus, imperdiet eu tincidunt a, hendrerit a elit. Nullam ut tincidunt augue. Aenean vehicula nulla sed ipsum bibendum, non congue risus mollis. Etiam dolor diam, semper nec sem et, convallis gravida urna.";
                if(!isset($this->vars->metas["organizer_vars"][0]["metas"]["evo_mail"][0]) or !$this->vars->metas["organizer_vars"][0]["metas"]["evo_mail"][0]) $this->vars->metas["organizer_vars"][0]["metas"]["evo_mail"][0] = "lorem@ipsum.com";
                if(!isset($this->vars->metas["organizer_vars"][0]["metas"]["evo_phone"][0]) or !$this->vars->metas["organizer_vars"][0]["metas"]["evo_phone"][0]) $this->vars->metas["organizer_vars"][0]["metas"]["evo_phone"][0] = "<span class='lorem'></span>+123-456-789";
                if(!isset($this->vars->metas["organizer_vars"][0]["metas"]["evo_web"][0]) or !$this->vars->metas["organizer_vars"][0]["metas"]["evo_web"][0]) $this->vars->metas["organizer_vars"][0]["metas"]["evo_web"][0] = "loremipsum.com";

                if(!isset($this->vars->metas["places_vars"]) or !isset($this->vars->metas["places_vars"][0]["post"]) or !isset($this->vars->metas["places_vars"][0]["post"]->ID)){
                    $this->vars->metas["places_vars"] = array();
                    $this->vars->metas["places_vars"][0]["post"] = new stdClass();
                    $this->vars->metas["places_vars"][0]["post"]->ID = 1;
                }
                if(!isset($this->vars->metas["places_vars"][0]["post"]->post_title) or !$this->vars->metas["places_vars"][0]["post"]->post_title) $this->vars->metas["places_vars"][0]["post"]->post_title = "<span class='lorem'></span>Place Lorem ipsum";
                if(!isset($this->vars->metas["places_vars"][0]["post"]->post_content) or !$this->vars->metas["places_vars"][0]["post"]->post_content) $this->vars->metas["places_vars"][0]["post"]->post_content = "<span class='lorem'></span><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ultrices, ante commodo elementum posuere, velit justo fermentum enim, placerat tincidunt massa leo convallis purus. Praesent cursus arcu non tortor iaculis, eleifend lacinia enim euismod. Etiam consequat porttitor sapien, vitae feugiat nunc accumsan at. Mauris ultrices pretium lacus, vitae dapibus enim pharetra ut. Curabitur gravida vel lorem quis sollicitudin. Fusce commodo, mi et bibendum elementum, eros enim dapibus arcu, gravida rhoncus felis nulla ut eros. Suspendisse consequat ligula rhoncus erat gravida, et mollis quam placerat. Morbi in diam vestibulum, tempus ante molestie, pharetra nibh. Etiam nulla risus, imperdiet eu tincidunt a, hendrerit a elit. Nullam ut tincidunt augue. Aenean vehicula nulla sed ipsum bibendum, non congue risus mollis. Etiam dolor diam, semper nec sem et, convallis gravida urna.</p><p>Mauris porttitor, diam gravida dignissim iaculis, tellus dui commodo lectus, ac posuere metus odio nec lectus. Vestibulum scelerisque lorem imperdiet felis ornare, vitae volutpat erat aliquet. Fusce consequat eros sit amet lorem imperdiet faucibus. Vivamus commodo tempus varius. Etiam sodales sed enim ut pretium. Proin at lorem eleifend, euismod tellus eu, facilisis nunc. Fusce accumsan eget erat non commodo. Ut laoreet cursus risus, ac venenatis ipsum congue id. In elementum facilisis leo sed mattis. Curabitur in mauris tortor. Donec felis sapien, porta et pulvinar nec, gravida in purus. Aliquam tempus aliquet erat id interdum.</p><p>Fusce arcu sapien, laoreet ac dolor ac, rutrum lobortis purus. Sed mattis aliquam dignissim. Integer condimentum enim non orci auctor, quis dapibus velit pharetra. Pellentesque quis pulvinar quam, eu vehicula eros. Proin odio turpis, viverra vel odio et, malesuada ultricies lorem. Aliquam erat volutpat. Integer in varius enim. Donec sed aliquet purus, non blandit sem. In in tempus ipsum, sed fermentum libero. Vivamus luctus ligula vitae velit hendrerit consectetur. In imperdiet odio nec sem lacinia, nec tincidunt odio dignissim.</p>";
                if(!isset($this->vars->metas["places_vars"][0]["post"]->post_excerpt) or !$this->vars->metas["places_vars"][0]["post"]->post_excerpt) $this->vars->metas["places_vars"][0]["post"]->post_excerpt = "<span class='lorem'></span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ultrices, ante commodo elementum posuere, velit justo fermentum enim, placerat tincidunt massa leo convallis purus. Praesent cursus arcu non tortor iaculis, eleifend lacinia enim euismod. Etiam consequat porttitor sapien, vitae feugiat nunc accumsan at. Mauris ultrices pretium lacus, vitae dapibus enim pharetra ut. Curabitur gravida vel lorem quis sollicitudin. Fusce commodo, mi et bibendum elementum, eros enim dapibus arcu, gravida rhoncus felis nulla ut eros. Suspendisse consequat ligula rhoncus erat gravida, et mollis quam placerat. Morbi in diam vestibulum, tempus ante molestie, pharetra nibh. Etiam nulla risus, imperdiet eu tincidunt a, hendrerit a elit. Nullam ut tincidunt augue. Aenean vehicula nulla sed ipsum bibendum, non congue risus mollis. Etiam dolor diam, semper nec sem et, convallis gravida urna.";
                if(!isset($this->vars->metas["places_vars"][0]["metas"]["evp_mail"][0]) or !$this->vars->metas["places_vars"][0]["metas"]["evp_mail"][0]) $this->vars->metas["places_vars"][0]["metas"]["evp_mail"][0] = "lorem@ipsum.com";
                 if(!isset($this->vars->metas["places_vars"][0]["metas"]["evp_phone"][0]) or !$this->vars->metas["places_vars"][0]["metas"]["evp_phone"][0]) $this->vars->metas["places_vars"][0]["metas"]["evp_phone"][0] = "<span class='lorem'></span>+123-456-789";
                 if(!isset($this->vars->metas["places_vars"][0]["metas"]["evp_web"][0]) or !$this->vars->metas["places_vars"][0]["metas"]["evp_web"][0])  $this->vars->metas["places_vars"][0]["metas"]["evp_web"][0] = "loremipsum.com";
                if(!isset($this->vars->metas["places_vars"][0]["metas"]["evp_dir"][0]) or !$this->vars->metas["places_vars"][0]["metas"]["evp_dir"][0]) $this->vars->metas["places_vars"][0]["metas"]["evp_dir"][0] = "<span class='lorem'></span>2130 Fulton St";
                if(!isset($this->vars->metas["places_vars"][0]["metas"]["evp_city"][0]) or !$this->vars->metas["places_vars"][0]["metas"]["evp_city"][0]) $this->vars->metas["places_vars"][0]["metas"]["evp_city"][0] = "<span class='lorem'></span>San Francisco";
                if(!isset($this->vars->metas["places_vars"][0]["metas"]["evp_country"][0]) or !$this->vars->metas["places_vars"][0]["metas"]["evp_country"][0]) $this->vars->metas["places_vars"][0]["metas"]["evp_country"][0] = "<span class='lorem'></span>USA";
                if(!isset($this->vars->metas["places_vars"][0]["metas"]["evp_state"][0]) or !$this->vars->metas["places_vars"][0]["metas"]["evp_state"][0]) $this->vars->metas["places_vars"][0]["metas"]["evp_state"][0] = "<span class='lorem'></span>California";
                 if(!isset($this->vars->metas["places_vars"][0]["metas"]["evp_pc"][0]) or !$this->vars->metas["places_vars"][0]["metas"]["evp_pc"][0])  $this->vars->metas["places_vars"][0]["metas"]["evp_pc"][0] = "<span class='lorem'></span>94117";


                if(!isset($this->vars->metas["tickets_vars"]) or !$this->vars->metas["tickets_vars"][1]){
                    $this->vars->metas["tickets_vars"] = array();
                    $this->vars->metas["tickets_vars"][1]["title"] = "<span class='lorem'></span>Ticket lorem ipsum";
                    $this->vars->metas["tickets_vars"][1]["price"] = "<span class='lorem'></span>49";
                    $this->vars->metas["tickets_vars"][1]["sale-price"] = "<span class='lorem'></span>39";
                    $this->vars->metas["tickets_vars"][1]["capacity"] = "<span class='lorem'></span>100";
                    // $this->vars->metas["tickets_vars"][1]["min-user"] = "<span class='lorem'></span>1";
                    // $this->vars->metas["tickets_vars"][1]["max-user"] = "<span class='lorem'></span>5";
                    // $this->vars->metas["tickets_vars"][1]["start-time"] = "<span class='lorem'></span>10-05-2014";
                    // $this->vars->metas["tickets_vars"][1]["end-time"] = "<span class='lorem'></span>11-06-2014";
                    $this->vars->metas["tickets_vars"][1]["link"] = "<span class='lorem'></span>link.com";
                    $this->vars->metas["tickets_vars"][1]["soldout"] = "";
                    $this->vars->metas["tickets_vars"][1]["sale"] = "";
                    $this->vars->metas["tickets_vars"][1]["notes"] = "<span class='lorem'></span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ultrices, ante commodo elementum posuere, velit justo fermentum enim, placerat tincidunt massa leo convallis purus. Praesent cursus arcu non tortor iaculis, eleifend lacinia enim euismod. Etiam consequat porttitor sapien, vitae feugiat nunc accumsan at. Mauris ultrices pretium lacus, vitae dapibus enim pharetra ut. Curabitur gravida vel lorem quis sollicitudin. Fusce commodo, mi et bibendum elementum, eros enim dapibus arcu, gravida rhoncus felis nulla ut eros. Suspendisse consequat ligula rhoncus erat gravida, et mollis quam placerat. Morbi in diam vestibulum, tempus ante molestie, pharetra nibh. Etiam nulla risus, imperdiet eu tincidunt a, hendrerit a elit. Nullam ut tincidunt augue. Aenean vehicula nulla sed ipsum bibendum, non congue risus mollis. Etiam dolor diam, semper nec sem et, convallis gravida urna.";

                }
                if(!isset($this->vars->metas["ev-from"])) $this->vars->metas["ev-from"] = array();
                if(!isset($this->vars->metas["ev-from"][0]) or !$this->vars->metas["ev-from"][0]) $this->vars->metas["ev-from"][0] = "<span class='lorem'></span>20-10-2015";
                if(!isset($this->vars->metas["ev-to"][0]) or !$this->vars->metas["ev-to"][0]) $this->vars->metas["ev-to"][0] = "<span class='lorem'></span>21-10-2015";
                if(!isset($this->vars->metas["ev-from-h"][0]) or !$this->vars->metas["ev-from-h"][0]) $this->vars->metas["ev-from-h"][0] = "<span class='lorem'></span>0";
                if(!isset($this->vars->metas["ev-from-m"][0]) or !$this->vars->metas["ev-from-m"][0]) $this->vars->metas["ev-from-m"][0] = "<span class='lorem'></span>0";
                if(!isset($this->vars->metas["ev-to-h"][0]) or !$this->vars->metas["ev-to-h"][0]) $this->vars->metas["ev-to-h"][0] = "<span class='lorem'></span>23";
                if(!isset($this->vars->metas["ev-to-m"][0]) or !$this->vars->metas["ev-to-m"][0]) $this->vars->metas["ev-to-m"][0] = "<span class='lorem'></span>59";
           // }
            //cargariamos el hook de data fields default
            //echo "<pre>";print_r($this->vars);
            if($style == "back" or $style == "back-addon" or $style == "back-js" ){

                echo "<div class='chronosly-defaults' style='display:none;'>";

                echo "<div id='chronosly-pid'>".$this->vars->pid."</div>";
                echo "<div id='chronosly-title'>".$this->vars->post->post_title."</div>";
                echo "<div id='chronosly-content' >".$this->vars->post->post_content."</div>";
                echo "<div id='chronosly-excerpt' >".$this->vars->post->post_excerpt."</div>";
                $i = 0;
                echo "<div id='chronosly-category'>";
                foreach($this->vars->metas["cats_vars"] as $cat){
                    $color = $cat->metas["cat-color"];
                    if(!$color) $color = $this->settings["chronosly_category_color"];
                    echo "<div featured='".$cat->metas["featured"]."'  color='".$color."' class='".$cat->term_id."'>".$cat->name."</div>";
                    ++$i;
                }
                echo "</div>";
                $i = 0;
                echo "<div id='chronosly-tag'>";

                foreach($this->vars->metas["tags_vars"] as $tag){
                    echo "<div class='".$tag->term_id."'>".$tag->name."</div>";
                    ++$i;
                }
                echo "</div>";
                $i = 0;
                echo "<div id='chronosly-organizer'>";

                foreach($this->vars->metas["organizer_vars"] as $org){
                    echo "<div id='organizer-id$i'>".$org["post"]->ID."</div>";
                    echo "<div id='organizer-name$i'>".$org["post"]->post_title."</div>";
                    echo "<div id='organizer-description$i' >".$org["post"]->post_content."</div>";
                    echo "<div id='organizer-excerpt$i' >".$org["post"]->post_excerpt."</div>";
                    echo "<div id='organizer-mail$i'>".$org["metas"]["evo_mail"][0]."</div>";
                    echo "<div id='organizer-phone$i'>".$org["metas"]["evo_phone"][0]."</div>";
                    echo "<div id='organizer-web$i'>".$org["metas"]["evo_web"][0]."</div>";
                    if(wp_get_attachment_url( get_post_thumbnail_id($org["post"]->ID) )) echo "<div id='organizer-thumb$i'>".wp_get_attachment_url( get_post_thumbnail_id($org["post"]->ID) )."</div>";
                    else echo "<div id='organizer-thumb$i'>".CHRONOSLY_URL."css/img/noimg.jpg</div>";
                    ++$i;
                }
                echo "</div>";
                $i = 0;
                echo "<div id='chronosly-places'>";
                foreach($this->vars->metas["places_vars"] as $org){
                    echo "<div id='place-id$i'>".$org["post"]->ID."</div>";
                    echo "<div id='place-name$i'>".$org["post"]->post_title."</div>";
                    echo "<div id='place-description$i' >".$org["post"]->post_content."'</div>";
                    echo "<div id='place-excerpt$i' >".$org["post"]->post_excerpt."</div>";
                    echo "<div id='place-mail$i'>".$org["metas"]["evp_mail"][0]."</div>";
                    echo "<div id='place-phone$i'>".$org["metas"]["evp_phone"][0]."</div>";
                    echo "<div id='place-web$i'>".$org["metas"]["evp_web"][0]."</div>";
                    echo "<div id='place-dir$i'>".$org["metas"]["evp_dir"][0]."</div>";
                    echo "<div id='place-city$i'>".$org["metas"]["evp_city"][0]."</div>";
                    echo "<div id='place-country$i'>".$org["metas"]["evp_country"][0]."</div>";
                    echo "<div id='place-state$i'>".$org["metas"]["evp_state"][0]."</div>";
                    echo "<div id='place-pc$i'>".$org["metas"]["evp_pc"][0]."</div>";
                    if(wp_get_attachment_url( get_post_thumbnail_id($org["post"]->ID) )) echo "<div id='place-thumb$i'>".wp_get_attachment_url( get_post_thumbnail_id($org["post"]->ID) )."</div>";
                    else echo "<div id='place-thumb$i'>".CHRONOSLY_URL."css/img/noimg.jpg</div>";
                    ++$i;
                }
                echo "</div>";
                $i = 1;
                foreach($this->vars->metas["tickets_vars"] as $tik){

                    echo "<div id='chronosly-ticket-name$i'>".$tik["title"]."</div>";
                    echo "<div id='chronosly-ticket-price$i'>".$tik["price"]."</div>";
                    echo "<div id='chronosly-ticket-capacity$i'>".$tik["capacity"]."</div>";
                    echo "<div id='chronosly-ticket-min$i'>".$tik["min-user"]."</div>";
                    echo "<div id='chronosly-ticket-max$i'>".$tik["max-user"]."</div>";
                    echo "<div id='chronosly-ticket-start-time$i'>".$tik["start-time"]."</div>";
                    echo "<div id='chronosly-ticket-endtime$i'>".$tik["end-time"]."</div>";
                    echo "<div id='chronosly-ticket-link$i'>".$tik["link"]."</div>";
                    echo "<div id='chronosly-ticket-notes$i' >".$tik["notes"]."</div>";
                    echo "<div id='chronosly-ticket-soldout$i' >".$tik["soldout"]."</div>";
                    ++$i;

                }
                $lorem = 0;
                $init = strtotime(str_replace("<span class='lorem'></span>", "", $this->vars->metas["ev-from"][0]." ".$this->vars->metas["ev-from-h"][0].":".$this->vars->metas["ev-from-m"][0], $lorem));
                if($lorem) $init .= "<span class='lorem'></span>";
                echo "<div id='chronosly-start-date'>".$init."</div>";
                $lorem = 0;
                $end = strtotime(str_replace("<span class='lorem'></span>", "",$this->vars->metas["ev-to"][0]." ".$this->vars->metas["ev-to-h"][0].":".$this->vars->metas["ev-to-m"][0], $lorem));
                if($lorem) $end .= "<span class='lorem'></span>";
                echo "<div id='chronosly-end-date'>".$end."</div>";
                echo "<div id='chronosly-feat-img'>".CHRONOSLY_URL."css/img/noimg.jpg</div>";

                //cargariamos el hook de data fields creator
                echo "</div>";
            }

        }

        //pintamos el template del admin
        private function build_admin_template($vista,$template, $variant= "back", $html= 0){



            $st = $variant;
            $this->get_all_vars($st);
            if($variant == "back-js") {
                $this->templates_tabs($vista, 1);
            }
         //   else if($variant == "back-addon") $this->templates_tabs($vista, 0);
            else if ($variant == "back-addon-js") $this->templates_tabs($vista, 2);
            //else if($variant == "back-addon" or $variant == "back-addon-js") $this->templates_tabs($vista, 2);
            $temp = $this->vars;
            if($template == "chbd") $template = $temp->base;
            include(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."chronosly_dad_framework_metabox.php");

        }

        //pintamos el template del front, igual que el admin pero con alguna diferencia de clases
        private function build_front_template($vista,$template, $html=0){

            $st = "front";
            $this->get_all_vars($st);

            $temp = $this->vars;
            // echo "<pre>";print_r($temp);echo "</pre>";
            if($template == "chbd") $template = $temp->base;

            include(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."chronosly_dad_framework_metabox.php");

        }


        //Pinta las Variables del drag an drop
        public function templates_tabs($vista, $frontend = 0){
            global $ch_bubble_create_js_code, $ch_bubble_modify_js_code, $ch_field_create_js_code, $ch_field_modify_js_code, $ch_js_style_fields, $ch_php_style_fields;
            $dadcats = array();
            $out1 = $out2 = "";
            $dadcats = apply_filters("chronosly_dad_tabs",$dadcats, $vista, $this->vars);//llamamos para generar el array de tabs
            foreach($dadcats as $id=>$name){
                $out1 .='<li><a href="#'.$id.'">'.__($name, "chronosly").'</a></li>';
                $out2 .='<ul id="'.$id.'">';

                $ret = "";
                // echo "chronosly_dad_tabs_content_$id";
                 $out2.= apply_filters("chronosly_dad_tabs_content_$id",  $ret, "dad1", $this->vars);
                $out2 .="</ul>";
                }
                 if(!$frontend or $frontend == 2){
                     if(!$frontend) echo "<ul>$out1</ul>$out2";

                    //registramos el js para crear los addons en el dad del backend
                    wp_register_script( 'chronosly-admin-elements', CHRONOSLY_URL.'/js/admin-elements.js', array( 'jquery' ));
                    $translation_array = array(

                        "bubble_create_js_code" => json_encode($ch_bubble_create_js_code),
                        "bubble_modify_js_code" => json_encode($ch_bubble_modify_js_code),
                        "field_create_js_code" => json_encode($ch_field_create_js_code),
                        "field_modify_js_code" => json_encode($ch_field_modify_js_code),
                        "style_fields" => json_encode($ch_js_style_fields)
                    );
                    wp_localize_script( 'chronosly-admin-elements', 'translated', $translation_array );

                    wp_enqueue_script('chronosly-admin-elements');
                 }
        }

        //dad default boxes by view
       public static function chronosly_get_default_dad_tabs($dadcats, $view, $vars){
           // if($view == "dad1"){
            add_filter("chronosly_bubble_box", array("Chronosly_Dad_Elements","set_new_bubble_box"), 10, 3);
            add_filter("chronosly_field_custom_text", array("Chronosly_Dad_Elements", "custom_text_field"), 25, 1);
            add_filter("chronosly_field_custom_text_before", array("Chronosly_Dad_Elements", "custom_text_before_field"), 25, 1);
            add_filter("chronosly_field_custom_text_after", array("Chronosly_Dad_Elements", "custom_text_after_field"), 26, 1);
            add_filter("chronosly_field_custom_textarea", array("Chronosly_Dad_Elements", "custom_textarea_field"), 26, 1);
            add_filter("chronosly_field_custom_textbox", array("Chronosly_Dad_Elements", "custom_textbox_field"), 27, 1);
           add_filter("chronosly_field_readmore_text", array("Chronosly_Dad_Elements", "readmore_text_field"), 31, 1);
           add_filter("chronosly_field_readmore_check", array("Chronosly_Dad_Elements", "readmore_check_field"), 30, 1);
            add_filter("chronosly_field_readmore_action", array("Chronosly_Dad_Elements", "readmore_action_field"), 32, 1);
            add_filter("chronosly_field_external_url", array("Chronosly_Dad_Elements", "external_url_field"), 33, 1);
           add_filter("chronosly_field_target_blank", array("Chronosly_Dad_Elements", "target_blank_field"), 34, 1);
            add_filter("chronosly_field_nofollow", array("Chronosly_Dad_Elements", "nofollow_field"), 35, 1);
            add_filter("chronosly_field_shorten_text", array("Chronosly_Dad_Elements", "shorten_text_field"), 10, 1);
            add_filter("chronosly_field_time_format", array("Chronosly_Dad_Elements", "time_format_field"), 10, 1);
            add_filter("chronosly_field_upload_image", array("Chronosly_Dad_Elements", "upload_image_field"), 10, 1);
            add_filter("chronosly_field_upload_gallery", array("Chronosly_Dad_Elements", "upload_gallery_field"), 10, 1);
            add_filter("chronosly_field_tickets_title_check", array("Chronosly_Dad_Elements", "tickets_title_check_field"), 25, 1);
            add_filter("chronosly_field_tickets_price_check", array("Chronosly_Dad_Elements", "tickets_price_check_field"), 25, 1);
            add_filter("chronosly_field_tickets_capacity_check", array("Chronosly_Dad_Elements", "tickets_capacity_check_field"), 25, 1);
            // add_filter("chronosly_field_tickets_min_check", array("Chronosly_Dad_Elements", "tickets_min_check_field"), 25, 1);
            // add_filter("chronosly_field_tickets_max_check", array("Chronosly_Dad_Elements", "tickets_max_check_field"), 25, 1);
            // add_filter("chronosly_field_tickets_start_check", array("Chronosly_Dad_Elements", "tickets_start_check_field"), 25, 1);
            // add_filter("chronosly_field_tickets_end_check", array("Chronosly_Dad_Elements", "tickets_end_check_field"), 25, 1);
            add_filter("chronosly_field_tickets_buy_check", array("Chronosly_Dad_Elements", "tickets_buy_check_field"), 25, 1);
            add_filter("chronosly_field_tickets_note_check", array("Chronosly_Dad_Elements", "tickets_note_check_field"), 25, 1);


                $ret= array(
                    "event" => __("Event","chronosly"),
                    "organizer" => __("Organizer","chronosly"),
                    "place" => __("Place","chronosly"),
                    "categories" => __("Category & Tag","chronosly"),
                    "other" => __("Custom","chronosly"),
                    "time" => __("Time","chronosly"),
                    "tickets" => __("Tickets","chronosly"),
                    "image" => __("Image","chronosly")
                );
                return array_merge($dadcats,$ret);
           // }
            return $dadcats;
        }




        //event default box

        function chronosly_default_dad_content_event($return, $view, $vars){
           // if($view == "dad1" or $view == "dad2" or $view == "dad3" or $view == "dad4" or $view == "dad5"){
                //evento
                    add_filter("chronosly_bubble_event_title", array("Chronosly_Dad_Elements","set_new_bubble_event_title"), 10, 3);
                    $fields_array = array();
                    $return .= apply_filters("chronosly_bubble_event_title", 0, $fields_array, "");

                    add_filter("chronosly_bubble_event_description", array("Chronosly_Dad_Elements","set_new_bubble_event_description"), 10, 3);
                    $fields_array = array();
                    $return .= apply_filters("chronosly_bubble_event_description", 0, $fields_array, "");

                    add_filter("chronosly_bubble_event_excerpt", array("Chronosly_Dad_Elements","set_new_bubble_event_excerpt"), 10, 3);
                    $fields_array = array();
                    $return .= apply_filters("chronosly_bubble_event_excerpt", 0, $fields_array, "");

                    add_filter("chronosly_bubble_events_list", array("Chronosly_Dad_Elements","set_new_bubble_events_list"), 10, 3);
                    $fields_array = array();
                    $return .= apply_filters("chronosly_bubble_events_list", 0, $fields_array, "");


              //  }

            return $return;


        }


        function chronosly_default_dad_content_image($return, $view, $vars){
            // if($view == "dad1" or $view == "dad2" or $view == "dad3" or $view == "dad4" or $view == "dad5"){
            //evento
            add_filter("chronosly_bubble_featured_image", array("Chronosly_Dad_Elements","set_new_bubble_featured_image"), 10, 3);
            $fields_array = array();
            $return .= apply_filters("chronosly_bubble_featured_image", 0, $fields_array, "");

            add_filter("chronosly_bubble_custom_image", array("Chronosly_Dad_Elements","set_new_bubble_custom_image"), 10, 3);
            $fields_array = array();
            $return .= apply_filters("chronosly_bubble_custom_image", 0, $fields_array, "");

            add_filter("chronosly_bubble_gallery", array("Chronosly_Dad_Elements","set_new_bubble_gallery"), 10, 3);
            $fields_array = array();
            $return .= apply_filters("chronosly_bubble_gallery", 0, $fields_array, "");



            //  }

            return $return;


        }

        function chronosly_default_dad_content_tickets($return, $view, $vars){
            // if($view == "dad1" or $view == "dad2" or $view == "dad3" or $view == "dad4" or $view == "dad5"){
            $i = 1;
            add_filter("chronosly_bubble_ticket_list", array("Chronosly_Dad_Elements","set_new_bubble_ticket_list"), 10, 3);

            add_filter("chronosly_bubble_ticket_name", array("Chronosly_Dad_Elements","set_new_bubble_ticket_name"), 10, 3);
            add_filter("chronosly_bubble_ticket_price", array("Chronosly_Dad_Elements","set_new_bubble_ticket_price"), 10, 3);
            add_filter("chronosly_bubble_ticket_capacity", array("Chronosly_Dad_Elements","set_new_bubble_ticket_capacity"), 10, 3);
            add_filter("chronosly_bubble_ticket_link", array("Chronosly_Dad_Elements","set_new_bubble_ticket_link"), 10, 3);
            $return .= apply_filters("chronosly_bubble_ticket_list", 0, "", "");
            // add_filter("chronosly_bubble_ticket_min_per_user", array("Chronosly_Dad_Elements","set_new_bubble_ticket_min_per_user"), 10, 3);
            // add_filter("chronosly_bubble_ticket_max_per_user", array("Chronosly_Dad_Elements","set_new_bubble_ticket_max_per_user"), 10, 3);
            // add_filter("chronosly_bubble_ticket_start", array("Chronosly_Dad_Elements","set_new_bubble_ticket_start"), 10, 3);
            // add_filter("chronosly_bubble_ticket_end", array("Chronosly_Dad_Elements","set_new_bubble_ticket_end"), 10, 3);
            // add_filter("chronosly_bubble_ticket_notes", array("Chronosly_Dad_Elements","set_new_bubble_ticket_notes"), 10, 3);
            //mirar el order
            if(isset($vars->metas["tickets_vars"])){
                foreach($vars->metas["tickets_vars"] as $org){
                   if(isset($org["name"])) $return .= "<div class='group'><span class='group-title'>".$org["name"]."</span><div class='gr'>";
                    $fields_array = array("bubble_value" => $i);
                    $return .= apply_filters("chronosly_bubble_ticket_name", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_ticket_price", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_ticket_capacity", 0, $fields_array, "");
                    // $return .= apply_filters("chronosly_bubble_ticket_min_per_user", 0, $fields_array, "");
                    // $return .= apply_filters("chronosly_bubble_ticket_max_per_user", 0, $fields_array, "");
                    // $return .= apply_filters("chronosly_bubble_ticket_start", 0, $fields_array, "");
                    // $return .= apply_filters("chronosly_bubble_ticket_end", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_ticket_link", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_ticket_notes", 0, $fields_array, "");
                    if(isset($org["name"]))$return .="</div></div>";
                    $i++;
                }
            };
            return $return;
            //  }

            return $return;


        }

        function chronosly_default_dad_content_categories($return, $view, $vars){
            // if($view == "dad1" or $view == "dad2" or $view == "dad3" or $view == "dad4" or $view == "dad5"){
            //evento
            add_filter("chronosly_bubble_categories", array("Chronosly_Dad_Elements","set_new_bubble_categories"), 10, 3);
            $fields_array = array();
            $return .= apply_filters("chronosly_bubble_categories", 0, $fields_array, "");


            add_filter("chronosly_bubble_category_name", array("Chronosly_Dad_Elements","set_new_bubble_category_name"), 10, 3);
            $fields_array = array();
            $return .= apply_filters("chronosly_bubble_category_name", 0, $fields_array, "");

            add_filter("chronosly_bubble_category_description", array("Chronosly_Dad_Elements","set_new_bubble_category_description"), 10, 3);
            $fields_array = array();
            $return .= apply_filters("chronosly_bubble_category_description", 0, $fields_array, "");

            add_filter("chronosly_bubble_tags", array("Chronosly_Dad_Elements","set_new_bubble_tags"), 10, 3);
            $fields_array = array();
            $return .= apply_filters("chronosly_bubble_tags", 0, $fields_array, "");





            //  }

            return $return;


        }


        function chronosly_default_dad_content_organizer($return, $view, $vars){
            $i = 0;
            add_filter("chronosly_bubble_organizer_name", array("Chronosly_Dad_Elements","set_new_bubble_organizer_name"), 10, 3);
            add_filter("chronosly_bubble_organizer_description", array("Chronosly_Dad_Elements","set_new_bubble_organizer_description"), 10, 3);
            add_filter("chronosly_bubble_organizer_excerpt", array("Chronosly_Dad_Elements","set_new_bubble_organizer_excerpt"), 10, 3);
            add_filter("chronosly_bubble_organizer_phone", array("Chronosly_Dad_Elements","set_new_bubble_organizer_phone"), 10, 3);
            add_filter("chronosly_bubble_organizer_email", array("Chronosly_Dad_Elements","set_new_bubble_organizer_email"), 10, 3);
            add_filter("chronosly_bubble_organizer_web", array("Chronosly_Dad_Elements","set_new_bubble_organizer_web"), 10, 3);
            add_filter("chronosly_bubble_organizer_logo", array("Chronosly_Dad_Elements","set_new_bubble_organizer_logo"), 10, 3);
            //mirar el order
            if(isset($vars->metas["organizer_vars"])){
                foreach($vars->metas["organizer_vars"] as $org){
                    $return .= "<div class='group'><span class='group-title'>".$org["post"]->post_title."</span><div class='gr'>";
                    $fields_array = array("bubble_value" => $i);
                    $return .= apply_filters("chronosly_bubble_organizer_name", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_organizer_description", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_organizer_excerpt", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_organizer_phone", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_organizer_email", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_organizer_web", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_organizer_logo", 0, $fields_array, "");
                    $return .="</div></div>";
                    $i++;
                }
            };
            return $return;

        }

        function chronosly_default_dad_content_place($return, $view, $vars){


            $i = 0;
            add_filter("chronosly_bubble_place_name", array("Chronosly_Dad_Elements","set_new_bubble_place_name"), 10, 3);
            add_filter("chronosly_bubble_place_description", array("Chronosly_Dad_Elements","set_new_bubble_place_description"), 10, 3);
            add_filter("chronosly_bubble_place_excerpt", array("Chronosly_Dad_Elements","set_new_bubble_place_excerpt"), 10, 3);
            add_filter("chronosly_bubble_place_phone", array("Chronosly_Dad_Elements","set_new_bubble_place_phone"), 10, 3);
            add_filter("chronosly_bubble_place_email", array("Chronosly_Dad_Elements","set_new_bubble_place_email"), 10, 3);
            add_filter("chronosly_bubble_place_web", array("Chronosly_Dad_Elements","set_new_bubble_place_web"), 10, 3);
            add_filter("chronosly_bubble_place_direction", array("Chronosly_Dad_Elements","set_new_bubble_place_direction"), 10, 3);
            add_filter("chronosly_bubble_place_city", array("Chronosly_Dad_Elements","set_new_bubble_place_city"), 10, 3);
            add_filter("chronosly_bubble_place_state", array("Chronosly_Dad_Elements","set_new_bubble_place_state"), 10, 3);
            add_filter("chronosly_bubble_place_country", array("Chronosly_Dad_Elements","set_new_bubble_place_country"), 10, 3);
            add_filter("chronosly_bubble_place_pc", array("Chronosly_Dad_Elements","set_new_bubble_place_pc"), 10, 3);
            add_filter("chronosly_bubble_place_image", array("Chronosly_Dad_Elements","set_new_bubble_place_image"), 10, 3);
            add_filter("chronosly_bubble_place_gmap", array("Chronosly_Dad_Elements","set_new_bubble_place_gmap"), 10, 3);
            //mirar el order
            if(isset($vars->metas["places_vars"])){
                foreach($vars->metas["places_vars"] as $org){
                    $return .= "<div class='group'><span class='group-title'>".$org["post"]->post_title."</span><div class='gr'>";
                    $fields_array = array("bubble_value" => $i);

                    $return .= apply_filters("chronosly_bubble_place_name", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_place_description", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_place_excerpt", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_place_phone", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_place_email", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_place_web", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_place_direction", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_place_city", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_place_state", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_place_country", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_place_pc", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_place_image", 0, $fields_array, "");
                    $return .= apply_filters("chronosly_bubble_place_gmap", 0, $fields_array, "");

                    $return .="</div></div>";
                    $i++;
                }
            };
            return $return;
        }






        function chronosly_default_dad_content_other($return, $view, $vars){
            //if($view == "dad1"){
                add_filter("chronosly_bubble_custom_text", array("Chronosly_Dad_Elements","set_new_bubble_custom_text"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_custom_text", 0, $fields_array, "");

                add_filter("chronosly_bubble_custom_text_box", array("Chronosly_Dad_Elements","set_new_bubble_custom_text_box"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_custom_text_box", 0, $fields_array, "");

                add_filter("chronosly_bubble_custom_link", array("Chronosly_Dad_Elements","set_new_bubble_custom_link"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_custom_link", 0, $fields_array, "");

                add_filter("chronosly_bubble_custom_code", array("Chronosly_Dad_Elements","set_new_bubble_custom_code"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_custom_code", 0, $fields_array, "");

                add_filter("chronosly_bubble_cont_box", array("Chronosly_Dad_Elements","set_new_bubble_cont_box"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_cont_box", 0, $fields_array, "");
           // }
            return $return;
        }

        function chronosly_default_dad_content_time($return, $view, $vars){
            //if($view == "dad1"){
                add_filter("chronosly_bubble_full_datetime", array("Chronosly_Dad_Elements","set_new_bubble_full_datetime"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_full_datetime", 0, $fields_array, "");
                add_filter("chronosly_bubble_full_date", array("Chronosly_Dad_Elements","set_new_bubble_full_date"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_full_date", 0, $fields_array, "");
                add_filter("chronosly_bubble_full_time", array("Chronosly_Dad_Elements","set_new_bubble_full_time"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_full_time", 0, $fields_array, "");

                add_filter("chronosly_bubble_start_date", array("Chronosly_Dad_Elements","set_new_bubble_start_date"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_start_date", 0, $fields_array, "");

                add_filter("chronosly_bubble_start_hour", array("Chronosly_Dad_Elements","set_new_bubble_start_hour"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_start_hour", 0, $fields_array, "");

                add_filter("chronosly_bubble_start_datetime", array("Chronosly_Dad_Elements","set_new_bubble_start_datetime"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_start_datetime", 0, $fields_array, "");

                add_filter("chronosly_bubble_end_date", array("Chronosly_Dad_Elements","set_new_bubble_end_date"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_end_date", 0, $fields_array, "");

                add_filter("chronosly_bubble_end_hour", array("Chronosly_Dad_Elements","set_new_bubble_end_hour"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_end_hour", 0, $fields_array, "");

                add_filter("chronosly_bubble_end_datetime", array("Chronosly_Dad_Elements","set_new_bubble_end_datetime"), 10, 3);
                $fields_array = array();
                $return .= apply_filters("chronosly_bubble_end_datetime", 0, $fields_array, "");


           // }
            return $return;
        }



        public function parse_style($style){
            $s =array();
            $ex = explode(";", $style);
            foreach($ex as $x){
                $var = explode(":", $x);
                if(count($var) > 1)$s[trim($var[0])] = trim($var[1]);

            }

            return $s;
        }

        public function parse_data_style($name, $value, $html2=0){
            global $ch_php_style_fields;
            $out3 = "";
            if(isset($ch_php_style_fields[$name]) and ($value or $value === 0 or $value === "0")) {

                foreach($ch_php_style_fields[$name]["fields"] as $k=>$v){
                    //cargamos la accion del value, transformando el js a php
                    $val = $ch_php_style_fields[$name]["values"][$k];
                   // $check = Chronosly_Extend->is_field_chackebox($name);
                   // if(!$check or($check and $value)) {
                        if($name == "css") {
                            $color = "";

                            if(isset($this->vars->metas["cats_vars"][0]->metas["cat-color"]) and $this->vars->metas["cats_vars"][0]->metas["cat-color"] != "") $color = $this->vars->metas["cats_vars"][0]->metas["cat-color"];
                            if(!$this->vars->pid) {
                                $featimg = CHRONOSLY_URL."css/img/noimg.jpg";

                            }
                            else {
                               $pid = $this->vars->pid;
                                if($fin = stripos($pid, "_")) $pid = substr($pid, 0, $fin);
                                $featimg = wp_get_attachment_url( get_post_thumbnail_id($pid) );
                            }
                            if(!$color){
                                $color = $this->settings["chronosly_category_color"];
                            }
                            if(!$this->settings["chronosly_sale_color"]){
                                $color2 = "#ff9711";
                            } else $color2 = $this->settings["chronosly_sale_color"];

                            if($html2) $val = $value;
                            else $val = str_replace(array("#cat-color", "#sale-color", "#feat-image"), array($color,$color2, $featimg), $value);
                            $out3 .= $val;
                        }
                        else {
                            $val = str_replace("value", $value, $val);
                            $vals = explode(".", $val);
                            // print_r($vals);
                            $valconc = "";
                           // print_r($vals);
                            foreach($vals as $vx){
                                if(substr($vx, 0, 1) == "'") $vx = '"'.substr($vx, 1, -1).'"';
                                //echo '$valconc .= '.$vx.";";
                                $valconc .= eval('$valconc .= '.$vx.";");
                            }
                            $out3 .= "$v:$valconc;";
                        }



                   // }
                }
            }

            return $out3;

        }



        public function parse_box_style($style, $item, $html=0){
            $out2 = "";
            if(isset($item->attr)){
                foreach($item->attr as $attr){
                    if(!isset($attr->name)) continue;
                    $value = "";
                    if(isset($attr->value))$value =  str_replace("#plus#", "+",urldecode( $attr->value));
                     $out2 .= $this->parse_data_style($attr->name, $value, $html);

                }
            }
            return $style.$out2;
        }

        public function needed_style($st){
            $out ="";
            if(count($st) > 0){
                foreach($st as $a=>$b){
                    if($a == "width" )$out .="$a:$b;";
                    else if($a == "height") {
                        if($b != "18px") $out .="$a:$b;";
                    }
                    else if($a == "float" or $a == "clear")$out .="$a:$b;";
                }
            }
            return $out;
        }

       public function print_box($box, $temp, $st){
            $out = "";
           if(isset($box->attr) or $box == "hide"){

               if($box != "hide"){
                   foreach($box->attr as $attr){
                       if(!isset($attr->name)) continue;
                       $name = $attr->name;
                       $value = str_replace("#plus#", "+",urldecode( $attr->value));
                       //$out3 .= $this->parse_data_style($name, $value);
                       $style2[$name] = $value;
                   }
               }
               if(stripos( $st,"back")  !==  FALSE) $out .= apply_filters("chronosly_bubble_box",  1, "", $style2);

           }
           return $out;
       }

        public function print_item_box($item, $temp, $st, $html2=0){
            global $tabs, $timestamp;
            $out1 = $out2 = $out3 = $out11= $out111= "";
            $es = $this->parse_style($item->style);
            $style = $this->needed_style($es);
            $etype = "";
            $fields_array = array();


            $numItems = count($item->attr );
            $i = 0;
            $vars = $this->vars;
            if(isset($item->attr)){
                foreach($item->attr as $attr){
                    ++$i;
                    $type = $label = $content = "";
                    $type = $attr->name;
                    $value =  str_replace("#plus#", "+",urldecode( $attr->value));
                    if(isset($attr->extra))$extra =  str_replace("#plus#", "+",urldecode( $attr->extra));
                    if(isset($attr->label)) $label =  str_replace("#plus#", "+",urldecode( $attr->label));

                    if(isset($temp->post->post_content)) $content =  str_replace("#plus#", "+",urldecode( $temp->post->post_content));
                    if($i == 1){
                        if($type == "cont_box") {
                            $etype = $type;
                            ob_start();
                            foreach($item->items as $it) $this->print_item_box($it, $temp, $st, $html2);
                            $cont= ob_get_clean(); //aqui va el html del bucle de inside box
                            $cont = "\n$tabs<div class='sortable'>\n$tabs   ".$cont."\n$tabs</div>\n";
                            $box ="";
                            if(count($item->items) and stripos($cont, "ev-data") === FALSE) $box = "width:0px!important;";

                            $out1 .="\n$tabs<div class='ev-item $type'   style='$style#data_style$box'>\n";


                        }
                        else {
                            $out1 .="\n$tabs<div class='ev-item $type'  style='$style#data_style'>\n";

                                //variables fields

                            $cont = apply_filters("chronosly_bubble_render_$type", $value, $vars, $html2);
                            $etype = $type;
                            $x = -1;
                            $fields_array = array();

                            $fields_array["bubble_value"] = $value;
                            if(isset($attr->vars)){
                                foreach($attr->vars as $var){
                                    ++$x;
                                    if($x == 0) continue;//nos saltamos el primer elemento
                                    $type2 = $var->name;
                                    $fields_array[$type2] = array("order" => $x);
                                    $extra ="";
                                    if(isset($var->value)) {
                                        $value = str_replace("#plus#", "+",urldecode( $var->value));

                                        $fields_array[$type2]["value"] = $value;

                                    } else $value = null;
                                    if(isset($var->extra)){
                                        $fields_array[$type2]["extra"] =  str_replace("#plus#", "+",urldecode( $var->extra));
                                        $value = array("value" => $value, "extra"=> str_replace("#plus#", "+",urldecode( $var->extra)));
                                     }
                                    if(isset($var->label)) {
                                        $label = str_replace("#plus#", "+",urldecode( $var->label));
                                        $fields_array[$type2]["label"] = $label;

                                    }
                                    //echo "chronosly_field_render_$type2<br/>";
                                    if(($type2 == "custom_code" || $type2 == "custom_text") && stripos($st,"back")!== FALSE) $cont = " ".$value;
                                    else $cont = "   ".apply_filters("chronosly_field_render_$type2", $cont,$value, $vars, $html2);
                                }
                            }

                            if($type == "place_gmap"){
                                $width = "100%";
                                $height = "200";
                                if($html2) $cont = "$tabs   <div id='gmap{{id}}{{timestamp}}' class='ev-data $type'  style='width:$width; height:{$height}px;#data_style'>\n$tabs $cont\n$tabs</div>";
                                else {
                                    $cont = "  $tabs<div id='gmap{$vars->pid}".$timestamp."' class='ev-data $type'  style='width:$width; height:{$height}px;#data_style'>\n$tabs     $cont\n$tabs    </div>";
                                }
                            }
                            else{
                                $cont = "   $tabs<div class='ev-data $etype'>\n$tabs".$cont."\n     $tabs</div>\n$tabs";
                            }
                        }
                    } else {
                     //style fields
                        $name = $attr->name;
                        $value = str_replace("#plus#", "+",urldecode( $attr->value));
                        $out3 .= $this->parse_data_style($name, $value, $html2);
                        $style2[$name] = $value;

                    }

                } //end attr item foreach
            }
            // if($html2){
            //     $out1 .= '<div class="ch-clear"></div></div>';
            //    echo str_replace("#data_style", $out3, substr($cont, 0, -2)." | clousure $out1");
            //     // if(stripos( $st,"back")  !==  FALSE) echo apply_filters("chronosly_bubble_$etype",  1, $fields_array, $style2);

            // }
            // else {
               if(stripos( $st,"back")  !==  FALSE or ( stripos($cont, "class='lorem'") === FALSE and stripos($cont, 'class="lorem"') === FALSE )) echo str_replace("#data_style", $out3, $out1.$cont);
                if(stripos( $st,"back")  !==  FALSE) echo apply_filters("chronosly_bubble_$etype",  1, $fields_array, $style2);

               if(stripos( $st,"back")  !==  FALSE or  (stripos($cont, "class='lorem'") === FALSE and stripos($cont, 'class="lorem"') === FALSE )) echo "\n$tabs    <div class='ch-clear'></div>\n$tabs</div>";
            // }




        }

        public function full_update_templates_by_addon($addon, $vistas, $action= "update"){
            /*global $wp_filter;
            echo "<pre>";print_r($wp_filter);*/
            foreach($vistas as $v) $dads[] = "dad".$v;
            //se pasa en el action los templates a reinstalar. Para el core update(default), o para los updates de templates
            if(is_array($action)){
                $templates = $action;
                $action = "reinstall";
            }
            else $templates = $this->get_templates_options(1);

            $path = CHRONOSLY_TEMPLATES_PATH;//ruta hacia los templates del user
            foreach($templates as $temp){

                $addons_sets = unserialize(get_option("chronosly_settings_".$addon));
                    foreach($dads as $vista){
                        //miramos si ya hemos hecho un update del template
                        if((!@in_array($temp, $addons_sets["templates_auto_updated"][$vista]) and $action == "update") or (@in_array($temp, $addons_sets["templates_auto_updated"][$vista]) and$action == "remove") or $action == "reinstall"){
                            $vars = $this->get_template_vars($path.DIRECTORY_SEPARATOR.$vista.DIRECTORY_SEPARATOR.$temp.".php");
                            $vars = apply_filters("chronosly_update_template_$addon", $vars, $temp, str_replace("dad", "",$vista), str_replace("reinstall", "update",$action));
                            $f = @fopen($path.DIRECTORY_SEPARATOR.$vista.DIRECTORY_SEPARATOR.$temp.".php", "w");
                            @fwrite( $f , json_encode($vars));
                            @fclose($f);
                            if(!@in_array($temp, $addons_sets["templates_auto_updated"][$vista])) {
                                $addons_sets["templates_auto_updated"][$vista][] = $temp;
                                update_option("chronosly_settings_".$addon, serialize($addons_sets));
                            }
                        }
                    }

            }
            return true;
        }

    public function save_all_html_templates(){

        $dads = array("dad1","dad2","dad3","dad4","dad5","dad6","dad7","dad8","dad9","dad10","dad11","dad12");
        foreach($dads as $tid){
            $temps = $this->get_file_templates(0, $tid,2);
            // print_r($temps);
            foreach ($temps as $t) {
                if(!stripos($t,".html")) $this->template_2_html(str_replace(" ", "_", $t), $tid);
            }
        }
    }

        //funciones para el html de los templates

      public function template_2_html($template, $vista="dad1"){

            // $dads = array("dad1");

            $path = CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR;

               $f = fopen($path.$vista.DIRECTORY_SEPARATOR.$template.".html", "w");
                $template = str_replace(" ", "_",$template);
                // $id = 1;
                $style = "front";

                ob_start();
                $id = 1;
                $this->print_template($id, $vista, "", $template, $style, array(), 1);
                $cont= ob_get_clean();

                fwrite( $f , $cont);
                fclose($f);

                $f = fopen($path.$vista.DIRECTORY_SEPARATOR.$template."_featured.html", "w");
                $template = str_replace(" ", "_",$template);
                $id = 1;
                $style = "front";

                ob_start();

                $this->print_template($id, $vista, "", $template, $style, array(), 2);
                $cont= ob_get_clean();

                fwrite( $f , $cont);
                fclose($f);

            // }

      }
       public function print_template_html($id, $vista, $draganddropels="", $template ="", $style="back", $args = array()){
            global $timestamp;
            $timestamp = $timestamp+1;
            $vars = $this->settings;
            $data = "";
            $this->vars = new stdClass();
            $this->vars->pid = $id;
            $this->vars->vista = $vista;
            $this->vars->args = $args;
            $itid = $id;
            $this->get_all_vars("front");
            // $dads = array("dad1","dad2","dad3","dad4","dad5","dad6","dad7","dad8","dad9","dad10","dad11","dad12");
            // $dads = array("dad1");
            $path = CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR;
            // foreach($dads as $tid){
            $template = str_replace(" ", "_",$template);
            if($args["start"] and $args["end"]) $itid .= "_{$args["start"]}_{$args["end"]}";

            // if($style == "front" and $id =! 1 and  $html = Chronosly_Cache::load_item($itid, $vista)) {
            //    echo $html;
            // }
            // else {


                if(isset($vars['chronosly_template_default_active']) and $vars['chronosly_template_default_active'] and stripos($_SERVER['HTTP_REFERER'], "chronosly_edit_templates") === FALSE ) $template = $vars['chronosly_template_default'];
                if($_REQUEST["force_template"]) $template = $_REQUEST["force_template"];

                if(!$template) $template = $this->get_tipo_template($id, $vista);
                if(!$template) $template = $vars['chronosly_template_default'];
                if($this->vars->metas["featured"][0] and file_exists($path.$vista.DIRECTORY_SEPARATOR.$template."_featured.html")){
                    $f = fopen($path.$vista.DIRECTORY_SEPARATOR.$template."_featured.html", "r");
                    $content = @fread($f, filesize($path.$vista.DIRECTORY_SEPARATOR.$template."_featured.html"));
                }
                else {
                    $f = fopen($path.$vista.DIRECTORY_SEPARATOR.$template.".html", "r");
                    $content = @fread($f, filesize($path.$vista.DIRECTORY_SEPARATOR.$template.".html"));

                }

                @fclose($f);
                            // print_r($this->vars);

                $this->replace_html_tags($content);
                $color = "";

                if(isset($this->vars->metas["cats_vars"][0]->metas["cat-color"]) and $this->vars->metas["cats_vars"][0]->metas["cat-color"] != "") $color = $this->vars->metas["cats_vars"][0]->metas["cat-color"];
                if(!$this->vars->pid) {
                    $featimg = CHRONOSLY_URL."css/img/noimg.jpg";

                }
                else {
                   $pid = $this->vars->pid;
                    if($fin = stripos($pid, "_")) $pid = substr($pid, 0, $fin);
                    $featimg = wp_get_attachment_url( get_post_thumbnail_id($pid) );
                }

                if(!$color){
                    $color = $this->settings["chronosly_category_color"];
                }
                if(!$this->settings["chronosly_sale_color"]){
                    $color2 = "#ff9711";
                } else $color2 = $this->settings["chronosly_sale_color"];

                $content = str_replace(array("#cat-color", "#sale-color", "#feat-image"), array($color,$color2, $featimg), $content);

                echo $content;
                 // if($style == "front") Chronosly_Cache::save_item($itid, $vista, $content);
            // }


            // }

      }

        public function replace_html_tags(&$content){
            while($ini = stripos($content, "{{")){
                $cont = $ret = "";
                $this->replace_html_tags_rec(substr($content, $ini+2), $cont, $ret, 0);
                if(is_array($ret)) {
                    // echo $cont;
                    // print_r($ret);
                    $pos = strpos($content,$cont);
                    if ($pos !== false) {
                        $content = substr_replace($content,"",$pos,strlen($cont));
                    }
                    $pos = strrpos(substr($content, 0, $ini), "ev-item ".$ret[0]);
                    if($pos !== FALSE )$content = substr($content, 0, $pos)." hide ".substr($content, $pos);
                }
                else {
                    $pos = strpos($content,$cont);
                    // echo $cont." ".$ret;
                    if ($pos !== false) {
                        // echo "HOLA";
                        $content = substr_replace($content,$ret,$pos,strlen($cont));
                    }
                }

            }
        }

         public function replace_html_tags_rec($content, &$cont, &$ret, $deep){
            $i = 0;
            $dobleclose = false;
            $oneclose = false;
            $oneopen = false;
            // $pipe = false;
            $cont="{{";
            $rep = "";
            $var = "";
            // echo "init";
            while(!$dobleclose and $i < strlen($content)){
                $char = $content[$i];
                $cont .= $char;
                if($char == "}"){
                    if($oneclose) $dobleclose = true;
                    else $oneclose = true;
                } else if($oneclose) $oneclose = false;

                if($char == "{"){
                    if($oneopen) {
                        // echo $rep;
                        $oneopen = false;
                        // echo substr($content, $i, 30);
                        $c = "";
                        $r = "";
                        // echo "-->> ";
                        $this->replace_html_tags_rec(substr($content, $i+1), $c, $r, 0);
                        $cont .= substr($c, 2);
                        $i = $i-2+strlen($c);
                        // echo " me llevo esto ".$content[$pos+1].$content[$pos+2].$content[$pos+3].$content[$pos+4];
                       if(is_array($r)) {
                            $rep .= " ";

                        }
                        else {
                            $rep .= " $r";
                                // echo ",$r,$pos,".strlen($c);

                         }
                        // echo "  --<< ";
                        // echo "aqui $rep xx";
                    }
                    else $oneopen = true;

                } else if($oneopen) $oneopen = false;

                if($char == "|"){
                    if(!$deep){
                        $ret = $this->get_html_var($rep);

                        $var = trim($rep);
                        $deep++;
                    } else {
                        $f = explode(" ", trim($rep), 2);
                        $func = $f[0];
                        $arg = $f[1];
                        $ret = $this->run_html_var_function($ret, $func, $arg);
                    }
                    $rep = "";
                } else if($char != "}" and $char != "{"){
                    $rep .= $char;
                }
                //recursivo
                // if()
                ++$i;

            }
            // echo "fin";
            if($deep){
                if($rep[0] == " ") $rep = substr($rep, 1);
                $f = explode(" ", $rep, 2);
                // echo $rep;

                $func = $f[0];
                $arg = $f[1];
                $ret = $this->run_html_var_function($ret, $func, $arg);


            } else if($rep and !is_array($rep)){
                 $ret = $this->get_html_var($rep);
                 $var = trim($rep);
             }
             // echo $ret;
            if(stripos($ret, "class='lorem'") !== FALSE or stripos($ret, 'class="lorem"') !== FALSE  or stripos($ret, "class='lorem ") !== FALSE   or stripos($ret, 'class="lorem ') !== FALSE ) $ret = array($var);
        }

        public function get_html_var($var){
            global $timestamp;
            $settings = unserialize(get_option("chronosly-settings"));

            if($fin = stripos($this->vars->pid, "_")) $this->vars->pid = substr($this->vars->pid, 0, $fin);
            $vars = $this->vars;
            switch(trim($var)){
                case "id":
                    $repeat ="";
                    // if($vars->metas['repeat']) $repeat = "&repeat=" . $vars->metas['repeat'];
                    return $vars->pid.$repeat;
                break;
                case "idurl":
                    $repeat ="";
                    if($vars->metas['repeat']) $repeat = "&repeat=" . $vars->metas['repeat'];
                    return $vars->pid.$repeat;
                break;
                case "size":
                    if(isset($_REQUEST['small']) and $_REQUEST["small"]) return "small";
                    return "";
                break;
                case "translate":
                    return "translate";
                break;
                case "max-width":
                    if($settings["chronosly_template_max"]) return  "max-width:".$settings["chronosly_template_max"]."px;";
                    return ";";
                break;
                case "min-width":
                    if($settings["chronosly_template_min"]) return  "min-width:".$settings["chronosly_template_min"]."px;";
                    return ";";
                break;
                case "timestamp":
                    return $timestamp;
                break;

                case "event_link":
                    return str_replace("<span class='lorem'></span>", "",$vars->link);
                break;
                case "event_image":
                    return Chronosly_dad_elements::chronosly_create_featured_image("", $vars);
                break;
                case "categories":
                    return Chronosly_dad_elements::chronosly_create_categories("", $vars);
                break;
                case "event_title":
                    return Chronosly_dad_elements::chronosly_create_event_title("", $vars);
                break;
                case "event_description":
                    return Chronosly_dad_elements::chronosly_create_event_description("", $vars);
                break;
                case "event_excerpt":
                    return Chronosly_dad_elements::chronosly_create_event_excerpt("", $vars);
                break;
                case "event_list":
                    return Chronosly_dad_elements::chronosly_create_events_list("", $vars);
                break;
                case "tags":
                    return Chronosly_dad_elements::chronosly_create_tags("", $vars);
                break;
                case "tickets_list":
                    return Chronosly_dad_elements::chronosly_create_ticket_list("", $vars);
                break;
                case "seasons_list 1":
                    if(class_exists("Chronosly_Tickets_and_Repeats_Extended")){
                        // echo "HOLA";
                        return Chronosly_Tickets_and_Repeats_Extended::chronosly_create_seasons_item(1, $vars);
                    } else return "";
                break;
                case "seasons_list":
                    if(class_exists("Chronosly_Tickets_and_Repeats_Extended")){
                        // echo "HOLA";
                        return Chronosly_Tickets_and_Repeats_Extended::chronosly_create_seasons_item(0, $vars);
                    } else return "";
                break;

                case "full_date":
                case "full_time":
                case "full_datetime":
                case "start_date":
                case "start_hour":
                case "end_date":
                case "start_datetime":
                case "end_hour":
                case "end_datetime":
                case "place_id":
                case "place_name":
                case "place_phone":
                case "place_email":
                case "place_web":
                case "place_pc":
                case "place_image":
                case "place_description":
                case "place_excerpt":
                case "place_direction":
                case "place_city":
                case "place_state":
                case "place_country":
                case "place_gmap":
                case "organizer_link":
                case "place_link":
                case "organizer_name":
                case "organizer_description":
                case "organizer_excerpt":
                case "organizer_phone":
                case "organizer_email":
                case "organizer_web":
                case "organizer_image":
                case "organizer_id":
                case "category_slug":
                case "category_name":
                case "category_id":
                case "category_description":
                // case "place_full_address":
                case "ticket_price":
                case "ticket_link":
                case "ticket_name":
                case "ticket_capacity":
                case "ticket_min":
                case "ticket_max":
                case "ticket_start":
                case "ticket_end":
                case "ticket_notes":
                case "share_box":

                    return trim($var);

                break;
                case "notprev":
                    return "";

                break;



            }
                        echo "not recognized var $var<br>";

        }

         public function run_html_var_function($cont, $func, $arg){
            // echo $cont." ".$func." ".$arg;
            if($cont == "share_box" and trim($func) != "defaultchecked") $cont = "";
            $settings = unserialize(get_option("chronosly-settings"));

            $vars = $this->vars;
            if($cont == "translate") {
                if(stripos($func." ".$arg, "[") !== false and stripos( $func." ".$arg, "]") !== false) {
                    ob_start();
                    do_shortcode(__($func." ".$arg, "chronosly"));
                    $ret = ob_get_contents();
                    if(!$ret){
                     echo do_shortcode(__($func." ".$arg, "chronosly"));
                      $ret = ob_get_contents(); 
                    } 
                    ob_end_clean();
                    return $ret;
                } else  return __($func." ".$arg, "chronosly");
            }

            switch(trim($func)){
                case "filter":
                // echo $cont;
                    return apply_filters($arg, $cont);
                break;
                case "text_after":

                    return Chronosly_dad_elements::create_custom_text_after_item($cont, $arg, $vars);
                break;
                case "text_before":
                    // echo "$cont";
                    return Chronosly_dad_elements::create_custom_text_before_item($cont, $arg, $vars);
                break;
                case "shorten_text":
                    // echo "$cont";
                    return Chronosly_dad_elements::create_shorten_text_item($cont, $arg, $vars);
                break;
                case "time_format":
                    $value["extra"] = $cont;
                    $value["value"] = $arg;
                    return Chronosly_dad_elements::create_time_format_item("", $value, $vars);
                break;
                case "ticket_title":
                    return Chronosly_dad_elements::create_ticket_title($cont, $arg, $vars);
                break;
                case "ticket_price":
                    return Chronosly_dad_elements::create_ticket_price($cont, $arg, $vars);
                break;
                case "ticket_capacity":
                    return Chronosly_dad_elements::create_ticket_capacity($cont, $arg, $vars);
                break;
                case "ticket_min":
                    return Chronosly_dad_elements::create_ticket_min($cont, $arg, $vars);
                break;
                case "ticket_max":
                    return Chronosly_dad_elements::create_ticket_max($cont, $arg, $vars);
                break;
                case "ticket_start":
                    return Chronosly_dad_elements::create_ticket_start($cont, $arg, $vars);
                break;
                case "ticket_end":
                    return Chronosly_dad_elements::create_ticket_end($cont, $arg, $vars);
                break;
                case "ticket_link":
                    return Chronosly_dad_elements::create_ticket_buy($cont, $arg, $vars);
                break;
                case "ticket_note":
                    return Chronosly_dad_elements::create_ticket_note($cont, $arg, $vars);
                break;

                case "seasons_calendar":
                    if(class_exists("Chronosly_Tickets_and_Repeats_Extended")){
                        return Chronosly_Tickets_and_Repeats_Extended::chronosly_create_seasons_calendar_item($cont, $arg, $vars);
                    } else return "";
                break;
                 case "seasons_date_format":
                    if(class_exists("Chronosly_Tickets_and_Repeats_Extended")){
                        return Chronosly_Tickets_and_Repeats_Extended::chronosly_create_seasons_date_format($cont, $arg, $vars);
                    } else return "";
                break;
                 case "seasons_hour_format":
                    if(class_exists("Chronosly_Tickets_and_Repeats_Extended")){
                        return Chronosly_Tickets_and_Repeats_Extended::chronosly_create_seasons_hour_format($cont, $arg, $vars);
                    } else return "";
                break;
                 case "seasons_ticket_name":
                    if(class_exists("Chronosly_Tickets_and_Repeats_Extended")){
                        return Chronosly_Tickets_and_Repeats_Extended::chronosly_create_seasons_ticket_name($cont, $arg, $vars);
                    } else return "";
                break;
                 case "seasons_ticket_price":
                    if(class_exists("Chronosly_Tickets_and_Repeats_Extended")){
                        return Chronosly_Tickets_and_Repeats_Extended::chronosly_create_seasons_ticket_price($cont, $arg, $vars);
                    } else return "";
                break;
                 case "seasons_ticket_link":
                    if(class_exists("Chronosly_Tickets_and_Repeats_Extended")){
                        return Chronosly_Tickets_and_Repeats_Extended::chronosly_create_seasons_ticket_link($cont, $arg, $vars);
                    } else return "";
                break;

                case "share_fb":
                    if(class_exists("Chronosly_Social_Media_Share")){
                        return Chronosly_Social_Media_Share::chronosly_create_fb_item($cont, $arg, $vars);
                    } else return "";
                break;
                case "share_tw":
                    if(class_exists("Chronosly_Social_Media_Share")){
                        return Chronosly_Social_Media_Share::chronosly_create_tw_item($cont, $arg, $vars);
                    } else return "";
                break;
                case "share_in":
                    if(class_exists("Chronosly_Social_Media_Share")){
                        return Chronosly_Social_Media_Share::chronosly_create_in_item($cont, $arg, $vars);
                    } else return "";
                break;
                case "share_go":
                    if(class_exists("Chronosly_Social_Media_Share")){
                        return Chronosly_Social_Media_Share::chronosly_create_go_item($cont, $arg, $vars);
                    } else return "";
                break;
                case "share_pi":
                    if(class_exists("Chronosly_Social_Media_Share")){
                        return Chronosly_Social_Media_Share::chronosly_create_pi_item($cont, $arg, $vars);
                    } else return "";
                break;
                case "share_ics":
                    if(class_exists("Chronosly_Social_Media_Share")){
                        return Chronosly_Social_Media_Share::chronosly_create_ics_item($cont, $arg, $vars);
                    } else return "";
                break;
                case "defaultchecked":
                    switch($cont){
                        case "share_box":
                            if(class_exists("Chronosly_Social_Media_Share")){

                                return Chronosly_Social_Media_Share::chronosly_create_default_item($cont, $arg, $vars);
                            } else return "";
                        break;
                    }
                break;
                case "id":
                    $arg = trim($arg);
                    switch($cont){
                        case "place_id":
                            $id = $vars->metas["places_vars"][$arg]["post"]->ID;
                            if($id == 1) return "class='lorem'";
                        break;
                        case "organizer_id":
                        // echo "arg $arg";
                            $id = $vars->metas["organizer_vars"][$arg]["post"]->ID;
                            if($id == 1) return "class='lorem'";
                        break;
                        case "organizer_name":
                        // echo "arg $arg";
                                                    if(!$settings["chronosly_organizers"]) return "";

                            return $vars->metas["organizer_vars"][$arg]["post"]->post_title;
                        break;
                        case "organizer_description":
                            return  Chronosly_dad_elements::chronosly_create_organizer_description($arg, $vars);
                        break;
                        case "organizer_excerpt":
                            return  Chronosly_dad_elements::chronosly_create_organizer_excerpt($arg, $vars);
                        break;
                        case "organizer_phone":
                            return  Chronosly_dad_elements::chronosly_create_organizer_phone($arg, $vars);
                        break;
                        case "organizer_email":
                            return  Chronosly_dad_elements::chronosly_create_organizer_email($arg, $vars);
                        break;
                        case "organizer_web":
                            return  Chronosly_dad_elements::chronosly_create_organizer_web($arg, $vars);
                        break;
                        case "organizer_image":
                            return  Chronosly_dad_elements::chronosly_create_organizer_logo($arg, $vars);
                        break;
                        case "organizer_link":
                        // echo "arg $arg";
                         if(!$settings["chronosly_organizers"]) return "";
                           if($arg) return get_post_permalink($arg);
                           else return "";
                        break;
                        case "place_link":
                        // echo "arg $arg";
                                                 if(!$settings["chronosly_places"]) return "";

                           if($arg) return get_post_permalink($arg);
                           else return "";
                        break;
                        // case "place_full_address":
                        //     return Chronosly_dad_elements::chronosly_create_place_full_address($arg, $vars);
                        // break;
                        case "place_name":
                            if(!$settings["chronosly_places"]) return "";

                            return $vars->metas["places_vars"][$arg]["post"]->post_title;
                        break;
                        case "place_phone":
                            return  Chronosly_dad_elements::chronosly_create_place_phone($arg, $vars);
                        break;
                        case "place_email":
                            return  Chronosly_dad_elements::chronosly_create_place_email($arg, $vars);
                        break;
                        case "place_web":
                            return  Chronosly_dad_elements::chronosly_create_place_web($arg, $vars);
                        break;
                        case "place_pc":
                            return  Chronosly_dad_elements::chronosly_create_place_pc($arg, $vars);
                        break;
                        case "place_image":
                            return  Chronosly_dad_elements::chronosly_create_place_image($arg, $vars);
                        break;
                        case "place_description":
                            return  Chronosly_dad_elements::chronosly_create_place_description($arg, $vars);
                        break;
                        case "place_excerpt":
                            return  Chronosly_dad_elements::chronosly_create_place_excerpt($arg, $vars);
                        break;
                        case "place_direction":
                            return Chronosly_dad_elements::chronosly_create_place_direction($arg, $vars);
                        break;
                        case "place_city":
                            return Chronosly_dad_elements::chronosly_create_place_city($arg, $vars);
                        break;
                        case "place_state":
                            return Chronosly_dad_elements::chronosly_create_place_state($arg, $vars);
                        break;
                        case "place_country":
                            return Chronosly_dad_elements::chronosly_create_place_country($arg, $vars);
                        break;
                        case "place_gmap":
                            return Chronosly_dad_elements::chronosly_create_place_gmap($arg, $vars, "print");
                        break;
                        case "category_slug":
                            return $vars->metas["cats_vars"][$arg]->slug;
                        break;
                        case "category_name":
                            return $vars->metas["cats_vars"][$arg]->name;
                        break;
                        case "category_id":
                            return $vars->metas["cats_vars"][$arg]->tag_id;
                        break;
                        case "category_description":
                            return $vars->metas["cats_vars"][$arg]->description;
                        break;
                        case "ticket_price":
                            return Chronosly_dad_elements::chronosly_create_ticket_price($arg, $vars);
                        break;
                        case "ticket_name":
                            return Chronosly_dad_elements::chronosly_create_ticket_name($arg, $vars);
                        break;
                        case "ticket_capacity":
                            return Chronosly_dad_elements::chronosly_create_ticket_capacity($arg, $vars);
                        break;
                        case "ticket_min":
                            return Chronosly_dad_elements::chronosly_create_ticket_min_per_user($arg, $vars);
                        break;
                        case "ticket_max":
                            return Chronosly_dad_elements::chronosly_create_ticket_max_per_user($arg, $vars);
                        break;
                        case "ticket_start":
                            return Chronosly_dad_elements::chronosly_create_ticket_start($arg, $vars);
                        break;
                        case "ticket_end":
                            return Chronosly_dad_elements::chronosly_create_ticket_end($arg, $vars);
                        break;
                        case "ticket_notes":
                            return Chronosly_dad_elements::chronosly_create_ticket_notes($arg, $vars);
                        break;

                        case "ticket_link":
                        if(!$settings["chronosly_tickets"] || $vars->metas['tickets_vars'][$arg]["soldout"]) return "class='lorem'";
                            $link= $vars->metas['tickets_vars'][$arg]["link"];
                            if($link){
                                if(!stripos($link, "://")) $link ="http://$link";
                                return $link;

                            }
                            return "class='lorem'";
                        break;
                    }
                    // echo $cont;
                    return $id;
                break;

            }
             echo "not recognized function $func<br>";
        }

    }//End class

    class Chronosly_Templates_Vars{
        public $pid = "";
        public $post = "";
        public $vista = "";
        public $args = array();
        public $metas = array();

    }
}


?>