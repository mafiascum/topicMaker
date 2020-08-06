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
	$force_edit_allowed = $event['force_edit_allowed'];
	$force_edit_allowed = true;
	print("<div>The code inside cto</div>");
	$event['force_edit_allowed'] = $force_edit_allowed;
	$s_cannot_edit_locked = $event['s_cannot_edit_locked'];
	$s_cannot_edit_locked = ITEM_UNLOCKED;
	$event['s_cannot_edit_locked'] = $s_cannot_edit_locked;
	}
	
	public function load_language_on_setup($event)
	{
	$lang_set_ext = $event['lang_set_ext'];
	print("<div>load lang</div>");
        $lang_set_ext[] = array(
            'ext_name' => 'mafiascum/topicMaker',
            'lang_set' => 'common',
        );
        $event['lang_set_ext'] = $lang_set_ext;
	}
	

}



