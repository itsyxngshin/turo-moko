<?php

namespace App\Livewire\Learner;

use Livewire\Component;

class Assessment extends Component
{
    public $questions = [];
    public $currentQuestion = 0;
    public $answers = [];

    public function mount()
    {
        // Add sample questions here
        $this->questions = [
            [
                'text' => 'What is the main purpose of encryption in information security?',
                'type' => 'radio',
                'options' => [
                    'a' => 'To compress data',
                    'b' => 'To make data unreadable to unauthorized users',
                    'c' => 'To remove data redundancy',
                    'd' => 'To back up data automatically'
                ],
            ],
            [
                'text' => 'Which cipher involves shifting each letter by a fixed number of positions in the alphabet?',
                'type' => 'radio',
                'options' => [
                    'a' => 'Vigenère Cipher',
                    'b' => 'Caesar Cipher',
                    'c' => 'Playfair Cipher',
                    'd' => 'Hill Cipher'
                ],
            ],
            [
                'text' => 'Explain how symmetric encryption differs from asymmetric encryption.',
                'type' => 'text',
            ],
        ];
    }

    public function nextQuestion()
    {
        if ($this->currentQuestion < count($this->questions) - 1) {
            $this->currentQuestion++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestion > 0) {
            $this->currentQuestion--;
        }
    }

    public function submitAssessment()
    {
        // For now, just dump answers (later can save to DB)
        session()->flash('message', 'Assessment submitted successfully!');
    }

    public function render()
    {
        return view('livewire.learner.assessment');
    }
}
