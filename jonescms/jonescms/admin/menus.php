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
        <h2><span>Menu manager</span></h2>
      </div>
      <div class="contentBox">
          <form method="post" action="menus.php">
            <fieldset>
              <input type="hidden" name="cmd" value="add" />
              <table>
                <caption>Add Menu</caption>
                <tr>
                  <th>Title</th>
                  <th>Type</th>
                </tr>
                <tr>
                  <td><input type="text" maxlength="20" name="title" /></td>
                  <td>
                    <select name="type">
                      <option value="0">Type 0</option>
                      <option value="1">Type 1</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td class="buttons" colspan="2">
                    <input type="submit" value="Add" />
                  </td>
                </tr>
              </table>
            </fieldset>
          </form>
<?php
  $result = sql_query( "select * from jones_menu order by menu_type asc, weight asc", $dbi );
  if( sql_num_rows( $result, $dbi ) > 0 ) {
?>
          <table>
            <caption>Existing menus</caption>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Type</th>
              <th>Operation</th>
            </tr>
<?php
      while( list( $mid, $title, $menu_type ) = sql_fetch_row( $result, $dbi ) ) {
?>
          <tr>
            <td><?php echo $mid; ?></td>
            <td><?php echo $title; ?></td>
            <td><?php if( $menu_type == '0' ) echo "Type 0"; else echo "Type 1"; ?></td>
            <td class="buttons">
              <a href="menus.php?cmd=menuWeight&amp;op=up&amp;id=<?php echo $mid; ?>"><img src="img/up.gif" alt="Up" /></a>
              <a href="menus.php?cmd=menuWeight&amp;op=down&amp;id=<?php echo $mid; ?>"><img src="img/down.gif" alt="Down" /></a>
              <a href="menus.php?cmd=edit&amp;mid=<?php echo $mid; ?>"><img src="img/edit.gif" alt="Edit"/></a>
              <a href="menus.php?cmd=delete&amp;mid=<?php echo $mid; ?>"><img src="img/delete.gif" alt="Delete"/></a>
            </td>
          </tr>
<?php
      }
?>
        </table>
      </div>

      <div class="contentBox">
        <form action="menus.php" method="post">
          <fieldset>
            <input type="hidden" name="cmd" value="addSpecialMenu" />
            <table>
              <caption>Special Menu</caption>
              <tr>
                <th>Add special menu item</th>
                <td>
                  <select name="specialMenuType">
                    <option value="0">Home</option>
                    <option value="1">Admin</option>
                    <option value="2">Statistics</option>
                    <option value="3">Blog</option>
                  </select>
                </td>
                <th>in</th>
                <td>
                  <select name="specialMenu">
<?php
      $result = sql_query( "select id, title from jones_menu", $dbi );
      while( list( $menu_id, $menu_title ) = sql_fetch_row( $result, $dbi ) ) {
?>
                    <option value="<?php echo $menu_id; ?>"><?php echo $menu_title; ?></option>
<?php
      }
?>
                  </select>
                </td>
                <th>with label</th>
                <td><input type="text" name="specialLabel" maxlength=="20" /></td>
                <td><input type="submit" value="Go" /></td>
              </tr>
            </table>
          </fieldset>
        </form>
<?php
    }
