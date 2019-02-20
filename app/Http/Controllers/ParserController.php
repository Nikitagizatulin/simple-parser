<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParserVideo;
use App\Http\Requests\EditParseVideo;
use phpQuery;
use GuzzleHttp\Client;
use App\Models\Parser;
use Intervention\Image\Facades\Image as ImageInt;

class ParserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allVideo = Parser::orderBy('id', 'desc')->paginate(5);
        return view('main',compact('allVideo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreParserVideo $request)
    {
        $host = parse_url($request->search,PHP_URL_HOST);
        if($host === 'rutube.ru') {
            $param['rutube'] = true;
            $param['title'] = 'meta[property="og:title"]';
            $param['iframe'] = 'meta[property="og:video:iframe"]';
            $param['img'] = 'meta[property="og:image:url"]';
            $res = self::getVideo($request->search,$param);
        }
        elseif ($host === 'www.youtube.com' || $host ==='vimeo.com'){
            $param['title'] = 'meta[name="twitter:title"]';
            $param['description'] = $host === 'www.youtube.com' ? 'p#eow-description':'div.clip_details-description';
            $param['iframe'] = 'meta[name="twitter:player"]';
            $param['img'] = 'meta[name="twitter:image"]';
            $res = self::getVideo($request->search,$param);
        }
        else{
            $res['errors'] = 'incorrect link';
        }
        if(array_key_exists('errors',$res)){
            return back()->withErrors($res['errors']);
        }
        else{
            Parser::create($res);
            return  redirect('/');
        }

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
    public function edit($id)
    {
        $video = Parser::find($id);
        return view('edit',compact('video'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditParseVideo $request, $id)
    {
        $video = Parser::find($id);
        if((!empty($request->header)) && $request->header != $video->header){
            $video->header = $request->header;
        }
        if((!empty($request->description)) && $request->description != $video->description){
            $video->description = $request->description;
        }
        if($request->hasFile('img')){
            $imgName = self::saveImage($request->file('img'));
            if($imgName){
                $video->img = $imgName;
            }
        }
        $video->save();
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destr = Parser::destroy($id);
        return  $destr == 1 ? back() : back()->withErrors(['errors'=>'There is no such element']);
    }

    /**
     *  Parsing the video by url and parameters
     * @param string $url
     * @param  array $param
     * @return array
     */
    protected function getVideo($url,$param)
    {
        $cli = new Client();
        $res = $cli->request('GET', $url);
        $str = phpQuery::newDocument($res->getBody()->getContents());
        $arr['header'] = $str->find($param['title'])->attr('content');
        $arr['description'] = array_key_exists('rutube',$param) ? $str->find('meta[property="og:description"]')->attr('content') : $str->find($param['description'])->html();
        $arr['description'] = trim(strip_tags($arr['description']));
        $arr['iframe'] = $str->find($param['iframe'])->attr('content');
        $arr['img']  = $str->find($param['img'])->attr('content');
        if(empty($arr['img']) || empty($arr['iframe']) || empty($arr['header']) || empty($arr['description'])){
            $arr['errors'] = "Data wasn't write. Some element was not found or incorrect link";
            return $arr;
        }else{
            //generate random name for image
            if (!file_exists(public_path("/images"))) {
                mkdir(public_path("/images"), 0777, true);
            }
            $imgName = self::saveImage($arr['img']);
            if(!empty($imgName)){
                $arr['img'] = $imgName;
                return $arr;
            } else{
                $arr['errors'] = "Сould not save the picture.Probable, source of image incorrect";
                return $arr;
            }
        }
    }
    /**
     * Login via email and password
     *
     *
     */
    protected function saveImage($image)
    {
        //create and resize image
        $img = ImageInt::make($image)
            ->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();});
        //check type of image
        if ($img->mime() == 'image/jpeg'){
            $extension = '.jpg';
        } elseif ($img->mime() == 'image/png'){
            $extension = '.png';
        } elseif ($img->mime() == 'image/gif'){
            $extension = '.gif';
        } else{
            $extension = '';
        }
        //generate unique name for image
        $imgName = str_random(20) . $extension;
        //save image
        if($img->save(public_path("/images/$imgName"))){
            return $imgName;
        }else{
            return false;
        }
    }
}
