<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;
    public $open = false;

    public $title, $content, $image, $identifier;

    public function mount(){
        $this->identifier = rand();
    }

    protected $rules = [
        'title'     => 'required',
        'content'   =>  'required',
        'image'     => 'required|image|max:2048'
        ];

    // public function updated($propertyName){
    //     $this->validateOnly($propertyName);
    // }

    public function save(){
        $this->validate();

        $image = $this->image->store('posts');


        Post::create([
            'title'     =>  $this->title,
            'content'   =>  $this->content,
            'image'     =>  $image
        ]);

        //Borramos los valores de los inputs
        $this->reset(['open', 'title', 'content', 'image']);

        $this->identifier = rand();

        // Emitimos un evento
        // $this->emit('render');
        $this->emitTo('show-posts', 'render');

        $this->emit('alert', 'El post se creó satisfactoriamente');
    }

    public function render()
    {
        return view('livewire.create-post');
    }

    public function updatingOpen(){
        if($this->open == false){
            $this->reset(['content', 'title', 'image']);
            $this->identifier = rand();
            $this->emit('resetCKEditor');

        }
    }

}
