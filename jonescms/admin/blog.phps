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

  function main( ) {
    include 'header.php';

    $res_cats = sql_query( "select id, name from jones_blog_category", $dbi );
    $res_cats_full = sql_query( "select id, name, parent, image from jones_blog_category", $dbi );
    $res_blog_entries = sql_query( "select id, category, entry_date, entry_title from jones_blog", $dbi );
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administrative Menu</span></h1>
        <h2><span>Blog manager</span></h2>
      </div>
      <div class="contentBox">
<?php
    if( sql_num_rows( $res_blog_entries ) > 0 ) {
?>
        <table>
          <caption>Existing entries</caption>
          <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Date</th>
            <th>Title</th>
            <th><em>Operation</em></th>
          </tr>
<?php
      while( list( $blog_id, $blog_category, $blog_date, $blog_title ) = sql_fetch_row( $res_blog_entries, $dbi ) ) {
        if( $blog_category != '0' ) {
          list( $category_name ) = sql_fetch_row( sql_query( "select name from jones_blog_category where id='$blog_category'", $dbi ), $dbi );
        } else {
          $category_name = "";
        }
?>
          <tr>
            <td><?php echo $blog_id; ?></td>
            <td><?php echo $category_name; ?></td>
            <td><?php echo $blog_date; ?></td>
            <td><?php echo $blog_title; ?></td>
            <td class="buttons">
              <a href="<?php echo $_SERVER['PHP_SELF']; ?>?cmd=edit&amp;id=<?php echo $blog_id; ?>"><img src="img/edit.gif" alt="Edit" title="Edit"/></a>
              <a href="<?php echo $_SERVER['PHP_SELF']; ?>?cmd=delete&amp;id=<?php echo $blog_id; ?>"><img src="img/delete.gif" alt="Delete" title="Delete" /></a>
              <a href="<?php echo $_SERVER['PHP_SELF']; ?>?cmd=upload&amp;id=<?php echo $blog_id; ?>"><img src="img/jpg.gif" alt="Add image" title="Add image" /></a>
            </td>
          </tr>
<?php
      }
?>
        </table>
<?php
    }

    if( sql_num_rows( $res_cats, $dbi ) > 0 ) {
?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <input type="hidden" name="cmd" value="addBlogEntry" />
          <fieldset>
            <table>
              <tr>
                <th>Create new blog entry in</th>
                <td>
                  <select name="blog_category">
<?php
      while( list( $category_id, $category_name ) = sql_fetch_row( $res_cats, $dbi ) ) {
?>
                    <option value="<?php echo $category_id; ?>"><?php echo $category_name; ?></option>
<?php
      }
?>
                  </select>
                </td>
                <td><input type="submit" value="Go"/></td>
              </tr>
            </table>
          </fieldset>
        </form>
<?php
    }
?>
      </div>
      
      <div class="contentBox">
<?php
    if( sql_num_rows( $res_cats_full, $dbi ) > 0 ) {
?>
        <table>
          <caption>Existing categories</caption>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Parent</th>
            <th>Image</th>
            <th><i>Operation</i></th>
          </tr>
<?php
      while( list( $category_id, $category_name, $category_parent, $category_image ) = sql_fetch_row( $res_cats_full, $dbi ) ) {
        list( $category_parent_name ) = sql_fetch_row( sql_query( "select name from jones_blog_category where id='$category_parent'", $dbi ), $dbi );
?>
          <tr>
            <td><?php echo $category_id; ?></td>
            <td><?php echo $category_name; ?></td>
            <td><?php echo $category_parent_name; ?></td>
            <td><?php echo $category_image; ?></td>
            <td class="buttons">
               <a href="<?php echo $_SERVER['PHP_SELF']; ?>?cmd=editCategory&amp;id=<?php echo $category_id; ?>"><img src="img/edit.gif" alt="Edit" /></a>
               <a href="<?php echo $_SERVER['PHP_SELF']; ?>?cmd=deleteCategory&amp;id=<?php echo $category_id; ?>"><img src="img/delete.gif" alt="Delete" /></a>
            </td>
          </tr>
<?php
      }
?>
        </table>
<?php
    }
?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
          <fieldset>
            <input type="hidden" name="cmd" value="addCategory" />
            <table>
              <tr>
                <th>Create a new category</th>
                <td><input type="submit" value="Go" /></td>
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

  function addCategory( ) {
    global $dbi;
    include 'header.php';
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administrative Menu</span></h1>
        <h2><span>Add Blog category</h2>
      </div>
      <div class="contentBox">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <fieldset>
            <input type="hidden" name="cmd" value="finishCategory" />
            <table>
              <tr>
                <th>Name</th>
                <th>Parent</th>
                <th>Image URL</th>
              </tr>
              <tr>
                <td><input type="text" name="name" maxlength="40" /></td>
                <td>
                  <select name="parent">
                    <option value="0">-- None --</option>
<?php
    $categories_res = sql_query( "select id, name from jones_blog_category", $dbi );
    while( list( $parent_id, $parent_name ) = sql_fetch_row( $categories_res, $dbi ) ) {
?>
                    <option value="<?php echo $parent_id; ?>"><?php echo $parent_name; ?></option>
<?php
    }
?>
                  </select>
                </td>
                <td>
                  <select name="image">
                    <option value="#">-- None --</option>
<?php
    $list = getBlogImages( );
    for( $i = 0; $i < sizeof( $list ); $i++ ) {
      if( $list[ $i ] != "" ) {
?>
                    <option value="<?php echo $list[ $i ];?>"><?php echo $list[ $i ];?></option>
<?php
      }
    }
?>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="buttons" colspan="3">
                  <input type="submit" value="Add" />
                </td>
              </tr>
            </table>
          </fieldset>
        </form>
      </div>
      <div class="backLink">
        <a href="index.php">Back to Main Menu</a>
      </div>

<?php
    include 'footer.php';
  }

  function finishCategory( $name, $parent, $image ) {
    global $dbi;
    sql_query( "insert into jones_blog_category values( NULL, '$name', '$parent', '$image' )", $dbi );
    Header( "Location: ".$_SERVER['PHP_SELF'] );
  }

  function addBlogEntry( $blog_category ) {
    include 'header.php';
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administrative Menu"</span></h1>
        <h2><span>Add Blog entry</span></h2>
      </div>
      <div class="contentBox">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="addBlogEntry">
          <fieldset>
            <input type="hidden" name="cmd" value="finishBlogEntry" />
            <input type="hidden" name="entry_category" value="<?php echo $blog_category; ?>" />
            <table class="adminForm">
              <caption>New Blog Entry</caption>
              <tr>
                <th>Title</th>
                <td><input type="text" name="entry_title" maxlength="60" size="59" /></td>
              </tr>
              <tr>
                <th>Text</th>
                <td>
	                <table cellpadding="0" cellspacing=0">
	                	<tr>
	                		<td>
     		                	<a href="javascript:insertMarkup( document.addBlogEntry.entry_text, '[i][/i]' )"><img src="img/italic-button.gif" alt="I" title="Italic" /></a>
        	                	<a href="javascript:insertMarkup( document.addBlogEntry.entry_text, '[b][/b]' )"><img src="img/bold-button.gif" alt="B" title="Bold"/></a>
     		                	<a href="javascript:insertMarkup( document.addBlogEntry.entry_text, '[u][/u]' )"><img src="img/underline-button.gif" alt="U" title="Underline" /></a>
     		                	<a href="javascript:insertMarkup( document.addBlogEntry.entry_text, '[l][/l]' )"><img src="img/left-button.gif" alt="Left" title="Align left" /></a>
        	                	<a href="javascript:insertMarkup( document.addBlogEntry.entry_text, '[j][/j]' )"><img src="img/justified-button.gif" alt="Justified" title="Justify" /></a>
     		                	<a href="javascript:insertMarkup( document.addBlogEntry.entry_text, '[r][/r]' )"><img src="img/right-button.gif" alt="Right" title="Align right" /></a>
        	               	<a href="javascript:insertMarkup( document.addBlogEntry.entry_text, '[c][/c]' )"><img src="img/center-button.gif" alt="Center" title="Center" /></a>
	                		</td>
	                	</tr>
	                	<tr>
	                		<td colspan="8">
		                		<textarea name="entry_text" rows="10" cols="50"></textarea>
	                		</td>
	                	</tr>
	                </table>
                </td>
              </tr>
              <tr>
                <th>Extended text</th>
                <td>
                	 <table cellpadding="0" cellspacing=0">
	                	<tr>
	                		<td>
     		                	<a href="javascript:insertMarkup( document.addBlogEntry.entry_ext_text, '[i][/i]' )"><img src="img/italic-button.gif" alt="I" title="Italic" /></a>
        	                	<a href="javascript:insertMarkup( document.addBlogEntry.entry_ext_text, '[b][/b]' )"><img src="img/bold-button.gif" alt="B" title="Bold"/></a>
     		                	<a href="javascript:insertMarkup( document.addBlogEntry.entry_ext_text, '[u][/u]' )"><img src="img/underline-button.gif" alt="U" title="Underline" /></a>
     		                	<a href="javascript:insertMarkup( document.addBlogEntry.entry_ext_text, '[l][/l]' )"><img src="img/left-button.gif" alt="Left" title="Align left" /></a>
        	                	<a href="javascript:insertMarkup( document.addBlogEntry.entry_ext_text, '[j][/j]' )"><img src="img/justified-button.gif" alt="Justified" title="Justify" /></a>
     		                	<a href="javascript:insertMarkup( document.addBlogEntry.entry_ext_text, '[r][/r]' )"><img src="img/right-button.gif" alt="Right" title="Align right" /></a>
        	               	<a href="javascript:insertMarkup( document.addBlogEntry.entry_ext_text, '[c][/c]' )"><img src="img/center-button.gif" alt="Center" title="Center" /></a>
	                		</td>
	                	</tr>
	                	<tr>
	                		<td colspan="8">
     		                	<textarea name="entry_ext_text" rows="30" cols="50"></textarea>
	                		</td>
	                	</tr>
	                </table>
                </td>
              </tr>
              <tr>
              	 <td>&nbsp;</td>
                <td class="buttons"><input type="submit" value="Submit" /></td>
              </tr>
            </table>
          </fieldset>
        </form>
      </div>
      <div class="backLink">
        <a href="index.php">Back to Main Menu</a></div>
      </div>
    </div>

<?php
    include 'footer.php';
  }

  function finishBlogEntry( $entry_category, $entry_title, $entry_text, $entry_ext_text ) {
    global $dbi;
    $now = date( "M, d Y @ h:i a" );
    sql_query( "insert into jones_blog values( NULL, '$entry_category', '$now', '$entry_title', '$entry_text', '$entry_ext_text', '0' )", $dbi );
    Header( "Location: ".$_SERVER['PHP_SELF'] );
  }

  function editCategory( $id ) {
    global $dbi;
    list( $category_name, $category_parent, $category_image) = sql_fetch_row( sql_query( "select name, parent, image from jones_blog_category where id='$id'", $dbi ), $dbi );
    include 'header.php';
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administrative Menu</span></h1>
        <h2><span>Add Blog category</h2>
      </div>
      <div class="contentBox">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <fieldset>
            <input type="hidden" name="cmd" value="finishEditCategory" />
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <table>
              <tr>
                <th>Name</th>
                <th>Parent</th>
                <th>Image URL</th>
              </tr>
              <tr>
                <td><input type="text" name="name" maxlength="40" value="<?php echo $category_name; ?>"/></td>
                <td>
                  <select name="parent">
                    <option value="0">-- None --</option>
<?php
    $categories_res = sql_query( "select id, name from jones_blog_category", $dbi );
    while( list( $parent_id, $parent_name ) = sql_fetch_row( $categories_res, $dbi ) ) {
      if( $id != $parent_id ) {
?>
                    <option value="<?php echo $parent_id; ?>" <?php if( $parent_id == $category_parent ) echo " selected"; ?>><?php echo $parent_name; ?></option>
<?php
      }
    }
?>
                  </select>
                </td>
                <td>
                  <select name="image">
                    <option value="#">-- None --</option>
<?php
    $list = getBlogImages( );
    for( $i = 0; $i < sizeof( $list ); $i++ ) {
      if( $list[ $i ] != "" ) {
?>
                    <option value="<?php echo $list[ $i ];?>" <?php if( $list[ $i ] == $category_image ) echo " selected"; ?>><?php echo $list[ $i ];?></option>
<?php
      }
    }
?>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="buttons" colspan="3">
                  <input type="submit" value="Save" />
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

  function finishEditCategory( $id, $name, $parent, $image ) {
    global $dbi;
    sql_query( "update jones_blog_category set name='$name', parent='$parent', image='$image' where id='$id'", $dbi );
    Header( "Location: ".$_SERVER['PHP_SELF'] );
  }

  function deleteCategory( $id ) {
    global $dbi;
    sql_query( "delete from jones_blog_category where id='$id'", $dbi );
    sql_query( "update jones_blog_category set parent='0' where parent='$id'", $dbi );
    sql_query( "update jones_blog set category='0' where category='$id'", $dbi );
    Header( "Location: ".$_SERVER['PHP_SELF'] );
  }

  function editBlogEntry( $id ) {
    global $dbi;
    list( $entry_category, $entry_title, $entry_text, $entry_ext_text ) = sql_fetch_row( sql_query( "select category, entry_title, entry_text, entry_ext_text from jones_blog where id='$id'", $dbi ), $dbi );
    $categories_res = sql_query( "select id, name from jones_blog_category", $dbi );
    include 'header.php';
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administrative Menu</span></h1>
        <h2><span>Edit Blog Entry</span></h2>
      </div>
      <div class="contentBox">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="editBlogEntry">
          <fieldset>
            <input type="hidden" name="cmd" value="finishEdit" />
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <table class="adminForm">
              <caption>Edit Blog Entry</caption>
              <tr>
                <th>Category</th>
                <td>
                  <select name="editBlogCategory">
                    <option value="0">-- None --</option>
<?php
    while( list( $cat_id, $cat_name ) = sql_fetch_row( $categories_res, $dbi ) ) {
?>
                    <option value="<?php echo $cat_id; ?>" <?php if( $cat_id == $entry_category ) echo " selected"; ?>><?php echo $cat_name; ?></option>
<?php
    }
?>
                  </select>
                </td>
              </tr>
              <tr>
                <th>Title</th>
                <td><input type="text" name="editBlogTitle" value="<?php echo $entry_title; ?>" maxlength="60" size="59" /></td>
              </tr>
              <tr>
                <th>Text</th>
                <td>
	                <table cellpadding="0" cellspacing="0">
	                	<tr>
	                		<td>
     		                	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogText, '[i][/i]' )"><img src="img/italic-button.gif" alt="I" title="Italic" /></a>
        	                	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogText, '[b][/b]' )"><img src="img/bold-button.gif" alt="B" title="Bold"/></a>
     		                	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogText, '[u][/u]' )"><img src="img/underline-button.gif" alt="U" title="Underline" /></a>
     		                	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogText, '[l][/l]' )"><img src="img/left-button.gif" alt="Left" title="Align left" /></a>
        	                	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogText, '[j][/j]' )"><img src="img/justified-button.gif" alt="Justified" title="Justify" /></a>
     		                	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogText, '[r][/r]' )"><img src="img/right-button.gif" alt="Right" title="Align right" /></a>
        	               	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogText, '[c][/c]' )"><img src="img/center-button.gif" alt="Center" title="Center" /></a>
	                		</td>
	                	</tr>
	                	<tr>
	                		<td colspan="8">
		                		<textarea name="editBlogText" rows="10" cols="50"><?php echo $entry_text; ?></textarea>
	                		</td>
	                	</tr>
	                </table>
                </td>
              </tr>
              <tr>
                <th>Extended Text</th>
                  <td>
 	                <table cellpadding="0" cellspacing="0">
	                	<tr>
	                		<td>
     		                	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogExtText, '[i][/i]' )"><img src="img/italic-button.gif" alt="I" title="Italic" /></a>
        	                	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogExtText, '[b][/b]' )"><img src="img/bold-button.gif" alt="B" title="Bold"/></a>
     		                	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogExtText, '[u][/u]' )"><img src="img/underline-button.gif" alt="U" title="Underline" /></a>
     		                	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogExtText, '[l][/l]' )"><img src="img/left-button.gif" alt="Left" title="Align left" /></a>
        	                	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogExtText, '[j][/j]' )"><img src="img/justified-button.gif" alt="Justified" title="Justify" /></a>
     		                	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogExtText, '[r][/r]' )"><img src="img/right-button.gif" alt="Right" title="Align right" /></a>
        	               	<a href="javascript:insertMarkup( document.editBlogEntry.editBlogExtText, '[c][/c]' )"><img src="img/center-button.gif" alt="Center" title="Center" /></a>
	                		</td>
	                	</tr>
	                	<tr>
	                		<td colspan="8">
									<textarea name="editBlogExtText" rows="30" cols="50"><?php echo $entry_ext_text; ?></textarea>
	                		</td>
	                	</tr>
	                </table>
                </td>
              </tr>
              <tr>
                <td class="buttons" colspan="2">
                  <input type="submit" value="Save" />
                </td>
              </tr>
            </table>
          </fieldset>
        </table>
      </div>
      <div class="backLink">
        <a href="index.php">Back to Main Menu</a>
      </div>
    </div>
