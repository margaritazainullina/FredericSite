<?php
if(!class_exists('Chronosly_Paint'))
{
	class Chronosly_Paint
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('init', array(&$this, 'admin_init'), 10);
        //	add_action('admin_menu', array(&$this, 'add_menu'));
			//add_action( 'admin_enqueue_scripts', array(&$this,'admin_template') );

		} // END public static function __construct
		
        /**
         * hook into WP's admin_init action hook
         */
        public  function admin_init()
        {
            // register your plugin's settings
        	/*register_setting('chronosly-group', 'chronosly-paint');
        	if(!get_option("chronosly-paint")){
				$settings = array(
                    //urls config
					"style-order" => array("color", "bold", "font-size", "font-family", "text-align", "text-decoration","background-color", "background-round","padding", "margin",
                                            "border-size", "border-color", "border-style", "css" ) //definimos el orden por defect de los inputs de paint styler



				);
				update_option('chronosly-paint', serialize($settings));
            }*/

                //text style
                add_filter("chronosly_style_box_text_fields", array("Chronosly_Paint", "color_field"), 10, 2);
                add_filter("chronosly_style_box_text_fields", array("Chronosly_Paint", "font_size_field"), 10, 2);
                add_filter("chronosly_style_box_text_fields", array("Chronosly_Paint", "font_family_field"), 10, 2);
                 add_filter("chronosly_style_box_text_fields", array("Chronosly_Paint", "bold_field"), 10, 2);
                 add_filter("chronosly_style_box_text_fields", array("Chronosly_Paint", "uppercase_field"), 10, 2);
                 add_filter("chronosly_style_box_text_fields", array("Chronosly_Paint", "text_align_field"), 10, 2);
                 add_filter("chronosly_style_box_text_fields", array("Chronosly_Paint", "text_decoration_field"), 10, 2);
                 // background style
                 add_filter("chronosly_style_box_background_fields", array("Chronosly_Paint", "background_color_field"), 10, 2);
                 add_filter("chronosly_style_box_background_fields", array("Chronosly_Paint", "background_round_field"), 10, 2);
                 //spacing style
                 add_filter("chronosly_style_box_spacing_fields", array("Chronosly_Paint", "padding_field"), 10, 2);
                 add_filter("chronosly_style_box_spacing_fields", array("Chronosly_Paint", "margin_field"), 10, 2);
                 //border style
                 add_filter("chronosly_style_box_border_fields", array("Chronosly_Paint", "border_color_field"), 10, 2);
                 add_filter("chronosly_style_box_border_fields", array("Chronosly_Paint", "border_size_field"), 10, 2);
                 add_filter("chronosly_style_box_border_fields", array("Chronosly_Paint", "border_style_field"), 10, 2);
                 //custom style
                 add_filter("chronosly_style_box_custom_fields", array("Chronosly_Paint", "custom_css_field"), 10, 2);






        } // END public static static function activate


        public static function default_style_boxes(){
            return apply_filters("chronosly_style_boxes", array("text" => "Text", "background" => "Background", "border" => "Border", "spacing" => "Spacing", "custom" => "Custom"));
        }


        public static function color_field($cont, $style){
            $value = "";
            $name = "color";
            if(isset($style[$name])) $value = $style[$name];
            $args = array(
                "label" => __("Color","chronosly"),
                "name" => $name,
                "type" => "input",
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("color"),
                "js_style_values" => array("value"),
                "php_style_values" => array("'value'")

            );
            return $cont.Chronosly_Extend::create_dad_field($args);

        }

        public static function font_size_field($cont, $style){
            $value = "";
            $name = "font-size";
            if(isset($style[$name])) $value = $style[$name];
            $options = array();
            for($i= 0; $i < 45; $i = $i+2){

                $options[($i+10)] = (10+$i);
            }
            $args = array(
                "label" => __("Text size","chronosly"),
                "name" => $name,
                "type" => "select",
                "options" => $options,
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("font-size", "line-height"),
                "js_style_values" => array("value+'px'", "(parseInt(value)+4)+'px'"),
                "php_style_values" => array("value.'px'", "(value+4).'px'")

            );
            return $cont.Chronosly_Extend::create_dad_field($args);
        }

        public static function font_family_field($cont, $style){
            $value = "";
            $name = "font-family";
            if(isset($style[$name])) $value = $style[$name];
            $options = array();
            //https://developers.google.com/fonts/docs/developer_api
            $options["Georgia, serif"] = "Georgia";
            $options["\"Palatino Linotype\", \"Book Antiqua\", Palatino, serif"] = "Book Antiqua"; //mirar lo de \ que no coincide en el selector
            $options["\"Times New Roman\", Times, serif"] = "Times New Roman";
            $options["Arial, Helvetica, sans-serif"] = "Arial";
            $options["\"Arial Black\", Gadget, sans-serif"] = "Arial Black";
            $options["\"Comic Sans MS\", cursive, sans-serif"] = "Comic Sans MS";
            $options["Impact, Charcoal, sans-serif"] = "Impact";
            $options["\"Lucida Sans Unicode\", \"Lucida Grande\", sans-serif"] = "Lucida Sans";
            $options["Tahoma, Geneva, sans-serif"] = "Tahoma";
            $options["\"Trebuchet MS\", Helvetica, sans-serif"] = "Trebuchet MS";
            $options["Verdana, Geneva, sans-serif"] = "Verdana";
            $options["\"Courier New\", Courier, monospace"] = "Courier New";
            $options["\"Lucida Console\", Monaco, monospace"] = "Lucida Console";
            asort($options);
            $args = array(
                "label" => __("Font family","chronosly"),
                "name" => $name,
                "type" => "select",
                "options" => $options,
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("font-family"),
                "js_style_values" => array("value"),
                "php_style_values" => array("'value'")

            );
            return $cont.Chronosly_Extend::create_dad_field($args);
        }

        public static function bold_field($cont, $style){
            $value = "";
            $name = "bold";
            if(isset($style[$name])) $value = $style[$name];
            $args = array(
                "label" => __("Bold","chronosly"),
                "name" => $name,
                "type" => "checkbox",
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("font-weight"),
                "js_style_values" => array("'bold'"),
                "php_style_values" => array("'bold'")

            );
            return $cont.Chronosly_Extend::create_dad_field($args);
        }

        public static function uppercase_field($cont, $style){
            $value = "";
            $name = "uppercase";
            if(isset($style[$name])) $value = $style[$name];
            $args = array(
                "label" => __("Uppercase","chronosly"),
                "name" => $name,
                "type" => "checkbox",
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("text-transform"),
                "js_style_values" => array("'uppercase'"),
                "php_style_values" => array("'uppercase'")

            );
            return $cont.Chronosly_Extend::create_dad_field($args);
        }

        public static function text_align_field($cont, $style){
            $value = "";
            $name = "text-align";
            if(isset($style[$name])) $value = $style[$name];
            $options = array();
            //https://developers.google.com/fonts/docs/developer_api
            $options["left"] = "left";
            $options["right"] = "right";
            $options["center"] = "center";
            $options["justify"] = "justify";

            //asort($options);
            $args = array(
                "label" => __("Align","chronosly"),
                "name" => $name,
                "type" => "select",
                "options" => $options,
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("text-align"),
                "js_style_values" => array("value"),
                "php_style_values" => array("'value'")

            );
            return $cont.Chronosly_Extend::create_dad_field($args);
        }

        public static function text_decoration_field($cont, $style){
            $value = "";
            $name = "text-decoration";
            if(isset($style[$name])) $value = $style[$name];
            $options = array();
            //https://developers.google.com/fonts/docs/developer_api
            $options["none"] = "none";
            $options["underline"] = "underline";
            $options["overline"] = "overline";
            $options["line-through"] = "line-through";

            //asort($options);
            $args = array(
                "label" => __("Decoration","chronosly"),
                "name" => $name,
                "type" => "select",
                "options" => $options,
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("text-decoration"),
                "js_style_values" => array("value"),
                "php_style_values" => array("'value'")

            );
            return $cont.Chronosly_Extend::create_dad_field($args);

        }

