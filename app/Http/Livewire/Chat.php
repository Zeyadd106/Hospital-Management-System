<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Doctor;
use App\Models\Message;
use App\Models\MessageThread;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    public $doctors;
    public $selectedDoctor = null;
    public $messages = [];
    public $messageText = '';
    public $search = '';
    public $showProfile = false;
    
    protected $listeners = ['refreshMessages' => 'getMessages'];
    
    public function mount()
    {
        $this->doctors = Doctor::where('is_available', true)->get();
    }
    
    public function render()
    {
        $filteredDoctors = $this->doctors;
        
        if ($this->search) {
            $filteredDoctors = $this->doctors->filter(function($doctor) {
                return stripos($doctor->name, $this->search) !== false || 
                       stripos($doctor->specialty, $this->search) !== false;
            });
        }
        
        return view('livewire.chat', [
            'filteredDoctors' => $filteredDoctors
        ]);
    }
    
    public function selectDoctor($doctorId)
    {
        $this->selectedDoctor = Doctor::find($doctorId);
        $this->getMessages();
        $this->showProfile = false;
    }
    
    public function getMessages()
    {
        if (!$this->selectedDoctor) {
            return;
        }
        
        $userId = Auth::id() ?? 1;
        
        $thread = MessageThread::firstOrCreate([
            'user_id' => $userId,
            'doctor_id' => $this->selectedDoctor->id
        ], [
            'last_message_at' => now()
        ]);
        
        $this->messages = Message::where('thread_id', $thread->id)
            ->orderBy('created_at', 'asc')
            ->get();
            
        Message::where('thread_id', $thread->id)
            ->where('sender_type', 'doctor')
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
    
    public function sendMessage()
    {
        if (!$this->messageText || !$this->selectedDoctor) {
            return;
        }
        
        $userId = Auth::id() ?? 1;
        
        $thread = MessageThread::firstOrCreate([
            'user_id' => $userId,
            'doctor_id' => $this->selectedDoctor->id
        ], [
            'last_message_at' => now()
        ]);
        
        Message::create([
            'user_id' => $userId,
            'doctor_id' => $this->selectedDoctor->id,
            'thread_id' => $thread->id,
            'sender_type' => 'user',
            'message' => $this->messageText,
            'is_read' => false
        ]);
        
        $this->messageText = '';
        $this->getMessages();
        
        // Simulate doctor response
        $this->simulateDoctorResponse($thread->id);
    }
    
    private function simulateDoctorResponse($threadId)
    {
        // Add a small delay to simulate typing
        sleep(1);
        
        $responses = [
            "Thank you for your message. How can I help you today?",
            "I understand your concern. Could you provide more details?",
            "I'm here to help. What symptoms are you experiencing?",
            "Thank you for reaching out. Let me assist you with that."
        ];
        
        Message::create([
            'user_id' => Auth::id() ?? 1,
            'doctor_id' => $this->selectedDoctor->id,
            'thread_id' => $threadId,
            'sender_type' => 'doctor',
            'message' => $responses[array_rand($responses)],
            'is_read' => false
        ]);
        
        $this->getMessages();
    }
    
    public function toggleProfile()
    {
        $this->showProfile = !$this->showProfile;
    }
}

