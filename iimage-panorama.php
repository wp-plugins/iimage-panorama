<?php
/*
Plugin Name: IImage Panorama
Version: 1.0
Plugin URI: http://fredfred.net/skriker/
Description: Add 360° panoramas to your posts!
Author: Martin Chlupac
Author URI: http://fredfred.net/skriker/
Update: http://fredfred.net/skriker/plugin-update.php?p=142
*/


/*
IImage Panorama
Copyright (C) 2005 Martin Chlupac

IImage Panorama uses PTViewer3.1.2 (C) Copyright 2003,2004  Helmut Dersch (der@fh-furtwangen.de), http://webuser.fh-furtwangen.de/~dersch/


This program is free software; you can redistribute it and/or 
modify it under the terms of the GNU General Public License as 
published by the Free Software Foundation; either version 2 of the 
License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but 
WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU 
General Public License for more details.

You should have received a copy of the GNU General Public License 
along with this program; if not, write to the Free Software 
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 
USA
*/


//====================EDIT HERE=================
$ip_blog_url = get_settings('siteurl');

$ip_path_to_viewer = '/iimage_panorama/';	//relative path from $ip_blog_url to iimage_panorama_ptviewer.jar

$ip_default_width = 400;	//default width of panorama

$ip_default_height = 200;	//default height of panorama

$ip_default_speed = 0;	//rotation speed -360...360 allowed

$ip_default_inside_menu = true;	//if small menu in picture should be shown

$ip_default_outside_menu = false;	//if big menu out of image should be shown;

$ip_default_outside_menu_use_images = true;	//if outside menu is composed of images or just buttons

$ip_default_start = 0; //starting angle -180..180

$ip_default_title = "Click and move the mouse here!";	//default title of every panorama

//====================STOP EDITING==============
$ip_inside_menu_height = 15;

$ip_viewer = $ip_blog_url.$ip_path_to_viewer;


//------------------------------------------------------------------------------

