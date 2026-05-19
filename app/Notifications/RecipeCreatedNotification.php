<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Illuminate\Contracts\Queue\ShouldQueue;

class RecipeCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $recipeTitle;

    public function __construct($recipeTitle)
    {
        $this->recipeTitle = $recipeTitle;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Recipe Created')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your recipe has been successfully created.')
            ->line('Recipe: ' . $this->recipeTitle)
            ->action('View Recipes', url('/recipes'))
            ->line('Thank you for using our platform!');
    }
}
