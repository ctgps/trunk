<?php

/*
Plugin Name: CTGPS Widget - Rank Review ( for old system only )
Description: View the rank of all students in specific course.
Version: 0.0.1
Author: Dinosoft dinosoft@qq.com
*/

function ctgps_widget_rank_review(){
?>
<form method="post" >
<label for="ctgps-target-course">把课程信息粘贴到这里:</label>
<input type="text" name="ctgps-target-course" />
<input type="submit" name="submit" value="查询" />
</form>
<?php
 if ( isset($_POST['ctgps-target-course'] ) ){
     $preg="/kkkh=([0-9]*)/i";
     preg_match_all($preg, $_POST['ctgps-target-course'] ,$target);
     //var_dump($target);
     $con = file_get_contents("http://202.116.64.33/zsujw/stdscoreanal.do?method=getCheckPageByTch&kkkh=".$target[1][0] );
     
     
     $con= iconv( "GBK", "UTF-8", $con);
     $beginPos =  mb_strpos($con, "<form");
     $endPos = mb_strpos($con, "</form>");
     $con  = mb_substr( $con, $beginPos, $endPos-$beginPos+7 );
     //var_dump( $con );
     $con = preg_replace("/<input.*>/u", "", $con);
   
     /* 居然不行...
     
     $preg="/(<tabla[\x{4e00}-\x{9fa5}.]*<\/table>)/u";
     var_dump($con);
     preg_match_all($preg,$con,$rank_table);
      var_dump($rank_table);
     foreach($rank_table[1] as $id=>$v){
       echo $v;
       
    }
     */
     ob_start();
     ?>
     <style type="css/text">
     #ctgps-rank-result td{
     padding:0px;
       
     }
     </style>
     <div id='ctgps-rank-result'>
     <?php
      echo $con."</div>";
 } 
}

add_shortcode("ctgps-widget-rank-review", "ctgps_widget_rank_review");
