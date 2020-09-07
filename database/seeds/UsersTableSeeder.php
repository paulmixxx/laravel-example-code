<?php

use App\User;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Paul',
                'email' => 'vp@fugr.ru',
                'role_id' => 1,
                'status_id' => 2,
            ],
            [
                'name' => 'Ivan',
                'email' => 'test@test.com',
                'role_id' => 4,
                'status_id' => 2,
            ],
        ];

        foreach ($users as $user) {
            if (!App\User::where('email', $user['email'])->count()) {
                $role = App\UserRoles::find($user['role_id']);
                $status = App\UserStatuses::find($user['status_id']);
                $model = new App\User();

                $model->code = Uuid::uuid4();
                $model->name = $user['name'];
                $model->email = $user['email'];
                $model->password = bcrypt('123123');
                $model->remember_token = str_random(10);
                $model->role()->associate($role);
                $model->status()->associate($status);

                $model->save();
            }
        }
    }
}
