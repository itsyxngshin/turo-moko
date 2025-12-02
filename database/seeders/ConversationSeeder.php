<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Carbon\Carbon;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        // 1. Get all users
        $users = User::all();

        // Safety check: We need at least 2 users to chat
        if ($users->count() < 2) {
            $this->command->info('Please create at least 2 users before running this seeder.');
            return;
        }

        // 2. Create 10 Random Conversations
        // You can increase the loop count if you want more chats
        for ($i = 0; $i < 10; $i++) {
            
            // Pick User One
            $userOne = $users->random();
            
            // Pick User Two (Ensure it is not User One)
            $userTwo = $users->where('id', '!=', $userOne->id)->random();

            // 3. Check if conversation already exists (to avoid duplicates)
            $conversationExists = Conversation::where(function ($query) use ($userOne, $userTwo) {
                $query->where('user_one_id', $userOne->id)
                      ->where('user_two_id', $userTwo->id);
            })->orWhere(function ($query) use ($userOne, $userTwo) {
                $query->where('user_one_id', $userTwo->id)
                      ->where('user_two_id', $userOne->id);
            })->exists();

            if (!$conversationExists) {
                $conversation = Conversation::create([
                    'user_one_id' => $userOne->id,
                    'user_two_id' => $userTwo->id,
                    'created_at' => Carbon::now()->subDays(rand(1, 30)), // Random start date
                    'updated_at' => Carbon::now(),
                ]);

                $this->command->info("Created conversation between {$userOne->email} and {$userTwo->email}");

                // 4. Generate Messages for this conversation
                $this->generateMessages($conversation, $userOne, $userTwo);
            }
        }
    }

    private function generateMessages($conversation, $userOne, $userTwo)
    {
        $messageCount = rand(5, 20); // Generate 5 to 20 messages per chat
        
        // Start the first message at the time the conversation was created
        $currentTime = Carbon::parse($conversation->created_at);

        for ($j = 0; $j < $messageCount; $j++) {
            // Randomly select who is sending the message
            $sender = rand(0, 1) === 0 ? $userOne : $userTwo;

            // Advance time by 5-60 minutes so messages appear in order
            $currentTime = $currentTime->addMinutes(rand(5, 60));

            // Use 'content' to match your migration schema
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $sender->id,
                'content' => $this->getRandomMessageContent(),
                'is_read' => $j < ($messageCount - 1), // Mark all as read except the very last one
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);
        }
    }

    // Helper to return realistic chat text instead of "Lorem Ipsum"
    private function getRandomMessageContent()
    {
        $messages = [
            "Hey, how are things going?",
            "Did you see the latest update?",
            "I'm working on the new feature right now.",
            "Can we meet later?",
            "That sounds great!",
            "Let me check and get back to you.",
            "Thanks for your help.",
            "No problem at all.",
            "Are you free for a call?",
            "I'll send the files over in a minute.",
            "Haha, that's funny.",
            "Okay, talk to you later.",
            "What do you think about the design?",
            "It looks good to me.",
            "Sorry, I was away from my keyboard."
        ];

        return $messages[array_rand($messages)];
    }
}
