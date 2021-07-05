<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

final class UploadService
{
    public function getContentFromRequest(Request $req, string $filename = '')
    {
        $data = [];
        $content = $req->getContent();
        $content = preg_split("/form-data; /", $content);
        unset($content[0]);
        // dd($content);
        foreach($content as $el) {
            $attr = preg_split("/\r\n/", $el);
            array_pop($attr);
            array_pop($attr);
            // dump($attr);
            $key = explode('"', $attr[0]);
            $key = $key[1];
            if(strchr($key,$filename)) {
                $attr = preg_split("/\r\n\r\n/", $el);
                // $data = end($attr);
                // dd($attr);
                // dd((base64_encode($data)));
                // dd($attr);
                $data[$key] = $this->uploadFile($attr[sizeof($attr)-1].end($attr));
                // dd($data);
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
        // $elementsTab = [];
        // $elements = preg_split("/form-data; /", $raw);
        $data =[];
        // dd($elements);
        for ($i=0;isset($elementsTab[$i+1]);$i+=2){
            $key = str_replace(["\r\n",' "','"'],'',$elementsTab[$i]);
            if (strchr($key,$fileName)){
                $stream =fopen('php://memory','r+');
                dd($elementsTab[$i+1]);
                fwrite($stream,$elementsTab[$i +1]);
                rewind($stream);
                $data[$fileName] = $stream;
            }else{
                $val=$elementsTab[$i+1];
                // dump($val);
                $data[$key] = $val;
            }
        }
        // dd($data);
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