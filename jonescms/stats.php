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

  include 'base.php';
  require_once 'include/common.php';
  include 'header.php';

  $result = sql_query( "select * from jones_session", $dbi );
  $online = sql_num_rows( $result, $dbi );

  $result = sql_query( "select visitors from jones_stats where id='0'", $dbi );
  list( $visitors ) = sql_fetch_row( $result, $dbi );

  $result = sql_query( "select hits from jones_stats where id='0'", $dbi );
  list( $hits ) = sql_fetch_row( $result, $dbi );

  $result = sql_query( "select * from jones_blog", $dbi );
  $entries = sql_num_rows( $result, $dbi );
  
  $result = sql_query( "select * from jones_blog_comment", $dbi );
  $comments = sql_num_rows( $result, $dbi );

  $result = sql_query( "select sum(views) as images_views from jones_binarydata", $dbi );
  list( $images_views ) = sql_fetch_row( $result, $dbi );

?>

    <div id="main">
      <div id="pageHeader">
        <h1 id="stats"><span>Statistics</span></h1>
      </div>
      <div class="contentBox">
        <table>
          <caption>Statistics</caption>
          <tr>
            <th><img src="img/stats/visitors.gif" alt="Visitors" /></th>
            <th>Unique visitors</th>
            <td><?php echo $visitors; ?></td>
          </tr>
          <tr>
            <th><img src="img/stats/mouse.gif" alt="Hits" /></th>
            <th>Page Hits</th>
            <td><?php echo $hits; ?></td>
          </tr>
          <tr>
            <th><img src="img/stats/online.gif" alt="Online" /></th>
            <th>Visitors online</th>
            <td><?php echo $online; ?></td>
          </tr>
          <tr>
            <th><img src="img/stats/blog.gif" alt="Blog entries" /></th>
            <th>Blog entries</th>
            <td><?php echo $entries; ?></td>
          </tr>
          <tr>
            <th><img src="img/stats/comment.gif" alt="Comments" /></th>
            <th>Comments</th>
            <td><?php echo $comments; ?></td>
          </tr>
          <tr>
            <th><img src="img/stats/images.gif" alt="Images views" /></th>
            <th>Images views</th>
            <td><?php echo $images_views; ?></td>
          </tr>
        </table>
      </div>
    </div>

<?php
  include 'footer.php';
?>
