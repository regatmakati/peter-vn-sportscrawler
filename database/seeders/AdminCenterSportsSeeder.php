<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminCenterSportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert("INSERT INTO `center_sports`.`admin_menu`(`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES (1, 0, 1, '仪表盘', 'fa-bar-chart', '/', NULL, NULL, NULL);");
        DB::insert("INSERT INTO `center_sports`.`admin_menu`(`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES (2, 0, 2, '后台管理', 'fa-tasks', '', NULL, NULL, NULL);");
        DB::insert("INSERT INTO `center_sports`.`admin_menu`(`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES (3, 2, 3, '用户', 'fa-users', 'auth/users', NULL, NULL, NULL);");
        DB::insert("INSERT INTO `center_sports`.`admin_menu`(`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES (4, 2, 4, '角色', 'fa-user', 'auth/roles', NULL, NULL, NULL);");
        DB::insert("INSERT INTO `center_sports`.`admin_menu`(`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES (5, 2, 5, '权限', 'fa-ban', 'auth/permissions', NULL, NULL, NULL);");
        DB::insert("INSERT INTO `center_sports`.`admin_menu`(`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES (6, 2, 6, '菜单', 'fa-bars', 'auth/menu', NULL, NULL, NULL);");
        DB::insert("INSERT INTO `center_sports`.`admin_menu`(`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES (7, 2, 7, '操作日志', 'fa-history', 'auth/logs', NULL, NULL, NULL);");
        DB::insert("INSERT INTO `center_sports`.`admin_menu`(`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES (8, 0, 0, '足球', 'fa-soccer-ball-o', NULL, 'football', '2020-12-07 00:10:11', '2020-12-07 00:10:11');");
        DB::insert("INSERT INTO `center_sports`.`admin_menu`(`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES (9, 8, 0, '比赛列表', 'fa-tv', '/football/matchList', 'football', '2020-12-07 00:13:13', '2020-12-07 00:18:47');");
        DB::insert("INSERT INTO `center_sports`.`admin_menu`(`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES (10, 0, 0, '篮球', 'fa-dribbble', NULL, 'basketball', '2020-12-07 00:14:33', '2020-12-07 00:14:33');");
        DB::insert("INSERT INTO `center_sports`.`admin_menu`(`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES (11, 10, 0, '比赛列表', 'fa-tv', '/basketball/matchList', 'basketball', '2020-12-07 00:15:08', '2020-12-07 00:18:54');");


        DB::insert("INSERT INTO `center_sports`.`admin_permissions`(`id`, `name`, `slug`, `http_method`, `http_path`, `created_at`, `updated_at`) VALUES (1, '所有权限', '*', '', '*', NULL, NULL);");
        DB::insert("INSERT INTO `center_sports`.`admin_permissions`(`id`, `name`, `slug`, `http_method`, `http_path`, `created_at`, `updated_at`) VALUES (2, '仪表盘', 'dashboard', 'GET', '/', NULL, NULL);");
        DB::insert("INSERT INTO `center_sports`.`admin_permissions`(`id`, `name`, `slug`, `http_method`, `http_path`, `created_at`, `updated_at`) VALUES (3, '登录登出', 'auth.login', '', '/auth/login/auth/logout', NULL, NULL);");
        DB::insert("INSERT INTO `center_sports`.`admin_permissions`(`id`, `name`, `slug`, `http_method`, `http_path`, `created_at`, `updated_at`) VALUES (4, '用户信息', 'auth.setting', 'GET,PUT', '/auth/setting', NULL, NULL);");
        DB::insert("INSERT INTO `center_sports`.`admin_permissions`(`id`, `name`, `slug`, `http_method`, `http_path`, `created_at`, `updated_at`) VALUES (5, '后台权限', 'auth.management', '', '/auth/roles/auth/permissions/auth/menu/auth/logs', NULL, NULL);");
        DB::insert("INSERT INTO `center_sports`.`admin_permissions`(`id`, `name`, `slug`, `http_method`, `http_path`, `created_at`, `updated_at`) VALUES (6, '足球', 'football', 'GET,POST', '/football/*', '2020-12-07 00:08:02', '2020-12-07 00:08:02');");
        DB::insert("INSERT INTO `center_sports`.`admin_permissions`(`id`, `name`, `slug`, `http_method`, `http_path`, `created_at`, `updated_at`) VALUES (7, '篮球', 'basketball', 'GET,POST', '/basketball/*', '2020-12-07 00:09:16', '2020-12-07 00:09:16');");


        DB::insert("INSERT INTO `center_sports`.`admin_role_menu`(`role_id`, `menu_id`, `created_at`, `updated_at`) VALUES (1, 2, NULL, NULL);");


        DB::insert("INSERT INTO `center_sports`.`admin_role_permissions`(`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES (1, 1, NULL, NULL);");


        DB::insert("INSERT INTO `center_sports`.`admin_role_users`(`role_id`, `user_id`, `created_at`, `updated_at`) VALUES (1, 1, NULL, NULL);");


        DB::insert("INSERT INTO `center_sports`.`admin_roles`(`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (1, 'Administrator', 'administrator', '2020-12-06 21:58:47', '2020-12-06 21:58:47');");


        DB::insert("INSERT INTO `center_sports`.`admin_users`(`id`, `username`, `password`, `name`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES (1, 'admin', '$2y$10$/HABiFzxn0guwqkMgCM95eiYorW5CmWWhWj//rYgCQK0XA4Q5DLse', 'Administrator', NULL, 'CqGuO6jkLv31XVze6LzrjWGZClj8wRFk851RgpnAXoAX8kVyY3IHckE9RkXa', '2020-12-06 21:58:47', '2020-12-06 21:58:47');");
    }
}
