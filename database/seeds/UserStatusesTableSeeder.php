<?php

use App\UserStatuses;
use Illuminate\Database\Seeder;

class UserStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Не подтвержден',
                'code' => 'unconfirmed',
            ],
            [
                'name' => 'Активен',
                'code' => 'active',
            ],
            [
                'name' => 'Деактивирован',
                'code' => 'inactive',
            ],
        ];

        foreach ($data as $value) {
            if (!UserStatuses::where('code', $value['code'])->count()) {
                $model = new App\UserStatuses();
                $model->name = $value['name'];
                $model->code = $value['code'];

                $model->save();
            }
        }
    }
}
