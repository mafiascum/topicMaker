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
			'core.memberlist_view_profile'			=> 'replace_joined_lang_entry',
			);
	}
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\request\request $request, \phpbb\db\driver\driver_interface $db)
	{
	    $this->helper = $helper;
        $this->request = $request;
        $this->db = $db;

	$this->language = $language;
		$this->template = $template;
		$this->user = $user;
	}

	public function check_topic_own($event)
	{
	print("<div>The code inside blah blah</div>");
	print_r($event);
	$force_edit_allowed = $event['force_edit_allowed'];
	$force_edit_allowed = true;
	$event['force_edit_allowed'] = $force_edit_allowed;
	$s_cannot_edit_locked = $event['s_cannot_edit_locked'];
	$s_cannot_edit_locked = ITEM_UNLOCKED;
	$event['s_cannot_edit_locked'] = $s_cannot_edit_locked;
	}
	public function load_language_on_setup($event)
	{$lang_set_ext = $event['lang_set_ext'];
	print("<div>The code inside blah blah</div>");
        $lang_set_ext[] = array(
            'ext_name' => 'mafiascum/topicMaker',
            'lang_set' => 'common',
        );
        $event['lang_set_ext'] = $lang_set_ext;
	}
		/**
	 * Replaces 'JOINED' language entry with 'REGISTEREDFOR' one
	 *
	 * @param \phpbb\event\data	$event		Event object
	 * @param string			$eventname	Name of the event
	 */
	public function replace_joined_lang_entry($event, $eventname)
	{
		$user_id = $event['topic_data']['topic_poster'] ?? $event['member']['user_id'] ?? $event['message_row']['author_id'] ?? ANONYMOUS;
		if ((int) $user_id != ANONYMOUS)
		{
			if ($eventname == 'core.memberlist_view_profile')
			{
				$this->language->add_lang('registeredfor', 'rxu/registeredfor');
			}
			$this->template->assign_var('L_JOINED', $this->language->lang('REGISTEREDFOR'));
		}
	}
}



