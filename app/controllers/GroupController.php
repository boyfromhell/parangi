<?php namespace Parangi;

use App;
use View;

class GroupController extends BaseController
{
    use \Earlybird\FoundryController;

	/**
	 * Show all groups
	 *
	 * @return Response
	 */
	public function showAll()
	{
		$_PAGE = array(
			'category' => 'community',
			'title'    => 'Groups',
		);

		// Groups
		$groups = Group::where('approved', '=', 1)
			->orderBy('name', 'asc')
			->get();

		return View::make('groups.list')
			->with('_PAGE', $_PAGE)
			->with('menu', GroupController::fetchMenu('groups'))
			->with('groups', $groups);
	}

	/**
	 * Display a group by name
	 *
	 * @param  string  $name
	 */
	public function displayName($name)
	{
		$name = urldecode($name);
		$group = Group::where('name', '=', $name)->first();

		if (! $group->id) {
			App::abort(404);
		} else {
			return $this->display($group->id);
		}
	}

	/**
	 * Display a group
	 *
	 * @param  int  $id
	 * @param  string  $name  For SEO only
	 * @return Response
	 */
	public function display($id, $name = null)
	{
		$group = Group::findOrFail($id);

		// @todo add and remove members
		// @todo request to join groups
		// @todo send private message to all members

		// Add a new member to this group
		/*if (isset($_POST['add_member']) && $group->check_membership($me->id) == 2) {
			$id = User::lookup_id($_POST['username']);
			$group->add_member($id, (int)$_POST['type']);
			header("Location: " . $group->url);
			exit;
		}*/

		$_PAGE = array(
			'category' => 'community',
			'title'    => $group->name,
		);

		return View::make('groups.display')
			->with('_PAGE', $_PAGE)
			->with('menu', GroupController::fetchMenu('groups'))
			->with('group', $group);

		/*$Smarty->assign('info', $group->get_info($mygroups));
		$Smarty->assign('membership', $group->check_membership($me->id));*/
	}

	public function get_type()
	{
		switch ($this->type) {
			case 'open':
				return 'Open';
				break;

			case 'closed':
				return 'Closed';
				break;

			case 'invite':
			default:
				return 'Invite Only';
				break;
		}
	}

	public function get_info($my_groups = array())
	{
		if (in_array($this->id, $my_groups)) {
			return 'You are a member of this group';
		}

		switch ($this->type) {
			case 'open':
				return 'You may join this group';
				break;

			case 'closed':
				return 'You may request to join this group';
				break;

			case 'invite':
			default:
				return 'This group is invite-only';
				break;
		}
	}
	
	/**
	 * Add a member based on ID
	 */
	public function add_member($user_id, $type)
	{
		global $_db;

		$sql = "INSERT INTO `users_groups` SET
			`group_id` = {$this->id},
			`user_id`  = {$user_id},
			`type`     = {$type}
			ON DUPLICATE KEY UPDATE
			`type`     = {$type}";
		$_db->query($sql);
	}

	/**
	 * Delete a member based on ID
	 */
	public function delete_member($user_id)
	{
		global $_db;

		$sql = "DELETE FROM `users_groups`
			WHERE `group_id` = {$this->id}
				AND `user_id` = {$user_id}";
		$_db->query($sql);
	}

	/** 
	 * Check if user ID is a member
	 * @return type mixed 1 if member, 2 if moderator, false if not a member
	 */
	public function check_membership($user_id)
	{
		global $_db, $me;

		if ($user_id == $me->id && $me->is_admin) {
			return 2;
		}
	
		$sql = "SELECT `type`
			FROM `users_groups`
			WHERE `group_id` = {$this->id}
				AND `user_id` = {$user_id}";
		$exec = $_db->query($sql);

		if ($exec->num_rows) {
			list($type) = $exec->fetch_row();
			return ($type + 1);
		}
		return false;
	}
	
	public function delete()
	{
		global $_db;
		
		$sql = "DELETE FROM `users_groups`
			WHERE `group_id` = {$this->id}";
		$_db->query($sql);
		
		$sql = "DELETE FROM `groups`
			WHERE `id` = {$this->id}";
		$_db->query($sql);
		
		// todo: delete badge image?
	}

	/**
	 * Menu for community pages
     *
     * @return array
     */
    public static function fetchMenu($active = null)
    {
		global $me;

        $menu = array();

        $menu['members'] = array(
            'url' => '/members',
            'name' => 'Members',
        );
        $menu['groups'] = array(
            'url' => '/groups',
            'name' => 'Groups',
        );
        $menu['honor-rolls'] = array(
            'url' => '/honor-rolls',
            'name' => 'Honor Rolls',
        );
		$menu['badges'] = array(
			'url' => '/badges',
			'name' => 'Badges',
		);
        $menu['chat'] = array(
            'url' => '/community/chat',
            'name' => 'Chat',
        );
		if ($me->id) {
			$menu['gmail'] = array(
				'url' => 'http://mail.attnam.com/',
				'name' => 'Gmail',
			);
		}

		if ($active !== null) {
	        $menu[$active]['active'] = true;
		}

        return $menu;
    }

}

