<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemNotificationTest extends TestCase
{
    public function test_it_creates_and_fetches_notification()
    {
        // 1. Create a user (or use first)
        $user = User::factory()->create();

        // 2. Create a notification
        $user->notify(new SystemNotification('Test Manual Notification', 'This is checking if it works correctly.', 'success'));

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'notifiable_type' => get_class($user)
        ]);

        $notification = $user->notifications()->first();

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
        ]);

        // 3. Act as user and fetch notifications via API
        $response = $this->actingAs($user)->getJson('/api/notifications');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'Test Manual Notification',
            'type' => 'success'
        ]);

        // 4. Mark as read
        $readResponse = $this->actingAs($user)->postJson('/api/notifications/read', [
            'id' => $notification->id
        ]);

        $readResponse->assertStatus(200);

        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id,
            'read_at' => null
        ]);
    }
}
