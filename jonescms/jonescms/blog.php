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
  require_once( "include/common.php" );

  function main( ) {
    global $dbi;
    $blog_entries_res = sql_query( "select id, category, entry_date, entry_title, entry_text, reads from jones_blog order by id desc", $dbi );
    include 'header.php';
?>    
    <div id="main">
      <div id="pageHeader">
        <h1>Blog</h1>
      </div>
<?php
    while( list( $blog_entry_id, $blog_entry_category, $blog_entry_date, $blog_entry_title, $blog_entry_text, $blog_entry_reads ) = sql_fetch_row( $blog_entries_res, $dbi ) ) {
      if( $blog_entry_category != '0' )
        list( $blog_entry_category_name, $blog_entry_category_image ) = sql_fetch_row( sql_query( "select name, image from jones_blog_category where id='$blog_entry_category'", $dbi ), $dbi );
      else
        $blog_entry_category_name = "";
      $blog_comments = sql_num_rows( sql_query( "select * from jones_blog_comment where blog_entry_id='$blog_entry_id'", $dbi ), $dbi );
?>
        <div class="contentBox">
          <div class="blogEntry">
            <img src="image.php?id=<?php echo $blog_entry_category_image; ?>" alt="<?php echo $blog_entry_category_name; ?>" />
            <span class="blogDate"><?php echo $blog_entry_date; ?></span>
            <h1><a href="blog.php?cmd=viewCategory&amp;id=<?php echo $blog_entry_category; ?>"><?php echo $blog_entry_category_name; ?></a> - <?php echo $blog_entry_title; ?></h1>
            <p><?php echo htmlFormat( $blog_entry_text ); ?></p>
            <!-- images -->
<?php
      $res = sql_query( "SELECT id, filename, height, width FROM jones_binarydata WHERE blogentry_id='$blog_entry_id'", $dbi );
      $pics_num = sql_num_rows( $res, $dbi );
      if( $pics_num > 0 ) {
?>
              <table>
<?php
      $n = 0;
      $pics_per_row = 6;
      while( list( $img_id, $img_filename, $height, $width ) = sql_fetch_row( $res, $dbi ) ) {
        if( ($n % $pics_per_row) == 0 ) {
?>
                <tr>
<?php
        }
?>
                  <td>
                    <a href="image.php?id=<?php echo $img_id;?>" target="_blank" onclick="openCenteredWindow('popImage.php?id=<?php echo $img_id; ?>', '<?php echo $height; ?>', '<?php echo $width; ?>', '<?php echo $img_filename; ?>'); return false;">
                      <img src="thumbnail.php?id=<?php echo $img_id; ?>" alt="<?php echo $img_filename; ?>" width="50" height="50" />
                    </a>
                  </td>
<?php
        if( ((($n+1) % $pics_per_row) == 0) || ($n == $pics_num - 1) ) {
?>
                </tr>
<?php
        }
        $n++;
      }
?>
            </table>
<?php
    }
?>
            <div class="blogFooter">
              <a href="blog.php?cmd=readAll&amp;id=<?php echo $blog_entry_id; ?>">Read all</a>&nbsp;|&nbsp;Total reads: <?php echo $blog_entry_reads; ?>&nbsp;|&nbsp;Comments: <?php echo $blog_comments; ?>
            </div>
          </div>
        </div>
<?
    }
?>
    </div>
<?php    
    include 'footer.php';
  }

  function viewCategory( $id ) {
    global $dbi;
    $blog_entries_res = sql_query( "select id, category, entry_date, entry_title, entry_text, reads from jones_blog where category='$id' order by id desc", $dbi );
    include 'header.php';
?>    
    <div id="main">
      <div id="pageHeader">
        <h1>Blog</h1>
      </div>
<?php
    while( list( $blog_entry_id, $blog_entry_category, $blog_entry_date, $blog_entry_title, $blog_entry_text, $blog_entry_reads ) = sql_fetch_row( $blog_entries_res, $dbi ) ) {
      if( $blog_entry_category != '0' )
        list( $blog_entry_category_name, $blog_entry_category_image ) = sql_fetch_row( sql_query( "select name, image from jones_blog_category where id='$blog_entry_category'", $dbi ), $dbi );
      else
        $blog_entry_category_name = "";
      $blog_comments = sql_num_rows( sql_query( "select * from jones_blog_comment where blog_entry_id='$blog_entry_id'", $dbi ), $dbi );
?>
        <div class="contentBox">
          <div class="blogEntry">
            <img src="image.php?id=<?php echo $blog_entry_category_image; ?>" alt="<?php echo $blog_entry_category_name; ?>" />
            <span class="blogDate"><?php echo $blog_entry_date; ?></span>
            <h1><a href="blog.php?cmd=viewCategory&amp;id=<?php echo $blog_entry_category; ?>"><?php echo $blog_entry_category_name; ?></a> - <?php echo $blog_entry_title; ?></h1>
            <p><?php echo htmlFormat( $blog_entry_text ); ?></p>

            <!-- images -->
<?php
      $res = sql_query( "SELECT id, filename, height, width FROM jones_binarydata WHERE blogentry_id='$blog_entry_id'", $dbi );
      $pics_num = sql_num_rows( $res, $dbi );
      if( $pics_num > 0 ) {
?>
              <table>
<?php
        $n = 0;
        $pics_per_row = 6;
        while( list( $img_id, $img_filename, $height, $width ) = sql_fetch_row( $res, $dbi ) ) {
          if( ($n % $pics_per_row) == 0 ) {
?>
                <tr>
<?php
          }
?>
                  <td>
                    <a href="image.php?id=<?php echo $img_id; ?>" target="_blank" onclick="openCenteredWindow('popImage.php?id=<?php echo $img_id; ?>', '<?php echo $height; ?>', '<?php echo $width; ?>', '<?php echo $img_filename; ?>'); return false;">
                      <img src="thumbnail.php?id=<?php echo $img_id; ?>" alt="<?php echo $img_filename; ?>" width="50" height="50" />
                    </a>
                  </td>
<?php
        if( ((($n+1) % $pics_per_row) == 0) || ($n == $pics_num - 1) ) {
?>
                </tr>
<?php
        }
        $n++;
      }
?>
            </table>
<?php
    }
?>

<div class="blogFooter">
              <a href="blog.php?cmd=readAll&amp;id=<?php echo $blog_entry_id; ?>">Read all</a>&nbsp;|&nbsp;Total reads: <?php echo $blog_entry_reads; ?>&nbsp;|&nbsp;Comments: <?php echo $blog_comments; ?>
           </div>
          </div>
        </div>
<?
    }
?>
    </div>
<?php    
    include'footer.php';
  }

  function readAll( $id ) {
    global $dbi;
    updateReads( $id );
    $blog_entry_res = sql_query( "select category, entry_date, entry_title, entry_text, entry_ext_text, reads from jones_blog where id='$id'", $dbi );
    include 'header.php';
?>    
    <div id="main">
      <div id="pageHeader">
        <h1>Blog</h1>
      </div>
<?php
    list( $blog_entry_category, $blog_entry_date, $blog_entry_title, $blog_entry_text, $blog_entry_ext_text, $blog_entry_reads ) = sql_fetch_row( $blog_entry_res, $dbi );
      if( $blog_entry_category != '0' )
        list( $blog_entry_category_name, $blog_entry_category_image ) = sql_fetch_row( sql_query( "select name, image from jones_blog_category where id='$blog_entry_category'", $dbi ), $dbi );
      else
        $blog_entry_category_name = "";
      $blog_comments = sql_num_rows( sql_query( "select * from jones_blog_comment where blog_entry_id='$id'", $dbi ), $dbi );
?>
        <div class="contentBox">
          <div class="blogEntry">
            <img src="image.php?id=<?php echo $blog_entry_category_image; ?>" alt="<?php echo $blog_entry_category_name; ?>" />
            <span class="blogDate"><?php echo $blog_entry_date; ?></span>
            <h1><a href="blog.php?cmd=viewCategory&amp;id=<?php echo $blog_entry_category; ?>"><?php echo $blog_entry_category_name; ?></a> - <?php echo $blog_entry_title; ?></h1>
            <p><?php echo htmlFormat( $blog_entry_text ); ?></p>
            <p><?php echo htmlFormat($blog_entry_ext_text ); ?></p>
            <!-- images -->
<?php
      $res = sql_query( "SELECT id, filename, height, width FROM jones_binarydata WHERE blogentry_id='$id'", $dbi );
      $pics_num = sql_num_rows( $res, $dbi );
      if( $pics_num > 0 ) {
?>
            <table>
<?php
      $n = 0;
      $pics_per_row = 6;
      while( list( $img_id, $img_filename, $height, $width ) = sql_fetch_row( $res, $dbi ) ) {
      if( ($n % $pics_per_row) == 0 ) {
?>
              <tr>
<?php
      }
?>
                <td>
                  <a href="image.php?id=<?php echo $img_id; ?>" target="_blank" onclick="openCenteredWindow('popImage.php?id=<?php echo $img_id; ?>', '<?php echo $height; ?>', '<?php echo $width; ?>', '<?php echo $img_filename; ?>'); return false;">
                    <img src="thumbnail.php?id=<?php echo $img_id; ?>" alt="<?php echo $img_filename; ?>" width="50" height="50" />
                  </a>
                </td>
<?php
      if( ((($n+1) % $pics_per_row) == 0) || ($n == $pics_num - 1) ) {
?>
              </tr>
<?php
      }
      $n++;
    }
?>
            </table>
<?php
    }
?>

            <div class="blogFooter">
              Total reads: <?php echo $blog_entry_reads; ?>&nbsp;|&nbsp;Comments: <?php echo $blog_comments; ?>
            </div>
          </div>
        </div>
<?php
      if( $blog_comments > 0 ) {
?>
          <div class="contentBox">
<?php
        $comments_res = sql_query( "select poster, email, subject, body, comment_date from jones_blog_comment where blog_entry_id='$id'", $dbi );
        while( list( $poster, $email, $subject, $body, $when ) = sql_fetch_row( $comments_res, $dbi ) ) { 
?>
            <div class="comment">
              <span class="date"><?php echo $when; ?></span>
              <h1><?php echo $subject; ?></h1>
              <p><?php echo $body; ?></p>
              <span class="poster"><a href="mailto:<?php echo $email; ?>"><?php echo $poster; ?></a></span>
            </div>
<?php
        }
      }
?>
          <div id="postComment">
            <form action="blog.php" method="post">
              <fieldset>
                <input type="hidden" name="cmd" value="postComment" />
                <input type="hidden" name="blog_entry_id" value="<?php echo $id; ?>" />
                <table>
                  <caption>Post a comment</caption>
                  <tr>
                    <th>Name</th>
                    <td><input type="text" name="name" size="30" maxlength="20" /></td>
                  </tr>
                  <tr>
                    <th>Email</th>
                    <td><input type="text" name="email" size="30" maxlength="60" /></td>
                  </tr>
                  <tr>
                    <th>Subject</th>
                    <td><input type="text" name="subject" size="30" maxlength="255" /></td>
                  </tr>
                  <tr>
                    <th>Text</th>
                    <td><textarea rows="3" cols="25" name="body"></textarea></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <input type="submit" value="Submit" />
                    </td>
                  </tr>                  
                </table>
              </fieldset>
            </form>
          </div>
        </div>
    </div>
<?php    
    include 'footer.php';
  }
  
  function postComment( $blog_entry_id, $name, $email, $subject, $body ) {
    global $dbi;
    $now = date( "M, d Y @ h:i a" );
    sql_query( "insert into jones_blog_comment values( null, '$blog_entry_id', '$name', '$email', '$subject', '$body', '$now' )", $dbi );
    Header( "Location: blog.php?cmd=readAll&id=$blog_entry_id" );
  }

	set_error_handler("myErrorHandler");
	
  if( isSet( $_GET['cmd'] ) )
  	$cmd = $_GET['cmd'];
  else if( isSet( $_POST['cmd'] ) )
  	$cmd = $_POST['cmd'];
  else
  	$cmd = "";

  switch( $cmd ) {
    case "viewCategory":
      viewCategory( $_GET['id'] );
      break;
    case "readAll":
      readAll( $_GET['id'] );
      break;
    case "postComment":
      postComment( $_POST['blog_entry_id'], $_POST['name'], $_POST['email'], $_POST['subject'], $_POST['body'] );
      break;
    default:
      main( );
      break;
  }
?>
