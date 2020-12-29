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
                $data = end($attr);
                dd((base64_encode($data)));

                $data[$key] = $this->uploadFile(end($attr));
            }else {
                $data[$key] = end($attr);
            }
        }
        // dd($data);
        return $data;
    }

    public function getContentFromReq(Request $request,string $fileName = null): array
    {
        $raw =$request->getContent();
        $delimiteur = "multipart/form-data; boundary=";
        $boundary= "--" . explode($delimiteur,$request->headers->get("content-type"))[1];
        $elements = str_replace([$boundary,'Content-Disposition: form-data;',"name="],"",$raw);
        $elementsTab = explode("\r\n\r\n",$elements);
        $data =[];
        // dd($elementsTab);
        for ($i=0;isset($elementsTab[$i+1]);$i+=2){
            $key = str_replace(["\r\n",' "','"'],'',$elementsTab[$i]);
            // dd($key);
            if (strchr($key,$fileName)){
                $stream =fopen('php://memory','r+');
                fwrite($stream,$elementsTab[$i +1]);
                rewind($stream);
                $data[$fileName] = $stream;
            }else{
                $val=$elementsTab[$i+1];
                $data[$key] = $val;
            }
        }
        return $data;
    }

    private function uploadFile(string $img)
    {
        $file = fopen("php://memory", "r+");
        fwrite($file, $img);
        rewind($file);
    
        return $file;
    }

}