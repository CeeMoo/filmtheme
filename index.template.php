<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */

/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	/* The version this template/theme is for.
		This should probably be the version of SMF it was created for. */
	$settings['theme_version'] = '2.0';

	/* Set a setting that tells the theme that it can render the tabs. */
	$settings['use_tabs'] = true;

	/* Use plain buttons - as opposed to text buttons? */
	$settings['use_buttons'] = true;

	/* Show sticky and lock status separate from topic icons? */
	$settings['separate_sticky_lock'] = true;

	/* Does this theme use the strict doctype? */
	$settings['strict_doctype'] = false;

	/* Does this theme use post previews on the message index? */
	$settings['message_index_preview'] = false;

	/* Set the following variable to true if this theme requires the optional theme strings file to be loaded. */
	$settings['require_theme_strings'] = false;
}

// The main sub template above the content.
function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>';

	// The ?fin20 part of this link is just here to make sure browsers don't cache it wrongly.
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />';

	// Some browsers need an extra stylesheet due to bugs/compatibility issues.
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';

	// RTL languages require an additional stylesheet.
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';
	
     //--[if lte IE 7]>
     echo  '
     <style type="text/css">
     html .jqueryslidemenu{height: 1%;} /*Holly Hack for IE7 and below*/
     </style>';
     //[endif]-->

    //menu code json_deco
	echo '
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>';


	// Here comes the JavaScript bits!
	echo '
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/tabs_old.js"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';

	echo '
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>';

	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="', $scripturl, '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
</head>
<body>';
}

function template_body_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
    <div id="wrapper">';
	
	       if (!empty($settings['film_slider'])) {flimslider();}
	echo '
	<div id="filmmenu">
	 ' , template_menu() ,'
	</div>';

   echo '
	<div id="header">
		 <div id="top_section">';
		 
	// Show a random news item? (or you could pick one from news_lines...)
	if (!empty($settings['enable_news']))
		echo '
		<div id="newscover">
<div id="newscovery">
<br/><h2>', $txt['news'], ': <br/>
				', $context['random_news_line'], '</h2> </div></div>';

		 
    echo '
	<div id="logo">
        <h1 class="forumtitle">
             <a href="', $scripturl, '">', empty($context['header_logo_url_html_safe']) ? '<img width="140" src="' . $settings['images_url'] . '/logo.png" alt="' . $context['forum_name'] . '" />' : '<img src="' . $context['header_logo_url_html_safe'] . '" alt="' . $context['forum_name'] . '" />', '</a>			
        </h1>
    </div>';
		 
    echo ' 
    <div id="usergo">';
      // If the user is logged in, display stuff like their name, new messages, etc.
	if ($context['user']['is_logged'])
	{
            echo '<div id="teknouser">
                    <ul class="tekno">
                                        <li>';
		if (!empty($context['user']['avatar']))
                    echo 
                           $context['user']['avatar']['image'];
                 else 
                    echo '<img class="avatar" src="' . $settings['images_url'] . '/avatar.png" alt="" />';
                 
		echo '</li>
					<li><h2>', $txt['hello_member_ndt'], '! ', $context['user']['name'], '</h2></li>
					<li><a href="', $scripturl, '?action=unread">', $txt['unread_since_visit'], '</a></li>
					<li><a href="', $scripturl, '?action=unreadreplies">', $txt['show_unread_replies'], '</a></li>';
		echo '</ul></div>';
	}
	// Otherwise they're a guest - this time ask them to either register or login - lazy bums...
	elseif (!empty($context['show_login_bar']))
	{
		echo '
				<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
				<form id="guest_form" action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
                                         <input type="text" name="user" class="input_text" value="' , $txt['username'] , '" onfocus="this.value=(this.value==\'' ,$txt['username'] , '\') ? \'\' : this.value;" onblur="this.value=(this.value==\'\') ? \'' ,$txt['username'] , '\' : this.value;"/>
					 <input type="password" name="passwrd"  class="input_password" value="' , $txt['password'] , '" onfocus="this.value=(this.value==\'' ,$txt['password'] , '\') ? \'\' : this.value;" onblur="this.value=(this.value==\'\') ? \'' ,$txt['password'] , '\' : this.value;" />			
					<input type="submit" value="', $txt['login'], '" class="button_submit" /><br />';
                

		if (!empty($modSettings['enableOpenID']))
			echo '
					<br /><input type="text" name="openid_identifier" id="openid_url" size="25" class="input_text openid_login" />';

		echo '
					<input type="hidden" name="hash_passwrd" value="" />
				</form>';
	}
    echo '   </div>';
	

	echo '
	<div class="news normaltext"><form id="search_form" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
					<input type="text" name="search" value="" class="input_text" />&nbsp;
					<input type="submit" name="submit" value="', $txt['search'], '" class="button_submit" />
					<input type="hidden" name="advanced" value="0" /></form></div>';
					
    echo '
	<div class="timezaman">
	  <span id="zaman1">',timeformat(time(),'%d %B %Y'), '</span><br />
	   ', $txt['saat'], ' 
	  <span id="zaman2">', $txt['loading'], '</span>	
					
    <script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
