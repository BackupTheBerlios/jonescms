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

function main( $error, $uname, $fname, $lname, $email, $title, $sitename, $topname, $logo, $theme )
{
  list( $title, $sitename, $topname, $logo, $theme, $homepage ) = getSiteData( );
  header("Content-type: text/html; charset=iso-8859-1");
  echo "<?xml version=\"1.0\" encoding=\"iso-iso-8859-1\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <link rel="stylesheet" href="themes/<?php echo $theme; ?>/style.css" type="text/css">
    <title><?php echo $title; ?></title>
  </head>
  <body>
    <div id="container">
      <div id="banner">
        <img id="logo" src="img/<?php echo $logo; ?>" alt="<?php echo $sitename; ?>" />
        <div id="topName"><?php echo $topname; ?></div>
      </div>
  
      <div id="main">
        <div id="pageHeader">
          <h1>First time initialization</h1>
          <h2>Wecolme to Jones' CMS. Fill in these simple data to get into the system, please.</h2>
        </div>
        <div class="contentBox">
            <p class="error"><?php echo $error; ?></p>
            <form method="post" action="init.php?cmd=finish">
              <table>
                <caption>User data</caption>
                <tr>
                  <th>Username:</th>
                  <td><input type="text" name="uname" value="<?php echo $uname; ?>" maxlength="20" /></td>
                </tr>
                <tr>
                  <th>First name:</th>
                  <td><input type="text" name="fname" value="<?php echo $fname; ?>" maxlength="20" /></td>
                </tr>
                <tr>
                  <th>Last name:</th>
                  <td><input type="text" name="lname" value="<?php echo $lname; ?>" maxlength="20" /></td>
                </tr>
                <tr>
                  <th>Password:</th>
                  <td><input type="password" name="pwd1" /></td>
                </tr>
                <tr>
                  <th>Re-type it:</th>
                  <td><input type="password" name="pwd2" /></td>
                </tr>
                <tr>
                  <th>Email:</th>
                  <td><input type="text" name="email" value="<?php echo $email; ?>" maxlength="30" /></td>
                </tr>
              </table>
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
                  <th>Logo URL:</th>
                  <td><input type="text" name="logo" value="<?php echo $logo; ?>" maxlength="60" /></td>
                </tr>
                <tr>
                  <th>Theme:</th>
                  <td><input type="text" name="theme" value="<?php echo $theme; ?>" maxlength="20" /></td>
                </tr>
              </table>
              <table class="normal">
                <tr>
                  <td class="normal"><input type="submit" value="Submit" /></td>
                  <td class="normal"><input type="reset" value="Reset" /></td>
                </tr>
              </table>
            </form>
        </div>
      </div>
    </body>
  </html>
<?php
}

function finish( $uname, $fname, $lname, $pwd1, $pwd2, $email, $title, $sitename, $topname, $logo, $theme )
{
  global $dbi;
  # is she already registered?
  if( num_users( ) > 0 ) {
    Header( "Location: index.php" );
    die( );
    echo "ASDASD";
  }
  # do the passwords differ?
  if( strcmp($pwd1, $pwd2 ) != 0 ) {
    $error = "The two passwords provided differ!";
    Header( "Location: init.php?error=$error&uname=$uname&fname=$fname&lname=$lname&email=$email" );
    die( );
  }
  # is the pwd longer than 6 chars?
  if( strlen( $pwd1 ) < 6 ) {
    $error = "Password cannot be shorter than 6 chars! Passowrd is = ".$pwd1;
    Header( "Location: init.php?error=$error&uname=$uname&fname=$fname&lname=$lname&email=$email" );
    die( );
  }
  # is it a valid email?
  if( (!$email) || ($email == "") || (!eregi( "^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $email ) ) || (strrpos( $email, ' ' ) > 0 ) ) {
    $error = "Invalid email address!";
    Header( "Location: init.php?error=$error&uname=$uname&fname=$fname&lname=$lname&email=$email" );
    die( );
  }
  # is it a valid username?
  if( (!$uname) || ($uname == "") || (ereg( "[^a-zA-Z0-9_-]", $uname) ) || (is_numeric( $uname{0} ) ) ) {
    $error = "Invalid username!";
    Header( "Location: init.php?error=$error&uname=$uname&fname=$fname&lname=$lname&email=$email" );
    die( );
  }

  # cypher pwd
  $pwd = md5( $pwd1 );

  # set privs at 0 (root)
  $privs = 0;

  # insert into db
  sql_query( "insert into jones_user values (NULL, '$uname', '$pwd', '$fname', '$lname', '$email', '$privs')", $dbi );
  sql_query( "update jones_site set title='$title', sitename='$sitename', topname='$topname', logo='$logo', theme='$theme'", $dbi );
  Header( "Location: index.php" );
}

switch( $_GET[ "cmd" ] ) {
  case "finish":
		finish( $_POST["uname"], $_POST["fname"], $_POST["lname"], $_POST["pwd1"], $_POST["pwd2"], $_POST["email"], $_POST["title"], $_POST["sitename"], $_POST["topname"], $_POST["logo"], $_POST["theme"] );
    break;
  default:
    main( $_GET["error"], $_GET["uname"], $_GET["fname"], $_GET["lname"], $_GET["email"], $_GET["title"], $_GET["sitename"], $_GET["topname"], $_GET["logo"], $_GET["theme"] );
    break;
}
?>
