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

  global $dbi;
  $lb_result1 = sql_query( "select id, title from jones_banner_area order by weight asc", $dbi );
  while( list( $banner_area_id, $banner_area_title ) = sql_fetch_row( $lb_result1, $dbi ) ) {
    $lb_banner_result = sql_query( "select img, link from jones_banner where area_id='$banner_area_id' order by weight asc", $dbi );
?>
      <div class="bannerBox">
        <div class="title"><?php echo $banner_area_title; ?></div>
        <ul>
<?php
    while( list( $bannerImg, $bannerLink ) = sql_fetch_row( $lb_banner_result, $dbi ) ) {
?>
          <li>
            <a href="<?php echo $bannerLink; ?>">
              <img src="<?php echo $bannerImg; ?>" alt="" />
            </a>
          </li>
<?php
    }
?>
          </ul>
        </div>
<?php
  }
?>
