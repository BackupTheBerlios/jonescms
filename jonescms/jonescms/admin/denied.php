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

  require_once( "../include/common.php" );
  
  list( $title, $sitename, $topname, $logo, $theme, $homepage ) = getSiteData( );
  
  header("Content-type: text/html; charset=iso-8859-1");
  echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <link rel="stylesheet" href="../themes/<?php echo $theme; ?>/style.css" type="text/css" />
    <title><?php echo $title; ?></title>
  </head>
  <body>
    <div id="container">
      <div id="banner">
        <img id="logo" src="../img/<?php echo $logo; ?>" alt="<?php echo $sitename; ?>" />
        <div id="topName"><?php echo $topname; ?></div>
        <div id="login">
          <a href="../login.php">Login</a>
        </div>
      </div>
<?php
  include 'menu.php';
?>
      <div id="main">
        <div id="pageHeader">
          <h1>Permission denied</h1>
        </div>
        <div class="contentBox">
          <p class="error">
            You don't have the right privileges to access this page.
          </p>
          <p>
            You might want to <a href="../login.php">login</a> to access the specified resource.
          </p>
        </div>
      </div>
<?php
  include 'footer.php';
?>
