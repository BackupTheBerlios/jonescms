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
 $id = $_GET['id'];
 $res = sql_query( "SELECT filetype, fullimage FROM jones_binarydata WHERE id='$id'", $dbi );
 list( $filetype, $fullimage ) = sql_fetch_row( $res, $dbi );
 
 Header( "Content-type: $filetype" );
 echo $fullimage;
?> 
 
 