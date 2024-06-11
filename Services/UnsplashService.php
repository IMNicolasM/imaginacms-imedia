<?php

namespace Modules\Media\Services;

class UnsplashService
{

    private $log = "Media: UnsplashService|";
    private $mimetypes = array(
        'bmp'     => 'image/bmp',
        'ico'     => 'image/x-icon',
        'jpe'     => 'image/jpeg',
        'jpeg'    => 'image/jpeg',
        'jpg'     => 'image/jpeg',
        'png'     => 'image/png',
        'svg'     => 'image/svg+xml',
        'svgz'    => 'image/svg+xml',
        'tif'     => 'image/tiff',
        'tiff'    => 'image/tiff'
    );

    /**
     * Get data from url (needed to save to the database later)
     */
    public function getDataFromUrl(string $url,string $disk = null)
    {

        $parts = parse_url($url);
        $data['fileName'] = substr($parts['path'], 1);
  
        parse_str($parts['query'], $query);
        
        //Extension
        if(isset($query['fm'])){
          $data['extension'] = $query['fm'];

          if (isset($this->mimetypes[$data['extension']]))
            $data['mimetype'] = $this->mimetypes[$data['extension']];

        }

        //\Log::info("First Url: ".$url);
        
        //Clean Params
        unset($query['ixid']);
        unset($query['ixlib']);
        $newParams = http_build_query($query);

        //New Path (size restriction per DB)
        $newPath = $parts['scheme']."://".$parts['host'].$parts['path']."?".$newParams;
        $data['path'] = $newPath;

        return $data;

    }
    
    /**
     * @param $name (Thumbnail Name)
     * https://unsplash.com/documentation#supported-parameters
     */
    public function getThumbnail($file,string $name)
    {
        
        //\Log::info($this->log."getThumbnail|".$name);

        $url = $file->path->getRelativeUrl();
        $url = strtok($url, '?');

        //Get configurations to thumbnail
        $config = json_decode(config("asgard.media.config.defaultThumbnails")); 
       
        $q = $config->{$name}->quality;
        $w = $config->{$name}->width;
        $fm = $config->{$name}->format;

        //Set params in final url
        $thumbnail = $url."?q={$q}&fm={$fm}&w={$w}&fit=max";

        return $thumbnail;

    }

}