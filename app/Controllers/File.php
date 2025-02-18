<?php

namespace app\Controllers;

class File
{
    public function list(): void
    {
        echo 'filesList()';
    }

    public function getId($id = null): void
    {
        echo "filesGetId($id)";
    }

    public function add(): void
    {
        echo "filesAdd()";
    }

    public function rename(): void
    {
        echo "filesRename()";
    }

    public function removeId($id = null): void
    {
        echo "filesRemoveId($id)";
    }

    public function shareId($id = null): void
    {
        echo "filesShareId($id)";
    }

    public function shareIdUserId($id = null, $user_id = null): void
    {
        echo "filesShareId($id, $user_id)";
    }

    public function deleteIdUserId($id = null, $user_id = null): void
    {
        echo "filesDeleteIdUserId($id, $user_id)";
    }

}