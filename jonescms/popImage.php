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

  require_once 'include/common.php';
  $id = $_GET['id'];

  updateImageViews( $id );
	
  $res = sql_query( "SELECT width, height, description, views FROM jones_binarydata WHERE id='$id'", $dbi );
  list( $width, $height, $description, $views ) = sql_fetch_row( $res, $dbi );
  list( $title, $sitename, $topname, $logo, $theme, $homepage ) = getSiteData( );

  header("Content-type: text/html; charset=iso-8859-1");
  echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
  	<meta content-type="text/html" />
    <title>Show image</title>
    <link rel="stylesheet" href="themes/<?php echo $theme; ?>/style.css" type="text/css" />
  </head>
  <body style="background:none">
    <div id="container" style="margin:0px;;width: <?php echo $width+10; ?>px">
      <div class="contentBox" style="width: <?php echo $width+10; ?>px">
        <table>
          <tr>
            <td>
              <a href="#" onclick="window.close( )"><img style="border: 0px" src="image.php?id=<?php echo $id; ?>" alt="Image" /></a>
            </td>
          </tr>
          <tr>
            <td>
              <strong>Views:</strong>&nbsp;<?php echo $views; ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $description; ?>
            </td>
          </tr>
        </div>
      </div>
    </div>
  </body>
</html>
