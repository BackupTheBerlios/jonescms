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

create table jones_site (
  title varchar( 60 ) not null,
  sitename varchar( 60 ) not null,
  topname varchar( 60 ) not null,
  logo varchar( 60 ) not null,
  theme varchar( 20 ) not null,
  homepage varchar( 60 ) not null,
  primary key (title)
) type = myISAM;

create table jones_user (
  id int not null auto_increment primary key,
  uname varchar( 20 ) not null,
  pwd varchar( 40 ) not null,
  fname varchar( 20 ),
  lname varchar( 20 ),
  email varchar( 30 ) not null,
  privs int not null
) type = MyISAM;

create table jones_page (
  id int not null auto_increment primary key,
  menu_id int not null,
  weight int not null,
  parent int not null,
  label varchar( 20 ) not null,
  title varchar( 60 ) not null,
  body text not null
) type= MyISAM;

create table jones_menu (
  id int not null auto_increment primary key,
  title varchar( 20 ) not null,
  menu_type int not null,
  weight int not null
) type = MyISAM;

create table jones_special_menu (
  id int not null auto_increment primary key,
  label varchar( 20 ) not null,
  menu_type int not null,
  menu_id int( 11 ) not null,
  weight int not null
) type = MyISAM;

create table jones_banner_area (
	id int not null auto_increment primary key,
	title varchar( 20 ) not null,
	weight int not null
) type= MyISAM;

create table jones_banner (
  id int not null auto_increment primary key,
  img varchar( 255 ) not null,
	link varchar( 255 ) not null,
	area_id int not null,
  weight int not null
) type = MyISAM;

create table jones_blog (
  id int not null auto_increment primary key,
  category int not null,
  entry_date varchar( 23 ) not null,
  entry_title varchar( 60 ) not null,
  entry_text text not null,
  entry_ext_text text not null,
  reads int not null
) type = MyISAM;

create table jones_blog_category (
  id int not null auto_increment primary key,
  name varchar( 40 ) not null,
  parent int not null default 0,
  image varchar( 60 )
) type = MyISAM;

create table jones_blog_comment (
  id int not null auto_increment primary key,
  blog_entry_id int not null,
  poster varchar( 20 ) not null,
  email varchar( 60 ),
  subject varchar( 255 ) not null,
  body text not null,
  comment_date varchar( 23 ) not null
) type = MyISAM;

create table jones_stats (
  id int( 11 ) not null primary key,
  hits int( 10 ) not null,
  visitors int( 10 ) not null
) type = MyISAM;

create table jones_session (
  ip varchar( 15 ) not null primary key,
  time int( 10 ) not null
) type = MyISAM;

CREATE TABLE `jones_binarydata` (
  `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
  `filename` VARCHAR( 50 ) NOT NULL ,
  `filesize` VARCHAR( 50 ) NOT NULL ,
  `filetype` VARCHAR( 50 ) NOT NULL ,
  `date` VARCHAR( 10 ) NOT NULL ,
  `description` VARCHAR( 255 ) NOT NULL ,
  `fullimage` MEDIUMBLOB NOT NULL ,
  `thumbnail` MEDIUMBLOB NOT NULL ,
  `blogentry_id` INT( 11 ) DEFAULT '0' NOT NULL ,
  PRIMARY KEY ( `id` )
);

insert into jones_site values( 'Jones\' CMS', 'Jones\' CMS', 'Welcome to Jones\' CMS Site', 'jones.gif', 'jones1', 'blog.php' );
insert into jones_stats values( '0', '0', '0' );
