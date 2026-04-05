<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@projectflow.com')->first();
        $alice = User::where('email', 'alice@projectflow.com')->first();
        $bob   = User::where('email', 'bob@projectflow.com')->first();
        $carol = User::where('email', 'carol@projectflow.com')->first();
        $david = User::where('email', 'david@projectflow.com')->first();
        $emma  = User::where('email', 'emma@projectflow.com')->first();

        $projects = [
            [
                'name'        => 'ProjectFlow Platform MVP',
                'description' => 'Core platform development including authentication, project management, task tracking, and team collaboration features.',
                'status'      => 'Active',
                'color'       => '#6366f1',
                'start_date'  => '2024-01-15',
                'end_date'    => '2024-06-30',
                'owner'       => $admin,
            ],
            [
                'name'        => 'Mobile App Redesign',
                'description' => 'Complete redesign of the mobile application with modern UI/UX principles and improved performance.',
                'status'      => 'Active',
                'color'       => '#10b981',
                'start_date'  => '2024-02-01',
                'end_date'    => '2024-05-31',
                'owner'       => $alice,
            ],
            [
                'name'        => 'API Integration Suite',
                'description' => 'Third-party API integrations including Slack, GitHub, Jira, and Stripe payment processing.',
                'status'      => 'OnHold',
                'color'       => '#f59e0b',
                'start_date'  => '2024-03-01',
                'end_date'    => '2024-08-31',
                'owner'       => $bob,
            ],
            [
                'name'        => 'Q1 Marketing Campaign',
                'description' => 'Digital marketing campaign across social media, email, and paid advertising channels.',
                'status'      => 'Completed',
                'color'       => '#ec4899',
                'start_date'  => '2024-01-01',
                'end_date'    => '2024-03-31',
                'owner'       => $carol,
            ],
            [
                'name'        => 'Infrastructure Migration',
                'description' => 'Migration from legacy on-premise servers to AWS cloud infrastructure with zero-downtime deployment.',
                'status'      => 'Active',
                'color'       => '#8b5cf6',
                'start_date'  => '2024-02-15',
                'end_date'    => '2024-07-15',
                'owner'       => $emma,
            ],
        ];

        $allMembers = [$alice, $bob, $carol, $david, $emma];

        foreach ($projects as $data) {
            $owner = $data['owner'];
            unset($data['owner']);

            $project = Project::create(array_merge($data, ['owner_id' => $owner->id]));
            $project->projectMembers()->firstOrCreate(
                ['user_id' => $owner->id],
                ['role' => 'owner']
            );

            $others = array_filter($allMembers, fn($m) => $m->id !== $owner->id);
            foreach (array_slice(array_values($others), 0, 3) as $member) {
                $project->projectMembers()->firstOrCreate(
                    ['user_id' => $member->id],
                    ['role' => 'member']
                );
            }
        }
    }
}