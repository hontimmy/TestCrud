<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CreateNewsRequest;


use App\Models\News;
use App\Events\NewsCreated;


class NewsController extends Controller
{	
	protected $news;
	
    public function __construct(News $news){
		
        $this->news = $news;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {		
		return response()->json($this->news->paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		 return view('news.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateNewsRequest $request)
    {	
		$data = $request->all();
		$data['user_id'] = $request->user()->id;
        
		// Create News
        $news = $this->news::create($data);   // creates and return news
		
		// Fire News Event
		broadcast(new NewsCreated($news))->toOthers();

        return response()
        ->json([
            'data' =>  $news,
            'message' => 'News created'
        ], 201);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return response()->json($news);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
		return view('news.edit',compact('news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(CreateNewsRequest $request, News $news)
    {
		$data = $request->all();
		
        $news->update($data);
		
		return response()->json($news);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $news->delete();
		
        return response()->json(null,204);
    }
	
	
	
	/**
     * Cronjob deleting news after 14 days
	 *
	 * return job
	 *
	**/
	public function CronJob()
	{
		// Delete record older 14 days
		News::whereDate('created_at', '<=', now()->subDays(14))->delete();		
	}
}
