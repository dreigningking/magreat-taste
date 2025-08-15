<?php

namespace App\Livewire\DashboardArea\Blog;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Title('Manage Comments')]
class ListComments extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $postFilter = '';
    public $selectedComments = [];
    public $selectAll = false;
    public $showDeleteModal = false;
    public $commentToDelete = null;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search', 'statusFilter', 'postFilter'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPostFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedComments = $this->getComments()->pluck('id')->map(fn($id) => (string) $id);
        } else {
            $this->selectedComments = [];
        }
    }

    public function updatedSelectedComments()
    {
        $this->selectAll = false;
    }

    public function approveComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->approve(Auth::id());
        
        session()->flash('success', 'Comment approved successfully.');
        $this->resetPage();
    }

    public function rejectComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->reject(Auth::id());
        
        session()->flash('success', 'Comment rejected successfully.');
        $this->resetPage();
    }

    public function markAsSpam($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->markAsSpam();
        
        session()->flash('success', 'Comment marked as spam.');
        $this->resetPage();
    }

    public function toggleFeatured($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->toggleFeatured();
        
        $status = $comment->is_featured ? 'featured' : 'unfeatured';
        session()->flash('success', "Comment {$status} successfully.");
        $this->resetPage();
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->delete();
        
        session()->flash('success', 'Comment deleted successfully.');
        $this->resetPage();
    }

    public function confirmDelete($commentId)
    {
        $this->commentToDelete = $commentId;
        $this->showDeleteModal = true;
    }

    public function deleteConfirmed()
    {
        if ($this->commentToDelete) {
            $this->deleteComment($this->commentToDelete);
            $this->commentToDelete = null;
        }
        $this->showDeleteModal = false;
    }

    public function bulkApprove()
    {
        if (empty($this->selectedComments)) {
            session()->flash('error', 'Please select comments to approve.');
            return;
        }

        Comment::whereIn('id', $this->selectedComments)->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        session()->flash('success', count($this->selectedComments) . ' comments approved successfully.');
        $this->selectedComments = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function bulkReject()
    {
        if (empty($this->selectedComments)) {
            session()->flash('error', 'Please select comments to reject.');
            return;
        }

        Comment::whereIn('id', $this->selectedComments)->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        session()->flash('success', count($this->selectedComments) . ' comments rejected successfully.');
        $this->selectedComments = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function bulkDelete()
    {
        if (empty($this->selectedComments)) {
            session()->flash('error', 'Please select comments to delete.');
            return;
        }

        Comment::whereIn('id', $this->selectedComments)->delete();

        session()->flash('success', count($this->selectedComments) . ' comments deleted successfully.');
        $this->selectedComments = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function getComments()
    {
        $query = Comment::with(['post', 'approvedBy'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('content', 'like', '%' . $this->search . '%')
                      ->orWhere('guest_name', 'like', '%' . $this->search . '%')
                      ->orWhere('guest_email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->postFilter, function ($query) {
                $query->where('post_id', $this->postFilter);
            })
            ->orderBy('created_at', 'desc');

        return $query->paginate(15);
    }

    public function getPosts()
    {
        return Post::select('id', 'title')
            ->where('allow_comments', true)
            ->orderBy('title')
            ->get();
    }

    public function getStatusCounts()
    {
        return [
            'total' => Comment::count(),
            'pending' => Comment::where('status', 'pending')->count(),
            'approved' => Comment::where('status', 'approved')->count(),
            'rejected' => Comment::where('status', 'rejected')->count(),
            'spam' => Comment::where('status', 'spam')->count(),
        ];
    }

    public function render()
    {
        $comments = $this->getComments();
        $posts = $this->getPosts();
        $statusCounts = $this->getStatusCounts();

        return view('livewire.dashboard-area.blog.list-comments', compact('comments', 'posts', 'statusCounts'));
    }
}
