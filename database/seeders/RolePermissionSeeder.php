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

        // Fast path: if Shield permissions already exist AND super_admin is
        // already mapped to (roughly) all of them, the role↔permission graph
        // is already in the desired state. Skip the (re-)sync work — it's
        // the slowest step of the seeder (5 roles × ~150 perms = ~750 pivot
        // writes, each a separate query in syncPermissions).
        $permCount = Permission::query()->count();
        if ($permCount >= 50 && Role::where('name', 'super_admin')->first()?->permissions()->count() >= $permCount - 5) {
            $this->command?->info("  ✓ permissions already configured (skip) — {$permCount} perms");
            return;
        }

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
        $this->command?->info('  ↻ syncing operator');
        $operator->syncPermissions(
            Permission::query()->whereIn('name', [
                'ViewAny:Complaint', 'View:Complaint', 'Update:Complaint',
                'ViewAny:Post', 'View:Post',
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
