<?php

if(!class_exists('Chronosly_Utils')){
    class Chronosly_Utils{

        public function rrmdir($dir) {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object);
                        else unlink($dir."/".$object);
                    }
                }
                reset($objects);
                rmdir($dir);
            }
        }

        public function rcopy($src, $dst) {
                // if (file_exists ( $dst ))
                //     $this->rrmdir ( $dst );
                if (is_dir ( $src )) {
                    if(!file_exists($dst) && !is_dir($dst)) mkdir ( $dst );
                    $files = scandir ( $src );
                    foreach ( $files as $file )
                        if ($file != "." && $file != "..")
                            $this->rcopy ( "$src/$file", "$dst/$file" );
                } else if (file_exists ( $src ))
                    copy ( $src, $dst );
        }


        public static function validate_closure($html){
            $tags   = array();
            $result = "";

            $is_open   = false;
            $grab_open = false;
            $is_close  = false;
            $in_double_quotes = false;
            $in_single_quotes = false;
            $same_line = false;
            $tag = "";

            $i = 0;
            $stripped = 0;
            $topen = 0;
            $negative = 0;


            $stripped_text = strip_tags($html);

            while ($i < strlen($html))
            {
                $symbol  = $html{$i};
                $result .= $symbol;
                // echo $symbol."<br>";
                if($negative) --$negative; //para los \'  o \"
                switch ($symbol)
                {
                    case '<':
                        $is_open   = true;
                        $grab_open = true;
                        break;

                    case '"':
                        if(!$negative){
                            if ($in_double_quotes)
                                $in_double_quotes = false;
                            else
                                $in_double_quotes = true;
                        }

                        break;

                    case "'":
                         if(!$negative){
                            if ($in_single_quotes)
                                $in_single_quotes = false;
                            else
                                $in_single_quotes = true;
                        }
                        break;

                    case '/':
                        if ($is_open && !$in_double_quotes && !$in_single_quotes)
                        {
                            $is_close  = true;
                            $is_open   = false;
                            $grab_open = false;
                        }

                        break;
                    case '\\':
                       $negative = 2;

                        break;

                    case ' ':
                        if ($is_open)
                            $grab_open = false;
                        else
                            $stripped++;

                        break;

                    case '>':
                        if ($is_open )
                        {
                            $is_open   = false;
                            $grab_open = false;
                            array_push($tags, $tag);

                            $pushed = true;
                             // echo "empieza $tag<br/>";
                            $tag = "";
                            // print_r($tags);
                        }
                        else if ($is_close)
                        {
                            $is_close = false;
                            $old_tag = array_pop($tags);
                            if($tag != $old_tag) array_push($tags, $old_tag);
                             // echo "acaba $tag<br/>";
                            // print_r($tags);

                            $tag = "";
                        }

                        break;

                    default:
                        if ($grab_open || $is_close)
                            $tag .= $symbol;

                        if (!$is_open && !$is_close)
                            $stripped++;
                }

                $i++;

            }


            return !count($tags);

        }


    }
}