// background style

        public static function background_color_field($cont, $style){
            $value = "";
            $name = "background-color";
            if(isset($style[$name])) $value = $style[$name];
            $args = array(
                "label" => __("Background color","chronosly"),
                "name" => $name,
                "type" => "input",
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("background-color"),
                "js_style_values" => array("value"),
                "php_style_values" => array("'value'")

            );
            return $cont.Chronosly_Extend::create_dad_field($args);
        }

        public static function background_round_field($cont, $style){
            $value = "";
            $name = "background-round";
            if(isset($style[$name])) $value = $style[$name];
            $options = array();
            for($i= 0; $i < 31; $i = $i+5){

                $options[$i] = $i;
            }
            $args = array(
                "label" => __("Round","chronosly"),
                "name" => $name,
                "type" => "select",
                "options" => $options,
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("border-radius", "-moz-border-radius", "-webkit-border-radius"),
                "js_style_values" => array("value+'px'", "value+'px'", "value+'px'"),
                "php_style_values" => array("value.'px'", "value.'px'", "value.'px'")



            );
            return $cont.Chronosly_Extend::create_dad_field($args);
        }

//spacing style

        public static function padding_field($cont, $style){
            $value = "";
            $name = "padding";
            if(isset($style[$name])) $value = $style[$name];
            $options = array();
            for($i= 0; $i < 25; $i = $i+1){

                $options[$i] = $i;
            }
            $args = array(
                "label" => __("Padding","chronosly"),
                "name" => $name,
                "type" => "select",
                "options" => $options,
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("padding"),
                "js_style_values" => array("value+'%'"),
                "php_style_values" => array("value.'%'")


            );
            return $cont.Chronosly_Extend::create_dad_field($args);
        }

        public static function margin_field($cont, $style){
            $value = "";
            $name = "margin";
            if(isset($style[$name])) $value = $style[$name];
            $options = array();
            for($i= 0; $i < 25; $i = $i+1){

                $options[$i] = $i;
            }
            $args = array(
                "label" => __("Margin","chronosly"),
                "name" => $name,
                "type" => "select",
                "options" => $options,
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("margin"),
                "js_style_values" => array("value+'%'"),
                "php_style_values" => array("value.'%'")


            );
            return $cont.Chronosly_Extend::create_dad_field($args);
        }