function iimage_panorama_create($text){
global $ip_viewer, $ip_default_width, $ip_default_height, $ip_inside_menu_height, $id,$ip_default_speed, $ip_default_inside_menu, $ip_default_outside_menu, $ip_default_outside_menu_use_images,$ip_default_start,$ip_default_title;


$panorama = false;
$ip_width = $ip_default_width;
$ip_height = $ip_default_height;
$ip_speed = $ip_default_speed;

$ip_inside_menu = $ip_default_inside_menu;
$ip_outside_menu = $ip_default_outside_menu;
$ip_outside_menu_use_images = $ip_default_outside_menu_use_images;
$ip_start = $ip_default_start;
$ip_title = $ip_default_title;

$panorama_counter = 0; //counts number of panoramas in this post

$text = preg_split("/\n/",$text);



foreach($text as $line){
		$line = trim($line);
		
		if(preg_match('/(.*?)<panorama([^>]*)>(.*?)/i',$line,$foo)){
		$inpanorama = true;
		$panorama_counter++;
		$line = $foo[1];
		$current_panorama = $foo[3];
		
			if(preg_match("/width=\"(\d+)\"/i",$foo[2],$barr)) $ip_width = $barr[1];
			if(preg_match("/height=\"(\d+)\"/i",$foo[2],$barr)) $ip_height = $barr[1];
			if(preg_match("/speed=\"(-?\d+\.*\d+)\"/i",$foo[2],$barr)) $ip_speed = $barr[1];
			if(preg_match("/inside=\"(true)\"/i",$foo[2],$barr)) $ip_inside_menu = true;
			if(preg_match("/inside=\"(false)\"/i",$foo[2],$barr)) $ip_inside_menu = false;
			if(preg_match("/outside=\"(true)\"/i",$foo[2],$barr)) $ip_outside_menu = true;
			if(preg_match("/outside=\"(false)\"/i",$foo[2],$barr)) $ip_outside_menu = false;
			if(preg_match("/images=\"(true)\"/i",$foo[2],$barr)) $ip_outside_menu_use_images = true;
			if(preg_match("/images=\"(false)\"/i",$foo[2],$barr)) $ip_outside_menu_use_images = false;
			if(preg_match("/start=\"(-?\d+\.*\d+)\"/i",$foo[2],$barr)) $ip_start = $barr[1];
			if(preg_match("/title=\"([^\"]*)\"/i",$foo[2],$barr)) $ip_title = $barr[1];
			
			
			
		
		
		
		}
		elseif(preg_match('/(.*?)<\/panorama>(.*?)/i',$line,$foo)){
				$inpanorama = false;
				$inpanorama_items = '';
				$params = '';
				$panorama_hash = 'ptviewer'.$id.$panorama_counter;
				
				preg_match("/<img[^>]+src\s*=\s*\"([^\"]+)\"[^>]*>/i",$current_panorama.$foo[1],$matches);
				
				
				for($i=0;$i<preg_match_all("/<param[^>]*>/i",$current_panorama.$foo[1],$result,PREG_SET_ORDER);$i++)
					{
						$params .= $result[$i][0];
					};
				
				
				
				
				
	
				$inpanorama_items .= "\n\n<div class=\"iimage_panorama\">"
									."<div class=\"iimage_panorama_image\"  title=\"{$ip_title}\">"
									."<applet name=\"{$panorama_hash}\" archive=\"{$ip_viewer}iimage_panorama_ptviewer.jar\"  code=\"ptviewer.class\" width=\"{$ip_width}\" height=\"{$ip_height}\" mayscript=\"true\">"
									.$params
									."<param name=\"file\" value=\"".trim($matches[1])."\" />"
									."<param name=\"cursor\"	value=\"move\" />"
									."<param name=\"view_height\" value=\"{$ip_height}\" />"
									."<param name=\"fov\" value=\"95\" />"
									."<param name=\"pan\" value=\"{$ip_start}\" />"
									."<param name=\"auto\" value=\"{$ip_speed}\" />"
									."<param name=\"bgcolor\" value=\"ffffff\" />"
									."<param name=\"barcolor\" value=\"000000\" />"
									."<param name=\"wait\" value=\"{$ip_viewer}iimage_panorama_wait.gif\" />"
									."<param name=\"waittime\" value=\"1000\" />"
									."<param name=\"quality\" value=\"4\" />"
									."<param name=\"bar_y\" value=\"".($ip_height-10)."\" />";
									
							if($ip_inside_menu){
								$inpanorama_items .= "<param name=\"frame\"		value=\"{$ip_viewer}iimage_panorama_inside.gif\" />"
								."<param name=\"shotspot0\"   value=\" x".($ip_width - 56)." y".($ip_height - 14)." a".($ip_width - 42)." b{$ip_height} u'ptviewer:startAutoPan(".(($ip_speed != 0) ? $ip_speed : '0.5' ).",0,1)' \" />"
									."<param name=\"shotspot1\"   value=\" x".($ip_width - 42)." y".($ip_height - 14)." a".($ip_width - 28)." b{$ip_height} u'ptviewer:stopAutoPan()' \" />"
									."<param name=\"shotspot2\"   value=\" x".($ip_width - 28)." y".($ip_height - 14)." a".($ip_width - 14)." b{$ip_height} u'ptviewer:ZoomIn()' \" />"
									."<param name=\"shotspot3\"   value=\" x".($ip_width - 14)." y".($ip_height - 14)." a{$ip_width} b{$ip_height} u'ptviewer:ZoomOut()' \" />";
									}
									
									$inpanorama_items .= "</applet></div>";

							if($ip_outside_menu){
							
								if($ip_outside_menu_use_images){
										$inpanorama_items .= "<form class=\"iimage_panorama_menu\">"
										."<input type=\"image\" name=\"Play{$panorama_hash}\" src=\"{$ip_viewer}iimage_panorama_play.gif\" class=\"iimage_panorama_button\" onClick=\"{$panorama_hash}.startAutoPan(".(($ip_speed != 0) ? $ip_speed : '0.5' ).",0,1); return false;\"  />"
										."<input type=\"image\" name=\"Stop{$panorama_hash}\" src=\"{$ip_viewer}iimage_panorama_stop.gif\" class=\"iimage_panorama_button\" onClick=\"{$panorama_hash}.stopAutoPan(); return false;\"  />"
										."<input type=\"image\" name=\"Plus{$panorama_hash}\" src=\"{$ip_viewer}iimage_panorama_plus.gif\" class=\"iimage_panorama_button\" onClick=\"{$panorama_hash}.ZoomIn(); return false;\"  />"
										."<input type=\"image\" name=\"Minus{$panorama_hash}\" src=\"{$ip_viewer}iimage_panorama_minus.gif\" class=\"iimage_panorama_button\" onClick=\"{$panorama_hash}.ZoomOut(); return false;\"  />"
										."</form>";	
								
								}
								else {
							
										$inpanorama_items .= "<form>"
										."<input type=\"button\" name=\"Play{$panorama_hash}\" value=\"play\" class=\"iimage_panorama_button\" onClick=\"{$panorama_hash}.startAutoPan(".(($ip_speed != 0) ? $ip_speed : '0.5' ).",0,1);\"  />"
										."<input type=\"button\" name=\"Stop{$panorama_hash}\" value=\"stop\" class=\"iimage_panorama_button\" onClick=\"{$panorama_hash}.stopAutoPan();\"  />"
										."<input type=\"button\" name=\"Plus{$panorama_hash}\" value=\"+\" class=\"iimage_panorama_button\" onClick=\"{$panorama_hash}.ZoomIn();\"  />"
										."<input type=\"button\" nameé=\"Minus{$panorama_hash}\" value=\"-\" class=\"iimage_panorama_button\" onClick=\"{$panorama_hash}.ZoomOut();\"  />"
										."</form>";}
							
							
							}

$inpanorama_items .= "</div>\n\n";
			
				
				$line = $inpanorama_items.$foo[2];
				$current_panorama = '';
				$params = '';
				
				$ip_width = $ip_default_width;
				$ip_height = $ip_default_height;
				$ip_speed = $ip_default_speed;

				$ip_inside_menu = $ip_default_inside_menu;
				$ip_outside_menu = $ip_default_outside_menu;
				$ip_outside_menu_use_images = $ip_default_outside_menu_use_images;
				$ip_start = $ip_default_start;
				$ip_title = $ip_default_title;
				
				}
		elseif($inpanorama){
			$current_panorama .= $line; //we get all "inpanorama" lines to one string
			$line = '';
			}
		else {$line .="\n";}
		
		
		$lineout[] = $line;
	}

	
	
	$text = implode("",$lineout);

return $text;
}



// And now for the filters

add_filter('the_content', 'iimage_panorama_create');

?>