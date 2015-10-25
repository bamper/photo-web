<?php
/**
 * index
 */
error_reporting(E_ALL);
class Image_uploader
{
    private $db_params = array(
        'db_user' => 'root',
        'db_password' => '100.pudov'
    );

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=portfolio', $this->db_params['db_user'], $this->db_params['db_password']);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function createNewAlbum ($album_name, $album_id)
    {
        if(settype($album_name, 'string') && settype($album_id, 'integer'))
        {
            $stm = $this->db->prepare("INSERT INTO album (album_id, album_name) VALUES (:id, :name)");
            $stm->bindParam(':id', $album_id);
            $stm->bindParam(':name', $album_name);
            if($stm->execute())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public function updateThisAlbum ($album_name, $album_id)
    {
        if($album_name or $album_id)
        {
            if ($album_name)
            {
                $stm = $this->db->prepare("UPDATE album SET album_name=:name WHERE album_id=:id");
                $stm->bindParam(':id', $album_id);
                $stm->bindParam(':name', $album_name);
                if($stm->execute())
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
    }

    public function selectImagesFromAlbum ($album_name = null, $album_id = null)
    {
        if($album_name)
        {
            $stm = $this->db->prepare("SELECT album_id FROM album WHERE album_name=:name");
            $stm->bindParam(':name', $album_name);
            $stm->execute();
            $obj = $stm->fetch();
            $stm = $this->db->prepare("SELECT image, image_name, image_thumb FROM images WHERE album_id=:id");
            $stm->bindParam(':id', $obj['album_id']);
            $stm->execute();
            $row = $stm->fetchAll();
            return $row;
        }
        if($album_id) {
            $stm = $this->db->prepare("SELECT image, image_name, image_thumb FROM images WHERE album_id=:id");
            $stm->bindParam(':id', $album_id);
            $stm->execute();
            $row = $stm->fetchAll();
            return $row;
        }
    }
    public function selectAllImages()
    {
        $stm = $this->db->prepare("SELECT image, image_name, image_thumb FROM images");
        $stm->execute();
        $row = $stm->fetchAll();
        return $row;
    }

    public function insertNewImages ($image = array(), $album_id)
    {
        foreach($image as $key => $value)
        {
            switch($key)
            {
                case 'image':
                    $sql_prepare[':image'] = $value;
                    continue;
                case 'image_name':
                    $sql_prepare[':image_name'] = $value;
                    continue;
                case 'image_thumb':
                    $sql_prepare[':image_thumb'] = $value;
                    continue;
            }

        }
        if($album_id) {
            $stm = $this->db->prepare("INSERT INTO images (image, image_name, image_thumb, album_id) VALUES (:image, :image_name, :image_thumb, :album_id)");
            $stm->bindParam(':album_id', $album_id);
            $stm->execute($sql_prepare);
            return $stm->rowCount();
        }
        else
        {
            return false;
        }
    }

    public function deleteImage ($image=array(), $album_id)
    {
        foreach($image as $key => $value)
        {
            switch($key)
            {
                case 'image':
                    $sql_prepare[':image'] = $value;
                    continue;
                case 'image_name':
                    $sql_prepare[':image_name'] = $value;
                    continue;
                case 'image_thumb':
                    $sql_prepare[':image_thumb'] = $value;
                    continue;
            }

        }
        $stm = $this->db->prepare("DELETE FROM images WHERE image=:image AND image_name=:image_name AND image_thumb=:image_thumb AND album_id=:album_id");
        $stm->bindParam(':album_id', $album_id);
        $stm->execute($sql_prepare);
    }

    public function getRandomImages ($count)
    {
        if(is_numeric($count) && !empty($count)) {
            $stm = $this->db->prepare("SELECT * FROM images ORDER BY RAND() LIMIT :count");
            $stm->bindParam(':count', $count);
            $stm->execute();
            $row = $stm->fetchAll();
            return $row;
        }
    }


}