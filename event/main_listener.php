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
    /** @var \phpbb\user */
    protected $user;
	
    /** @var \phpbb\language\language */
    protected $language;

    static public function getSubscribedEvents()
    {
	return array(
		'core.user_setup' => 'load_language_on_setup',
		'core.posting_modify_cannot_edit_conditions'	=> 'post_edit',
		'core.viewtopic_modify_post_action_conditions'	=> 'viewtopic_edit',
		'core.permissions'	=> 'add_permissions',
		'core.modify_posting_auth'   => 'post_auth',
		'core.mcp_lock_unlock_after' => 'force_unlock',
		'core.viewtopic_assign_template_vars_before' => 'inject_template_vars',
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
	
	public function force_unlock($event)
	{
	if ($event['action'] == 'unlock')
		lock_unlock($event['action'], $event['ids']);
	}
	public function inject_template_vars($event)
	{
		
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
		
	}
	
	public function viewtopic_edit($event)
	{
		
	}
	public function add_permissions($event)
	{
		
	}
	public function post_auth($event)
	{
		
	}
	public function add_unlock($event)
	{
		
	}
}



