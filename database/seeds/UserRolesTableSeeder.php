<?php

use App\User;
use App\UserRoles;
use Illuminate\Database\Seeder;


class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Админ',
                'code' => 'admin',
            ],
            [
                'name' => 'Редактор',
                'code' => 'editor',
            ],
            [
                'name' => 'Модератор',
                'code' => 'moderator',
            ],
            [
                'name' => 'Пользователь',
                'code' => 'user',
            ],
        ];

        foreach ($roles as $role) {
            if (!UserRoles::where('code', $role['code'])->count()) {
                $model = new App\UserRoles();
                $model->name = $role['name'];
                $model->code = $role['code'];

                $model->save();
            }
        }
    }
}
