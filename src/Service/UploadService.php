<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

final class UploadService
{
    public function getContentFromRequest(Request $req, string $filename = '')
    {
        $data = [];
        $content = $req->getContent();
        // dd($content);
        $content = preg_split("/form-data; /", $content);
        unset($content[0]);
        foreach($content as $el) {
            $attr = preg_split("/\r\n/", $el);
            array_pop($attr);
            array_pop($attr);
            // dump($attr);
            $key = explode('"', $attr[0]);
            $key = $key[1];
            if($key === $filename) {
                $data[$key] = $this->uploadFile(end($attr));
            }else {
                $data[$key] = end($attr);
            }
        }
        return $data;
    }

    private function uploadFile(string $img) {
        $file = fopen("php://memory", "r+");
        fwrite($file, $img);
        rewind($file);
    
        return $file;
    }
}