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

  function main( $error, $title, $sitename, $topname, $logo, $theme, $homepage ) {
    include 'header.php';
    list( $title, $sitename, $topname, $logo, $theme, $homepage ) = getSiteData( );
    $themeslist = getThemesList( );
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administration Area</span></h1>
        <h2><span>Edit site properties</span></h2>
      </div>
      <div class="contentBox">
          <p class="error"><?php echo $error; ?></p>
          <form method="post" action="properties.php">
            <fieldset>
              <table>
                <caption>Site data</caption>
                <tr>
                  <th>Title:</th>
                  <td><input type="text" name="title" value="<?php echo $title; ?>" maxlength="60" /></td>
                </tr>
                <tr>
                  <th>Name:</th>
                  <td><input type="text" name="sitename" value="<?php echo $sitename; ?>" maxlength="60" /></td>
                </tr>
                <tr>
                  <th>Slogan:</th>
                  <td><input type="text" name="topname" value="<?php echo $topname; ?>" maxlength="60" /></td>
                </tr>
                <tr>
                  <th>Logo:</th>
                  <td>
                    <select name="logo">
                      <option value="#">-- None --</option>
<?php
                      $res = sql_query( "SELECT id, filename FROM jones_binarydata", $dbi );
                      while( list( $img_id, $img_filename ) = sql_fetch_row( $res, $dbi ) ) {
?>
                        <option value="<?php echo $img_id; ?>"<?php if( $img_id == $logo ) echo " selected"; ?>><?php echo $img_filename; ?></option>
<?php
                      }
?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <th>Theme:</th>
                  <td>
                    <select name="theme">
<?php
    for( $i = 0; $i < sizeof( $themeslist ); $i++ ) {
      if( $themeslist[ $i ] != "" ) {
?>
                      <option value="<?php echo $themeslist[ $i ]; ?>"
<?php
        if( $themeslist[ $i ] == $theme)
          echo "selected";
?>
                        ><?php echo $themeslist[ $i ]; ?></option>
<?php
      }
    }
?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <th>Homepage:</th>
                  <td>
                    <select name="homepage">
                      <option value="blog.php">Blog</option>
<?php
    $result = sql_query( "select id, label from jones_page", $dbi );
    while( list( $page_id, $page_label ) = sql_fetch_row( $result, $dbi ) ) {
?>
                      <option value="page.php?id=<?php echo $page_id; ?>"
<?php
  $b = strcmp( trim( $homepage ), trim( "page.php?id=".$page_id ) );
  if( $b == 0 )
    echo " selected";
?>
                        ><?php echo $page_label; ?></option>
<?php
    }
?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td class="buttons" colspan="2">
                    <input type="submit" value="Submit" />
										<input type="reset" value="Reset" />
										<input type="hidden" name="cmd" value="finish" />
                  </td>
                </tr>
              </table>
            </fieldset>
          </form>
      </div>
      <div class="backLink">
        <a href="index.php">Back to Main Menu</a>
      </div>
    </div>
<?php
    include 'footer.php';
  }

  function finish( $title, $sitename, $topname, $logo, $theme, $homepage ) {
    global $dbi;
    sql_query( "update jones_site set title='$title', sitename='$sitename', topname='$topname', logo='$logo', theme='$theme', homepage='$homepage'", $dbi );
    Header( "Location: index.php" );
  }

  switch( $_POST['cmd'] ) {
    case "finish":
      finish( $_POST['title'], $_POST['sitename'], $_POST['topname'], $_POST['logo'], $_POST['theme'], $_POST['homepage'] );
      break;
    default:
      main( $_GET['error'], $_GET['title'], $_GET['sitename'], $_GET['topname'], $_GET['logo'], $_GET['theme'], $_GET['homepage'] );
      break;  }
?>
