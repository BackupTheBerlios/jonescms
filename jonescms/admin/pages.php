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
    $result = sql_query( "select id, menu_id, parent, label, title from jones_page order by weight asc", $dbi );
    include 'header.php';
?>
    <div id="main">
      <div id="pageHeader">
        <h1><span>Administrative Area</span></h1>
        <h2><span>Pages manager</span></h2>
      </div>
      <div class="contentBox">
<?php
  if( sql_num_rows( $result, $dbi ) > 0 ) {
?>
        <table>
          <caption>Existing pages</caption>
          <tr>
            <th>ID</th>
            <th>Menu</th>
            <th>Parent page</th>
            <th>Label</th>
            <th>Title</th>
            <th><i>Operation</i></th>
            <th><i>Move to</i></th>
          </tr>
<?php
      while( list( $id, $menu_id, $parent, $label, $title ) = sql_fetch_row( $result, $dbi ) ) {
        list( $menu_title ) = sql_fetch_row( sql_query( "select title from jones_menu where id='$menu_id'", $dbi ), $dbi );
        list( $parent_page_label ) = sql_fetch_row( sql_query( "select label from jones_page where id='$parent'", $dbi ), $dbi );
?>
          <tr>
            <td><?php echo $id; ?></td>
            <td><?php echo $menu_title; ?></td>
            <td><?php echo $parent_page_label; ?></td>
            <td><?php echo $label; ?></td>
            <td><?php echo $title; ?></td>
            <td class="buttons">
              <a href="pages.php?cmd=weight&amp;op=up&amp;id=<?php echo $id; ?>"><img src="img/up.gif" alt="Up" /></a>
              <a href="pages.php?cmd=weight&amp;op=down&amp;id=<?php echo $id; ?>"><img src="img/down.gif" alt="Down" /></a>
              <a href="pages.php?cmd=edit&amp;pid=<?php echo $id; ?>"><img src="img/edit.gif" alt="Edit" /></a>
              <a href="pages.php?cmd=delete&amp;pid=<?php echo $id; ?>"><img src="img/delete.gif" alt="Delete" /></a>
            </td>
            <td>
              <form action="pages.php" method="post">
                <fieldset>
                  <input type="hidden" name="cmd" value="move" />
                  <input type="hidden" name="pid" value="<?php echo $id; ?>" />
                  <select name="page_menu_id">
<?php
      $result2 = sql_query( "select id, title from jones_menu", $dbi );
      while( list( $chose_menu_id, $chose_menu_title ) = sql_fetch_row( $result2, $dbi ) ) {
?>
                    <option value="<?php echo $chose_menu_id; ?>"><?php echo $chose_menu_title; ?></option>
<?php
      }
?>
                </select>
                <input type="submit" value="Go" />
              </fieldset>
            </form>
           </td>
          </tr>
<?php
      }
?>
        </table>
<?php
  } else {
?>
          <p>No pages so far.</p>
<?php
  }
  
  if( sql_num_rows( sql_query( "select id, title from jones_menu", $dbi ), $dbi ) > 0 ) {
?>
      <form action="pages.php" method="post">
        <fieldset>
          <input type="hidden" name="cmd" value="add" />
          <table>
            <tr>
              <th>Create a new page in</th>
              <td>
                <select name="page_menu_id">
<?php
      $result = sql_query( "select id, title from jones_menu", $dbi );
      while( list( $chose_menu_id, $chose_menu_title ) = sql_fetch_row( $result, $dbi ) ) {
?>
                  <option value="<?php echo $chose_menu_id; ?>"><?php echo $chose_menu_title; ?></option>
<?php
      }
?>
                </select>
              </td>
              <td><input type="submit" value="Go" /></td>
            </table>
          </fieldset>
        </form>
<?php
  } else {
?>
        <p>You have to create a menu, first.</p>
<?php
  }
?>
      </div>
      <div class="backLink">
        <a href="index.php">Back to Main Menu</a>
      </div>
