<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;
use Session;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $posts = Post::where('title', 'LIKE', "%$keyword%")
                ->orWhere('content', 'LIKE', "%$keyword%")
                ->orWhere('category', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $posts = Post::orderBy('created_at', 'desc')->paginate($perPage);
        }

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.posts.create');
    }
    /**
     * Copy of image to local (server) disk
     * create a random image name in numeric format
     * gets data from Illuminate\Support\Facades\Input
     *
     *
     */
    private function copyImage(){
        $newFileName = null;
        if (Input::hasFile('file')) {
            $file = Input::file('file');
            $newFileName = rand(100, 999999) . '.' . $file->getClientOriginalExtension();
            try{
                $file->move('uploads', $newFileName);
            }catch (Exception $e){
                die('cant move file ' + $e->getMessage());
            }
        }

        return $newFileName;
    }

    /**
     * Validation Rules for create / update
     * @param \Illuminate\Http\Request $request
     */
    public function makeValidation(Request $request){
        $this->validate(
            $request,
            [
                'title' => 'required|min:5|max:255',
                'content' => 'required|max:1000',
                'category' => 'required',
                'file' => 'max:2048|image'
            ]
        );
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->makeValidation($request);

        $requestData = $request->all();

        $requestData['image'] = $this->copyImage();

        Post::create($requestData);

        Session::flash('flash_message', 'Post added!');

        return redirect('admin/posts');
    }



    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {

        $this->makeValidation($request);

        $requestData = $request->all();

        $post = Post::findOrFail($id);

        $newFileName = $this->copyImage();

        if($newFileName != null){
            $requestData['image'] = $newFileName;
        }

        $post->update($requestData);

        Session::flash('flash_message', 'Post updated!');

        return redirect('admin/posts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Post::destroy($id);

        Session::flash('flash_message', 'Post deleted!');

        return redirect('admin/posts');
    }
}
