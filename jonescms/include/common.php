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
 
if( eregi( "common.php", $PHP_SELF ) )
{
  Header( "Location: index.php" );
  die( );
}

$base = dirname( realpath( 'common.php' ) );

require_once( $base."config.php" );
require_once( $base."sql_layer.php" );

$dbi = sql_connect( $dbhost, $dbuname, $dbpass, $dbname );
if( $dbi == null ) {
  echo "ERROR connecting to the DB";
  die( );
}

function myErrorHandler($errno, $errstr, $errfile, $errline) {
  echo "<br/>Error number $errno \"$errstr\" in $errfile on line $errline.<br/>";
} 

function getSiteData( ) {
  global $dbi;
  $result = sql_query( "select title, sitename, topname, logo, theme, homepage from jones_site", $dbi );
  return sql_fetch_row( $result, $dbi );
}

function getUserData( $uid  ) {
  global $dbi;
  $result = sql_query( "select uname, fname, lname, email from jones_user where id='$uid'", $dbi );
  return sql_fetch_row( $result, $dbi );
}

function num_users( ) {
  global $dbi;
  return sql_num_rows( sql_query( "select * from jones_user", $dbi ), $dbi );
}

function sendCookie( $uid, $uname, $pwd, $fname, $lname, $email, $privs ) {
  $info = base64_encode( "$uid:$uname:$pwd:$fname:$lname:$email:$privs" );
  setcookie( "jonesCMSUser", "$info", time( ) + 2592000, "/");
}

function unsetCookie( ) {
  setCookie( "jonesCMSUser", "", time( ) - 2592000, "/" );
}

function getCookie( ) {
  global $dbi;
  if( isSet( $_COOKIE['jonesCMSUser'] ) ) {
	  $user = base64_decode( $_COOKIE['jonesCMSUser'] );
	  $cookie = explode( ":", $user );
	  list( $db_pwd ) = sql_fetch_row( sql_query( "select pwd from jones_user where id='$cookie[0]'", $dbi ), $dbi );
	  if( ( strcmp( $cookie[2], $db_pwd ) == 0 ) && $db_pwd != "" ) {
	    return $cookie;
	  } else {
	    return false;
	  }
	} else
		return false;
}

function isRoot( $cookie ) {
  return ($cookie && ($cookie[6]=="0"));
}

function getThemesList( ) {
  $handle = opendir( '../themes' );
  while( $file = readdir( $handle ) ) {
    if( !ereg( "[.]", $file ) ) {
      $themeslist .= "$file ";
    }
  }
  closedir( $handle );
  $themeslist = explode( " ", $themeslist );
  sort( $themeslist );
  return $themeslist;
}

function getBlogImages( ) {
  $handle = opendir( '../img/blog' );
  while( $file = readdir( $handle ) ) {
    if( ereg( "^([_0-9a-zA-Z]+)([.]{1})([_0-9a-zA-Z]{3})$", $file ) ) {
      $list .= "$file ";
    }
  }
  closedir( $handle );
  $list = explode( " ", $list );
  sort( $list );
  return $list;
}

function getMaxMenuWeight( $type ) {
  global $dbi;
  $result = sql_query( "select max( weight ) from jones_menu where menu_type='$type'", $dbi );
  list( $weight ) = sql_fetch_row( $result, $dbi );
  return $weight;
}

function getMaxSpecialMenuWeight( ) {
  global $dbi;
  $result = sql_query( "select max( weight ) from jones_special_menu", $dbi );
  list( $weight ) = sql_fetch_row( $result, $dbi );
  return $weight;
}

function getMaxPageWeight( ) {
  global $dbi;
  $result = sql_query( "select max( weight ) from jones_page", $dbi );
  list( $weight ) = sql_fetch_row( $result, $dbi );
  return $weight;
}

function getMaxBannerAreaWeight( ) {
  global $dbi;
  $result = sql_query( "select max( weight ) from jones_banner_area", $dbi );
  list( $weight ) = sql_fetch_row( $result, $dbi );
  return $weight;
}

function getMaxBannerWeight( $area_id ) {
  global $dbi;
  $result = sql_query( "select max( weight ) from jones_banner where area_id='$area_id'", $dbi );
  list( $weight ) = sql_fetch_row( $result, $dbi );
  return $weight;
}

function updateHits( )
{
  global $dbi;

  $result = sql_query( "select hits from jones_stats where id='0'", $dbi );
  list( $hits ) = sql_fetch_row( $result, $dbi );
  $hits++;
  sql_query( "update jones_stats set hits='$hits' where id='0'", $dbi );
}

function updateVisitors( )
{
  global $dbi;

  $result = sql_query( "select visitors from jones_stats where id='0'", $dbi );
  list( $visitors ) = sql_fetch_row( $result, $dbi );
  $visitors++;
  sql_query( "update jones_stats set visitors='$visitors' where id='0'", $dbi );
}

function enterSession( )
{
  global $dbi;

  $ip = getenv( "REMOTE_ADDR" ); 
  $time = time( );

  $result = sql_query( "insert into jones_session values ('$ip', '$time')", $dbi );
  if( $result )
    updateVisitors( );    

  $gap = $time - 600;
  sql_query( "delete from jones_session where time < '$gap'", $dbi );
}

function updateReads( $id ) {
  global $dbi;
  list( $reads ) = sql_fetch_row( sql_query( "select reads from jones_blog where id='$id'", $dbi ), $dbi );
  $reads++;
  sql_query( "update jones_blog set reads='$reads' where id='$id'", $dbi );
}

function htmlFormat( $text ) {
  $ret = $text;

  $ret = htmlentities( $ret );
  $ret = nl2br( $ret );
  
  $ret = str_replace( "[i]", "<span style=\"font-style: italic\">", $ret );
  $ret = str_replace( "[b]", "<span style=\"font-weight: bold\">", $ret );
  $ret = str_replace( "[u]", "<span style=\"text-decoration:underline\">", $ret );
  $ret = str_replace( "[l]", "<div align=\"left\">", $ret );
  $ret = str_replace( "[j]", "<div align=\"justify\">", $ret );
  $ret = str_replace( "[r]", "<div align=\"right\">", $ret );
  $ret = str_replace( "[c]", "<div align=\"center\">", $ret );
  
  $ret = str_replace( "[/i]", "</span>", $ret );
  $ret = str_replace( "[/b]", "</span>", $ret );
  $ret = str_replace( "[/u]", "</span>", $ret );
  $ret = str_replace( "[/l]", "</div>", $ret );
  $ret = str_replace( "[/j]", "</div>", $ret );
  $ret = str_replace( "[/r]", "</div>", $ret );
  $ret = str_replace( "[/c]", "</div>", $ret );

  $ret = str_replace( "[code]", "<pre>", $ret );
  $ret = str_replace( "[/code]", "</pre>", $ret );
  return $ret;
}

function updateImageViews( $id )
{
  global $dbi;

  $result = sql_query( "select views from jones_binarydata where id='$id'", $dbi );
  list( $views ) = sql_fetch_row( $result, $dbi );
  $views++;
  sql_query( "update jones_binarydata set views='$views' where id='$id'", $dbi );
}

?>
