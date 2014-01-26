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

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;
	
	echo '
	<div class="col3 floatright">';
	categori();
	echo '
	</div>';
	
	echo '
	<div class="col6 floatleft">';
	
	echo '
	<div id="tabContainer">
    <div id="tabs">
      <ul>
        <li id="tabHeader_1">', $txt['recent_posts'], '</li>
        <li id="tabHeader_2">', $txt['online_users'], '</li>
        <li id="tabHeader_3">', $txt['forum_stats'], '</li>
      </ul>
    </div>
    <div id="tabscontent">
      <div class="tabpage" id="tabpage_1">';
	 son();
    echo '
      </div>
	  <div class="tabpage" id="tabpage_2">';
	online();
	echo '
	   </div>
      <div class="tabpage" id="tabpage_3">';
	  bilgi();
	  echo '
	  </div>
	  </div></div>';
	  
	if (!empty($settings['film_board'])) {sonfilm();}
	echo'
	</div>';
	
	echo '
	<br class="clear" />';



	template_info_center();
}

function template_info_center()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

}

function bilgi(){
	global $context, $settings, $options, $txt, $scripturl, $modSettings;
	// Show statistical style information...
	if ($settings['show_stats_index'])
	{
		echo ' 
			<p>
				', $context['common_stats']['total_posts'], ' ', $txt['posts_made'], ' ', $txt['in'], ' ', $context['common_stats']['total_topics'], ' ', $txt['topics'], ' ', $txt['by'], ' ', $context['common_stats']['total_members'], ' ', $txt['members'], '. ', !empty($settings['show_latest_member']) ? $txt['latest_member'] . ': <strong> ' . $context['common_stats']['latest_member']['link'] . '</strong>' : '', '<br />
				', (!empty($context['latest_post']) ? $txt['latest_post'] . ': <strong>&quot;' . $context['latest_post']['link'] . '&quot;</strong>  ( ' . $context['latest_post']['time'] . ' )<br />' : ''), '
				<a href="', $scripturl, '?action=recent">', $txt['recent_view'], '</a>', $context['show_stats'] ? '<br />
				<a href="' . $scripturl . '?action=stats">' . $txt['more_stats'] . '</a>' : '', '
			</p>';
	}


}
function online(){
    global $context, $settings, $options, $txt, $scripturl, $modSettings;
	// "Users online" - in order of activity.
	echo '
			<p class="inline stats">
				', $context['show_who'] ? '<a href="' . $scripturl . '?action=who">' : '', comma_format($context['num_guests']), ' ', $context['num_guests'] == 1 ? $txt['guest'] : $txt['guests'], ', ' . comma_format($context['num_users_online']), ' ', $context['num_users_online'] == 1 ? $txt['user'] : $txt['users'];

	// Handle hidden users and buddies.
	$bracketList = array();
	if ($context['show_buddies'])
		$bracketList[] = comma_format($context['num_buddies']) . ' ' . ($context['num_buddies'] == 1 ? $txt['buddy'] : $txt['buddies']);
	if (!empty($context['num_spiders']))
		$bracketList[] = comma_format($context['num_spiders']) . ' ' . ($context['num_spiders'] == 1 ? $txt['spider'] : $txt['spiders']);
	if (!empty($context['num_users_hidden']))
		$bracketList[] = comma_format($context['num_users_hidden']) . ' ' . $txt['hidden'];

	if (!empty($bracketList))
		echo ' (' . implode(', ', $bracketList) . ')';

	echo $context['show_who'] ? '</a>' : '', '
			</p>
			<p class="inline smalltext">';

	// Assuming there ARE users online... each user in users_online has an id, username, name, group, href, and link.
	if (!empty($context['users_online']))
	{
		echo '
				', sprintf($txt['users_active'], $modSettings['lastActive']), ':<br />', implode(', ', $context['list_users_online']);

		// Showing membergroups?
		if (!empty($settings['show_group_key']) && !empty($context['membergroups']))
			echo '
				<br />[' . implode(']&nbsp;&nbsp;[', $context['membergroups']) . ']';
	}
	echo '
			</p>
			<p class="last smalltext">
				', $txt['most_online_today'], ': <strong>', comma_format($modSettings['mostOnlineToday']), '</strong>.
				', $txt['most_online_ever'], ': ', comma_format($modSettings['mostOnline']), ' (', timeformat($modSettings['mostDate']), ')
			</p>';

	// If they are logged in, but statistical information is off... show a personal message bar.
	if ($context['user']['is_logged'] && !$settings['show_stats_index'])
	{
		echo '
			<div class="title_barIC">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						', $context['allow_pm'] ? '<a href="' . $scripturl . '?action=pm">' : '', '<img class="icon" src="', $settings['images_url'], '/message_sm.gif" alt="', $txt['personal_message'], '" />', $context['allow_pm'] ? '</a>' : '', '
						<span>', $txt['personal_message'], '</span>
					</span>
				</h4>
			</div>
			<p class="pminfo">
				<strong><a href="', $scripturl, '?action=pm">', $txt['personal_message'], '</a></strong>
				<span class="smalltext">
					', $txt['you_have'], ' ', comma_format($context['user']['messages']), ' ', $context['user']['messages'] == 1 ? $txt['message_lowercase'] : $txt['msg_alert_messages'], '.... ', $txt['click'], ' <a href="', $scripturl, '?action=pm">', $txt['here'], '</a> ', $txt['to_view'], '
				</span>
			</p>';
	}
}
function son(){
    global $context, $settings, $options, $txt, $scripturl, $modSettings;

		// This is the "Recent Posts" bar.
	if (!empty($settings['number_recent_posts'])  && (!empty($context['latest_posts']) || !empty($context['latest_post'])))
	{
		echo '

				<div class="entry-title" style="display: none;">', $context['forum_name_html_safe'], ' - ', $txt['recent_posts'], '</div>
				<div class="entry-content" style="display: none;">
					<a rel="feedurl" href="', $scripturl, '?action=.xml;type=webslice">', $txt['subscribe_webslice'], '</a>
				</div>';

		// Only show one post.
		if ($settings['number_recent_posts'] == 1)
		{
			// latest_post has link, href, time, subject, short_subject (shortened with...), and topic. (its id.)
			echo '
				<strong><a href="', $scripturl, '?action=recent">', $txt['recent_posts'], '</a></strong>
				<p id="infocenter_onepost" class="middletext">
					', $txt['recent_view'], ' &quot;', $context['latest_post']['link'], '&quot; ', $txt['recent_updated'], ' (', $context['latest_post']['time'], ')<br />
				</p>';
		}
		// Show lots of posts.
		elseif (!empty($context['latest_posts']))
		{
			echo '
				<table id="ic_recentposts" class="middletext" width="100%" ><tbody>';

			/* Each post in latest_posts has:
					board (with an id, name, and link.), topic (the topic's id.), poster (with id, name, and link.),
					subject, short_subject (shortened with...), time, link, and href. */
			foreach ($context['latest_posts'] as $post)
				echo '
					<tr><td width="40%"><strong>', $post['link'], '</strong></td><td width="20%"> ', $txt['by'], ' ', $post['poster']['link'], ' </td><td width="25%">(', $post['board']['link'], ')</td><td width="15%">
					', $post['time'], '</td></tr>';
			echo '
				</tbody></table>';
		}
		
	}
}
function sonfilm(){
		global $smcFunc, $context, $settings, $options, $txt, $scripturl, $modSettings;
		

$boards = array(1,2,3,4);

$request = $smcFunc['db_query']('', '
  SELECT t.id_topic, m.subject, m.body
  FROM {db_prefix}topics AS t
     INNER JOIN {db_prefix}messages AS m ON (m.id_msg = t.id_first_msg)
  WHERE t.id_board IN ({array_int:boards})
  ORDER BY t.id_topic DESC
       LIMIT {int:limit}',
  array(
    'boards' => $boards,
               'limit' => 16,
  )
);
$topics = array();
while ($row = $smcFunc['db_fetch_assoc']($request))
  $topics[] = array(
     'id_topic' => $row['id_topic'],
     'subject' => $row['subject'],
     'body' => $row['body'],
     'first_image'  => preg_match_all('~\[img.*?\]([^\]]+)\[\/img\]~i', $row['body'],  $images) ? '<img src="' . $images[1][0] . '" alt="' .  $row['subject'] . '" height="155" width="100%" />      ' : '',
  );
$smcFunc['db_free_result']($request);
    
	echo '
	<div id="konuis">
	 ' , $txt['latest_post'] ,'
	</div>';
	
	echo '
	<div id="sonfilmayar">';
	
foreach ($topics as $topic)
    echo '
	 <div class="sonfilmayar20">
      <a  href="', $scripturl, '?topic=', $topic['id_topic'], '.0" class="boardfilmst">',  $topic['subject'], '</a><a  href="', $scripturl, '?topic=', $topic['id_topic'], '.0">',  $topic['first_image'], '</a>
	 </div>';
	  
	echo '
	</div>';
}
function categori(){
    global $context, $settings, $options, $txt, $scripturl, $modSettings;

	/* Each category in categories is made up of:
	id, href, link, name, is_collapsed (is it collapsed?), can_collapse (is it okay if it is?),
	new (is it new?), collapse_href (href to collapse/expand), collapse_image (up/down image),
	and boards. (see below.) */
	foreach ($context['categories'] as $category)
	{

		echo '
			<table class="content" id="category_', $category['id'], '_boards">';
			/* Each board in each category's boards has:
			new (is it new?), id, name, description, moderators (see below), link_moderators (just a list.),
			children (see below.), link_children (easier to use.), children_new (are they new?),
			topics (# of), posts (# of), link, href, and last_post. (see below.) */
			foreach ($category['boards'] as $board)
			{
				echo '
				<table id="board_', $board['id'], '" class="win1" style="width: 100%;">
					<tr class="info">
						<td style="width:64%;"><a class="subject" href="', $board['href'], '" name="b', $board['id'], '">', $board['name'], '</a></td>';

				
				// Show some basic information about the number of posts, etc.
					echo '
				
						<td style="width:30%;"><span>', $board['is_redirect'] ? '' : comma_format($board['topics']) . ' ' . $txt['board_topics'], '</span></td>
					
                <td style="width:5%;"><a href="' . $scripturl . '?action=.xml;board=' . $board['id'] . ';type=rss"><img src="' . $settings['images_url'] . '/rss.png" alt="rss" /></a></td>'; 

echo'   
				</tr>';
				// Show the "Child Boards: ". (there's a link_children but we're going to bold the new ones...)
				if (!empty($board['children']))
				{
					// Sort the links into an array with new boards bold so it can be imploded.
					$children = array();
					/* Each child in each board's children has:
							id, name, description, new (is it new?), topics (#), posts (#), href, link, and last_post. */
					foreach ($board['children'] as $child)
					{
						if (!$child['is_redirect'])
							$child['link'] = '<a href="' . $child['href'] . '" ' . ($child['new'] ? 'class="new_posts" ' : '') . 'title="' . ($child['new'] ? $txt['new_posts'] : $txt['old_posts']) . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')">' . $child['name'] . ($child['new'] ? '</a> <a href="' . $scripturl . '?action=unread;board=' . $child['id'] . '" title="' . $txt['new_posts'] . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')"><img src="' . $settings['lang_images_url'] . '/new.gif" class="new_posts" alt="" />' : '') . '</a>';
						else
							$child['link'] = '<a href="' . $child['href'] . '" title="' . comma_format($child['posts']) . ' ' . $txt['redirects'] . '">' . $child['name'] . '</a>';

						// Has it posts awaiting approval?
						if ($child['can_approve_posts'] && ($child['unapproved_posts'] || $child['unapproved_topics']))
							$child['link'] .= ' <a href="' . $scripturl . '?action=moderate;area=postmod;sa=' . ($child['unapproved_topics'] > 0 ? 'topics' : 'posts') . ';brd=' . $child['id'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '" title="' . sprintf($txt['unapproved_posts'], $child['unapproved_topics'], $child['unapproved_posts']) . '" class="moderation_link">(!)</a>';

						$children[] = $child['new'] ? '<strong>' . $child['link'] . '</strong>' : $child['link'];
					}
					echo '
					<tr id="board_', $board['id'], '_children">
						<td colspan="3" class="children win2">
							<table style="width:100%">
								<tr>';

							foreach ($children as $key => $child)
							{
								if ($key % 2 == 0 && $key != 0)
								echo '
								</tr>
								<tr>';

								echo '
									<td>', $child, '</td>';
							}

							echo '
								</tr>
							</table>
						</td>
					</tr>';
				}
			echo '
			</table>';
			}

	}
}
?>

