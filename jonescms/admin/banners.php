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
    global $dbi;
    include 'header.php';
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administration Area</span></h1>
        <h2><span>Banners manager</span></h2>
      </div>
      <div class="contentBox">
          <form method="post" action="banners.php">
            <fieldset>
              <input type="hidden" name="cmd" value="add" />
              <table>
                <caption>Add Banners' Area</caption>
                <tr>
                  <th>Title</th>
                </tr>
                <tr>
                  <td><input type="text" maxlength="20" name="title" /></td>
                </tr>
                <tr>
                  <td class="buttons">
                    <input type="submit" value="Add" />
                  </td>
                </tr>
              </table>
            </fieldset>
          </form>
<?php
  $result = sql_query( "select * from jones_banner_area order by weight asc", $dbi );
  if( sql_num_rows( $result, $dbi ) > 0 ) {
?>
          <table>
            <caption>Existing banners' areas</caption>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Operation</th>
            </tr>
<?php
      while( list( $bid, $title ) = sql_fetch_row( $result, $dbi ) ) {
?>
          <tr>
            <td><?php echo $bid; ?></td>
            <td><?php echo $title; ?></td>
            <td class="buttons">
              <a href="banners.php?cmd=bannerAreaWeight&amp;op=up&amp;id=<?php echo $bid; ?>"><img src="img/up.gif" alt="Up" /></a>
              <a href="banners.php?cmd=bannerAreaWeight&amp;op=down&amp;id=<?php echo $bid; ?>"><img src="img/down.gif" alt="Down" /></a>
              <a href="banners.php?cmd=edit&amp;bid=<?php echo $bid; ?>"><img src="img/edit.gif" alt="Edit"/></a>
              <a href="banners.php?cmd=delete&amp;bid=<?php echo $bid; ?>"><img src="img/delete.gif" alt="Delete"/></a>
            </td>
          </tr>
<?php
      }
?>
        </table>
      </div>

      <div class="contentBox">
        <form action="banners.php" method="post">
          <fieldset>
            <input type="hidden" name="cmd" value="addBanner" />
            <table>
              <caption>Banners</caption>
              <tr>
                <th>Image ULR</th>
                <td>
									<input type="text" name="bannerImg" size="10" />
                </td>
								<th>Banner link</th>
								<td>
									<input type="text" name="bannerLink" size="10" />
								</td>
								<th>Banner Area</th>
								<td>
									<select name="area_id">
<?php
  $result = sql_query( "select id, title from jones_banner_area order by weight asc", $dbi );
  while( list( $area_id, $area_title ) = sql_fetch_row( $result, $dbi ) ) {
?>
										<option value="<?php echo $area_id; ?>"><?php echo $area_title; ?></option>
<?php
	}
?>
									</select>
								</td>
                <td><input type="submit" value="Go" /></td>
              </tr>
            </table>
          </fieldset>
        </form>
<?php
    }
?>
<?php
    $result = sql_query( "select id, img, area_id from jones_banner order by weight asc", $dbi );
    if( sql_num_rows( $result, $dbi ) > 0 ) {
?>
        <table>
          <caption>Existing banners</caption>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Area</th>
            <th><i>Operation</i></th>
          </tr>
<?php
      while( list( $banner_id, $banner_img, $banner_area_id ) = sql_fetch_row( $result, $dbi ) ) {
        list( $banner_area_title ) = sql_fetch_row( sql_query( "select title from jones_banner_area where id='$banner_area_id'", $dbi ), $dbi );
?>
          <tr>
            <td><?php echo $banner_id; ?></td>
            <td><img src="<?php echo $banner_img; ?>" alt="" /></td>
            <td><?php echo $banner_area_title; ?></td>
            <td class="buttons">
              <a href="banners.php?cmd=bannerWeight&amp;op=up&amp;id=<?php echo $banner_id; ?>"><img src="img/up.gif" alt="Up" /></a>
              <a href="banners.php?cmd=bannerWeight&amp;op=down&amp;id=<?php echo $banner_id; ?>"><img src="img/down.gif" alt="Down" /></a>
              <a href="banners.php?cmd=editBanner&amp;id=<?php echo $banner_id; ?>"><img src="img/edit.gif" alt="Edit" /></a>
              <a href="banners.php?cmd=deleteBanner&amp;id=<?php echo $banner_id; ?>"><img src="img/delete.gif" alt="Delete" /></a>
           </td>
          </tr>
<?php
      }
?>
        </table>
<?php
    }
