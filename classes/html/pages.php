<?php
/**
 * VDC 24
 *
 * Cloud hosting interface
 * @author Alla Mamontova <alla.mamontova@ubn24.de>
 * @version 1.0 2011
 * @package vdc
 */

/**
 * Gives you input of pages to display on html-page
 *
 * @author Alla Mamontova <alla.mamontova@ubn24.de>
 * @version 1.0 2011
 */
class pages
{

  private static function get_info($show_count, $count, $page)
  {
    $real_count = $count;
    if ($count == 0){
      $count = 1;
    }
    $count_pages = ceil($count/$show_count);
    if ($page < 1){
      $page = 1;
    }
    if ($page > $count_pages){
      $page = $count_pages;
    }

    $page_prev = $page - 1;
    $page_next = $page + 1;
    if ($page_prev < 1){
      $page_prev = 1;
    }
    if ($page_next > $count_pages){
      $page_next = $count_pages;
    }

    return array(
        'count_pages' => $count_pages
      , 'page_prev' => $page_prev
      , 'page_next' => $page_next
      , 'real_count' => $real_count
    );
  }

  private static function get_indexes($count_pages, $page)
  {
    $indexes = array();
    if ($count_pages > 5){
      $indexes = array();
      $indexes[1] = 1;
      $indexes[$count_pages] = $count_pages;
      $indexes[$page - 1] = $page - 1;
      $indexes[$page] = $page;
      $indexes[$page + 1] = $page + 1;
    }
    else{
      for ($i = 1; $i <= $count_pages; $i++){
        $indexes[] = $i;
      }
    }
    return $indexes;
  }

  public static function get_pages($show_count, $count, $page, $parser_pages, $parser_page, $parser_page_active, $parser_page_separator)
  {
    $html = '';

    $res = self::get_info($show_count, $count, $page);
    $count_pages = $res['count_pages'];
    $page_prev = $res['page_prev'];
    $page_next = $res['page_next'];
    $real_count = $res['real_count'];

    $pages = '';
    $pred_i = 0;
    $indexes = self::get_indexes($count_pages, $page);
    for ($i = 1; $i <= $count_pages; $i++){
      if (in_array($i,$indexes)){
        if ($pred_i + 1 < $i){
          $pages .= $parser_page_separator;
        }
        if ($page == $i){
          $pages .= str_replace('%%page%%',$i,$parser_page_active);
        }
        else{
          $pages .= str_replace('%%page%%',$i,$parser_page);
        }
        if ($i < 1){
          $num_page = $i - 1;
        }
        else{
          $num_page = $i;
        }
        $pages = str_replace('%%num_page%%',$num_page,$pages);
        $pred_i = $i;
      }
    }
    $html = str_replace('%%pages%%',$pages,$parser_pages);
    $html = str_replace('%%prev_page%%',$page_prev,$html);
    $html = str_replace('%%next_page%%',$page_next,$html);
    $html = str_replace('%%all_count%',$count,$html);
    $html = str_replace('%%from%%',$count ? ($page - 1) * $show_count + 1 : $count,$html);
    $html = str_replace('%%to%%',($page * $show_count > $count ? $count : $page * $show_count),$html);

    return $html;
  }
}
?>
