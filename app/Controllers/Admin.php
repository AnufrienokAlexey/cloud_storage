<?php

namespace app\Controllers;

class Admin
{
    public function list(): void
    {
        echo 'adminList()';
    }

    public function get($id = null): void
    {
        echo "adminGet($id)";
    }

    public function delete($id = null): void
    {
        echo "adminDelete($id)";
    }

    public function update($id = null): void
    {
        echo "adminUpdate($id)";
    }
}