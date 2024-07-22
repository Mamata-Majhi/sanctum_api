<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use function PHPUnit\Framework\fileExists;

class PostControler extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['posts'] = Post::all();

        return response()->json([
            'status'=>true,
            'message'=>'All post data.',
            'data'=>$data,
           
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validateUser=Validator::make(

            $request->all(),
            [
                'name'=>'required',
                'description'=>'required |',
                'image'=>'required|mimes:png,jpg,jpeg,gif',
            ]
        );

            if ($validateUser->fails()) {
                return response()->json([
                    'status'=>false,
                    'message'=>'Validation error',
                    'errors'=>$validateUser->errors()->all()
                ],401);
            }

            $img=$request->image;
            // $ext= $img->getClientOriginalExtention();
            $imageName=time();
            $img->move(public_path().'/uploads',$imageName);

            $post=Post::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'image'=>$imageName,
              
            ]);
            return response()->json([
                'status'=>true,
                'message'=>'Post created successfully',
                'post'=>$post,

            ],200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['post']= Post::select(
            'id',
            'name',
            'description',
            'image'
        )->where(['id'=>$id])->get();

        return response()->json([
            'status'=>true,
            'message'=>'Your single post',
            'data'=>$data,

        ],200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',
            ]
        );
    
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()->all()
            ], 401);
        }
    
        // Fetch the specific post by ID
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found',
            ], 404);
        }
    
        // Handle the image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            $path = public_path('/uploads');
            if ($post->image && file_exists($path . '/' . $post->image)) {
                unlink($path . '/' . $post->image);
            }
    
            // Get the new image and move it to the uploads directory
            $img = $request->file('image');
            // $ext = $img->getClientOriginalExtension();
            $imageName = time();
            $img->move($path, $imageName);
        } else {
            $imageName = $post->image;
        }
    
        // Update the post with new data
        $post->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imageName,
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Post updated successfully',
            'post' => $post,
        ], 200);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagePath=Post::select('image')->where('id',$id)->get();
        $post =Post::where('id',$id)->delete();
        $filePath=public_path().'/uploads/'.$imagePath[0]['image'];
        unlink($filePath);

        return response()->json([
            'status'=>true,
            'message'=>'Your Post has been removed.',
            'post'=>$post,

        ],200);

    }
}