?>
      </div>
      <div class="backLink">
        <a href="index.php">Back to Main Menu</a>
      </div>
    </div>
<?php
    include 'footer.php';
  }

  function add( $title ) {
    global $dbi;
    $weight = getMaxBannerAreaWeight( );
    $weight++;
    sql_query( "insert into jones_banner_area values( NULL, '$title', '$weight' )", $dbi );
    Header( "Location: banners.php" );
  }

  function edit( $bid ) {
    global $dbi;
    list( $edit_banner_area_title ) = sql_fetch_row( sql_query( "select title from jones_banner_area where id='$bid'", $dbi ), $dbi );
    include 'header.php';
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administration Area</span></h1>
        <h2><span>Banners' areas manager</span></h2>
      </div>
      <div class="contentBox">
          <form method="post" action="banners.php">
            <fieldset>
              <input type="hidden" name="cmd" value="finishEdit" />
              <input type="hidden" name="bid" value="<?php echo $bid; ?>" />
              <table>
                <tr>
                  <th>Title</th>
                </tr>
                <tr>
                  <td><input type="text" maxlength="20" name="edit_banner_area_title" value="<?php echo $edit_banner_area_title; ?>" /></td>
                </tr>
                <tr>
                  <td class="buttons" colspan="2">
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

  function finishEdit( $bid, $edit_banner_area_title ) {
    global $dbi;
    sql_query( "update jones_banner_area set title='$edit_banner_area_title' where id='$bid'", $dbi );
    Header( "Location: banners.php" );
  }

  function deleteBannerArea( $bid ) {
    global $dbi;
    sql_query( "delete from jones_banner_area where id='$bid'", $dbi );
    Header( "Location: banners.php" );
  }

  function addBanner( $bannerImg, $bannerLink, $area_id ) {
    global $dbi;
    $weight = getMaxBannerWeight( $area_id );
    $weight++;
    sql_query( "insert into jones_banner values( NULL, '$bannerImg', '$bannerLink', '$area_id', '$weight' )", $dbi );
    Header( "Location: banners.php" );
  }

  function editBanner( $id ) {
    global $dbi;
    list( $bImg, $bLink, $b_area_id ) = sql_fetch_row( sql_query( "select img, link, area_id from jones_banner where id='$id'", $dbi ), $dbi );
    include 'header.php';
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administrative Menu</span></h1>
        <h2><span>Edit banner</span></h2>
      </div>
      <div class="contentBox">
        <form action="banners.php" method="post">
          <fieldset>
            <input type="hidden" name="cmd" value="finishEditBanner" />
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <table>
              <caption>Banner</caption>
              <tr>
								<th>Image</th>
								<td><input type="text" name="bannerImg" size="10" value="<?php echo $bImg; ?>" /></td>
								<th>Link</th>
								<td><input type="text" name="bannerLink" size="10" value="<?php echo $bLink; ?>" /></td>
								<th>Area</th>
								<td>
									<select name="area_id">
<?php
  $result = sql_query( "select id, title from jones_banner_area order by weight asc", $dbi );
  while( list( $area_id, $area_title ) = sql_fetch_row( $result, $dbi ) ) {
?>
										<option value="<?php echo $area_id; ?>"<?php if( $area_id == $_area_id ) echo " selected"; ?>><?php echo $area_title; ?></option>
<?php
	}
?>
									</select>
								</td>
                <td><input type="submit" value="Save" /></td>
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

  function finishEditBanner( $id, $bannerImg, $bannerLink, $area_id ) {
    global $dbi;
    sql_query( "update jones_banner set img='$bannerImg', link='$bannerLink', area_id='$area_id' where id='$id'", $dbi );
    Header( "Location: banners.php" );
  }

  function deleteBanner( $id ) {
    global $dbi;
    sql_query( "delete from jones_banner where id='$id'", $dbi );
    Header( "Location: banners.php" );
  }

  function findLighter( $area_id, $weight ) {
    global $dbi;
    list( $lighter_id ) = sql_fetch_row( sql_query( "select id from jones_banner where weight < '$weight' and area_id='$area_id' order by weight desc limit 1", $dbi ), $dbi );
    return $lighter_id;
  }
 
 function findHeavier( $area_id, $weight ) {
    global $dbi;
    list( $heavier_id ) = sql_fetch_row( sql_query( "select id from jones_banner where weight > '$weight' and area_id='$area_id' order by weight asc limit 1", $dbi ), $dbi );
    return $heavier_id;
  }
 
  function bannerWeight( $op, $id ) {
    global $dbi;
    list( $area_id, $weight ) = sql_fetch_row( sql_query( "select area_id, weight from jones_banner where id='$id'", $dbi ), $dbi );
    switch( $op ) {
      case "up":
        if( $weigth == "0" ) {
          Header( "Location: banners.php" );
          die( );
        }
        $lighter_id = findLighter( $area_id, $weight );
        list( $lighter_weight ) = sql_fetch_row( sql_query( "select weight from jones_banner where id='$lighter_id'", $dbi ), $dbi );
        sql_query( "update jones_banner set weight='$weight' where id='$lighter_id'", $dbi );
        sql_query( "update jones_banner set weight='$lighter_weight' where id='$id'", $dbi );
        break;
      case "down":
        if( $weight == getMaxBannerWeight( $area_id ) ) {
          Header( "Location: banners.php" );
          die( );
        }
        $heavier_id = findHeavier( $area_id, $weight );
        list( $heavier_weight ) = sql_fetch_row( sql_query( "select weight from jones_banner where id='$heavier_id'", $dbi ), $dbi );
        sql_query( "update jones_banner set weight='$weight' where id='$heavier_id'", $dbi );
        sql_query( "update jones_banner set weight='$heavier_weight' where id='$id'", $dbi );
        break;
    }
    Header( "Location: banners.php" );
  }

  function findAreaLighter( $weight ) {
    global $dbi;
    list( $lighter_id ) = sql_fetch_row( sql_query( "select id from jones_banner_area where weight < '$weight' order by weight desc limit 1", $dbi ), $dbi );
    return $lighter_id;
  }
 
 function findAreaHeavier( $weight ) {
    global $dbi;
    list( $heavier_id ) = sql_fetch_row( sql_query( "select id from jones_banner_area where weight > '$weight' order by weight asc limit 1", $dbi ), $dbi );
    return $heavier_id;
  }
 
  function bannerAreaWeight( $op, $id ) {
    global $dbi;
    list( $weight ) = sql_fetch_row( sql_query( "select weight from jones_banner_area where id='$id'", $dbi ), $dbi );
    switch( $op ) {
      case "up":
        if( $weigth == "0" ) {
          Header( "Location: banners.php" );
          die( );
        }
        $lighter_id = findAreaLighter( $weight );
        list( $lighter_weight ) = sql_fetch_row( sql_query( "select weight from jones_banner_area where id='$lighter_id'", $dbi ), $dbi );
        sql_query( "update jones_banner_area set weight='$weight' where id='$lighter_id'", $dbi );
        sql_query( "update jones_banner_area set weight='$lighter_weight' where id='$id'", $dbi );
        break;
      case "down":
        if( $weight == getMaxBannerAreaWeight( ) ) {
          Header( "Location: banners.php" );
          die( );
        }
        $heavier_id = findAreaHeavier( $weight );
        list( $heavier_weight ) = sql_fetch_row( sql_query( "select weight from jones_banner_area where id='$heavier_id'", $dbi ), $dbi );
        sql_query( "update jones_banner_area set weight='$weight' where id='$heavier_id'", $dbi );
        sql_query( "update jones_banner_area set weight='$heavier_weight' where id='$id'", $dbi );
        break;
    }
    Header( "Location: banners.php" );
  }

  if( isSet( $_GET['cmd'] ) )
  	$cmd = $_GET['cmd'];
  else if( isSet( $_POST['cmd'] ) )
  	$cmd = $_POST['cmd'];

  switch( $cmd ) {
    case "add":
      add( $_POST['title'] );
      break;
    case "edit":
      edit( $_GET['bid'] );
      break;
    case "finishEdit":
      finishEdit( $_POST['bid'], $_POST['edit_banner_area_title'] );
      break;
    case "delete":
      deleteBannerArea( $_GET['bid'] );
      break;
    case "addBanner":
      addBanner( $_POST['bannerImg'], $_POST['bannerLink'], $_POST['area_id'] );
      break;
    case "editBanner":
      editBanner( $_GET['id'] );
      break;
    case "finishEditBanner":
      finishEditBanner( $_POST['id'], $_POST['bannerImg'], $_POST['bannerLink'], $_POST['area_id'] );
      break;
    case "deleteBanner":
      deleteBanner( $_GET['id'] );
      break;
    case "bannerAreaWeight":
      bannerAreaWeight( $_GET['op'], $_GET['id'] );
      break;
    case "bannerWeight":
      bannerWeight( $_GET['op'], $_GET['id'] );
      break;
    default:
      main( );
      break;
  }
?>