//border style

        public static function border_color_field($cont, $style){
            $value = "";
            $name = "border-color";
            if(isset($style[$name])) $value = $style[$name];
            $args = array(
                "label" => __("Border color","chronosly"),
                "name" => $name,
                "type" => "input",
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("border-color"),
                "js_style_values" => array("value"),
                "php_style_values" => array("'value'")


            );
            return $cont.Chronosly_Extend::create_dad_field($args);
        }

        public static function border_size_field($cont, $style){
            $value = "";
            $name = "border-size";
            if(isset($style[$name])) $value = $style[$name];
            $options = array();
            for($i= 0; $i < 10; $i = $i+1){

                $options[$i] = $i;
            }
            $args = array(
                "label" => __("Border size","chronosly"),
                "name" => $name,
                "type" => "select",
                "options" => $options,
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("border-width"),
                "js_style_values" => array("value+'px'"),
                "php_style_values" => array("value.'px'")


            );
            return $cont.Chronosly_Extend::create_dad_field($args);
        }

        public static function border_style_field($cont, $style){
            $value = "";
            $name = "border-style";
            if(isset($style[$name])) $value = $style[$name];
            $options["dotted"]= "dotted";
            $options["dashed"]= "dashed";
            $options["solid"]= "solid";
            $options["double"]= "double";
            $options["groove"]= "groove";
            $options["ridge"]= "ridge";
            $options["inset"]= "inset";
            $options["outset"]= "outset";
            $args = array(
                "label" => __("Border style","chronosly"),
                "name" => $name,
                "type" => "select",
                "options" => $options,
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("border-style"),
                "js_style_values" => array("value"),
                "php_style_values" => array("'value'")


            );
            return $cont.Chronosly_Extend::create_dad_field($args);
        }

//custom style

        public static function custom_css_field($cont, $style){
            $value = "";
            $name = "css";
            if(isset($style[$name])) $value = $style[$name];

            $args = array(
                "label" => __("Custom css","chronosly"),
                "name" => $name,
                "type" => "textarea",
                "value" => $value,
                "el_type" => "style",
                "style_fields" => array("css"),
                "js_style_values" => array("value"),
                "php_style_values" => array("'value'")

            );
            return $cont.Chronosly_Extend::create_dad_field($args);
        }

		
		
    } // END 
} // END 


