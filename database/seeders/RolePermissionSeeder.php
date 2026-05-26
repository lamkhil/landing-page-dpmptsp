<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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

        // NB: tidak ada fast-path skip. Setiap kali resource baru ditambah dan
        // `shield:generate` dijalankan, permission baru harus disinkron ulang ke
        // role. syncPermissions bersifat idempotent & cukup cepat (~1s), jadi
        // aman dijalankan berulang. (Sebelumnya ada skip berbasis jumlah perm
        // super_admin — keliru menganggap "selesai" padahal entity baru belum
        // dipetakan ke editor/operator/viewer.)
        $permCount = Permission::query()->count();

        // 1) Make sure Shield has generated the panel permissions.
        // if ($permCount < 50) {
        //     $this->command?->warn('  ↻ shield:generate (scans Filament resources, ~1-2s)');
        //     $t = microtime(true);
        //     Artisan::call('shield:generate', ['--all' => true, '--panel' => 'admin']);
        //     $this->command?->info(sprintf('  ✓ shield:generate done (%.2fs, %d perms)', microtime(true) - $t, Permission::count()));
        // }

        // 2) Roles
        $this->command?->info('  ↻ ensuring 5 roles exist');
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin      = Role::firstOrCreate(['name' => 'admin',       'guard_name' => 'web']);
        $editor     = Role::firstOrCreate(['name' => 'editor',      'guard_name' => 'web']);
        $operator   = Role::firstOrCreate(['name' => 'operator',    'guard_name' => 'web']);
        $viewer     = Role::firstOrCreate(['name' => 'viewer',      'guard_name' => 'web']);

        // 3) super_admin gets EVERYTHING (also bypassed by Gate::before in AppServiceProvider).
        $this->command?->info('  ↻ syncing super_admin (all permissions)');
        $superAdmin->syncPermissions(Permission::all());

        // 4) admin: everything except ForceDelete on User/Role/Permission.
        $this->command?->info('  ↻ syncing admin');
        $admin->syncPermissions(
            Permission::query()
                ->whereNotIn('name', [
                    'ForceDelete:User', 'ForceDeleteAny:User',
                    'ForceDelete:Role', 'ForceDeleteAny:Role',
                ])->get()
        );

        // 5) editor: full CRUD on content modules; no destructive actions.
        $this->command?->info('  ↻ syncing editor');
        $contentEntities = [
            'HeroSection', 'Post', 'News', 'Article', 'Announcement', 'Infographic',
            'Application', 'ApplicationCategory',
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
        $this->command?->info('  ↻ syncing operator');
        $operator->syncPermissions(
            Permission::query()->whereIn('name', [
                'ViewAny:Complaint', 'View:Complaint', 'Update:Complaint',
                'ViewAny:Post', 'View:Post',
                'ViewAny:News', 'View:News',
                'ViewAny:Article', 'View:Article',
                'ViewAny:Announcement', 'View:Announcement',
                'ViewAny:Infographic', 'View:Infographic',
                'ViewAny:Application', 'View:Application',
            ])->get()
        );

        // 7) viewer: read-only.
        $this->command?->info('  ↻ syncing viewer');
        $viewer->syncPermissions(
            Permission::query()->where(function ($q) {
                $q->where('name', 'like', 'View:%')->orWhere('name', 'like', 'ViewAny:%');
            })->get()
        );
    }
}
