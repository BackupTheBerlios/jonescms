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

  $base = dirname( realpath( '../index.php' ) );
  require_once( $base."/include/common.php" );
  include 'header.php';
  $uid = $cookie[0];
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administration Area</span></h1>
        <h2><span>Main Menu</span></h2>
      </div>
      <div class="contentBox">
        <div id="mainMenu">
          <div class="item">
            <a href="user.php?uid=<?php echo $uid; ?>"><img src="img/user.gif" alt="User properties" /></a>
            <span class="label"><a href="user.php?uid=<?php echo $uid; ?>">User properties</a></span>
            <span class="description">Use this function to edit your properties, such as your username, password or email.</span>
          </div>
          <div class="item">            
            <a href="properties.php"><img src="img/properties.gif" alt="Site properties" /></a>
            <span class="label"><a href="properties.php">Site properties</a></span>
            <span class="description">Use this function to edit properties of the site, such as its name, title, logo or stylesheet.</span>
          </div>
          <div class="item">
            <a href="menus.php"><img src="img/menu.gif" alt="Menu manager" /></a>
            <span class="label"><a href="menus.php">Menu manager</a></span>
            <span class="description">You can create menus which display on custom positions according to their type.</span>
          </div>
          <div class="item">
            <a href="banners.php"><img src="img/banners.gif" alt="Banners manager" /></a>
            <span class="label"><a href="banners.php">Banners manager</a></span>
            <span class="description">You can create a space in which to add advertisement banners.</span>
          </div>
          <div class="item">
            <a href="pages.php"><img src="img/page.gif" alt="Pages manager" /></a>
            <span class="label"><a href="pages.php">Pages manager</a></span>
            <span class="description">You can create pages in HTML, and assign them to a menu.</span>
          </div>
          <div class="item">
            <a href="blog.php"><img src="img/blog.gif" alt="Blog manager" /></a>
            <span class="label"><a href="blog.php">Blog manager</a></span>
            <span class="description">Add, edit and delete entries in your Blog. You can even create categries which to put your entry in.</span>
          </div>
          <div class="item">
            <a href="repository.php"><img src="img/repository.gif" alt="Images repository" /></a>
            <span class="label"><a href="repository.php">Images repository</a></span>
            <span class="description">Manage all the images of the site, store and delete them from the database.</span>
          </div>
        </div>
        <div class="spacer">&nbsp;</div>
      </div>
    </div>
<?php
  include 'footer.php';
?>
