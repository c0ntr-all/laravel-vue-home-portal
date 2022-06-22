<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FolderService
{
    private function showDisks()
    {
        if(php_uname('s')=='Windows NT'){
            // windows
            $disks = exec('fsutil fsinfo drives');
            $disks = explode(' ', $disks);
            unset($disks[0]);

            foreach($disks as $key => $disk) {
                $disks[$key] = $disk;
            }

            return $disks;
        }else{
            // unix
            $data = exec('mount');
            $data = explode(' ', $data);

            $disks = [];
            foreach($data as $token) {
                if(substr($token,0,5) == '/dev/') {
                    $disks[] = $token;
                }
            }

            return $disks;
        }
    }

    public function getFolder($folder)
    {
        if(!empty($folder)) {
            $dirElements = scandir($folder);
            $dirs = [];
            foreach($dirElements as $dirItem) {
                if($dirItem != '..' && $dirItem != '.' && is_dir($folder . '/' . $dirItem)) {
                    $dirs[] = $dirItem;
                }
            }

            return $dirs;
        }else{
            return $this->showDisks();
        }
    }
}
