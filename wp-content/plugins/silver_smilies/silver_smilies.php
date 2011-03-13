<?php
/*
Plugin Name: silver_smilies
Plugin URI: http://www.zdyi.com/wordpress-commend-plugin-2/389
Description: 评论表情插件
Version: 0.2
Author: silver
Author URI: http://www.zdyi.com

*/



if(!class_exists('silver_smilies'))

{

    class silver_smilies

    {

        private $img_dir;

        private $img_path;

        private $face_files = array();

        private $allow_extension = array();



        public function __construct($allow_extension)

        {

            $this->img_path = get_settings('siteurl') . '/wp-content/plugins/silver_smilies/face';

            $this->img_dir  = WP_PLUGIN_DIR . "/silver_smilies/face";

            $this->allow_extension = $allow_extension;

            $this->face_files = $this->get_face_files();

            add_action('comment_form', array(& $this, 'smilie_faces'));

            add_filter('comment_text', array(& $this, 'smilie_replace'));

        }



        private function get_face_files()

        {

            $files = array();

            if(is_dir($this->img_dir))

            {

                if ($dh = opendir($this->img_dir))

                {

                    while (($file = readdir($dh)) !== false)

                    {

                        if($file == '.') continue;

                        if($file == '..') continue;

                        $fileinfo = explode('.', (basename($file)));

                        if(in_array($fileinfo[1], $this->allow_extension))

                        {

                            $files[] = array(

                                'filename' => $fileinfo[0],

                                'extension' => $fileinfo[1],

                            );

                        }

                    }

                    closedir($dh);

                }

            }

            return $files;

        }





        // 显示表情图片

        public function smilie_faces()

        {

            $num = 0;
            foreach($this->face_files as $value)

            {
                ++$num;
                echo "<img src='{$this->img_path}/{$value['filename']}.{$value['extension']}' alt='{$value['filename']}' style='cursor:pointer;width=24px;height:24px;' onclick='return inface(\"{$value['filename']}\");'>";
                if ( $num % 25 == 0 ) echo "<br/>";

            }



            echo <<<END

            <script type="text/javascript">

            function inface(img)

            {

                document.getElementById("comment").value += '[face:' + img + ']';

            }

            </script>

END;

        }



        // 转换为表情图片

        public function smilie_replace($comment_text)

        {

            $keys = array();

            $smilies = array();

            foreach($this->face_files as $value)

            {

                $keys[] = "[face:{$value['filename']}]";

                $smilies[] = "<img src='{$this->img_path}/{$value['filename']}.{$value['extension']}' alt='{$value['filename']}'>";

            }



            $comment_text = str_replace($keys,$smilies,$comment_text);

            return $comment_text;

        }

    }

}



if(!isset($silver_smilies))

{

	$silver_smilies =& new silver_smilies(array('gif', 'jpeg', 'jpg', 'png'));

}

?>
