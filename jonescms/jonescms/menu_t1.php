<?php
/*
 * This file is part of JonesCMS.
 *
 * JonesCMS is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * JonesCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with JonesCMS; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

  if( eregi( "menu_t1.php", $_SERVER['PHP_SELF'] ) ) {
    Header( "Location: index.php" );
    die( );
  }
?>

<!-- Menu Type 1 -->
    <div id="menuType1">
<?php
  $lm_result1 = sql_query( "select id, title from jones_menu where menu_type='1' order by weight asc", $dbi );
  while( list( $menu_id, $menu_title ) = sql_fetch_row( $lm_result1, $dbi ) ) {
?>
      <div class="navType1">
        <div class="title"><?php echo $menu_title; ?></div>
        <ul class="navList">
<?php
    $lm_special_menu_result = sql_query( "select label, menu_type from jones_special_menu where menu_id='$menu_id' order by weight asc", $dbi );
    while( list( $sm_label, $sm_menu_type ) = sql_fetch_row( $lm_special_menu_result, $dbi ) ) {
      switch( $sm_menu_type ) {
        case "0": $sm_link = "index.php"; break;
        case "1": $sm_link = "admin/index.php"; break;
        case "2": $sm_link = "stats.php"; break;
        case "3": $sm_link = "blog.php"; break;
      }
?>
            <li><a href="<?php echo $sm_link; ?>"><?php echo $sm_label; ?></a></li>
<?php
    }
    $lm_result2 = sql_query( "select id, label from jones_page where menu_id='$menu_id' and parent='0' order by weight asc", $dbi );
    while( list( $page_id, $page_label ) = sql_fetch_row( $lm_result2, $dbi ) ) {
      $lm_result3 = sql_query( "select id, label from jones_page where parent='$page_id'", $dbi );
      if( sql_num_rows( $lm_result3, $dbi ) == 0 )
        $link = "page.php?id=$page_id";
      else
        $link = "#";
?>
            <li>
              <a href="<?php echo $link; ?>"><?php echo $page_label; ?></a>
<?php
      if( sql_num_rows( $lm_result3, $dbi ) > 0 ) {
        echo "<ul class=\"subNavList\">\n";
        while( list( $sub_page_id, $sub_page_title ) = sql_fetch_row( $lm_result3, $dbi ) ) {
?>
              <li><a href="page.php?id=<?php echo $sub_page_id; ?>"><?php echo $sub_page_title; ?></a></li>
<?php
        }
        echo "</ul>\n";
      }
?>
           </li>
<?php
    }    
?>
          </ul>
        </div>
<?php
  }
?>
      </div> <!-- menuType1 -->
