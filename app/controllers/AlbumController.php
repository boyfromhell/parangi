<?php

class AlbumController extends Earlybird\FoundryController
{
	
	public function get_cover()
	{
		global $_db;
		
		try {
			if( $this->cover === null ) {
				throw new Exception('No cover set');
			}
			// @todo why doesn't this throw an exception when cover is null??
			$photo = new Photo($this->cover);
			$thumbnail = substr($photo->file, 0, -4) . '.jpg';
			return "/photos/{$this->folder}/thumbs/{$thumbnail}";
		}
		catch( Exception $e ) {
			$sql = "SELECT `albums`.`id`, `albums`.`cover`, `albums`.`folder`, `photos`.`file`
				FROM `albums`
					LEFT JOIN `photos`
						ON `albums`.`cover` = `photos`.`id`
				WHERE `albums`.`parent_id` = {$this->id}
				ORDER BY `albums`.`name` ASC";
			$exec = $_db->query($sql);
		
			while( $data = $exec->fetch_assoc() ) {
				$child = new Album($data['id'], $data);
			
				if( !$data['cover'] ) {
					$cover = $child->get_cover();
					if( $cover != '/photos/empty.png' ) {
						return $cover;
					}
				}
				else {
					$thumbnail = substr($data['file'], 0, -4) . '.jpg';
					return "/photos/{$child->folder}/thumbs/{$thumbnail}";
				}
			}
		}

		return "/photos/empty.png";
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