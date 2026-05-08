<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Permissions are owned by Filament Shield and use the
     * "{Action}:{Entity}" naming convention (mis. View:Application).
     * This seeder ensures Shield has generated the permission set,
     * then maps the 5 DPMPTSP roles to subsets of those permissions.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 1) Make sure Shield has generated the panel permissions.
        if (Permission::query()->count() < 50) {
            Artisan::call('shield:generate', ['--all' => true, '--panel' => 'admin']);
        }

        // 2) Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin      = Role::firstOrCreate(['name' => 'admin',       'guard_name' => 'web']);
        $editor     = Role::firstOrCreate(['name' => 'editor',      'guard_name' => 'web']);
        $operator   = Role::firstOrCreate(['name' => 'operator',    'guard_name' => 'web']);
        $viewer     = Role::firstOrCreate(['name' => 'viewer',      'guard_name' => 'web']);

        // 3) super_admin gets EVERYTHING (also bypassed by Gate::before in AppServiceProvider).
        $superAdmin->syncPermissions(Permission::all());

        // 4) admin: everything except ForceDelete on User/Role/Permission.
        $admin->syncPermissions(
            Permission::query()
                ->whereNotIn('name', [
                    'ForceDelete:User', 'ForceDeleteAny:User',
                    'ForceDelete:Role', 'ForceDeleteAny:Role',
                ])->get()
        );

        // 5) editor: full CRUD on content modules; no destructive actions.
        $contentEntities = [
            'HeroSection', 'Post', 'Application', 'ApplicationCategory',
            'Faq', 'Testimonial', 'StatisticCounter', 'FooterLink', 'SeoSetting', 'Menu',
        ];
        $editorActions = ['ViewAny', 'View', 'Create', 'Update', 'Delete', 'Replicate', 'Reorder'];
        $editor->syncPermissions(
            Permission::query()
                ->where(function ($q) use ($editorActions, $contentEntities) {
                    foreach ($contentEntities as $entity) {
                        foreach ($editorActions as $action) {
                            $q->orWhere('name', "{$action}:{$entity}");
                        }
                    }
                })->get()
        );

        // 6) operator: handle complaints + view-only on content.
        $operator->syncPermissions(
            Permission::query()->whereIn('name', [
                'ViewAny:Complaint', 'View:Complaint', 'Update:Complaint',
                'ViewAny:Post', 'View:Post',
                'ViewAny:Application', 'View:Application',
            ])->get()
        );

        // 7) viewer: read-only.
        $viewer->syncPermissions(
            Permission::query()->where(function ($q) {
                $q->where('name', 'like', 'View:%')->orWhere('name', 'like', 'ViewAny:%');
            })->get()
        );
    }
}