?>
<?php
    $result = sql_query( "select id, label, menu_type, menu_id from jones_special_menu order by weight asc", $dbi );
    if( sql_num_rows( $result, $dbi ) > 0 ) {
?>
        <table>
          <caption>Existing special menu entries</caption>
          <tr>
            <th>ID</th>
            <th>Label</th>
            <th>Type</th>
            <th>Menu</th>
            <th><i>Operation</i></th>
          </tr>
<?php
      while( list( $special_menu_id, $special_menu_label, $special_menu_type, $special_menu ) = sql_fetch_row( $result, $dbi ) ) {
        list( $menu_title ) = sql_fetch_row( sql_query( "select title from jones_menu where id='$special_menu'", $dbi ), $dbi );
?>
          <tr>
            <td><?php echo $special_menu_id; ?></td>
            <td><?php echo $special_menu_label; ?></td>
            <td>
<?php
        switch( $special_menu_type ) {
          case "0": echo "Home"; break;
          case "1": echo "Admin"; break;
          case "2": echo "Statistics"; break;
          case "3": echo "Blog"; break;
        }
?>
            </td>
            <td><?php echo $menu_title; ?></td>
            <td class="buttons">
              <a href="menus.php?cmd=specialMenuWeight&amp;op=up&amp;id=<?php echo $special_menu_id; ?>"><img src="img/up.gif" alt="Up" /></a>
              <a href="menus.php?cmd=specialMenuWeight&amp;op=down&amp;id=<?php echo $special_menu_id; ?>"><img src="img/down.gif" alt="Down" /></a>
              <a href="menus.php?cmd=editSpecialMenu&amp;id=<?php echo $special_menu_id; ?>"><img src="img/edit.gif" alt="Edit" /></a>
              <a href="menus.php?cmd=deleteSpecialMenu&amp;id=<?php echo $special_menu_id; ?>"><img src="img/delete.gif" alt="Delete" /></a>
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

  function add( $title, $type ) {
    global $dbi;
    $weight = getMaxMenuWeight( $type );
    $weight++;
    sql_query( "insert into jones_menu values( NULL, '$title', '$type', '$weight' )", $dbi );
    Header( "Location: menus.php" );
  }

  function edit( $mid ) {
    global $dbi;
    list( $edit_menu_title, $menu_type ) = sql_fetch_row( sql_query( "select title, menu_type from jones_menu where id='$mid'", $dbi ), $dbi );
    include 'header.php';
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administration Area</span></h1>
        <h2><span>Menu manager</span></h2>
      </div>
      <div class="contentBox">
          <form method="post" action="menus.php">
            <fieldset>
              <input type="hidden" name="cmd" value="finishEdit" />
              <input type="hidden" name="mid" value="<?php echo $mid; ?>" />
              <table>
                <tr>
                  <th>Title</th>
                  <th>Type</th>
                </tr>
                <tr>
                  <td><input type="text" maxlength="20" name="edit_menu_title" value="<?php echo $edit_menu_title; ?>" /></td>
                  <td>
                    <select name="menu_type">
                      <option value="0"<?php if( $menu_type=="0" ) echo " selected"; ?>>Type 0</option>
                      <option value="1"<?php if( $menu_type=="1" ) echo " selected"; ?>>Type 1</option>
                    </select>
                  </td>
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

  function finishEdit( $mid, $edit_menu_title, $menu_type ) {
    global $dbi;
    if( $menu_type == '0' ) {
      if( sql_num_rows( sql_query( "select * from jones_page where menu_id='$mid' and parent!='0'", $dbi ), $dbi ) > 0 ) {
        include 'header.php';
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Menu manager</span></h1>
        <h2><span>Edit menu</span></h2>
      </div>
      <div class="contentBox">
        <p class="error">Selected menu contains nested pages. You cannot move it on the top.</p>
      </div>
      <div class="backLink">
        <a href="index.php">Back to Main Menu</a>
      </div>
    </div>
<?php
        include 'footer.php';
        die( );
      }
    }
    sql_query( "update jones_menu set title='$edit_menu_title', menu_type='$menu_type' where id='$mid'", $dbi );
    Header( "Location: menus.php" );
  }

  function deleteMenu( $mid ) {
    global $dbi;
    sql_query( "delete from jones_menu where id='$mid'", $dbi );
    Header( "Location: menus.php" );
  }

  function addSpecialMenu( $specialMenuType, $specialMenu, $specialLabel ) {
    global $dbi;
    $weight = getMaxSpecialMenuWeight( );
    $weight++;
    sql_query( "insert into jones_special_menu values( NULL, '$specialLabel', '$specialMenuType', '$specialMenu', '$weight' )", $dbi );
    Header( "Location: menus.php" );
  }

  function editSpecialMenu( $id ) {
    global $dbi;
    list( $edit_label, $edit_type, $edit_menu_id ) = sql_fetch_row( sql_query( "select label, menu_type, menu_id from jones_special_menu where id='$id'", $dbi ), $dbi );
    include 'header.php';
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administrative Menu</span></h1>
        <h2><span>Edit special menu</span></h2>
      </div>
      <div class="contentBox">
        <form action="menus.php" method="post">
          <fieldset>
            <input type="hidden" name="cmd" value="finishEditSpecialMenu" />
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <table>
              <caption>Special Menu</caption>
              <tr>
                <th>Set type</th>
                <td>
                  <select name="specialMenuType">
                    <option value="0"<?php if( $edit_type=='0' ) echo " selected";?>>Home</option>
                    <option value="1"<?php if( $edit_type=='1' ) echo " selected";?>>Admin</option>
                    <option value="2"<?php if( $edit_type=='2' ) echo " selected";?>>Statistics</option>
                  </select>
                </td>
                <th>in</th>
                <td>
                  <select name="specialMenu">
<?php
      $result = sql_query( "select id, title from jones_menu", $dbi );
      while( list( $menu_id, $menu_title ) = sql_fetch_row( $result, $dbi ) ) {
?>
                    <option value="<?php echo $menu_id; ?>"<?php if( $menu_id == $edit_menu_id ) echo " selected"; ?>><?php echo $menu_title; ?></option>
<?php
      }
?>
                  </select>
                </td>
                <th>with label</th>
                <td><input type="text" name="specialLabel" maxlength=="20" value="<?php echo $edit_label; ?>"/></td>
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

  function finishEditSpecialMenu( $id, $specialMenuType, $specialMenu, $specialLabel ) {
    global $dbi;
    sql_query( "update jones_special_menu set label='$specialLabel', menu_type='$specialMenuType', menu_id='$specialMenu' where id='$id'", $dbi );
    Header( "Location: menus.php" );
  }

  function deleteSpecialMenu( $id ) {
    global $dbi;
    sql_query( "delete from jones_special_menu where id='$id'", $dbi );
    Header( "Location: menus.php" );
  }

  function findLighter( $type, $weight ) {
    global $dbi;
    list( $lighter_id ) = sql_fetch_row( sql_query( "select id from jones_menu where weight < '$weight' and menu_type='$type' order by weight desc limit 1", $dbi ), $dbi );
    return $lighter_id;
  }
 
 function findHeavier( $type, $weight ) {
    global $dbi;
    list( $heavier_id ) = sql_fetch_row( sql_query( "select id from jones_menu where weight > '$weight' and menu_type='$type' order by weight asc limit 1", $dbi ), $dbi );
    return $heavier_id;
  }
 
  function menuWeight( $op, $id ) {
    global $dbi;
    list( $type, $weight ) = sql_fetch_row( sql_query( "select menu_type, weight from jones_menu where id='$id'", $dbi ), $dbi );
    switch( $op ) {
      case "up":
        if( $weigth == "0" ) {
          Header( "Location: menus.php" );
          die( );
        }
        $lighter_id = findLighter( $type, $weight );
        list( $lighter_weight ) = sql_fetch_row( sql_query( "select weight from jones_menu where id='$lighter_id'", $dbi ), $dbi );
        sql_query( "update jones_menu set weight='$weight' where id='$lighter_id'", $dbi );
        sql_query( "update jones_menu set weight='$lighter_weight' where id='$id'", $dbi );
        break;
      case "down":
        if( $weight == getMaxMenuWeight( $type ) ) {
          Header( "Location: menus.php" );
          die( );
        }
        $heavier_id = findHeavier( $type, $weight );
        list( $heavier_weight ) = sql_fetch_row( sql_query( "select weight from jones_menu where id='$heavier_id'", $dbi ), $dbi );
        sql_query( "update jones_menu set weight='$weight' where id='$heavier_id'", $dbi );
        sql_query( "update jones_menu set weight='$heavier_weight' where id='$id'", $dbi );
        break;
    }
    Header( "Location: menus.php" );
  }

  function findSpecialLighter( $weight ) {
    global $dbi;
    list( $lighter_id ) = sql_fetch_row( sql_query( "select id from jones_special_menu where weight < '$weight' order by weight desc limit 1", $dbi ), $dbi );
    return $lighter_id;
  }
 
 function findSpecialHeavier( $weight ) {
    global $dbi;
    list( $heavier_id ) = sql_fetch_row( sql_query( "select id from jones_special_menu where weight > '$weight' order by weight asc limit 1", $dbi ), $dbi );
    return $heavier_id;
  }
 
  function specialMenuWeight( $op, $id ) {
    global $dbi;
    list( $weight ) = sql_fetch_row( sql_query( "select weight from jones_special_menu where id='$id'", $dbi ), $dbi );
    switch( $op ) {
      case "up":
        if( $weigth == "0" ) {
          Header( "Location: menus.php" );
          die( );
        }
        $lighter_id = findSpecialLighter( $weight );
        list( $lighter_weight ) = sql_fetch_row( sql_query( "select weight from jones_special_menu where id='$lighter_id'", $dbi ), $dbi );
        sql_query( "update jones_special_menu set weight='$weight' where id='$lighter_id'", $dbi );
        sql_query( "update jones_special_menu set weight='$lighter_weight' where id='$id'", $dbi );
        break;
      case "down":
        if( $weight == getMaxSpecialMenuWeight( ) ) {
          Header( "Location: menus.php" );
          die( );
        }
        $heavier_id = findSpecialHeavier( $weight );
        list( $heavier_weight ) = sql_fetch_row( sql_query( "select weight from jones_special_menu where id='$heavier_id'", $dbi ), $dbi );
        sql_query( "update jones_special_menu set weight='$weight' where id='$heavier_id'", $dbi );
        sql_query( "update jones_special_menu set weight='$heavier_weight' where id='$id'", $dbi );
        break;
    }
    Header( "Location: menus.php" );
  }
  
  if( isSet( $_GET['cmd'] ) )
  	$cmd = $_GET['cmd'];
  else if( isSet( $_POST['cmd'] ) )
  	$cmd = $_POST['cmd'];

  switch( $cmd ) {
    case "add":
      add( $_POST['title'], $_POST['type'] );
      break;
    case "edit":
      edit( $_GET['mid'] );
      break;
    case "finishEdit":
      finishEdit( $_POST['mid'], $_POST['edit_menu_title'], $_POST['menu_type'] );
      break;
    case "delete":
      deleteMenu( $_GET['mid'] );
      break;
    case "addSpecialMenu":
      addSpecialMenu( $_POST['specialMenuType'], $_POST['specialMenu'], $_POST['specialLabel'] );
      break;
    case "editSpecialMenu":
      editSpecialMenu( $_GET['id'] );
      break;
    case "finishEditSpecialMenu":
      finishEditSpecialMenu( $_POST['id'], $_POST['specialMenuType'], $_POST['specialMenu'], $_POST['specialLabel'] );
      break;
    case "deleteSpecialMenu":
      deleteSpecialMenu( $_GET['id'] );
      break;
    case "menuWeight":
      menuWeight( $_GET['op'], $_GET['id'] );
      break;
    case "specialMenuWeight":
      specialMenuWeight( $_GET['op'], $_GET['id'] );
      break;
    default:
      main( );
      break;
  }
?>
