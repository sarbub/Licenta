<?php

namespace App\Livewire;
use App\Events\NewPostCreated;
use App\Models\User; // Import User model if not already
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
// Correct Model Name Convention (Singular, PascalCase)
// If your model file is user_posts.php, rename it to UserPost.php
// and the class inside to UserPost
use App\Models\user_posts; // <-- Assuming you rename the model file/class
// use App\Models\user_posts; // <-- Use this if you DON'T rename the model/class
use Illuminate\Support\Facades\Log; // Optional: for logging

class GetUserPostForm extends Component
{
    use WithFileUploads;
    public $content = '';
    // public $imagePath = null; // This public property isn't really used here
    public $image;


    public function rules(): array
    {
        return [
            'content' => 'required|string|min:5|max:5000',
            'image' => 'nullable|image|max:2048|mimes:jpg,jpeg,png'
        ];
    }
    public function messages(): array
    {
        // Your messages are fine
        return [
            'content.required' => 'The post content cannot be empty.',
            'content.min' => 'The post must be at least 5 characters long.',
            'content.max' => 'The post cannot exceed 5000 characters.',
            'content.string' => 'The post content must be a valid text.',
            'image.image' => 'The uploaded file must be an image.',
            'image.max' => 'The image size cannot exceed 2MB.',
            'image.mimes' => 'Only JPG, JPEG, and PNG images are allowed.',
        ];
    }

    public function submit(){
        // Use the defined rules and messages
        $validated = $this->validate();

        $imagePathValue = null; // Initialize local variable for image path

        if($this->image){
            try {
                $userId = Auth::id();
                $imageName = Str::uuid() . '.' . $this->image->getClientOriginalExtension();
                $imagePathValue = $this->image->storeAs("posts/user_{$userId}", $imageName, 'public'); // Changed 'uploads' to 'posts' for consistency

                // Ensure storage link exists: run `php artisan storage:link`
            } catch (\Exception $e) {
                session()->flash('error', 'Could not upload image.');
                Log::error("Image upload failed: " . $e->getMessage());
                return; // Stop if upload fails
            }
        }

        // create the post
        try {
            // Use the correct Model name (UserPost or user_posts)
            $post = user_posts::create([ // <-- Capture the created post instance
                'user_id' => Auth::id(),
                'title'   => 'Untitled Post', // Placeholder - Consider making nullable in migration or adding a title field
                'content' => $validated['content'],
                'image'   => $imagePathValue,
            ]);

            // Reset form and give feedback
            $this->reset(['content', 'image']);
            session()->flash('post_created', 'Post created successfully!');
            $this->dispatch('post-created'); // Notify other Livewire components if needed
            NewPostCreated::dispatch(Auth::user(), $post);

        } catch (\Exception $e) {
             session()->flash('error', 'Failed to create post.');
             Log::error("Post creation failed: " . $e->getMessage());
             // Optionally re-throw or handle differently
        }
    }

    public function removeImage()
    {
        $this->reset('image');
    }


    public function render()
    {
        // Add display for session messages if not already present
        return view('livewire.get-user-post-form');
    }
}