<?php
    include 'footer.php';
  }

  function add( $page_menu_id ) {
    global $dbi;
    $result2 = sql_query( "select id, title from jones_page where parent='0' and menu_id='$page_menu_id'", $dbi );

    include 'header.php';
?>
      <div id="main">
        <div id="pageHeader">
          <h1><span>Administrative Area</span></h1>
          <h2><span>Pages manager</span></h2>
        </div>
        <div class="contentBox">
          <form action="pages.php" method="post">
            <fieldset>
              <input type="hidden" name="cmd" value="finishAdd" />
              <input type="hidden" name="page_menu_id" value="<?php echo $page_menu_id; ?>" />
              <table class="adminForm">
<?php
    list( $menu_type ) = sql_fetch_row( sql_query( "select menu_type from jones_menu where id='$page_menu_id'", $dbi ), $dbi );
    if( sql_num_rows( $result2, $dbi ) > 0 ) {
?>
                <tr>
                  <th>Parent page</th>
                  <td>
                    <select name="page_parent">
                      <option value="0" selected>-- None --</option>
<?php
      while( list( $page_id, $page_title ) = sql_fetch_row( $result2, $dbi ) ) {
?>
                      <option value="<?php echo $page_id; ?>"><?php echo $page_title; ?></option>
<?php
      }
?>
                    </select>
                  </td>
                </tr>
<?php
    } else {
?>
                <input type="hidden" name="page_parent" value="0" />
<?php
    }
?>
                <tr>
                  <th>Label</th>
                    <td><input type="text" name="page_label" maxlength="20" /></td>
                </tr>
                <tr>
                  <th>Title</th>
                  <td><input type="text" name="page_title" maxlength="60" /></td>
                </tr>
                <tr>
                  <th>Body</th>
                  <td><textarea name="page_body" rows="20" cols="50"></textarea></td>
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
<?php
    include 'footer.php';
  }

  function finishAdd( $page_menu_id, $page_parent, $page_label, $page_title, $page_body ) {
    global $dbi;
    $weight = getMaxPageWeight( ) + 1;
    sql_query( "insert into jones_page values( NULL, '$page_menu_id', '$weight', '$page_parent', '$page_label', '$page_title', '$page_body' )", $dbi );
    Header( "Location: pages.php" );
  }
  
  function edit( $pid ) {
    global $dbi;
    $result = sql_query( "select menu_id, parent, label, title, body from jones_page where id='$pid'", $dbi );
    list( $edit_menu_id, $edit_parent, $edit_label, $edit_title, $edit_body ) = sql_fetch_row( $result, $dbi );

    $result2 = sql_query( "select id, title from jones_page where parent='0' and menu_id='$edit_menu_id' and id!='$pid'", $dbi );

    include 'header.php';
?>
      <div id="main">
        <div id="pageHeader">
          <h1><span>Administrative Area</span></h1>
          <h2><span>Pages manager</span></h2>
        </div>
        <div class="contentBox">
          <form action="pages.php" method="post">
            <fieldset>
              <input type="hidden" name="cmd" value="finishEdit" />
              <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
              <input type="hidden" name="page_menu_id" value="<?php echo $edit_menu_id; ?>" />
              <table class="adminForm">
<?php
      list( $menu_type ) = sql_fetch_row( sql_query( "select menu_type from jones_menu where id='$edit_menu_id'", $dbi ), $dbi );
      if( sql_num_rows( $result2, $dbi ) > 1 ) {
?>
                <tr>
                  <th>Parent page</th>
                  <td>
                    <select name="page_parent">
                      <option value="0">-- None --</option>
<?php
        while( list( $page_id, $page_title ) = sql_fetch_row( $result2, $dbi ) ) {
?>
                      <option value="<?php echo $page_id; ?>"<?php if( $edit_parent == $page_id ) echo " selected"; ?>><?php echo $page_title; ?></option>
<?php
        }
?>
                    </select>
                  </td>
                </tr>
<?php
      } else {
?>
                <input type="hidden" name="page_parent" value="0" />
<?php
      }
?>
                <tr>
                  <th>Label</th>
                  <td><input type="text" name="page_label" maxlength="20" value="<?php echo $edit_label; ?>" /></td>
                </tr>
                <tr>
                  <th>Title</th>
                  <td><input type="text" name="page_title" maxlength="60" value="<?php echo $edit_title; ?>" /></td>
                </tr>
                <tr>
                  <th>Body</th>
                  <td><textarea name="page_body" rows="20" cols="50"><?php echo $edit_body; ?></textarea></td>
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

<?php
    include 'footer.php';
  }

  function finishEdit( $pid, $page_menu_id, $page_parent, $page_label, $page_title, $page_body ) {
    global $dbi;
    sql_query( "update jones_page set menu_id='$page_menu_id', parent='$page_parent', label='$page_label', title='$page_title', body='$page_body' where id='$pid'", $dbi );
    Header( "Location: pages.php" );
  }

  function deletePage( $pid ) {
    global $dbi;
    sql_query( "delete from jones_page where id='$pid'", $dbi );
    Header( "Location: pages.php" );
  }

  function move( $pid, $page_menu_id ) {
    global $dbi;
    sql_query( "update jones_page set menu_id='$page_menu_id', parent='0' where id='$pid'", $dbi );
    sql_query( "update jones_page set menu_id='$page_menu_id' where parent='$pid'", $dbi );
    Header( "Location: pages.php" );
  }

  function findLighter( $weight ) {
    global $dbi;
    list( $lighter_id ) = sql_fetch_row( sql_query( "select id from jones_page where weight < '$weight' order by weight desc limit 1", $dbi ), $dbi );
    return $lighter_id;
  }
 
 function findHeavier( $weight ) {
    global $dbi;
    list( $heavier_id ) = sql_fetch_row( sql_query( "select id from jones_page where weight > '$weight' order by weight asc limit 1", $dbi ), $dbi );
    return $heavier_id;
  }
 
  function weight( $op, $id ) {
    global $dbi;
    list( $weight ) = sql_fetch_row( sql_query( "select weight from jones_page where id='$id'", $dbi ), $dbi );
    switch( $op ) {
      case "up":
        if( $weigth == "0" ) {
          Header( "Location: pages.php" );
          die( );
        }
        $lighter_id = findLighter( $weight );
        list( $lighter_weight ) = sql_fetch_row( sql_query( "select weight from jones_page where id='$lighter_id'", $dbi ), $dbi );
        sql_query( "update jones_page set weight='$weight' where id='$lighter_id'", $dbi );
        sql_query( "update jones_page set weight='$lighter_weight' where id='$id'", $dbi );
        break;
      case "down":
        if( $weight == getMaxPageWeight( ) ) {
          Header( "Location: pages.php" );
          die( );
        }
        $heavier_id = findHeavier( $weight );
        list( $heavier_weight ) = sql_fetch_row( sql_query( "select weight from jones_page where id='$heavier_id'", $dbi ), $dbi );
        sql_query( "update jones_page set weight='$weight' where id='$heavier_id'", $dbi );
        sql_query( "update jones_page set weight='$heavier_weight' where id='$id'", $dbi );
        break;
    }
    Header( "Location: pages.php" );
  }

  if( isSet( $_GET['cmd'] ) )
  	$cmd = $_GET['cmd'];
  else if( isSet( $_POST['cmd'] ) )
  	$cmd = $_POST['cmd'];
  	
  switch( $cmd ) {
    case "add":
      add( $_POST['page_menu_id'] );
      break;
    case "finishAdd":
      finishAdd( $_POST['page_menu_id'], $_POST['page_parent'], $_POST['page_label'], $_POST['page_title'], $_POST['page_body'] );
      break;
    case "edit":
      edit( $_GET['pid'] );
      break;
    case "finishEdit":
      finishEdit( $_POST['pid'], $_POST['page_menu_id'], $_POST['page_parent'], $_POST['page_label'], $_POST['page_title'], $_POST['page_body'] );
      break;
    case "delete":
      deletePage( $_GET['pid'] );
      break;
    case "move":
      move( $_POST['pid'], $_POST['page_menu_id'] );
      break;
    case "weight":
      weight( $_GET['op'], $_GET['id'] );
      break;
    default:
      main( );
      break;
  }
?>
