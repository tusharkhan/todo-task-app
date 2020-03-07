<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $todos = Todo::paginate();

        return response()->json([
            'todos' => $todos,
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
//        $id = auth()->id();
        //todo:: add user id
        $request['user_id'] = 1;

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->errors());

        $todo = Todo::create($request->all());

        return response()->json([
            'msg' => 'Todo has been created!',
            'todo' => $todo,
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param Todo $todo
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Todo $todo)
    {
        return response()->json([
            'todo' => $todo::with('user')->get()
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Todo $todo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Todo $todo)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'completed' => 'required'
        ]);

        if ($validator->fails()) return response()->json($validator->errors());

        $res = $todo->save($request->all());

        //returning true
        return response()->json([
            'msg' => 'Todo has been updated!',
            'todo' => $todo,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Todo $todo
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Todo $todo)
    {
        if ( $todo->delete() ) return response()->json([
            'msg' => 'Todo deleted'
        ], 200);
    }


    private function get_browser_id(){
        $user_agent = request()->header('User-Agent');
//
        if(preg_match('/MSIE/i',$user_agent) && !preg_match('/Opera/i',$user_agent))
            return $this->get_real_id($user_agent, 'MSIE');
        elseif(preg_match('/Firefox/i',$user_agent))
            return $this->get_real_id($user_agent, 'Firefox');
        elseif(preg_match('/Chrome/i',$user_agent))
            return $this->get_real_id($user_agent, 'Chrome');
        elseif(preg_match('/Safari/i',$user_agent))
            return $this->get_real_id($user_agent, 'Safari');
        elseif(preg_match('/Opera/i',$user_agent))
            return $this->get_real_id($user_agent, 'Opera');
        elseif(preg_match('/Netscape/i',$user_agent))
            return $this->get_real_id($user_agent, 'Netscape');
    }


    private function get_real_id($string, $delete){
        return ltrim(ltrim(strstr(ltrim(strstr($string, $delete . '/'), '/'), $delete), $delete), '/');
    }
}
