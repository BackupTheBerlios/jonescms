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

function openCenteredWindow(url, height, width, name) {
  height = parseInt( height) + 100;
  width = parseInt( width) + 50;
  var left = Math.floor( (screen.width - width) / 2 );
  var top = Math.floor( (screen.height - height) / 2 );
  var winParms = "top=" + top + ",left=" + left + ",height=" + height + ",width=" + width;
  var win = window.open( url, "Image", winParms );
  if (navigator && parseInt(navigator.appVersion) >= 4) {
    win.focus();
  }
}

function insertMarkup( field, value ) {
  field.value += value;
}

