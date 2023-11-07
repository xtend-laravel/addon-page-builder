<?php

namespace XtendLunar\Addons\PageBuilder\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use XtendLunar\Addons\PageBuilder\Models\FormSubmission;

class FormSubmissionAdminNotification extends Notification
{
    use Queueable;

    protected Collection $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected FormSubmission $formSubmission)
    {
        $formName = Str::of($this->formSubmission->form->name)->headline()->value();

        $this->message = collect([
            __('Form: :form', ['form' => $formName]),
            __('Name: :name', ['name' => $this->formSubmission->payload['name']]),
            __('Email: :email', ['email' => $this->formSubmission->payload['email']]),
            __('Subject: :subject', ['subject' => $this->formSubmission->payload['subject']]),
            __('Message: :message', ['message' => $this->formSubmission->payload['message']]),
            __('Submitted at: :date', ['date' => $this->formSubmission->created_at->format('m/d/Y h:i A')]),
        ]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->lines($this->message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
