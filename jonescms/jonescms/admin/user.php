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

  function main( $error, $uid, $uname, $fname, $lname, $email ) {
    global $dbi;
    include 'header.php';
    list( $uname, $fname, $lname, $email ) = getUserData( $uid );
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administration Area</span></h1>
        <h2><span>Edit user properties</span></h2>
      </div>
      <div class="contentBox">
        <p class="error"><?php echo $error; ?></p>
        <form method="post" action="user.php">
          <fieldset>
            <input type="hidden" name="cmd" value="finish" />
            <input type="hidden" name="uid" value="<?php echo $uid; ?>" />
            <input type="hidden" name="uname" value="<?php echo $uname; ?>" />
            <table>
              <caption>User data</caption>
              <tr>
                <th>Username:</th>
                <td><input type="text" value="<?php echo $uname; ?>" maxlength="20" disabled /></td>
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
                <th>Re-type:</th>
                <td><input type="password" name="pwd2" /></td>
              </tr>
              <tr>
                <th>Email:</th>
                <td><input type="text" name="email" value="<?php echo $email; ?>"/></td>
              </tr>
              <tr>
                <td class="buttons" colspan="2">
                  <input type="submit" value="Submit" />
                  <input type="reset" value="Reset" />
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

  function finish( $uid, $uname, $pwd1, $pwd2, $fname, $lname, $email ) {
    global $dbi;

    # is it a valid email?
    if( (!$email) || ($email == "") || (!eregi( "^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $email ) ) || (strrpos( $email, ' ' ) > 0 ) ) {
      $error = "Invalid email address!";
      Header( "Location: user.php?error=$error&uid=$uid&uname=$uname&fname=$fname&lname=$lname&email=$email" );
      die( );
    }
   
    if( strlen( $pwd1) > 0 || strlen( $pwd2 ) > 0 ) {
      # do the passwords differ?
      if( strcmp($pwd1, $pwd2 ) != 0 ) {
        $error = "The two passwords provided differ!";
        Header( "Location: user.php?error=$error&uid=$uid&uname=$uname&fname=$fname&lname=$lname&email=$email" );
        die( );
      }
      # is the pwd longer than 6 chars?
      if( strlen( $pwd1 ) < 6 ) {
        $error = "Password cannot be shorter than 6 chars!";
        Header( "Location: user.php?error=$error&uid=$uid&uname=$uname&fname=$fname&lname=$lname&email=$email" );
        die( );
      }

      # cypher pwd
      $pwd = md5( $pwd1 );

      sql_query( "update jones_user set pwd='$pwd', fname='$fname', lname='$lname', email='$email' where id='$uid'", $dbi );
      list( $privs ) = sql_fetch_row( sql_query( "select privs from jones_user where id='$uid'", $dbi ), $dbi );
      sendCookie( $uid, $uname, $pwd, $fname, $lname, $email, $privs );
    } else {
      sql_query( "update jones_user set fname='$fname', lname='$lname', email='$email' where id='$uid'", $dbi );
    }
    Header( "Location: index.php" );
  }

  switch( $_POST['cmd'] ) {
    case "finish":
      finish( $_POST['uid'], $_POST['uname'], $_POST['pwd1'], $_POST['pwd2'], $_POST['fname'], $_POST['lname'], $_POST['email'] );
      break;
    default:
      main( $_GET['error'], $_GET['uid'], $_GET['uname'], $_GET['fname'], $_GET['lname'], $_GET['email'] );
      break;  }
?>
