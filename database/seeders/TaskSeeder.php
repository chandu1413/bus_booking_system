<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users    = User::all();
        $projects = Project::all();
        $statuses  = ['todo', 'in_progress', 'in_review', 'done'];
        $priorities = ['low', 'medium', 'high', 'urgent'];

        $taskTemplates = [
            ['title' => 'Set up CI/CD pipeline', 'description' => 'Configure GitHub Actions for automated testing and deployment.'],
            ['title' => 'Design database schema', 'description' => 'Create comprehensive ER diagram and finalize table structures.'],
            ['title' => 'Implement user authentication', 'description' => 'Build login, registration, and password reset functionality.'],
            ['title' => 'Create API documentation', 'description' => 'Document all REST API endpoints using Swagger/OpenAPI.'],
            ['title' => 'Write unit tests', 'description' => 'Achieve 80% code coverage with PHPUnit test suite.'],
            ['title' => 'Performance optimization', 'description' => 'Optimize database queries and implement caching strategies.'],
            ['title' => 'Code review process', 'description' => 'Review pull requests and provide constructive feedback.'],
            ['title' => 'Deploy to staging environment', 'description' => 'Configure staging server and deploy latest release for QA testing.'],
        ];

        foreach ($projects as $project) {
            $projectUsers = $project->members()->pluck('users.id')->toArray();
            if (empty($projectUsers)) $projectUsers = [$users->first()->id];
            $creator = $project->owner;

            foreach ($taskTemplates as $i => $template) {
                $assigneeId = $projectUsers[array_rand($projectUsers)];
                $task = Task::create([
                    'project_id'      => $project->id,
                    'assignee_id'     => $assigneeId,
                    'created_by'      => $creator->id,
                    'title'           => $template['title'],
                    'description'     => $template['description'],
                    'status'          => $statuses[array_rand($statuses)],
                    'priority'        => $priorities[array_rand($priorities)],
                    'due_date'        => now()->addDays(rand(-10, 30)),
                    'estimated_hours' => rand(2, 16),
                    'order'           => $i,
                ]);

                // Add subtasks
                $subtitles = ['Research and planning', 'Initial implementation', 'Testing and QA', 'Documentation'];
                foreach ($subtitles as $j => $subtitle) {
                    Subtask::create([
                        'task_id'      => $task->id,
                        'title'        => $subtitle,
                        'is_completed' => (bool)rand(0, 1),
                        'order'        => $j,
                    ]);
                }

                // Add comments
                $commentTexts = [
                    'Looks great, let me know if you need any help!',
                    'I have started working on this. Will update progress soon.',
                    'Found a potential issue with the approach. Let\'s discuss in the next standup.',
                    'Completed the initial implementation, moving to testing phase.',
                ];
                foreach (array_slice($commentTexts, 0, rand(1, 3)) as $commentText) {
                    $commenterId = $projectUsers[array_rand($projectUsers)];
                    Comment::create([
                        'task_id' => $task->id,
                        'user_id' => $commenterId,
                        'body'    => $commentText,
                    ]);
                }
            }
        }
    }
}