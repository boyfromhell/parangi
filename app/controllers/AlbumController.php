<?php

class AlbumController extends Earlybird\FoundryController
{

	/**
	 * Gallery page
	 *
	 * @return Response
	 */
	public function gallery()
	{
		// @todo
		$access = 2;

		$_PAGE = array(
			'category' => 'gallery',
			'section'  => 'gallery',
			'title'    => 'Media'
		);

		$limit = $is_mobile ? 1 : 6;

		// Get random photos
		$photos = Photo::join('albums', 'photos.album_id', '=', 'albums.id')
			->where('albums.permission_view', '<=', $access)
			->orderBy(DB::raw('RAND()'), 'asc')
			->take($limit)
			->get(['photos.*']);
		if( count($photos) > 0 ) {
			$photos->load(['album', 'user']);
		}

		// Get recent albums
		$albums = Album::where('permission_view', '<=', $access)
			->whereHas('photos')
			->orderBy('modified', 'desc')
			->take($limit)
			->get();
		if( count($albums) > 0 ) {
			$albums->load(['user']);
		}

		return View::make('albums.gallery')
			->with('_PAGE', $_PAGE)
			->with('photos', $photos)
			->with('albums', $albums);

		/**
		<?php 
		if( $board_apps["videos"]["enabled"] && $board_apps["videos"]["permission"] <= $access ) {
		?>
		<tr>
			<th>Videos</th>
		</tr>
		<tr>
			<td class="category">Recent Videos</td>
		</tr>
		<tr>
			<td class="lower">
			<div style='overflow:hidden; height:180px;'>
			<?php
			$sql = "SELECT video_id, users.id, users.name, video_filename, video_name
			FROM videos, users, video_albums
			WHERE video_approved = 1 AND video_owner = users.id 
				AND video_album = album_id AND permission_view <= '$access'
			ORDER BY video_date DESC LIMIT 6";
			$res = query($sql, __FILE__, __LINE__);
			while( $vid = mysql_fetch_array($res)) {
				list( $v, $ownid, $owner, $thumb, $name ) = $vid;
				$name = stripslashes($name);
				echo "<div class=\"photo\" style=\"height:185px;\">
				<a class=\"thumb\" href=\"video.php?v=$v\"><img src=\"videos/preview/".$thumb."_sm.jpg\"></a>
				<div style=\"height:16px;overflow:hidden;\">$name</div>
				<small>by <a href=\"profile.php?u=$ownid\">$owner</a></small></div>\n";
			}
			?>
			</div></td>
		</tr>
		<?php } else { echo "<tr><td class=\"spacer-bot\">&nbsp;</td></tr>"; } ?>
		</table>
		*/
	}

	/**
	 * Display an album
	 *
	 * @param  int  $id  Defaults to 1, the parent album
	 * @param  string  $name  For SEO only
	 * @return Response
	 */
	public function display( $id = 1, $name = NULL )
	{
		$album = Album::findOrFail($id);

		/*if( isset($_GET['gallery']) ) {
			$sql = "SELECT `id`
				FROM `albums`
				WHERE `folder` = '" . $_db->escape($_GET['gallery']) . "'";
			$exec = $_db->query($sql);
			
			list( $id ) = $exec->fetch_row();
		}*/

		// @todo
		$access = 2;

		if( $album->permission_view > $access ) {
			App::abort(403);
		}

		$_PAGE = array(
			'category' => 'gallery',
			'section'  => 'photos',
			'title'    => $album->name
		);

		// Permissions
		/*if( $me->administrator ) { $allow = true; }
		else if( $album->permission_upload == 0 && $album->user_id == $me->id ) { $allow = true; }
		else if( $album->permission_upload == 1 && $me->loggedin ) { $allow = true; }
		else { $allow = false; }*/

		// Parents and child albums
		/*$sql = "SELECT `albums`.*, `users`.`name`
			FROM `albums`
				JOIN `users`
					ON `albums`.`user_id` = `users`.`id`
			WHERE `parent_id` = {$album->id}
				AND `albums`.`permission_view` <= {$access}
			ORDER BY `albums`.`name` ASC";
		$exec = $_db->query($sql);

		$children = array();
		while( $data = $exec->fetch_assoc() ) {
			$sql = "SELECT COUNT(1)
				FROM `albums`
				WHERE `parent_id` = {$child->id}
					AND `permission_view` <= {$access}";
			$exec2 = $_db->query($sql);
			list( $total_albums ) = $exec2->fetch_row();
			
			$child->total_albums = $total_albums;
			
			if( strlen($child->description) > 80 ) {
				$child->description = substr($child->description, 0, 79) . '...';
			}
			$child->description = BBCode::simplify($child->description);
		}*/

		$photos = $album->photos()
			->paginate(20);

		return View::make('albums.display')
			->with('_PAGE', $_PAGE)
			->with('album', $album)
			->with('photos', $photos);

		/*$Smarty->assign('allow', $allow);*/
	}
	
	/**
	 * Delete album
	 */
	public function delete()
	{
		global $_CONFIG, $_db;
		
		$folder = ROOT . 'web/photos/' . $this->folder;
		
		if( $_CONFIG['aws'] === null ) {
			foreach( glob("$folder/{*.jpg,*.png,*.gif}", GLOB_BRACE) as $photo ) {
				@unlink($photo);
			}
			foreach( glob("$folder/scale/{*.jpg}", GLOB_BRACE) as $photo ) {
				@unlink($photo);
			}
			foreach( glob("$folder/thumbs/{*.jpg}", GLOB_BRACE) as $photo ) {
				@unlink($photo);
			}

			rmdir($folder . '/scale/');
			rmdir($folder . '/thumbs/');
			rmdir($folder . '/');
		}
		else {
			$sql = "SELECT *
				FROM `photos`
				WHERE `album_id` = {$this->id}";
			$exec = $_db->query($sql);

			while( $data = $exec->fetch_assoc() ) {
				$photo = new Photo($data['id'], $data);
				$photo->delete($this->folder, false);
			}
		}

		$sql = "DELETE FROM `photos`
			WHERE `album_id` = {$this->id}";
		$_db->query($sql);
		
		$sql = "DELETE FROM `albums`
			WHERE `id` = {$this->id}";
		$_db->query($sql);
	}
}
