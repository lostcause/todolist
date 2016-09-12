<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskControllerTest extends TestCase
{
	use DatabaseMigrations;

	public function setUp()
	{
		parent::setUp();
		Artisan::call('migrate');
	}

	public function test_controller_returns_all_tasks_for_a_logged_user_when_calling_index_method()
	{
		$user = factory(App\User::class)->create(['password' => bcrypt('admin')]);

		$token = JWTAuth::fromUser($user);

		$this->refreshApplication();

		$this->get($this->baseUrl.'/task', ['HTTP_Authorization' => 'Bearer '.$token])
			 ->seeStatusCode(200);
	}

	public function test_controller_returns_error_for_non_logged_users_when_calling_index_method()
	{
		$this->get($this->baseUrl.'/task')
		    ->seeStatusCode(401)
			->seeJson(
				['message' => 'Failed to authenticate because of bad credentials or an invalid authorization header.']
			);
	}

	public function test_controller_creates_a_task_for_a_logged_in_user()
	{
		$user = factory(App\User::class)->create(['password' => bcrypt('admin')]);

		$token = JWTAuth::fromUser($user);

		$this->refreshApplication();

		$data = ['todo' => 'Do the monkey dance', 'done' => false, 'user_id' => $user->id];

		$this->post($this->baseUrl.'/task/save',
				$data,
				['HTTP_Authorization' => 'Bearer '.$token])
		    ->seeStatusCode(200)
			->seeInDatabase('tasks', $data);
	}

	public function test_controller_returns_error_when_creating_task_for_a_non_logged_user()
	{
		$user = factory(App\User::class)->create(['password' => bcrypt('admin')]);

		$data = ['todo' => 'Do the monkey dance', 'done' => false, 'user_id' => $user->id];

		$this->post($this->baseUrl.'/task/save', $data)
		     ->seeStatusCode(401)
		     ->notSeeInDatabase('tasks', $data);
	}

	public function test_controller_updates_a_task_for_a_logged_in_user()
	{
		$user = factory(App\User::class)->create(['password' => bcrypt('admin')]);

		$token = JWTAuth::fromUser($user);

		$this->refreshApplication();

		$task = factory(App\Task::class)->create(['user_id' => $user->id]);

		$this->seeInDatabase('tasks', ['todo' => $task->todo, 'done' => $task->done]);

		$data = ['todo' => 'Do the monkey dance', 'done' => true];

		$this->patch($this->baseUrl.'/task/'.$task->id.'/update', $data, ['HTTP_Authorization' => 'Bearer '.$token])
		     ->seeStatusCode(200)
		     ->seeInDatabase('tasks', $data);
	}

	public function test_controller_returns_error_when_updating_a_task_for_a_non_logged_in_user()
	{
		$user = factory(App\User::class)->create(['password' => bcrypt('admin')]);
		$task = factory(App\Task::class)->create(['user_id' => $user->id]);

		$this->seeInDatabase('tasks', ['todo' => $task->todo, 'done' => $task->done]);

		$data = ['todo' => 'Do the monkey dance', 'done' => true];

		$this->patch($this->baseUrl.'/task/'.$task->id.'/update', $data)
		     ->seeStatusCode(401)
		     ->notSeeInDatabase('tasks', $data);
	}

	public function test_controller_updates_task_status_for_a_logged_in_user()
	{
		$user = factory(App\User::class)->create(['password' => bcrypt('admin')]);

		$token = JWTAuth::fromUser($user);

		$this->refreshApplication();

		$task = factory(App\Task::class)->create(['user_id' => $user->id, 'done' => false]);

		$data = ['todo' => $task->todo, 'done' => false];

		$this->seeInDatabase('tasks', $data);

		$data['done'] = true;

		$this->patch($this->baseUrl.'/task/'.$task->id.'/status', [], ['HTTP_Authorization' => 'Bearer '.$token])
		     ->seeStatusCode(200)
		     ->seeInDatabase('tasks', $data);
	}

	public function test_controller_returns_error_when_updating_task_status_for_a_non_logged_in_user()
	{
		$user = factory(App\User::class)->create(['password' => bcrypt('admin')]);

		$this->refreshApplication();

		$task = factory(App\Task::class)->create(['user_id' => $user->id, 'done' => false]);

		$data = ['todo' => $task->todo, 'done' => false];

		$this->seeInDatabase('tasks', $data);

		$data['done'] = true;

		$this->patch($this->baseUrl.'/task/'.$task->id.'/status', [])
		     ->seeStatusCode(401)
		     ->notSeeInDatabase('tasks', $data);
	}

	public function test_controller_deletes_a_task_for_a_logged_in_user()
	{
		$user = factory(App\User::class)->create(['password' => bcrypt('admin')]);

		$token = JWTAuth::fromUser($user);

		$this->refreshApplication();

		$task = factory(App\Task::class)->create(['user_id' => $user->id]);

		$data = ['todo' => $task->todo, 'done' => $task->done];

		$this->seeInDatabase('tasks', $data);

		$this->delete($this->baseUrl.'/task/'.$task->id.'/delete', [], ['HTTP_Authorization' => 'Bearer '.$token])
		     ->seeStatusCode(200)
		     ->notSeeInDatabase('tasks', $data);
	}

	public function test_controller_returns_error_when_deleting_a_task_for_a_non_logged_in_user()
	{
		$user = factory(App\User::class)->create(['password' => bcrypt('admin')]);

		$task = factory(App\Task::class)->create(['user_id' => $user->id]);

		$data = ['todo' => $task->todo, 'done' => $task->done];

		$this->seeInDatabase('tasks', $data);

		$this->delete($this->baseUrl.'/task/'.$task->id.'/delete')
		     ->seeStatusCode(401);
	}
}
