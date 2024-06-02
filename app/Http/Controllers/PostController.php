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
            $photo_name = $this->uploadPhoto($img,'imgs',$post_id);
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

    public function UpdatePost(PostRequest $request ,$id){

        if ($request->images){
            
            $data = $request->all();
            $update = Post::find($id)->Update($data);

            foreach ($request->images as $img) {
                $photo_name = $this->uploadPhoto($img,'imgs',$id);
                $photo_url[] = asset("imgs/$photo_name");
            }
    
            for ($init=0; $init < count($photo_url) ; $init++) { 
                
                $img_data = [
                    'post_id' => 1,
                    'img_name' => $photo_url[$init],
                ];
                Image::where('post_id',$id);
            }
            
            return $this->SuccessMessage("Post Updated Successfully",200);
        }
        else {
            $data = $request->all();
            $update = Post::find($id)->Update($data);
            return $this->SuccessMessage("Post Updated Successfully",200);
        }
    }
    public function DeletePost($id){

        Post::find($id)->delete();
        Image::where('post_id',$id)->delete();
        return $this->SuccessMessage("Post Deleted Successfully",200);

    }
}
