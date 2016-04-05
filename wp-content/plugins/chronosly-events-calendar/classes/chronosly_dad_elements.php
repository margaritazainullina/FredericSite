<?php

if (!class_exists('Chronosly_Dad_Elements')) {
    class Chronosly_Dad_Elements

    {
        public

        function __construct()
        {
        } // END publicpublic static function __construct
        public static

        function set_new_bubble_box($type, $fields_array, $style)
        {
            $args = array(
                "name" => "box",
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        /* FUNCIONES DE LAS FIELDS POR DEFECTO */
        public

        function custom_text_field($default = array())
        {
            /* HTML contenido */
            if (isset($default['label'])) $label = $default['label'];
            else $label = __("Value", "chronosly");
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = "";
            $args = array(
                "name" => "custom_text",
                "label" => $label,
                "el_type" => "var",
                "type" => "input",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_custom_text_item"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_custom_text_item_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_custom_text_item($cont, $value, $vars, $html = 0)
        {
            if($html){
                if (stripos($cont, "#custom_text#")) return str_replace("#custom_text#", "{{ translate | $value}}" , $cont);
                if ($value) {
                    return "{{ translate | $value}}";
                }
            }

            if (stripos($cont, "#custom_text#")) return str_replace("#custom_text#", __($value, "chronosly") , $cont);
            if ($value) {
                return do_shortcode(__($value, "chronosly"));
            }

            return $cont;
        }

        static
        function create_custom_text_item_js($type)
        {
            switch ($type) {
            case "create":
                $return = 'if(val) {
                                var html = encodeURI(element.find(".ev-data").html());
                                element.find(".ev-data").attr("prev-html", html);
                                element.find(".ev-data").html(val);
                              }';
                break;

            case "modify":
                $return = '
                                if(val) {
                                var html = encodeURI(element.find(".ev-data").html());
                                if(typeof(element.find(".ev-data").attr("prev-html")) == "undefined") element.find(".ev-data").attr("prev-html", html);
                                element.find(".ev-data").html(val);
                              } else if(typeof(element.find(".ev-data").attr("prev-html")) != "undefined") {
                                element.find(".ev-data").html(decodeURI(element.find(".ev-data").attr("prev-html")));
                                element.find(".ev-data").removeAttr("prev-html");
                              }';
                break;
            }

            return $return;
        }

        public

        function custom_text_before_field($default = array())
        {
            /* HTML contenido */
            if (isset($default['label'])) $label = $default['label'];
            else $label = __("Text before", "chronosly");
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = "";
            $args = array(
                "name" => "custom_text_before",
                "label" => $label,
                "el_type" => "var",
                "type" => "input",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_custom_text_before_item"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_custom_text_before_item_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_custom_text_before_item($cont, $value, $vars, $html = 0)
        {
            if ($value) {
                if ($html) {
                    $pos = strrpos($cont, "}}");
                    if ($pos !== false) {
                        $cont = substr_replace($cont, " | text_before " .  $value . "}}", $pos, 2);
                    }


                    return $cont;
                }
                else if(trim($cont) and stripos($cont, "{{notprev}}") === FALSE ){

                    $value2 = __($value , "chronosly");
                    $value = str_replace(trim($value), $value2, $value);
                    return "<span class='before'>" .  $value . "</span> " . $cont;
                }
            }

            return $cont;
        }

        static
        function create_custom_text_before_item_js($type)
        {
            switch ($type) {
            case "create":
                $return = 'if(val) {
                                var html = element.find(".ev-data").html();
                                element.find(".ev-data").html("<span class=\'before\'>"+val+"</span>"+html);
                              }';
                break;

            case "modify":
                $return = '
                                if(val) {
                                var html = element.find(".ev-data").html();
                                    if(element.find(".ev-data span.before").length)  element.find(".ev-data span.before").html(val);
                                    else  element.find(".ev-data").html("<span class=\'before\'>"+val+"</span>"+html);
                              } else {
                                element.find(".ev-data span.before").remove();
                              }';
                break;
            }

            return $return;
        }

        public

        function custom_text_after_field($default = array())
        {
            /* HTML contenido */
            if (isset($default['label'])) $label = $default['label'];
            else $label = __("Text after", "chronosly");
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = "";
            $args = array(
                "name" => "custom_text_after",
                "label" => $label,
                "el_type" => "var",
                "type" => "input",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_custom_text_after_item"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_custom_text_after_item_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_custom_text_after_item($cont, $value, $vars, $html = 0)
        {
            if ($value) {

                if ($html) {
                    $pos = strrpos($cont, "}}");
                    if ($pos !== false) {
                        $cont = substr_replace($cont, " | text_after " .  $value . "}}", $pos, 2);
                    }

                    return $cont;
                }
                else if(trim($cont)) {
                    $value2 = __(trim($value) , "chronosly");
                    $value = str_replace(trim($value), $value2, $value);
                    return $cont . "<span class='after'>" .  $value . "</span> ";
                }
            }

            return $cont;
        }

        static
        function create_custom_text_after_item_js($type)
        {
            switch ($type) {
            case "create":
                $return = 'if(val) {
                                var html = element.find(".ev-data").html();
                                element.find(".ev-data").html(html+"<span class=\'after\'>"+val+"</span>");
                              }';
                break;

            case "modify":
                $return = '
                                if(val) {
                                var html = element.find(".ev-data").html();
                                    if(element.find(".ev-data span.after").length)  element.find(".ev-data span.after").html(val);
                                    else  element.find(".ev-data").html(html+"<span class=\'after\'>"+val+"</span>");
                              } else {
                                element.find(".ev-data span.after").remove();
                              }';
                break;
            }

            return $return;
        }

        public

        function custom_textarea_field($default = array())
        {
            /* HTML contenido */
            if (isset($default['label'])) $label = $default['label'];
            else $label = __("Custom Text", "chronosly");
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = "";
            $args = array(
                "name" => "custom_textarea",
                "label" => $label,
                "el_type" => "var",
                "type" => "textarea",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_custom_textarea_item"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_custom_textarea_item_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_custom_textarea_item($cont, $value, $vars, $html = 0)
        {
            if ($value) return __($value, "chronosly");
            return $cont;
        }

        static
        function create_custom_textarea_item_js($type)
        {
            switch ($type) {
            case "create":
                $return = 'if(val) {
                                var html = encodeURI(element.find(".ev-data").html());
                                element.find(".ev-data").attr("prev-html", html);
                                element.find(".ev-data").html(val);
                              }';
                break;

            case "modify":
                $return = '
                                if(val) {
                                var html = encodeURI(element.find(".ev-data").html());
                                if(typeof(element.find(".ev-data").attr("prev-html")) == "undefined") element.find(".ev-data").attr("prev-html", html);
                                element.find(".ev-data").html(val);
                              } else if(typeof(element.find(".ev-data").attr("prev-html")) != "undefined") {
                                element.find(".ev-data").html(decodeURI(element.find(".ev-data").attr("prev-html")));
                                element.find(".ev-data").removeAttr("prev-html");
                              }';
                break;
            }

            return $return;
        }

        public

        function custom_textbox_field($default = array())
        {
            /* HTML contenido */
            if (isset($default['label'])) $label = $default['label'];
            else $label = __("Custom Text", "chronosly");
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = "";
            $args = array(
                "name" => "custom_textbox",
                "label" => $label,
                "el_type" => "var",
                "type" => "wyswyg",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_custom_textbox_item"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_custom_textbox_item_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_custom_textbox_item($cont, $value, $vars, $html = 0)
        {
            if ($value) return __($value, "chronosly");
            return $cont;
        }

        static
        function create_custom_textbox_item_js($type)
        {
            switch ($type) {
            case "create":
                $return = 'if(val) {
                                var html = encodeURI(element.find(".ev-data").html());
                                element.find(".ev-data").attr("prev-html", html);
                                element.find(".ev-data").html(val);
                              }';
                break;

            case "modify":
                $return = '
                                if(val) {
                                var html = encodeURI(element.find(".ev-data").html());
                                if(typeof(element.find(".ev-data").attr("prev-html")) == "undefined") element.find(".ev-data").attr("prev-html", html);
                                element.find(".ev-data").html(val);
                              } else if(typeof(element.find(".ev-data").attr("prev-html")) != "undefined") {
                                element.find(".ev-data").html(decodeURI(element.find(".ev-data").attr("prev-html")));
                                element.find(".ev-data").removeAttr("prev-html");
                              }';
                break;
            }

            return $return;
        }

        public

        function readmore_check_field($default = array())
        {
            /* HTML contenido */
            if (isset($default['label'])) $label = $default['label'];
            else $label = __("Readmore link", "chronosly");
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = "";
            $args = array(
                "name" => "readmore_check",
                "label" => $label,
                "el_type" => "var",
                "type" => "checkbox",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_readmore_check_item"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_readmore_check_item_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_readmore_check_item($cont, $value, $vars, $html = 0)
        {
            if ($value and trim($cont)) {
                if ($ini = stripos($cont, "ch-organizer-")) {
                    $id = substr($cont, $ini + 13);
                    $id = substr($id, 0, stripos($id, "'"));
                    if ($html) return "<a href='{{organizer_link | id $id}}' class='ch-readmore'>$cont</a>";
                    return "<a href='" . get_post_permalink($id) . "' class='ch-readmore'>$cont</a>";
                }
                else
                if ($ini = stripos($cont, "ch-place-")) {
                    $id = substr($cont, $ini + 9);
                    $id = substr($id, 0, stripos($id, "'"));
                    if ($html) return "<a href='{{place_link | id $id}}' class='ch-readmore'>$cont</a>";
                    return "<a href='" . get_post_permalink($id) . "' class='ch-readmore'>$cont</a>";
                }
                else
                if ($ini = stripos($cont, "ch-address-")) {
                    $id = substr($cont, $ini + 11);
                    $id = substr($id, 0, stripos($id, "'"));
                    if ($html) return "<a href='{{place_link | id $id}}' class='ch-readmore'>$cont</a>";
                    return "<a href='" . get_post_permalink($id) . "' class='ch-readmore'>$cont</a>";
                }
                else
                if ($ini = stripos($cont, "ch-category-")) {

                    // revisar

                    $id = substr($cont, $ini + 12);
                    $id = substr($id, 0, stripos($id, "'"));
                    $link = get_term_link($id, "chronosly_category");
                    if (isset($link->errors)) return "<a href='' class='ch-readmore'>$cont</a>";
                    return "<a href='$link' class='ch-readmore'>$cont</a>";
                }

                if ($html) return "<a href='{{event_link}}' class='ch-readmore'>$cont</a>";
                return "<a href='" . str_replace("<span class='lorem'></span>", "", $vars->link) . "' class='ch-readmore'>$cont</a>";
            }
            else return $cont;
        }

        static
        function create_readmore_check_item_js($type)
        {
            switch ($type) {
            case "create":
                $return = '';
                break;

            case "modify":
                $return = '';
                break;
            }

            return $return;
        }

        public

        function readmore_text_field($default = array())
        {
            /* HTML contenido */
            if (isset($default['label'])) $label = $default['label'];
            else $label = __("Readmore text", "chronosly");
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = "more";
            $args = array(
                "name" => "readmore_text",
                "label" => $label,
                "el_type" => "var",
                "type" => "input",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_readmore_text_item"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_readmore_text_item_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_readmore_text_item($cont, $value, $vars, $html = 0)
        {
            if ($value) {
                $dom = new DOMDocument("1.0", "utf8");
                if (extension_loaded('mbstring')) @$dom->loadHTML(mb_convert_encoding($cont, 'HTML-ENTITIES', 'UTF-8'));
                else @$dom->loadHTML($cont);
                $xpath = new DOMXPath($dom);
                $link = $xpath->query("//a[@class='ch-readmore']");
                if ($link->length) {
                   if($html) $el = $dom->createElement("span", "{{translate | $value}}");
                   else $el = $dom->createElement("span", __($value, "chronosly"));
                    $at = $dom->createAttribute("class");
                    $at->value = "ch-more";
                    $el->appendChild($at);
                    $link->item(0)->appendChild($el);
                    $cont = $dom->saveHTML();
                    if ($html) $cont = urldecode(str_replace("+", "#plus#", $cont));
                    $cont = substr($cont, stripos($cont, "<body>") + 6);
                    $cont = substr($cont, 0, stripos($cont, "</body>"));
                    $cont = str_replace("#plus#", "+", $cont);
                }
            }

            return $cont;
        }

        static
        function create_readmore_text_item_js($type)
        {
            switch ($type) {
            case "create":
                $return = 'if(item.prevAll(".readmore_check").length) var show = item.prevAll(".readmore_check").is(":checked");
                                else var show = 1;
                                if(val && show){
                                    element.find(".ev-data").append(\'<span class="ch-more">\'+val+"</span>");
                                }';
                break;

            case "modify":
                $return = 'element.find(".ev-data span.ch-more").remove();
                                if(item.prevAll(".readmore_check").length) var show = item.prevAll(".readmore_check").is(":checked");
                                else var show = 1;
                                if(val && show){
                                    if(element.find(".ev-data .readmore").length) element.find(".ev-data .ch-readmore").append(\'<span class="ch-more">\'+val+"</span>");
                                    else element.find(".ev-data").append(\'<span class="ch-more">\'+val+"</span>");
                                }';
                break;
            }

            return $return;
        }

        public

        function readmore_action_field($default = array())
        {
            /* HTML contenido */
            if (isset($default['label'])) $label = $default['label'];
            else $label = __("Link action", "chronosly");
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = 1;
            if (!$default['options']) {
                $options[2] = "Open element page";
                $options[1] = "Open external page";
            }
            else $options = $default['options'];

            //  $options[2]= "Slide to show hidden boxes";

            $args = array(
                "name" => "readmore_action",
                "label" => $label,
                "el_type" => "var",
                "type" => "select",
                "options" => $options,
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_readmore_action_item"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_readmore_action_item_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_readmore_action_item($cont, $value, $vars, $html = 0)
        {   
            switch ($value) {
            case 1: //external url
                $dom = new DOMDocument("1.0", "utf8");
                if (extension_loaded('mbstring')) @$dom->loadHTML(mb_convert_encoding($cont, 'HTML-ENTITIES', 'UTF-8'));
                else @$dom->loadHTML($cont);
                $xpath = new DOMXPath($dom);
                $link = $xpath->query("//a[@class='ch-readmore']");
                if ($link->length) {
                    $link->item(0)->setAttribute('href', '#external_url#');
                    $cont = $dom->saveHTML();
                    if ($html) $cont = urldecode(str_replace("+", "#plus#", $cont));
                    $cont = substr($cont, stripos($cont, "<body>") + 6);
                    $cont = substr($cont, 0, stripos($cont, "</body>"));
                    $cont = str_replace("#plus#", "+", $cont);
                }

                break;

            case 3: //slide hidden box
                $dom = new DOMDocument("1.0", "utf8");
                if (extension_loaded('mbstring')) @$dom->loadHTML(mb_convert_encoding($cont, 'HTML-ENTITIES', 'UTF-8'));
                else @$dom->loadHTML($cont);
                $xpath = new DOMXPath($dom);
                $link = $xpath->query("//a[@class='ch-readmore']");
                if ($link->length) {
                    $link->item(0)->setAttribute('href', 'javascript:void(null)');
                    if ($html) $link->item(0)->setAttribute('onclick', 'ev_slide("{{id}}", this)');
                    else $link->item(0)->setAttribute('onclick', 'ev_slide("' . $vars->pid . '", this)');
                    $cont = $dom->saveHTML();
                    if ($html) $cont = urldecode(str_replace("+", "#plus#", $cont));
                    $cont = substr($cont, stripos($cont, "<body>") + 6);
                    $cont = substr($cont, 0, stripos($cont, "</body>"));
                    $cont = str_replace("#plus#", "+", $cont);
                }

                break;

            case 4:
                $dom = new DOMDocument("1.0", "utf8");
                if (extension_loaded('mbstring')) @$dom->loadHTML(mb_convert_encoding($cont, 'HTML-ENTITIES', 'UTF-8'));
                else @$dom->loadHTML($cont);
                $xpath = new DOMXPath($dom);
                $link = $xpath->query("//a[@class='ch-readmore']");
                if ($link->length) {

                    // print_r($vars->post->post_type);

                    $id = 0;
                    $repe = "";
                    $variable = "{{idurl}}";
                    $postype = "chronosly";
                    if ($ini = stripos($cont, "ch-organizer-")) {
                        $id = substr($cont, $ini + 13);
                        $id = substr($id, 0, stripos($id, "'"));
                        $variable = $id;
                        $postype = "chronosly_organizer";
                    }
                    else
                    if ($ini = stripos($cont, "ch-place-")) {
                        $id = substr($cont, $ini + 9);
                        $id = substr($id, 0, stripos($id, "'"));
                        $variable = $id;
                        $postype = "chronosly_places";
                    }
                    else
                    if ($ini = stripos($cont, "ch-address-")) {
                        $id = substr($cont, $ini + 11);
                        $id = substr($id, 0, stripos($id, "'"));
                        $variable = $id;
                        $postype = "chronosly_places";
                    }
                    else
                    if ($ini = stripos($cont, "ch-category-")) {

                        // revisar

                        $id = substr($cont, $ini + 12);
                        $id = substr($id, 0, stripos($id, "'"));
                    }

                    if (!$id) {
                        if ($vars->metas['repeat']) $repe = "&repeat=" . $vars->metas['repeat'];
                        $id = explode("_", $vars->pid);
                        $id = $id[0];
                    }

                    if ($html) $link->item(0)->setAttribute('href', "javascript:ev_popup('$variable&post_type=$postype')");
                    else $link->item(0)->setAttribute('href', 'javascript:ev_popup("' . $id . $repe . '&post_type=' . $vars->post->post_type . '")');
                    $cont = $dom->saveHTML();
                    if ($html) $cont = urldecode(str_replace("+", "#plus#", $cont));
                    $cont = substr($cont, stripos($cont, "<body>") + 6);
                    $cont = substr($cont, 0, stripos($cont, "</body>"));
                    $cont = str_replace("#plus#", "+", $cont);
                }

                break;
            }

            return $cont;
        }

        static
        function create_readmore_action_item_js($type)
        {
            switch ($type) {
            case "create":
                $return = '';
                break;

            case "modify":
                $return = '';
                break;
            }

            return $return;
        }

        public

        function external_url_field($default = array())
        {
            /* HTML contenido */
            if (isset($default['label'])) $label = $default['label'];
            else $label = __("External url", "chronosly");
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = "";
            $args = array(
                "name" => "external_url",
                "label" => $label,
                "el_type" => "var",
                "type" => "input",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_external_url_item"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_external_url_item_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_external_url_item($cont, $value, $vars, $html = 0)
        {
            if ($value) {
                if (!stripos($value, ":") && stripos($value, "#") != 0) $value = "http://$value";
                return str_replace("#external_url#", $value, $cont);
            }

            return $cont;
        }

        static
        function create_external_url_item_js($type)
        {
            switch ($type) {
            case "create":
                $return = '';
                break;

            case "modify":
                $return = '';
                break;
            }

            return $return;
        }

        public

        function target_blank_field($default = array())
        {
            /* HTML contenido */
            if (isset($default['label'])) $label = $default['label'];
            else $label = __("Open in a new page", "chronosly");
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = 1;
            $args = array(
                "name" => "target_blank",
                "label" => $label,
                "el_type" => "var",
                "type" => "checkbox",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_target_blank_item"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_target_blank_item_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_target_blank_item($cont, $value, $vars, $html = 0)
        {
            if ($value) {
                $dom = new DOMDocument("1.0", "utf8");
                if (extension_loaded('mbstring')) @$dom->loadHTML(mb_convert_encoding($cont, 'HTML-ENTITIES', 'UTF-8'));
                else @$dom->loadHTML($cont);
                $xpath = new DOMXPath($dom);
                $link = $xpath->query("//a[@class='ch-readmore']");
                if ($link->length) $link->item(0)->setAttribute('target', '_blank');

                // if($link->length) $link->item(0)->setAttribute('href', $link->item(0)->getAttribute('href'));

                $cont = $dom->saveHTML();
                if ($html) $cont = urldecode(str_replace("+", "#plus#", $cont));
                $cont = substr($cont, stripos($cont, "<body>") + 6);
                $cont = substr($cont, 0, stripos($cont, "</body>"));
                $cont = str_replace("#plus#", "+", $cont);
            }

            return $cont;
        }

        static
        function create_target_blank_item_js($type)
        {
            switch ($type) {
            case "create":
                $return = '';
                break;

            case "modify":
                $return = '';
                break;
            }

            return $return;
        }

        public

        function nofollow_field($default = array())
        {
            /* HTML contenido */
            if (isset($default['label'])) $label = $default['label'];
            else $label = __("Nofollow", "chronosly");
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = 0;
            $args = array(
                "name" => "nofollow",
                "label" => $label,
                "el_type" => "var",
                "type" => "checkbox",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_nofollow_item"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_nofollow_item_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_nofollow_item($cont, $value, $vars, $html = 0)
        {
            if ($value) {
                $dom = new DOMDocument("1.0", "utf8");
                if (extension_loaded('mbstring')) @$dom->loadHTML(mb_convert_encoding($cont, 'HTML-ENTITIES', 'UTF-8'));
                else @$dom->loadHTML($cont);
                $xpath = new DOMXPath($dom);
                $link = $xpath->query("//a[@class='ch-readmore']");
                if ($link->length) $link->item(0)->setAttribute('rel', 'nofollow');
                $cont = $dom->saveHTML();
                if ($html) $cont = urldecode(str_replace("+", "#plus#", $cont));
                $cont = substr($cont, stripos($cont, "<body>") + 6);
                $cont = substr($cont, 0, stripos($cont, "</body>"));
                $cont = str_replace("#plus#", "+", $cont);
            }

            return $cont;
        }

        static
        function create_nofollow_item_js($type)
        {
            switch ($type) {
            case "create":
                $return = '';
                break;

            case "modify":
                $return = '';
                break;
            }

            return $return;
        }

        public

        function shorten_text_field($default = array())
        {
            /* HTML funcion | shorten chars */
            if (isset($default['label'])) $label = $default['label'];
            else $label = __("Max chars to show", "chronosly");
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = "";
            $args = array(
                "name" => "shorten_text",
                "label" => $label,
                "el_type" => "var",
                "type" => "input",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_shorten_text_item"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_shorten_text_item_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function html_cut($text, $max_length)
        {

            // echo "$text $max_length";

            $tags = array();
            $result = "";
            $is_open = false;
            $grab_open = false;
            $is_close = false;
            $in_double_quotes = false;
            $in_single_quotes = false;
            $same_line = false;
            $tag = "";
            $i = 0;
            $stripped = 0;
            $topen = 0;
            $stripped_text = strip_tags($text);
            if ($negative) --$negative; //para los \'  o \"
            while ($is_open || ($i < strlen($text) && $stripped < strlen($stripped_text) && $stripped < $max_length)) {
                $symbol = $text{$i};
                $result.= $symbol;
                switch ($symbol) {
                case '<':
                    $is_open = true;
                    $grab_open = true;
                    break;

                case '"':
                    if (!$negative) {
                        if ($in_double_quotes) $in_double_quotes = false;
                        else $in_double_quotes = true;
                    }

                    break;

                case "'":
                    if (!$negative) {
                        if ($in_single_quotes) $in_single_quotes = false;
                        else $in_single_quotes = true;
                    }

                    break;

                case '/':
                    if ($is_open && !$in_double_quotes && !$in_single_quotes) {
                        $is_close = true;
                        $is_open = false;
                        $grab_open = false;
                    }

                    break;

                case '\\':
                    $negative = 2;
                    break;

                case ' ':
                    if ($is_open) $grab_open = false;
                    else $stripped++;
                    break;

                case '>':
                    if ($is_open) {
                        $is_open = false;
                        $grab_open = false;
                        array_push($tags, $tag);
                        $pushed = true;

                        // echo "empieza $tag<br/>";

                        $tag = "";

                        // print_r($tags);

                    }
                    else
                    if ($is_close) {
                        $is_close = false;
                        $old_tag = array_pop($tags);
                        if ($tag != $old_tag) array_push($tags, $old_tag);

                        // echo "acaba $tag<br/>";
                        // print_r($tags);

                        $tag = "";
                    }

                    break;

                default:
                    if ($grab_open || $is_close) $tag.= $symbol;
                    if (!$is_open && !$is_close) $stripped++;
                }

                $i++;
            }

            $result = $result . "...";

            // print_r($tags);

            while ($tags) $result.= "</" . array_pop($tags) . ">";
            return $result;
        }

        static
        function create_shorten_text_item($cont, $value, $vars, $html = 0)
        {
            if ($html) return str_replace("}}", " | shorten_text $value}}", $cont);
            if ($value and strlen($cont) > $value) {

                // $cont = substr($cont, 0, $value);

                $cont = Chronosly_Dad_Elements::html_cut($cont, $value);

                // //substituimos cualquier htmlentitie que quede al final
                // $fin = strripos($cont, "&");
                // if( $fin > strlen($cont)-5) $cont = substr($cont, 0, $fin);

            }

            return $cont;
        }

        static
        function create_shorten_text_item_js($type)
        {
            switch ($type) {
            case "create":
                $return = 'if(val){
                                    var html = element.find(".ev-data").html();

                                    if(val && val < html.length) {
                                        element.find(".ev-data").attr("prev-html", encodeURI(html));
                                        element.find(".ev-data").html(html.substring(0,val));
                                        element.find(".ev-data").html(element.find(".ev-data").html().substring(0,html.lastIndexOf(" "))+"...");
                                        if( element.find(".ev-data").html().indexOf("<p>") >= 0)  element.find(".ev-data").append("</p>");
                                      }
                                }';
                break;

            case "modify":
                $return = 'if(val) {
                                    var html = element.find(".ev-data").html();
                                    if(typeof(element.find(".ev-data").attr("prev-html")) == "undefined") element.find(".ev-data").attr("prev-html",encodeURI(html));
                                    else html = decodeURI(element.find(".ev-data").attr("prev-html"));
                                     if(val && val < html.length) {
                                        element.find(".ev-data").html(html.substring(0,val));
                                        element.find(".ev-data").html(element.find(".ev-data").html().substring(0,html.lastIndexOf(" "))+"...");
                                        if( element.find(".ev-data").html().indexOf("<p>") >= 0)  element.find(".ev-data").append("</p>");
                                      } else element.find(".ev-data").html(html);
                              } else if(typeof(element.find(".ev-data").attr("prev-html")) != "undefined") {
                                    element.find(".ev-data").html(decodeURI(element.find(".ev-data").attr("prev-html")));
                                    element.find(".ev-data").removeAttr("prev-html");
                              }';
                break;
            }

            return $return;
        }

        public

        function upload_image_field($default = array())
        {
            /* HTML contenido */
            if (isset($default['label'])) $label = $default['label'];
            else $label = "";
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = "";
            $args = array(
                "name" => "upload_image",
                "label" => $label,
                "el_type" => "var",
                "type" => "image",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_upload_image"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_upload_image_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function chronosly_create_upload_image($cont, $value, $vars, $html = 0)
        {
            return $cont . '<img width="100%" height="auto" src="' . $value . '"  />';
        }

        static
        function chronosly_create_upload_image_js($type)
        {
            switch ($type) {
            case "create":
                $return = 'if(val) element.find(".ev-data").html("<img width=\'100%\' height=\'auto\' src=\'"+val+"\' />");else element.find(".ev-data").html("Upload an Image")';
                break;

            case "modify":
                $return = 'el.children(".ev-data").html("<img width=\'100%\' height=\'auto\' src=\'"+val+"\' />");';
                break;
            }

            return $return;
        }

        public

        function upload_gallery_field($default = array())
        {
            /* HTML contenido */
            if (isset($default['label'])) $label = $default['label'];
            else $label = "";
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = "";
            $args = array(
                "name" => "upload_gallery",
                "label" => $label,
                "el_type" => "var",
                "type" => "gallery",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_upload_gallery"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_upload_gallery_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function chronosly_create_upload_gallery($cont, $value, $vars, $html = 0)
        {
            return $cont . do_shortcode($value);
        }

        static
        function chronosly_create_upload_gallery_js($type)
        {
            $return = 'element.find(".ev-data").html("Make a gallery and save to view it")';
            return $return;
        }

        public

        function time_format_field($default = array())
        {
            /* HTML funcion | time_format format */
            if (isset($default['label'])) $label = $default['label'];
            else $label = __("Time Format", "chronosly");
            if (isset($default['order'])) $order = $default['order'];
            else $order = 3;
            if (isset($default['value'])) $value = $default['value'];
            else $value = "";
            $time = "time_format";
            if (isset($default['time'])) {
                $time = $default['time'];
            }

            $args = array(
                "name" => "time_format",
                "label" => $label,
                "el_type" => "var",
                "type" => "time",
                "extra" => $time,
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_time_format_item"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_time_format_item_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        public static function create_time_format_item($cont, $value, $vars, $html = 0)
        {
            $settings = unserialize(get_option("chronosly-settings"));
            $extra = $value["extra"];
            if (stripos(strftime("%#d") , "#") === FALSE) $value = str_replace("%e", "%#d", $value["value"]);
            else $value = $value["value"];

            // print_r($vars->metas["ev-from"]);
            $lorem = 0;

            switch ($extra) {
            case "full_datetime":
                if ($html) {
                    $cont = str_replace("}}", " | time_format $value}}", $cont);
                }
                else {
                    if (!$value) $value = $settings["chronosly_format_date_time"];
                    $date1 = $vars->metas["ev-from"][0];
                    if(!isset($vars->metas["events"][0]) or $vars->metas["events"][0] == "") $date1 .= " " . $vars->metas["ev-from-h"][0] . ":" . $vars->metas["ev-from-m"][0];
                    $date2 = $vars->metas["ev-to"][0];
                    if(!isset($vars->metas["events"][0]) or $vars->metas["events"][0] == "") $date2 .= " " . $vars->metas["ev-to-h"][0] . ":" . $vars->metas["ev-to-m"][0];
                    $date1 = str_replace("<span class='lorem'></span>", "", $date1, $lorem);
                    $date2 = str_replace("<span class='lorem'></span>", "", $date2, $lorem);
                    if (stripos($vars->metas["ev-from"][0], "'lorem'") === FALSE) $lorem = 0;
                    $date1o = strtotime($date1);
                    $date2o = strtotime($date2);
                    if (stripos($value, "%") === FALSE) {
                        if(date_i18n($value, $date1o) != date_i18n($value, $date2o)) {
                            if($vars->metas["ev-from"][0] != $vars->metas["ev-to"][0]) $cont.= date_i18n($value, $date1o) . " " . __($settings["chronosly_full_datetime_separator"] , "chronosly"). " " . date_i18n($value, $date2o);
                            else $cont= date_i18n($value, $date1o) . " " . __($settings["chronosly_full_datetime_separator"] , "chronosly")." ". date_i18n( $settings["chronosly_format_time"], $date2o);
                        }
                        else $cont.= date_i18n($value, $date1o);
                    } else {
                        if(strftime($value, $date1o) != strftime($value, $date2o)) $cont.= strftime($value, $date1o) . " " .__($settings["chronosly_full_datetime_separator"] , "chronosly") . " " . strftime($value, $date2o);
                        else $cont= strftime($value, $date1o);
                    }
                }

                break;

            case "full_date":
                if ($html) {
                    $cont = str_replace("}}", " | time_format $value}}", $cont);
                }
                else {
                    if (!$value) $value = $settings["chronosly_format_date"];
                    $date1 = $vars->metas["ev-from"][0] ;
                    if(!isset($vars->metas["events"][0]) or $vars->metas["events"][0] == "") $date1 .= " " . $vars->metas["ev-from-h"][0] . ":" . $vars->metas["ev-from-m"][0];
                    $date2 = $vars->metas["ev-to"][0] ;
                    if(!isset($vars->metas["events"][0]) or $vars->metas["events"][0] == "") $date2 .= " " . $vars->metas["ev-to-h"][0] . ":" . $vars->metas["ev-to-m"][0];
                    $date1 = str_replace("<span class='lorem'></span>", "", $date1, $lorem);
                    $date2 = str_replace("<span class='lorem'></span>", "", $date2, $lorem);
                    if (stripos($vars->metas["ev-from"][0], "'lorem'")) $lorem = 0;
                    $date1o = strtotime($date1);
                    $date2o = strtotime($date2);
                    if (stripos($value, "%") === FALSE) {
                        if ($vars->metas["ev-from"][0] and stripos($vars->metas["ev-from"][0], "'lorem'") === FALSE) $cont.= date_i18n($value, $date1o);
                        if ($vars->metas["ev-from"][0] != $vars->metas["ev-to"][0] and $vars->metas["ev-to"][0] and stripos($vars->metas["ev-to"][0], "'lorem'") === FALSE) $cont.= " " . __($settings["chronosly_full_datetime_separator"], "chronosly") . " " . date_i18n($value, $date2o);
                        else $cont = "{{notprev}}".date_i18n($value, $date1o);
                    }
                    else {
                        if ($vars->metas["ev-from"][0] and stripos($vars->metas["ev-from"][0], "'lorem'") === FALSE) $cont.= strftime($value, $date1o);
                        if ($vars->metas["ev-from"][0] != $vars->metas["ev-to"][0] and $vars->metas["ev-to"][0] and stripos($vars->metas["ev-to"][0], "'lorem'") === FALSE) $cont.= " " . __($settings["chronosly_full_datetime_separator"], "chronosly") . " " . strftime($value, $date2o);
                        else $cont = "{{notprev}}".date_i18n($value, $date1o);
                    }
                }

                break;

            case "full_time":
                if ($html) {
                    $cont = str_replace("}}", " | time_format $value}}", $cont);
                }
                else {

                    if (!$value) $value = $settings["chronosly_format_time"];
                    if(!isset($vars->metas["events"][0]) or $vars->metas["events"][0] == "") {
                        $date1 = $vars->metas["ev-from"][0] ;
                        if(!isset($vars->metas["events"][0]) or $vars->metas["events"][0] == "") $date1 .= " " . $vars->metas["ev-from-h"][0] . ":" . $vars->metas["ev-from-m"][0];
                        $date2 = $vars->metas["ev-to"][0] ;
                        if(!isset($vars->metas["events"][0]) or $vars->metas["events"][0] == "") $date2 .= " " . $vars->metas["ev-to-h"][0] . ":" . $vars->metas["ev-to-m"][0];

                        $date1 = str_replace("<span class='lorem'></span>", "", $date1, $lorem);
                        $date2 = str_replace("<span class='lorem'></span>", "", $date2, $lorem);
                        if (stripos($vars->metas["ev-from"][0], "'lorem'") === FALSE) $lorem = 0;
                        $date1o = strtotime($date1);
                        $date2o = strtotime($date2);
                        if (stripos($value, "%") === FALSE) {
                            if ($vars->metas["ev-from-h"][0] and stripos($vars->metas["ev-from-h"][0], "'lorem'") === FALSE) $cont.= date_i18n($value, $date1o);
                            if ($vars->metas["ev-to-h"][0] and stripos($vars->metas["ev-to-h"][0], "'lorem'") === FALSE) $cont.= " " . __($settings["chronosly_full_datetime_separator"] , "chronosly"). " " . date_i18n($value, $date2o);
                        }
                        else {
                            if ($vars->metas["ev-from-h"][0] and stripos($vars->metas["ev-from-h"][0], "'lorem'") === FALSE) $cont.= strftime($value, $date1o);
                            if ($vars->metas["ev-to-h"][0] and stripos($vars->metas["ev-to-h"][0], "'lorem'") === FALSE) $cont.= " " . __($settings["chronosly_full_datetime_separator"] , "chronosly") . " " . strftime($value, $date2o);
                        }

                        if (!$cont) $cont = __("All day long", "chronosly");
                    } else {

                         $sets = unserialize(get_option("chronosly_settings_tickets_and_repeats_extended"));

                          if(!$sets["seasons_normal_display"]){
                            $tickets = unserialize(get_option("chronosly_settings_tickets_and_repeats_extended"));
                            if(stripos($vars->metas["ev-from-h"][0], ",")) $cont = __($tickets["event_repeats_seasons_name"], "chronosly")." ";
                            $s = json_decode($vars->metas["ev-from-h"][0]);
                            foreach($s as $id=>$s1) if($s1 == ":") $s[$id] = __("All day long", "chronosly");
                            if(is_array($s)) $cont .= implode(", ", $s);
                            else $cont .=  $s;
                        } else {

                            $date1 = $vars->metas["ev-from"][0] . " " . $vars->metas["ev-from-h"][0] . ":" . $vars->metas["ev-from-m"][0];

                            if($_REQUEST["repeat"]){
                                $ses = explode("_", $_REQUEST["repeat"]);
                                if(count($ses) == 4) $date1 = $vars->metas["ev-from"][0] . " " . $ses[2] . ":" . $ses[3];
                            }else {

                                $date1 = $vars->metas["ev-from"][0] . " " . $vars->metas["ev-from-h"][0] . ":" . $vars->metas["ev-from-m"][0];
                            }
                            $date1 = str_replace("<span class='lorem'></span>", "", $date1, $lorem);
                            if (stripos($vars->metas["ev-from-h"][0], "'lorem'") === FALSE) $lorem = 0;
                            $date1o = strtotime($date1);
                            if (stripos($value, "%") === FALSE) $cont.= date_i18n($value, $date1o);
                            else $cont.= strftime($value, $date1o);
                        }
                      
                    }
                }

                break;

            case "start_datetime":
                if ($html) {
                    $cont = str_replace("}}", " | time_format $value}}", $cont);
                }
                else {
                     if(!isset($vars->metas["events"][0]) or $vars->metas["events"][0] == "" || !stripos($vars->metas["ev-from-h"][0], "]")) {
                        if (!$value) $value = $settings["chronosly_format_date_time"];
                        $date1 = $vars->metas["ev-from"][0] ;
                       $date1 .= " " . $vars->metas["ev-from-h"][0] . ":" . $vars->metas["ev-from-m"][0];
                        $date1 = str_replace("<span class='lorem'></span>", "", $date1, $lorem);
                        if (stripos($vars->metas["ev-from"][0], "'lorem'") === FALSE) $lorem = 0;
                        $date1o = strtotime($date1);
                        if (stripos($value, "%") === FALSE) $cont.= date_i18n($value, $date1o);
                        else $cont.= strftime($value, $date1o);
                    } else {
                         $sets = unserialize(get_option("chronosly_settings_tickets_and_repeats_extended"));
                      
                        if(!$sets["seasons_normal_display"]){
                             $tickets = unserialize(get_option("chronosly_settings_tickets_and_repeats_extended"));
                            if(stripos($vars->metas["ev-from-h"][0], ",")) $cont = __($tickets["event_repeats_seasons_name"], "chronosly")." ";

                            $s = json_decode($vars->metas["ev-from-h"][0]);
                            if(is_array($s)) $cont .= implode(", ", $s);
                            else $cont .=  $s;
                        } else {
                            $date1 = $vars->metas["ev-from"][0] . " " . $vars->metas["ev-from-h"][0] . ":" . $vars->metas["ev-from-m"][0];
                            $date1 = str_replace("<span class='lorem'></span>", "", $date1, $lorem);
                            if (stripos($vars->metas["ev-from-h"][0], "'lorem'") === FALSE) $lorem = 0;
                            $date1o = strtotime($date1);
                            if (stripos($value, "%") === FALSE) $cont.= date_i18n($value, $date1o);
                            else $cont.= strftime($value, $date1o);


                        }

                         
                    }
                }

                break;

            case "end_datetime":
                if ($html) {
                    $cont = str_replace("}}", " | time_format $value}}", $cont);
                }
                else {
                    if (!$value) $value = $settings["chronosly_format_date_time"];
                    $date1 = $vars->metas["ev-to"][0];
                    if(!isset($vars->metas["events"][0]) or $vars->metas["events"][0] == "") $date1 .= " " . $vars->metas["ev-to-h"][0] . ":" . $vars->metas["ev-to-m"][0];
                    $date1 = str_replace("<span class='lorem'></span>", "", $date1, $lorem);
                    if (stripos($vars->metas["ev-to"][0], "'lorem'") === FALSE) $lorem = 0;
                    $date1o = strtotime($date1);
                    if (stripos($value, "%") === FALSE) $cont.= date_i18n($value, $date1o);
                    else $cont.= strftime($value, $date1o);
                }

                break;

            case "start_date":
                if ($html) {
                    $cont = str_replace("}}", " | time_format $value}}", $cont);
                }
                else {
                    if (!$value) $value = $settings["chronosly_format_date"];
                    $date1 = $vars->metas["ev-from"][0];
                    // echo "<pre>";print_r($vars->metas);
                    if(!isset($vars->metas["events"][0]) or $vars->metas["events"][0] == "") $date1 .= " " . $vars->metas["ev-from-h"][0] . ":" . $vars->metas["ev-from-m"][0];
                    $date1 = str_replace("<span class='lorem'></span>", "", $date1, $lorem);
                    if (stripos($vars->metas["ev-from"][0], "'lorem'") === FALSE) $lorem = 0;
                    $date1o = strtotime($date1);
                    if (stripos($value, "%") === FALSE) $cont.= date_i18n($value, $date1o);

                    else $cont.= strftime($value, $date1o);
                }

                break;

            case "end_date":
                if ($html) {
                    $cont = str_replace("}}", " | time_format $value}}", $cont);
                }
                else {
                    if (!$value) $value = $settings["chronosly_format_date"];
                    $date1 = $vars->metas["ev-to"][0]; 
                    if(!isset($vars->metas["events"][0]) or $vars->metas["events"][0] == "") $date1 .= " " . $vars->metas["ev-to-h"][0] . ":" . $vars->metas["ev-to-m"][0];
                    $date1 = str_replace("<span class='lorem'></span>", "", $date1, $lorem);
                    if (stripos($vars->metas["ev-to"][0], "'lorem'") === FALSE) $lorem = 0;
                    $date1o = strtotime($date1);
                    if (stripos($value, "%") === FALSE) $cont.= date_i18n($value, $date1o);
                    else $cont.= strftime($value, $date1o);
                }

                break;

            case "start_hour":
                if ($html) {
                    $cont = str_replace("}}", " | time_format $value}}", $cont);
                }
                else {
                    if(!isset($vars->metas["events"][0]) or $vars->metas["events"][0] == "" || !stripos($vars->metas["ev-from-h"][0], "]")) {
                        if (!$value) $value = $settings["chronosly_format_time"];
                        $date1 = $vars->metas["ev-from"][0] . " " . $vars->metas["ev-from-h"][0] . ":" . $vars->metas["ev-from-m"][0];
                        $date1 = str_replace("<span class='lorem'></span>", "", $date1, $lorem);
                        if (stripos($vars->metas["ev-from-h"][0], "'lorem'") === FALSE) $lorem = 0;
                        $date1o = strtotime($date1);
                        if (stripos($value, "%") === FALSE) $cont.= date_i18n($value, $date1o);
                        else $cont.= strftime($value, $date1o);
                    } else {

                         $sets = unserialize(get_option("chronosly_settings_tickets_and_repeats_extended"));
                      
                        if(!$sets["seasons_normal_display"]){
                             $tickets = unserialize(get_option("chronosly_settings_tickets_and_repeats_extended"));
                            if(stripos($vars->metas["ev-from-h"][0], ",")) $cont = __($tickets["event_repeats_seasons_name"], "chronosly")." ";
    
                           
                            $s = json_decode($vars->metas["ev-from-h"][0]);

                            // $m = json_decode($vars->metas["ev-from-m"][0]);
                            if(is_array($s)) {
                                foreach ($s as $v) {
                                    if($v == ":") $cont .= __("All day long", "chronosly").", ";
                                    else $cont .= $v.", ";
                                }
                                $cont = substr($cont, 0,-2);
                            }
                            else {
                               if($s == ":") $cont .= __("All day long", "chronosly");
                               else $cont .= $s;
                            }

                        } else {
                            $date1 = $vars->metas["ev-from"][0] . " " . $vars->metas["ev-from-h"][0] . ":" . $vars->metas["ev-from-m"][0];
                            $date1 = str_replace("<span class='lorem'></span>", "", $date1, $lorem);
                            if (stripos($vars->metas["ev-from-h"][0], "'lorem'") === FALSE) $lorem = 0;
                            $date1o = strtotime($date1);
                            if (stripos($value, "%") === FALSE) $cont.= date_i18n($value, $date1o);
                            else $cont.= strftime($value, $date1o);
                        }
                        // print_r($vars->metas["ev-from-h"][0]);

                    }
                }

                break;

            case "end_hour":
                if ($html) {
                    $cont = str_replace("}}", " | time_format $value}}", $cont);
                }
                else {
                    if (!$value) $value = $settings["chronosly_format_time"];
                    $date1 = $vars->metas["ev-to"][0] . " " . $vars->metas["ev-to-h"][0] . ":" . $vars->metas["ev-to-m"][0];
                    $date1 = str_replace("<span class='lorem'></span>", "", $date1, $lorem);
                    if (stripos($vars->metas["ev-to-h"][0], "'lorem'") === FALSE) $lorem = 0;
                    $date1o = strtotime($date1);
                    if (stripos($value, "%") === FALSE) $cont.= date_i18n($value, $date1o);
                 
                }

                break;
            }

            if ($lorem and (stripos($vars->metas["ev-from"][0], "'lorem'") !== FALSE or stripos($vars->metas["ev-to"][0], "'lorem'") !== FALSE)) {
                $cont.= "<span class='lorem'></span>";
            }

            return $cont;
        }

        static
        function create_time_format_item_js($type)
        {
            $settings = unserialize(get_option("chronosly-settings"));
            $return = 'var time ="";
                        var lorem = 0;
                       switch(item.attr("extra")){
                            case "full_datetime":
                                if(!val) val = "' . $settings["chronosly_format_date_time"] . '";
                                original = jQuery(".chronosly-defaults #chronosly-start-date").html();
                                if(original.indexOf("lorem")>0){
                                    original = jQuery(".chronosly-defaults #chronosly-start-date").text();
                                    lorem = 1;
                                }
                                var d1 = new Date(parseInt(original-(60*60*2))*1000);
                                original = jQuery(".chronosly-defaults #chronosly-end-date").html();
                                if(original.indexOf("lorem")>0){
                                    original =jQuery(".chronosly-defaults #chronosly-end-date").text();
                                    lorem = 1;
                                }
                                var d2 = new Date(parseInt(original-(60*60*2))*1000);
                                if(val.indexOf("%") < 0) time = jQuery.format.date(d1, val)+" ' . $settings["chronosly_full_datetime_separator"] . ' "+jQuery.format.date(d2,val);
                                else time = d1.strftime(val)+" ' . $settings["chronosly_full_datetime_separator"] . ' "+d2.strftime(val);
                         break;
                          case "full_date":
                                if(!val) val = "' . $settings["chronosly_format_date"] . '";
                                original = jQuery(".chronosly-defaults #chronosly-start-date").html();
                                if(original.indexOf("lorem")>0){
                                    original = jQuery(".chronosly-defaults #chronosly-start-date").text();
                                    lorem = 1;
                                }
                                var d1 = new Date(parseInt(original-(60*60*2))*1000);
                                original = jQuery(".chronosly-defaults #chronosly-end-date").html();
                                if(original.indexOf("lorem")>0){
                                    original =jQuery(".chronosly-defaults #chronosly-end-date").text();
                                    lorem = 1;
                                }
                                var d2 = new Date(parseInt(original-(60*60*2))*1000);
                                if(val.indexOf("%") < 0) time = jQuery.format.date(d1, val)+" ' . $settings["chronosly_full_datetime_separator"] . ' "+jQuery.format.date(d2,val);
                                else time = d1.strftime(val)+" ' . $settings["chronosly_full_datetime_separator"] . ' "+d2.strftime(val);
                         break;
                          case "full_time":
                                if(!val) val = "' . $settings["chronosly_format_time"] . '";
                                original = jQuery(".chronosly-defaults #chronosly-start-date").html();
                                if(original.indexOf("lorem")>0){
                                    original = jQuery(".chronosly-defaults #chronosly-start-date").text();
                                    lorem = 1;
                                }
                                var d1 = new Date(parseInt(original-(60*60*2))*1000);
                                original = jQuery(".chronosly-defaults #chronosly-end-date").html();
                                if(original.indexOf("lorem")>0){
                                    original =jQuery(".chronosly-defaults #chronosly-end-date").text();
                                    lorem = 1;
                                }
                                var d2 = new Date(parseInt(original-(60*60*2))*1000);
                                if(val.indexOf("%") < 0) time = jQuery.format.date(d1, val)+" ' . $settings["chronosly_full_datetime_separator"] . ' "+jQuery.format.date(d2,val);
                                else time = d1.strftime(val)+" ' . $settings["chronosly_full_datetime_separator"] . ' "+d2.strftime(val);
                         break;
                          case "start_datetime":
                                if(!val) val = "' . $settings["chronosly_format_date_time"] . '";
                                original = jQuery(".chronosly-defaults #chronosly-start-date").html();
                                 if(original.indexOf("lorem")>0){
                                    original = jQuery(".chronosly-defaults #chronosly-start-date").text();
                                    lorem = 1;
                                }
                                var d1 = new Date(parseInt(original-(60*60*2))*1000);

                                time = d1.strftime(val);
                         break;
                         case "end_datetime":
                                if(!val) val = "' . $settings["chronosly_format_date_time"] . '";
                                original = jQuery(".chronosly-defaults #chronosly-end-date").html();
                                 if(original.indexOf("lorem")>0){
                                    original = jQuery(".chronosly-defaults #chronosly-end-date").text();
                                    lorem = 1;
                                }
                                var d1 = new Date(parseInt(original-(60*60*2))*1000);

                                time = d1.strftime(val);
                         break;
                         case "start_date":
                                if(!val) val = "' . $settings["chronosly_format_date"] . '";
                                original = jQuery(".chronosly-defaults #chronosly-start-date").html();
                                 if(original.indexOf("lorem")>0){
                                    original = jQuery(".chronosly-defaults #chronosly-start-date").text();
                                    lorem = 1;
                                }
                                var d1 = new Date(parseInt(original-(60*60*2))*1000);

                                time = d1.strftime(val);
                         break;
                         case "end_date":
                                if(!val) val = "' . $settings["chronosly_format_date"] . '";
                                original = jQuery(".chronosly-defaults #chronosly-end-date").html();
                                 if(original.indexOf("lorem")>0){
                                    original = jQuery(".chronosly-defaults #chronosly-end-date").text();
                                    lorem = 1;
                                }
                                var d1 = new Date(parseInt(original-(60*60*2))*1000);

                                time = d1.strftime(val);
                         break;
                         case "start_hour":
                                if(!val) val = "' . $settings["chronosly_format_time"] . '";
                                original = jQuery(".chronosly-defaults #chronosly-start-date").html();
                                 if(original.indexOf("lorem")>0){
                                    original = jQuery(".chronosly-defaults #chronosly-start-date").text();
                                    lorem = 1;
                                }
                                var d1 = new Date(parseInt(original-(60*60*2))*1000);

                                time = d1.strftime(val);
                         break;
                         case "end_hour":
                                if(!val) val = "' . $settings["chronosly_format_time"] . '";
                                original = jQuery(".chronosly-defaults #chronosly-end-date").html();
                                 if(original.indexOf("lorem")>0){
                                    original = jQuery(".chronosly-defaults #chronosly-end-date").text();
                                    lorem = 1;
                                }
                                var d1 = new Date(parseInt(original-(60*60*2))*1000);

                                time = d1.strftime(val);
                         break;
                       }
                       if(lorem) time += "<span class=\'lorem\'></span>";
                       element.find(".ev-data").html(time);';
            return $return;
        }

        // event default bubbles

        /* events list public static functionS*/
        public static

        function set_new_bubble_events_list($type, $fields_array, $style)
        {
            /* HTML variable {{event_list}} */
            $args = array(
                "box_name" => __("List of all events", "chronosly") ,
                "box_info" => __("Display all events for this element, is the event list content used inside Organizer, Place, Category and Calendar", "chronosly") ,
                "name" => "events_list",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_events_list"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_events_list_js"
                ) ,
                "fields_associated" => array()
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_events_list($value, $vars, $html = 0)
        {
            if (!$html) return "#event_list#";
            else return "{{event_list}}";
        }

        public static

        function chronosly_create_events_list_js($type)
        {
            return 'content = "#event_list#"';
        }

        /* EVENT TITTLEpublic static functionS*/
        public static

        function set_new_bubble_event_title($type, $fields_array, $style)
        {
            /* HTML variable {{event_title}}*/
            $args = array(
                "box_name" => __("Event title", "chronosly") ,
                "box_info" => __("Name of your event. This is how users will first see your event. Use something they will instantly recognize", "chronosly") ,
                "name" => "event_title",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_event_title"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_event_title_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "readmore_check"
                    ) ,
                    array(
                        "name" => "readmore_text"
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            2 => __("Open event page", "chronosly") ,
                            1 => __("Open external page", "chronosly") ,
                            4 => __("Pop up event page", "chronosly")
                        )
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    ) ,
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_event_title($value, $vars, $html = 0)
        {
            if (!$html) return apply_filters('the_title', $vars->post->post_title);
            else return "{{event_title}}";
        }

        public static

        function chronosly_create_event_title_js($type)
        {
            return 'var val = el.children(".ev-hidden").find(".vars input.readmore_w").val();
                    var cont = jQuery("input#title").val();
                    var def = jQuery(".chronosly-defaults #chronosly-title").html();
                    if(val) content = val;
                    else if(cont) content = cont;
                    else content = def;';
        }

        /* EVENT DESCRIPTIONpublic static functionS*/
        public static

        function set_new_bubble_event_description($type, $fields_array, $style)
        {
            /* HTML variable {{event_description}}*/
            $args = array(
                "box_name" => __("Event description", "chronosly") ,
                "box_info" => __("Summarize and include details about what the event will be about so users will know what they can expect of that particular event.<br/><br/>The more details you can provide, the more vivid the image of the event will be in the users mind.", "chronosly") ,
                "name" => "event_description",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_event_description"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_event_description_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "shorten_text"
                    ) ,
                    array(
                        "name" => "readmore_check"
                    ) ,
                    array(
                        "name" => "readmore_text"
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            2 => __("Open event page", "chronosly") ,
                            1 => __("Open external page", "chronosly") ,
                            4 => __("Pop up event page", "chronosly")
                        )
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_event_description($value, $vars, $html = 0)
        {
            if (!$html) return apply_filters('the_content', $vars->post->post_content);
            else return "{{event_description}}";
        }

        public static

        function chronosly_create_event_description_js($type)
        {
            return 'var val = el.children(".ev-hidden").find(".vars input.readmore_w").val();
                    var cont = jQuery("#content_ifr").contents().find("body").html();
                    var def = jQuery(".chronosly-defaults #chronosly-content").html();
                    if(val) content = val;
                    else if(cont) content = cont;
                    else content = def;';
        }

        /* EVENT EXCERPTpublic static functionS*/
        public static

        function set_new_bubble_event_excerpt($type, $fields_array, $style)
        {
            /* HTML variable {{event_excerpt}}*/
            $args = array(
                "box_name" => __("Event excerpt", "chronosly") ,
                "box_info" => __("Optional summary or description of an event, an event summary.<br/><br/>Excerpt is used to describe events in RSS feeds and also in search results.", "chronosly") ,
                "name" => "event_excerpt",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_event_excerpt"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_event_excerpt_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "readmore_check"
                    ) ,
                    array(
                        "name" => "readmore_text"
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            2 => __("Open event page", "chronosly") ,
                            1 => __("Open external page", "chronosly") ,
                            4 => __("Pop up event page", "chronosly")
                        )
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_event_excerpt($value, $vars, $html = 0)
        {
            if (!$html) return apply_filters('the_content', $vars->post->post_excerpt);
            else return "{{event_excerpt}}";
        }

        public static

        function chronosly_create_event_excerpt_js($type)
        {
            return 'var val = el.children(".ev-hidden").find(".vars input.readmore_w").val();
                    var cont = jQuery("#excerpt").val();
                    var def = jQuery(".chronosly-defaults #chronosly-excerpt").html();
                    if(val) content = val;
                    else if(cont) content = cont;
                    else content = def;';
        }

        /* OTHER CUSTOM TEXTpublic static functionS*/
        public static

        function set_new_bubble_custom_text($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("Custom text", "chronosly") ,
                "box_info" => __("Add and customize some text to give customers extra information about your event.<br/>This option does not allow edit the text with bold, images or other resources. If text edititing is needed choose Custom text box", "chronosly") ,
                "name" => "custom_text",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_custom_text"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_custom_text_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_textarea",
                        "value" => __("Change me", "chronosly")
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_custom_text($value, $vars, $html = 0)
        {
            return apply_filters('the_content', $value);
        }

        public static

        function chronosly_create_custom_text_js($type)
        {
            return '';
        }

        /* OTHER CUSTOM TEXT BOXpublic static functionS*/
        public static

        function set_new_bubble_custom_text_box($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("Custom text box", "chronosly") ,
                "box_info" => __("Add and customize some text to give customers extra information about your event.<br/>This option allows users to edit the text with bold, images or other resources", "chronosly") ,
                "name" => "custom_text_box",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_custom_text_box"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_custom_text_box_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_textbox",
                        "value" => __("Change me", "chronosly")
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_custom_text_box($value, $vars, $html = 0)
        {
            return $value;
        }

        public static

        function chronosly_create_custom_text_box_js($type)
        {
            return '';
        }

        /* OTHER CUSTOM LINKpublic static functionS*/
        public static

        function set_new_bubble_custom_link($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("Custom link", "chronosly") ,
                "box_info" => __("Add and customize an internal or external link to give extra information or references about the event", "chronosly") ,
                "name" => "custom_link",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_custom_link"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_custom_link_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "readmore_text",
                        "label" => __("Link text", "chronosly") ,
                        "value" => __("Change me", "chronosly")
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            1 => "Open external link",
                            2 => "Open inside page",
                            3 => "Show hidden boxes",
                            4 => __("Pop up inside page", "chronosly")
                        )
                    ) ,
                    array(
                        "name" => "external_url",
                        "label" => __("Url", "chronosly")
                    ) ,
                    array(
                        "name" => "target_blank",
                    ) ,
                    array(
                        "name" => "nofollow",
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_custom_link($value, $vars, $html = 0)
        {
            // return "<a class='ch-readmore'></a>";
            $vista = $vars->vista;
            $id = $vars->pid;
                if ($vista == "dad6") {
                   
                    if ($html) return "<a href='{{organizer_link | id 0}' class='ch-readmore'></a>";
                    return "<a href='" . get_post_permalink($id) . "' class='ch-readmore'>$cont</a>";
                }
                else
                if ($vista == "dad7") {
                    $id = substr($cont, $ini + 9);
                    $id = substr($id, 0, stripos($id, "'"));
                    if ($html) return "<a href='{{place_link | id 0}}' class='ch-readmore'>$cont</a>";
                    return "<a href='" . get_post_permalink($id) . "' class='ch-readmore'>$cont</a>";
                }
                // else
                // if ($vista == "dad4") {

                //     // revisar

                   
                //     if (isset($link->errors)) return "<a href='{{place_link | id 0}}' class='ch-readmore'></a>";
                //     $link = get_term_link($id, "chronosly_category");
                //     return "<a href='$link' class='ch-readmore'></a>";
                // }
                
                if ($html) return "<a href='{{event_link}}' class='ch-readmore'></a>";
                return "<a href='" . str_replace("<span class='lorem'></span>", "", $vars->link) . "' class='ch-readmore'></a>";
            
           
        }

        public static

        function chronosly_create_custom_link_js($type)
        {
            return '';
        }

        /* OTHER CUSTOM CODEpublic static functionS*/
        public static

        function set_new_bubble_custom_code($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("Custom code", "chronosly") ,
                "box_info" => __("Insert any WP shortcode, always compatible with the rest of your installed plugins", "chronosly") ,
                "name" => "custom_code",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_custom_code"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_custom_code_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text",
                        "label" => "Code",
                        "value" => "Insert shortcode"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_custom_code($value, $vars, $html = 0)
        {
            ob_start();
            do_shortcode($value);
            $ret = ob_get_contents();
            ob_end_clean();
            return $ret;
        }

        public static

        function chronosly_create_custom_code_js($type)
        {
            return '';
        }

        /* OTHER INSIDE BOXpublic static functionS*/
        public static

        function set_new_bubble_cont_box($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("Inside box", "chronosly") ,
                "box_info" => __("Insert another Drag & Drop box inside the main box for better styling of elements inside. Multiple Inside Boxes can be nested.", "chronosly") ,
                "name" => "cont_box",
                "type" => "cont_box",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_cont_box"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_cont_box_js"
                ) ,
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_cont_box($value, $vars, $html = 0)
        {
            return $value;
        }

        public static

        function chronosly_create_cont_box_js($type)
        {
            return '';
        }

        /* TIME FULL DATE TIMEpublic static functionS*/
        public static

        function set_new_bubble_full_date($type, $fields_array, $style)
        {
            /* HTML variable {{full_date}}*/
            $settings = unserialize(get_option("chronosly-settings"));
            $args = array(
                "box_name" => __("Full Date", "chronosly") ,
                "box_info" => __("Set display format for start / end date at the same time", "chronosly") ,
                "name" => "full_date",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_full_date"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_full_date_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "time_format",
                        "value" => "",
                        "time" => "full_date"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_full_date($value, $vars, $html = 0)
        {
            if (!$html) return $value;
            else return "{{full_date}}";
        }

        public static

        function chronosly_create_full_date_js($type)
        {
            return '';
        }

        /* TIME FULL DATE TIMEpublic static functionS*/
        public static

        function set_new_bubble_full_time($type, $fields_array, $style)
        {
            /* HTML variable {{full_time}}*/
            $settings = unserialize(get_option("chronosly-settings"));
            $args = array(
                "box_name" => __("Full Time", "chronosly") ,
                "box_info" => __("Set display format for start / end time at the same time", "chronosly") ,
                "name" => "full_time",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_full_time"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_full_time_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "time_format",
                        "value" => "",
                        "time" => "full_time"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_full_time($value, $vars, $html = 0)
        {
            if (!$html) return $value;
            else return "{{full_time}}";
        }

        public static

        function chronosly_create_full_time_js($type)
        {
            return '';
        }

        /* TIME FULL DATE TIMEpublic static functionS*/
        public static

        function set_new_bubble_full_datetime($type, $fields_array, $style)
        {
            /* HTML variable {{full_datetime}}*/
            $settings = unserialize(get_option("chronosly-settings"));
            $args = array(
                "box_name" => __("Full DateTime", "chronosly") ,
                "box_info" => __("Set display format for start / end date and time at the same time", "chronosly") ,
                "name" => "full_datetime",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_full_datetime"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_full_datetime_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "time_format",
                        "value" => "",
                        "time" => "full_datetime"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_full_datetime($value, $vars, $html = 0)
        {
            if (!$html) return $value;
            else return "{{full_datetime}}";
        }

        public static

        function chronosly_create_full_datetime_js($type)
        {
            return '';
        }

        /* TIME START DATEpublic static functionS*/
        public static

        function set_new_bubble_start_date($type, $fields_array, $style)
        {
            /* HTML variable {{start_date}}*/
            $settings = unserialize(get_option("chronosly-settings"));
            $args = array(
                "box_name" => __("Start date", "chronosly") ,
                "box_info" => __("Set the start date for your event", "chronosly") ,
                "name" => "start_date",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_start_date"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_start_date_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "time_format",
                        "value" => "",
                        "time" => "start_date"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_start_date($value, $vars, $html = 0)
        {
            if (!$html) return $value;
            else return "{{start_date}}";
        }

        public static

        function chronosly_create_start_date_js($type)
        {
            return '';
        }

        /* TIME START TIMEpublic static functionS*/
        public static

        function set_new_bubble_start_hour($type, $fields_array, $style)
        {
            /* HTML variable {{start_hour}}*/
            $settings = unserialize(get_option("chronosly-settings"));
            $args = array(
                "box_name" => __("Start hour", "chronosly") ,
                "box_info" => __("Set the start hour for your event", "chronosly") ,
                "name" => "start_hour",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_start_hour"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_start_hour_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "time_format",
                        "value" => "",
                        "time" => "start_hour"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_start_hour($value, $vars, $html = 0)
        {
            if (!$html) return $value;
            else return "{{start_hour}}";
        }

        public static

        function chronosly_create_start_hour_js($type)
        {
            return '';
        }

        /* TIME START DATETIMEpublic static functionS*/
        public static

        function set_new_bubble_start_datetime($type, $fields_array, $style)
        {
            /* HTML variable {{start_datetime}}*/
            $settings = unserialize(get_option("chronosly-settings"));
            $args = array(
                "box_name" => __("Start DateTime", "chronosly") ,
                "box_info" => __("Set display format for start date and time", "chronosly") ,
                "name" => "start_datetime",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_start_datetime"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_start_datetime_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "time_format",
                        "value" => "",
                        "time" => "start_datetime"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_start_datetime($value, $vars, $html = 0)
        {
            if (!$html) return $value;
            else return "{{start_datetime}}";
        }

        public static

        function chronosly_create_start_datetime_js($type)
        {
            return '';
        }

        /* TIME END DATEpublic static functionS*/
        public static

        function set_new_bubble_end_date($type, $fields_array, $style)
        {
            /* HTML variable {{end_date}}*/
            $settings = unserialize(get_option("chronosly-settings"));
            $args = array(
                "box_name" => __("End date", "chronosly") ,
                "box_info" => __("Set the end date for your event", "chronosly") ,
                "name" => "end_date",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_end_date"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_end_date_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "time_format",
                        "value" => "",
                        "time" => "end_date"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_end_date($value, $vars, $html = 0)
        {
            if (!$html) return $value;
            else return "{{end_date}}";
        }

        public static

        function chronosly_create_end_date_js($type)
        {
            return '';
        }

        /* TIME END TIMEpublic static functionS*/
        public static

        function set_new_bubble_end_hour($type, $fields_array, $style)
        {
            $settings = unserialize(get_option("chronosly-settings"));
            $args = array(
                "box_name" => __("End hour", "chronosly") ,
                "box_info" => __("Set the end hour for your event", "chronosly") ,
                "name" => "end_hour",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_end_hour"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_end_hour_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "time_format",
                        "value" => "",
                        "time" => "end_hour"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_end_hour($value, $vars, $html = 0)
        {
            if (!$html) return $value;
            else return "{{end_hour}}";
        }

        public static

        function chronosly_create_end_hour_js($type)
        {
            return '';
        }

        /* TIME END DATETIMEpublic static functionS*/
        public static

        function set_new_bubble_end_datetime($type, $fields_array, $style)
        {
            $settings = unserialize(get_option("chronosly-settings"));
            $args = array(
                "box_name" => __("End DateTime", "chronosly") ,
                "box_info" => __("Set display format for end date and time", "chronosly") ,
                "name" => "end_datetime",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_end_datetime"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_end_datetime_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "time_format",
                        "value" => "",
                        "time" => "end_datetime"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_end_datetime($value, $vars, $html = 0)
        {
            if (!$html) return $value;
            else return "{{end_datetime}}";
        }

        public static

        function chronosly_create_end_datetime_js($type)
        {
            return '';
        }

        /* IMAGE FEATURED IMAGEpublic static functionS*/
        public static

        function set_new_bubble_featured_image($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("Featured image", "chronosly") ,
                "box_info" => __("Add a featured image to your event.<br/>You can customize your event by adding an image that will represent the event contents and will be useful for users to identify it", "chronosly") ,
                "name" => "featured_image",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_featured_image"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_featured_image_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "readmore_check",
                        "label" => __("Link Image", "chronosly")
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            2 => __("Open event page", "chronosly") ,
                            1 => __("Open external page", "chronosly") ,
                            4 => __("Pop up event page", "chronosly")
                        )
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_featured_image($value, $vars, $html = 0)
        {
            if ($html) return '{{event_image}}';
            if (!$vars->pid or !has_post_thumbnail($vars->pid)) {
                $src = CHRONOSLY_URL . "css/img/noimg.jpg";
                return '<img  class="lorem" width="100%" height="100%" src="' . $src . '"  />';
            }
            else $src = wp_get_attachment_url(get_post_thumbnail_id($vars->pid));
            return '<img width="100%" height="100%" src="' . $src . '"  />';
        }

        public static

        function chronosly_create_featured_image_js($type)
        {
            return 'var val = jQuery("#set-post-thumbnail img").attr("src");
                    if(!val) {
                        val = jQuery(".chronosly-defaults #chronosly-feat-img").html();
                        content = "<img class=\'lorem\' width=\'100%\' height=\'100%\' src=\'"+val+"\' />";
                     }
                    else if(val.indexOf("-") > 0){
                        ext = val.substring(val.lastIndexOf("."));
                         val = val.substring(0, val.lastIndexOf("-"))+ext;
                         content = "<img width=\'100%\' height=\'100%\' src=\'"+val+"\' />";
                     }';
        }

        /* IMAGE CUSTOM IMAGEpublic static functionS*/
        public static

        function set_new_bubble_custom_image($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("Custom image", "chronosly") ,
                "box_info" => __("Add a customised image to your event.<br/>You can customize your event adding an image that will represent the event contents and will be useful for users to identify it.", "chronosly") ,
                "name" => "custom_image",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_custom_image"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_custom_image_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "upload_image",
                        "label" => ""
                    ) ,
                    array(
                        "name" => "readmore_check",
                        "label" => __("Link Image", "chronosly")
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            2 => __("Open event page", "chronosly") ,
                            1 => __("Open external page", "chronosly") ,
                            4 => __("Pop up event page", "chronosly")
                        )
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_custom_image($value, $vars, $html = 0)
        {
            return '';
        }

        public static

        function chronosly_create_custom_image_js($type)
        {
            return "";
        }

        /* IMAGE GALLERYpublic static functionS*/
        public static

        function set_new_bubble_gallery($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("Gallery", "chronosly") ,
                "box_info" => __("Add an image gallery to your event.<br/>You can customize your event adding an image gallery that will represent the event contents and will be useful for users to identify it.<br/><br/>This advanced setting is fully compatible with most common image gallery plugins for wordpress", "chronosly") ,
                "name" => "gallery",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_gallery"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_gallery_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "upload_gallery"
                    )
                ) ,
                array(
                    "name" => "custom_text_before"
                ) ,
                array(
                    "name" => "custom_text_after"
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_gallery($value, $vars, $html = 0)
        {
            return "";
        }

        public static

        function chronosly_create_gallery_js($type)
        {
            return '';
        }

        /* ORGANIZER NAME public static functionS*/
        public static

        function set_new_bubble_organizer_name($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Name", "chronosly") ,
                "name" => "organizer_name",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_name"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_name_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "readmore_check",
                        "label" => __("Link to organizer", "chronosly")
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            2 => "Open organizer page",
                            1 => __("Open external page", "chronosly") ,
                            4 => __("Pop up organizer page", "chronosly")
                        ) ,
                        "value" => 2
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_organizer_name($value, $vars, $html = 0)
        {
            if ($html) return "<span class='ch-organizer-{{organizer_id | id $value}}'>{{organizer_name | id $value | filter the_title}}</span>";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_organizers"]) return "";
            return "<span class='ch-organizer-" . $vars->metas["organizer_vars"][$value]["post"]->ID . "'>" . apply_filters('the_title', $vars->metas["organizer_vars"][$value]["post"]->post_title) . "</span>";
        }

        public static

        function chronosly_create_organizer_name_js($type)
        {
            return 'var cont = "Select organizer and save to view";
                    var def = jQuery(".chronosly-defaults #organizer-name"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* ORGANIZER DESCRIPTION public static functionS*/
        public static

        function set_new_bubble_organizer_description($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Description", "chronosly") ,
                "name" => "organizer_description",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_description"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_description_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "shorten_text"
                    ) ,
                    array(
                        "name" => "readmore_check",
                        "label" => "Show readmore"
                    ) ,
                    array(
                        "name" => "readmore_text",
                        "value" => "more"
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            2 => __("Open organizer page", "chronosly") ,
                            1 => __("Open external page", "chronosly") ,
                            4 => __("Pop up organizer page", "chronosly")
                        ) ,
                        "value" => 2
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_organizer_description($value, $vars, $html = 0)
        {
            if ($html) return "{{organizer_description | id $value  | filter the_content}}";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_organizers"]) return "";
            return apply_filters('the_content', $vars->metas["organizer_vars"][$value]["post"]->post_content);
        }

        public static

        function chronosly_create_organizer_description_js($type)
        {
            return 'var cont = "Select organizer and save to view";
                    var def = jQuery(".chronosly-defaults #organizer-description"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* ORGANIZER EXCERPT public static functionS*/
        public static

        function set_new_bubble_organizer_excerpt($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Excerpt", "chronosly") ,
                "name" => "organizer_excerpt",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_excerpt"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_excerpt_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "readmore_check"
                    ) ,
                    array(
                        "name" => "readmore_text",
                        "value" => "more"
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            1 => __("Open organizer page", "chronosly") ,
                            3 => __("Open external page", "chronosly") ,
                            4 => __("Pop up organizer page", "chronosly")
                        )
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_organizer_excerpt($value, $vars, $html = 0)
        {
            if ($html) return "{{organizer_excerpt  | id $value | filter the_content}}";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_organizers"]) return "";
            return apply_filters('the_content', $vars->metas["organizer_vars"][$value]["post"]->post_excerpt);
        }

        public static

        function chronosly_create_organizer_excerpt_js($type)
        {
            return 'var cont = "Select organizer and save to view";
                    var def = jQuery(".chronosly-defaults #organizer-excerpt"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* ORGANIZER PHONE public static functionS*/
        public static

        function set_new_bubble_organizer_phone($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Phone", "chronosly") ,
                "name" => "organizer_phone",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_phone"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_phone_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_organizer_phone($value, $vars, $html = 0)
        {
            if ($html) return "{{organizer_phone | id $value }}";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_organizers"]) return "";
            return "<span class='ch-phone'>" . $vars->metas["organizer_vars"][$value]["metas"]["evo_phone"][0] . "</span>";
        }

        public static

        function chronosly_create_organizer_phone_js($type)
        {
            return 'var cont = "Select organizer and save to view";
                    var def = jQuery(".chronosly-defaults #organizer-phone"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* ORGANIZER EMAIL public static functionS*/
        public static

        function set_new_bubble_organizer_email($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Email", "chronosly") ,
                "name" => "organizer_email",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_email"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_email_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_organizer_email($value, $vars, $html = 0)
        {
            if ($html) return "{{organizer_email | id $value }}";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_organizers"]) return "";
            $class = "";
            if ($vars->metas["organizer_vars"][$value]["metas"]["evo_mail"][0] == "lorem@ipsum.com") $class = "lorem";
            return "<a class='$class ch-email' href='mailto:" . $vars->metas["organizer_vars"][$value]["metas"]["evo_mail"][0] . "'>" . $vars->metas["organizer_vars"][$value]["metas"]["evo_mail"][0] . "</a>";
        }

        public static

        function chronosly_create_organizer_email_js($type)
        {
            return 'var cont = "Select organizer and save to view";
                    var def = jQuery(".chronosly-defaults #organizer-mail"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* ORGANIZER WEB public static functionS*/
        public static

        function set_new_bubble_organizer_web($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Web", "chronosly") ,
                "name" => "organizer_web",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_web"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_web_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_organizer_web($value, $vars, $html = 0)
        {
            if ($html) return "{{organizer_web | id $value }}";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_organizers"]) return "";
            $web = $vars->metas["organizer_vars"][$value]["metas"]["evo_web"][0];
            $class = "";
            if ($web == "loremipsum.com") $class = "lorem";
            if (!stripos($web, "://")) $web = "http://$web";
            return "<a style='#data_style'  class='$class ch-web ev-data organizer_web' href='$web' target='_blank'>$web</a>";
        }

        public static

        function chronosly_create_organizer_web_js($type)
        {
            return 'var cont = "Select organizer and save to view";
                    var def = "Web";
                    if(def) content = def;
                    else content = cont;';
        }

        /* ORGANIZER LOGO public static functionS*/
        public static

        function set_new_bubble_organizer_logo($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Logo", "chronosly") ,
                "name" => "organizer_logo",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_logo"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_organizer_logo_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "readmore_check",
                        "label" => "Link Image"
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            2 => __("Open organizer page", "chronosly") ,
                            1 => __("Open external page", "chronosly") ,
                            4 => __("Pop up organizer page", "chronosly")
                        )
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_organizer_logo($value, $vars, $html = 0)
        {
            if ($html) return "<span class='ch-organizer-{{organizer_id | id $value}}'>{{organizer_image | id $value }}</span>";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_organizers"]) return "";
            if (!$vars->metas["organizer_vars"][$value]["post"]->ID or !has_post_thumbnail($vars->metas["organizer_vars"][$value]["post"]->ID)) {
                $src = CHRONOSLY_URL . "css/img/noimg.jpg";
                return '<img  class="lorem" width="100%" height="100%" src="' . $src . '"  />';
            }
            else $src = wp_get_attachment_url(get_post_thumbnail_id($vars->metas["organizer_vars"][$value]["post"]->ID));
            return '<img class="ch-organizer-' . $vars->metas["organizer_vars"][$value]["post"]->ID . '" width="100%" height="100%" src="' . $src . '"  />';
        }

        public static

        function chronosly_create_organizer_logo_js($type)
        {
            return 'var val = jQuery(".chronosly-defaults #organizer-thumb"+val).html();
                    if(!val) val = jQuery(".chronosly-defaults #chronolsy-feat-img").html();
                    content = "<img width=\'100%\' height=\'100%\' src=\'"+val+"\' />"';
        }

        /* PLACE NAME public static functionS*/
        public static

        function set_new_bubble_place_name($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Name", "chronosly") ,
                "name" => "place_name",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_name"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_name_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "readmore_check",
                        "label" => "Show readmore"
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            2 => __("Open place page", "chronosly") ,
                            1 => __("Open external page", "chronosly") ,
                            4 => __("Pop up place page", "chronosly")
                        ) ,
                        "value" => 2
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_place_name($value, $vars, $html = 0)
        {
            if ($html) return "<span class='ch-place-{{place_id | id $value}}'>{{place_name | id $value  | filter the_title}}</span>";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_places"]) return "";
            return "<span class='ch-place-" . $vars->metas["places_vars"][$value]["post"]->ID . "'>" . apply_filters('the_title', $vars->metas["places_vars"][$value]["post"]->post_title) . "</span>";
        }

        public static

        function chronosly_create_place_name_js($type)
        {
            return 'var cont = "Select place and save to view";
                    var def = jQuery(".chronosly-defaults #place-name"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* place DESCRIPTION public static functionS*/
        public static

        function set_new_bubble_place_description($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Description", "chronosly") ,
                "name" => "place_description",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_description"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_description_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "shorten_text"
                    ) ,
                    array(
                        "name" => "readmore_check",
                        "label" => __("Show readmore", "chronosly")
                    ) ,
                    array(
                        "name" => "readmore_text",
                        "value" => __("more", "chronosly")
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            2 => __("Open organizer page", "chronosly") ,
                            1 => __("Open external page", "chronosly") ,
                            4 => __("Pop up place page", "chronosly")
                        ) ,
                        "value" => 2
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_place_description($value, $vars, $html = 0)
        {
            if ($html) return "{{place_description | id $value  | filter the_content}}";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_places"]) return "";
            return apply_filters('the_content', $vars->metas["places_vars"][$value]["post"]->post_content);
        }

        public static

        function chronosly_create_place_description_js($type)
        {
            return 'var cont = "Select place and save to view";
                    var def = jQuery(".chronosly-defaults #place-description"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* place EXCERPT public static functionS*/
        public static

        function set_new_bubble_place_excerpt($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Excerpt", "chronosly") ,
                "name" => "place_excerpt",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_excerpt"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_excerpt_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "readmore_check"
                    ) ,
                    array(
                        "name" => "readmore_text",
                        "value" => __("more", "chronosly")
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            1 => __("Open place page", "chronosly") ,
                            2 => __("Show hidden boxes", "chronosly") ,
                            3 => __("Open external page", "chronosly") ,
                            4 => __("Pop up place page", "chronosly")
                        )
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_place_excerpt($value, $vars, $html = 0)
        {
            if ($html) return "{{place_excerpt | id $value  | filter the_content}}";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_places"]) return "";
            return apply_filters('the_content', $vars->metas["places_vars"][$value]["post"]->post_excerpt);
        }

        public static

        function chronosly_create_place_excerpt_js($type)
        {
            return 'var cont = "Select place and save to view";
                    var def = jQuery(".chronosly-defaults #place-excerpt"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* place PHONE public static functionS*/
        public static

        function set_new_bubble_place_phone($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Phone", "chronosly") ,
                "name" => "place_phone",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_phone"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_phone_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_place_phone($value, $vars, $html = 0)
        {
            if ($html) return "<span class='ch-phone'>{{place_phone | id $value }}</span>";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_places"]) return "";
            return "<span class='ch-phone'>" . $vars->metas["places_vars"][$value]["metas"]["evp_phone"][0] . "</span>";
        }

        public static

        function chronosly_create_place_phone_js($type)
        {
            return 'var cont = "Select place and save to view";
                    var def = jQuery(".chronosly-defaults #place-phone"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* place EMAIL public static functionS*/
        public static

        function set_new_bubble_place_email($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Email", "chronosly") ,
                "name" => "place_email",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_email"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_email_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_place_email($value, $vars, $html = 0)
        {
            if ($html) return "{{place_email | id $value }}";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_places"]) return "";
            $class = "";
            if ($vars->metas["places_vars"][$value]["metas"]["evp_mail"][0] == "lorem@ipsum.com") $class.= "lorem";
            return "<a class='$class ch-email' href='mailto:" . $vars->metas["places_vars"][$value]["metas"]["evp_mail"][0] . "'>" . $vars->metas["places_vars"][$value]["metas"]["evp_mail"][0] . "</a>";
        }

        public static

        function chronosly_create_place_email_js($type)
        {
            return 'var cont = "Select place and save to view";
                    var def = jQuery(".chronosly-defaults #place-mail"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* place WEB public static functionS*/
        public static

        function set_new_bubble_place_web($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Web", "chronosly") ,
                "name" => "place_web",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_web"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_web_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_place_web($value, $vars, $html = 0)
        {
            if ($html) return "{{place_web | id $value }}";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_places"]) return "";
            $class = "";
            $web = $vars->metas["places_vars"][$value]["metas"]["evp_web"][0];
            if ($web == "loremipsum.com") $class.= "lorem";
            if (!stripos($web, "://")) $web = "http://$web";
            return "<a style='#data_style'  class='$class ch-web ev-data place_web' href='" . $web . "' target='_blanck'>" . __("Web", "chronosly") . "</a>";
        }

        public static

        function chronosly_create_place_web_js($type)
        {
            return 'var cont = "Select place and save to view";
                    var def = "Web";
                    if(def) content = def;
                    else content = cont;';
        }

        /* place LOGO public static functionS*/
        public static

        function set_new_bubble_place_image($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Image", "chronosly") ,
                "name" => "place_image",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_image"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_image_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "readmore_check",
                        "label" => __("Link Image", "chronosly")
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            2 => __("Open place page", "chronosly") ,
                            1 => __("Open external page", "chronosly") ,
                            4 => __("Pop up place page", "chronosly")
                        )
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    ) ,
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_place_image($value, $vars, $html = 0)
        {
            if ($html) return '{{place_image | id $value }}';
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_places"]) return "";
            if (!$vars->metas["places_vars"][$value]["post"]->ID or !has_post_thumbnail($vars->metas["places_vars"][$value]["post"]->ID)) {
                $src = CHRONOSLY_URL . "css/img/noimg.jpg";
                return '<img  class="lorem" width="100%" height="100%" src="' . $src . '"  />';
            }
            else $src = wp_get_attachment_url(get_post_thumbnail_id($vars->metas["places_vars"][$value]["post"]->ID));
            return '<img width="100%" height="100%" src="' . $src . '"  />';
        }

        public static

        function chronosly_create_place_image_js($type)
        {
            return 'var val = jQuery(".chronosly-defaults #place-thumb"+val).html();
                    if(!val) val = jQuery(".chronosly-defaults #chronolsy-feat-img").html();
                    content = "<img width=\'100%\' height=\'100%\' src=\'"+val+"\' />"';
        }

        /* place state public static functionS*/
        public static

        function set_new_bubble_place_state($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("state", "chronosly") ,
                "name" => "place_state",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_state"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_state_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_place_state($value, $vars, $html = 0)
        {
            if ($html) return "{{place_state | id $value }}";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_places"]) return "";
            return $vars->metas["places_vars"][$value]["metas"]["evp_state"][0];
        }

        public static

        function chronosly_create_place_state_js($type)
        {
            return 'var cont = "Select place and save to view";
                    var def = jQuery(".chronosly-defaults #place-state"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* place city public static functionS*/
        public static

        function set_new_bubble_place_city($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("City", "chronosly") ,
                "name" => "place_city",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_city"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_city_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_place_city($value, $vars, $html = 0)
        {
            if ($html) return "{{place_city | id $value }}";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_places"]) return "";
            return $vars->metas["places_vars"][$value]["metas"]["evp_city"][0];
        }

        public static

        function chronosly_create_place_city_js($type)
        {
            return 'var cont = "Select place and save to view";
                    var def = jQuery(".chronosly-defaults #place-city"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* place country public static functionS*/
        public static

        function set_new_bubble_place_country($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("country", "chronosly") ,
                "name" => "place_country",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_country"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_country_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_place_country($value, $vars, $html = 0)
        {
            if ($html) return "{{place_country | id $value }}";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_places"]) return "";
            return $vars->metas["places_vars"][$value]["metas"]["evp_country"][0];
        }

        public static

        function chronosly_create_place_country_js($type)
        {
            return 'var cont = "Select place and save to view";
                    var def = jQuery(".chronosly-defaults #place-country"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* place pc public static functionS*/
        public static

        function set_new_bubble_place_pc($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Postal Code", "chronosly") ,
                "name" => "place_pc",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_pc"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_pc_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_place_pc($value, $vars, $html = 0)
        {
            if ($html) return "{{place_pc | id $value }}";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_places"]) return "";
            return $vars->metas["places_vars"][$value]["metas"]["evp_pc"][0];
        }

        public static

        function chronosly_create_place_pc_js($type)
        {
            return 'var cont = "Select place and save to view";
                    var def = jQuery(".chronosly-defaults #place-pc"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* place gmap public static functionS*/
        public static

        function set_new_bubble_place_gmap($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("gmap", "chronosly") ,
                "name" => "place_gmap",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_gmap"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_gmap_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_place_gmap($value, $vars, $html = 0)
        {
            global $timestamp;
            $settings = unserialize(get_option("chronosly-settings"));
            $address = "";
            $zoom = $settings["chronosly_dad_gmap_zoom"];
            if ($html and $html != "print") return "{{place_gmap | id $value}}";
            if(!$settings["chronosly_places"]) return "<span class='lorem'></span>";
            if (isset($vars->metas["places_vars"][$value]["metas"]["latlong"][0]) and $vars->metas["places_vars"][$value]["metas"]["latlong"][0]) {
                $q = "latlong" . $vars->metas["places_vars"][$value]["metas"]["latlong"][0];
            }
            else {
                if (stripos($vars->metas["places_vars"][$value]["metas"]["evp_dir"][0], "lorem") === FALSE) $address.= $vars->metas["places_vars"][$value]["metas"]["evp_dir"][0];
                if (stripos($vars->metas["places_vars"][$value]["metas"]["evp_city"][0], "lorem") === FALSE) $address.= ", " . $vars->metas["places_vars"][$value]["metas"]["evp_city"][0];
                if (stripos($vars->metas["places_vars"][$value]["metas"]["evp_state"][0], "lorem") === FALSE) $address.= ", " . $vars->metas["places_vars"][$value]["metas"]["evp_state"][0];
                if (stripos($vars->metas["places_vars"][$value]["metas"]["evp_country"][0], "lorem") === FALSE) $address.= ", " . $vars->metas["places_vars"][$value]["metas"]["evp_country"][0];
                if (stripos($vars->metas["places_vars"][$value]["metas"]["evp_pc"][0], "lorem") === FALSE) $address.= ", " . $vars->metas["places_vars"][$value]["metas"]["evp_pc"][0];
                $q = $address;
            }

            if ((!is_admin() or stripos($_SERVER["REQUEST_URI"], "wp-admin") === FALSE or $_REQUEST["action"] == "ch_run_shortcode" or $_REQUEST["action"] == "chronosly_filter_and_sort") and $q) {
                if ($html and $html == "print") return __("Loading map", "chronosly") . "<script>jQuery(window).load(function(){gmap_initialize('gmap{$vars->pid}$timestamp', '$q', $zoom);});</script>";
                else {
                    $timestamp += 1;

                    return __("Loading map", "chronosly") . "<script>jQuery(window).load(function(){gmap_initialize('gmap{$vars->pid}" . $timestamp . "', '$q', $zoom);});</script>";
                }
            }
            else if (!$q) return "<span class='lorem'></span>";
            return "";
        }

        public static

        function chronosly_create_place_gmap_js($type)
        {
            return 'content = "";';
        }

        /* place DIRECTION public static functionS*/
        public static

        function set_new_bubble_place_direction($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Address", "chronosly") ,
                "name" => "place_direction",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_direction"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_place_direction_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    ) ,
                    array(
                        "name" => "readmore_check"
                    ) ,
                    array(
                        "name" => "readmore_text"
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            2 => __("Open place page", "chronosly") ,
                            1 => __("Open external page", "chronosly") ,
                            4 => __("Pop up place page", "chronosly")
                        )
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    ) ,
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_place_direction($value, $vars, $html = 0)
        {
            if ($html) return "<span class='ch-address-{{place_id | id $value}}'>{{place_direction | id $value }}</span>";
            $settings = unserialize(get_option("chronosly-settings"));
            if(!$settings["chronosly_places"]) return "<span class='lorem'></span>";
            // if($html) return "{{place_direction | id $value }}";

            return "<span class='ch-address-" . $vars->metas["places_vars"][$value]["post"]->ID . "'>" . $vars->metas["places_vars"][$value]["metas"]["evp_dir"][0] . "</span>";
        }

        public static

        function chronosly_create_place_direction_js($type)
        {
            return 'var cont = "Select place and save to view";
                    var def = jQuery(".chronosly-defaults #place-dir"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* TICKET list public static functionS*/
        public static

        function set_new_bubble_ticket_list($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("List of all tickets", "chronosly") ,
                "box_info" => __("Display all tickets filled for this event in a predefined format", "chronosly") ,
                "name" => "ticket_list",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_list"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_list_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "tickets_title_check",
                        "value" => 1
                    ) ,
                    array(
                        "name" => "tickets_price_check",
                        "value" => 1
                    ) ,
                    array(
                        "name" => "tickets_capacity_check",
                        "value" => 1
                    ) ,
                
                     array(
                        "name" => "tickets_capacity_check",
                        "value" => 1
                    ) ,
                    // array(
                    //     "name" => "tickets_min_check",
                    //     "value" => 1
                    // ) ,
                    // array(
                    //     "name" => "tickets_max_check",
                    //     "value" => 1
                    // ) ,
                    // array(
                    //     "name" => "tickets_start_check",
                    //     "value" => 1
                    // ) ,
                    // array(
                    //     "name" => "tickets_end_check",
                    //     "value" => 1
                    // ) ,
                    array(
                        "name" => "tickets_buy_check",
                        "value" => 1
                    ) ,
                    array(
                        "name" => "tickets_note_check",
                        "value" => 1
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_ticket_list($value, $vars, $html = 0)
        {
            if ($html) return "{{tickets_list}}";
            $settings = unserialize(get_option("chronosly-settings"));
            if (!$settings["chronosly_tickets"]) return;

            // print_r($settings);

            $color = $vars->metas["cat-color"];
            if (!$color) $color = $settings["chronosly_category_color"];
            $ret = "";
            if (isset($vars->metas["tickets_vars"])) {
                $ret = "<div class='tickets'>";
                $ret.= "<ul class='titles'>";
                $ret.= "<li class='title'>" . __("Ticket", 'chronosly') . "</li>";
                $ret.= "<li class='price'>" . __("Price", 'chronosly') . "</li>";
                $ret.= "<li class='capacity'>" . __("Capacity", 'chronosly') . "</li>";
                // $ret.= "<li class='min'>" . __("Min. tickets", 'chronosly') . "</li>";
                // $ret.= "<li class='max'>" . __("Max. tickets", 'chronosly') . "</li>";
                // $ret.= "<li class='start'>" . __("Sales start", 'chronosly') . "</li>";
                // $ret.= "<li class='end'>" . __("Sales end", 'chronosly') . "</li>";
                $ret.= "</ul>";
                foreach($vars->metas["tickets_vars"] as $tik) {

                    // print_r($tik);
                    $currency = $settings["chronosly_currency"];
                    if($tick["currency"]) $currency = $tick["currency"];
                    $ret.= "<ul>";
                    $ret.= "<li class='title'><i class='fa fa-ticket'></i> " . $tik["title"] . "</li>";
                    if (!$tik["price"]) $tik["price"] = 0;
                    if($tik["sales-price"] and $tik["sale"] ) $ret.= "<li class='price sale'><span class='ch-currency'>" . $currency . "</span> " . $tik["sales-price"] . "</li>";
                    else $ret.= "<li class='price'><span class='ch-currency'>" . $currency . "</span> " . $tik["price"] . "</li>";
                    $ret.= "<li class='capacity'>" . $tik["capacity"] . "</li>";
                    // $ret.= "<li class='min'>" . $tik["min-user"] . "</li>";
                    // $ret.= "<li class='max'>" . $tik["max-user"] . "</li>";
                    // $ret.= "<li class='start'>";
                    // if ($tik["start-time"]) {
                    //     if (stripos($settings["chronosly_format_date"], "%") === FALSE) $ret.= date_i18n($settings["chronosly_format_date"], strtotime($tik["start-time"]));
                    //     else $ret.= strftime($settings["chronosly_format_date"], strtotime($tik["start-time"]));
                    // }

                    // $ret.= "</li>";
                    // $ret.= "<li class='end'>";
                    // if ($tik["end-time"]) {
                    //     if (stripos($settings["chronosly_format_date"], "%") === FALSE) $ret.= date_i18n($settings["chronosly_format_date"], strtotime($tik["end-time"]));
                    //     else $ret.= strftime($settings["chronosly_format_date"], strtotime($tik["end-time"]));
                    // }

                    $ret.= "</li>";
                    $ret.= "<li class='buy'>";
                    if ($tik["soldout"]) $ret.= __("Sold out", "chronosly");
                    else{
                        
                        if ($tik["link"] and stripos($tik["link"], "lorem") === false) $ret.= "<a class='buy_ticket' style='background:$color' href='" . $tik["link"] . "' target='_blank' rel='nofollow'>" . __("Buy", "chronosly") . "</a>";
                        else $ret.= "<a class='buy_ticket lorem' style='background:$color' href='' target='_blank' rel='nofollow'>" . __("Buy", "chronosly") . "</a>";
                    }
                    $ret.= "</li>";
                    $ret.= "</ul>";
                    if ($tik["notes"]) $ret.= "<ul class='note'><li class='notes_title'>" . __("Note", "chronosly") . "</li><li class='notes'>" . $tik["notes"] . "</li></ul>";
                }

                $ret.= "</div>";
            }

            return $ret;
        }

        function tickets_title_check_field($default)
        {
            if ($default['label']) $label = $default['label'];
            else $label = "Title";
            if ($default['order']) $order = $default['order'];
            else $order = 1;
            if (isset($default['value'])) $value = $default['value'];
            else $value = 1;
            $args = array(
                "name" => "tickets_title_check",
                "label" => $label,
                "el_type" => "var",
                "type" => "checkbox",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_ticket_title"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_ticket_title_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_ticket_title($cont, $value, $vars, $html = 0)
        {
            if ($html) {
                $pos = strrpos($cont, "}}");
                if ($pos !== false) {
                    $cont = substr_replace($cont, " | ticket_title " . $value . "}}", $pos, 2);
                }

                return $cont;
            }

            $settings = unserialize(get_option("chronosly-settings"));
            if (!$value or !$settings["chronosly_tickets"]) {
                $cont = str_replace("class='title'", "class='title hide'", $cont);
                return $cont;
            }

            return $cont;
        }

        static
        function create_ticket_title_js($type)
        {
            switch ($type) {
            case "create":
                $return = 'if(!val) {
                                element.find(".ev-data .tickets .title").addClass("hide");
                              }';
                break;

            case "modify":
                $return = '
                               if(!val) {
                                element.find(".ev-data .tickets .title").addClass("hide");
                              } else{
                                element.find(".ev-data .tickets .title").removeClass("hide");

                              }';
                break;
            }

            return $return;
        }

        function tickets_price_check_field($default)
        {
            if ($default['label']) $label = $default['label'];
            else $label = "Price";
            if ($default['order']) $order = $default['order'];
            else $order = 1;
            if (isset($default['value'])) $value = $default['value'];
            else $value = 1;
            $args = array(
                "name" => "tickets_price_check",
                "label" => $label,
                "el_type" => "var",
                "type" => "checkbox",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_ticket_price"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_ticket_price_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_ticket_price($cont, $value, $vars, $html = 0)
        {
            if ($html) {
                $pos = strrpos($cont, "}}");
                if ($pos !== false) {
                    $cont = substr_replace($cont, " | ticket_price " . $value . "}}", $pos, 2);
                }

                return $cont;
            }

            $settings = unserialize(get_option("chronosly-settings"));
            if (!$value or !$settings["chronosly_tickets"]) {
                $cont = str_replace("class='price'", "class='price hide'", $cont);
                return $cont;
            }

            return $cont;
        }

        static
        function create_ticket_price_js($type)
        {
            switch ($type) {
            case "create":
                $return = 'if(!val) {
                                element.find(".ev-data .tickets .price").addClass("hide");
                              }';
                break;

            case "modify":
                $return = '
                               if(!val) {
                                element.find(".ev-data .tickets .price").addClass("hide");
                              } else{
                                element.find(".ev-data .tickets .price").removeClass("hide");

                              }';
                break;
            }

            return $return;
        }

        function tickets_capacity_check_field($default)
        {
            if ($default['label']) $label = $default['label'];
            else $label = "Capacity";
            if ($default['order']) $order = $default['order'];
            else $order = 1;
            if (isset($default['value'])) $value = $default['value'];
            else $value = 1;
            $args = array(
                "name" => "tickets_capacity_check",
                "label" => $label,
                "el_type" => "var",
                "type" => "checkbox",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_ticket_capacity"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_ticket_capacity_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_ticket_capacity($cont, $value, $vars, $html = 0)
        {
            if ($html) {
                $pos = strrpos($cont, "}}");
                if ($pos !== false) {
                    $cont = substr_replace($cont, " | ticket_capacity " . $value . "}}", $pos, 2);
                }

                return $cont;
            }

            $settings = unserialize(get_option("chronosly-settings"));
            if (!$value or !$settings["chronosly_tickets"]) {
                $cont = str_replace("class='capacity'", "class='capacity hide'", $cont);
                return $cont;
            }

            return $cont;
        }

        static
        function create_ticket_capacity_js($type)
        {
            switch ($type) {
            case "create":
                $return = 'if(!val) {
                                element.find(".ev-data .tickets .capacity").addClass("hide");
                              }';
                break;

            case "modify":
                $return = '
                               if(!val) {
                                element.find(".ev-data .tickets .capacity").addClass("hide");
                              } else{
                                element.find(".ev-data .tickets .capacity").removeClass("hide");

                              }';
                break;
            }

            return $return;
        }

        // function tickets_min_check_field($default)
        // {
        //     if ($default['label']) $label = $default['label'];
        //     else $label = "Min. tickets";
        //     if ($default['order']) $order = $default['order'];
        //     else $order = 1;
        //     if (isset($default['value'])) $value = $default['value'];
        //     else $value = 1;
        //     $args = array(
        //         "name" => "tickets_min_check",
        //         "label" => $label,
        //         "el_type" => "var",
        //         "type" => "checkbox",
        //         "order" => $order,
        //         "value" => $value,
        //         "php_function" => array(
        //             "Chronosly_Dad_Elements",
        //             "create_ticket_min"
        //         ) ,
        //         "js_function" => array(
        //             "Chronosly_Dad_Elements",
        //             "create_ticket_min_js"
        //         )
        //     );
        //     return Chronosly_Extend::create_dad_field($args);
        // }

        // static
        // function create_ticket_min($cont, $value, $vars, $html = 0)
        // {
        //     if ($html) {
        //         $pos = strrpos($cont, "}}");
        //         if ($pos !== false) {
        //             $cont = substr_replace($cont, " | ticket_min " . $value . "}}", $pos, 2);
        //         }

        //         return $cont;
        //     }

        //     $settings = unserialize(get_option("chronosly-settings"));
        //     if (!$value or !$settings["chronosly_tickets"]) {
        //         $cont = str_replace("class='min'", "class='min hide'", $cont);
        //         return $cont;
        //     }

        //     return $cont;
        // }

        // static
        // function create_ticket_min_js($type)
        // {
        //     switch ($type) {
        //     case "create":
        //         $return = 'if(!val) {
        //                         element.find(".ev-data .tickets .min").addClass("hide");
        //                       }';
        //         break;

        //     case "modify":
        //         $return = '
        //                        if(!val) {
        //                         element.find(".ev-data .tickets .min").addClass("hide");
        //                       } else{
        //                         element.find(".ev-data .tickets .min").removeClass("hide");

        //                       }';
        //         break;
        //     }

        //     return $return;
        // }

        // function tickets_max_check_field($default)
        // {
        //     if ($default['label']) $label = $default['label'];
        //     else $label = "Max. tickets";
        //     if ($default['order']) $order = $default['order'];
        //     else $order = 1;
        //     if (isset($default['value'])) $value = $default['value'];
        //     else $value = 1;
        //     $args = array(
        //         "name" => "tickets_max_check",
        //         "label" => $label,
        //         "el_type" => "var",
        //         "type" => "checkbox",
        //         "order" => $order,
        //         "value" => $value,
        //         "php_function" => array(
        //             "Chronosly_Dad_Elements",
        //             "create_ticket_max"
        //         ) ,
        //         "js_function" => array(
        //             "Chronosly_Dad_Elements",
        //             "create_ticket_max_js"
        //         )
        //     );
        //     return Chronosly_Extend::create_dad_field($args);
        // }

        // static
        // function create_ticket_max($cont, $value, $vars, $html = 0)
        // {
        //     if ($html) {
        //         $pos = strrpos($cont, "}}");
        //         if ($pos !== false) {
        //             $cont = substr_replace($cont, " | ticket_max " . $value . "}}", $pos, 2);
        //         }

        //         return $cont;
        //     }

        //     $settings = unserialize(get_option("chronosly-settings"));
        //     if (!$value or !$settings["chronosly_tickets"]) {
        //         $cont = str_replace("class='max'", "class='max hide'", $cont);
        //         return $cont;
        //     }

        //     return $cont;
        // }

        // static
        // function create_ticket_max_js($type)
        // {
        //     switch ($type) {
        //     case "create":
        //         $return = 'if(!val) {
        //                         element.find(".ev-data .tickets .max").addClass("hide");
        //                       }';
        //         break;

        //     case "modify":
        //         $return = '
        //                        if(!val) {
        //                         element.find(".ev-data .tickets .max").addClass("hide");
        //                       } else{
        //                         element.find(".ev-data .tickets .max").removeClass("hide");

        //                       }';
        //         break;
        //     }

        //     return $return;
        // }

        // function tickets_start_check_field($default)
        // {
        //     if ($default['label']) $label = $default['label'];
        //     else $label = "Start date";
        //     if ($default['order']) $order = $default['order'];
        //     else $order = 1;
        //     if (isset($default['value'])) $value = $default['value'];
        //     else $value = 1;
        //     $args = array(
        //         "name" => "tickets_start_check",
        //         "label" => $label,
        //         "el_type" => "var",
        //         "type" => "checkbox",
        //         "order" => $order,
        //         "value" => $value,
        //         "php_function" => array(
        //             "Chronosly_Dad_Elements",
        //             "create_ticket_start"
        //         ) ,
        //         "js_function" => array(
        //             "Chronosly_Dad_Elements",
        //             "create_ticket_start_js"
        //         )
        //     );
        //     return Chronosly_Extend::create_dad_field($args);
        // }

        // static
        // function create_ticket_start($cont, $value, $vars, $html = 0)
        // {
        //     if ($html) {
        //         $pos = strrpos($cont, "}}");
        //         if ($pos !== false) {
        //             $cont = substr_replace($cont, " | ticket_start " . $value . "}}", $pos, 2);
        //         }

        //         return $cont;
        //     }

        //     $settings = unserialize(get_option("chronosly-settings"));
        //     if (!$value or !$settings["chronosly_tickets"]) {
        //         $cont = str_replace("class='start'", "class='start hide'", $cont);
        //         return $cont;
        //     }

        //     return $cont;
        // }

        // static
        // function create_ticket_start_js($type)
        // {
        //     switch ($type) {
        //     case "create":
        //         $return = 'if(!val) {
        //                         element.find(".ev-data .tickets .start").addClass("hide");
        //                       }';
        //         break;

        //     case "modify":
        //         $return = '
        //                        if(!val) {
        //                         element.find(".ev-data .tickets .start").addClass("hide");
        //                       } else{
        //                         element.find(".ev-data .tickets .start").removeClass("hide");

        //                       }';
        //         break;
        //     }

        //     return $return;
        // }

        // function tickets_end_check_field($default)
        // {
        //     if ($default['label']) $label = $default['label'];
        //     else $label = "End date";
        //     if ($default['order']) $order = $default['order'];
        //     else $order = 1;
        //     if (isset($default['value'])) $value = $default['value'];
        //     else $value = 1;
        //     $args = array(
        //         "name" => "tickets_end_check",
        //         "label" => $label,
        //         "el_type" => "var",
        //         "type" => "checkbox",
        //         "order" => $order,
        //         "value" => $value,
        //         "php_function" => array(
        //             "Chronosly_Dad_Elements",
        //             "create_ticket_end"
        //         ) ,
        //         "js_function" => array(
        //             "Chronosly_Dad_Elements",
        //             "create_ticket_end_js"
        //         )
        //     );
        //     return Chronosly_Extend::create_dad_field($args);
        // }

        // static
        // function create_ticket_end($cont, $value, $vars, $html = 0)
        // {
        //     if ($html) {
        //         $pos = strrpos($cont, "}}");
        //         if ($pos !== false) {
        //             $cont = substr_replace($cont, " | ticket_end " . $value . "}}", $pos, 2);
        //         }

        //         return $cont;
        //     }

        //     $settings = unserialize(get_option("chronosly-settings"));
        //     if (!$value or !$settings["chronosly_tickets"]) {
        //         $cont = str_replace("class='end'", "class='end hide'", $cont);
        //         return $cont;
        //     }

        //     return $cont;
        // }

        // static
        // function create_ticket_end_js($type)
        // {
        //     switch ($type) {
        //     case "create":
        //         $return = 'if(!val) {
        //                         element.find(".ev-data .tickets .end").addClass("hide");
        //                       }';
        //         break;

        //     case "modify":
        //         $return = '
        //                        if(!val) {
        //                         element.find(".ev-data .tickets .end").addClass("hide");
        //                       } else{
        //                         element.find(".ev-data .tickets .end").removeClass("hide");

        //                       }';
        //         break;
        //     }

        //     return $return;
        // }

        function tickets_buy_check_field($default)
        {
            if ($default['label']) $label = $default['label'];
            else $label = "Buy link";
            if ($default['order']) $order = $default['order'];
            else $order = 1;
            if (isset($default['value'])) $value = $default['value'];
            else $value = 1;
            $args = array(
                "name" => "tickets_buy_check",
                "label" => $label,
                "el_type" => "var",
                "type" => "checkbox",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_ticket_buy"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_ticket_buy_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_ticket_buy($cont, $value, $vars, $html = 0)
        {
            if ($html) {
                $pos = strrpos($cont, "}}");
                if ($pos !== false) {
                    $cont = substr_replace($cont, " | ticket_link " . $value . "}}", $pos, 2);
                    return $cont;
                }

                return "{{translate | $cont}}";
            }

            $settings = unserialize(get_option("chronosly-settings"));
            if (!$value or !$settings["chronosly_tickets"]) {
                $cont = str_replace("class='buy'", "class='buy hide'", $cont);
                return $cont;
            }

            return $cont;
        }

        static
        function create_ticket_buy_js($type)
        {
            switch ($type) {
            case "create":
                $return = 'if(!val) {
                                element.find(".ev-data .tickets .buy").addClass("hide");
                              }';
                break;

            case "modify":
                $return = '
                               if(!val) {
                                element.find(".ev-data .tickets .buy").addClass("hide");
                              } else{
                                element.find(".ev-data .tickets .buy").removeClass("hide");

                              }';
                break;
            }

            return $return;
        }

        function tickets_note_check_field($default)
        {
            if ($default['label']) $label = $default['label'];
            else $label = "Notes";
            if ($default['order']) $order = $default['order'];
            else $order = 1;
            if (isset($default['value'])) $value = $default['value'];
            else $value = 1;
            $args = array(
                "name" => "tickets_note_check",
                "label" => $label,
                "el_type" => "var",
                "type" => "checkbox",
                "order" => $order,
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_ticket_note"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "create_ticket_note_js"
                )
            );
            return Chronosly_Extend::create_dad_field($args);
        }

        static
        function create_ticket_note($cont, $value, $vars, $html = 0)
        {
            if ($html) {
                $pos = strrpos($cont, "}}");
                if ($pos !== false) {
                    $cont = substr_replace($cont, " | ticket_note " . $value . "}}", $pos, 2);
                }

                return $cont;
            }

            $settings = unserialize(get_option("chronosly-settings"));
            if (!$value or !$settings["chronosly_tickets"]) {
                $cont = str_replace("class='note'", "class='note hide'", $cont);
                return $cont;
            }

            return $cont;
        }

        static
        function create_ticket_note_js($type)
        {
            switch ($type) {
            case "create":
                $return = 'if(!val) {
                                element.find(".ev-data .tickets .note").addClass("hide");
                              }';
                break;

            case "modify":
                $return = '
                               if(!val) {
                                element.find(".ev-data .tickets .note").addClass("hide");
                              } else{
                                element.find(".ev-data .tickets .note").removeClass("hide");

                              }';
                break;
            }

            return $return;
        }

        public static

        function chronosly_create_ticket_list_js($type)
        {
            return ' content = "<div class=\'defaults\'>Save to view the list</div>";';
        }

        /* TICKET NAME public static functionS*/
        public static

        function set_new_bubble_ticket_name($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Name", "chronosly") ,
                "name" => "ticket_name",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_name"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_name_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_ticket_name($value, $vars, $html = 0)
        {
            if ($html) return "{{ticket_name | id $value}}";
            $settings = unserialize(get_option("chronosly-settings"));
            if (!$settings["chronosly_tickets"]) return;
            return $vars->metas['tickets_vars'][$value]["title"];
        }

        public static

        function chronosly_create_ticket_name_js($type)
        {
            return 'var cont = "Create a ticket and save to view";
                    var def = jQuery(".chronosly-defaults #chronosly-ticket-name"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* ticket price public static functionS*/
        public static

        function set_new_bubble_ticket_price($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Price", "chronosly") ,
                "name" => "ticket_price",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_price"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_price_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_ticket_price($value, $vars, $html = 0)
        {
            if ($html) return "{{ticket_price | id $value}}";
            $settings = unserialize(get_option("chronosly-settings"));
            $soldout = "";
            $soldoutClass = "solded";
            if (!$settings["chronosly_tickets"]) return;
            if (isset($vars->metas['tickets_vars'][$value]["soldout"]) and $vars->metas['tickets_vars'][$value]["soldout"]) $soldout = " <span class='ch-soldout'>" . __("Sold Out", "chronosly") . "</span>";
            
            foreach($vars->metas['tickets_vars'] as $t){
                if(!$t["soldout"]) {
                    $soldoutClass = "";
                    break;
                }

            }
            $currency = $settings["chronosly_currency"];
            if($vars->metas['tickets_vars'][$value]["currency"]) $currency = $vars->metas['tickets_vars'][$value]["currency"];
            $currency_type = "";
            $length = strlen(utf8_decode(html_entity_decode($currency, ENT_COMPAT, 'utf-8')));
            if ($length > 1) $currency_type = "$length";
            $ant = "";
            if(count($vars->metas['tickets_vars']) > 1) {
                $ant = "<span class='price-from'>".__("From", "chronosly")."</span> ";
                $value = 1;
                $price = $vars->metas['tickets_vars'][1]["price"];
                foreach($vars->metas['tickets_vars'] as $k=>$v){
                    if($v["sale"] and $v["sales-price"] < $price) {
                        $price = $v["sales-price"];
                        $value = $k;
                    } else if($v["price"] < $price){
                        $price = $v["price"];
                        $value = $k;
                        if($v["currency"]) $currency = $v["currency"];

                    }
                }
            }
            if($vars->metas['tickets_vars'][$value]["sale"] && $vars->metas['tickets_vars'][$value]["sales-price"]) {
                if($vars->metas['tickets_vars'][$value]["currency"]) $currency = $vars->metas['tickets_vars'][$value]["currency"];

                $porc = " <span class='sale-discount' style='background-color: #sale-color'>".round((($vars->metas['tickets_vars'][$value]["sales-price"]-$vars->metas['tickets_vars'][$value]["price"])/$vars->metas['tickets_vars'][$value]["price"])*100)."%</span>";
                return "<div class='$soldoutClass'>$ant<span class='sale'><span class='ch-currency$currency_type'>" . $currency . "</span> <span class='oldprice'>" .$vars->metas['tickets_vars'][$value]["price"]."</span> <span class='price'>". $vars->metas['tickets_vars'][$value]["sales-price"] ."</span>" .$porc. $soldout."</span></div>";
            }
            return "<div class='$soldoutClass'>$ant<span class='ch-currency$currency_type'>" . $currency . "</span> <span class='price'>" . $vars->metas['tickets_vars'][$value]["price"] ."</span>". $soldout."</div>";
        }

        public static

        function chronosly_create_ticket_price_js($type)
        {
            return 'var cont = "Create a ticket and save to view";
                    var def = jQuery(".chronosly-defaults #chronosly-ticket-price"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* ticket capacitypublic static functionS*/
        public static

        function set_new_bubble_ticket_capacity($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Capacity", "chronosly") ,
                "name" => "ticket_capacity",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_capacity"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_capacity_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_ticket_capacity($value, $vars, $html = 0)
        {
            if ($html) return "{{ticket_capacity | id $value}}";
            $settings = unserialize(get_option("chronosly-settings"));
            if (!$settings["chronosly_tickets"]) return;
            return $vars->metas['tickets_vars'][$value]["capacity"];
        }

        public static

        function chronosly_create_ticket_capacity_js($type)
        {
            return 'var cont = "Create a ticket and save to view";
                    var def = jQuery(".chronosly-defaults #chronosly-ticket-capacity"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* ticket min per user public static functionS*/
        public static

        function set_new_bubble_ticket_min_per_user($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Min per user", "chronosly") ,
                "name" => "ticket_min_per_user",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_min_per_user"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_min_per_user_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_ticket_min_per_user($value, $vars, $html = 0)
        {
            if ($html) return "{{ticket_min | id $value}}";
            return $vars->metas['tickets_vars'][$value]["min-user"];
        }

        public static

        function chronosly_create_ticket_min_per_user_js($type)
        {
            return 'var cont = "Create a ticket and save to view";
                    var def = jQuery(".chronosly-defaults #chronosly-ticket-min"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* ticket max per user public static functionS*/
        public static

        function set_new_bubble_ticket_max_per_user($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Max per user", "chronosly") ,
                "name" => "ticket_max_per_user",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_max_per_user"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_max_per_user_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_ticket_max_per_user($value, $vars, $html = 0)
        {
            if ($html) return "{{ticket_max | id $value}}";
            $settings = unserialize(get_option("chronosly-settings"));
            if (!$settings["chronosly_tickets"]) return;
            return $vars->metas['tickets_vars'][$value]["max-user"];
        }

        public static

        function chronosly_create_ticket_max_per_user_js($type)
        {
            return 'var cont = "Create a ticket and save to view";
                    var def = jQuery(".chronosly-defaults #chronosly-ticket-max"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* ticket start public static functionS*/
        public static

        function set_new_bubble_ticket_start($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Start time", "chronosly") ,
                "name" => "ticket_start",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_start"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_start_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_ticket_start($value, $vars, $html = 0)
        {
            $settings = unserialize(get_option("chronosly-settings"));
            if ($html) return "{{ticket_start | id $value}}";

            // print_r($vars->metas['tickets_vars'][$value]["start-time"]);

            if (!$settings["chronosly_tickets"]) return;
            $ret = "";
            if (!$vars->metas['tickets_vars'][$value]["start-time"] or stripos($vars->metas['tickets_vars'][$value]["start-time"], "lorem") !== false) $ret = "<span class='lorem'></span>";
            if (stripos($settings["chronosly_format_date"], "%") === FALSE) return $ret . date_i18n($settings["chronosly_format_date"], strtotime($vars->metas['tickets_vars'][$value]["start-time"]));
            else return $ret . strftime($settings["chronosly_format_date"], strtotime($vars->metas['tickets_vars'][$value]["start-time"]));
        }

        public static

        function chronosly_create_ticket_start_js($type)
        {
            return 'var cont = "Create a ticket and save to view";
                    var def = jQuery(".chronosly-defaults #chronosly-ticket-start-time"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* ticket end public static functionS*/
        public static

        function set_new_bubble_ticket_end($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("End time", "chronosly") ,
                "name" => "ticket_end",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_end"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_end_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_ticket_end($value, $vars, $html = 0)
        {
            if ($html) return "{{ticket_end | id $value}}";
            $settings = unserialize(get_option("chronosly-settings"));
            if (!$settings["chronosly_tickets"]) return;
            $ret = "";
            if (!$vars->metas['tickets_vars'][$value]["end-time"] or stripos($vars->metas['tickets_vars'][$value]["end-time"], "lorem") !== false) $ret = "<span class='lorem'></span>";
            if (stripos($settings["chronosly_format_date"], "%") === FALSE) return $ret . date_i18n($settings["chronosly_format_date"], strtotime($vars->metas['tickets_vars'][$value]["end-time"]));
            else return $ret . strftime($settings["chronosly_format_date"], strtotime($vars->metas['tickets_vars'][$value]["end-time"]));
        }

        public static

        function chronosly_create_ticket_end_js($type)
        {
            return 'var cont = "Create a ticket and save to view";
                    var def = jQuery(".chronosly-defaults #chronosly-ticket-endtime"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* ticket notes public static functionS*/
        public static

        function set_new_bubble_ticket_notes($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Notes", "chronosly") ,
                "name" => "ticket_notes",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_notes"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_notes_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_ticket_notes($value, $vars, $html = 0)
        {
            if ($html) return "{{ticket_notes | id $value}}";
            $settings = unserialize(get_option("chronosly-settings"));
            if (!$settings["chronosly_tickets"]) return;
            return $vars->metas['tickets_vars'][$value]["notes"];
        }

        public static

        function chronosly_create_ticket_notes_js($type)
        {
            return 'var cont = "Create a ticket and save to view";
                    var def = jQuery(".chronosly-defaults #chronosly-ticket-notes"+val).html();
                    if(def) content = def;
                    else content = cont;';
        }

        /* tickets link public static functionS*/
        public static

        function set_new_bubble_ticket_link($type, $fields_array, $style)
        {
            if (isset($fields_array["bubble_value"])) $value = $fields_array["bubble_value"];
            $args = array(
                "box_name" => __("Buy link", "chronosly") ,
                "name" => "ticket_link",
                "type" => "hidden",
                "value" => $value,
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_link"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_ticket_link_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text",
                        "label" => __("Buy text", "chronosly") ,
                        "value" => __("Buy", "chronosly")
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_ticket_link($value, $vars, $html = 0)
        {
            if ($html) return "<a href='{{ticket_link | id $value}}' target='_blank'>#custom_text#</a>";
            $settings = unserialize(get_option("chronosly-settings"));
            if (!$settings["chronosly_tickets"]) return;
            $link = str_replace("<span class='lorem'></span>", "", $vars->metas['tickets_vars'][$value]["link"], $lorem);
            $class = "";
            if (!stripos($link, "://")) $link = "http://$link";
            if ($lorem) return "<a href='" . $link . "' class='lorem' target='_blank'>#custom_text#</a>";
            return "<a href='" . $link . "' target='_blank'>#custom_text#</a>";
        }

        public static

        function chronosly_create_ticket_link_js($type)
        {
            return 'var cont = "Create a ticket and save to view";
                    var def = jQuery(".chronosly-defaults #chronosly-ticket-link"+val).html();
                    if(def) content = "";
                    else content = cont;';
        }

        /* categories public static functionS*/
        public static

        function set_new_bubble_categories($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("Categories", "chronosly") ,
                "name" => "categories",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_categories"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_categories_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_categories($value, $vars, $html = 0)
        {
            if ($html) return "{{categories}}";
            $settings = unserialize(get_option("chronosly-settings"));
            $ret = "";

            // print_r($vars->metas["cats_vars"]);

            if (isset($vars->metas["cats_vars"])) {
                foreach($vars->metas["cats_vars"] as $cat) {
                    $feat = "";
                    $color = $cat->metas["cat-color"];
                    if (!$color) $color = $settings["chronosly_category_color"];
                    if ($cat->metas["featured"]) $feat = "class='cat-feat' color='$color'";
                    $ret.= $settings["chronosly_dad_cat_separator"] . "<a $feat href='" . get_term_link($cat) . "'>" . apply_filters('the_title', $cat->name) . "</a>";
                }
            }

            if ($ret) return preg_replace("/" . $settings["chronosly_dad_cat_separator"] . "/", "", $ret, 1);
            return "";
        }

        public static

        function chronosly_create_categories_js($type)
        {
            $settings = unserialize(get_option("chronosly-settings"));
            return 'var value="";
                    jQuery("#chronosly_category-all input:checked").each(function(){
                        value += "' . $settings["chronosly_dad_cat_separator"] . '"+jQuery(this).parent().text();
                    });
                    if(!value){
                        jQuery(".chronosly-defaults #chronosly-category div").each(function(){
                            value += "' . $settings["chronosly_dad_cat_separator"] . '"+jQuery(this).html();
                        });
                    }
                    if(!value) content = "Select category";
                    else  content = value.replace("' . $settings["chronosly_dad_cat_separator"] . '", "");';
        }

        public static

        function set_new_bubble_category_name($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("Category Name", "chronosly") ,
                "name" => "category_name",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_category_name"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_category_name_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "readmore_check"
                    ) ,
                    array(
                        "name" => "readmore_text"
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            3 => __("Show hidden boxes", "chronosly") ,
                            2 => __("Open category page", "chronosly") ,
                            1 => __("Open external page", "chronosly")
                        )
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    ) ,
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_category_name($value, $vars, $html = 0)
        {

            // print_r($vars->metas);

            if (!$value) $value = 0;
            if ($html) return "<span class='ch-category-{{category_slug | id $value}}'>{{category_name | id $value | filter the_title}}</span>";
            $settings = unserialize(get_option("chronosly-settings"));
            $ret = "";
            return "<span class='ch-category-" . $vars->metas["cats_vars"][$value]->slug . "'>" . apply_filters('the_title', $vars->metas["cats_vars"][$value]->name) . "</span>";
        }

        public static

        function chronosly_create_category_name_js($type)
        {
            return 'var val = el.children(".ev-hidden").find(".vars input.readmore_w").val();
                    var cont = jQuery("input#name").val();
                    var def = jQuery(".chronosly-defaults #chronosly-title").html();
                    if(val) content = val;
                    else if(cont) content = cont;
                    else content = def;';
        }

        public static

        function set_new_bubble_category_description($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("Category Description", "chronosly") ,
                "name" => "category_desc",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_category_desc"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_category_desc_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "shorten_text"
                    ) ,
                    array(
                        "name" => "readmore_check"
                    ) ,
                    array(
                        "name" => "readmore_text"
                    ) ,
                    array(
                        "name" => "readmore_action",
                        "options" => array(
                            2 => __("Open category page", "chronosly") ,
                            1 => __("Open external page", "chronosly") ,
                            3 => __("Show hidden boxes", "chronosly")
                        )
                    ) ,
                    array(
                        "name" => "external_url"
                    ) ,
                    array(
                        "name" => "target_blank",
                        "value" => 0
                    ) ,
                    array(
                        "name" => "nofollow"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_category_desc($value, $vars, $html = 0)
        {
            if ($html) return "<span class='ch-category-{{category_id | id $value}}'>{{category_description | id $value | filter the_content}}</span>";
            $settings = unserialize(get_option("chronosly-settings"));
            $ret = "";
            if (!$value) $value = 0;
            return "<span class='ch-category-" . $vars->metas["cats_vars"][$value]->term_id . "'>" . apply_filters('the_content', $vars->metas["cats_vars"][$value]->description) . "</span>";
        }

        public static

        function chronosly_create_category_desc_js($type)
        {
            return 'var val = el.children(".ev-hidden").find(".vars input.readmore_w").val();
                    var cont = jQuery("input#description").val();
                    var def = jQuery(".chronosly-defaults #chronosly-content").html();
                    if(val) content = val;
                    else if(cont) content = cont;
                    else content = def;';
        }

        /* tags public static functionS*/
        public static

        function set_new_bubble_tags($type, $fields_array, $style)
        {
            $args = array(
                "box_name" => __("Tags", "chronosly") ,
                "name" => "tags",
                "type" => "hidden",
                "php_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_tags"
                ) ,
                "js_function" => array(
                    "Chronosly_Dad_Elements",
                    "chronosly_create_tags_js"
                ) ,
                "fields_associated" => array(
                    array(
                        "name" => "custom_text_before"
                    ) ,
                    array(
                        "name" => "custom_text_after"
                    )
                )
            );
            $return = Chronosly_Extend::create_dad_buble($args, $type, $fields_array, $style);
            return $return;
        }

        // the content creator for events tab

        public static

        function chronosly_create_tags($value, $vars, $html = 0)
        {
            if ($html) return "{{tags}}";
            $settings = unserialize(get_option("chronosly-settings"));
            $ret = "";
            if (isset($vars->metas["tags_vars"])) {
                foreach($vars->metas["tags_vars"] as $cat) {
                    $ret.= $settings["chronosly_dad_tag_separator"] . $cat->name;
                }
            }

            if ($ret) return preg_replace("/" . $settings["chronosly_dad_tag_separator"] . "/", "", $ret, 1);
            return "";
        }

        public static

        function chronosly_create_tags_js($type)
        {
            $settings = unserialize(get_option("chronosly-settings"));
            return 'var value="";
                    jQuery(".tagchecklist span").each(function(){
                        value += "' . $settings["chronosly_dad_tag_separator"] . '"+jQuery(this).text().replace("X&nbsp;", "");
                    });
                    if(!value){
                        jQuery(".chronosly-defaults #chronosly-tag div").each(function(){
                            value += "' . $settings["chronosly_dad_tag_separator"] . '"+jQuery(this).html();
                        });
                    }
                    if(!value) content = "Select Tag";
                    else  content = value.replace("' . $settings["chronosly_dad_tag_separator"] . '", "");';
        }
    } // END
} // END
