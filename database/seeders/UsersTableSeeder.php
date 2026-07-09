<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \Schema::disableForeignKeyConstraints();

        \DB::table('users')->delete();
        
        \DB::table('users')->insertOrIgnore(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Rafael Sir',
                'email' => 'a@b.com',
                'phone' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$tIy3hJeOfB0MKW7kMT8AK.rNlj58rQ1D45hJRYvWGYAbnUxJTkFxm',
                'image' => 'images/profile/e763da25-ae33-46fd-beb7-c3967f3ffdce.jpg',
                'remember_token' => '16nlMfwSWrYSYR2xQkAdEISIkZympHNMb2xtALJz9X810a8EE1gvQEzjjbIF',
                'deleted_at' => NULL,
                'created_by' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-05-03 17:02:38',
            ),
            1 => 
            array (
                'id' => 12,
                'name' => 'Rasel',
                'email' => 'rasel@gmail.com',
                'phone' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$jPhbFrWb/5vS2w7VaRimbufobHrquaard95Ah.dzUlKZk5LEg4f.2',
                'image' => 'images/profile/f45b53f8-4908-4a90-8a18-6bcf91405461.png',
                'remember_token' => 'Cg3fFbWDPy3UoeD3dJsNKAtL2pZCMW1PWIh72XjexVdDyA8JzU8Fq4FXKi1I',
                'deleted_at' => NULL,
                'created_by' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-15 11:05:29',
                'updated_at' => '2025-11-15 11:34:29',
            ),
            2 => 
            array (
                'id' => 13,
                'name' => 'Sagor',
                'email' => 'sagor@gmail.com',
                'phone' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$fKTM5kczAr1j5j2ESz4iG.erSSsqmQVNrbkZqgpLK9QXIofOG9XkO',
                'image' => NULL,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_by' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-15 11:13:05',
                'updated_at' => '2025-11-15 11:13:05',
            ),
            3 => 
            array (
                'id' => 14,
                'name' => 'Md Abdullah',
                'email' => '0abdullahalmasud150@gmail.com',
                'phone' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$dPQaA.Ya2K.F0aRSkeTiYuXbS76UzAYYifOlgqXykr6BUZySYanry',
                'image' => NULL,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_by' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-29 12:02:11',
                'updated_at' => '2025-11-29 12:02:11',
            ),
            4 => 
            array (
                'id' => 16,
                'name' => 'Mahfujur Rahman Antor',
                'email' => 'mahfujurr751@gmail.com',
                'phone' => '01926271512',
                'email_verified_at' => NULL,
                'password' => '$2y$12$KY7HwIF29oOT8u9UCt8L1.ecwjAU0kpBHGqwK8IYo5twOQtpOnScm',
                'image' => 'images/profile/7e91ef50-869f-4c8b-ab03-c99b8e9deb20.jpeg',
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_by' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-03-04 14:48:43',
                'updated_at' => '2026-03-08 12:07:11',
            ),
            5 => 
            array (
                'id' => 17,
                'name' => 'Sagor Avijatry',
                'email' => 'sagor@avijatry.com',
                'phone' => '01',
                'email_verified_at' => NULL,
                'password' => '$2y$12$5TMDYhew9j2XGm3VuRA6vO382RnZF8Pxh46NcNv/M63CukTHmNphS',
                'image' => 'images/profile/188d4cc5-aa0f-42fd-bc0c-7147493161d7.jpg',
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_by' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-04-22 22:00:34',
                'updated_at' => '2026-04-23 12:50:25',
            ),
        ));
        
        \Schema::enableForeignKeyConstraints();
    }
}
