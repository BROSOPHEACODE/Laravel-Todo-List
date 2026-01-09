<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
   
    public function index()
    {
        // ១. ទាញយក Task ទាំងអស់ដែលបង្កើតក្នុង "ថ្ងៃនេះ" (Today)
    // ធ្វើបែបនេះ គឺដើម្បីឱ្យការងារម្សិលមិញបាត់ពីបញ្ជីនៅថ្ងៃថ្មី
    $tasks = \App\Models\Task::whereDate('created_at', \Carbon\Carbon::today())
                 ->orderBy('is_completed', 'asc') // អាមិនទាន់ឆូតនៅលើ អាឆូតហើយនៅក្រោម
                 ->orderBy('created_at', 'desc')
                 ->get();

    // ២. គណនាទិន្នន័យ Report (គណនាពី Task ទាំងអស់តាំងពីថ្ងៃដំបូងមក)
    $totalTasks = \App\Models\Task::count();
    $completedTasks = \App\Models\Task::where('is_completed', true)->count();
    $pendingTasks = \App\Models\Task::where('is_completed', false)->count();
    $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

    // ៣. បោះអថេរទាំងអស់ទៅកាន់ View
    return view('todo', compact('tasks', 'totalTasks', 'completedTasks', 'pendingTasks', 'percentage'));
    }


    public function store(Request $request)
    {
        // Validate ទិន្នន័យដែលផ្ញើមកពី Form
        $request->validate([
            'title'=> 'required|max:255',
        ]);

        Task::create([
            'title' => $request->title,
            'is_completed'=> false
        ]);

        return redirect()->back()->with('success', 'Insert success bro');
    }
    
    public function update(Request $request, string $id)
    {
      $task = Task::findOrFail($id);

        // ឆែកមើលថាតើជាការចុច "ធីក" (Toggle) ឬជាការកែឈ្មោះ (Edit Title)
        if ($request->action == 'toggle_status') {
            $task->update([
                'is_completed' => !$task->is_completed
            ]);

            // ដាក់ត្រង់នេះ៖ ប្តូរពី 'success' ទៅជា 'toast' វិញសម្រាប់តែការចុចធីក
            return redirect()->back()->with('toast', 'Updated status!');
        }

        // សម្រាប់ការកែសម្រួលឈ្មោះ (Edit Title) ទុកឱ្យចេញ Success ធម្មតា (ផ្ទាំងធំ)
        if ($request->action == 'update_title') {
            $task->update([
                'title' => $request->title
            ]);

            return redirect()->back()->with('success', 'Task updated successfully!');
        }
    }

    
    public function destroy(string $id)
    {
        $tasks = Task::findOrFail($id);
        
        $tasks->delete();
        return redirect()->back()->with('success','delete success');
    }

    public function history() {
        
        // ទាញយក Task ទាំងអស់ដែលបង្កើត "មុនថ្ងៃនេះ"
        $historyTasks = \App\Models\Task::whereDate('created_at', '<', \Carbon\Carbon::today())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('history', compact('historyTasks'));
    }
}
