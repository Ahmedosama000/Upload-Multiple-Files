<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Requests\PostRequest;
use App\Http\traits\media;
use App\Models\Image;

class PostController extends Controller
{
    use ApiTrait;
    use media;

    public function CreatePost(PostRequest $request){
        
        $data = $request->all();
        $create = Post::create($data);

        $post_id = $create->id;

        foreach ($request->images as $img) {
            $photo_name = $this->uploadPhoto($img,'imgs',uniqid());
            $photo_url[] = asset("imgs/$photo_name");
        }

        for ($init=0; $init < count($photo_url) ; $init++) { 
            
            $img_data = [
                'post_id' => 1,
                'img_name' => $photo_url[$init],
            ];
            Image::create($img_data);
        }
        return $this->SuccessMessage("Post Uploaded Successfully",201);
    }
}
