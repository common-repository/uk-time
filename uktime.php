<?php
/*
Plugin Name: UK Time
Plugin URI: http://www.alexcoles.com/wordpress/uk-time-wordpress-plugin/
Description: Checks dates and times and adjusts for British Summer Time if applicable. Requires the use of the defaults <code>the_time()</code> and <code>the_date()</code> in the active theme. No times are permanently changed.
Author: Alex Coles
Version: 1.2
Author URI: http://www.alexcoles.com
*/ 

function adjust_for_BST($timestamp)
{
	//  find the year of the supplied timestamp
	$supplied_year=(date('Y',$timestamp));
	//  Find the date (mm-dd-yyyy) of the last Sunday in March of the given year...
	$date_bits=split(",",date('n,j,Y',strtotime('last Sunday', mktime(0,0,0,3,32,$supplied_year))));
	//  ... and convert it to a Unix timestamp, setting the hour to 1.00am (start of British Summer Time).
	$BST_start=mktime(1,0,0,$date_bits[0],$date_bits[1],$date_bits[2]);
	
	//  Now do the same thing with the last Sunday in October.
	$date_bits=split(",",date('n,j,Y',strtotime('last Sunday', mktime(0,0,0,10,32,$supplied_year))));
	$BST_end=mktime(1,0,0,$date_bits[0],$date_bits[1],$date_bits[2]);
	
	//  Add an hour to the time, if the date falls within BST.
	if (($timestamp>=$BST_start) && ($timestamp<$BST_end)) { $timestamp=$timestamp+3600; }
	return $timestamp;
}

function adjust_post_time($supplied_time)
{
	//  Check if supplied arg is a date or a time (so this function can be used for both)
	if ( $supplied_time==get_post_time(get_settings('time_format')) )
	{
		$filter='time';
	}
	elseif ( $supplied_time==get_post_time(get_settings('date_format')) )
	{
		$filter='date';
	}
	//  Date or time format is set by theme -- do nothing.
	else
	{
		return $supplied_time;
	}
	// retrieve post time in Unix timestamp format
	$timestamp=adjust_for_BST(get_post_time('U',true));
	if ($filter=='time')
	{
		return date(get_settings('time_format'),$timestamp);
	}
	else
	{
		return date(get_settings('date_format'),$timestamp);
	}
}

function adjust_comment_time($supplied_time)
{
	global $comment;
	//  Check if supplied arg is a date or a time (so this function can be used for both)
	if ( $supplied_time==mysql2date(get_option('time_format'), $comment->comment_date_gmt) )
	{
		$filter='time';
	}
	elseif ( $supplied_time==mysql2date(get_option('date_format'), $comment->comment_date_gmt) )
	{
		$filter='date';
	}
	//  Date or time format is set by theme -- do nothing.
	else
	{
		return $supplied_time;
	}
	// retrieve post time in Unix timestamp format
	$timestamp=adjust_for_BST(mysql2date('U', $comment->comment_date_gmt));
	if ($filter=='time')
	{
		return date(get_settings('time_format'),$timestamp);
	}
	else
	{
		return date(get_settings('date_format'),$timestamp);
	}
}

add_filter('the_time', 'adjust_post_time');
add_filter('the_date', 'adjust_post_time');
add_filter('get_comment_time', 'adjust_comment_time');
add_filter('get_comment_date', 'adjust_comment_time');
?>