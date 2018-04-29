<?php

namespace App\Http\Controllers;

use App\Media;
use App\Post;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('app.pages');
        return view('admin.pages', compact('title'));
    }

    public function pagesData(){
        $pages = Post::select('id', 'title', 'slug', 'created_at')->whereType('page')->orderBy('id', 'desc')->get();

        return  Datatables::of($pages)
            ->editColumn('created_at',function($page){
                return $page->created_at_datetime();
            })
            ->addColumn('actions', function($page){

                $button = '';
                $button .= '<a href="'.route('edit_page',$page->slug).'" class="btn btn-primary"><i class="fa fa-edit"></i> </a><a href="javascript:;" class="btn btn-danger deletePage" data-slug="'.$page->slug.'"><i class="fa fa-trash"></i> </a>';
                return $button;
            })
            ->removeColumn('id', 'slug')
            ->make();
    }


    public function posts()
    {
        $title = trans('app.posts');
        return view('admin.posts', compact('title'));
    }

    public function postsData(){
        $posts = Post::select('id', 'title', 'slug', 'created_at')->whereType('post')->orderBy('id', 'desc')->get();

        return  Datatables::of($posts)
            ->editColumn('created_at',function($post){
                return $post->created_at_datetime();
            })
            ->addColumn('actions', function($post){

                $button = '';
                $button .= '<a href="'.route('edit_post',$post->slug).'" class="btn btn-primary"><i class="fa fa-edit"></i> </a><a href="javascript:;" class="btn btn-danger deletePage" data-slug="'.$post->slug.'"><i class="fa fa-trash"></i> </a>';
                return $button;
            })
            ->removeColumn('id', 'slug')
            ->make();
    }

    public function createPost()
    {
        $user_id = Auth::user()->id;
        $title = trans('app.create_new_post');
        $ads_images = Media::whereUserId($user_id)->wherePostId(0)->whereRef('blog')->get();

        return view('admin.post_create', compact('title', 'ads_images'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePost(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = Auth::user();
        $rules = [
            'title'     => 'required',
            'post_content'   => 'required',
        ];
        $this->validate($request, $rules);

        $slug = unique_slug($request->title, 'Post');
        $data = [
            'user_id'               => $user->id,
            'title'                 => $request->title,
            'slug'                  => $slug,
            'post_content'          => $request->post_content,
            'type'                  => 'post',
            'status'                => '1',
        ];

        $post_created = Post::create($data);

        if ($post_created){
            Media::whereUserId($user_id)->wherePostId(0)->whereRef('blog')->update(['post_id'=>$post_created->id]);

            return redirect(route('posts'))->with('success', trans('app.post_has_been_created'));
        }
        return redirect()->back()->with('error', trans('app.error_msg'));
    }


    public function editPost($slug)
    {
        $user_id = Auth::user()->id;
        $title = trans('app.edit_post');
        $post = Post::whereSlug($slug)->first();

        return view('admin.edit_post', compact('title', 'post'));
    }

    public function updatePost(Request $request, $slug){
        $user_id = Auth::user()->id;
        $rules = [
            'title'     => 'required',
            'post_content'   => 'required',
        ];
        $this->validate($request, $rules);
        $page = Post::whereSlug($slug)->first();

        $data = [
            'title'                 => $request->title,
            'post_content'          => $request->post_content
        ];

        $post_update = $page->update($data);
        if ($post_update){
            if (! $page->feature_img){
                Media::whereUserId($user_id)->wherePostId(0)->whereRef('blog')->update(['post_id'=>$page->id]);
            }

            return redirect()->back()->with('success', trans('app.post_has_been_updated'));
        }
        return redirect()->back()->with('error', trans('app.error_msg'));
    }

    public function uploadPostImage(Request $request){
        $user_id = Auth::user()->id;
        $post_id = $request->post_id ? $request->post_id : 0 ;

        //Check is this post belongs with any image
        $attachedPostMediaCount = Media::whereUserId($user_id)->wherePostId($post_id)->whereRef('blog')->count();
        if ($attachedPostMediaCount > 0){
            return ['success' => 0, 'msg' => trans('app.max_image_uploaded_msg')];
        }else{
            $postMediaCount = Media::whereUserId($user_id)->wherePostId(0)->whereRef('blog')->count();
            if ($postMediaCount > 0){
                return ['success' => 0, 'msg'=> trans('app.max_image_uploaded_msg')];
            }
        }

        if ($request->hasFile('images')){
            $image = $request->file('images');
            $valid_extensions = ['jpg','jpeg','png'];

            if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
                return ['success' => 0, 'msg' => implode(',', $valid_extensions).' '.trans('app.valid_extension_msg')];
            }

            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());

            $resized = Image::make($image)->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();
            $resized_thumb = Image::make($image)->resize(320, 213)->stream();

            $image_name = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

            $imageFileName = 'uploads/images/'.$image_name;
            $imageThumbName = 'uploads/images/thumbs/'.$image_name;

            //Upload original image
            $is_uploaded = current_disk()->put($imageFileName, $resized->__toString(), 'public');

            if ($is_uploaded) {
                //Save image name into db
                $created_img_db = Media::create(['user_id' => $user_id, 'media_name'=>$image_name, 'type'=>'image','is_feature'=>'1', 'storage' => get_option('default_storage'), 'ref'=>'blog']);

                //upload thumb image
                current_disk()->put($imageThumbName, $resized_thumb->__toString(), 'public');
                $img_url = media_url($created_img_db, false);
                return ['success' => 1, 'img_url' => $img_url];
            } else {
                return ['success' => 0];
            }
        }
    }


    public function appendPostMediaImage(){
        $user_id = Auth::user()->id;
        $ads_images = Media::whereUserId($user_id)->wherePostId(0)->whereRef('blog')->get();

        return view('admin.append_media', compact('ads_images'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('app.pages');
        return view('admin.page_create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'title'     => 'required',
            'post_content'   => 'required',
        ];
        $this->validate($request, $rules);

        $show_in_header_menu = $request->show_in_header_menu ? 1:0;
        $show_in_footer_menu = $request->show_in_footer_menu ? 1:0;

        $slug = unique_slug($request->title, 'Post');
        $data = [
            'user_id'               => $user->id,
            'title'                 => $request->title,
            'slug'                  => $slug,
            'post_content'          => $request->post_content,
            'type'                  => 'page',
            'status'                => 1,
            'show_in_header_menu'   => $show_in_header_menu,
            'show_in_footer_menu'   => $show_in_footer_menu,
        ];

        $post_created = Post::create($data);

        if ($post_created){
            return redirect(route('pages'))->with('success', trans('app.page_has_been_created'));
        }
        return redirect()->back()->with('error', trans('app.error_msg'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $title = trans('app.edit_page');
        $page = Post::whereSlug($slug)->first();
        return view('admin.edit_page', compact('title', 'page'));
    }

    public function updatePage(Request $request, $slug){
        $rules = [
            'title'     => 'required',
            'post_content'   => 'required',
        ];
        $this->validate($request, $rules);
        $page = Post::whereSlug($slug)->first();

        $show_in_header_menu = $request->show_in_header_menu ? 1:0;
        $show_in_footer_menu = $request->show_in_footer_menu ? 1:0;

        $data = [
            'title'                 => $request->title,
            'post_content'          => $request->post_content,
            'status'                => 1,
            'show_in_header_menu'   => $show_in_header_menu,
            'show_in_footer_menu'   => $show_in_footer_menu,
        ];

        $post_update = $page->update($data);
        if ($post_update){
            return redirect()->back()->with('success', trans('app.page_has_been_updated'));
        }
        return redirect()->back()->with('error', trans('app.error_msg'));
    }
    
    public function showPage($slug){
        $page = Post::whereSlug($slug)->first();

        if (! $page){
            return view('theme.error_404');
        }
        $title = $page->title;
        return view('theme.single_page', compact('title', 'page'));
    }


    public function blogIndex(){
        $posts = Post::whereType('post')->whereStatus('1')->paginate(20);
        $title = trans('app.blog');
        return view('theme.blog', compact('title', 'posts'));
    }

    public function blogSingle($slug){
        $post = Post::whereSlug($slug)->first();
        $title = $post->title;
        
        $enable_discuss = get_option('enable_disqus_comment_in_blog');
        return view('theme.blog_single', compact('title', 'post', 'enable_discuss'));
    }

    public function authorPosts($id){
        $posts = Post::whereType('post')->whereUserId($id)->whereStatus(1)->paginate(20);
        $user = User::find($id);
        $title = $user->name."'s ".trans('app.blog');
        return view('theme.blog', compact('title', 'posts'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $slug = $request->slug;
        $page = Post::whereSlug($slug)->first();
        if ($page){
            $page->delete();
            return ['success' => 1, 'msg' => trans('app.operation_success')];
        }
        return ['success' => 0, 'msg' => trans('app.error_msg')];
    }
}
