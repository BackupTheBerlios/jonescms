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
 
require_once( "include/common.php" );

function main( $error ) {
  include 'header.php';
?>    
    <div id="main">
      <div id="pageHeader">
        <h1><span>Login</span></h1>
      </div>
      <div class="contentBox">
        <p class="error"><?php echo $error; ?></p>
        <form action="login.php" method="post">
          <fieldset>
            <table>
              <caption>Login</caption>
              <tr>
                <th>Username:</th>
                <td><input type="text" name="uname" /></td>
              </tr>
              <tr>
                <th>Password:</th>
                <td><input type="password" name="pwd" /></td>
              </tr>
              <tr>
                <td class="buttons" colspan="2">
                  <input type="submit" value="Submit" />
                  <input type="reset" value="Reset" />
                </td>
              </tr>
            </table>
            <input type="hidden" name="cmd" value="finish" />
          </fieldset>
        </form>
      </div>
    </div>
<?php    
  include 'footer.php';
}

function finish( $uname, $pwd ) {
  global $dbi;
  $md5_pwd = md5( $pwd );

  $l_result = sql_query( "select * from jones_user where uname='$uname' and pwd='$md5_pwd'", $dbi );

  if( sql_num_rows( $l_result, $dbi ) == 0 ) {
    $error = "Incorrect username or password";
    Header( "Location: login.php?error=$error" );
    die( );
  }
  list( $uid, $uname, $pwd, $fname, $lname, $email, $privs ) = sql_fetch_row( $l_result, $dbi );
  sendCookie( $uid, $uname, $pwd, $fname, $lname, $email, $privs );
  Header( "Location: index.php" );
}

  if( isSet( $_GET['cmd'] ) )
  	$cmd = $_GET['cmd'];
  else if( isSet( $_POST['cmd'] ) )
  	$cmd = $_POST['cmd'];
  	
switch( $cmd ) {
  case "finish":
    finish( $_POST["uname"], $_POST["pwd"] );
    break;
  default:
    main( $_GET["error"] );
    break;
}
?>