<?php
    include 'footer.php';
  }

  function finishEditBlogEntry( $id, $editBlogCategory, $editBlogTitle, $editBlogText, $editBlogExtText ) {
    global $dbi;
    sql_query( "update jones_blog set category='$editBlogCategory', entry_title='$editBlogTitle', entry_text='$editBlogText', entry_ext_text='$editBlogExtText' where id='$id'", $dbi );
    Header( "Location: ".$SERVER['PHP_SELF'] );
  }
  
  function deleteBlogEntry( $id ) {
    global $dbi;
    sql_query( "delete from jones_blog where id='$id'", $dbi );
    Header( "Location: ".$_SERVER['PHP_SELF'] );
  }
  
  function upload( $id ) {
  	global $dbi;
  	$res = sql_query( "SELECT id, filename, description, fullimage, thumbnail FROM jones_binarydata WHERE blogentry_id=$id", $dbi );
    include 'header.php';
?>
    <div id="main">
      <div id="pageHeader">
        <h1 id="admin_menu"><span>Administrative Menu</span></h1>
        <h2 id="add_image_to_blog_entry"><span>Add image to Blog Entry</span></h2>
      </div>
      <div class="contentBox">
<?php
		if( sql_num_rows( $res ) > 0 ) {
?>
				<table class="resume">
					<caption>Existing images</caption>
					<tr>
						<th>&nbsp;ID&nbsp;</th>
						<th>Thumbnail</th>
						<th>Description</th>
						<th><em>Operation</em></th>
					</tr>
<?php
			while( list( $img_id, $filename, $description, $fullimage, $thumbnail ) = sql_fetch_row( $res, $dbi ) ) {
				$src_img = imagecreatefromstring( $fullimage ); // create a image resource with your original image in it
				$width = imagesx( $src_img ); //get original image width
				$height = imagesy( $src_img ); //get original image height
?>
					<tr>
						<td><?php echo $img_id; ?></td>
						<td><img src="../thumbnail.php?id=<?php echo $img_id; ?>" alt="<?php echo $filename; ?>" /></td>
						<td><?php echo $description; ?></td>
						<td>
							<a href="#" onClick="javascript: openCenteredWindow('../popImage.php?id=<?php echo $img_id; ?>', '<?php echo $height + 10; ?>', '<?php echo $width + 10; ?>', '<?php echo $filename; ?>')"><img src="img/view.gif" alt="View" title="View" /></a>
							<a href="<?php echo $_SERVER['PHP_SELF']; ?>?cmd=editImage&id=<?php echo $img_id; ?>"><img src="img/edit.gif" alt="Edit" title="Edit image description" /></a>
							<a href="<?php echo $_SERVER['PHP_SELF']; ?>?cmd=deleteImage&id=<?php echo $img_id; ?>&blog_id=<?php echo $id;?>"><img src="img/delete.gif" alt="Delete" title="Delete" /></a>
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
						<input type="hidden" name="blog_id" value="<?php echo $id; ?>" />
						<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
						<table class="adminForm">
							<caption>Insert a new image</caption>
							<tr>
								<th>Description</th>
								<td><textarea name="description"></textarea></td>
							</tr>
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

	function finishUpload( $blog_id, $description, $image_file, $type, $size, $name ) {
	  global $dbi;
	  $filesize = filesize( $image_file );
    if (!($fp = @fopen( $image_file, 'rb'))) {
	    trigger_error("Can't open file $image_file", E_USER_WARNING);
	    die( );
	  }

	  $data = fread( $fp, $filesize );
		
	  // generate thumbnail
	  $src_img = ImageCreateFromString( $data ); // create a image resource with your original image in it
	  if( ! $src_img ) {
      echo "Error in imagecreatedatafromstring";
    }
	  $width = imagesx( $src_img ); //get original image width
	  $height = imagesy( $src_img ); //get original image height
	  //scale the image uses variable $p as the proportion of the original size the thumbnail wants to be
	  $dest_width = 50;
	  $dest_height = 50;
	  $dest_img = ImageCreate( $dest_width, $dest_height );
	  if( !$dest_img ) {
	    echo "Error in imagecreate";
    }
		
		
	  $result = ImageCopyResized( $dest_img, $src_img, 0 ,0 ,0 ,0 , $dest_width, $dest_height, $width, $height );
	  if( ! $result ) {
	  	echo "Error in imagecopyresampled";
	  }

    ob_start( );
    ImageJPEG( $dest_img );
    $thumbnail = ob_get_contents( );
    ob_end_clean( );
  
		// populate db
    $now = date( "Y/m/d" );
    $data = addslashes( $data );
    $thumbnail = addslashes( $thumbnail );
    $res = sql_query( "INSERT INTO jones_binarydata VALUES( NULL, '$name', '$size', '$type', '$now', '$description', '$data', '$thumbnail', '$blog_id' )", $dbi );
    if( $res ) {
    	echo "OK";
			//Header( "Location: ".$_SERVER['PHP_SELF']."?cmd=upload&id=$blog_id" );
    } else {
    	ImageDestroy( $dest_img );
    }

	}
	
	function editImage( $id ) {
		global $dbi;
		$res = sql_query( "SELECT filename, description, thumbnail, blogentry_id FROM jones_binarydata WHERE id='$id'", $dbi );
		list( $filename, $description, $thumbnail, $blog_id ) = sql_fetch_row( $res, $dbi );
		include 'header.php';
?>
    <div id="main">
      <div id="pageHeader">
        <h1 id="admin_menu"><span>Administrative Menu</span></h1>
        <h2 id="Edit image description"><span>Edit image description</span></h2>
      </div>
      <div class="contentBox">
      	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      		<input type="hidden" name="cmd" value="finishEditImage" />
      		<input type="hidden" name="id" value="<?php echo $id; ?>" />
      		<input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>" />
      		<fieldset>
		      	<table class="adminForm">
		      		<tr>
		      			<th>Image</th>
		      			<td><img src="../thumbnail.php?id=<?php echo $id; ?>" alt="<?php echo $filename; ?>" /></td>
		      		</tr>
		      		<tr>
		      			<th>Description</th>
		      			<td>
			      			<textarea cols="4" name="description"><?php echo $description; ?></textarea>
		      			</td>
		      		</tr>
		      		<tr>
		      			<th>&nbsp;</th>
		      			<td class="buttons">
		      				<input type="submit" value="Submit" />
		      			</td>
		      		</tr>
		      	</fieldset>
	      	</form>
      	</table>
      </div>
    </div>
<?php
	}
	
	function finishEditImage( $id, $description, $blog_id ) {
		global $dbi;
		$res = sql_query( "UPDATE jones_binarydata SET description='$description' WHERE id='$id'", $dbi );
		if( ! $res )
			echo mysql_error( );
		else
			Header( "Location: ".$_SERVER['PHP_SELF']."?cmd=upload&id=$blog_id" );
	}
	
	function deleteImage( $id, $blog_id ) {
		global $dbi;
		sql_query( "DELETE FROM jones_binarydata WHERE id='$id'", $dbi );
		Header( "Location: ".$_SERVER['PHP_SELF']."?cmd=upload&id=$blog_id" );
	}
  
	set_error_handler("myErrorHandler");

  if( isSet( $_GET['cmd'] ) )
  	$cmd = $_GET['cmd'];
  else if( isSet( $_POST['cmd'] ) )
  	$cmd = $_POST['cmd'];
  else
  	$cmd = "";
  	
  switch( $cmd ) {
    case "addCategory":
      addCategory( );
      break;
    case "finishCategory":
      finishCategory( $_POST['name'], $_POST['parent'], $_POST['image'] );
      break;
    case "addBlogEntry":
      addBlogEntry( $_POST['blog_category'] );
      break;
    case "finishBlogEntry":
      finishBlogEntry( $_POST['entry_category'], $_POST['entry_title'], $_POST['entry_text'], $_POST['entry_ext_text'] );
      break;
    case "editCategory":
      editCategory( $_GET['id'] );
      break;
    case "finishEditCategory":
      finishEditCategory( $_POST['id'], $_POST['name'], $_POST['parent'], $_POST['image'] );
      break;
    case "deleteCategory":
      deleteCategory( $_GET['id'] );
      break;
    case "edit":
      editBlogEntry( $_GET['id'] );
      break;
    case "finishEdit":
      finishEditBlogEntry( $_POST['id'], $_POST['editBlogCategory'], $_POST['editBlogTitle'], $_POST['editBlogText'], $_POST['editBlogExtText'] );
      break;
    case "delete":
      deleteBlogEntry( $_GET['id'] );
      break;
    case "upload":
    	upload( $_GET['id'] );
    	break;
	  case "finishUpload":
   		finishUpload( $_POST['blog_id'], $_POST['description'], $_FILES['image_file']['tmp_name'], $_FILES['image_file']['type'], $_FILES['image_file']['size'], $_FILES['image_file']['name'] );
   		break;
   	case "editImage":
   		editImage( $_GET['id'] );
   		break;
   	case "finishEditImage":
   		finishEditImage( $_POST['id'], $_POST['description'], $_POST['blog_id'] );
   		break;
   	case "deleteImage":
   		deleteImage( $_GET['id'], $_GET['blog_id'] );
   		break;
    default:
      main( );
      break;
  }
?>
