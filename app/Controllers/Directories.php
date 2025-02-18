<?php

namespace app\Controllers;

class Directories
{
    public function add(): void
    {
        echo "directoriesAdd()";
    }

    public function rename(): void
    {
        echo "directoriesRename()";
    }

    public function get($id = null): void
    {
        echo "directoriesGet($id)";
    }

    public function delete($id = null): void
    {
        echo "directoriesDelete($id)";
    }
}