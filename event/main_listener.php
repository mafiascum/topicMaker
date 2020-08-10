<?php

namespace mafiascum\topicMaker\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{
	/* @var \phpbb\controller\helper */
    protected $helper;

    /* @var \phpbb\template\template */
    protected $template;

    /* @var \phpbb\request\request */
    protected $request;
	/* @var \phpbb\auth\auth */
	protected $auth;
    /* @var \phpbb\db\driver\driver */
	protected $db;
	/** @var \phpbb\user */
	protected $user;
	
	/** @var \phpbb\language\language */
	protected $language;

	static public function getSubscribedEvents()
	{
		return array(
			'core.posting_modify_cannot_edit_conditions' => 'check_topic_own',
			'core.user_setup' => 'load_language_on_setup',
			'core.posting_modify_cannot_edit_conditions'	=> 'post_edit',
			'core.viewtopic_modify_post_action_conditions'	=> 'viewtopic_edit',
			'core.permissions'								=> 'add_permissions',
			'core.modify_posting_auth'   					=> 'post_auth',
			);
	}
	
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\request\request $request, \phpbb\db\driver\driver_interface $db)
	{
	$this->helper = $helper;
    $this->request = $request;
    $this->db = $db;
	$this->auth = $auth;
	$this->user = $user;
	$this->language = $language;
	$this->template = $template;
	$this->user = $user;
	}

	public function check_topic_own($event)
	{
	print("<div>The code inside blah blah</div>");
	$force_edit_allowed = $event['force_edit_allowed'];
	$force_edit_allowed = true;
	$event['force_edit_allowed'] = $force_edit_allowed;
	$s_cannot_edit_locked = $event['s_cannot_edit_locked'];
	$s_cannot_edit_locked = ITEM_UNLOCKED;
	$event['s_cannot_edit_locked'] = $s_cannot_edit_locked;
	}
	
	public function load_language_on_setup($event)
	{
	$lang_set_ext = $event['lang_set_ext'];
	
        $lang_set_ext[] = array(
            'ext_name' => 'mafiascum/topicMaker',
            'lang_set' => 'common',
        );
        $event['lang_set_ext'] = $lang_set_ext;
	}
	
	public function post_edit($event)
	{
		// Are we working on the first post of the topic?
		$is_first_post = $event['post_data']['topic_first_post_id'] == $event['post_data']['post_id'];
		$is_author = $event['post_data']['poster_id'] == $this->user->data['user_id'];

		// Time based editing
		if ($event['s_cannot_edit_time'])
		{
			// First post time bypass
			$allowed_forum = $this->auth->acl_get('f_time_edit_first_post', $event['post_data']['forum_id']);
			$event['s_cannot_edit_time'] = !($is_first_post && $allowed_forum);

			// Other posts:
			if (!$is_first_post)
			{
				$event['s_cannot_edit_time'] = !$this->auth->acl_get('f_time_edit', $event['post_data']['forum_id']);
			}
		}

		// Independent permissions for first post:
		if ($is_first_post)
		{
			$event['s_cannot_edit'] = !($is_author && $this->auth->acl_get('f_edit_first_post', $event['post_data']['forum_id']));
		}
		else
		{
			// We need to check again for edit permissions because we bypassed that earlier
			// Ignore data served by event because we changed the way this permission works
			$event['s_cannot_edit'] = !($is_author && $this->auth->acl_get('f_edit', $event['post_data']['forum_id']));
		}
	}
	public function viewtopic_edit($event)
	{
		$is_first_post = $event['topic_data']['topic_first_post_id'] == $event['row']['post_id'];
		$is_author = $event['row']['user_id'] == $this->user->data['user_id'];

		// Time based editing
		if ($event['s_cannot_edit_time'])
		{
			// First Post
			$allowed_forum = $this->auth->acl_get('f_time_edit_first_post', $event['row']['forum_id']);
			$event['s_cannot_edit_time'] = !($is_first_post && $allowed_forum);

			// Other posts
			if (!$is_first_post)
			{
				$event['s_cannot_edit_time'] = !$this->auth->acl_get('f_time_edit', $event['row']['forum_id']);
			}
		}

		// Independent permissions for first post:
		if ($is_first_post)
		{
			$event['s_cannot_edit'] = !($is_author && $this->auth->acl_get('f_edit_first_post', $event['row']['forum_id']));
		}
		else
		{
			// We need to check again for edit permissions because we bypassed that earlier
			// Ignore data served by event because we changed the way this permission works
			$event['s_cannot_edit'] = !($is_author && $this->auth->acl_get('f_edit', $event['row']['forum_id']));
		}
	}
	public function add_permissions($event)
	{
		// We redefine f_edit so its new meaning is reflected in the text of the permissions
		$event['permissions'] = array_merge($event['permissions'], array(
			// Forum perms
			'f_edit_first_post'			=> array('lang' => 'ACL_F_FIRST_POST_EDIT', 'cat' => 'post'),
			'f_time_edit_first_post'	=> array('lang' => 'ACL_F_TIME_FIRST_POST_EDIT', 'cat' => 'post'),
			'f_time_edit'				=> array('lang' => 'ACL_F_TIME_EDIT', 'cat' => 'post'),
			'f_edit'					=> array('lang' => 'ACL_F_EDIT_REPLY', 'cat' => 'post'),
		));
	}
	public function post_auth($event)
	{
		if (!$event['is_authed'] && $event['mode'] == 'edit')
		{
			// May still be authed if f_edit_first_post is set
			$event['is_authed'] = $this->auth->acl_get('f_edit_first_post', $event['forum_id']);

			// May also be authed if f_edit is set
			// overrule any additional extension run before this one to keep things consistent
			$event['is_authed'] = $event['is_authed'] || $this->auth->acl_get('f_edit', $event['forum_id']);
		}
	}
}



