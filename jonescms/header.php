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

  if( eregi( "header.php", $_SERVER['PHP_SELF'] ) ) {
    Header( "Location: index.php" );
    die( );
  }

  $num_users = num_users( );
  if( $num_users == 0 ) {
    Header( "Location: init.php" );
    die( );
  }
 
  list( $title, $sitename, $topname, $logo, $theme, $homepage ) = getSiteData( );
  $cookie = getCookie( );

  updateHits( );
  enterSession( );

  header("Content-type: text/html; charset=iso-8859-1");
  echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <link rel="stylesheet" href="themes/<?php echo $theme; ?>/style.css" type="text/css" />
    <title><?php echo $title; ?></title>
    <script type="text/javascript" src="common.js"></script>
  </head>
  <body>
    <div id="container">
      <div id="banner">
<?php
  if( $logo != "0" ) {
?>
        <img id="logo" src="image.php?id=<?php echo $logo; ?>" alt="<?php echo $sitename; ?>" />
<?php
  }
?>
        <div id="topName"><?php echo $topname; ?></div>
        <div id="login">
<?php
  if( $cookie != false ) {
?>
          Hello, <?php echo $cookie[1]; ?>. <a href="logout.php">Logout</a>.
<?php          
  } else {
?>
          <a href="login.php">Login</a>
<?php
  }
?>
        </div>
      </div>
<?php
  include 'menu_t0.php';
?>
      <div id="floats">
<?php
  include 'menu_t1.php';
  include 'banners.php';
?>
      </div> <!-- floats -->
