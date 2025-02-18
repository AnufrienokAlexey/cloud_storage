<?php

namespace app\Controllers;

class Directory
{
    public function add(): void
    {
        echo "directoriesAdd()";
    }

    public function rename(): void
    {
        echo "directoriesRename()";
    }

    public function getId($id = null): void
    {
        echo "directoriesGetId($id)";
    }

    public function deleteId($id = null): void
    {
        echo "directoriesDeleteId($id)";
    }
}