function refrClock()
{
var d=new Date();
var s=d.getSeconds();
var m=d.getMinutes();
var h=d.getHours();
var am_pm;
if (s<10) {s="0" + s}
if (m<10) {m="0" + m}
if (h>24) {h="24"}
else {am_pm=""}
if (h<10) {h="0" + h}
document.getElementById("zaman2").innerHTML=h + ":" + m + ":" + s + am_pm;
setTimeout("refrClock()",1000);
}
refrClock();
	// ]]></script></div>';
	
	
      echo'
   <div class="teknosocial">
          
       <ul class="tekno_social">';
       if (!empty($settings['active_twitter'])) {
        echo '<li><a class="twitter" href="',$settings['url_twitter'],'" target="_blank"></a></li>';
        }
       if (!empty($settings['active_google'])) {
        echo '<li><a class="gplus" href="',$settings['url_google'],'" target="_blank"></a></li>';
        }
       if (!empty($settings['active_facebook'])) {
        echo '<li><a class="facebook" href="',$settings['url_facebook'],'" target="_blank"><span></span></a></li>';
        }
       if (!empty($settings['active_youtube'])) {
        echo '<li><a class="youtube" href="',$settings['url_youtube'],'" target="_blank"></a></li>';
        }
       if (!empty($settings['active_rss'])) {
        echo '<li><a class="rss" href="' . $scripturl . '?action=.xml;type=rss" target="_blank"></a></li>';
        }
        echo '
      </ul>
	</div>';
			


	

	echo '

	</div></div>';
	

	// Custom banners and shoutboxes should be placed here, before the linktree.

	// Show the navigation tree.
	theme_linktree();
	
	//forum orta arkaplan
	echo '
	<div id="forumorta">';
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

    echo '
	</div>';
	
	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
		echo '
		<div id="fottmenu">
	<div class="fottcontainer p1">
				<div class="card">';
        if (!empty($settings['active_teknobox0'])) {
        echo '<div class="obverse"><h1>',$settings['url_teknobox0b'],'</h1><img src="',$settings['url_teknobox0i'],'" alt="" /></div>
			<div class="reverse"><h2>',$settings['url_teknobox0t'],'</h2><ol>',$settings['url_teknobox0'],'</ol></div>';
        }			
		echo '	
		</div>
	</div>
	<div class="fottcontainer p2">
		<div class="card">';
        if (!empty($settings['active_teknobox1'])) {
        echo '<div class="obverse"><h1>',$settings['url_teknobox1b'],'</h1><img src="',$settings['url_teknobox1i'],'" alt="" /></div>
			<div class="reverse"><h2>',$settings['url_teknobox1t'],'</h2><ol>',$settings['url_teknobox1'],'</ol></div>';
        }			
		echo '	
		</div>
	</div>
	<div class="fottcontainer p3">
		<div class="card">
			';
		  if (!empty($settings['active_teknobox2'])) {
        echo '<div class="obverse"><h1>',$settings['url_teknobox2b'],'</h1><img src="',$settings['url_teknobox2i'],'" alt="" /></div>
			<div class="reverse"><h2>',$settings['url_teknobox2t'],'</h2><ol>',$settings['url_teknobox2'],'</ol></div>';
        }
		echo '
		</div>
	</div>
	<div class="fottcontainer p4">
		<div class="card">';
		  if (!empty($settings['active_teknobox3'])) {
        echo '<div class="obverse"><h1>',$settings['url_teknobox3b'],'</h1><img src="',$settings['url_teknobox3i'],'" alt="" /></div>
			<div class="reverse"><h2>',$settings['url_teknobox3t'],'</h2><ol>',$settings['url_teknobox3'],'</ol></div>';
        }
		echo '
		</div>
	</div>
</div><ul class="lisans">
			<li class="copyright">', theme_copyright(), '</li></ul>';
	// Show the load time?
	if ($context['show_load_time'])
		echo '
		<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

	echo '
 </div>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<div class="navigate_section">
		<ul>';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
			<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';

		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		// Show the link, including a URL if it should have one.
		echo $settings['linktree_link'] && isset($tree['url']) ? '
				<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		// Don't show a separator for the last one.
		if ($link_num != count($context['linktree']) - 1)
			echo ' &#187;';

		echo '
			</li>';
	}
	echo '
		</ul>
	</div>';

	$shown_linktree = true;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
   <div id="myslidemenu" class="jqueryslidemenu">
    <ul>';

	foreach ($context['menu_buttons'] as $act => $button)
	{
		echo '
				<li id="button_', $act, '">
					<a class="', $button['active_button'] ? 'active ' : '" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>
						', $button['title'], '
					</a>';
		if (!empty($button['sub_buttons']))
		{
			echo '
					<ul>';

			foreach ($button['sub_buttons'] as $childbutton)
			{
				echo '
						<li>
							<a href="', $childbutton['href'], '"', isset($childbutton['target']) ? ' target="' . $childbutton['target'] . '"' : '', '>
							', $childbutton['title'], !empty($childbutton['sub_buttons']) ? '...' : '', '
							</a>';
				// 3rd level menus :)
				if (!empty($childbutton['sub_buttons']))
				{
					echo '
							<ul>';

					foreach ($childbutton['sub_buttons'] as $grandchildbutton)
						echo '
								<li>
									<a href="', $grandchildbutton['href'], '"', isset($grandchildbutton['target']) ? ' target="' . $grandchildbutton['target'] . '"' : '', '>
										', $grandchildbutton['title'], '
									</a>
								</li>';

					echo '
							</ul>';
				}

				echo '
						</li>';
			}
				echo '
					</ul>';
		}
		echo '
				</li>';
	}

	echo '
			</ul>
          </div>';
}

// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a></li>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul>',
				implode('', $buttons), '
			</ul>
		</div>';
}

function flimslider() {

    echo '
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
$.fn.infiniteCarousel = function () {
    function repeat(str, num) {
        return new Array( num + 1 ).join( str );
    }
  
    return this.each(function () {
        var $wrapper = $(\'> div\', this).css(\'overflow\', \'hidden\'),
            $slider = $wrapper.find(\'> ul\'),
            $items = $slider.find(\'> li\'),
            $single = $items.filter(\':first\'),
            
            singleWidth = $single.outerWidth(), 
            visible = Math.ceil($wrapper.innerWidth() / singleWidth), // note: doesn\'t include padding or border
            currentPage = 1,
            pages = Math.ceil($items.length / visible);            

            if (($items.length % visible) != 0) {
            $slider.append(repeat(\'<li class="empty" />\', visible - ($items.length % visible)));
            $items = $slider.find(\'> li\');
        }

        $items.filter(\':first\').before($items.slice(- visible).clone().addClass(\'cloned\'));
        $items.filter(\':last\').after($items.slice(0, visible).clone().addClass(\'cloned\'));
        $items = $slider.find(\'> li\'); 
        
        $wrapper.scrollLeft(singleWidth * visible);
        
            function gotoPage(page) {
            var dir = page < currentPage ? -1 : 1,
                n = Math.abs(currentPage - page),
                left = singleWidth * dir * visible * n;
            
            $wrapper.filter(\':not(:animated)\').animate({
                scrollLeft : \'+=\' + left
            }, 500, function () {
                if (page == 0) {
                    $wrapper.scrollLeft(singleWidth * visible * pages);
                    page = pages;
                } else if (page > pages) {
                    $wrapper.scrollLeft(singleWidth * visible);
                    // reset back to start position
                    page = 1;
                } 
                currentPage = page;
            });                         
            return false;
        }
        
        $wrapper.after(\'<a class="icon-angle-left"></a><a class="icon-angle-right"></a>\');
        
          $(\'a.icon-angle-left\', this).click(function () {
            return gotoPage(currentPage - 1);                
        });
        
        $(\'a.icon-angle-right\', this).click(function () {
            return gotoPage(currentPage + 1);
        });
        
        $(this).bind(\'goto\', function (event, page) {
            gotoPage(page);
        });
    });  
};
$(document).ready(function () {
  $(\'.infiniteCarousel\').infiniteCarousel();
});
//]]>
</script>';

		
 global $smcFunc, $scripturl, $settings, $options, $txt ,$context, $modSettings;
		

$boards=array(3);

$request = $smcFunc['db_query']('', '
  SELECT t.id_topic, m.subject, m.body
  FROM {db_prefix}topics AS t
     INNER JOIN {db_prefix}messages AS m ON (m.id_msg = t.id_first_msg)
  WHERE t.id_board IN ({array_int:boards})
  ORDER BY t.id_topic DESC
       LIMIT {int:limit}',
  array(
    'boards' => $boards,
               'limit' => 50,
  )
);
$topics = array();
while ($row = $smcFunc['db_fetch_assoc']($request))
  $topics[] = array(
     'id_topic' => $row['id_topic'],
     'subject' => $row['subject'],
     'first_image'  => preg_match_all('~\[img.*?\]([^\]]+)\[\/img\]~i', $row['body'],  $images) ? '<img src="' . $images[1][0] . '" alt="' .  $row['subject'] . '" height="145" width="102"/>      ' : '',
  );
$smcFunc['db_free_result']($request);

     echo '
    <div id="caja-carrusel">
<div class="infiniteCarousel">
<div class="wrapper" style="overflow-x: hidden; overflow-y: hidden; ">
<ul>
      
                   ';
foreach ($topics as $topic)
  echo '
                       
      
<li><a  href="', $scripturl, '?topic=', $topic['id_topic'], '.0">',  $topic['first_image'], ' </a></li>

                      ';
echo '
                  
   </ul>
</div>
</div></div>';
}

?>
