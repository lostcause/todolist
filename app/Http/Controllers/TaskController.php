<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

use App\Http\Requests;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskController extends Controller
{

	public function index()
	{
		return response()->json(JWTAuth::parseToken()->toUser()->tasks()->get());
	}

	public function save(Request $request)
	{
		$this->validate($request, ['todo' => 'required']);

		$task = new Task;
		$task->todo = $request->get('todo');
		$task->done = (bool)$request->get('done');

		JWTAuth::parseToken()->toUser()->tasks()->save($task);

		return response()->json(JWTAuth::parseToken()->toUser()->tasks()->get());
    }

	public function update(Request $request, $id)
	{
		$this->validate($request, ['todo' => 'required']);

		$task = JWTAuth::parseToken()->toUser()->tasks()->find($id);

		$task->todo = $request->get('todo');
		$task->done = $request->get('done');

		$task->save();

		return response()->json(JWTAuth::parseToken()->toUser()->tasks()->get());
    }

	public function status($id)
	{
		$task = JWTAuth::parseToken()->toUser()->tasks()->find($id);
		$task->done = $task->done ^ 1;
		$task->save();

		return response()->json(JWTAuth::parseToken()->toUser()->tasks()->get());
    }

	public function delete($id)
	{
		$task = Task::find($id);
		$task->destroy($id);

		return response()->json(JWTAuth::parseToken()->toUser()->tasks()->get());
    }
}
