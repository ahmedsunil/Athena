<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

#[Signature('app:chat-command {prompt? : The prompt to send to Gemini}')]
#[Description('Send a prompt to Gemini and print the response')]
class ChatCommand extends Command
{
    /**
     * Execute the console command.
     *
     * @throws ConnectionException
     */
    public function handle(): int
    {
        $apiKey = config('services.gemini.key');

        if (blank($apiKey)) {
            $this->error('Missing GEMINI_API_KEY.');

            return self::FAILURE;
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->post('https://generativelanguage.googleapis.com/v1beta/openai/chat/completions', [
                'model' => config('services.gemini.model', 'gemini-2.5-flash'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful programming assistant. only answer question that is related to programming only',
                    ],
                    ['role' => 'user', 'content' => $this->argument('prompt') ?: 'How are you today?'],
                ],
            ])->throw()->json();

        $this->line(data_get($response, 'choices.0.message.content', 'No response content returned.'));

        return self::SUCCESS;
    }
}
