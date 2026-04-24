<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Feedback;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        // Only create feedback for completed bookings
        $completedBookings = Booking::where('status', 'completed')
            ->with('trip')
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $subjects = [
            5 => ['Excellent service!', 'Very comfortable ride', 'On-time departure', 'Great driver!'],
            4 => ['Good trip overall', 'Comfortable enough', 'Slight delay but okay', 'Clean bus'],
            3 => ['Average experience', 'Bus was okay', 'Nothing special'],
            2 => ['AC was weak', 'Driver was speeding', 'Bus arrived late'],
            1 => ['Very bad experience', 'Bus broke down', 'Rude driver', 'Terrible condition'],
        ];

        $comments = [
            5 => 'Everything was great. The bus was clean, comfortable, and on time. Will definitely book again!',
            4 => 'Good experience overall. Minor delays but nothing too bad. The driver was polite.',
            3 => 'It was okay. Nothing special to note. Bus could have been cleaner.',
            2 => 'The air conditioning was barely working. Not worth the fare.',
            1 => 'Terrible experience. Bus was late by 3 hours and no one informed us. Very disappointing.',
        ];

        foreach ($completedBookings as $booking) {
            // 30% chance of no feedback (realistic)
            if (rand(1, 10) <= 3) {
                continue;
            }

            $rating  = rand(1, 5);
            $subject = collect($subjects[$rating])->random();
            $hasReply = $rating <= 2 && rand(1, 2) === 1; // admin replies to negative feedback

            Feedback::create([
                'user_id'    => $booking->user_id,
                'booking_id' => $booking->id,
                'trip_id'    => $booking->trip_id,
                'rating'     => $rating,
                'subject'    => $subject,
                'comment'    => $comments[$rating],
                'type'       => collect(['trip', 'driver', 'bus', 'general'])->random(),
                'status'     => $hasReply ? 'resolved' : ($rating >= 4 ? 'reviewed' : 'pending'),
                'admin_reply'=> $hasReply
                    ? 'Thank you for bringing this to our attention. We sincerely apologize for the inconvenience and will address this with our team.'
                    : null,
                'replied_at' => $hasReply ? now()->subDays(rand(1, 5)) : null,
            ]);
        }
    }
}