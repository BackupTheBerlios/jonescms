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

  function main( $error ) {
    global $dbi;
    $res = sql_query( "SELECT id, filename, description, width, height, thumbnail FROM jones_binarydata WHERE blogentry_id=0", $dbi );

    include 'header.php';

?>
    <div id="main">
      <div id="pageHeader">
        <h1 id="admin_menu"><span>Administrative Menu</span></h1>
        <h2 id="images_repository"><span>Images repository</span></h2>
      </div>
<?php
    if( isSet( $error ) && $error != "" ) {
      switch( $error ) {
        case "1": $error_string = "Cannot upload image: invalid file type"; break;
        default: $error_string = "Error"; break;
      }
?>
      <div class="contentBox">
        <p class="error">
          <?php echo $error_string; ?>
        </p>
      </div>
<?php
    }
?>
      <div class="contentBox">
<?php
    if( sql_num_rows( $res ) > 0 ) {
?>
        <table class="resume">
          <caption>Existing images</caption>
          <tr>
            <th>&nbsp;ID&nbsp;</th>
            <th>Thumbnail</th>
            <th><em>Operation</em></th>
          </tr>
<?php
    while( list( $img_id, $filename, $description, $width, $height, $thumbnail ) = sql_fetch_row( $res, $dbi ) ) {
?>
          <tr>
            <td><?php echo $img_id; ?></td>
            <td><img src="../thumbnail.php?id=<?php echo $img_id; ?>" alt="<?php echo $filename; ?>" /></td>
            <td>
              <a href="#" onClick="javascript: openCenteredWindow('../popImage.php?id=<?php echo $img_id; ?>', '<?php echo $height + 10; ?>', '<?php echo $width + 10; ?>', '<?php echo $filename; ?>')"><img src="img/view.gif" alt="View" title="View" /></a>
              <a href="<?php echo $_SERVER['PHP_SELF']; ?>?cmd=deleteImage&id=<?php echo $img_id; ?>"><img src="img/delete.gif" alt="Delete" title="Delete" /></a>
            </td>
          </tr>
<?php
    }
?>
        </table>
      </div>
      <div class="contentBox">
<?php
  }
?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
          <fieldset>
            <input type="hidden" name="cmd" value="finishUpload" />
            <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
            <table class="adminForm">
              <caption>Insert a new image</caption>
              <tr>
                <th>Image</th>
                <td><input class="file" type="file" name="image_file" size="40" /></td>
               </tr>
              <tr>
                <td class="buttons" colspan="2"><input type="submit" value="Submit" /></td>
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


  function finishUpload( $image_file, $type, $size, $name ) {
    global $dbi;
    $filesize = filesize( $image_file );
    if (!($fp = @fopen( $image_file, 'rb'))) {
      trigger_error( "Can't open file $image_file", E_USER_WARNING );
      die( );
    }

    $data = fread( $fp, $filesize );

    switch( $type ) {
      case "image/jpeg":
        // generate thumbnail
        $src_img = ImageCreateFromJPEG( $image_file ); // create a image resource with your original image in it
        if( ! $src_img ) {
          trigger_error( "Can't create image from jpeg", E_USER_WARNING );
          die( );
        }
        $width = ImageSx( $src_img ); //get original image width
        $height = ImageSy( $src_img ); //get original image height
    
        //scale the image
        $dest_width = 50;
        $dest_height = 50;
        $dest_img = ImageCreate( $dest_width, $dest_height );
        if( !$dest_img ) {
          trigger_error( "Can't create thubnails canvas", E_USER_WARNING );
          die( );
        }
    
        $result = ImageCopyResized( $dest_img, $src_img, 0 ,0 ,0 ,0 , $dest_width, $dest_height, $width, $height );
        if( ! $result ) {
          trigger_error( "Can't resize the image", E_USER_WARNING );
          die( );
        }

        ob_start( );
        ImageJPEG( $dest_img );
        $thumbnail = ob_get_contents( );
        ob_end_clean( );
        break;
      case "image/gif":
         // generate thumbnail
        $src_img = ImageCreateFromGIF( $image_file ); // create a image resource with your original image in it
        if( ! $src_img ) {
          trigger_error( "Can't create image from gif", E_USER_WARNING );
          die( );
        }
        $width = ImageSx( $src_img ); //get original image width
        $height = ImageSy( $src_img ); //get original image height
    
        //scale the image
        $dest_width = 50;
        $dest_height = 50;
        $dest_img = ImageCreate( $dest_width, $dest_height );
        if( !$dest_img ) {
          trigger_error( "Can't create thubnails canvas", E_USER_WARNING );
          die( );
        }
    
        $result = ImageCopyResized( $dest_img, $src_img, 0 ,0 ,0 ,0 , $dest_width, $dest_height, $width, $height );
        if( ! $result ) {
          trigger_error( "Can't resize the image", E_USER_WARNING );
          die( );
        }

        ob_start( );
        ImageGIF( $dest_img );
        $thumbnail = ob_get_contents( );
        ob_end_clean( );
        break;
      default:
        Header( "Location: ".$_SERVER['PHP_SELF']."?error=1" );
        die( );
        break;        
     }
        
        
    
    // populate db
    $now = date( "Y/m/d" );
    $data = addslashes( $data );
    $thumbnail = addslashes( $thumbnail );
    $res = sql_query( "INSERT INTO jones_binarydata VALUES( NULL, '$name', '$size', '$type', '$now', '', '$data', '$width', '$height', '$thumbnail', '0' )", $dbi );

    ImageDestroy( $dest_img );
    ImageDestroy( $src_img );
    if( $res )
      Header( "Location: ".$_SERVER['PHP_SELF'] );
  }
 
  function deleteImage( $id ) {
    global $dbi;
    sql_query( "DELETE FROM jones_binarydata WHERE id='$id'", $dbi );
    Header( "Location: ".$_SERVER['PHP_SELF'] );
  }

  set_error_handler("myErrorHandler");

  if( isSet( $_GET['cmd'] ) )
  	$cmd = $_GET['cmd'];
  else if( isSet( $_POST['cmd'] ) )
  	$cmd = $_POST['cmd'];
  else
  	$cmd = "";
  	
  switch( $cmd ) {
    case "finishUpload":
      finishUpload( $_FILES['image_file']['tmp_name'], $_FILES['image_file']['type'], $_FILES['image_file']['size'], $_FILES['image_file']['name'] );
      break;
    case "deleteImage":
      deleteImage( $_GET['id'] );
      break;
    default:
      main( isSet( $_GET['error'] ) ? $_GET['error'] : "" );
      break;
  }
?>
